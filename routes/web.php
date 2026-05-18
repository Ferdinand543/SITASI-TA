<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PengajuanMahasiswaController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ProposalMahasiswaController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\PanduanTAController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\BimbinganController;
use App\Http\Controllers\DosenBimbinganController;
use App\Http\Controllers\MahasiswaDosenController;

// ROOT
Route::get('/', fn() => redirect('/login'));

// AUTH
Route::get('/login',          [AuthController::class, 'showLogin']);
Route::post('/login',         [AuthController::class, 'login']);
Route::get('/logout', function () {
    session()->forget('user');
    return redirect('/login')->with('success', 'Berhasil logout');
});
Route::get('/forgot-password',                    fn() => view('auth.forgotpass'));
Route::post('/forgot-password',                   [AuthController::class, 'sendResetLink']);
Route::get('/reset-password/{token}',             [AuthController::class, 'showResetForm'])->name('password.reset');
Route::get('/reset-success', fn() => view('auth.reset_success'));
Route::post('/reset-password',                    [AuthController::class, 'doResetPassword']);


// =====================================================
// DASHBOARD
// =====================================================

Route::get('/dashboard/dosen', function () {
    if (!session('user')) return redirect('/login');
    return view('dosen.dashboard');
});

Route::get('/admin', function () {
    if (!session('user')) return redirect('/login');
    return view('admin.admin');
});

Route::get('/mahasiswa', function () {
    if (!session('user')) return redirect('/login');

    $user = session('user');
    $nim  = $user->nim_nid;

    // ── Stat counts ──
    $totalPengajuan = DB::table('pengajuan_judul')->where('nim_nid', $nim)->count();
    $totalProposal  = DB::table('proposal')->where('nim_nid', $nim)->count();
    $totalBimbingan = DB::table('bimbingan')->where('nim_nid', $nim)->count();

    // ── Judul yang disetujui ──
    $judulDisetujui = DB::table('pengajuan_judul')
        ->where('nim_nid', $nim)
        ->where('status', 'disetujui')
        ->latest('updated_at')
        ->first();

    // ── Dosen pembimbing ──
    $namaDosen1 = null;
    $namaDosen2 = null;

    $proposal = DB::table('proposal')
        ->where('nim_nid', $nim)
        ->latest()
        ->first();

    if ($proposal) {
        $dosbing1 = DB::table('dosen_pembimbing')
            ->where('proposal_id', $proposal->id)
            ->where('urutan', 1)->first();
        $dosbing2 = DB::table('dosen_pembimbing')
            ->where('proposal_id', $proposal->id)
            ->where('urutan', 2)->first();

        $namaDosen1 = $dosbing1
            ? DB::table('users')->where('nim_nid', $dosbing1->nim_nid_dosen)->value('nama')
            : null;
        $namaDosen2 = $dosbing2
            ? DB::table('users')->where('nim_nid', $dosbing2->nim_nid_dosen)->value('nama')
            : null;
    }

    // ── TARGET BIMBINGAN: 6 per dosen × 2 = 12 ──
    $targetBimbingan = 12;

    // ── AKTIVITAS TERBARU (realtime dari DB) ──

    // Bimbingan terbaru (max 2)
    $bimbinganTerbaru = DB::table('bimbingan')
        ->where('nim_nid', $nim)
        ->latest('created_at')
        ->limit(2)
        ->get()
        ->map(fn($b) => (object)[
            'teks'  => 'Bimbingan ke-' . $b->pertemuan_ke . ': ' . \Illuminate\Support\Str::limit($b->topik_bimbingan, 40),
            'waktu' => $b->created_at,
        ]);

    // Proposal terbaru
    $proposalTerbaru = DB::table('proposal')
        ->where('nim_nid', $nim)
        ->latest('created_at')
        ->limit(1)
        ->get()
        ->map(fn($p) => (object)[
            'teks'  => 'Proposal berhasil diunggah',
            'waktu' => $p->created_at,
        ]);

    // Pembimbing ditentukan
    $pembimbingDitentukan = collect();
    if ($proposal) {
        $tanggalPembimbing = DB::table('dosen_pembimbing')
            ->where('proposal_id', $proposal->id)
            ->latest('tanggal_penetapan')
            ->value('tanggal_penetapan');
        if ($tanggalPembimbing) {
            $pembimbingDitentukan->push((object)[
                'teks'  => 'Pembimbing telah ditentukan',
                'waktu' => $tanggalPembimbing,
            ]);
        }
    }

    // Judul disetujui
    $judulDisetujuiAktivitas = DB::table('pengajuan_judul')
        ->where('nim_nid', $nim)
        ->where('status', 'disetujui')
        ->latest('updated_at')
        ->limit(1)
        ->get()
        ->map(fn($j) => (object)[
            'teks'  => 'Judul TA Disetujui',
            'waktu' => $j->updated_at,
        ]);

    // Pengajuan judul terbaru
    $pengajuanTerbaru = DB::table('pengajuan_judul')
        ->where('nim_nid', $nim)
        ->latest('created_at')
        ->limit(1)
        ->get()
        ->map(fn($p) => (object)[
            'teks'  => 'Pengajuan Judul TA Baru',
            'waktu' => $p->created_at,
        ]);

    // Gabungkan & sort by waktu terbaru, ambil 5
    $aktivitas = $bimbinganTerbaru
        ->concat($proposalTerbaru)
        ->concat($pembimbingDitentukan)
        ->concat($judulDisetujuiAktivitas)
        ->concat($pengajuanTerbaru)
        ->sortByDesc('waktu')
        ->take(5)
        ->values();

    // ── ALUR KEMAJUAN (realtime) ──
    $adaPengajuan  = DB::table('pengajuan_judul')->where('nim_nid', $nim)->exists();
    $judulApproved = DB::table('pengajuan_judul')
        ->where('nim_nid', $nim)
        ->where('status', 'disetujui')
        ->exists();
    $adaProposal   = DB::table('proposal')->where('nim_nid', $nim)->exists();

    // Verifikasi pembimbing: ada dosen_pembimbing di proposal ini
    $verifikasiPembimbing = false;
    if ($proposal) {
        $verifikasiPembimbing = DB::table('dosen_pembimbing')
            ->where('proposal_id', $proposal->id)
            ->exists();
    }

    // Review proposal: proposal status selesai/disetujui
    $reviewProposal = DB::table('proposal')
        ->where('nim_nid', $nim)
        ->whereIn('status', ['selesai', 'disetujui'])
        ->exists();

    // Proses bimbingan: sudah ada minimal 1 bimbingan
    $prosesBimbingan = DB::table('bimbingan')->where('nim_nid', $nim)->exists();

    // Seminar: belum ada tabel, default false
    $seminarProposal = false;

    $steps = [
        'pengajuan_judul'       => $adaPengajuan && $judulApproved,
        'upload_proposal'       => $adaProposal,
        'verifikasi_pembimbing' => $verifikasiPembimbing,
        'review_proposal'       => $reviewProposal,
        'proses_bimbingan'      => $prosesBimbingan,
        'seminar_proposal'      => $seminarProposal,
    ];

    return view('mahasiswa.index', compact(
        'totalPengajuan',
        'totalProposal',
        'totalBimbingan',
        'judulDisetujui',
        'namaDosen1',
        'namaDosen2',
        'targetBimbingan',
        'aktivitas',
        'steps'
    ));
});


// =====================================================
// PROFIL
// =====================================================

Route::get('/admin/profil', [AuthController::class, 'profilAdmin'])->name('admin.profil');

Route::get('/mahasiswa/profil',       [AuthController::class, 'profilMahasiswa'])->name('mahasiswa.profil');
Route::post('/mahasiswa/profil/foto', [AuthController::class, 'uploadFotoMahasiswa'])->name('mahasiswa.profil.foto');

Route::get('/dosen/profil',       [AuthController::class, 'profilDosen'])->name('dosen.profil');
Route::post('/dosen/profil/foto', [AuthController::class, 'uploadFotoDosen'])->name('dosen.profil.foto');


// =====================================================
// PANDUAN TA
// =====================================================

Route::get('/panduan-ta/mahasiswa',     [PanduanTAController::class, 'mahasiswa'])->name('panduan-ta.mahasiswa');
Route::get('/panduan-ta/dosen',         [PanduanTAController::class, 'dosen'])->name('panduan-ta.dosen');
Route::get('/panduan-ta/download/{id}', [PanduanTAController::class, 'download'])->name('panduan-ta.download');


// =====================================================
// PENGAJUAN JUDUL — MAHASISWA
// =====================================================

Route::get('/pengajuan-mahasiswa',   [PengajuanMahasiswaController::class, 'index'])->name('pengajuan.mahasiswa');
Route::post('/pengajuan/store',      [PengajuanMahasiswaController::class, 'store'])->name('pengajuan.store');
Route::get('/pengajuan/detail/{id}', [PengajuanMahasiswaController::class, 'detail'])->name('pengajuan.detail');


// =====================================================
// PENGAJUAN JUDUL — KOORDINATOR/DOSEN
// =====================================================

Route::get('/pengajuan',                 [PengajuanController::class, 'index'])->name('pengajuan');
Route::get('/pengajuan/verifikasi/{id}', [PengajuanController::class, 'verifikasi'])->name('pengajuan.verifikasi');
Route::post('/pengajuan/proses/{id}',    [PengajuanController::class, 'prosesVerifikasi'])->name('pengajuan.proses');


// =====================================================
// PROPOSAL TA-1 — MAHASISWA
// =====================================================

Route::get('/proposal/mahasiswa',        [ProposalMahasiswaController::class, 'index'])->name('proposal.mahasiswa');
Route::post('/proposal/mahasiswa/store', [ProposalMahasiswaController::class, 'store'])->name('proposal.store');
Route::get('/proposal/mahasiswa/{id}',   [ProposalMahasiswaController::class, 'detail'])->name('proposal.mahasiswa.detail');


// =====================================================
// PROPOSAL TA-1 — KOORDINATOR
// =====================================================

Route::get('/proposal',                                [ProposalController::class, 'index'])->name('proposal.index');
Route::get('/proposal/{id}/verifikasi',                [ProposalController::class, 'verifikasi'])->name('proposal.verifikasi');
Route::post('/proposal/{id}/verifikasi',               [ProposalController::class, 'prosesVerifikasi'])->name('proposal.prosesVerifikasi');
Route::post('/proposal/{id}/tetapkan/{urutan}',        [ProposalController::class, 'tetapkanUsulan'])->name('proposal.tetapkan');
Route::post('/proposal/{id}/lanjutkan',                [ProposalController::class, 'lanjutkanKeReviewer'])->name('proposal.lanjutkan');
Route::post('/proposal/{id}/ubah-pembimbing/{urutan}', [ProposalController::class, 'ubahPembimbing'])->name('proposal.ubahPembimbing');
Route::get('/proposal/{id}',                           [ProposalController::class, 'detail'])->name('proposal.detail');


// =====================================================
// PROPOSAL TA-1 — DOSEN REVIEWER
// =====================================================

Route::get('/reviewer/proposal',              [ReviewerController::class, 'index'])->name('reviewer.proposal');
Route::post('/reviewer/proposal/{id}/review', [ReviewerController::class, 'simpanReview'])->name('reviewer.simpanReview');
Route::get('/reviewer/proposal/{id}/detail',  [ReviewerController::class, 'detail'])->name('reviewer.proposal.detail');


// =====================================================
// JADWAL
// =====================================================

Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');


// =====================================================
// RIWAYAT BIMBINGAN — DOSEN
// =====================================================

Route::get('/dosen/bimbingan', [DosenBimbinganController::class, 'index'])
    ->name('dosen.bimbingan.index');

Route::put('/dosen/bimbingan/proposal/{id}/status', [DosenBimbinganController::class, 'updateStatusProposal'])
    ->name('dosen.bimbingan.proposal.status');

Route::get('/dosen/bimbingan/mahasiswa/{nim}', [DosenBimbinganController::class, 'detailMahasiswa'])
    ->name('dosen.bimbingan.detail');

Route::get('/dosen/proposal/{id}/lihat', [DosenBimbinganController::class, 'lihatProposal'])
    ->name('dosen.proposal.lihat');


// =====================================================
// BIMBINGAN — MAHASISWA
// =====================================================

Route::get('/bimbingan',           [BimbinganController::class, 'index'])->name('bimbingan.index');
Route::post('/bimbingan/store',    [BimbinganController::class, 'store'])->name('bimbingan.store');
Route::post('/bimbingan/proposal', [BimbinganController::class, 'storeProposal'])->name('bimbingan.proposal.store');


// =====================================================
// DATA MAHASISWA — DOSEN
// =====================================================

Route::get('/dosen/mahasiswa', [MahasiswaDosenController::class, 'index'])->name('dosen.mahasiswa');
