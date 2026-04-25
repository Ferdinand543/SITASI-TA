<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ROOT
Route::get('/', function () {
    return redirect('/login');
});

// LOGIN
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);

// FORGOT PASSWORD ← TAMBAH INI
Route::get('/forgot-password', function () {
    return view('auth.forgotpass');
});
Route::post('/reset-password', [AuthController::class, 'resetPassword']); // ← TAMBAH INI

// DOSEN
Route::get('/dashboard/dosen', function () {
    return view('dosen.dashboard');
});

// ADMIN
Route::get('/admin', function () {
    return view('admin.admin');
});

// MAHASISWA
Route::get('/mahasiswa', function () {
    return view('mahasiswa.index');
});

// PENGAJUAN 
Route::get('/pengajuan', function () {
    if (!session('user')) {
        return redirect('/login')->with('error', 'Silakan login dulu!');
    }
    return view('pengajuan.index');
})->name('pengajuan');

// PROPOSAL 
Route::get('/proposal', function () {
    if (!session('user')) {
        return redirect('/login');
    }
    return view('pengajuan.proposal');
})->name('proposal');