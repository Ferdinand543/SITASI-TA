<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalAkademik;

class JadwalAkademikController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalAkademik::query();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_kegiatan', 'like', '%' . $request->search . '%')
                  ->orWhere('kategori', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi', 'like', '%' . $request->search . '%');
            });
        }

        // Filter status
        if ($request->status && $request->status != 'Semua Status') {
            $query->where('status', $request->status);
        }

        $jadwals = $query->orderBy('id', 'desc')->paginate(10);

        // Statistik
        $totalKegiatan = JadwalAkademik::count();

        $kegiatanAktif = JadwalAkademik::where(
            'status',
            'Berlangsung'
        )->count();

        $seminarMendatang = JadwalAkademik::where(
            'kategori',
            'Seminar'
        )->where(
            'status',
            'Akan Datang'
        )->count();

        $deadlineBerakhir = JadwalAkademik::where(
            'status',
            'Ditutup'
        )->count();

        return view('admin.jadwal.index', compact(
            'jadwals',
            'totalKegiatan',
            'kegiatanAktif',
            'seminarMendatang',
            'deadlineBerakhir'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'kategori'      => 'required',
            'status'        => 'required',
            'tanggal'       => 'required',
            'waktu'         => 'required',
        ]);

        JadwalAkademik::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'sub_judul'     => $request->sub_judul,
            'kategori'      => $request->kategori,
            'status'        => $request->status,
            'tanggal'       => $request->tanggal,
            'waktu'         => $request->waktu,
            'lokasi'        => $request->lokasi,
            'deskripsi'     => $request->deskripsi,
        ]);

        return redirect()->back()
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'kategori'      => 'required',
            'status'        => 'required',
            'tanggal'       => 'required',
            'waktu'         => 'required',
        ]);

        $jadwal = JadwalAkademik::findOrFail($id);

        $jadwal->update([
            'nama_kegiatan' => $request->nama_kegiatan,
            'sub_judul'     => $request->sub_judul,
            'kategori'      => $request->kategori,
            'status'        => $request->status,
            'tanggal'       => $request->tanggal,
            'waktu'         => $request->waktu,
            'lokasi'        => $request->lokasi,
            'deskripsi'     => $request->deskripsi,
        ]);

        return redirect()->back()
            ->with('success', 'Jadwal berhasil diupdate');
    }

    public function destroy($id)
    {
        $jadwal = JadwalAkademik::findOrFail($id);

        $jadwal->delete();

        return redirect()->back()
            ->with('success', 'Jadwal berhasil dihapus');
    }
}