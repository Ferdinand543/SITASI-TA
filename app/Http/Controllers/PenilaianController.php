<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenilaianController extends Controller
{
    private function guardPenguji()
    {
        if (!session('user')) {
            abort(redirect('/login'));
        }

        $nim = session('user')->nim_nid;
        $isPenguji = DB::table('dosen_roles')
            ->where('nim_nid', $nim)
            ->where('role_dosen', 'penguji')
            ->exists();

        if (!$isPenguji) {
            abort(403, 'Akses ditolak.');
        }
    }

    public function index()
    {
        $this->guardPenguji();
        $nimPenguji = session('user')->nim_nid;

        $proposals = DB::table('proposal as p')
            ->join('users as u', 'u.nim_nid', '=', 'p.nim_nid')
            ->join('pengajuan_seminars as psem',
                DB::raw('psem.mahasiswa_id COLLATE utf8mb4_unicode_ci'),
                '=',
                DB::raw('p.nim_nid COLLATE utf8mb4_unicode_ci')
            )
            // ✅ FIX: join ke dosen_penguji_seminar biar cuma mahasiswa
            // yang dosen ini jadi pengujinya yang muncul
            ->join('dosen_penguji_seminar as dps', function ($join) use ($nimPenguji) {
                $join->on('dps.pengajuan_seminar_id', '=', 'psem.id')
                     ->where('dps.nim_nid_dosen', '=', $nimPenguji);
            })
            ->where('psem.status_administrasi', 'Lolos Administrasi')
            ->leftJoin('penilaian_seminar as ps', function ($join) use ($nimPenguji) {
                $join->on('ps.proposal_id', '=', 'p.id')
                     ->where('ps.nim_nid_penguji', '=', $nimPenguji);
            })
            ->select(
                'p.id as proposal_id',
                'p.nim_nid',
                'p.judul as judul_ta',
                'p.status as status_proposal',
                'u.nama',
                'dps.urutan as urutan_penguji',
                'ps.status as status_penilaian',
                'ps.nilai_akhir',
                'ps.id as penilaian_id'
            )
            ->orderBy('u.nama')
            ->get();

        $total        = $proposals->count();
        $belumDinilai = $proposals->whereNull('status_penilaian')->count();
        $draft        = $proposals->where('status_penilaian', 'draft')->count();
        $sudahDinilai = $proposals->where('status_penilaian', 'submitted')->count();

        return view('penilaianPenguji.index', compact(
            'proposals', 'total', 'belumDinilai', 'draft', 'sudahDinilai'
        ));
    }

    public function form($proposalId)
    {
        $this->guardPenguji();
        $nimPenguji = session('user')->nim_nid;

        $proposal = DB::table('proposal as p')
            ->join('users as u', 'u.nim_nid', '=', 'p.nim_nid')
            ->where('p.id', $proposalId)
            ->select('p.*', 'p.judul as judul_ta', 'u.nama as nama_mahasiswa')
            ->first();

        abort_if(!$proposal, 404);

        $dosenPenguji = DB::table('users')
            ->where('nim_nid', $nimPenguji)
            ->first();

        // ✅ FIX: ambil urutan dari dosen_penguji_seminar
        $urutan = DB::table('dosen_penguji_seminar as dps')
            ->join('pengajuan_seminars as psem', 'psem.id', '=', 'dps.pengajuan_seminar_id')
            ->join('proposal as p',
                DB::raw('p.nim_nid COLLATE utf8mb4_unicode_ci'),
                '=',
                DB::raw('psem.mahasiswa_id COLLATE utf8mb4_unicode_ci')
            )
            ->where('p.id', $proposalId)
            ->where('dps.nim_nid_dosen', $nimPenguji)
            ->value('dps.urutan');

        $urutanPenguji = $urutan ? 'Penguji ' . $urutan : 'Penguji';

        $penilaian = DB::table('penilaian_seminar')
            ->where('proposal_id', $proposalId)
            ->where('nim_nid_penguji', $nimPenguji)
            ->first();

        if ($penilaian && $penilaian->status === 'submitted') {
            $jadwalSeminar = DB::table('jadwal_akademik')
                ->where('kategori', 'Seminar')
                ->whereNotNull('tanggal_selesai')
                ->orderBy('tanggal', 'desc')
                ->first();

            $bisaEdit = true;
            if ($jadwalSeminar && $jadwalSeminar->tanggal_selesai) {
                $bisaEdit = now()->lte(\Carbon\Carbon::parse($jadwalSeminar->tanggal_selesai)->endOfDay());
            }

            if (!$bisaEdit) {
                return redirect()->route('penilaian.show', $proposalId)
                    ->with('info', 'Batas waktu edit penilaian sudah berakhir.');
            }
        }

        return view('penilaianPenguji.form', compact(
            'proposal', 'penilaian', 'dosenPenguji', 'urutanPenguji'
        ));
    }

    public function store(Request $request, $proposalId)
    {
        $this->guardPenguji();
        $nimPenguji = session('user')->nim_nid;

        $request->validate([
            'nilai_teknik_presentasi'    => 'required|numeric|min:0|max:15',
            'nilai_dokumentasi'          => 'required|numeric|min:0|max:20',
            'nilai_pemahaman_teori'      => 'required|numeric|min:0|max:30',
            'nilai_pemahaman_kebutuhan'  => 'required|numeric|min:0|max:35',
            'kelayakan'                  => 'required|in:layak,tidak_layak',
            'catatan'                    => 'nullable|string|max:1000',
            'aksi'                       => 'required|in:draft,submitted',
        ]);

        $proposal = DB::table('proposal')->where('id', $proposalId)->first();
        abort_if(!$proposal, 404);

        $existing = DB::table('penilaian_seminar')
            ->where('proposal_id', $proposalId)
            ->where('nim_nid_penguji', $nimPenguji)
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

        if ($existing && $existing->status === 'submitted' && !$bisaEdit) {
            return redirect()->route('penilaian.show', $proposalId)
                ->with('error', 'Batas waktu edit penilaian sudah berakhir.');
        }

        // ✅ FIX: ambil urutan dari dosen_penguji_seminar
        $urutan = DB::table('dosen_penguji_seminar as dps')
            ->join('pengajuan_seminars as psem', 'psem.id', '=', 'dps.pengajuan_seminar_id')
            ->join('proposal as p',
                DB::raw('p.nim_nid COLLATE utf8mb4_unicode_ci'),
                '=',
                DB::raw('psem.mahasiswa_id COLLATE utf8mb4_unicode_ci')
            )
            ->where('p.id', $proposalId)
            ->where('dps.nim_nid_dosen', $nimPenguji)
            ->value('dps.urutan');

        $nilaiAkhir = round(
            $request->nilai_teknik_presentasi +
            $request->nilai_dokumentasi +
            $request->nilai_pemahaman_teori +
            $request->nilai_pemahaman_kebutuhan,
            2
        );

        $data = [
            'nim_nid'                    => $proposal->nim_nid,
            'nim_nid_penguji'            => $nimPenguji,
            'proposal_id'                => (int) $proposalId,
            'urutan_penguji'             => $urutan ?? 1,
            'nilai_teknik_presentasi'    => $request->nilai_teknik_presentasi,
            'nilai_dokumentasi'          => $request->nilai_dokumentasi,
            'nilai_pemahaman_teori'      => $request->nilai_pemahaman_teori,
            'nilai_pemahaman_kebutuhan'  => $request->nilai_pemahaman_kebutuhan,
            'nilai_akhir'                => $nilaiAkhir,
            'kelayakan'                  => $request->kelayakan,
            'catatan'                    => $request->catatan,
            'status'                     => $request->aksi,
            'updated_at'                 => now(),
        ];

        if ($existing) {
            DB::table('penilaian_seminar')
                ->where('proposal_id', $proposalId)
                ->where('nim_nid_penguji', $nimPenguji)
                ->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('penilaian_seminar')->insert($data);
        }

        $msg = $request->aksi === 'submitted'
            ? 'Penilaian berhasil disubmit!'
            : 'Draft penilaian berhasil disimpan.';

        if ($request->aksi === 'submitted') {
            return redirect()->route('penilaian.show', $proposalId)->with('success', $msg);
        }

        return redirect()->route('penilaian.index')->with('success', $msg);
    }

    public function show($proposalId)
    {
        $this->guardPenguji();
        $nimPenguji = session('user')->nim_nid;

        $proposal = DB::table('proposal as p')
            ->join('users as u', 'u.nim_nid', '=', 'p.nim_nid')
            ->where('p.id', $proposalId)
            ->select('p.*', 'p.judul as judul_ta', 'u.nama as nama_mahasiswa')
            ->first();

        abort_if(!$proposal, 404);

        $penilaian = DB::table('penilaian_seminar')
            ->where('proposal_id', $proposalId)
            ->where('nim_nid_penguji', $nimPenguji)
            ->first();

        abort_if(!$penilaian, 404);

        $dosenPenguji = DB::table('users')
            ->where('nim_nid', $nimPenguji)
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

        return view('penilaianPenguji.show', compact(
            'proposal', 'penilaian', 'dosenPenguji', 'bisaEdit'
        ));
    }
}