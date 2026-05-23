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

            $keywordMap = [
                'Pengajuan Judul'            => ['pengajuan judul'],
                'Verifikasi Judul'           => ['verifikasi judul'],
                'Upload Proposal'            => ['upload proposal'],
                'Penetapan Dosen Pembimbing' => ['penetapan dosen', 'dosen pembimbing'],
                'Review Proposal'            => ['review proposal'],
                'Bimbingan Tugas Akhir'      => ['bimbingan tugas akhir', 'bimbingan mahasiswa'],
            ];

            $jadwalAkademikAll = DB::table('jadwal_akademik')->get();

            foreach ($tahapUrutan as $tahap) {
                $keywords = $keywordMap[$tahap];

                $match = $jadwalAkademikAll->first(function ($j) use ($keywords) {
                    foreach ($keywords as $kw) {
                        if (str_contains(strtolower($j->nama_kegiatan), $kw)) {
                            return true;
                        }
                    }
                    return false;
                });

                if (!$match) {
                    $status          = 'belum';
                    $tanggal         = null;
                    $tanggal_selesai = null;
                    $keterangan      = null;
                } else {
                    $tanggalMulai    = $match->tanggal;
                    // Kalau tanggal_selesai tidak diisi, anggap sama dengan tanggal mulai
                    $tanggalSelesai  = $match->tanggal_selesai ?? $match->tanggal;
                    $keterangan      = $match->nama_kegiatan;

                    if ($today < $tanggalMulai) {
                        $status = 'mendatang'; // belum waktunya → abu
                    } elseif ($today >= $tanggalMulai && $today <= $tanggalSelesai) {
                        $status = 'aktif';     // dalam rentang → kuning
                    } else {
                        $status = 'selesai';   // sudah lewat tanggal selesai → hijau
                    }

                    $tanggal         = $tanggalMulai;
                    $tanggal_selesai = $match->tanggal_selesai ?? null;
                }

                $timeline[] = [
                    'label'           => $tahap,
                    'status'          => $status,
                    'tanggal'         => $tanggal,
                    'tanggal_selesai' => $tanggal_selesai,
                    'keterangan'      => $keterangan,
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