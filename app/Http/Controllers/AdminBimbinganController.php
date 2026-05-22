<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBimbinganController extends Controller
{
    public function index()
    {
        if (!session('user')) return redirect('/login');

        $admin = session('user');

        $proposalList = DB::table('pengajuan_proposal_bimbingan as ppb')
            ->join('users as mhs', 'mhs.nim_nid', '=', 'ppb.nim_nid')
            ->leftJoin('users as dosen', 'dosen.nim_nid', '=', 'ppb.dosen_nid')
            ->select(
                'ppb.id', 'ppb.nim_nid', 'ppb.judul', 'ppb.tanggal_pengajuan',
                'ppb.file_proposal', 'ppb.status', 'ppb.created_at',
                'mhs.nama as nama_mahasiswa', 'dosen.nama as nama_dosen'
            )
            ->orderBy('ppb.created_at', 'desc')
            ->get();

        $dosenList = DB::table('dosen_pembimbing as dp')
            ->join('users as d', 'd.nim_nid', '=', 'dp.nim_nid_dosen')
            ->select(
                'd.nim_nid',
                'd.nama as nama_dosen',
                DB::raw('COUNT(DISTINCT dp.proposal_id) as jumlah_mahasiswa')
            )
            ->groupBy('d.nim_nid', 'd.nama')
            ->orderBy('d.nama')
            ->get();

        $countProposal = $proposalList->count();
        $countDosen    = $dosenList->count();

        return view('admin.bimbingan', compact(
            'proposalList', 'dosenList', 'countProposal', 'countDosen', 'admin'
        ));
    }

    public function updateStatusProposal(Request $request, $id)
    {
        if (!session('user')) return redirect('/login');
        DB::table('pengajuan_proposal_bimbingan')->where('id', $id)->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status proposal berhasil diperbarui.');
    }

    public function lihatProposal($id)
    {
        if (!session('user')) return redirect('/login');
        $proposal = DB::table('pengajuan_proposal_bimbingan')->where('id', $id)->first();
        if (!$proposal) abort(404);
        return redirect(asset('uploads/proposal/' . $proposal->file_proposal));
    }

    public function detailDosen($nim_nid_dosen)
    {
        if (!session('user')) return redirect('/login');

        $dosen = DB::table('users')->where('nim_nid', $nim_nid_dosen)->first();
        if (!$dosen) abort(404);

        $mahasiswaList = DB::table('dosen_pembimbing as dp')
            ->join('proposal as p', 'p.id', '=', 'dp.proposal_id')
            ->join('users as mhs', 'mhs.nim_nid', '=', 'p.nim_nid')
            ->where('dp.nim_nid_dosen', $nim_nid_dosen)
            ->select(
                'mhs.nim_nid',
                'mhs.nama as nama_mahasiswa',
                'mhs.angkatan',
                // ✅ hanya ngitung bimbingan ke dosen ini saja
                DB::raw('(SELECT COUNT(*) FROM bimbingan WHERE nim_nid = mhs.nim_nid AND dosen_nid = dp.nim_nid_dosen) as total_bimbingan')
            )
            ->orderBy('mhs.nama')
            ->get();

        return view('admin.detail_dosen_bimbingan', compact('dosen', 'mahasiswaList'));
    }

    public function detailMahasiswa($nim, $nim_nid_dosen)
    {
        if (!session('user')) return redirect('/login');

        $mahasiswa = DB::table('users')->where('nim_nid', $nim)->first();
        $dosen     = DB::table('users')->where('nim_nid', $nim_nid_dosen)->first();

        // ✅ filter bimbingan hanya ke dosen yang dipilih
        $bimbingan = DB::table('bimbingan as b')
            ->leftJoin('users as dosen', 'dosen.nim_nid', '=', 'b.dosen_nid')
            ->where('b.nim_nid', $nim)
            ->where('b.dosen_nid', $nim_nid_dosen)
            ->select('b.*', 'dosen.nama as nama_dosen')
            ->orderBy('b.pertemuan_ke', 'asc')
            ->get();

        $pengajuan = DB::table('pengajuan_judul')
            ->where('nim_nid', $nim)
            ->where('status', 'disetujui')
            ->latest('updated_at')
            ->first();

        $judulTA        = $pengajuan->judul_disetujui ?? '-';
        $totalBimbingan = $bimbingan->count();
        $minBimbingan   = 6;

        return view('admin.detail_bimbingan', compact(
            'mahasiswa', 'dosen', 'bimbingan', 'judulTA',
            'totalBimbingan', 'minBimbingan'
        ));
    }

    public function updateStatusBimbingan(Request $request, $id)
    {
        if (!session('user')) return redirect('/login');
        DB::table('bimbingan')->where('id', $id)->update(['status' => 'Sudah Dilihat']);
        return redirect()->back()->with('success', 'Status bimbingan berhasil diperbarui.');
    }
}