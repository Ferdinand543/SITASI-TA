<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    public function index()
    {
        // =========================
        // CEK LOGIN
        // =========================
        if (!session('user')) {
            return redirect('/login')
                ->with('error', 'Silakan login dulu!');
        }

        $user = session('user');

        // =========================
        // CEK APAKAH KOORDINATOR
        // =========================
        $koordinator = DB::table('dosen_roles')
            ->where('nim_nid', $user->nim_nid)
            ->where('role_dosen', 'koordinator')
            ->exists();

        // =========================
        // JIKA BUKAN KOORDINATOR
        // =========================
        if (!$koordinator) {

            if ($user->role == 'mahasiswa') {
                return redirect('/mahasiswa')
                    ->with('error', 'Akses ditolak!');
            }

            return redirect('/dashboard/dosen')
                ->with('error', 'Akses ditolak!');
        }

        // =========================
        // AMBIL DATA PENGAJUAN
        // =========================
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
        // =========================
        // CEK LOGIN
        // =========================
        if (!session('user')) {
            return redirect('/login')
                ->with('error', 'Silakan login dulu!');
        }

        $user = session('user');

        // =========================
        // CEK KOORDINATOR
        // =========================
        $koordinator = DB::table('dosen_roles')
            ->where('nim_nid', $user->nim_nid)
            ->where('role_dosen', 'koordinator')
            ->exists();

        if (!$koordinator) {
            return redirect('/dashboard/dosen')
                ->with('error', 'Akses ditolak!');
        }

        // =========================
        // AMBIL DATA PENGAJUAN
        // =========================
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
        // =========================
        // CEK LOGIN
        // =========================
        if (!session('user')) {
            return redirect('/login')
                ->with('error', 'Silakan login dulu!');
        }

        $user = session('user');

        // =========================
        // CEK KOORDINATOR
        // =========================
        $koordinator = DB::table('dosen_roles')
            ->where('nim_nid', $user->nim_nid)
            ->where('role_dosen', 'koordinator')
            ->exists();

        if (!$koordinator) {
            return redirect('/dashboard/dosen')
                ->with('error', 'Akses ditolak!');
        }

        // =========================
        // VALIDASI
        // =========================
        $request->validate([
            'judul_disetujui' => 'nullable'
        ]);

        // =========================
        // AMBIL DATA
        // =========================
        $data = DB::table('pengajuan_judul')
            ->where('id', $id)
            ->first();

        $judulDipilih = null;

        // =========================
        // PILIH JUDUL
        // =========================
        if ($request->judul_disetujui == 1) {
            $judulDipilih = $data->judul_1;
        }

        if ($request->judul_disetujui == 2) {
            $judulDipilih = $data->judul_2;
        }

        if ($request->judul_disetujui == 3) {
            $judulDipilih = $data->judul_3;
        }

        // =========================
        // STATUS
        // =========================
        $status = $judulDipilih
            ? 'disetujui'
            : 'ditolak';

        // =========================
        // UPDATE DATA
        // =========================
        DB::table('pengajuan_judul')
            ->where('id', $id)
            ->update([
                'status'           => $status,
                'judul_disetujui'  => $judulDipilih,
                'updated_at'       => now()
            ]);

        // =========================
        // PESAN
        // =========================
        $pesan = $judulDipilih
            ? 'Judul berhasil disetujui'
            : 'Semua judul berhasil ditolak';

        return redirect('/pengajuan')
            ->with('success', $pesan);
    }
}