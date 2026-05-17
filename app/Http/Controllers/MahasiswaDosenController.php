<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaDosenController extends Controller
{
    public function index(Request $request)
    {
        if (!session('user')) return redirect('/login');

        $user = session('user');
        $role = strtolower(trim($user->role));

        // Hanya dosen yang boleh akses
        if ($role !== 'dosen') {
            return redirect('/login')->with('error', 'Akses ditolak');
        }

        $search   = $request->get('search', '');
        $angkatan = $request->get('angkatan', '');

        // Ambil semua mahasiswa
        $query = DB::table('users')->where('role', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nim_nid', 'like', "%$search%");
            });
        }

        if ($angkatan) {
            $query->where('angkatan', $angkatan);
        }

        $mahasiswaList = $query->orderBy('nama')->get();

        // Hitung progress tiap mahasiswa
        $mahasiswaList = $mahasiswaList->map(function ($mhs) {
            $nim = $mhs->nim_nid;

            // Step 1: Ada pengajuan judul (+20%)
            $adaPengajuan = DB::table('pengajuan_judul')
                ->where('nim_nid', $nim)
                ->exists();

            // Step 2: Judul disetujui (+20%)
            $judulDisetujui = DB::table('pengajuan_judul')
                ->where('nim_nid', $nim)
                ->where('status', 'disetujui')
                ->exists();

            // Step 3: Upload proposal (+20%)
            $adaProposal = DB::table('proposal')
                ->where('nim_nid', $nim)
                ->exists();

            // Step 4: Proposal disetujui/selesai review (+20%)
            $proposalSelesai = DB::table('proposal')
                ->where('nim_nid', $nim)
                ->where('status', 'selesai')
                ->exists();

            // Step 5: Daftar seminar (+20%) — belum ada tabel, nanti ditambah
            $daftarSeminar = false;

            $progress = 0;
            if ($adaPengajuan)    $progress += 20;
            if ($judulDisetujui)  $progress += 20;
            if ($adaProposal)     $progress += 20;
            if ($proposalSelesai) $progress += 20;
            if ($daftarSeminar)   $progress += 20;

            $mhs->progress = $progress;
            return $mhs;
        });

        // Data untuk filter angkatan
        $angkatanList = DB::table('users')
            ->where('role', 'mahasiswa')
            ->whereNotNull('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan');

        $totalMahasiswa = DB::table('users')->where('role', 'mahasiswa')->count();
        $totalAngkatan  = $angkatanList->count();

        return view('dosen.mahasiswa_list', compact(
            'mahasiswaList',
            'angkatanList',
            'totalMahasiswa',
            'totalAngkatan',
            'search',
            'angkatan'
        ));
    }
}