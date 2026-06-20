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

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Hanken Grotesk', sans-serif;
            background: #f5f6fa;
            color: #735C00;
            margin: 0;
            padding: 0;
        }

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
            overflow: hidden;
            border-right: 1px solid #e5e7eb;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.03);
            transition: transform 0.3s ease, width 0.3s ease;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar-brand {
            padding: 16px 20px 14px 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .sidebar-brand .brand-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #735C00;
            letter-spacing: -0.3px;
        }

        .sidebar-brand .brand-subtitle {
            font-size: 0.65rem;
            color: #4D4632;
            margin-top: 1px;
        }

        .sidebar-nav {
            padding: 10px 12px;
            flex: 1;
            overflow-y: hidden;
            min-height: 0;
        }

        .nav-label {
            font-size: 0.6rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 0 8px;
            margin: 10px 0 3px 0;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 10px;
            color: #735C00;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 600;
            transition: 0.2s;
            margin-bottom: 1px;
            white-space: nowrap;
            border: none;
            background: none;
            width: 100%;
            cursor: pointer;
            text-align: left;
            position: relative;
        }

        .sidebar-link i {
            width: 16px;
            font-size: 0.82rem;
            text-align: center;
            flex-shrink: 0;
            color: #735C00;
        }

        .sidebar-link:hover {
            background: #FFE083;
            color: #4D4632;
            text-decoration: none;
        }

        .sidebar-link:hover i {
            color: #4D4632;
        }

        .sidebar-link.active {
            background: #FFE083;
            color: #4D4632;
            font-weight: 700;
        }

        .sidebar-link.active i {
            color: #4D4632;
        }

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

        .sidebar-link-locked:hover i {
            color: #dc2626 !important;
        }

        .link-badge-notif {
            width: 8px;
            height: 8px;
            background: red;
            border-radius: 50%;
            display: inline-block;
            position: absolute;
            top: 10px;
            right: 12px;
        }

        .role-dosen .sidebar-link {
            padding: 7px 12px;
        }

        .role-mahasiswa .sidebar-link {
            padding: 10px 12px;
        }

        /* ══ ADMIN ONLY ══ */
        .role-admin .sidebar-brand {
            padding: 10px 16px 9px 16px;
        }

        .role-admin .sidebar-nav {
            padding: 4px 10px;
            overflow-y: hidden;
        }

        .role-admin .nav-label {
            margin: 4px 0 1px 0;
            font-size: 0.56rem;
        }

        .role-admin .sidebar-link {
            padding: 5px 8px;
            font-size: 0.73rem;
            margin-bottom: 0px;
        }

        .role-admin .sidebar-footer {
            padding: 6px 8px;
        }

        .sidebar-footer {
            padding: 10px 10px;
            border-top: 1px solid #f1f5f9;
            flex-shrink: 0;
        }

        .sidebar-footer .sidebar-link {
            color: #dc2626;
        }

        .sidebar-footer .sidebar-link i {
            color: #dc2626;
        }

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
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
            transition: left 0.3s ease;
        }

        body.sidebar-collapsed .topbar {
            left: 0;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

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

        .btn-hamburger:hover {
            background: #FFE083;
            color: #735C00;
        }

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

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .topbar-user .user-info {
            text-align: right;
            line-height: 1.05;
        }

        .topbar-user .user-nim {
            font-size: 0.82rem;
            font-weight: 700;
            color: #111827;
        }

        .topbar-user .user-role {
            font-size: 0.7rem;
            color: #374151;
            font-weight: 500;
        }

        .topbar-user .user-avatar {
            width: auto;
            height: auto;
            background: transparent;
            border: none;
            border-radius: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #111827;
            font-size: 1.2rem;
            padding: 0;
        }

        .topbar-user:hover {
            opacity: 0.75;
        }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        body.sidebar-collapsed .main-wrapper {
            margin-left: 0;
        }

        .main-content {
            padding: 24px;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            z-index: 999;
        }

        .sidebar-overlay.show {
            display: block;
        }

        .badge-status {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
        }

        .approved {
            background: #c8e6c9;
            color: #2e7d32;
        }

        .rejected {
            background: #ffcdd2;
            color: #c62828;
        }

        .pending {
            background: #ffe082;
            color: #8d6e00;
        }

        .modal-content {
            border-radius: 15px;
            padding: 10px;
        }

        .modal-body label {
            font-size: 14px;
            font-weight: 500;
        }

        .modal-body input,
        .modal-body textarea {
            border-radius: 8px;
        }

        .is-invalid {
            border: 2px solid #dc3545 !important;
        }

        .form-header-box {
            background: #f1f1f1;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            min-width: 400px;
        }

        /* ══ DROPDOWN SIDEBAR ══ */
        .sidebar-dropdown {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s ease;
        }
        .sidebar-chevron {
            margin-left: auto;
            font-size: 0.65rem;
            transition: transform 0.25s ease;
            flex-shrink: 0;
        }
        .sidebar-chevron.open {
            transform: rotate(90deg);
        }
        .sidebar-sublink {
            padding-left: 32px !important;
            font-size: 0.76rem !important;
            font-weight: 500 !important;
            white-space: normal !important;
            line-height: 1.3 !important;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0) !important;
            }

            .topbar {
                left: 0 !important;
            }

            .main-wrapper {
                margin-left: 0 !important;
            }

            .main-content {
                padding: 16px;
            }
        }
    </style>
</head>

<body class="role-{{ session('user')->role ?? '' }}">

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <div id="popupSidebarDenied" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:99999;align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:20px;padding:40px 32px 32px;max-width:360px;width:90%;text-align:center;box-shadow:0 12px 40px rgba(0,0,0,0.2);">
            <div style="width:64px;height:64px;border-radius:50%;background:#fdecea;border:3px solid #fca5a5;display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px;font-size:1.6rem;color:#dc2626;">
                <i class="fa fa-lock"></i>
            </div>
            <div style="font-size:1.2rem;font-weight:800;color:#111;margin-bottom:8px;">Akses Ditolak</div>
            <div id="popupSidebarMsg" style="font-size:0.88rem;color:#555;margin-bottom:22px;line-height:1.5;"></div>
            <button onclick="document.getElementById('popupSidebarDenied').style.display='none'" style="padding:10px 32px;border-radius:10px;border:none;background:#FACC15;color:#333;font-size:0.92rem;font-weight:700;cursor:pointer;">OK</button>
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

            @php
            $nimMhs = session('user')->nim_nid;

            $notifJudulMahasiswa = DB::table('pengajuan_judul')
                ->where('nim_nid', $nimMhs)
                ->whereIn('status', ['disetujui', 'ditolak'])
                ->exists();

            $notifProposalMahasiswa = DB::table('proposal')
                ->where('nim_nid', $nimMhs)
                ->whereIn('status', ['selesai', 'ditolak'])
                ->exists();
            @endphp

            <div class="nav-label">Tugas Akhir</div>
            <a href="{{ session('user') ? route('pengajuan.mahasiswa') : '/login' }}" class="sidebar-link {{ request()->is('pengajuan*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-circle-plus"></i> Pengajuan Judul
                @if($notifJudulMahasiswa)<span class="link-badge-notif"></span>@endif
            </a>
            <a href="{{ route('proposal.mahasiswa') }}" class="sidebar-link {{ request()->is('proposal*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-arrow-up"></i> Proposal
                @if($notifProposalMahasiswa)<span class="link-badge-notif"></span>@endif
            </a>
            <a href="{{ url('/bimbingan') }}" class="sidebar-link {{ request()->is('bimbingan*') ? 'active' : '' }}">
                <i class="fa-solid fa-comments"></i> Riwayat Bimbingan
            </a>
            <a href="{{ route('seminar.daftar') }}" class="sidebar-link {{ request()->is('seminar*') ? 'active' : '' }}">
                <i class="fa-solid fa-rectangle-list"></i> Daftar Seminar
            </a>
            <div class="nav-label">Akademik</div>
            <a href="{{ route('mahasiswa.hasil.penilaian') }}" class="sidebar-link {{ request()->is('nilai*') ? 'active' : '' }}">
                <i class="fa-solid fa-star"></i> Nilai
            </a>
            <a href="{{ route('jadwal.index') }}" class="sidebar-link {{ request()->is('jadwal*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-days"></i> Jadwal
            </a>
            <a href="{{ url('/panduan-ta/mahasiswa') }}" class="sidebar-link {{ request()->is('panduan-ta*') ? 'active' : '' }}">
                <i class="fa-solid fa-book-open"></i> Panduan TA
            </a>
            @endif

            {{-- ══════════ DOSEN ══════════ --}}
            @if($role === 'dosen')

            @php
            $nimSesi = session('user')->nim_nid;
            $rolesDb = DB::table('dosen_roles')
            ->where('nim_nid', $nimSesi)
            ->pluck('role_dosen')
            ->toArray();
            $isKoor = in_array('koordinator', $rolesDb);
            $isReviewer = in_array('reviewer', $rolesDb);
            $isPembimbing = in_array('pembimbing', $rolesDb);
            $isPenguji = in_array('penguji', $rolesDb);

            $punyaMahasiswaBimbingan = false;
            if ($isPembimbing) {
            $punyaMahasiswaBimbingan = DB::table('proposal as p')
            ->join('dosen_pembimbing as dp', function($join) use ($nimSesi) {
            $join->on('dp.proposal_id', '=', 'p.id')
            ->where('dp.nim_nid_dosen', '=', $nimSesi);
            })
            ->join('pengajuan_seminars as psem',
            DB::raw('psem.mahasiswa_id COLLATE utf8mb4_unicode_ci'),
            '=',
            DB::raw('p.nim_nid COLLATE utf8mb4_unicode_ci')
            )
            ->where('psem.status_administrasi', 'Lolos Administrasi')
            ->exists();
            }

            $adaPengajuanBaruSidebar = $isKoor
                ? DB::table('pengajuan_judul')->where('status', 'menunggu verifikasi')->exists()
                : false;

            $jumlahMenungguProposalSidebar = $isKoor
                ? DB::table('proposal')->whereIn('status', ['menunggu_review', 'menunggu_verifikasi'])->count()
                : ($isReviewer
                    ? DB::table('proposal')
                        ->where('status', 'menunggu_review')
                        ->whereNotNull('nim_nid_reviewer')
                        ->where('nim_nid_reviewer', $nimSesi)
                        ->count()
                    : 0);

            $jumlahBimbinganBaruSidebar = $isPembimbing
                ? DB::table('bimbingan')->where('dosen_nid', $nimSesi)->where('status', 'Baru Dikirim')->count()
                  + DB::table('pengajuan_proposal_bimbingan')->where('dosen_nid', $nimSesi)->where('status', 'pending')->count()
                : 0;

            $proposalDropdownOpen = request()->is('proposal*') || request()->is('reviewer*');
            $bimbinganDropdownOpen = request()->is('dosen/bimbingan*');
            $jadwalDropdownOpen = request()->is('jadwal') || request()->is('kelola-seminar*') || request()->is('jadwal-seminar-mahasiswa*');
            $pengujiDropdownOpen = request()->routeIs('dosen.penguji.index') || request()->routeIs('penguji.show') || request()->routeIs('penguji.tetapkan') || request()->routeIs('penguji.mahasiswa.index');
            @endphp

            <div class="nav-label">Tugas Akhir</div>

            @if($isKoor)
            <a href="{{ route('pengajuan') }}" class="sidebar-link {{ request()->is('pengajuan*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-circle-plus"></i> Pengajuan Judul
                @if($adaPengajuanBaruSidebar)<span class="link-badge-notif"></span>@endif
            </a>
            @else
            <button class="sidebar-link sidebar-link-locked" onclick="showSidebarDenied('Halaman ini khusus untuk Dosen Koordinator.')">
                <i class="fa-solid fa-file-circle-plus"></i> Pengajuan Judul
            </button>
            @endif

            {{-- PROPOSAL DROPDOWN --}}
            <button class="sidebar-link {{ $proposalDropdownOpen ? 'active' : '' }}"
                onclick="toggleDropdownProposal()">
                <i class="fa-solid fa-file-arrow-up"></i> Proposal
                @if($jumlahMenungguProposalSidebar > 0)<span class="link-badge-notif"></span>@endif
                <i class="fa-solid fa-chevron-right sidebar-chevron {{ $proposalDropdownOpen ? 'open' : '' }}" id="chevron-proposal"></i>
            </button>
            <div class="sidebar-dropdown" id="dropdown-proposal"
                style="max-height: {{ $proposalDropdownOpen ? '200px' : '0' }};">

                @if($isKoor)
                <a href="{{ route('proposal.index') }}?tab=pembimbing"
                    class="sidebar-link sidebar-sublink {{ request()->is('proposal*') && request()->query('tab') === 'pembimbing' ? 'active' : '' }}">
                    <i class="fa-solid fa-chalkboard-user"></i> Penetapan Dospem
                </a>
                @else
                <button class="sidebar-link sidebar-sublink sidebar-link-locked"
                    onclick="showSidebarDenied('Halaman ini khusus untuk Dosen Koordinator.')">
                    <i class="fa-solid fa-chalkboard-user"></i> Penetapan Dospem
                </button>
                @endif

                @if($isKoor)
                <a href="{{ route('proposal.index') }}?tab=reviewer"
                    class="sidebar-link sidebar-sublink {{ request()->is('proposal*') && request()->query('tab') === 'reviewer' ? 'active' : '' }}">
                    <i class="fa-solid fa-user-check"></i> Penetapan Reviewer
                </a>
                @else
                <button class="sidebar-link sidebar-sublink sidebar-link-locked"
                    onclick="showSidebarDenied('Halaman ini khusus untuk Dosen Koordinator.')">
                    <i class="fa-solid fa-user-check"></i> Penetapan Reviewer
                </button>
                @endif

                @if($isReviewer)
                <a href="{{ route('reviewer.proposal') }}"
                    class="sidebar-link sidebar-sublink {{ request()->is('reviewer*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-circle-check"></i> Review Proposal
                    @if($jumlahMenungguProposalSidebar > 0)<span class="link-badge-notif"></span>@endif
                </a>
                @else
                <button class="sidebar-link sidebar-sublink sidebar-link-locked"
                    onclick="showSidebarDenied('Halaman ini khusus untuk Dosen Reviewer.')">
                    <i class="fa-solid fa-file-circle-check"></i> Review Proposal
                </button>
                @endif

            </div>
            {{-- END PROPOSAL DROPDOWN --}}

            {{-- BIMBINGAN DROPDOWN --}}
            @if($isPembimbing)
            <button class="sidebar-link {{ $bimbinganDropdownOpen ? 'active' : '' }}"
                onclick="toggleDropdownBimbingan()">
                <i class="fa-solid fa-comments"></i> Riwayat Bimbingan
                @if($jumlahBimbinganBaruSidebar > 0)<span class="link-badge-notif"></span>@endif
                <i class="fa-solid fa-chevron-right sidebar-chevron {{ $bimbinganDropdownOpen ? 'open' : '' }}" id="chevron-bimbingan"></i>
            </button>
            <div class="sidebar-dropdown" id="dropdown-bimbingan"
                style="max-height: {{ $bimbinganDropdownOpen ? '200px' : '0' }};">

                <a href="{{ route('dosen.bimbingan.index') }}?tab=dokumen"
                    class="sidebar-link sidebar-sublink {{ $bimbinganDropdownOpen && request()->query('tab') === 'dokumen' ? 'active' : '' }}">
                    <i class="fa-solid fa-file-lines"></i> Dokumen Bimbingan
                </a>

                <a href="{{ route('dosen.bimbingan.index') }}?tab=mahasiswa"
                    class="sidebar-link sidebar-sublink {{ $bimbinganDropdownOpen && request()->query('tab') === 'mahasiswa' ? 'active' : '' }}">
                    <i class="fa-solid fa-user-graduate"></i> Mahasiswa Bimbingan
                </a>

            </div>
            @else
            <button class="sidebar-link sidebar-link-locked" onclick="showSidebarDenied('Halaman ini khusus untuk Dosen Pembimbing.')">
                <i class="fa-solid fa-comments"></i> Riwayat Bimbingan
            </button>
            @endif
            {{-- END BIMBINGAN DROPDOWN --}}

            @if($isPenguji || $isPembimbing)
            <a href="{{ route('dosen.mahasiswa.seminar') }}" class="sidebar-link {{ request()->routeIs('dosen.mahasiswa.seminar') ? 'active' : '' }}">
                <i class="fa-solid fa-user-graduate"></i> Mahasiswa Seminar
            </a>
            @else
            <button class="sidebar-link sidebar-link-locked" onclick="showSidebarDenied('Halaman ini khusus untuk Dosen Penguji.')">
                <i class="fa-solid fa-user-graduate"></i> Mahasiswa Seminar
            </button>
            @endif

            <div class="nav-label">Akademik</div>

            @if($isPenguji || $isPembimbing)
            <a href="{{ route('penilaian.index') }}" class="sidebar-link {{ request()->is('penilaian*') ? 'active' : '' }}">
                <i class="fa-solid fa-star"></i> Nilai
            </a>
            @else
            <button class="sidebar-link sidebar-link-locked" onclick="showSidebarDenied('Akses Ditolak. Halaman ini khusus untuk Dosen Pembimbing dan Dosen Penguji.')">
                <i class="fa-solid fa-star"></i> Nilai
            </button>
            @endif

            {{-- JADWAL DROPDOWN --}}
            <button class="sidebar-link {{ $jadwalDropdownOpen ? 'active' : '' }}"
                onclick="toggleDropdownJadwal()">
                <i class="fa-solid fa-calendar-days"></i> Jadwal
                <i class="fa-solid fa-chevron-right sidebar-chevron {{ $jadwalDropdownOpen ? 'open' : '' }}" id="chevron-jadwal"></i>
            </button>
            <div class="sidebar-dropdown" id="dropdown-jadwal"
                style="max-height: {{ $jadwalDropdownOpen ? '200px' : '0' }};">

                <a href="{{ route('jadwal.index') }}"
                    class="sidebar-link sidebar-sublink {{ request()->is('jadwal') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-check"></i> Jadwal Akademik
                </a>

                @if($isKoor)
                <a href="{{ route('jadwalseminar.index') }}"
                    class="sidebar-link sidebar-sublink {{ request()->is('kelola-seminar*') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check"></i> Kelola Jadwal Seminar
                </a>
                @else
                <button class="sidebar-link sidebar-sublink sidebar-link-locked"
                    onclick="showSidebarDenied('Halaman ini khusus untuk Dosen Koordinator.')">
                    <i class="fa-solid fa-list-check"></i> Kelola Jadwal Seminar
                </button>
                @endif

                @if($isPenguji || $isPembimbing)
                <a href="{{ route('jadwalseminar.mahasiswa.list') }}"
                    class="sidebar-link sidebar-sublink {{ request()->is('jadwal-seminar-mahasiswa*') ? 'active' : '' }}">
                    <i class="fa-solid fa-eye"></i> Lihat Jadwal Seminar
                </a>
                @else
                <button class="sidebar-link sidebar-sublink sidebar-link-locked"
                    onclick="showSidebarDenied('Halaman ini khusus untuk Dosen Pembimbing/Penguji.')">
                    <i class="fa-solid fa-eye"></i> Lihat Jadwal Seminar
                </button>
                @endif

            </div>
            {{-- END JADWAL DROPDOWN --}}

            @if($isKoor)
            <a href="{{ route('dosen.mahasiswa') }}" class="sidebar-link {{ request()->routeIs('dosen.mahasiswa') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i> Mahasiswa
            </a>
            @else
            <button class="sidebar-link sidebar-link-locked" onclick="showSidebarDenied('Halaman ini khusus untuk Dosen Koordinator.')">
                <i class="fa-solid fa-users"></i> Mahasiswa
            </button>
            @endif

            {{-- KELOLA DOSEN PENGUJI DROPDOWN --}}
            @if($isKoor)
            <button class="sidebar-link {{ $pengujiDropdownOpen ? 'active' : '' }}"
                onclick="toggleDropdownPenguji()">
                <i class="fa-solid fa-user-tie"></i> Kelola Dosen Penguji
                <i class="fa-solid fa-chevron-right sidebar-chevron {{ $pengujiDropdownOpen ? 'open' : '' }}" id="chevron-penguji"></i>
            </button>
            <div class="sidebar-dropdown" id="dropdown-penguji"
                style="max-height: {{ $pengujiDropdownOpen ? '200px' : '0' }};">
                <a href="{{ route('dosen.penguji.index') }}"
                    class="sidebar-link sidebar-sublink {{ request()->routeIs('dosen.penguji.index') || request()->routeIs('penguji.show') || request()->routeIs('penguji.tetapkan') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-tie"></i> Dosen Penguji
                </a>
                <a href="{{ route('penguji.mahasiswa.index') }}"
                    class="sidebar-link sidebar-sublink {{ request()->routeIs('penguji.mahasiswa.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-graduate"></i> Mahasiswa
                </a>
            </div>
            @else
            <button class="sidebar-link sidebar-link-locked" onclick="showSidebarDenied('Halaman ini khusus untuk Dosen Koordinator.')">
                <i class="fa-solid fa-user-tie"></i> Kelola Dosen Penguji
            </button>
            @endif
            {{-- END KELOLA DOSEN PENGUJI DROPDOWN --}}

            <a href="{{ url('/panduan-ta/dosen') }}" class="sidebar-link {{ request()->is('panduan-ta*') ? 'active' : '' }}">
                <i class="fa-solid fa-book-open"></i> Panduan TA
            </a>

            @endif

            {{-- ══════════ KOORDINATOR ══════════ --}}
            @if($role === 'koordinator')
            <div class="nav-label">Tugas Akhir</div>
            <a href="#" class="sidebar-link {{ request()->is('pengajuan*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-circle-plus"></i> Pengajuan Judul
            </a>
            <a href="#" class="sidebar-link {{ request()->is('proposal*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-arrow-up"></i> Upload Proposal
            </a>
            <a href="#" class="sidebar-link {{ request()->is('bimbingan*') ? 'active' : '' }}">
                <i class="fa-solid fa-comments"></i> Riwayat Bimbingan
            </a>

            <div class="nav-label">Akademik</div>
            <a href="#" class="sidebar-link {{ request()->is('nilai*') ? 'active' : '' }}">
                <i class="fa-solid fa-star"></i> Nilai
            </a>
            <a href="#" class="sidebar-link {{ request()->is('jadwal*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-days"></i> Jadwal
            </a>
            <a href="#" class="sidebar-link {{ request()->is('dosen-pembimbing*') ? 'active' : '' }}">
                <i class="fa-solid fa-chalkboard-user"></i> Dosen Pembimbing
            </a>
            @endif

            {{-- ══════════ ADMIN ══════════ --}}
            @if($role === 'admin')

            @php
            $notifJudulAdmin = DB::table('pengajuan_judul')
                ->where('status', 'menunggu verifikasi')
                ->exists();

            $notifProposalAdmin = DB::table('proposal')
                ->whereIn('status', ['menunggu_review', 'menunggu_verifikasi'])
                ->exists();

            $notifSeminarAdmin = DB::table('pengajuan_seminars')
                ->where('status_administrasi', 'Menunggu Verifikasi')
                ->exists();

            $proposalAdminDropdownOpen = request()->routeIs('admin.proposal.*') || request()->is('reviewer/proposal*');
            $bimbinganAdminDropdownOpen = request()->is('admin/bimbingan') || request()->is('admin/bimbingan/*');
            $jadwalAdminDropdownOpen = request()->routeIs('jadwal-akademik.*')
                || request()->is('kelola-seminar*')
                || request()->routeIs('admin.jadwal.seminar.mahasiswa');
            @endphp

            <a href="/admin/judul" class="sidebar-link {{ request()->is('admin/judul*') ? 'active' : '' }}">
                <i class="fa-regular fa-file-lines"></i> Pengajuan Judul
                @if($notifJudulAdmin)<span class="link-badge-notif"></span>@endif
            </a>

            {{-- PROPOSAL MAHASISWA DROPDOWN --}}
            <button class="sidebar-link {{ $proposalAdminDropdownOpen ? 'active' : '' }}"
                onclick="toggleDropdownProposalAdmin()">
                <i class="fa-regular fa-folder-open"></i> Proposal Mahasiswa
                @if($notifProposalAdmin)<span class="link-badge-notif"></span>@endif
                <i class="fa-solid fa-chevron-right sidebar-chevron {{ $proposalAdminDropdownOpen ? 'open' : '' }}" id="chevron-proposal-admin"></i>
            </button>
            <div class="sidebar-dropdown" id="dropdown-proposal-admin"
                style="max-height: {{ $proposalAdminDropdownOpen ? '300px' : '0' }};">

                <a href="{{ route('admin.proposal.index') }}"
                    class="sidebar-link sidebar-sublink {{ request()->routeIs('admin.proposal.index') && !request()->query('tab') ? 'active' : '' }}">
                    <i class="fa-solid fa-chalkboard-user"></i> Penetapan Dospem
                </a>

                <a href="{{ route('admin.proposal.index', ['tab' => 'reviewer']) }}"
                    class="sidebar-link sidebar-sublink {{ request()->query('tab') === 'reviewer' ? 'active' : '' }}">
                    <i class="fa-solid fa-user-check"></i> Penetapan Reviewer
                </a>

                <a href="{{ route('reviewer.proposal') }}"
                    class="sidebar-link sidebar-sublink {{ request()->is('reviewer/proposal*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-circle-check"></i> Review Proposal
                </a>

            </div>
            {{-- END PROPOSAL MAHASISWA DROPDOWN --}}

            {{-- RIWAYAT BIMBINGAN DROPDOWN (ADMIN) --}}
            <button class="sidebar-link {{ $bimbinganAdminDropdownOpen ? 'active' : '' }}"
                onclick="toggleDropdownBimbinganAdmin()">
                <i class="fa-regular fa-clock"></i> Riwayat Bimbingan
                <i class="fa-solid fa-chevron-right sidebar-chevron {{ $bimbinganAdminDropdownOpen ? 'open' : '' }}" id="chevron-bimbingan-admin"></i>
            </button>
            <div class="sidebar-dropdown" id="dropdown-bimbingan-admin"
                style="max-height: {{ $bimbinganAdminDropdownOpen ? '300px' : '0' }};">

                <a href="/admin/bimbingan"
                    class="sidebar-link sidebar-sublink {{ request()->is('admin/bimbingan') && !request()->query('tab') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-lines"></i> Proposal Bimbingan
                </a>

                <a href="/admin/bimbingan?tab=mahasiswa"
                    class="sidebar-link sidebar-sublink {{ request()->is('admin/bimbingan*') && request()->query('tab') === 'mahasiswa' ? 'active' : '' }}">
                    <i class="fa-solid fa-user-graduate"></i> Mahasiswa Bimbingan
                </a>

            </div>
            {{-- END RIWAYAT BIMBINGAN DROPDOWN (ADMIN) --}}

            <a href="{{ route('admin.seminar.index') }}" class="sidebar-link {{ request()->routeIs('admin.seminar.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-graduate"></i> Administrasi Seminar
                @if($notifSeminarAdmin)<span class="link-badge-notif"></span>@endif
            </a>
            <a href="{{ route('admin.mahasiswa-seminar') }}" class="sidebar-link {{ request()->routeIs('admin.mahasiswa-seminar') ? 'active' : '' }}">
                <i class="fa-solid fa-chalkboard-user"></i> Mahasiswa Seminar
            </a>
            <a href="{{ route('admin.mahasiswa.progress') }}" class="sidebar-link {{ request()->is('admin/mahasiswa-progress*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i> Mahasiswa
            </a>

            <div class="nav-label">Master Data</div>
            <a href="/admin/mahasiswa" class="sidebar-link {{ request()->is('admin/mahasiswa') || request()->is('admin/mahasiswa/*') ? 'active' : '' }}">
                <i class="fa-solid fa-database"></i> Data Mahasiswa
            </a>
            <a href="/admin/dosen" class="sidebar-link {{ request()->is('admin/dosen*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i> Data Dosen
            </a>

            {{-- JADWAL DROPDOWN (ADMIN) --}}
            <button class="sidebar-link {{ $jadwalAdminDropdownOpen ? 'active' : '' }}"
                onclick="toggleDropdownJadwalAdmin()">
                <i class="fa-regular fa-calendar-days"></i> Jadwal
                <i class="fa-solid fa-chevron-right sidebar-chevron {{ $jadwalAdminDropdownOpen ? 'open' : '' }}" id="chevron-jadwal-admin"></i>
            </button>
            <div class="sidebar-dropdown" id="dropdown-jadwal-admin"
                style="max-height: {{ $jadwalAdminDropdownOpen ? '300px' : '0' }};">

                <a href="{{ route('jadwal-akademik.index') }}"
                    class="sidebar-link sidebar-sublink {{ request()->routeIs('jadwal-akademik.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-check"></i> Kelola Jadwal & Timeline
                </a>

                <a href="{{ route('jadwalseminar.index') }}"
                    class="sidebar-link sidebar-sublink {{ request()->is('kelola-seminar*') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check"></i> Kelola Jadwal Seminar
                </a>

                <a href="{{ route('admin.jadwal.seminar.mahasiswa') }}"
                    class="sidebar-link sidebar-sublink {{ request()->routeIs('admin.jadwal.seminar.mahasiswa') ? 'active' : '' }}">
                    <i class="fa-solid fa-eye"></i> Jadwal Seminar Mahasiswa
                </a>

            </div>
            {{-- END JADWAL DROPDOWN (ADMIN) --}}

            <a href="/panduan-ta/admin" class="sidebar-link {{ request()->is('panduan-ta/admin*') ? 'active' : '' }}">
                <i class="fa-regular fa-bookmark"></i> Panduan TA
            </a>
            @endif

            {{-- Profil --}}
            @php
            $profilUrl = match($role) {
            'mahasiswa' => route('mahasiswa.profil'),
            'admin' => route('admin.profil_admin_tu'),
            'dosen' => route('dosen.profil'),
            default => '#',
            };
            @endphp
            <a href="{{ $profilUrl }}" class="sidebar-link {{ request()->is('mahasiswa/profil*') || request()->is('admin/profil*') || request()->is('dosen/profil*') ? 'active' : '' }}">
                <i class="fa-solid fa-circle-user"></i> Profil
            </a>

        </nav>

        <div class="sidebar-footer">
            <a href="#" class="sidebar-link" onclick="konfirmasiLogout()">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
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
            @php
            $topbarProfilUrl = match($role ?? '') {
            'mahasiswa' => route('mahasiswa.profil'),
            'admin' => route('admin.profil_admin_tu'),
            'dosen' => route('dosen.profil'),
            default => '#',
            };
            @endphp
            <a href="{{ $topbarProfilUrl }}" class="topbar-user" style="text-decoration:none;">
                <div class="user-info">
                    <div class="user-nim">{{ session('user')->nim_nid }}</div>
                    <div class="user-role">{{ ucfirst(session('user')->role) }}</div>
                </div>
                <div class="user-avatar">
                    @if(!empty(session('user')->foto))
                    <img src="{{ asset('storage/' . session('user')->foto) }}"
                        style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                    @else
                    <i class="fa-solid fa-user" style="font-size:0.9rem;"></i>
                    @endif
                </div>
            </a>
            @else
            <a href="/login" class="btn btn-sm btn-outline-dark" style="border-radius:8px;font-size:0.8rem;font-weight:600;">Masuk</a>
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

        function closeAllDropdowns(except) {
            const allDropdowns = [
                { id: 'dropdown-proposal', chevronId: 'chevron-proposal' },
                { id: 'dropdown-bimbingan', chevronId: 'chevron-bimbingan' },
                { id: 'dropdown-jadwal', chevronId: 'chevron-jadwal' },
                { id: 'dropdown-penguji', chevronId: 'chevron-penguji' },
                { id: 'dropdown-proposal-admin', chevronId: 'chevron-proposal-admin' },
                { id: 'dropdown-bimbingan-admin', chevronId: 'chevron-bimbingan-admin' },
                { id: 'dropdown-jadwal-admin', chevronId: 'chevron-jadwal-admin' },
            ];
            allDropdowns.forEach(function(d) {
                if (d.id === except) return;
                const dd = document.getElementById(d.id);
                const chevron = document.getElementById(d.chevronId);
                if (dd) dd.style.maxHeight = '0';
                if (chevron) chevron.classList.remove('open');
            });
        }

        function toggleDropdownProposal() {
            const dd      = document.getElementById('dropdown-proposal');
            const chevron = document.getElementById('chevron-proposal');
            const isOpen  = dd.style.maxHeight !== '0px' && dd.style.maxHeight !== '';
            closeAllDropdowns('dropdown-proposal');
            dd.style.maxHeight = isOpen ? '0' : '200px';
            chevron.classList.toggle('open', !isOpen);
        }

        function toggleDropdownBimbingan() {
            const dd      = document.getElementById('dropdown-bimbingan');
            const chevron = document.getElementById('chevron-bimbingan');
            const isOpen  = dd.style.maxHeight !== '0px' && dd.style.maxHeight !== '';
            closeAllDropdowns('dropdown-bimbingan');
            dd.style.maxHeight = isOpen ? '0' : '200px';
            chevron.classList.toggle('open', !isOpen);
        }

        function toggleDropdownJadwal() {
            const dd      = document.getElementById('dropdown-jadwal');
            const chevron = document.getElementById('chevron-jadwal');
            const isOpen  = dd.style.maxHeight !== '0px' && dd.style.maxHeight !== '';
            closeAllDropdowns('dropdown-jadwal');
            dd.style.maxHeight = isOpen ? '0' : '200px';
            chevron.classList.toggle('open', !isOpen);
        }

        function toggleDropdownPenguji() {
            const dd      = document.getElementById('dropdown-penguji');
            const chevron = document.getElementById('chevron-penguji');
            const isOpen  = dd.style.maxHeight !== '0px' && dd.style.maxHeight !== '';
            closeAllDropdowns('dropdown-penguji');
            dd.style.maxHeight = isOpen ? '0' : '200px';
            chevron.classList.toggle('open', !isOpen);
        }

        function toggleDropdownProposalAdmin() {
            const dd      = document.getElementById('dropdown-proposal-admin');
            const chevron = document.getElementById('chevron-proposal-admin');
            const isOpen  = dd.style.maxHeight !== '0px' && dd.style.maxHeight !== '';
            closeAllDropdowns('dropdown-proposal-admin');
            dd.style.maxHeight = isOpen ? '0' : '300px';
            chevron.classList.toggle('open', !isOpen);
        }

        function toggleDropdownBimbinganAdmin() {
            const dd      = document.getElementById('dropdown-bimbingan-admin');
            const chevron = document.getElementById('chevron-bimbingan-admin');
            const isOpen  = dd.style.maxHeight !== '0px' && dd.style.maxHeight !== '';
            closeAllDropdowns('dropdown-bimbingan-admin');
            dd.style.maxHeight = isOpen ? '0' : '300px';
            chevron.classList.toggle('open', !isOpen);
        }

        function toggleDropdownJadwalAdmin() {
            const dd      = document.getElementById('dropdown-jadwal-admin');
            const chevron = document.getElementById('chevron-jadwal-admin');
            const isOpen  = dd.style.maxHeight !== '0px' && dd.style.maxHeight !== '';
            closeAllDropdowns('dropdown-jadwal-admin');
            dd.style.maxHeight = isOpen ? '0' : '300px';
            chevron.classList.toggle('open', !isOpen);
        }

        function konfirmasiLogout() {
            Swal.fire({
                title: 'Yakin ingin logout?',
                text: 'Anda akan keluar dari sesi ini.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#FACC15',
                cancelButtonColor: '#d1d5db',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Berhasil Logout!',
                        text: 'Sampai jumpa lagi.',
                        icon: 'success',
                        confirmButtonColor: '#FACC15',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                    });
                    setTimeout(function() {
                        window.location.href = '/logout';
                    }, 800);
                }
            });
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