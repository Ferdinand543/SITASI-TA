<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PengujiMahasiswaSeminarController extends Controller
{
    public function mahasiswaSeminar()
    {
        if (!session('user')) return redirect('/login');

        $nimSesi = session('user')->nim_nid;

        // 1. Mahasiswa yang dosen ini jadi PENGUJI-nya
        $sebagaiPenguji = DB::table('dosen_penguji_seminar as dps')
            ->join('pengajuan_seminars as ps', 'ps.id', '=', 'dps.pengajuan_seminar_id')
            ->join('users as u', 'u.nim_nid', '=', 'ps.mahasiswa_id')
            ->where('dps.nim_nid_dosen', $nimSesi)
            ->where('ps.status_administrasi', 'Lolos Administrasi')
            ->where('ps.status_seminar', 'Menunggu Jadwal')
            ->select('u.nim_nid', 'u.nama', 'ps.judul_ta', 'ps.id as pengajuan_id', 'ps.mahasiswa_id')
            ->get();

        // 2. Mahasiswa yang dosen ini jadi PEMBIMBING-nya
        $sebagaiPembimbing = DB::table('dosen_pembimbing as dp')
            ->join('proposal as p', 'p.id', '=', 'dp.proposal_id')
            ->join('pengajuan_seminars as ps', function($join) {
                $join->on(DB::raw('ps.mahasiswa_id COLLATE utf8mb4_unicode_ci'), '=', DB::raw('p.nim_nid COLLATE utf8mb4_unicode_ci'));
            })
            ->join('users as u', 'u.nim_nid', '=', 'ps.mahasiswa_id')
            ->where('dp.nim_nid_dosen', $nimSesi)
            ->where('ps.status_administrasi', 'Lolos Administrasi')
            ->where('ps.status_seminar', 'Menunggu Jadwal')
            ->whereExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('dosen_penguji_seminar')
                    ->whereColumn('dosen_penguji_seminar.pengajuan_seminar_id', 'ps.id');
            })
            ->select('u.nim_nid', 'u.nama', 'ps.judul_ta', 'ps.id as pengajuan_id', 'ps.mahasiswa_id')
            ->get();

        // 3. Gabung
        $mahasiswaList = $sebagaiPenguji
            ->concat($sebagaiPembimbing)
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

                return $mhs;
            });

        $totalMahasiswa = $mahasiswaList->count();

        return view('dosen.mahasiswa_seminar', compact('mahasiswaList', 'totalMahasiswa'));
    }
}