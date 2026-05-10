<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanController;

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

// DOSEN
Route::get('/dashboard/dosen', function () {
    if (!session('user')) return redirect('/login');
    return view('dosen.dashboard');
});

// ADMIN
Route::get('/admin', function () {
    if (!session('user')) return redirect('/login');
    return view('admin.admin');
});

// MAHASISWA
Route::get('/mahasiswa', function () {
    if (!session('user')) return redirect('/login');
    return view('mahasiswa.index');
});

// PENGAJUAN
Route::get('/pengajuan', [PengajuanController::class, 'index'])
    ->name('pengajuan');

// DETAIL
Route::get('/pengajuan/detail/{id}', [PengajuanController::class, 'verifikasi'])
    ->name('pengajuan.detail');

// PROSES SIMPAN VERIFIKASI
Route::post('/pengajuan/proses/{id}', [PengajuanController::class, 'prosesVerifikasi'])
    ->name('pengajuan.proses');

// PROPOSAL
Route::get('/proposal', function () {
    if (!session('user')) return redirect('/login');
    return view('pengajuan.proposal');
})->name('proposal');