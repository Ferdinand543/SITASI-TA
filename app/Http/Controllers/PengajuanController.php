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
        $request->validate([
            'judul_disetujui' => 'required'
        ]);

        $data = DB::table('pengajuan_judul')
            ->where('id', $id)
            ->first();

        $judulDipilih = '';

        if ($request->judul_disetujui == 1) {
            $judulDipilih = $data->judul_1;
        }

        if ($request->judul_disetujui == 2) {
            $judulDipilih = $data->judul_2;
        }

        if ($request->judul_disetujui == 3) {
            $judulDipilih = $data->judul_3;
        }

        DB::table('pengajuan_judul')
            ->where('id', $id)
            ->update([
                'status' => 'disetujui',
                'judul_disetujui' => $judulDipilih,
                'updated_at' => now()
            ]);

        return redirect('/pengajuan')
            ->with('success', 'Judul berhasil diverifikasi');
    }
}