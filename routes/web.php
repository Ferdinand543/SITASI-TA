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
Route::get('/forgot-password', fn() => view('auth.forgotpass'));
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


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

    $totalPengajuan = DB::table('pengajuan_judul')->where('nim_nid', $nim)->count();
    $totalProposal  = DB::table('proposal')->where('nim_nid', $nim)->count();
    $totalBimbingan = 0;

    return view('mahasiswa.index', compact(
        'totalPengajuan',
        'totalProposal',
        'totalBimbingan'
    ));
});


// =====================================================
// PROFIL
// =====================================================

// Profil Admin
Route::get('/admin/profil', [AuthController::class, 'profilAdmin'])->name('admin.profil');

// Profil Mahasiswa
Route::get('/mahasiswa/profil',       [AuthController::class, 'profilMahasiswa'])->name('mahasiswa.profil');
Route::post('/mahasiswa/profil/foto', [AuthController::class, 'uploadFotoMahasiswa'])->name('mahasiswa.profil.foto');

// Profil Dosen
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
// PROPOSAL TA-1 — MAHASISWA (taruh di atas semua {id}!)
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
// DATA MAHASISWA — DOSEN
// =====================================================

Route::get('/dosen/mahasiswa', [MahasiswaDosenController::class, 'index'])->name('dosen.mahasiswa');