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
use App\Http\Controllers\AdminBimbinganController;
use App\Http\Controllers\MahasiswaDosenController;
use App\Http\Controllers\AdminDosenController;
use App\Http\Controllers\AdminMahasiswaController;
use App\Http\Controllers\JadwalAkademikController;
use App\Http\Controllers\AdminProposalController;
use App\Http\Controllers\AdminjudulController;


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

    $totalPengajuan = DB::table('pengajuan_judul')->where('nim_nid', $nim)->count();
    $totalProposal  = DB::table('proposal')->where('nim_nid', $nim)->count();
    $totalBimbingan = DB::table('bimbingan')->where('nim_nid', $nim)->count();

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
Route::get('/panduan-ta/admin',         [PanduanTAController::class, 'admin'])->name('panduan-ta.admin');
Route::get('/panduan-ta/download/{id}', [PanduanTAController::class, 'download'])->name('panduan-ta.download');
Route::get('/admin/panduan-ta/create', [PanduanTAController::class, 'create'])
    ->name('panduan.create');

Route::post('/admin/panduan-ta/store', [PanduanTAController::class, 'store'])
    ->name('panduan.store');

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
// JADWAL
// =====================================================

Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
Route::resource('jadwal-akademik', JadwalAkademikController::class);

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
// RIWAYAT BIMBINGAN — ADMIN
// =====================================================

Route::get('/admin/bimbingan', [AdminBimbinganController::class, 'index'])
    ->name('admin.bimbingan.index');

Route::put('/admin/bimbingan/proposal/{id}/status', [AdminBimbinganController::class, 'updateStatusProposal'])
    ->name('admin.bimbingan.proposal.status');

Route::get('/admin/bimbingan/{nim}', [AdminBimbinganController::class, 'detailMahasiswa'])
    ->name('admin.bimbingan.detail');
    
Route::get('/admin/proposal/{id}/lihat', [AdminBimbinganController::class, 'lihatProposal'])
    ->name('admin.proposal.lihat');

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

// ===============================
// DOSEN CRUD
// ===============================

// INDEX
Route::get('/admin/dosen', [AdminDosenController::class, 'index'])
    ->name('dosen.index');

// STORE
Route::post('/admin/dosen/store', [AdminDosenController::class, 'store'])
    ->name('dosen.store');

// DETAIL DOSEN
Route::get('/admin/dosen/{nim_nid}', [AdminDosenController::class, 'show'])
    ->name('dosen.show');

// EDIT DOSEN
Route::get('/admin/dosen/{nim_nid}/edit', [AdminDosenController::class, 'edit'])
    ->name('dosen.edit');

// UPDATE DOSEN
Route::put('/admin/dosen/{nim_nid}', [AdminDosenController::class, 'update'])
    ->name('dosen.update');

// HAPUS DOSEN
Route::delete('/admin/dosen/{nim_nid}', [AdminDosenController::class, 'destroy'])
    ->name('dosen.destroy');

// Mahasiswa
// INDEX
Route::get('/admin/mahasiswa', [AdminMahasiswaController::class, 'index'])
    ->name('mahasiswa.index');

// STORE
Route::post('/admin/mahasiswa/store', [AdminMahasiswaController::class, 'store'])
    ->name('mahasiswa.store');

// DETAIL DOSEN
Route::get('/admin/mahasiswa/{nim_nid}', [AdminMahasiswaController::class, 'show'])
    ->name('mahasiswa.show');

// EDIT DOSEN
Route::get('/admin/mahasiswa/{nim_nid}/edit', [AdminMahasiswaController::class, 'edit'])
    ->name('mahasiswa.edit');

// UPDATE DOSEN
Route::put('/admin/mahasiswa/{nim_nid}', [AdminMahasiswaController::class, 'update'])
    ->name('mahasiswa.update');

// HAPUS DOSEN
Route::delete('/admin/mahasiswa/{nim_nid}', [AdminMahasiswaController::class, 'destroy'])
    ->name('mahasiswa.destroy');    

// =====================================================
// RIWAYAT PENGAJUAN PROPOSAL — ADMIN
// =====================================================

Route::prefix('admin')->group(function () {

    Route::get('/proposal', [AdminProposalController::class, 'index'])
        ->name('admin.proposal.index');

    Route::get('/proposal/{id}', [AdminProposalController::class, 'show'])
        ->name('admin.proposal.detail');
    
    Route::post('/proposal/{id}/approve', [AdminProposalController::class, 'approve'])
        ->name('admin.proposal.approve');

    Route::post('/proposal/{id}/reject', [AdminProposalController::class, 'reject'])
        ->name('admin.proposal.reject');
});

//pengajuan judul-admin
Route::prefix('admin')->group(function () {

    Route::get('/judul', [AdminjudulController::class, 'index'])
        ->name('admin.judul.index');

    Route::get('/judul/{id}', [AdminjudulController::class, 'show'])
        ->name('admin.judul.show');

        // PROSES VERIFIKASI
    Route::post('/judul/{id}/proses', [AdminjudulController::class, 'proses'])
        ->name('admin.judul.proses');
});

// register admin
Route::get('/register-admin', function () {

    // kalau belum login → boleh (admin pertama)
    if (!session('user')) {
        return view('auth.register-admin');
    }

    // kalau login tapi bukan admin
    if (strtolower(session('user')->role) !== 'admin') {
        return redirect('/login')
            ->with('error', 'Akses ditolak!');
    }

    return view('auth.register-admin');
});

Route::post('/register-admin', [AuthController::class, 'register']);