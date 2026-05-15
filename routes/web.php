<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminMahasiswaController;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminDosenController;

// logout
Route::post('/logout', function (Request $request) {

    $request->session()->forget('user');

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/login');

})->name('logout');

// ROOT
Route::get('/', function () {
    return redirect('/login');
});

//register admin
Route::get('/register-admin', function () {
    // kalau belum login → tetap boleh (buat admin pertama)
    if (!session('user')) {
        return view('auth.register-admin');
    }

    // kalau sudah login tapi bukan admin → tolak
    if (strtolower(session('user')->role) !== 'admin') {
        return redirect('/login')->with('error', 'Akses ditolak!');
    }

    return view('auth.register-admin');
});
Route::post('/register-admin', [AuthController::class, 'register']);

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

Route::resource(
    'admin/mahasiswa',
    AdminMahasiswaController::class
);

// daftar Mahasiswa
Route::get('/admin/mahasiswa', [AdminMahasiswaController::class, 'index'])
    ->name('mahasiswa.index');

Route::post('/admin/mahasiswa/store', [AdminMahasiswaController::class, 'store'])
    ->name('mahasiswa.store');

Route::get('/admin/mahasiswa/{id}/edit', [AdminMahasiswaController::class, 'edit'])
    ->name('mahasiswa.edit');

Route::put('/admin/mahasiswa/{id}', [AdminMahasiswaController::class, 'update'])
    ->name('mahasiswa.update');

Route::delete('/admin/mahasiswa/{id}', [AdminMahasiswaController::class, 'destroy'])
    ->name('mahasiswa.destroy');

// Daftar Dosen
Route::get('/admin/dosen', [AdminDosenController::class, 'index'])
    ->name('dosen.index');

Route::post('/admin/dosen/store', [AdminDosenController::class, 'store'])
    ->name('dosen.store');