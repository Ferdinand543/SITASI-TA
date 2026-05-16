<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatBimbinganController extends Controller
{
    private function cekLogin()
    {
        if (!session('user')) return redirect('/login');
        return null;
    }

    private function getNimDosen(): string
    {
        return session('user')->nim_nid ?? '';
    }

    // ─────────────────────────────────────────────────────────
    //  TAB: PROPOSAL BIMBINGAN
    // ─────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        $nimDosen = $this->getNimDosen();
        $search   = trim($request->input('search', ''));
        $status   = trim($request->input('status', ''));

        $query = DB::table('proposal as p')
            ->join('dosen_pembimbing as dp', 'dp.proposal_id', '=', 'p.id')
            ->leftJoin('users as m', 'm.nim_nid', '=', 'p.nim_nid')
            // Ambil status bimbingan terakhir dari bimbingan_mahasiswa
            ->leftJoin('bimbingan_mahasiswa as bm', function ($join) {
                $join->on('bm.nim_nid', '=', 'p.nim_nid')
                     ->whereRaw('bm.id = (
                         SELECT id FROM bimbingan_mahasiswa
                         WHERE nim_nid = p.nim_nid
                         ORDER BY tanggal_bimbingan DESC
                         LIMIT 1
                     )');
            })
            ->where('dp.nim_nid_dosen', $nimDosen)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('m.nama', 'like', "%$search%")
                       ->orWhere('m.nim_nid', 'like', "%$search%")
                       ->orWhere('p.judul', 'like', "%$search%");
                });
            })
            ->when($status, fn($q) => $q->where('bm.status', $status))
            ->select(
                'p.id',
                'p.nim_nid as nim',
                'm.nama',
                'p.tanggal_pengajuan',
                'p.judul',
                'p.file_proposal',
                'bm.status as status',  // status dari bimbingan_mahasiswa
                'dp.urutan'
            )
            ->orderByDesc('p.tanggal_pengajuan');

        $proposals = $query->paginate(10)->withQueryString();

        return view('riwayatbimbingan.index', compact('proposals', 'search', 'status'));
    }

    // ─────────────────────────────────────────────────────────
    //  TANDAI DILIHAT — File Proposal dibuka Dosen
    // ─────────────────────────────────────────────────────────
    public function tandaiDilihat($id)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        $proposal = DB::table('proposal')->where('id', $id)->first();
        if (!$proposal) abort(404);

        // Update semua bimbingan mahasiswa ini yang masih "Baru Dikirim"
        DB::table('bimbingan_mahasiswa')
            ->where('nim_nid', $proposal->nim_nid)
            ->where('status', 'Baru Dikirim')
            ->update(['status' => 'Sudah Dilihat']);

        return redirect(asset('storage/' . $proposal->file_proposal));
    }

    // ─────────────────────────────────────────────────────────
    //  TAB: MAHASISWA BIMBINGAN
    // ─────────────────────────────────────────────────────────
    public function mahasiswa(Request $request)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        $nimDosen = $this->getNimDosen();
        $search   = trim($request->input('search', ''));

        $query = DB::table('bimbingan_mahasiswa as b')
            ->join('proposal as p', 'p.nim_nid', '=', 'b.nim_nid')
            ->join('dosen_pembimbing as dp', function ($join) use ($nimDosen) {
                $join->on('dp.proposal_id', '=', 'p.id')
                     ->where('dp.nim_nid_dosen', $nimDosen);
            })
            ->leftJoin('users as m', 'm.nim_nid', '=', 'b.nim_nid')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('m.nama', 'like', "%$search%")
                       ->orWhere('b.nim_nid', 'like', "%$search%");
                });
            })
            ->select(
                'b.nim_nid as nim',
                'm.nama',
                'm.angkatan',
                DB::raw('COUNT(b.id) as total_pertemuan'),
                DB::raw('MAX(b.tanggal_bimbingan) as tanggal_terakhir'),
                DB::raw("SUBSTRING_INDEX(
                    GROUP_CONCAT(b.topik_bimbingan ORDER BY b.tanggal_bimbingan DESC SEPARATOR '|||'),
                    '|||', 1
                ) as topik_terakhir"),
                DB::raw("SUBSTRING_INDEX(
                    GROUP_CONCAT(b.status ORDER BY b.tanggal_bimbingan DESC SEPARATOR '|||'),
                    '|||', 1
                ) as status_terakhir")
            )
            ->groupBy('b.nim_nid', 'm.nama', 'm.angkatan')
            ->orderByDesc('tanggal_terakhir');

        $mahasiswaList = $query->paginate(10)->withQueryString();

        return view('riwayatbimbingan.mahasiswabimbingan', compact('mahasiswaList', 'search'));
    }

    // ─────────────────────────────────────────────────────────
    //  DETAIL — Semua pertemuan satu mahasiswa
    // ─────────────────────────────────────────────────────────
    public function detail(Request $request, string $nim)
    {
        $redirect = $this->cekLogin();
        if ($redirect) return $redirect;

        $nimDosen = $this->getNimDosen();

        $mahasiswa = DB::table('users as m')
            ->join('proposal as p', 'p.nim_nid', '=', 'm.nim_nid')
            ->join('dosen_pembimbing as dp', function ($join) use ($nimDosen) {
                $join->on('dp.proposal_id', '=', 'p.id')
                     ->where('dp.nim_nid_dosen', $nimDosen);
            })
            ->where('m.nim_nid', $nim)
            ->select('m.nim_nid as nim', 'm.nama', 'p.judul')
            ->first();

        if (!$mahasiswa) {
            return redirect()->route('dosen.riwayat-bimbingan.mahasiswa')
                             ->with('error', 'Data tidak ditemukan.');
        }

        // Tandai semua "Baru Dikirim" jadi "Sudah Dilihat" saat dosen buka detail
        DB::table('bimbingan_mahasiswa')
            ->where('nim_nid', $nim)
            ->where('status', 'Baru Dikirim')
            ->update(['status' => 'Sudah Dilihat']);

        $bimbinganList = DB::table('bimbingan_mahasiswa')
            ->where('nim_nid', $nim)
            ->orderBy('tanggal_bimbingan')
            ->orderBy('pertemuan_ke')
            ->get();

        $totalPertemuan = $bimbinganList->count();

        return view('riwayatbimbingan.detail', compact(
            'mahasiswa',
            'bimbinganList',
            'totalPertemuan'
        ));
    }
}