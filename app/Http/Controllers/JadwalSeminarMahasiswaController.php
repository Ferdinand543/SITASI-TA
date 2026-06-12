<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalSeminarMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        if (!session('user')) return redirect('/login');

        $user = session('user');
        $nim  = $user->nim_nid;
        $role = $user->role;

        if ($role !== 'dosen') {
            abort(403);
        }

        // =====================================================
        // ID pengajuan_seminars dimana dosen ini jadi PEMBIMBING
        // (lewat dosen_pembimbing -> proposal -> nim_nid mahasiswa)
        // =====================================================
        $idsPembimbing = DB::table('dosen_pembimbing as dp')
            ->join('proposal as p', 'p.id', '=', 'dp.proposal_id')
            ->join('pengajuan_seminars as ps', DB::raw('ps.mahasiswa_id COLLATE utf8mb4_unicode_ci'), '=', DB::raw('p.nim_nid COLLATE utf8mb4_unicode_ci'))
            ->where('dp.nim_nid_dosen', $nim)
            ->where('ps.is_draft', 0)
            ->pluck('ps.id');

        // =====================================================
        // ID pengajuan_seminars dimana dosen ini jadi PENGUJI
        // =====================================================
        $idsPenguji = DB::table('dosen_penguji_seminar as dps')
            ->join('pengajuan_seminars as ps', 'ps.id', '=', 'dps.pengajuan_seminar_id')
            ->where('dps.nim_nid_dosen', $nim)
            ->where('ps.is_draft', 0)
            ->pluck('ps.id');

        $allIds = $idsPembimbing->merge($idsPenguji)->unique()->values();

        // =====================================================
        // QUERY UTAMA
        // =====================================================
        $query = DB::table('pengajuan_seminars as ps')
            ->join('users as u', DB::raw('u.nim_nid COLLATE utf8mb4_unicode_ci'), '=', DB::raw('ps.mahasiswa_id COLLATE utf8mb4_unicode_ci'))
            ->select(
                'ps.id',
                'ps.mahasiswa_id',
                'ps.judul_ta',
                'ps.status_seminar',
                'ps.status_administrasi',
                'ps.tanggal_seminar',
                'ps.waktu_mulai',
                'ps.waktu_selesai',
                'ps.ruang',
                'ps.file_proposal',
                'u.nama',
                'u.angkatan'
            )
            ->whereIn('ps.id', $allIds)
            ->whereNotNull('ps.tanggal_seminar');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('u.nama', 'like', "%$search%")
                  ->orWhere('ps.mahasiswa_id', 'like', "%$search%")
                  ->orWhere('ps.judul_ta', 'like', "%$search%");
            });
        }

        if ($request->filled('status') && $request->status !== 'Semua Status') {
            $query->where('ps.status_seminar', $request->status);
        }

        $seminars = $query->orderBy('ps.tanggal_seminar', 'asc')->get();

        // =====================================================
        // LENGKAPI: peran dosen ini, daftar pembimbing & penguji
        // =====================================================
        foreach ($seminars as $s) {
            $s->pembimbing = [];
            $s->penguji    = [];
            $s->peran      = [];

            $proposal = DB::table('proposal')
                ->where('nim_nid', $s->mahasiswa_id)
                ->latest()
                ->first();

            if ($proposal) {
                $dosbings = DB::table('dosen_pembimbing')
                    ->where('proposal_id', $proposal->id)
                    ->orderBy('urutan')
                    ->get();

                foreach ($dosbings as $d) {
                    $nama = DB::table('users')->where('nim_nid', $d->nim_nid_dosen)->value('nama');
                    if ($nama) $s->pembimbing[] = ['nama' => $nama, 'is_self' => $d->nim_nid_dosen === $nim];
                    if ($d->nim_nid_dosen === $nim) $s->peran[] = 'Pembimbing';
                }
            }

            $dospengujis = DB::table('dosen_penguji_seminar')
                ->where('pengajuan_seminar_id', $s->id)
                ->orderBy('urutan')
                ->get();

            foreach ($dospengujis as $dp) {
                $nama = DB::table('users')->where('nim_nid', $dp->nim_nid_dosen)->value('nama');
                if ($nama) $s->penguji[] = ['nama' => $nama, 'is_self' => $dp->nim_nid_dosen === $nim];
                if ($dp->nim_nid_dosen === $nim) $s->peran[] = 'Penguji';
            }
        }

        $today = now()->toDateString();

        $totalJadwalSeminar = $seminars->count();

        $seminarHariIni = $seminars->filter(function ($s) use ($today) {
            return $s->tanggal_seminar === $today;
        })->values();

        $totalHariIni = $seminarHariIni->count();

        return view('jadwalseminar.JadwalMahasiswaSeminar', compact(
            'seminars',
            'totalJadwalSeminar',
            'totalHariIni',
            'seminarHariIni'
        ));
    }
}