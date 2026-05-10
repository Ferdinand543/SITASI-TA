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
    if (!session('user')) {
        return redirect('/login')->with('error', 'Silakan login dulu!');
    }
    return view('dosen.dashboard');
});

// ADMIN
Route::get('/admin', function () {
    if (!session('user')) {
        return redirect('/login')->with('error', 'Silakan login dulu!');
    }
    return view('admin.admin');
});

// MAHASISWA
Route::get('/mahasiswa', function () {
    if (!session('user')) {
        return redirect('/login')->with('error', 'Silakan login dulu!');
    }
    return view('mahasiswa.index');
});

// PENGAJUAN — hanya koordinator, pakai PengajuanController
Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan');

// PROPOSAL
Route::get('/proposal', function () {
    if (!session('user')) {
        return redirect('/login');
    }
    return view('pengajuan.proposal');
})->name('proposal');

// PENGAJUAN DETAIL — hanya koordinator
Route::get('/pengajuan/detail/{id}', function ($id) {
    if (!session('user')) {
        return redirect('/login')->with('error', 'Silakan login dulu!');
    }

    $role = strtolower(trim(session('user')->role));

    if ($role !== 'koordinator') {
        if ($role === 'mahasiswa') {
            return redirect('/mahasiswa')->with('error', 'Akses ditolak!');
        }
        return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
    }

    // TODO: return view('pengajuan.detail', ['id' => $id]);
    return "Halaman detail pengajuan ID: " . $id . " (belum dibuat)";
})->name('pengajuan.detail');