<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DosenBimbinganController extends Controller
{
    public function index()
    {
        if (!session('user')) return redirect('/login');

        $dosen    = session('user');
        $nidDosen = $dosen->nim_nid;

        $proposalIds = DB::table('dosen_pembimbing')
            ->where('nim_nid_dosen', $nidDosen)
            ->pluck('proposal_id');

        $nimMahasiswaList = DB::table('proposal')
            ->whereIn('id', $proposalIds)
            ->pluck('nim_nid');

        // TAB 1 — Proposal Bimbingan (filter per dosen juga)
        $proposalList = DB::table('pengajuan_proposal_bimbingan as ppb')
            ->join('users as u', 'u.nim_nid', '=', 'ppb.nim_nid')
            ->whereIn('ppb.nim_nid', $nimMahasiswaList)
            ->where('ppb.dosen_nid', $nidDosen) // ✅ hanya proposal yang ditujukan ke dosen ini
            ->select(
                'ppb.id',
                'ppb.nim_nid',
                'ppb.judul',
                'ppb.tanggal_pengajuan',
                'ppb.file_proposal',
                'ppb.status',
                'ppb.created_at',
                'u.nama as nama_mahasiswa'
            )
            ->orderBy('ppb.created_at', 'desc')
            ->get();

        // TAB 2 — Mahasiswa Bimbingan
        // ✅ Filter dari tabel bimbingan, bukan dosen_pembimbing
        // Jadi Rose hanya muncul di Jennie kalau Rose pernah bimbingan ke Jennie
        $mahasiswaList = DB::table('bimbingan as b')
            ->join('users as u', 'u.nim_nid', '=', 'b.nim_nid')
            ->where('b.dosen_nid', $nidDosen)
            ->select(
                'b.nim_nid',
                'u.nama as nama_mahasiswa',
                'u.angkatan',
                DB::raw('COUNT(b.id) as total_bimbingan')
            )
            ->groupBy('b.nim_nid', 'u.nama', 'u.angkatan')
            ->orderBy('u.nama')
            ->get();

        return view('dosen.bimbingan', compact('proposalList', 'mahasiswaList', 'dosen'));
    }

    public function updateStatusProposal(Request $request, $id)
    {
        if (!session('user')) return redirect('/login');

        DB::table('pengajuan_proposal_bimbingan')
            ->where('id', $id)
            ->update(['status' => $request->status, 'updated_at' => now()]);

        return redirect()->back()->with('success', 'Status proposal berhasil diperbarui.');
    }

    /**
     * Dipanggil saat dosen klik Preview PDF.
     * Otomatis update status → 'sudah_dilihat', lalu buka file PDF.
     */
    public function lihatProposal($id)
    {
        if (!session('user')) return redirect('/login');

        $proposal = DB::table('pengajuan_proposal_bimbingan')->where('id', $id)->first();

        if (!$proposal) abort(404);

        if ($proposal->status === 'pending') {
            DB::table('pengajuan_proposal_bimbingan')
                ->where('id', $id)
                ->update(['status' => 'sudah_dilihat', 'updated_at' => now()]);
        }

        return redirect(asset('uploads/proposal/' . $proposal->file_proposal));
    }

    public function detailMahasiswa($nim)
    {
        if (!session('user')) return redirect('/login');

        $dosen    = session('user');
        $nidDosen = $dosen->nim_nid;

        $mahasiswa = DB::table('users')->where('nim_nid', $nim)->first();

        $bimbingan = DB::table('bimbingan')
            ->where('nim_nid', $nim)
            ->where('dosen_nid', $nidDosen)
            ->orderBy('pertemuan_ke', 'asc')
            ->get();

        $pengajuan = DB::table('pengajuan_judul')
            ->where('nim_nid', $nim)
            ->where('status', 'disetujui')
            ->latest('updated_at')
            ->first();

        $judulTA        = $pengajuan->judul_disetujui ?? '-';
        $totalBimbingan = $bimbingan->count();
        $minBimbingan   = 6;

        return view('dosen.detail_bimbingan', compact(
            'mahasiswa', 'bimbingan', 'judulTA',
            'dosen', 'totalBimbingan', 'minBimbingan'
        ));
    }

    public function updateStatusBimbingan(Request $request, $id)
    {
        if (!session('user')) return redirect('/login');

        DB::table('bimbingan')
            ->where('id', $id)
            ->update(['status' => 'Sudah Dilihat', 'updated_at' => now()]);

        return redirect()->back()->with('success', 'Status bimbingan diperbarui.');
    }
}
