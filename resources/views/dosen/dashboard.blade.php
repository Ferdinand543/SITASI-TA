@extends('layouts.app')

@section('content')

@php
$adaPengajuanBaru = \Illuminate\Support\Facades\DB::table('pengajuan_judul')
->where('status', 'menunggu verifikasi')
->exists();
@endphp

<!-- HERO -->
<div class="hero-section mb-4">
    <div class="hero-content">
        <h2 class="fw-bold">Sistem Bimbingan Tugas Akhir Mahasiswa</h2>
        <h2 class="fw-bold">Sistem Informasi</h2>
    </div>
</div>

<div class="container">

    <!-- MENU ROW 1 -->
    <div class="row g-4 justify-content-center text-center mb-4">

        {{-- CARD PENGAJUAN JUDUL --}}
        <div class="col-md-3">
            <a href="{{ session('user') ? route('pengajuan') : '/login' }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card" style="position:relative;">

                    @if($adaPengajuanBaru)
                    <span class="badge-notif"></span>
                    @endif

                    <img src="{{ asset('images/ta.jpeg') }}" class="menu-img mb-3">
                    <h6 class="fw-bold">Pengajuan Judul</h6>
                    <p class="text-muted small mb-0">Kelola Judul Mahasiswa</p>
                </div>
            </a>
        </div>

        {{-- CARD PROPOSAL --}}
        <div class="col-md-3">
            <a href="{{ route('proposal.index') }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card" style="position:relative;">

                    @if(($jumlahMenungguProposal ?? 0) > 0)
                    <span class="badge-notif"></span>
                    @endif

                    <img src="{{ asset('images/proposal.jpeg') }}" class="menu-img mb-3">
                    <h6 class="fw-bold">Proposal</h6>
                    <p class="text-muted small mb-0">Kelola Proposal Mahasiswa</p>
                </div>
            </a>
        </div>

        {{-- CARD RIWAYAT BIMBINGAN --}}
        <div class="col-md-3">
            <a href="{{ session('user') ? '#' : '/login' }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                    <img src="{{ asset('images/bimbingan.jpeg') }}" class="menu-img mb-3">
                    <h6 class="fw-bold">Riwayat Bimbingan</h6>
                    <p class="text-muted small mb-0">Riwayat bimbingan TA</p>
                </div>
            </a>
        </div>

        {{-- CARD PENILAIAN --}}
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

    <!-- MENU ROW 2 -->
    <div class="row g-4 justify-content-start text-center">

        {{-- CARD MAHASISWA --}}
        <div class="col-md-3">
            <a href="{{ session('user') ? route('pengajuan') : '/login' }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                    <img src="{{ asset('images/mahasiswa.jpeg') }}" class="menu-img mb-3">
                    <h6 class="fw-bold">Mahasiswa</h6>
                    <p class="text-muted small mb-0">Kelola Data Mahasiswa</p>
                </div>
            </a>
        </div>

        {{-- CARD JADWAL --}}
        <div class="col-md-3">
            <a href="{{ session('user') ? route('pengajuan') : '/login' }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                    <img src="{{ asset('images/jadwal.jpeg') }}" class="menu-img mb-3">
                    <h6 class="fw-bold">Jadwal</h6>
                    <p class="text-muted small mb-0">Pelaksanaan TA</p>
                </div>
            </a>
        </div>

    </div>

</div>
<br>

<style>
    /* ───────── HERO ───────── */
    .hero-section {
        background-image: url('{{ asset('images/bg.jpeg') }}');
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        padding: 48px 40px;
        position: relative;
        overflow: hidden;
        min-height: 200px;
        display: flex;
        align-items: center;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-content h2 {
        color: #735C00;
        font-size: 1.6rem;
        margin-bottom: 4px;
    }

    .hero-content p {
        color: #735C00;
        font-size: 0.88rem;
        margin-top: 10px;
        line-height: 1.7;
    }

    /* ───────── MENU BOTTOM ───────── */
    .menu-bottom-wrapper {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
    }

    .menu-bottom {
        padding: 6px 18px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #735C00;
        text-decoration: none;
        background: #f1f1f1;
        transition: 0.2s;
    }

    .menu-bottom:hover,
    .menu-bottom.active {
        background: #FACC15;
        color: #735C00;
    }

    /* ───────── CARDS ───────── */
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

    .badge-notif {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 12px;
        height: 12px;
        background: red;
        border-radius: 50%;
        display: inline-block;
        z-index: 1;
    }
</style>

@endsection