<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        // =========================
        // CEK LOGIN
        // =========================
        if (!session('user')) {
            return redirect('/login')
                ->with('error', 'Silakan login dulu!');
        }

        $sekarang = now()->toDateString();

        // =========================
        // AMBIL SEMUA JADWAL + FILTER BULAN + SEARCH
        // =========================
        $query = DB::table('jadwal_akademik')
            ->orderBy('tanggal', 'asc');

        if ($request->filled('bulan')) {
            $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$request->bulan]);
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nama_kegiatan', 'like', "%{$cari}%")
                  ->orWhere('sub_judul',   'like', "%{$cari}%")
                  ->orWhere('lokasi',      'like', "%{$cari}%");
            });
        }

        $jadwals = $query->get();

        // =========================
        // UPDATE STATUS OTOMATIS
        // =========================
        foreach ($jadwals as $jadwal) {
            if ($sekarang < $jadwal->tanggal) {
                $statusBaru = 'Akan Datang';
            } elseif ($sekarang == $jadwal->tanggal) {
                $statusBaru = 'Berlangsung';
            } else {
                $statusBaru = 'Selesai';
            }

            if ($jadwal->status !== $statusBaru) {
                DB::table('jadwal_akademik')
                    ->where('id', $jadwal->id)
                    ->update(['status' => $statusBaru]);

                $jadwal->status = $statusBaru;
            }
        }

        // =========================
        // AMBIL JADWAL TERDEKAT
        // = yang akan datang SETELAH hari ini (bukan yang berlangsung hari ini)
        // tanpa pengaruh filter search/bulan
        // =========================
        $terdekat = DB::table('jadwal_akademik')
            ->where('tanggal', '>', $sekarang)
            ->orderBy('tanggal', 'asc')
            ->first();

        $hariLagi = null;
        if ($terdekat) {
            $hariLagi = now()->startOfDay()->diffInDays(
                \Carbon\Carbon::parse($terdekat->tanggal)->startOfDay()
            );
        }

        return view('dosen.jadwal', compact('jadwals', 'terdekat', 'hariLagi'));
    }
}