<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        if (!session('user')) return redirect('/login');

        $user = session('user');
        $nim  = $user->nim_nid;
        $role = $user->role;

        // =====================================================
        // JADWAL AKADEMIK
        // =====================================================
        $query = DB::table('jadwal_akademik')->orderBy('tanggal', 'asc');

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('search')) {
            $query->where('nama_kegiatan', 'like', '%' . $request->search . '%');
        }

        $jadwal = $query->get();

        // Jadwal terdekat
        $terdekat = DB::table('jadwal_akademik')
            ->where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->first();

        // Ringkasan
        $totalJadwal  = DB::table('jadwal_akademik')->count();
        $akanDatang   = DB::table('jadwal_akademik')->where('status', 'Akan Datang')->count();
        $berlangsung  = DB::table('jadwal_akademik')->where('status', 'Berlangsung')->count();
        $selesaiCount = DB::table('jadwal_akademik')->where('status', 'Selesai')->count();

        // =====================================================
        // TIMELINE — hanya untuk mahasiswa
        // =====================================================
        $timeline       = [];
        $progressPersen = 0;

        if ($role === 'mahasiswa') {

            $tahapUrutan = [
                'Pengajuan Judul',
                'Verifikasi Judul',
                'Upload Proposal',
                'Penetapan Dosen Pembimbing',
                'Review Proposal',
                'Bimbingan Tugas Akhir',
            ];

            $today = now('Asia/Jakarta')->toDateString();

            // Keyword matching: nama_kegiatan di jadwal_akademik → tahap timeline
            // Sesuaikan keyword dengan nama kegiatan yang diinput admin
            $keywordMap = [
                'Pengajuan Judul'            => ['pengajuan judul'],
                'Verifikasi Judul'           => ['verifikasi judul'],
                'Upload Proposal'            => ['upload proposal'],
                'Penetapan Dosen Pembimbing' => ['penetapan dosen', 'dosen pembimbing'],
                'Review Proposal'            => ['review proposal'],
                'Bimbingan Tugas Akhir'      => ['bimbingan tugas akhir', 'bimbingan mahasiswa'],
            ];

            // Ambil semua jadwal akademik sekali saja
            $jadwalAkademikAll = DB::table('jadwal_akademik')->get();

            foreach ($tahapUrutan as $tahap) {
                $keywords = $keywordMap[$tahap];

                // Cari jadwal yang nama_kegiatannya mengandung keyword tahap ini
                $match = $jadwalAkademikAll->first(function ($j) use ($keywords) {
                    foreach ($keywords as $kw) {
                        if (str_contains(strtolower($j->nama_kegiatan), $kw)) {
                            return true;
                        }
                    }
                    return false;
                });

                if (!$match) {
                    // Tidak ada jadwal yang match → belum dijadwalkan admin
                    $status     = 'belum';
                    $tanggal    = null;
                    $keterangan = null;
                } else {
                    $tanggal    = $match->tanggal;
                    $keterangan = $match->nama_kegiatan;

                    if ($match->tanggal < $today) {
                        $status = 'selesai';    // tanggal sudah lewat → hijau
                    } elseif ($match->tanggal === $today) {
                        $status = 'aktif';      // hari ini → kuning
                    } else {
                        $status = 'mendatang';  // belum waktunya → abu
                    }
                }

                $timeline[] = [
                    'label'      => $tahap,
                    'status'     => $status,
                    'tanggal'    => $tanggal,
                    'keterangan' => $keterangan,
                ];
            }

            $selesaiTahap   = collect($timeline)->where('status', 'selesai')->count();
            $progressPersen = round(($selesaiTahap / count($tahapUrutan)) * 100);
        }

        return view('jadwal', compact(
            'jadwal', 'terdekat', 'timeline', 'progressPersen',
            'user', 'role', 'totalJadwal', 'akanDatang', 'berlangsung', 'selesaiCount'
        ));
    }
}