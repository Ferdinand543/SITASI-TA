<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>SITASI TA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #FACC15;
            --primary-dark: #d4a00e;
            --neutral: #1E293B;
            --sidebar-bg: #ffffff;
            --sidebar-width: 220px;
            --topbar-height: 56px;
            --tertiary: #FFFDF5;
            --text-muted-custom: #6b7280;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Hanken Grotesk', sans-serif;
            background: #f5f6fa;
            color: #735C00;
            margin: 0;
            padding: 0;
        }

        /* ============================================================
           SIDEBAR
           ============================================================ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
            border-right: 1px solid #e5e7eb;
            box-shadow: 2px 0 10px rgba(0,0,0,0.03);
            transition: transform 0.3s ease, width 0.3s ease;
        }
        .sidebar.collapsed { transform: translateX(-100%); }

        .sidebar-brand {
            padding: 18px 20px 14px 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .sidebar-brand .brand-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--primary-dark);
            letter-spacing: -0.3px;
        }
        .sidebar-brand .brand-subtitle {
            font-size: 0.65rem;
            color: #94a3b8;
            margin-top: 1px;
        }

        .sidebar-nav { padding: 16px 12px; flex: 1; }

        .nav-label {
            font-size: 0.6rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 0 8px;
            margin: 14px 0 6px 0;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            color: #735C00;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 600;
            transition: 0.2s;
            margin-bottom: 4px;
            white-space: nowrap;
            border: none;
            background: none;
            width: 100%;
            cursor: pointer;
            text-align: left;
        }
        .sidebar-link i {
            width: 16px;
            font-size: 0.85rem;
            text-align: center;
            flex-shrink: 0;
            color: #735C00;
        }
        .sidebar-link:hover {
            background: #FFE083;
            color: #735C00;
            text-decoration: none;
        }
        .sidebar-link:hover i { color: #735C00; }
        .sidebar-link.active {
            background: #FFE083;
            color: #735C00;
            font-weight: 700;
        }
        .sidebar-link.active i { color: #735C00; }

        /* ── LOCKED MENU ITEM ── */
        .sidebar-link-locked {
            opacity: 0.4;
            filter: grayscale(60%);
            cursor: pointer;
        }
        .sidebar-link-locked:hover {
            background: #fee2e2 !important;
            color: #dc2626 !important;
            opacity: 0.7;
        }
        .sidebar-link-locked:hover i { color: #dc2626 !important; }

        .sidebar-footer {
            padding: 14px 12px;
            border-top: 1px solid #f1f5f9;
        }
        .sidebar-footer .sidebar-link { color: #dc2626; }
        .sidebar-footer .sidebar-link i { color: #dc2626; }

        /* ============================================================
           TOPBAR
           ============================================================ */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 999;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            transition: left 0.3s ease;
        }
        body.sidebar-collapsed .topbar { left: 0; }

        .topbar-left { display: flex; align-items: center; gap: 12px; }

        .btn-hamburger {
            width: 36px;
            height: 36px;
            border: none;
            background: #f3f4f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #735C00;
            font-size: 1rem;
            transition: 0.2s;
            flex-shrink: 0;
        }
        .btn-hamburger:hover { background: #FFE083; color: #735C00; }

        .topbar-logo-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--tertiary);
            border-radius: 10px;
            padding: 5px 12px;
        }
        .topbar-logo-badge img {
            width: 25px;
            height: 25px;
            object-fit: contain;
            border-radius: 4px;
        }
        .topbar-logo-badge span {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--neutral);
        }

        .topbar-right { display: flex; align-items: center; gap: 10px; }

        .topbar-user { display: flex; align-items: center; gap: 8px; }
        .topbar-user .user-info { text-align: right; line-height: 1.05; }
        .topbar-user .user-nim { font-size: 0.82rem; font-weight: 700; color: #111827; }
        .topbar-user .user-role { font-size: 0.7rem; color: #374151; font-weight: 500; }
        .topbar-user .user-avatar {
            width: auto; height: auto;
            background: transparent; border: none; border-radius: 0;
            display: flex; align-items: center; justify-content: center;
            color: #111827; font-size: 1.2rem; padding: 0;
        }

        /* ============================================================
           MAIN WRAPPER
           ============================================================ */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        body.sidebar-collapsed .main-wrapper { margin-left: 0; }
        .main-content { padding: 24px; }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.35);
            z-index: 999;
        }
        .sidebar-overlay.show { display: block; }

        /* ============================================================
           MISC
           ============================================================ */
        .badge-status { padding: 6px 14px; border-radius: 20px; font-size: 13px; }
        .approved { background: #c8e6c9; color: #2e7d32; }
        .rejected { background: #ffcdd2; color: #c62828; }
        .pending  { background: #ffe082; color: #8d6e00; }

        .modal-content { border-radius: 15px; padding: 10px; }
        .modal-body label { font-size: 14px; font-weight: 500; }
        .modal-body input,
        .modal-body textarea { border-radius: 8px; }
        .is-invalid { border: 2px solid #dc3545 !important; }

        .form-header-box {
            background: #f1f1f1;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            min-width: 400px;
        }

        /* ============================================================
           RESPONSIVE
           ============================================================ */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0) !important; }
            .topbar { left: 0 !important; }
            .main-wrapper { margin-left: 0 !important; }
            .main-content { padding: 16px; }
        }
    </style>
</head>

<body>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    {{-- POPUP AKSES DITOLAK (global, dipakai sidebar locked) --}}
    <div id="popupSidebarDenied" style="
        display:none; position:fixed; inset:0;
        background:rgba(0,0,0,0.45); z-index:99999;
        align-items:center; justify-content:center;">
        <div style="
            background:#fff; border-radius:20px;
            padding:40px 32px 32px; max-width:360px;
            width:90%; text-align:center;
            box-shadow:0 12px 40px rgba(0,0,0,0.2);">
            <div style="
                width:64px; height:64px; border-radius:50%;
                background:#fdecea; border:3px solid #fca5a5;
                display:inline-flex; align-items:center; justify-content:center;
                margin-bottom:16px; font-size:1.6rem; color:#dc2626;">
                <i class="fa fa-lock"></i>
            </div>
            <div style="font-size:1.2rem; font-weight:800; color:#111; margin-bottom:8px;">Akses Ditolak</div>
            <div id="popupSidebarMsg" style="font-size:0.88rem; color:#555; margin-bottom:22px; line-height:1.5;"></div>
            <button onclick="document.getElementById('popupSidebarDenied').style.display='none'" style="
                padding:10px 32px; border-radius:10px; border:none;
                background:#FACC15; color:#333; font-size:0.92rem;
                font-weight:700; cursor:pointer;">OK</button>
        </div>
    </div>

    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand">
            <div>
                <div class="brand-title">SITASI-TA</div>
                <div class="brand-subtitle">Sistem Pengelolaan TA</div>
            </div>
            <button class="btn-hamburger" id="sidebarToggle" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <nav class="sidebar-nav">

            @php
                $role = session('user')->role ?? '';

                if ($role == 'mahasiswa') {
                    $dashboardUrl = url('mahasiswa');
                    $isActive = request()->is('mahasiswa');
                } elseif ($role == 'dosen') {
                    $dashboardUrl = url('dashboard/dosen');
                    $isActive = request()->is('dashboard/dosen');
                } elseif ($role == 'admin') {
                    $dashboardUrl = url('admin');
                    $isActive = request()->is('admin');
                } elseif ($role == 'koordinator') {
                    $dashboardUrl = url('koordinator');
                    $isActive = request()->is('koordinator');
                } else {
                    $dashboardUrl = url('/');
                    $isActive = false;
                }
            @endphp

            <a href="{{ $dashboardUrl }}" class="sidebar-link {{ $isActive ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i>
                @if($role === 'dosen') Beranda @else Dashboard @endif
            </a>

            {{-- ══════════ MAHASISWA ══════════ --}}
            @if($role === 'mahasiswa')

                <div class="nav-label">Tugas Akhir</div>

                <a href="{{ session('user') ? route('pengajuan.mahasiswa') : '/login' }}"
                    class="sidebar-link {{ request()->is('pengajuan*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-circle-plus"></i>
                    Pengajuan Judul
                </a>

                <a href="{{ route('proposal.mahasiswa') }}"
                    class="sidebar-link {{ request()->is('proposal*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-arrow-up"></i>
                    Proposal
                </a>

                <a href="{{ url('/bimbingan') }}"
                    class="sidebar-link {{ request()->is('bimbingan*') ? 'active' : '' }}">
                    <i class="fa-solid fa-comments"></i>
                    Riwayat Bimbingan
                </a>

                <a href="#" class="sidebar-link {{ request()->is('seminar*') ? 'active' : '' }}">
                    <i class="fa-solid fa-rectangle-list"></i>
                    Daftar Seminar
                </a>

                <div class="nav-label">Akademik</div>

                <a href="#" class="sidebar-link {{ request()->is('nilai*') ? 'active' : '' }}">
                    <i class="fa-solid fa-star"></i>
                    Nilai
                </a>

                <a href="{{ route('jadwal.index') }}"
                    class="sidebar-link {{ request()->is('jadwal*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    Jadwal
                </a>

                <a href="#" class="sidebar-link {{ request()->is('dosen-pembimbing*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    Dosen Pembimbing
                </a>

                <a href="{{ url('/panduan-ta/mahasiswa') }}"
                    class="sidebar-link {{ request()->is('panduan-ta*') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-open"></i>
                    Panduan TA
                </a>

            @endif

            {{-- ══════════ DOSEN ══════════ --}}
            @if($role === 'dosen')

                @php
                    $nimSesi      = session('user')->nim_nid;
                    $rolesDb      = DB::table('dosen_roles')
                                      ->where('nim_nid', $nimSesi)
                                      ->pluck('role_dosen')
                                      ->toArray();
                    $isKoor       = in_array('koordinator', $rolesDb);
                    $isReviewer   = in_array('reviewer',    $rolesDb);
                    $isPembimbing = in_array('pembimbing',  $rolesDb);
                    $isPenguji    = in_array('penguji',     $rolesDb);
                @endphp

                <div class="nav-label">Tugas Akhir</div>

                {{-- ── Pengajuan Judul ── --}}
                @if($isKoor)
                    <a href="{{ route('pengajuan') }}"
                       class="sidebar-link {{ request()->is('pengajuan*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-circle-plus"></i>
                        Pengajuan Judul
                    </a>
                @else
                    <button class="sidebar-link sidebar-link-locked"
                            onclick="showSidebarDenied('Halaman ini khusus untuk Dosen Koordinator.')">
                        <i class="fa-solid fa-file-circle-plus"></i>
                        Pengajuan Judul
                    </button>
                @endif

                {{-- ── Proposal ── --}}
                @if($isKoor)
                    <a href="{{ route('proposal.index') }}"
                       class="sidebar-link {{ request()->is('proposal*') && !request()->is('reviewer*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-arrow-up"></i>
                        Proposal
                    </a>
                @elseif($isReviewer)
                    <a href="{{ route('reviewer.proposal') }}"
                       class="sidebar-link {{ request()->is('reviewer*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-arrow-up"></i>
                        Proposal
                    </a>
                @else
                    <button class="sidebar-link sidebar-link-locked"
                            onclick="showSidebarDenied('Halaman ini khusus untuk Dosen Koordinator atau Reviewer.')">
                        <i class="fa-solid fa-file-arrow-up"></i>
                        Proposal
                    </button>
                @endif

                {{-- ── Riwayat Bimbingan ── --}}
                @if($isPembimbing)
                    <a href="{{ route('dosen.bimbingan.index') }}"
                       class="sidebar-link {{ request()->is('bimbingan*') ? 'active' : '' }}">
                        <i class="fa-solid fa-comments"></i>
                        Riwayat Bimbingan
                    </a>
                @else
                    <button class="sidebar-link sidebar-link-locked"
                            onclick="showSidebarDenied('Halaman ini khusus untuk Dosen Pembimbing.')">
                        <i class="fa-solid fa-comments"></i>
                        Riwayat Bimbingan
                    </button>
                @endif

                <a href="#" class="sidebar-link {{ request()->is('seminar*') ? 'active' : '' }}">
                    <i class="fa-solid fa-rectangle-list"></i>
                    Daftar Seminar
                </a>

                <div class="nav-label">Akademik</div>

                {{-- ── Nilai ── --}}
                @if($isPembimbing || $isPenguji)
                    <a href="#" class="sidebar-link {{ request()->is('nilai*') ? 'active' : '' }}">
                        <i class="fa-solid fa-star"></i>
                        Nilai
                    </a>
                @else
                    <button class="sidebar-link sidebar-link-locked"
                            onclick="showSidebarDenied('Akses Ditolak. Halaman ini khusus untuk Dosen Pembimbing dan Dosen Penguji.')">
                        <i class="fa-solid fa-star"></i>
                        Nilai
                    </button>
                @endif

                <a href="{{ route('jadwal.index') }}"
                    class="sidebar-link {{ request()->is('jadwal*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    Jadwal
                </a>

                {{-- ── Mahasiswa (khusus Koordinator) ── --}}
                @if($isKoor)
                    <a href="{{ route('dosen.mahasiswa') }}"
                       class="sidebar-link {{ request()->is('dosen/mahasiswa*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i>
                        Mahasiswa
                    </a>
                @else
                    <button class="sidebar-link sidebar-link-locked"
                            onclick="showSidebarDenied('Halaman ini khusus untuk Dosen Koordinator.')">
                        <i class="fa-solid fa-users"></i>
                        Mahasiswa
                    </button>
                @endif

                <a href="{{ url('/panduan-ta/dosen') }}"
                    class="sidebar-link {{ request()->is('panduan-ta*') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-open"></i>
                    Panduan TA
                </a>

            @endif

            {{-- ══════════ KOORDINATOR ══════════ --}}
            @if($role === 'koordinator')

                <div class="nav-label">Tugas Akhir</div>

                <a href="#" class="sidebar-link {{ request()->is('pengajuan*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-circle-plus"></i>
                    Pengajuan Judul
                </a>

                <a href="#" class="sidebar-link {{ request()->is('proposal*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-arrow-up"></i>
                    Upload Proposal
                </a>

                <a href="#" class="sidebar-link {{ request()->is('bimbingan*') ? 'active' : '' }}">
                    <i class="fa-solid fa-comments"></i>
                    Riwayat Bimbingan
                </a>

                <a href="#" class="sidebar-link {{ request()->is('seminar*') ? 'active' : '' }}">
                    <i class="fa-solid fa-rectangle-list"></i>
                    Daftar Seminar
                </a>

                <div class="nav-label">Akademik</div>

                <a href="#" class="sidebar-link {{ request()->is('nilai*') ? 'active' : '' }}">
                    <i class="fa-solid fa-star"></i>
                    Nilai
                </a>

                <a href="#" class="sidebar-link {{ request()->is('jadwal*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    Jadwal
                </a>

                <a href="#" class="sidebar-link {{ request()->is('dosen-pembimbing*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    Dosen Pembimbing
                </a>

            @endif

            {{-- ══════════ ADMIN ══════════ --}}
            @if($role === 'admin')

                <div class="nav-label">Tugas Akhir</div>

                <a href="#" class="sidebar-link {{ request()->is('pengajuan*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-circle-plus"></i>
                    Pengajuan Judul
                </a>

                <a href="#" class="sidebar-link {{ request()->is('proposal*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-arrow-up"></i>
                    Upload Proposal
                </a>

                <a href="#" class="sidebar-link {{ request()->is('bimbingan*') ? 'active' : '' }}">
                    <i class="fa-solid fa-comments"></i>
                    Riwayat Bimbingan
                </a>

                <a href="#" class="sidebar-link {{ request()->is('seminar*') ? 'active' : '' }}">
                    <i class="fa-solid fa-rectangle-list"></i>
                    Daftar Seminar
                </a>

                <div class="nav-label">Akademik</div>

                <a href="#" class="sidebar-link {{ request()->is('nilai*') ? 'active' : '' }}">
                    <i class="fa-solid fa-star"></i>
                    Nilai
                </a>

                <a href="#" class="sidebar-link {{ request()->is('jadwal*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    Jadwal
                </a>

                <a href="#" class="sidebar-link {{ request()->is('dosen-pembimbing*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    Dosen Pembimbing
                </a>

            @endif

            {{-- ══ PROFIL — otomatis sesuai role ══ --}}
            @php
                $profilUrl = match($role) {
                    'mahasiswa' => route('mahasiswa.profil'),
                    'admin'     => route('admin.profil'),
                    'dosen'     => route('dosen.profil'),
                    default     => '#',
                };
            @endphp
            <a href="{{ $profilUrl }}"
               class="sidebar-link {{ request()->is('mahasiswa/profil*') || request()->is('admin/profil*') || request()->is('dosen/profil*') ? 'active' : '' }}">
                <i class="fa-solid fa-circle-user"></i>
                Profil
            </a>

        </nav>

        <div class="sidebar-footer">
            <a href="/logout" class="sidebar-link">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                Logout
            </a>
        </div>

    </aside>

    <header class="topbar">

        <div class="topbar-left">
            <button class="btn-hamburger" id="topbarToggle" onclick="toggleSidebar()" style="display:none;">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="topbar-logo-badge">
                <img src="{{ asset('images/SI.jpeg') }}" alt="Logo SI">
                <span>S1 – Sistem Informasi Unjani</span>
            </div>
        </div>

        <div class="topbar-right">
            @if(session('user'))
            <div class="topbar-user">
                <div class="user-info">
                    <div class="user-nim">{{ session('user')->nim_nid }}</div>
                    <div class="user-role">{{ ucfirst(session('user')->role) }}</div>
                </div>
                <div class="user-avatar">
                    <i class="fa-solid fa-user" style="font-size:0.9rem;"></i>
                </div>
            </div>
            @else
            <a href="/login" class="btn btn-sm btn-outline-dark"
                style="border-radius:8px;font-size:0.8rem;font-weight:600;">
                Masuk
            </a>
            @endif
        </div>

    </header>

    <div class="main-wrapper" id="mainWrapper">
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const topbarToggle = document.getElementById('topbarToggle');
        const isMobile = () => window.innerWidth <= 768;

        function updateTopbarToggle() {
            if (!isMobile()) {
                topbarToggle.style.display = document.body.classList.contains('sidebar-collapsed') ? 'flex' : 'none';
            } else {
                topbarToggle.style.display = 'none';
            }
        }

        function toggleSidebar() {
            if (isMobile()) {
                const isOpen = sidebar.classList.contains('open');
                if (isOpen) {
                    closeSidebar();
                } else {
                    sidebar.classList.add('open');
                    overlay.classList.add('show');
                }
            } else {
                const isCollapsed = document.body.classList.contains('sidebar-collapsed');
                if (isCollapsed) {
                    document.body.classList.remove('sidebar-collapsed');
                    sidebar.classList.remove('collapsed');
                    localStorage.setItem('sidebarCollapsed', 'false');
                } else {
                    document.body.classList.add('sidebar-collapsed');
                    sidebar.classList.add('collapsed');
                    localStorage.setItem('sidebarCollapsed', 'true');
                }
                updateTopbarToggle();
            }
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        }

        function showSidebarDenied(msg) {
            document.getElementById('popupSidebarMsg').innerText = msg;
            document.getElementById('popupSidebarDenied').style.display = 'flex';
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (!isMobile()) {
                const collapsed = localStorage.getItem('sidebarCollapsed');
                if (collapsed === 'true') {
                    document.body.classList.add('sidebar-collapsed');
                    sidebar.classList.add('collapsed');
                }
                updateTopbarToggle();
            }
        });

        window.addEventListener('resize', function() {
            if (!isMobile()) {
                overlay.classList.remove('show');
                sidebar.classList.remove('open');
            }
            updateTopbarToggle();
        });
    </script>

    {{-- SweetAlert: success umum --}}
    @if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#FACC15',
                confirmButtonText: 'OK'
            });
        });
    </script>
    @endif

    {{-- SweetAlert: proposal success --}}
    @if(session('proposal_success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('proposal_success') }}",
                icon: 'success',
                confirmButtonColor: '#FACC15',
                confirmButtonText: 'OK'
            });
        });
    </script>
    @endif

    {{-- SweetAlert: proposal error --}}
    @if(session('proposal_error'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: 'Gagal!',
                text: "{{ session('proposal_error') }}",
                icon: 'error',
                confirmButtonColor: '#FACC15',
                confirmButtonText: 'OK'
            });
        });
    </script>
    @endif

</body>

</html>