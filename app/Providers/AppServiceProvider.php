<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use App\Models\ProposalMahasiswa;
use App\Models\PengajuanJudul;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useBootstrap();

        View::composer('*', function ($view) {
            $user = session('user');

            $notifJudulMahasiswa     = 0;
            $notifProposalMahasiswa  = 0;
            $notifBimbinganMahasiswa = 0;
            $notifSeminarMahasiswa   = 0;
            $notifNilaiMahasiswa     = 0;
            $notifLayakMahasiswa     = 0; // 🔵 BARU

            if ($user && strtolower($user->role) === 'mahasiswa') {
                $nim = $user->nim_nid;

                // ── Notif Pengajuan Judul ──
                $totalJudulDirespon = PengajuanJudul::where('nim_nid', $nim)
                    ->whereIn('status', ['disetujui', 'ditolak'])
                    ->count();

                $sudahDibacaJudul = session('notif_judul_terakhir_' . $nim, 0);
                $notifJudulMahasiswa = ($totalJudulDirespon > $sudahDibacaJudul)
                    ? $totalJudulDirespon - $sudahDibacaJudul
                    : 0;

                // ── Notif Proposal ──
                $totalProposalDirespon = ProposalMahasiswa::where('nim_nid', $nim)
                    ->whereIn('status', ['selesai', 'ditolak', 'disetujui'])
                    ->count();

                $sudahDibacaProposal = session('notif_proposal_terakhir_' . $nim, 0);
                $notifProposalMahasiswa = ($totalProposalDirespon > $sudahDibacaProposal)
                    ? $totalProposalDirespon - $sudahDibacaProposal
                    : 0;

                // ── Notif Riwayat Bimbingan ──
                // Notif muncul kalau ada bimbingan yang sudah divalidasi dosen
                // (status_validasi terisi: 'Valid' atau 'Tidak Valid') tapi belum dilihat mahasiswa.
                $totalBimbinganDirespon = DB::table('bimbingan')
                    ->where('nim_nid', $nim)
                    ->whereIn('status_validasi', ['Valid', 'Tidak Valid'])
                    ->count();

                $sudahDibacaBimbingan = session('notif_bimbingan_terakhir_' . $nim, 0);
                $notifBimbinganMahasiswa = ($totalBimbinganDirespon > $sudahDibacaBimbingan)
                    ? $totalBimbinganDirespon - $sudahDibacaBimbingan
                    : 0;

                // ── Notif Kelayakan Seminar (TTD Pembimbing 1) ── 🔵 BARU
                // Notif muncul kalau status_pembimbing1 = 'layak' (banner ijo "Dinyatakan Layak")
                // tapi belum dilihat mahasiswa. Badge ini ditampilkan bareng dengan
                // notifBimbinganMahasiswa di card "Riwayat Bimbingan" pada dashboard.
                $totalLayakDirespon = DB::table('pengajuan_seminars')
                    ->where('mahasiswa_id', $nim)
                    ->where('status_pembimbing1', 'layak')
                    ->count();

                $sudahDibacaLayak = session('notif_layak_terakhir_' . $nim, 0);
                $notifLayakMahasiswa = ($totalLayakDirespon > $sudahDibacaLayak)
                    ? $totalLayakDirespon - $sudahDibacaLayak
                    : 0;

                // ── Notif Daftar Seminar ──
                // Notif muncul kalau status_seminar berubah ke status yang relevan untuk mahasiswa lihat.
                $totalSeminarDirespon = DB::table('pengajuan_seminars')
                    ->where('mahasiswa_id', $nim)
                    ->whereIn('status_seminar', ['Lolos Administrasi', 'Menunggu Jadwal', 'Selesai', 'Ditolak'])
                    ->count();

                $sudahDibacaSeminar = session('notif_seminar_terakhir_' . $nim, 0);
                $notifSeminarMahasiswa = ($totalSeminarDirespon > $sudahDibacaSeminar)
                    ? $totalSeminarDirespon - $sudahDibacaSeminar
                    : 0;

                // ── Notif Nilai ──
                // Notif muncul kalau ada nilai seminar yang sudah final (status submitted / kelayakan terisi).
                $totalNilaiDirespon = DB::table('penilaian_seminar')
                    ->where('nim_nid', $nim)
                    ->whereNotNull('kelayakan')
                    ->count();

                $sudahDibacaNilai = session('notif_nilai_terakhir_' . $nim, 0);
                $notifNilaiMahasiswa = ($totalNilaiDirespon > $sudahDibacaNilai)
                    ? $totalNilaiDirespon - $sudahDibacaNilai
                    : 0;

                // ══ DEBUG SEMENTARA — hapus setelah selesai testing ══
                if (env('APP_DEBUG_NOTIF', false) && $nim === env('APP_DEBUG_NOTIF_NIM', '')) {
                    Log::info('DEBUG NOTIF SEMUA', [
                        'nim'                     => $nim,
                        'notif_judul_final'       => $notifJudulMahasiswa,
                        'notif_proposal_final'    => $notifProposalMahasiswa,
                        'notif_bimbingan_final'   => $notifBimbinganMahasiswa,
                        'notif_layak_final'       => $notifLayakMahasiswa, // 🔵 BARU
                        'notif_seminar_final'     => $notifSeminarMahasiswa,
                        'notif_nilai_final'       => $notifNilaiMahasiswa,
                        'session_id'              => session()->getId(),
                    ]);
                }
            }

            $view->with([
                'jumlahMenungguProposal'  => ProposalMahasiswa::whereRaw('LOWER(status) LIKE ?', ['%menunggu%'])->count(),
                'jumlahMenungguJudul'     => PengajuanJudul::whereRaw('LOWER(status) LIKE ?', ['%menunggu%'])->count(),
                'notifJudulMahasiswa'     => $notifJudulMahasiswa,
                'notifProposalMahasiswa'  => $notifProposalMahasiswa,
                'notifBimbinganMahasiswa' => $notifBimbinganMahasiswa,
                'notifLayakMahasiswa'     => $notifLayakMahasiswa, // 🔵 BARU
                'notifSeminarMahasiswa'   => $notifSeminarMahasiswa,
                'notifNilaiMahasiswa'     => $notifNilaiMahasiswa,
            ]);
        });
    }
}