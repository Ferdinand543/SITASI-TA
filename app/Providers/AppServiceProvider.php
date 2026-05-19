<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\ProposalMahasiswa;
use App\Models\PengajuanJudul;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $user = session('user');

            // ── Notif untuk mahasiswa ──
            $notifJudulMahasiswa    = 0;
            $notifProposalMahasiswa = 0;

            if ($user && strtolower($user->role) === 'mahasiswa') {
                $nim = $user->nim_nid;

                // Hitung jumlah pengajuan yang sudah direspon
                $totalJudulDirespon = PengajuanJudul::where('nim_nid', $nim)
                    ->whereIn('status', ['disetujui', 'ditolak'])
                    ->count();

                // Badge muncul kalau jumlah yg direspon BERBEDA dari yg terakhir dibaca
                $sudahDibacaJudul = session('notif_judul_terakhir_' . $nim, 0);
                $notifJudulMahasiswa = ($totalJudulDirespon > $sudahDibacaJudul) ? $totalJudulDirespon - $sudahDibacaJudul : 0;

                // Hitung jumlah proposal yang sudah direspon
                $totalProposalDirespon = ProposalMahasiswa::where('nim_nid', $nim)
                    ->whereIn('status', ['selesai', 'ditolak', 'disetujui'])
                    ->count();

                $sudahDibacaProposal = session('notif_proposal_terakhir_' . $nim, 0);
                $notifProposalMahasiswa = ($totalProposalDirespon > $sudahDibacaProposal) ? $totalProposalDirespon - $sudahDibacaProposal : 0;
            }

            $view->with([
                // ── Untuk dosen/admin/koordinator ──
                'jumlahMenungguProposal' => ProposalMahasiswa::whereRaw('LOWER(status) LIKE ?', ['%menunggu%'])->count(),
                'jumlahMenungguJudul'    => PengajuanJudul::whereRaw('LOWER(status) LIKE ?', ['%menunggu%'])->count(),

                // ── Untuk mahasiswa ──
                'notifJudulMahasiswa'    => $notifJudulMahasiswa,
                'notifProposalMahasiswa' => $notifProposalMahasiswa,
            ]);
        });
    }
}
