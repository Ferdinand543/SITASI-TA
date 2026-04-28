@extends('layouts.app')

@section('content')

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

    <!-- MENU -->
    <div class="row g-4 justify-content-center text-center mb-4">

        <!-- CARD 1 -->
        <div class="col-md-3">
            <a href="{{ session('user') ? route('pengajuan') : '/login' }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                    <img src="{{ asset('images/ta.jpeg') }}" class="menu-img mb-3">
                    <h6 class="fw-bold">Pengajuan Judul</h6>
                    <p class="text-muted small mb-0">Kelola Judul Mahasiswa</p>
                </div>
            </a>
        </div>

        <!-- CARD 2 -->
        <div class="col-md-3">
            <a href="{{ session('user') ? route('proposal') : '/login' }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                    <img src="{{ asset('images/proposal.jpeg') }}" class="menu-img mb-3">
                    <h6 class="fw-bold">Proposal</h6>
                    <p class="text-muted small mb-0">Kelola Proposal Mahasiswa</p>
                </div>
            </a>
        </div>

        <!-- CARD 3 -->
        <div class="col-md-3">
            <a href="{{ session('user') ? '#' : '/login' }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                    <img src="{{ asset('images/bimbingan.jpeg') }}" class="menu-img mb-3">
                    <h6 class="fw-bold">Riwayat Bimbingan</h6>
                    <p class="text-muted small mb-0">Riwayat bimbingan TA</p>
                </div>
            </a>
        </div>

        <!-- CARD 4 -->
        <div class="col-md-3">
            <a href="{{ session('user') ? '#' : '/login' }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                    <img src="{{ asset('images/penilaian.jpeg') }}" class="menu-img mb-3">
                    <h6 class="fw-bold">Penilaian</h6>
                    <p class="text-muted small mb-0">Kelola Penilaian Seminar</p>
                </div>
            </a>
        </div>
    </div>


    <!-- ROW KE-2 -->
    <div class="row g-4 justify-content-center text-center">

        <!-- MAHASISWA -->
        <div class="col-md-3">
            <a href="{{ session('user') ? route('pengajuan') : '/login' }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                    <img src="{{ asset('images/mahasiswa.jpeg') }}" class="menu-img mb-3">
                    <h6 class="fw-bold">Mahasiswa</h6>
                    <p class="text-muted small mb-0">Kelola Data Mahasiswa</p>
                </div>
            </a>
        </div>

        <!-- CARD JADWAL -->
        <div class="col-md-3">
            <a href="{{ session('user') ? route('pengajuan') : '/login' }}" style="text-decoration:none; color:inherit;">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                    <img src="{{ asset('images/jadwal.jpeg') }}" class="menu-img mb-3">
                    <h6 class="fw-bold">Jadwal</h6>
                    <p class="text-muted small mb-0">Pelaksanaan TA</p>
                </div>
        </div>

    </div>

</div>
<br>

<!-- STATUS -->
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

@endsection