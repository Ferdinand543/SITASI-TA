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

            // Cek data existing mahasiswa
            $pengajuan = DB::table('pengajuan_judul')
                ->where('nim_nid', $nim)
                ->latest('updated_at')
                ->first();

            $proposal = DB::table('proposal')
                ->where('nim_nid', $nim)
                ->latest('created_at')
                ->first();

            $dosenDitetapkan = DB::table('dosen_pembimbing')
                ->join('proposal', 'proposal.id', '=', 'dosen_pembimbing.proposal_id')
                ->where('proposal.nim_nid', $nim)
                ->exists();

            $adaBimbingan = DB::table('bimbingan')
                ->where('nim_nid', $nim)
                ->exists();

            // Tentukan status otomatis per tahap
            $autoStatus = [
                'Pengajuan Judul' =>
                    $pengajuan ? 'selesai' : 'belum',

                'Verifikasi Judul' =>
                    ($pengajuan && $pengajuan->status === 'disetujui') ? 'selesai'
                    : ($pengajuan ? 'aktif' : 'belum'),

                'Upload Proposal' =>
                    $proposal ? 'selesai'
                    : (($pengajuan && $pengajuan->status === 'disetujui') ? 'aktif' : 'belum'),

                'Penetapan Dosen Pembimbing' =>
                    $dosenDitetapkan ? 'selesai'
                    : ($proposal ? 'aktif' : 'belum'),

                'Review Proposal' =>
                    ($proposal && isset($proposal->status) && in_array($proposal->status, ['disetujui', 'diterima'])) ? 'selesai'
                    : ($dosenDitetapkan ? 'aktif' : 'belum'),

                'Bimbingan Tugas Akhir' =>
                    $adaBimbingan ? 'aktif' : 'belum',
            ];

            // Pastikan row progress_ta ada untuk mahasiswa ini
            $existingTahap = DB::table('progress_ta')
                ->where('nim_nid', $nim)
                ->pluck('tahap')
                ->toArray();

            foreach ($tahapUrutan as $tahap) {
                if (!in_array($tahap, $existingTahap)) {
                    DB::table('progress_ta')->insert([
                        'nim_nid'    => $nim,
                        'tahap'      => $tahap,
                        'status'     => 'belum',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Update status otomatis ke DB
            foreach ($autoStatus as $tahap => $status) {
                DB::table('progress_ta')
                    ->where('nim_nid', $nim)
                    ->where('tahap', $tahap)
                    ->update(['status' => $status, 'updated_at' => now()]);
            }

            // Ambil data terbaru dari DB (termasuk tanggal & keterangan dari admin)
            $progressDb = DB::table('progress_ta')
                ->where('nim_nid', $nim)
                ->get()
                ->keyBy('tahap');

            foreach ($tahapUrutan as $tahap) {
                $data = $progressDb->get($tahap);
                $timeline[] = [
                    'label'      => $tahap,
                    'status'     => $data->status ?? 'belum',
                    'tanggal'    => $data->tanggal ?? null,
                    'keterangan' => $data->keterangan ?? null,
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
