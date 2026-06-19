<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminJadwalSeminarMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        if (!session('user')) return redirect('/login');

        // Admin lihat SEMUA jadwal seminar mahasiswa, tanpa filter dosen manapun
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
            ->where('ps.status_administrasi', 'Lolos Administrasi')
            ->whereIn('ps.status_seminar', ['Menunggu Jadwal', 'Sudah Dijadwalkan', 'Selesai'])
            ->where('ps.is_draft', 0);

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

        foreach ($seminars as $s) {
            $s->pembimbing = [];
            $s->penguji    = [];

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
                    if ($nama) $s->pembimbing[] = ['nama' => $nama, 'urutan' => $d->urutan];
                }
            }

            $dospengujis = DB::table('dosen_penguji_seminar')
                ->where('pengajuan_seminar_id', $s->id)
                ->orderBy('urutan')
                ->get();
            foreach ($dospengujis as $dp) {
                $nama = DB::table('users')->where('nim_nid', $dp->nim_nid_dosen)->value('nama');
                if ($nama) $s->penguji[] = ['nama' => $nama, 'urutan' => $dp->urutan];
            }
        }

        $today              = now()->toDateString();
        $totalJadwalSeminar = $seminars->count();
        $seminarHariIni     = $seminars->filter(fn($s) => $s->tanggal_seminar === $today)->values();
        $totalHariIni       = $seminarHariIni->count();

        return view('admin.jadwal.seminar_mahasiswa', compact(
            'seminars', 'totalJadwalSeminar', 'totalHariIni', 'seminarHariIni'
        ));
    }
}