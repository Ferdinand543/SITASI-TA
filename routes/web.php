<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PengajuanMahasiswaController;
use App\Http\Controllers\ProposalController;

// ROOT
Route::get('/', function () {
    return redirect('/login');
});

// LOGIN
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);

// LOGOUT
Route::get('/logout', function () {
    session()->forget('user');
    return redirect('/login')->with('success', 'Berhasil logout');
});

// FORGOT PASSWORD
Route::get('/forgot-password', function () {
    return view('auth.forgotpass');
});
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


// ================= DOSEN =================
Route::get('/dashboard/dosen', function () {
    if (!session('user')) return redirect('/login');
    return view('dosen.dashboard');
});


// ================= ADMIN =================
Route::get('/admin', function () {
    if (!session('user')) return redirect('/login');
    return view('admin.admin');
});


// ================= MAHASISWA =================
Route::get('/mahasiswa', function () {
    if (!session('user')) return redirect('/login');
    return view('mahasiswa.index');
});


// =====================================================
// PENGAJUAN JUDUL MAHASISWA
// =====================================================

Route::get(
    '/pengajuan-mahasiswa',
    [PengajuanMahasiswaController::class, 'index']
)->name('pengajuan.mahasiswa');

Route::post(
    '/pengajuan/store',
    [PengajuanMahasiswaController::class, 'store']
)->name('pengajuan.store');

Route::get(
    '/pengajuan/detail/{id}',
    [PengajuanMahasiswaController::class, 'detail']
)->name('pengajuan.detail');


// =====================================================
// PENGAJUAN JUDUL — KOORDINATOR
// =====================================================

Route::get(
    '/pengajuan',
    [PengajuanController::class, 'index']
)->name('pengajuan');

Route::get(
    '/pengajuan/verifikasi/{id}',
    [PengajuanController::class, 'verifikasi']
)->name('pengajuan.verifikasi');

Route::post(
    '/pengajuan/proses/{id}',
    [PengajuanController::class, 'prosesVerifikasi']
)->name('pengajuan.proses');


// =====================================================
// PROPOSAL TA-1 — KOORDINATOR
// =====================================================

Route::get(
    '/proposal',
    [ProposalController::class, 'index']
)->name('proposal.index');

Route::get(
    '/proposal/{id}',
    [ProposalController::class, 'detail']
)->name('proposal.detail');

Route::get(
    '/proposal/{id}/verifikasi',
    [ProposalController::class, 'verifikasi']
)->name('proposal.verifikasi');

Route::post(
    '/proposal/{id}/verifikasi',
    [ProposalController::class, 'prosesVerifikasi']
)->name('proposal.prosesVerifikasi');

// ↓↓↓ TAMBAHAN BARU ↓↓↓
Route::post(
    '/proposal/{id}/tetapkan/{urutan}',
    [ProposalController::class, 'tetapkanUsulan']
)->name('proposal.tetapkan');

Route::post(
    '/proposal/{id}/lanjutkan',
    [ProposalController::class, 'lanjutkanKeReviewer']
)->name('proposal.lanjutkan');