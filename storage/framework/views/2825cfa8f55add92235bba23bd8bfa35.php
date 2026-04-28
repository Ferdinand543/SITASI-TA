

<?php $__env->startSection('content'); ?>

<!-- HERO -->
<div class="hero-section mb-4">
    <div class="hero-content">
        <h2 class="fw-bold">Sistem Bimbingan Tugas Akhir Mahasiswa</h2>
        <h2 class="fw-bold">Sistem Informasi</h2>

        <p>
            SITASI-TA digunakan untuk membantu mahasiswa dalam mengelola tugas akhir,<br>
            mulai dari pengajuan judul, proses bimbingan TA1,<br>
            hingga pelaksanaan seminar dan sidang secara terstruktur.
        </p>
    </div>
</div>

<div class="d-flex justify-content-end">
    <div class="menu-bottom-wrapper">
        <a href="#" class="menu-bottom active">Beranda</a>
        <a href="#" class="menu-bottom">Informasi</a>
        <a href="#" class="menu-bottom">Panduan TA</a>
    </div>
</div>

<div class="container">

    <!-- ROW 1 -->
    <div class="row g-4 justify-content-center text-center mb-4">

        <div class="col-md-3">
            <a href="<?php echo e(session('user') ? route('pengajuan') : '/login'); ?>" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                    <img src="<?php echo e(asset('images/kelola_mhs.jpeg')); ?>" class="menu-img mb-3">
                    <h6 class="fw-bold">Mahasiswa</h6>
                    <p class="text-muted small mb-0">Kelola Data Mahasiswa Tugas Akhir</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="<?php echo e(session('user') ? route('proposal') : '/login'); ?>" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                    <img src="<?php echo e(asset('images/mahasiswa.jpeg')); ?>" class="menu-img mb-3">
                    <h6 class="fw-bold">Dosen</h6>
                    <p class="text-muted small mb-0">Kelola Data Dosen</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="<?php echo e(session('user') ? '#' : '/login'); ?>" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                    <img src="<?php echo e(asset('images/penilaian.jpeg')); ?>" class="menu-img mb-3">
                    <h6 class="fw-bold">Manajemen Seminar</h6>
                    <p class="text-muted small mb-0">Kelola administrasi Seminar Mahasiswa</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="<?php echo e(session('user') ? '#' : '/login'); ?>" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                    <img src="<?php echo e(asset('images/jadwal.jpeg')); ?>" class="menu-img mb-3">
                    <h6 class="fw-bold">Jadwal</h6>
                    <p class="text-muted small mb-0">Kelola Data Mahasiswa Bimbingan</p>
                </div>
            </a>
        </div>

    </div>

    <!-- ROW 2 -->
    <div class="row g-4 justify-content-center text-center">

        <div class="col-md-3">
            <a href="<?php echo e(session('user') ? route('pengajuan') : '/login'); ?>" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                    <img src="<?php echo e(asset('images/ta.jpeg')); ?>" class="menu-img mb-3">
                    <h6 class="fw-bold">Pengajuan Judul</h6>
                    <p class="text-muted small mb-0">Kelola Data Mahasiswa Bimbingan</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="<?php echo e(session('user') ? route('pengajuan') : '/login'); ?>" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                    <img src="<?php echo e(asset('images/proposal.jpeg')); ?>" class="menu-img mb-3">
                    <h6 class="fw-bold">Proposal</h6>
                    <p class="text-muted small mb-0">Kelola Proposal Mahasiswa</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="<?php echo e(session('user') ? route('pengajuan') : '/login'); ?>" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                    <img src="<?php echo e(asset('images/bimbingan.jpeg')); ?>" class="menu-img mb-3">
                    <h6 class="fw-bold">Riwayat Bimbingan</h6>
                    <p class="text-muted small mb-0">Seminar & Sidang</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="<?php echo e(session('user') ? route('pengajuan') : '/login'); ?>" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                    <img src="<?php echo e(asset('images/absen.jpeg')); ?>" class="menu-img mb-3">
                    <h6 class="fw-bold">Presensi Audiens</h6>
                    <p class="text-muted small mb-0">Kelola Penilaian Seminar Mahasiswa</p>
                </div>
            </a>
        </div>

    </div>

</div>

<br>

<style>
.menu-img {
    height: 140px;
    object-fit: contain;
}

.menu-card {
    border-radius: 15px;
    transition: 0.3s;
    background: #f9fafb;
}

.menu-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\sitasita2\sitasita\resources\views/admin/admin.blade.php ENDPATH**/ ?>