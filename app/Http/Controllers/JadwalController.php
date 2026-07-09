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
        // ✅ CEK APAKAH DOSEN INI KOORDINATOR
        // =====================================================
        $isKoordinator = false;
        if ($role === 'dosen') {
            $isKoordinator = DB::table('dosen_roles')
                ->where('nim_nid', $nim)
                ->where('role_dosen', 'koordinator') // sesuai typo di database
                ->exists();
        }

        // =====================================================
        // ✅ CEK APAKAH DOSEN INI PEMBIMBING/PENGUJI AKTIF
        // (untuk munculkan card "Jadwal Seminar Mahasiswa")
        // =====================================================
        $isPembimbingAtauPenguji = false;
        if ($role === 'dosen') {
            $adaPembimbing = DB::table('dosen_pembimbing')
                ->where('nim_nid_dosen', $nim)
                ->exists();

            $adaPenguji = DB::table('dosen_penguji_seminar')
                ->where('nim_nid_dosen', $nim)
                ->exists();

            $isPembimbingAtauPenguji = $adaPembimbing || $adaPenguji;
        }

        // =====================================================
        // ✅ AUTO UPDATE STATUS — PALING ATAS SEBELUM SEMUA QUERY
        // =====================================================
        $today = now()->toDateString();
        DB::table('jadwal_akademik')
            ->where('status', '!=', 'Ditutup')
            ->get()
            ->each(function ($j) use ($today) {
                $mulai   = $j->tanggal;
                $selesai = $j->tanggal_selesai ?? $j->tanggal;
                if ($today < $mulai)                             $status = 'Akan Datang';
                elseif ($today >= $mulai && $today <= $selesai)  $status = 'Berlangsung';
                else                                             $status = 'Selesai';
                if ($j->status !== $status) {
                    DB::table('jadwal_akademik')->where('id', $j->id)->update(['status' => $status]);
                }
            });

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
        // ✅ JUMLAH MAHASISWA — untuk card Siap Dijadwalkan
        // ✅ DIUBAH: sekarang ikut syarat sudah ada dosen penguji ditetapkan
        // =====================================================
        $jumlahMahasiswaSiapSeminar = DB::table('pengajuan_seminars')
            ->where('is_draft', 0)
            ->whereIn('status_seminar', ['Menunggu Jadwal', 'Sudah Dijadwalkan', 'Selesai'])
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('dosen_penguji_seminar as dps')
                  ->whereColumn('dps.pengajuan_seminar_id', 'pengajuan_seminars.id');
            })
            ->count();

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
                'Review Proposal',
                'Penetapan Dosen Pembimbing',
                'Bimbingan Tugas Akhir',
            ];

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
                    $tanggalMulai   = $match->tanggal;
                    $tanggalSelesai = $match->tanggal_selesai ?? $match->tanggal;
                    $keterangan     = $match->nama_kegiatan;

                    if ($today < $tanggalMulai) {
                        $status = 'mendatang';
                    } elseif ($today >= $tanggalMulai && $today <= $tanggalSelesai) {
                        $status = 'aktif';
                    } else {
                        $status = 'selesai';
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
            'user', 'role', 'totalJadwal', 'akanDatang', 'berlangsung', 'selesaiCount',
            'isKoordinator', 'jumlahMahasiswaSiapSeminar',
            'isPembimbingAtauPenguji'
        ));
    }
}