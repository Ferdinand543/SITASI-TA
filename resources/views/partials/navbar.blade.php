<nav class="navbar navbar-light bg-white shadow-sm">
    <div class="container d-flex align-items-center justify-content-between">

        <!-- KIRI: LOGO -->
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/SI.jpeg') }}" width="35" class="me-2">
            <strong>S1 - Sistem Informasi</strong>
        </div>

        <!-- KANAN: USER INFO -->
        <div class="d-flex align-items-center gap-2">
            @if(session('user'))
                <div class="text-end me-1" style="line-height:1.2;">
                    <div class="fw-bold" style="font-size:0.9rem;">{{ session('user')->nim_nid }}</div>
                    <div class="text-muted" style="font-size:0.78rem;">{{ session('user')->role }}</div>
                </div>
                <i class="fa-regular fa-circle-user" style="font-size:1.5rem; color:#555;"></i>
                <a href="/logout" class="text-dark ms-1" title="Logout">
                    <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:1.3rem;"></i>
                </a>
            @else
                <a href="/login" class="btn btn-outline-dark btn-sm">Masuk</a>
            @endif
        </div>

    </div>
</nav>

<!-- SUB-MENU NAVIGASI -->
<div class="container">
    <div class="sub-menu-wrapper">
        @if(session('user') && in_array(strtolower(trim(session('user')->role)), ['koordinator']))
            {{-- MENU KOORDINATOR --}}
            <a href="/dashboard/dosen" class="sub-menu {{ request()->is('dashboard/dosen') ? 'active' : '' }}">Beranda</a>
            <a href="#" class="sub-menu {{ request()->is('aktivitas*') ? 'active' : '' }}">Aktivitas</a>
            <a href="#" class="sub-menu">Panduan TA</a>

        @elseif(session('user') && in_array(strtolower(trim(session('user')->role)), ['dosen pembimbing', 'dosen penguji', 'dosen reviewer']))
            {{-- MENU DOSEN --}}
            <a href="/dashboard/dosen" class="sub-menu {{ request()->is('dashboard/dosen') ? 'active' : '' }}">Beranda</a>
            <a href="#" class="sub-menu">Aktivitas</a>
            <a href="#" class="sub-menu">Panduan TA</a>

        @elseif(session('user') && strtolower(trim(session('user')->role)) === 'mahasiswa')
            {{-- MENU MAHASISWA --}}
            <a href="/mahasiswa" class="sub-menu {{ request()->is('mahasiswa') ? 'active' : '' }}">Beranda</a>
            <a href="#" class="sub-menu">Aktivitas</a>
            <a href="#" class="sub-menu">Dosen Pembimbing</a>
            <a href="#" class="sub-menu">Panduan TA</a>

        @else
            {{-- BELUM LOGIN --}}
            <a href="/" class="sub-menu active">Beranda</a>
            <a href="#" class="sub-menu">Informasi</a>
            <a href="#" class="sub-menu">Panduan TA</a>
        @endif
    </div>
</div>

<style>
    .sub-menu-wrapper {
        display: flex;
        gap: 8px;
        padding: 10px 0 14px 0;
    }

    .sub-menu {
        text-decoration: none;
        color: #555;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 14px;
        transition: 0.2s;
    }

    .sub-menu:hover {
        background: #f7d27c;
        color: #000;
    }

    .sub-menu.active {
        background: #f7d27c;
        color: #000;
        font-weight: 500;
    }
</style>