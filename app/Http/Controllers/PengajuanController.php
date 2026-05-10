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
}