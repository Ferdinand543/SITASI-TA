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
                'ppb.file_proposal', 'ppb.status', 'ppb.status_admin', 'ppb.created_at',
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

    // ── VALIDASI BIMBINGAN (Valid / Tidak Valid) ──
    // Pakai kolom status_validasi & catatan_dosen yang sama dengan sisi dosen,
    // jadi begitu salah satu pihak (dosen/admin) sudah validasi, pihak lain otomatis
    // melihatnya sebagai "sudah final" dan tidak bisa validasi ulang (dicegah di modal/JS).
    public function validasiBimbingan(Request $request, $id)
    {
        if (!session('user')) return redirect('/login');

        $request->validate([
            'status_validasi' => 'required|in:Valid,Tidak Valid',
            'catatan_dosen'   => 'nullable|string',
        ]);

        DB::table('bimbingan')->where('id', $id)->update([
            'status_validasi' => $request->status_validasi,
            'catatan_dosen'   => $request->status_validasi === 'Tidak Valid' ? $request->catatan_dosen : null,
            'updated_at'      => now(),
        ]);

        return redirect()->back()->with('success', 'Validasi bimbingan berhasil disimpan.');
    }

    // ── TRACK BUKA: dipanggil via AJAX tiap admin klik salah satu chip dokumen/link ──
    // Pakai status_admin (kolom terpisah) supaya tidak mengganggu status dosen
    public function trackBuka(Request $request, $id)
    {
        if (!session('user')) return response()->json(['ok' => false], 401);

        $proposal = DB::table('pengajuan_proposal_bimbingan')->where('id', $id)->first();

        if (!$proposal || $proposal->status_admin !== 'pending') {
            return response()->json(['ok' => true, 'status' => $proposal->status_admin ?? 'not_found']);
        }

        $decoded   = json_decode($proposal->file_proposal, true);
        $files     = is_array($decoded) ? ($decoded['files'] ?? []) : [$proposal->file_proposal];
        $links     = is_array($decoded) ? ($decoded['links'] ?? []) : [];
        $totalAset = count($files) + count($links);
        if ($totalAset === 0) $totalAset = 1;

        $sessionKey = 'admin_opened_proposal_' . $id;
        $opened     = session($sessionKey, []);
        $index      = $request->input('index');

        if ($index && !in_array($index, $opened)) {
            $opened[] = $index;
            session([$sessionKey => $opened]);
        }

        if (count($opened) >= $totalAset) {
            DB::table('pengajuan_proposal_bimbingan')
                ->where('id', $id)
                ->update(['status_admin' => 'sudah_dilihat', 'updated_at' => now()]);

            return response()->json(['ok' => true, 'status' => 'sudah_dilihat']);
        }

        return response()->json([
            'ok'     => true,
            'status' => 'pending',
            'opened' => count($opened),
            'total'  => $totalAset,
        ]);
    }
}