<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PengajuanMahasiswaController;

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

    return redirect('/login')
        ->with('success', 'Berhasil logout');
});

// FORGOT PASSWORD
Route::get('/forgot-password', function () {
    return view('auth.forgotpass');
});

Route::post('/reset-password', [AuthController::class, 'resetPassword']);


// ================= DOSEN =================
Route::get('/dashboard/dosen', function () {

    if (!session('user')) {
        return redirect('/login');
    }

    return view('dosen.dashboard');
});


// ================= ADMIN =================
Route::get('/admin', function () {

    if (!session('user')) {
        return redirect('/login');
    }

    return view('admin.admin');
});


// ================= MAHASISWA =================
Route::get('/mahasiswa', function () {

    if (!session('user')) {
        return redirect('/login');
    }

    return view('mahasiswa.index');
});


// =====================================================
// PENGAJUAN MAHASISWA
// =====================================================

// HALAMAN PENGAJUAN
Route::get(
    '/pengajuan-mahasiswa',
    [PengajuanMahasiswaController::class, 'index']
)->name('pengajuan.mahasiswa');


// SIMPAN PENGAJUAN
Route::post(
    '/pengajuan/store',
    [PengajuanMahasiswaController::class, 'store']
)->name('pengajuan.store');


// DETAIL PENGAJUAN
Route::get(
    '/pengajuan/detail/{id}',
    [PengajuanMahasiswaController::class, 'detail']
)->name('pengajuan.detail');


// =====================================================
// DOSEN / KOORDINATOR
// =====================================================

// HALAMAN VERIFIKASI
Route::get(
    '/pengajuan',
    [PengajuanController::class, 'index']
)->name('pengajuan');


// DETAIL VERIFIKASI
Route::get(
    '/pengajuan/verifikasi/{id}',
    [PengajuanController::class, 'verifikasi']
)->name('pengajuan.verifikasi');


// PROSES VERIFIKASI
Route::post(
    '/pengajuan/proses/{id}',
    [PengajuanController::class, 'prosesVerifikasi']
)->name('pengajuan.proses');


// ================= PROPOSAL =================
Route::get('/proposal', function () {

    if (!session('user')) {
        return redirect('/login');
    }

    return view('pengajuan.proposal');

})->name('proposal');