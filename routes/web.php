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
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PenilaianPembimbingController;
use App\Http\Controllers\DaftarSeminarController;
use App\Http\Controllers\AdminSeminarController;
use App\Http\Controllers\jadwalseminarcontroller;
use App\Http\Controllers\HasilPenilaianMahasiswaController;
use App\Http\Controllers\KelolaPengujiController;
use App\Http\Controllers\PengujiMahasiswaSeminarController;
use App\Http\Controllers\ImportMahasiswaController;

// ROOT
Route::get('/', fn() => redirect('/login'));


// =====================================================
// AUTH
// =====================================================

Route::get('/login',          [AuthController::class, 'showLogin']);
Route::post('/login',         [AuthController::class, 'login']);
Route::get('/logout', function () {
    session()->forget('user');
    return redirect('/login')->with('success', 'Berhasil logout');
});
Route::get('/forgot-password',         fn() => view('auth.forgotpass'));
Route::post('/forgot-password',        [AuthController::class, 'sendResetLink']);
Route::get('/reset-password/{token}',  [AuthController::class, 'showResetForm'])->name('password.reset');
Route::get('/reset-success',           fn() => view('auth.reset_success'));
Route::post('/reset-password',         [AuthController::class, 'doResetPassword']);


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

    $judulDisetujui = DB::table('pengajuan_judul')
        ->where('nim_nid', $nim)
        ->where('status', 'disetujui')
        ->latest('updated_at')
        ->first();

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

    $targetBimbingan = 12;

    $bimbinganTerbaru = DB::table('bimbingan')
        ->where('nim_nid', $nim)
        ->latest('created_at')
        ->limit(2)
        ->get()
        ->map(fn($b) => (object)[
            'teks'  => 'Bimbingan ke-' . $b->pertemuan_ke . ': ' . \Illuminate\Support\Str::limit($b->topik_bimbingan, 40),
            'waktu' => $b->created_at,
        ]);

    $proposalTerbaru = DB::table('proposal')
        ->where('nim_nid', $nim)
        ->latest('created_at')
        ->limit(1)
        ->get()
        ->map(fn($p) => (object)[
            'teks'  => 'Proposal berhasil diunggah',
            'waktu' => $p->created_at,
        ]);

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

    $pengajuanTerbaru = DB::table('pengajuan_judul')
        ->where('nim_nid', $nim)
        ->latest('created_at')
        ->limit(1)
        ->get()
        ->map(fn($p) => (object)[
            'teks'  => 'Pengajuan Judul TA Baru',
            'waktu' => $p->created_at,
        ]);

    $aktivitas = $bimbinganTerbaru
        ->concat($proposalTerbaru)
        ->concat($pembimbingDitentukan)
        ->concat($judulDisetujuiAktivitas)
        ->concat($pengajuanTerbaru)
        ->sortByDesc('waktu')
        ->take(5)
        ->values();

    $adaPengajuan  = DB::table('pengajuan_judul')->where('nim_nid', $nim)->exists();
    $judulApproved = DB::table('pengajuan_judul')
        ->where('nim_nid', $nim)
        ->where('status', 'disetujui')
        ->exists();
    $adaProposal = DB::table('proposal')->where('nim_nid', $nim)->exists();

    $verifikasiPembimbing = false;
    if ($proposal) {
        $verifikasiPembimbing = DB::table('dosen_pembimbing')
            ->where('proposal_id', $proposal->id)
            ->exists();
    }

    $reviewProposal = DB::table('proposal')
        ->where('nim_nid', $nim)
        ->whereIn('status', ['selesai', 'disetujui'])
        ->exists();

    $prosesBimbingan = DB::table('bimbingan')->where('nim_nid', $nim)->exists();
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

Route::get('/admin/profil_admin_tu',      [AuthController::class, 'profilAdmin'])->name('admin.profil_admin_tu');
Route::get('/mahasiswa/profil',           [AuthController::class, 'profilMahasiswa'])->name('mahasiswa.profil');
Route::post('/mahasiswa/profil/foto',     [AuthController::class, 'uploadFotoMahasiswa'])->name('mahasiswa.profil.foto');
Route::get('/dosen/profil',               [AuthController::class, 'profilDosen'])->name('dosen.profil');
Route::post('/dosen/profil/foto',         [AuthController::class, 'uploadFotoDosen'])->name('dosen.profil.foto');


// =====================================================
// PANDUAN TA
// =====================================================

Route::get('/panduan-ta/mahasiswa',     [PanduanTAController::class, 'mahasiswa'])->name('panduan-ta.mahasiswa');
Route::get('/panduan-ta/dosen',         [PanduanTAController::class, 'dosen'])->name('panduan-ta.dosen');
Route::get('/panduan-ta/admin',         [PanduanTAController::class, 'admin'])->name('panduan-ta.admin');
Route::get('/panduan-ta/download/{id}', [PanduanTAController::class, 'download'])->name('panduan-ta.download');
Route::get('/admin/panduan-ta/create',  [PanduanTAController::class, 'create'])->name('panduan.create');
Route::post('/admin/panduan-ta/store',  [PanduanTAController::class, 'store'])->name('panduan.store');
Route::delete('/panduan-ta/{id}',       [PanduanTAController::class, 'destroy'])->name('panduan.destroy');


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
// PROPOSAL TA-1 — FILE SERVE
// =====================================================

Route::get('/proposal/file/{id}', [AdminProposalController::class, 'serveFile'])->name('proposal.file');


// =====================================================
// PROPOSAL TA-1 — MAHASISWA
// =====================================================

Route::get('/proposal/mahasiswa',        [ProposalMahasiswaController::class, 'index'])->name('proposal.mahasiswa');
Route::post('/proposal/mahasiswa/store', [ProposalMahasiswaController::class, 'store'])->name('proposal.store');
Route::get('/proposal/mahasiswa/{id}',   [ProposalMahasiswaController::class, 'detail'])->name('proposal.mahasiswa.detail');


// =====================================================
// PROPOSAL TA-1 — DOSEN PENGUJI (READ ONLY)
// =====================================================

Route::get('/proposal/penguji', [ProposalController::class, 'indexPenguji'])->name('proposal.penguji');


// =====================================================
// PROPOSAL TA-1 — KOORDINATOR
// =====================================================

Route::get('/proposal',                                [ProposalController::class, 'index'])->name('proposal.index');
Route::get('/proposal/{id}/verifikasi',                [ProposalController::class, 'verifikasi'])->name('proposal.verifikasi');
Route::post('/proposal/{id}/verifikasi',               [ProposalController::class, 'prosesVerifikasi'])->name('proposal.prosesVerifikasi');
Route::post('/proposal/{id}/tetapkan/{urutan}',        [ProposalController::class, 'tetapkanUsulan'])->name('proposal.tetapkan');
Route::post('/proposal/{id}/lanjutkan',                [ProposalController::class, 'lanjutkanKeReviewer'])->name('proposal.lanjutkan');
Route::post('/proposal/{id}/assign-reviewer',          [ProposalController::class, 'assignReviewer'])->name('proposal.assignReviewer');
Route::post('/proposal/{id}/ubah-pembimbing/{urutan}', [ProposalController::class, 'ubahPembimbing'])->name('proposal.ubahPembimbing');
Route::post('/proposal/{id}/simpan',                   [ProposalController::class, 'simpanPenetapan'])->name('proposal.simpan');
Route::match(['POST', 'DELETE'], '/proposal/{id}/remove-reviewer', [ProposalController::class, 'removeReviewer'])->name('proposal.remove.reviewer');

Route::get('/proposal/reviewer/{nimReviewer}/kelola',            [ProposalController::class, 'kelolaReviewer'])->name('proposal.kelola.reviewer');
Route::post('/proposal/reviewer/{nimReviewer}/tambah-mahasiswa', [ProposalController::class, 'tambahMahasiswaReviewer'])->name('proposal.tambah.mahasiswa.reviewer');

Route::get('/proposal/{id}', [ProposalController::class, 'detail'])->name('proposal.detail');


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

Route::get('/dosen/bimbingan',                      [DosenBimbinganController::class, 'index'])->name('dosen.bimbingan.index');
Route::put('/dosen/bimbingan/proposal/{id}/status', [DosenBimbinganController::class, 'updateStatusProposal'])->name('dosen.bimbingan.proposal.status');
Route::get('/dosen/bimbingan/mahasiswa/{nim}',      [DosenBimbinganController::class, 'detailMahasiswa'])->name('dosen.bimbingan.detail');
Route::get('/dosen/proposal/{id}/lihat',            [DosenBimbinganController::class, 'lihatProposal'])->name('dosen.proposal.lihat');
Route::post('/dosen/bimbingan/proposal/{id}/track', [DosenBimbinganController::class, 'trackBuka'])->name('dosen.bimbingan.proposal.track');
Route::post('/dosen/bimbingan/validasi/{id}',       [DosenBimbinganController::class, 'validasiBimbingan'])->name('dosen.bimbingan.validasi'); // ✅ BARU


// =====================================================
// RIWAYAT BIMBINGAN — ADMIN
// =====================================================

Route::get('/admin/bimbingan',                       [AdminBimbinganController::class, 'index'])->name('admin.bimbingan.index');
Route::put('/admin/bimbingan/proposal/{id}/status',  [AdminBimbinganController::class, 'updateStatusProposal'])->name('admin.bimbingan.proposal.status');
Route::get('/admin/proposal/{id}/lihat',             [AdminBimbinganController::class, 'lihatProposal'])->name('admin.proposal.lihat');
Route::post('/admin/bimbingan/proposal/{id}/track',  [AdminBimbinganController::class, 'trackBuka'])->name('admin.proposal.track');
Route::get('/admin/bimbingan/dosen/{nim_nid}',       [AdminBimbinganController::class, 'detailDosen'])->name('admin.bimbingan.dosen');
Route::get('/admin/bimbingan/{nim}/{nim_nid_dosen}', [AdminBimbinganController::class, 'detailMahasiswa'])->name('admin.bimbingan.detail');


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


// =====================================================
// DOSEN CRUD — ADMIN
// =====================================================

Route::get('/admin/dosen',                [AdminDosenController::class, 'index'])->name('dosen.index');
Route::post('/admin/dosen/store',         [AdminDosenController::class, 'store'])->name('dosen.store');
Route::get('/admin/dosen/{nim_nid}',      [AdminDosenController::class, 'show'])->name('dosen.show');
Route::get('/admin/dosen/{nim_nid}/edit', [AdminDosenController::class, 'edit'])->name('dosen.edit');
Route::put('/admin/dosen/{nim_nid}',      [AdminDosenController::class, 'update'])->name('dosen.update');
Route::delete('/admin/dosen/{nim_nid}',   [AdminDosenController::class, 'destroy'])->name('dosen.destroy');


// =====================================================
// MAHASISWA CRUD — ADMIN
// =====================================================

Route::get('/admin/mahasiswa',                [AdminMahasiswaController::class, 'index'])->name('mahasiswa.index');
Route::post('/admin/mahasiswa/store',         [AdminMahasiswaController::class, 'store'])->name('mahasiswa.store');
Route::get('/admin/mahasiswa/{nim_nid}',      [AdminMahasiswaController::class, 'show'])->name('mahasiswa.show');
Route::get('/admin/mahasiswa/{nim_nid}/edit', [AdminMahasiswaController::class, 'edit'])->name('mahasiswa.edit');
Route::put('/admin/mahasiswa/{nim_nid}',      [AdminMahasiswaController::class, 'update'])->name('mahasiswa.update');
Route::delete('/admin/mahasiswa/{nim_nid}',   [AdminMahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');


// =====================================================
// RIWAYAT PENGAJUAN PROPOSAL — ADMIN
// =====================================================

Route::prefix('admin')->group(function () {
    Route::get('/proposal',               [AdminProposalController::class, 'index'])->name('admin.proposal.index');
    Route::get('/proposal/{id}',          [AdminProposalController::class, 'show'])->name('admin.proposal.detail');
    Route::post('/proposal/{id}/approve', [AdminProposalController::class, 'approve'])->name('admin.proposal.approve');
    Route::post('/proposal/{id}/reject',  [AdminProposalController::class, 'reject'])->name('admin.proposal.reject');
});


// =====================================================
// PENGAJUAN JUDUL — ADMIN
// =====================================================

Route::prefix('admin')->group(function () {
    Route::get('/judul',              [AdminjudulController::class, 'index'])->name('admin.judul.index');
    Route::get('/judul/{id}',         [AdminjudulController::class, 'show'])->name('admin.judul.show');
    Route::post('/judul/{id}/proses', [AdminjudulController::class, 'proses'])->name('admin.judul.proses');
});


// =====================================================
// ADMINISTRASI SEMINAR — ADMIN
// =====================================================

Route::prefix('admin')->group(function () {
    Route::get('/seminar',                  [AdminSeminarController::class, 'index'])->name('admin.seminar.index');
    Route::get('/seminar/{id}',             [AdminSeminarController::class, 'show'])->name('admin.seminar.show');
    Route::post('/seminar/{id}/verifikasi', [AdminSeminarController::class, 'verifikasi'])->name('admin.seminar.verifikasi');
    Route::post('/seminar/{id}/jadwalkan',  [AdminSeminarController::class, 'jadwalkan'])->name('admin.seminar.jadwalkan');
});


// =====================================================
// KELOLA JADWAL SEMINAR — ADMIN & KOORDINATOR
// =====================================================

Route::get('/kelola-seminar/massal',          [jadwalseminarcontroller::class, 'formMassal'])->name('jadwalseminar.massal.form');
Route::post('/kelola-seminar/massal',         [jadwalseminarcontroller::class, 'simpanMassal'])->name('jadwalseminar.massal.simpan');
Route::get('/kelola-seminar/mahasiswa',       [jadwalseminarcontroller::class, 'getMahasiswaBelumJadwal'])->name('jadwalseminar.mahasiswa');
Route::get('/kelola-seminar',                 [jadwalseminarcontroller::class, 'index'])->name('jadwalseminar.index');
Route::get('/kelola-seminar/{id}/detail',     [jadwalseminarcontroller::class, 'detail'])->name('jadwalseminar.detail');
Route::post('/kelola-seminar/{id}/jadwalkan', [jadwalseminarcontroller::class, 'jadwalkan'])->name('jadwalseminar.jadwalkan');
Route::post('/kelola-seminar/{id}/hapus',     [jadwalseminarcontroller::class, 'hapusJadwal'])->name('jadwalseminar.hapus');


// =====================================================
// REGISTER ADMIN
// =====================================================

Route::get('/register-admin', function () {
    if (!session('user')) {
        return view('auth.register-admin');
    }
    if (strtolower(session('user')->role) !== 'admin') {
        return redirect('/login')->with('error', 'Akses ditolak!');
    }
    return view('auth.register-admin');
});

Route::post('/register-admin', [AuthController::class, 'register']);


// =====================================================
// PENILAIAN SEMINAR — DOSEN PENGUJI
// =====================================================

Route::get('/penilaian',                     [PenilaianController::class, 'index'])->name('penilaian.index');
Route::get('/penilaian/{proposalId}/form',   [PenilaianController::class, 'form'])->name('penilaian.form');
Route::post('/penilaian/{proposalId}/store', [PenilaianController::class, 'store'])->name('penilaian.store');
Route::get('/penilaian/{proposalId}/show',   [PenilaianController::class, 'show'])->name('penilaian.show');


// =====================================================
// PENILAIAN SEMINAR — DOSEN PEMBIMBING
// =====================================================

Route::get('/penilaian-pembimbing',                     [PenilaianPembimbingController::class, 'index'])->name('penilaian.pembimbing.index');
Route::get('/penilaian-pembimbing/{proposalId}/form',   [PenilaianPembimbingController::class, 'form'])->name('penilaian.pembimbing.form');
Route::post('/penilaian-pembimbing/{proposalId}/store', [PenilaianPembimbingController::class, 'store'])->name('penilaian.pembimbing.store');
Route::get('/penilaian-pembimbing/{proposalId}/show',   [PenilaianPembimbingController::class, 'show'])->name('penilaian.pembimbing.show');


// =====================================================
// DAFTAR SEMINAR — MAHASISWA
// =====================================================

Route::get('/seminar',              [DaftarSeminarController::class, 'index'])->name('seminar.daftar');
Route::get('/seminar/ajukan',       [DaftarSeminarController::class, 'create'])->name('seminar.create');
Route::post('/seminar/ajukan',      [DaftarSeminarController::class, 'store'])->name('seminar.store');
Route::post('/seminar/draft',       [DaftarSeminarController::class, 'saveDraft'])->name('seminar.draft');
Route::get('/seminar/{id}/edit',    [DaftarSeminarController::class, 'edit'])->name('seminar.edit');
Route::get('/seminar/{id}',         [DaftarSeminarController::class, 'show'])->name('seminar.show');
Route::get('/seminar/{id}/daftar',  [DaftarSeminarController::class, 'formDaftar'])->name('seminar.formDaftar');
Route::post('/seminar/{id}/daftar', [DaftarSeminarController::class, 'submitDaftar'])->name('seminar.submitDaftar');


// =====================================================
// HASIL PENILAIAN — MAHASISWA
// =====================================================

Route::get('/mahasiswa/hasil-penilaian', [HasilPenilaianMahasiswaController::class, 'index'])->name('mahasiswa.hasil.penilaian');


// =====================================================
// KELOLA DOSEN PENGUJI — KOORDINATOR
// =====================================================

Route::get('/dosen/penguji',                     [KelolaPengujiController::class, 'index'])->name('dosen.penguji.index');
Route::get('/dosen/penguji/mahasiswa',            [KelolaPengujiController::class, 'mahasiswa'])->name('penguji.mahasiswa.index');
Route::get('/dosen/penguji/{nim_nid}',            [KelolaPengujiController::class, 'show'])->name('penguji.show');
Route::post('/dosen/penguji/{nim_nid}/tetapkan',  [KelolaPengujiController::class, 'tetapkan'])->name('penguji.tetapkan');


// =====================================================
// MAHASISWA SEMINAR — DOSEN PENGUJI
// =====================================================

Route::get('/dosen/mahasiswa-seminar', [PengujiMahasiswaSeminarController::class, 'mahasiswaSeminar'])->name('dosen.mahasiswa.seminar');

Route::get('/admin/mahasiswa/import/template', [ImportMahasiswaController::class, 'template'])->name('mahasiswa.import.template');
Route::post('/admin/mahasiswa/import',         [ImportMahasiswaController::class, 'import'])->name('mahasiswa.import');

// ===== TAMBAHKAN INI DI web.php =====
// Pastikan App\Http\Controllers\JadwalSeminarMahasiswaController sudah di-import / use namespace

Route::get('/jadwal-seminar-mahasiswa', [App\Http\Controllers\JadwalSeminarMahasiswaController::class, 'index'])
    ->name('jadwalseminar.mahasiswa.list');