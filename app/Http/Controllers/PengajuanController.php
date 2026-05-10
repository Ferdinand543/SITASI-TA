<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    public function index()
    {
        if (!session('user')) {
            return redirect('/login')->with('error', 'Silakan login dulu!');
        }

        $role = strtolower(trim(session('user')->role));

        if ($role !== 'koordinator') {
            if ($role === 'mahasiswa') {
                return redirect('/mahasiswa')->with('error', 'Akses ditolak!');
            }
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $pengajuans = DB::table('pengajuan_judul')
            ->join('users', 'pengajuan_judul.nim_nid', '=', 'users.nim_nid')
            ->select(
                'pengajuan_judul.id',
                'pengajuan_judul.nim_nid',
                'users.nama',
                'pengajuan_judul.judul_1',
                'pengajuan_judul.judul_2',
                'pengajuan_judul.judul_3',
                'pengajuan_judul.judul_disetujui',
                'pengajuan_judul.tanggal_pengajuan',
                'pengajuan_judul.status'
            )
            ->orderBy('pengajuan_judul.tanggal_pengajuan', 'asc')
            ->get();

        return view('pengajuan.index', compact('pengajuans'));
    }

    public function verifikasi($id)
    {
        $pengajuan = DB::table('pengajuan_judul')
            ->join('users', 'pengajuan_judul.nim_nid', '=', 'users.nim_nid')
            ->select(
                'pengajuan_judul.*',
                'users.nama'
            )
            ->where('pengajuan_judul.id', $id)
            ->first();

        return view('pengajuan.verifikasi', compact('pengajuan'));
    }

    public function prosesVerifikasi(Request $request, $id)
    {
        // judul_disetujui nullable — boleh kosong kalau semua ditolak
        $request->validate([
            'judul_disetujui' => 'nullable'
        ]);

        $data = DB::table('pengajuan_judul')
            ->where('id', $id)
            ->first();

        $judulDipilih = null;

        if ($request->judul_disetujui == 1) {
            $judulDipilih = $data->judul_1;
        }

        if ($request->judul_disetujui == 2) {
            $judulDipilih = $data->judul_2;
        }

        if ($request->judul_disetujui == 3) {
            $judulDipilih = $data->judul_3;
        }

        // Kalau ada judul dipilih → disetujui, kalau tidak → ditolak
        $status = $judulDipilih ? 'disetujui' : 'ditolak';

        DB::table('pengajuan_judul')
            ->where('id', $id)
            ->update([
                'status'          => $status,
                'judul_disetujui' => $judulDipilih,
                'updated_at'      => now()
            ]);

        $pesan = $judulDipilih
            ? 'Judul berhasil disetujui'
            : 'Semua judul berhasil ditolak';

        return redirect('/pengajuan')->with('success', $pesan);
    }
}