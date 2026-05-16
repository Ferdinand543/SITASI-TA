@extends('layouts.app')

@section('content')

@php
    $nimSesi      = session('user')->nim_nid;
    $rolesDb      = \Illuminate\Support\Facades\DB::table('dosen_roles')
                      ->where('nim_nid', $nimSesi)
                      ->pluck('role_dosen')
                      ->toArray();
    $isKoor       = in_array('koordinator', $rolesDb);
    $isReviewer   = in_array('reviewer',    $rolesDb);
    $isPembimbing = in_array('pembimbing',  $rolesDb);
    $isPenguji    = in_array('penguji',     $rolesDb);

    $proposalUrl  = $isKoor ? route('proposal.index') : ($isReviewer ? route('reviewer.proposal') : '#');
    $pengajuanUrl = $isKoor ? route('pengajuan') : '#';
    $bimbinganUrl = '#'; // belum ada route
    $penilaianUrl = '#'; // belum ada route

    $adaPengajuanBaru = $isKoor
        ? \Illuminate\Support\Facades\DB::table('pengajuan_judul')->where('status', 'menunggu verifikasi')->exists()
        : false;

    $jumlahMenungguProposal = $isKoor
    ? \Illuminate\Support\Facades\DB::table('proposal')->where('status', 'menunggu_verifikasi')->count()
    : ($isReviewer
        ? \Illuminate\Support\Facades\DB::table('proposal')->where('status', 'menunggu_review')->count()
        : 0);
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
            @if($isKoor)
                <a href="{{ $pengajuanUrl }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm h-100 border-0 p-3 menu-card" style="position:relative;">
                        @if($adaPengajuanBaru)<span class="badge-notif"></span>@endif
                        <img src="{{ asset('images/ta.jpeg') }}" class="menu-img mb-3">
                        <h6 class="fw-bold">Pengajuan Judul</h6>
                        <p class="text-muted small mb-0">Kelola Judul Mahasiswa</p>
                    </div>
                </a>
            @else
                <div style="cursor:pointer;" onclick="showAccessDenied('Akses Ditolak. Halaman ini khusus untuk Dosen Koordinator.')">
                    <div class="card shadow-sm h-100 border-0 p-3 menu-card menu-card-disabled" style="position:relative;">
                        <div class="lock-icon"><i class="fa fa-lock"></i></div>
                        <img src="{{ asset('images/ta.jpeg') }}" class="menu-img mb-3">
                        <h6 class="fw-bold">Pengajuan Judul</h6>
                        <p class="text-muted small mb-0">Kelola Judul Mahasiswa</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- CARD PROPOSAL --}}
        <div class="col-md-3">
            @if($isKoor || $isReviewer)
                <a href="{{ $proposalUrl }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm h-100 border-0 p-3 menu-card" style="position:relative;">
                        @if($jumlahMenungguProposal > 0)<span class="badge-notif"></span>@endif
                        <img src="{{ asset('images/proposal.jpeg') }}" class="menu-img mb-3">
                        <h6 class="fw-bold">Proposal</h6>
                        <p class="text-muted small mb-0">Kelola Proposal Mahasiswa</p>
                    </div>
                </a>
            @else
                <div style="cursor:pointer;" onclick="showAccessDenied('Akses Ditolak. Halaman ini khusus untuk Dosen Koordinator dan Reviewer.')">
                    <div class="card shadow-sm h-100 border-0 p-3 menu-card menu-card-disabled" style="position:relative;">
                        <div class="lock-icon"><i class="fa fa-lock"></i></div>
                        <img src="{{ asset('images/proposal.jpeg') }}" class="menu-img mb-3">
                        <h6 class="fw-bold">Proposal</h6>
                        <p class="text-muted small mb-0">Kelola Proposal Mahasiswa</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- CARD RIWAYAT BIMBINGAN --}}
        <div class="col-md-3">
            @if($isPembimbing)
                <a href="{{ $bimbinganUrl }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                        <img src="{{ asset('images/bimbingan.jpeg') }}" class="menu-img mb-3">
                        <h6 class="fw-bold">Riwayat Bimbingan</h6>
                        <p class="text-muted small mb-0">Riwayat bimbingan TA</p>
                    </div>
                </a>
            @else
                <div style="cursor:pointer;" onclick="showAccessDenied('Akses Ditolak. Halaman ini khusus untuk Dosen Pembimbing.')">
                    <div class="card shadow-sm h-100 border-0 p-3 menu-card menu-card-disabled">
                        <div class="lock-icon"><i class="fa fa-lock"></i></div>
                        <img src="{{ asset('images/bimbingan.jpeg') }}" class="menu-img mb-3">
                        <h6 class="fw-bold">Riwayat Bimbingan</h6>
                        <p class="text-muted small mb-0">Riwayat bimbingan TA</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- CARD PENILAIAN --}}
        <div class="col-md-3">
            @if($isPenguji)
                <a href="{{ $penilaianUrl }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                        <img src="{{ asset('images/penilaian.jpeg') }}" class="menu-img mb-3">
                        <h6 class="fw-bold">Penilaian</h6>
                        <p class="text-muted small mb-0">Kelola Penilaian Seminar</p>
                    </div>
                </a>
            @else
                <div style="cursor:pointer;" onclick="showAccessDenied('Akses Ditolak. Halaman ini khusus untuk Dosen Pembimbing dan Dosen Penguji.')">
                    <div class="card shadow-sm h-100 border-0 p-3 menu-card menu-card-disabled">
                        <div class="lock-icon"><i class="fa fa-lock"></i></div>
                        <img src="{{ asset('images/penilaian.jpeg') }}" class="menu-img mb-3">
                        <h6 class="fw-bold">Penilaian</h6>
                        <p class="text-muted small mb-0">Kelola Penilaian Seminar</p>
                    </div>
                </div>
            @endif
        </div>

    </div>

    <!-- MENU ROW 2 -->
    <div class="row g-4 justify-content-start text-center">

        {{-- CARD MAHASISWA --}}
        <div class="col-md-3">
            @if($isKoor)
                <a href="{{ route('pengajuan') }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                        <img src="{{ asset('images/mahasiswa.jpeg') }}" class="menu-img mb-3">
                        <h6 class="fw-bold">Mahasiswa</h6>
                        <p class="text-muted small mb-0">Kelola Data Mahasiswa</p>
                    </div>
                </a>
            @else
                <div style="cursor:pointer;" onclick="showAccessDenied('Akses Ditolak. Halaman ini khusus untuk Dosen Koordinator.')">
                    <div class="card shadow-sm h-100 border-0 p-3 menu-card menu-card-disabled">
                        <div class="lock-icon"><i class="fa fa-lock"></i></div>
                        <img src="{{ asset('images/mahasiswa.jpeg') }}" class="menu-img mb-3">
                        <h6 class="fw-bold">Mahasiswa</h6>
                        <p class="text-muted small mb-0">Kelola Data Mahasiswa</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- CARD JADWAL --}}
        <div class="col-md-3">
            @if($isKoor)
                <a href="#" class="text-decoration-none text-dark">
                    <div class="card shadow-sm h-100 border-0 p-3 menu-card">
                        <img src="{{ asset('images/jadwal.jpeg') }}" class="menu-img mb-3">
                        <h6 class="fw-bold">Jadwal</h6>
                        <p class="text-muted small mb-0">Pelaksanaan TA</p>
                    </div>
                </a>
            @else
                <div style="cursor:pointer;" onclick="showAccessDenied('Akses Ditolak. Halaman ini khusus untuk Dosen Koordinator.')">
                    <div class="card shadow-sm h-100 border-0 p-3 menu-card menu-card-disabled">
                        <div class="lock-icon"><i class="fa fa-lock"></i></div>
                        <img src="{{ asset('images/jadwal.jpeg') }}" class="menu-img mb-3">
                        <h6 class="fw-bold">Jadwal</h6>
                        <p class="text-muted small mb-0">Pelaksanaan TA</p>
                    </div>
                </div>
            @endif
        </div>

    </div>

</div>

{{-- POPUP AKSES DITOLAK --}}
<div id="popupAksesDitolak" style="
    display:none; position:fixed; inset:0;
    background:rgba(0,0,0,0.45); z-index:99999;
    align-items:center; justify-content:center;">
    <div style="
        background:#fff; border-radius:20px;
        padding:40px 32px 32px; max-width:380px;
        width:90%; text-align:center;
        box-shadow:0 12px 40px rgba(0,0,0,0.2);">
        <div style="
            width:72px; height:72px; border-radius:50%;
            background:#fdecea; border:3px solid #fca5a5;
            display:inline-flex; align-items:center; justify-content:center;
            margin-bottom:18px; font-size:2rem; color:#dc2626;">
            <i class="fa fa-lock"></i>
        </div>
        <div style="font-size:1.3rem; font-weight:800; color:#111; margin-bottom:8px;">Akses Ditolak</div>
        <div id="popupAksesMsg" style="font-size:0.9rem; color:#555; margin-bottom:24px; line-height:1.5;"></div>
        <button onclick="closeAccessDenied()" style="
            padding:10px 32px; border-radius:10px; border:none;
            background:#FACC15; color:#333; font-size:0.95rem;
            font-weight:700; cursor:pointer;">OK</button>
    </div>
</div>
<br>

<style>
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
    .hero-content h2 {
        color: #735C00;
        font-size: 1.6rem;
        margin-bottom: 4px;
    }
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
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    /* Card disabled: greyed out, no hover effect */
    .menu-card-disabled {
        opacity: 0.45;
        filter: grayscale(60%);
    }
    .menu-card-disabled:hover {
        transform: none !important;
        box-shadow: none !important;
    }
    /* Lock icon di pojok kanan atas */
    .lock-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 28px;
        height: 28px;
        background: #fee2e2;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #dc2626;
        font-size: 0.75rem;
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

<script>
    function showAccessDenied(msg) {
        document.getElementById('popupAksesMsg').innerText = msg;
        document.getElementById('popupAksesDitolak').style.display = 'flex';
    }
    function closeAccessDenied() {
        document.getElementById('popupAksesDitolak').style.display = 'none';
    }
</script>

@endsection