

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

        <div class="mt-3">
            <a href="#" class="btn btn-warning px-4 me-2">Bimbingan</a>
            <a href="#" class="btn btn-outline-dark px-4">Daftar TA</a>
        </div>

    </div>

</div>
<div class="d-flex justify-content-end">
    <div class="menu-bottom-wrapper">
        <a href="#" class="menu-bottom active">Beranda</a>
        <a href="#" class="menu-bottom">Informasi</a>
        <a href="#" class="menu-bottom">Dosen Pembimbing</a>
        <a href="#" class="menu-bottom">Panduan TA</a>
    </div>
</div>
<!-- MENU -->
<div class="container">
    <div class="row g-4 justify-content-center">
        <!-- CARD 1 -->
        <div class="col-md-4 col-lg-3">
            <a href="<?php echo e(session('user') ? route('pengajuan') : '/login'); ?>"
                style="text-decoration:none; color:inherit;">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card text-center">
                    <img src="<?php echo e(asset('images/ta.jpeg')); ?>" class="mb-3" style="height:150px; object-fit:contain;">
                    <h6 class="fw-bold">Pengajuan Judul</h6>
                    <p class="text-muted small">Ajukan judul dan topik TA</p>
                </div>

            </a>
        </div>

        <!-- CARD 2 -->
        <div class="col-md-4 col-lg-3">
            <a href="<?php echo e(session('user') ? route('proposal') : '/login'); ?>" style="text-decoration:none; color:inherit;">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card text-center">
                    <img src="<?php echo e(asset('images/proposal.jpeg')); ?>" class="mb-3" style="height:150px; object-fit:contain;">
                    <h6 class="fw-bold">Upload Proposal</h6>
                    <p class="text-muted small">Kirim proposal untuk di review</p>
                </div>
            </a>
        </div>

        <!-- CARD 3 -->
        <div class="col-md-4 col-lg-3">
            <a href="<?php echo e(session('user') ? route('proposal') : '/login'); ?>" style="text-decoration:none; color:inherit;">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card text-center">
                    <img src="<?php echo e(asset('images/bimbingan.jpeg')); ?>" class="mb-3" style="height:150px; object-fit:contain;">
                    <h6 class="fw-bold">Bimbingan</h6>
                    <p class="text-muted small">Riwayat bimbingan TA</p>
                </div>
            </a>
        </div>

        <!-- CARD 4 -->
        <div class="col-md-4 col-lg-3">
            <a href="<?php echo e(session('user') ? route('proposal') : '/login'); ?>" style="text-decoration:none; color:inherit;">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card text-center">
                    <img src="<?php echo e(asset('images/jadwal.jpeg')); ?>" class="mb-3" style="height:150px; object-fit:contain;">
                    <h6 class="fw-bold">Jadwal</h6>
                    <p class="text-muted small mb-0">Seminar & Sidang</p>
                </div>
            </a>
        </div>

    </div>
</div>

<br>

<!-- STATUS -->
<div class="card shadow-sm p-4" style="border-radius:15px;">

    <h5 class="fw-bold mb-3">Status Tugas Akhir</h5>

    <?php if(session('user')): ?>

    <!-- ✅ SUDAH LOGIN -->
    <table class="table table-borderless mb-0">
        <tr>
            <td width="150">Status</td>
            <td width="10">:</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Topik</td>
            <td>:</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Bimbingan</td>
            <td>:</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Seminar</td>
            <td>:</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Sidang</td>
            <td>:</td>
            <td>-</td>
        </tr>
    </table>

    <?php else: ?>

    <!-- 🔥 BELUM LOGIN (CTA) -->
    <div class="text-center py-5">
        <i class="fa-solid fa-lock mb-3" style="font-size:30px; color:#ccc;"></i>
        <p class="text-muted mb-3">
            Silahkan login untuk melihat progres tugas akhir anda
        </p>

        <a href="/login" class="btn btn-warning px-4">
            Login Sekarang
        </a>

    </div>

    <?php endif; ?>

</div>

<!-- STYLE -->
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\sitasita2\sitasita\resources\views/mahasiswa/index.blade.php ENDPATH**/ ?>