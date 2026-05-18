<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanJudul;

class PengajuanMahasiswaController extends Controller
{
    public function index()
    {
        if (!session('user')) {
            return redirect('/login')->with('error', 'Silakan login dulu!');
        }

        $nim = session('user')->nim_nid;

        $totalDirespon = PengajuanJudul::where('nim_nid', $nim)
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->count();
        session()->put('notif_judul_terakhir_' . $nim, $totalDirespon);

        $pengajuanList = PengajuanJudul::where('nim_nid', $nim)->latest()->get();

        return view('pengajuan.pengajuan_mahasiswa', compact('pengajuanList'));
    }

    public function store(Request $request)
    {
        if (!session('user')) {
            return redirect('/login')->with('error', 'Silakan login dulu!');
        }

        $request->validate([
            'tanggal_pengajuan' => 'required|date',
            'topik_1'           => 'required|string|max:255',
            'judul_1'           => 'required|string|max:255',
            'mitra_1'           => 'nullable|string|max:255',
            'topik_2'           => 'required|string|max:255',
            'judul_2'           => 'required|string|max:255',
            'mitra_2'           => 'nullable|string|max:255',
            'topik_3'           => 'required|string|max:255',
            'judul_3'           => 'required|string|max:255',
            'mitra_3'           => 'nullable|string|max:255',
        ]);

        PengajuanJudul::create([
            'nim_nid'           => session('user')->nim_nid,
            'topik_1'           => $request->topik_1,
            'judul_1'           => $request->judul_1,
            'mitra_1'           => $request->mitra_1,
            'topik_2'           => $request->topik_2,
            'judul_2'           => $request->judul_2,
            'mitra_2'           => $request->mitra_2,
            'topik_3'           => $request->topik_3,
            'judul_3'           => $request->judul_3,
            'mitra_3'           => $request->mitra_3,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'status'            => 'menunggu verifikasi',
        ]);

        return redirect()->route('pengajuan.mahasiswa')->with('success', 'Pengajuan berhasil dikirim!');
    }

    public function detail($id)
    {
        if (!session('user')) {
            return redirect('/login')->with('error', 'Silakan login dulu!');
        }

        $nim = session('user')->nim_nid;

        $totalDirespon = PengajuanJudul::where('nim_nid', $nim)
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->count();
        session()->put('notif_judul_terakhir_' . $nim, $totalDirespon);

        $pengajuan = PengajuanJudul::where('id', $id)
            ->where('nim_nid', $nim)
            ->firstOrFail();

        return view('pengajuan.detail', compact('pengajuan'));
    }
}