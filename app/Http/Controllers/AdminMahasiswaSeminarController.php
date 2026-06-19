<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AdminMahasiswaSeminarController extends Controller
{
    public function mahasiswaSeminar()
    {
        if (!session('user')) return redirect('/login');

        // Admin bisa liat SEMUA mahasiswa seminar, ga difilter dosen penguji/pembimbing manapun
        $mahasiswaList = DB::table('pengajuan_seminars as ps')
            ->join('users as u', 'u.nim_nid', '=', 'ps.mahasiswa_id')
            ->where('ps.status_administrasi', 'Lolos Administrasi')
            ->whereIn('ps.status_seminar', ['Menunggu Jadwal', 'Sudah Dijadwalkan', 'Selesai'])
            ->select('u.nim_nid', 'u.nama', 'ps.judul_ta', 'ps.id as pengajuan_id', 'ps.mahasiswa_id')
            ->get()
            ->unique('pengajuan_id')
            ->values()
            ->map(function ($mhs) {
                $mhs->angkatan = substr($mhs->nim_nid, 0, 4);

                $proposal = DB::table('proposal')
                    ->where('nim_nid', $mhs->mahasiswa_id)
                    ->latest()
                    ->first();

                $mhs->pembimbing1 = null;
                $mhs->pembimbing2 = null;

                if ($proposal) {
                    $pb1 = DB::table('dosen_pembimbing')->where('proposal_id', $proposal->id)->where('urutan', 1)->first();
                    $pb2 = DB::table('dosen_pembimbing')->where('proposal_id', $proposal->id)->where('urutan', 2)->first();
                    $mhs->pembimbing1 = $pb1 ? DB::table('users')->where('nim_nid', $pb1->nim_nid_dosen)->value('nama') : null;
                    $mhs->pembimbing2 = $pb2 ? DB::table('users')->where('nim_nid', $pb2->nim_nid_dosen)->value('nama') : null;
                }

                // tambahan: dosen penguji juga ditampilin di admin, dipecah per urutan kayak pembimbing
                $pengujiList = DB::table('dosen_penguji_seminar as dps')
                    ->join('users as u2', function($join) {
                        $join->on(DB::raw('u2.nim_nid COLLATE utf8mb4_unicode_ci'), '=', DB::raw('dps.nim_nid_dosen COLLATE utf8mb4_unicode_ci'));
                    })
                    ->where('dps.pengajuan_seminar_id', $mhs->pengajuan_id)
                    ->orderBy('dps.id')
                    ->pluck('u2.nama')
                    ->values();

                $mhs->penguji1 = $pengujiList->get(0);
                $mhs->penguji2 = $pengujiList->get(1);

                return $mhs;
            });

        $totalMahasiswa = $mahasiswaList->count();

        return view('admin.adminmahasiswa_seminar', compact('mahasiswaList', 'totalMahasiswa'));
    }
}