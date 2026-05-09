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

// PENGAJUAN DETAIL
// TODO: nanti ganti closure ini dengan PengajuanController@detail
Route::get('/pengajuan/detail/{id}', function ($id) {
    if (!session('user')) {
        return redirect('/login')->with('error', 'Silakan login dulu!');
    }
    // TODO: return view('pengajuan.detail', ['id' => $id]);
    return "Halaman detail pengajuan ID: " . $id . " (belum dibuat)";
})->name('pengajuan.detail');