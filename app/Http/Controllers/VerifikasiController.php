<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiController extends Controller
{
    // Halaman 3 judul (foto ketiga)
    public function verifikasiJudul($nim)
    {
        // Ambil data mahasiswa dari tabel users
        $mahasiswa = DB::table('users')
            ->where('nid', $nim)
            ->first();

        // Ambil data pengajuan judul dari tabel pengajuan_judul
        $pengajuan = DB::table('pengajuan_judul')
            ->where('nim_nid', $nim)
            ->first();

        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Data pengajuan tidak ditemukan');
        }

        return view('verifikasi_judul', compact('mahasiswa', 'pengajuan'));
    }

    // Setujui judul
    public function setujui(Request $request)
    {
        $pengajuan = DB::table('pengajuan_judul')
            ->where('nim_nid', $request->nim)
            ->first();

        $judulDipilih = '';
        switch ($request->judul_ke) {
            case 1:
                $judulDipilih = $pengajuan->judul_1;
                break;
            case 2:
                $judulDipilih = $pengajuan->judul_2;
                break;
            case 3:
                $judulDipilih = $pengajuan->judul_3;
                break;
        }

        DB::table('pengajuan_judul')
            ->where('nim_nid', $request->nim)
            ->update([
                'status' => 'disetujui',
                'judul_disetujui' => $judulDipilih,
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Judul berhasil disetujui!'
        ]);
    }

    // Tolak judul
    public function tolak(Request $request)
    {
        DB::table('pengajuan_judul')
            ->where('nim_nid', $request->nim)
            ->update([
                'status' => 'ditolak',
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan ditolak!'
        ]);
    }
}