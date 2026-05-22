<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
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

            $notifJudulMahasiswa    = 0;
            $notifProposalMahasiswa = 0;

            if ($user && strtolower($user->role) === 'mahasiswa') {
                $nim = $user->nim_nid;

                $totalJudulDirespon = PengajuanJudul::where('nim_nid', $nim)
                    ->whereIn('status', ['disetujui', 'ditolak'])
                    ->count();

                $sudahDibacaJudul = session('notif_judul_terakhir_' . $nim, 0);
                $notifJudulMahasiswa = ($totalJudulDirespon > $sudahDibacaJudul) ? $totalJudulDirespon - $sudahDibacaJudul : 0;

                $totalProposalDirespon = ProposalMahasiswa::where('nim_nid', $nim)
                    ->whereIn('status', ['selesai', 'ditolak', 'disetujui'])
                    ->count();

                $sudahDibacaProposal = session('notif_proposal_terakhir_' . $nim, 0);
                $notifProposalMahasiswa = ($totalProposalDirespon > $sudahDibacaProposal) ? $totalProposalDirespon - $sudahDibacaProposal : 0;
            }

            $view->with([
                'jumlahMenungguProposal' => ProposalMahasiswa::whereRaw('LOWER(status) LIKE ?', ['%menunggu%'])->count(),
                'jumlahMenungguJudul'    => PengajuanJudul::whereRaw('LOWER(status) LIKE ?', ['%menunggu%'])->count(),
                'notifJudulMahasiswa'    => $notifJudulMahasiswa,
                'notifProposalMahasiswa' => $notifProposalMahasiswa,
            ]);
        });
    }
}