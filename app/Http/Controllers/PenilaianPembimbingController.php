<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenilaianPembimbingController extends Controller
{
    private function guardPembimbing()
    {
        if (!session('user')) {
            abort(redirect('/login'));
        }

        $nim = session('user')->nim_nid;

        // ✅ FIXED: cek dari dosen_roles (bukan dosen_pembimbing)
        // dosen_pembimbing adalah tabel relasi mahasiswa-dosen,
        // sedangkan dosen_roles adalah tabel yang menentukan role dosen
        $isPembimbing = DB::table('dosen_roles')
            ->where('nim_nid', $nim)
            ->where('role_dosen', 'pembimbing')
            ->exists();

        if (!$isPembimbing) {
            abort(403, 'Akses ditolak. Anda bukan dosen pembimbing.');
        }
    }

    public function index()
    {
        $this->guardPembimbing();
        $nimPembimbing = session('user')->nim_nid;

        // ✅ Filter mahasiswa yang:
        // 1. Dibimbing oleh dosen ini → join dosen_pembimbing (dp.nim_nid_dosen = $nimPembimbing)
        // 2. Sudah Lolos Administrasi → join pengajuan_seminars (status_administrasi = 'Lolos Administrasi')
        // COLLATE dipakai untuk hindari collation mismatch antar kolom
        $proposals = DB::table('proposal as p')
            ->join('users as u', 'u.nim_nid', '=', 'p.nim_nid')
            ->join('dosen_pembimbing as dp', function ($join) use ($nimPembimbing) {
                $join->on('dp.proposal_id', '=', 'p.id')
                     ->where('dp.nim_nid_dosen', '=', $nimPembimbing);
            })
            ->join('pengajuan_seminars as psem',
                DB::raw('psem.mahasiswa_id COLLATE utf8mb4_unicode_ci'),
                '=',
                DB::raw('p.nim_nid COLLATE utf8mb4_unicode_ci')
            )
            ->where('psem.status_administrasi', 'Lolos Administrasi')
            ->leftJoin('penilaian_seminar_pembimbing as psp', function ($join) use ($nimPembimbing) {
                $join->on('psp.proposal_id', '=', 'p.id')
                     ->where('psp.nim_nid_pembimbing', '=', $nimPembimbing);
            })
            ->select(
                'p.id as proposal_id',
                'p.nim_nid',
                'p.judul as judul_ta',
                'u.nama',
                'dp.urutan as urutan_pembimbing',
                'psp.status as status_penilaian',
                'psp.nilai_akhir',
                'psp.id as penilaian_id'
            )
            ->orderBy('u.nama')
            ->get();

        $total        = $proposals->count();
        $belumDinilai = $proposals->whereNull('status_penilaian')->count();
        $draft        = $proposals->where('status_penilaian', 'draft')->count();
        $sudahDinilai = $proposals->where('status_penilaian', 'submitted')->count();

        return view('penilaianPembimbing.index', compact(
            'proposals', 'total', 'belumDinilai', 'draft', 'sudahDinilai'
        ));
    }

    public function form($proposalId)
    {
        $this->guardPembimbing();
        $nimPembimbing = session('user')->nim_nid;

        $proposal = DB::table('proposal as p')
            ->join('users as u', 'u.nim_nid', '=', 'p.nim_nid')
            ->where('p.id', $proposalId)
            ->select('p.*', 'p.judul as judul_ta', 'u.nama as nama_mahasiswa')
            ->first();

        abort_if(!$proposal, 404);

        $dosenPembimbing = DB::table('users')
            ->where('nim_nid', $nimPembimbing)
            ->first();

        $urutan = DB::table('dosen_pembimbing')
            ->where('proposal_id', $proposalId)
            ->where('nim_nid_dosen', $nimPembimbing)
            ->value('urutan');

        $urutanPembimbing = $urutan ? 'Pembimbing ' . $urutan : 'Pembimbing';

        $penilaian = DB::table('penilaian_seminar_pembimbing')
            ->where('proposal_id', $proposalId)
            ->where('nim_nid_pembimbing', $nimPembimbing)
            ->first();

        $jadwalSeminar = DB::table('jadwal_akademik')
            ->where('kategori', 'Seminar')
            ->whereNotNull('tanggal_selesai')
            ->orderBy('tanggal', 'desc')
            ->first();

        $bisaEdit = true;
        if ($jadwalSeminar && $jadwalSeminar->tanggal_selesai) {
            $bisaEdit = now()->lte(\Carbon\Carbon::parse($jadwalSeminar->tanggal_selesai)->endOfDay());
        }

        if ($penilaian && $penilaian->status === 'submitted' && !$bisaEdit) {
            return redirect()->route('penilaian.pembimbing.show', $proposalId)
                ->with('info', 'Batas waktu edit penilaian sudah berakhir.');
        }

        return view('penilaianPembimbing.form', compact(
            'proposal', 'penilaian', 'dosenPembimbing', 'urutanPembimbing', 'bisaEdit'
        ));
    }

    public function store(Request $request, $proposalId)
    {
        $this->guardPembimbing();
        $nimPembimbing = session('user')->nim_nid;

        $request->validate([
            'nilai_kualitas_bimbingan'    => 'required|integer|min:1|max:10',
            'nilai_kemampuan_penelusuran' => 'required|integer|min:1|max:15',
            'nilai_penggunaan_teori'      => 'required|integer|min:1|max:15',
            'nilai_dokumentasi_produk'    => 'required|integer|min:1|max:25',
            'nilai_kesesuaian_target'     => 'required|integer|min:1|max:35',
            'nilai_teknik_presentasi'     => 'required|integer|min:1|max:15',
            'nilai_dokumentasi_proposal'  => 'required|integer|min:1|max:20',
            'nilai_kemanfaatan_teori'     => 'required|integer|min:1|max:30',
            'nilai_pemahaman_kebutuhan'   => 'required|integer|min:1|max:35',
            'kelayakan' => 'required|in:layak,tidak_layak',
            'catatan'   => 'nullable|string|max:1000',
            'aksi'      => 'required|in:draft,submitted',
        ]);

        $proposal = DB::table('proposal')->where('id', $proposalId)->first();
        abort_if(!$proposal, 404);

        $jadwalSeminar = DB::table('jadwal_akademik')
            ->where('kategori', 'Seminar')
            ->whereNotNull('tanggal_selesai')
            ->orderBy('tanggal', 'desc')
            ->first();

        $bisaEdit = true;
        if ($jadwalSeminar && $jadwalSeminar->tanggal_selesai) {
            $bisaEdit = now()->lte(\Carbon\Carbon::parse($jadwalSeminar->tanggal_selesai)->endOfDay());
        }

        $existing = DB::table('penilaian_seminar_pembimbing')
            ->where('proposal_id', $proposalId)
            ->where('nim_nid_pembimbing', $nimPembimbing)
            ->first();

        if ($existing && $existing->status === 'submitted' && !$bisaEdit) {
            return redirect()->route('penilaian.pembimbing.show', $proposalId)
                ->with('error', 'Batas waktu edit penilaian sudah berakhir.');
        }

        $totalA =
            $request->nilai_kualitas_bimbingan    +
            $request->nilai_kemampuan_penelusuran  +
            $request->nilai_penggunaan_teori       +
            $request->nilai_dokumentasi_produk     +
            $request->nilai_kesesuaian_target;

        $totalB =
            $request->nilai_teknik_presentasi    +
            $request->nilai_dokumentasi_proposal +
            $request->nilai_kemanfaatan_teori    +
            $request->nilai_pemahaman_kebutuhan;

        $nilaiAkhir = $totalA + $totalB;

        $urutan = DB::table('dosen_pembimbing')
            ->where('proposal_id', $proposalId)
            ->where('nim_nid_dosen', $nimPembimbing)
            ->value('urutan');

        $data = [
            'nim_nid'                     => $proposal->nim_nid,
            'nim_nid_pembimbing'          => $nimPembimbing,
            'proposal_id'                 => (int) $proposalId,
            'urutan_pembimbing'           => $urutan ?? 1,
            'nilai_kualitas_bimbingan'    => $request->nilai_kualitas_bimbingan,
            'nilai_kemampuan_penelusuran' => $request->nilai_kemampuan_penelusuran,
            'nilai_penggunaan_teori'      => $request->nilai_penggunaan_teori,
            'nilai_dokumentasi_produk'    => $request->nilai_dokumentasi_produk,
            'nilai_kesesuaian_target'     => $request->nilai_kesesuaian_target,
            'nilai_teknik_presentasi'     => $request->nilai_teknik_presentasi,
            'nilai_dokumentasi_proposal'  => $request->nilai_dokumentasi_proposal,
            'nilai_kemanfaatan_teori'     => $request->nilai_kemanfaatan_teori,
            'nilai_pemahaman_kebutuhan'   => $request->nilai_pemahaman_kebutuhan,
            'total_a'                     => $totalA,
            'total_b'                     => $totalB,
            'nilai_akhir'                 => $nilaiAkhir,
            'kelayakan'                   => $request->kelayakan,
            'catatan'                     => $request->catatan,
            'status'                      => $request->aksi,
            'updated_at'                  => now(),
        ];

        if ($existing) {
            DB::table('penilaian_seminar_pembimbing')
                ->where('proposal_id', $proposalId)
                ->where('nim_nid_pembimbing', $nimPembimbing)
                ->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('penilaian_seminar_pembimbing')->insert($data);
        }

        $msg = $request->aksi === 'submitted'
            ? 'Penilaian berhasil disubmit!'
            : 'Draft penilaian berhasil disimpan.';

        if ($request->aksi === 'submitted') {
            return redirect()->route('penilaian.pembimbing.show', $proposalId)->with('success', $msg);
        }

        return redirect()->route('penilaian.pembimbing.index')->with('success', $msg);
    }

    public function show($proposalId)
    {
        $this->guardPembimbing();
        $nimPembimbing = session('user')->nim_nid;

        $proposal = DB::table('proposal as p')
            ->join('users as u', 'u.nim_nid', '=', 'p.nim_nid')
            ->where('p.id', $proposalId)
            ->select('p.*', 'p.judul as judul_ta', 'u.nama as nama_mahasiswa')
            ->first();

        abort_if(!$proposal, 404);

        $penilaian = DB::table('penilaian_seminar_pembimbing')
            ->where('proposal_id', $proposalId)
            ->where('nim_nid_pembimbing', $nimPembimbing)
            ->first();

        abort_if(!$penilaian, 404);

        $dosenPembimbing = DB::table('users')
            ->where('nim_nid', $nimPembimbing)
            ->first();

        $jadwalSeminar = DB::table('jadwal_akademik')
            ->where('kategori', 'Seminar')
            ->whereNotNull('tanggal_selesai')
            ->orderBy('tanggal', 'desc')
            ->first();

        $bisaEdit = true;
        if ($jadwalSeminar && $jadwalSeminar->tanggal_selesai) {
            $bisaEdit = now()->lte(\Carbon\Carbon::parse($jadwalSeminar->tanggal_selesai)->endOfDay());
        }

        return view('penilaianPembimbing.show', compact(
            'proposal', 'penilaian', 'dosenPembimbing', 'bisaEdit'
        ));
    }
}