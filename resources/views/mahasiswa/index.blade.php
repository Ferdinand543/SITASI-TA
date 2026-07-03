@extends('layouts.app')

@section('content')

{{-- LOADING OVERLAY --}}
<div id="loadingOverlay" style="
    position:fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(255,255,255,0.9); z-index:9999;
    display:flex; flex-direction:column; align-items:center; justify-content:center;
">
    <div class="spinner-border mb-3" role="status" style="width:3rem;height:3rem;color:#FACC15;border-width:3px;">
        <span class="visually-hidden">Loading...</span>
    </div>
    <p style="color:#6b7280;font-weight:600;font-family:'Hanken Grotesk',sans-serif;">Sedang memproses data...</p>
</div>

<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary: #FFE083;
        --primary-dark: #d4a00e;
        --neutral: #1E293B;
        --tertiary: #FFFDF5;
        --text-muted: #6b7280;
        --card-bg: #ffffff;
        --border-radius: 16px;
    }

    body, .sitasi-wrap *:not(i) {
        font-family: 'Hanken Grotesk', sans-serif !important;
    }

    .sitasi-wrap { background: #f5f6fa; min-height: 100vh; }

    /* ── HERO ── */
    .hero-card {
        background-image: url('{{ asset('images/bg.jpeg') }}');
        background-size: cover; background-position: center;
        border-radius: 20px; padding: 44px 48px;
        position: relative; overflow: hidden;
        margin-bottom: 0; min-height: 220px;
        display: flex; align-items: center;
    }
    .hero-body { position: relative; z-index: 2; }
    .hero-card h1 { font-size: 1.75rem; font-weight: 800; color: #735C00; line-height: 1.25; margin-bottom: 20px; }
    .btn-hero-primary { background: var(--primary); color: var(--neutral); border: none; border-radius: 10px; padding: 9px 22px; font-weight: 700; font-size: 0.82rem; text-decoration: none; transition: 0.2s; display: inline-block; }
    .btn-hero-primary:hover { background: #d2bf75; color: var(--neutral); text-decoration: none; }
    .btn-hero-outline { background: transparent; color: var(--neutral); border: 1.5px solid #d1d5db; border-radius: 10px; padding: 9px 22px; font-weight: 600; font-size: 0.82rem; text-decoration: none; transition: 0.2s; display: inline-block; }
    .btn-hero-outline:hover { background: rgba(0,0,0,0.05); color: var(--neutral); text-decoration: none; }

    /* ── STAT ROW ── */
    .stat-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 18px; margin-top: 18px; margin-bottom: 24px; }
    .stat-item { background: #fff; border-radius: 18px; padding: 18px 22px; display: flex; align-items: center; gap: 16px; min-height: 96px; border: 1px solid #f1f5f9; box-shadow: 0 2px 8px rgba(0,0,0,.04); transition: 0.25s; }
    .stat-item:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,.06); }
    .stat-icon-wrap { width: 48px; height: 48px; border-radius: 14px; background: #fffdf3; border: 1.5px solid #f4e28a; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .stat-number { font-size: 2rem; font-weight: 800; color: #1e293b; line-height: 1; margin-bottom: 4px; }
    .stat-label { font-size: 0.82rem; color: #64748b; font-weight: 500; line-height: 1.3; }
    .bimbingan-progress-wrap { margin-top: 8px; }
    .bimbingan-progress-track { background: #f1f5f9; border-radius: 99px; height: 6px; width: 110px; overflow: hidden; }
    .bimbingan-progress-fill { background: linear-gradient(90deg,#FACC15,#f59e0b); height: 6px; border-radius: 99px; transition: width 0.8s ease; }
    .bimbingan-progress-label { font-size: 0.65rem; color: #64748b; font-weight: 600; margin-top: 4px; display: block; }

    /* ── MIDDLE ROW ── */
    .middle-row { display: flex; gap: 20px; margin-bottom: 20px; align-items: flex-start; }
    .proposal-card { background: #fff; border-radius: var(--border-radius); padding: 22px 24px; flex: 1; box-shadow: 0 2px 10px rgba(0,0,0,.05); min-width: 0; }
    .proposal-label { font-size: 0.65rem; font-weight: 700; letter-spacing: 1.2px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; }
    .proposal-title { font-size: 1.05rem; font-weight: 800; color: var(--neutral); margin-bottom: 18px; line-height: 1.35; }
    .proposal-title-empty { font-size: 0.9rem; font-weight: 600; color: #94a3b8; font-style: italic; margin-bottom: 18px; }
    .dosen-row { display: flex; gap: 24px; flex-wrap: wrap; }
    .dosen-item { display: flex; align-items: center; gap: 8px; }
    .dosen-avatar { width: 30px; height: 30px; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0; }
    .dosen-role { font-size: 0.65rem; color: var(--text-muted); font-weight: 500; }
    .dosen-name { font-size: 0.75rem; font-weight: 700; color: var(--neutral); }
    .dosen-name-empty { font-size: 0.75rem; font-weight: 500; color: #94a3b8; font-style: italic; }
    .proposal-time { margin-top: 14px; font-size: 0.7rem; color: var(--text-muted); display: flex; align-items: center; gap: 5px; }
    .judul-status-badge { display: inline-flex; align-items: center; gap: 5px; background: #dcfce7; color: #16a34a; font-size: 0.65rem; font-weight: 700; padding: 3px 10px; border-radius: 99px; margin-bottom: 10px; }
    .judul-status-badge::before { content: ''; width: 6px; height: 6px; background: #16a34a; border-radius: 50%; display: inline-block; }

    /* ── AKTIVITAS ── */
    .aktivitas-card { background: #fff; border-radius: var(--border-radius); padding: 18px; width: 260px; flex-shrink: 0; box-shadow: 0 2px 10px rgba(0,0,0,.05); }
    .aktivitas-card h6 { font-size: 0.8rem; font-weight: 700; color: var(--neutral); margin-bottom: 12px; }
    .aktivitas-item { display: flex; align-items: flex-start; gap: 10px; padding: 8px 0; border-bottom: 1px solid #f5f5f5; }
    .aktivitas-item:last-of-type { border-bottom: none; }
    .aktivitas-bar { width: 4px; min-height: 36px; border-radius: 4px; background: var(--primary); flex-shrink: 0; margin-top: 2px; }
    .aktivitas-text { font-size: 0.74rem; color: var(--neutral); font-weight: 600; line-height: 1.4; }
    .aktivitas-time { font-size: 0.63rem; color: var(--text-muted); margin-top: 2px; }
    .aktivitas-empty { text-align: center; padding: 20px 0; color: #94a3b8; font-size: 0.78rem; }

    /* ── ALUR ── */
    .alur-section { background: #fff; border-radius: var(--border-radius); border: 1.5px solid #e5e7eb; padding: 20px 24px; margin-bottom: 24px; }
    .alur-section h6 { font-size: 0.8rem; font-weight: 700; color: var(--neutral); margin-bottom: 16px; }
    .alur-steps { display: flex; align-items: center; }
    .alur-step { display: flex; flex-direction: column; align-items: center; flex: 1; position: relative; }
    .alur-step:not(:last-child)::after { content: ''; position: absolute; top: 14px; left: calc(50% + 14px); width: calc(100% - 28px); height: 2px; background: #e5e7eb; }
    .alur-step.done:not(:last-child)::after { background: #FACC15; }
    .step-circle { width: 28px; height: 28px; border-radius: 50%; border: 2px solid #e5e7eb; background: #fff; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: #aaa; z-index: 1; position: relative; }
    .alur-step.done .step-circle { background: #FACC15; border-color: #FACC15; color: var(--neutral); }
    .alur-step.active .step-circle { background: #fff; border-color: #FACC15; border-width: 2px; border-style: dashed; color: var(--neutral); box-shadow: 0 0 0 3px rgba(250,204,21,.25); }
    .alur-step.active .step-circle::after { content: ''; width: 10px; height: 10px; border-radius: 50%; background: #FACC15; position: absolute; }
    .step-label { font-size: 0.6rem; font-weight: 600; color: #aaa; text-align: center; margin-top: 7px; line-height: 1.3; }
    .alur-step.done .step-label { color: var(--neutral); }
    .alur-step.active .step-label { color: var(--neutral); font-weight: 700; }

    /* ══════════════════════════════════════
       INFORMASI TANGGAL PENTING
    ══════════════════════════════════════ */
    .tanggal-section {
        background: #fff;
        border-radius: var(--border-radius);
        border: 1px solid #e5e7eb;
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }

    .tanggal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .tanggal-header h6 {
        font-size: 1rem;
        font-weight: 800;
        color: var(--neutral);
        margin: 0;
    }

    .tanggal-header-user {
        font-size: 0.72rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    /* Alert deadline terdekat */
    .deadline-alert {
        background: #FEF2F2;
        border: 1px solid #FECACA;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .deadline-alert.warning {
        background: #FFFBEB;
        border-color: #FDE68A;
    }

    .deadline-alert.info {
        background: #EFF6FF;
        border-color: #BFDBFE;
    }

    .deadline-alert.success {
        background: #F0FDF4;
        border-color: #BBF7D0;
    }

    .deadline-alert-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .deadline-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .deadline-icon-red    { background: #FEE2E2; color: #DC2626; }
    .deadline-icon-yellow { background: #FEF9C3; color: #CA8A04; }
    .deadline-icon-blue   { background: #DBEAFE; color: #1D4ED8; }
    .deadline-icon-green  { background: #DCFCE7; color: #15803D; }

    .deadline-name {
        font-size: 0.9rem;
        font-weight: 800;
        color: var(--neutral);
        margin-bottom: 2px;
    }

    .deadline-date {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .deadline-countdown {
        font-size: 0.7rem;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: 99px;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .countdown-red    { background: #FEE2E2; color: #DC2626; }
    .countdown-yellow { background: #FEF9C3; color: #92400E; }
    .countdown-blue   { background: #DBEAFE; color: #1D4ED8; }
    .countdown-green  { background: #DCFCE7; color: #15803D; }
    .countdown-gray   { background: #F3F4F6; color: #6B7280; }

    /* Grid jadwal */
    .jadwal-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }

    .jadwal-item {
        background: #F8FAFC;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        padding: 14px 16px;
        transition: .2s;
        position: relative;
        overflow: hidden;
    }

    .jadwal-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,.08);
    }

    .jadwal-item::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px; height: 100%;
        border-radius: 12px 0 0 12px;
    }

    .jadwal-item.kategori-seminar::before   { background: #7C3AED; }
    .jadwal-item.kategori-administrasi::before { background: #1D4ED8; }
    .jadwal-item.kategori-bimbingan::before { background: #15803D; }
    .jadwal-item.kategori-lainnya::before   { background: #C9A227; }
    .jadwal-item.status-selesai { opacity: 0.65; }

    .jadwal-kategori {
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .jadwal-kategori-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    .kat-seminar   { color: #7C3AED; }
    .kat-admin     { color: #1D4ED8; }
    .kat-bimbingan { color: #15803D; }
    .kat-lainnya   { color: #C9A227; }

    .dot-seminar   { background: #7C3AED; }
    .dot-admin     { background: #1D4ED8; }
    .dot-bimbingan { background: #15803D; }
    .dot-lainnya   { background: #C9A227; }

    .jadwal-nama {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--neutral);
        margin-bottom: 4px;
        line-height: 1.35;
    }

    .jadwal-sub {
        font-size: 0.68rem;
        color: var(--text-muted);
        margin-bottom: 6px;
        line-height: 1.3;
    }

    .jadwal-tanggal {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--neutral);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .jadwal-status-badge {
        display: inline-block;
        font-size: 0.58rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 99px;
        margin-top: 6px;
    }

    .status-aktif   { background: #DCFCE7; color: #15803D; }
    .status-selesai-badge { background: #F3F4F6; color: #6B7280; }
    .status-mendatang { background: #EFF6FF; color: #1D4ED8; }

    .jadwal-waktu {
        font-size: 0.65rem;
        color: var(--text-muted);
        margin-top: 3px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .tanggal-empty {
        text-align: center;
        padding: 32px;
        color: #94a3b8;
        font-size: 0.85rem;
    }

    /* ── MENU ── */
    .menu-section { margin-bottom: 24px; }
    .menu-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; }
    .menu-card-item { background: #fff; border-radius: 14px; padding: 0; text-align: center; text-decoration: none; color: inherit; display: flex; flex-direction: column; align-items: center; transition: 0.25s; box-shadow: 0 2px 8px rgba(0,0,0,.05); border: 1.5px solid #f0f0f0; overflow: visible; position: relative; }
    .menu-card-item:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,.1); border-color: #f0f0f0; color: inherit; text-decoration: none; }
    .menu-card-item .card-icon-wrap { width: 100%; height: 180px; background: #f3f4f7; border-radius: 14px 14px 0 0; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .menu-card-item img { height: 120px; width: 120px; object-fit: contain; }
    .menu-card-item .card-text { padding: 16px 16px 18px; width: 100%; }
    .menu-card-item h6 { font-size: 0.9rem; font-weight: 700; color: var(--neutral); margin-bottom: 4px; }
    .menu-card-item p { font-size: 0.72rem; color: var(--text-muted); margin: 0; line-height: 1.4; }
    .card-badge-notif { position: absolute; top: 10px; right: 10px; width: 12px; height: 12px; background: red; border-radius: 50%; display: inline-block; z-index: 10; }

    @media (max-width: 900px) {
        .menu-grid { grid-template-columns: repeat(2,1fr); }
        .stat-row  { grid-template-columns: 1fr; }
        .middle-row { flex-direction: column; }
        .aktivitas-card { width: 100%; }
        .hero-card { padding: 28px 24px; }
        .hero-card h1 { font-size: 1.4rem; }
        .jadwal-grid { grid-template-columns: repeat(2,1fr); }
    }
    @media (max-width: 600px) {
        .jadwal-grid { grid-template-columns: 1fr; }
    }
</style>

@php
    $judulAktif = $judulDisetujui->judul_disetujui ?? null;
    $updatedAt  = $judulDisetujui->updated_at      ?? null;

    $targetBimbinganVal = $targetBimbingan ?? 12;
    $totalBimbinganVal  = $totalBimbingan  ?? 0;
    $persenBimbingan    = $targetBimbinganVal > 0
        ? min(100, round(($totalBimbinganVal / $targetBimbinganVal) * 100))
        : 0;

    // ── Helper kategori jadwal ──
    $getKatClass = function($kat) {
        return match(strtolower($kat ?? '')) {
            'seminar'       => 'kategori-seminar',
            'administrasi'  => 'kategori-administrasi',
            'bimbingan'     => 'kategori-bimbingan',
            default         => 'kategori-lainnya',
        };
    };

    $getKatTextClass = function($kat) {
        return match(strtolower($kat ?? '')) {
            'seminar'       => 'kat-seminar',
            'administrasi'  => 'kat-admin',
            'bimbingan'     => 'kat-bimbingan',
            default         => 'kat-lainnya',
        };
    };

    $getKatDotClass = function($kat) {
        return match(strtolower($kat ?? '')) {
            'seminar'       => 'dot-seminar',
            'administrasi'  => 'dot-admin',
            'bimbingan'     => 'dot-bimbingan',
            default         => 'dot-lainnya',
        };
    };

    // Deadline terdekat: alert warna & countdown
    $deadlineColor = 'info';
    $iconClass     = 'deadline-icon-blue';
    $countClass    = 'countdown-blue';
    $hariLabel     = '';

    if ($deadlineDekat ?? null) {
        $tgl  = \Carbon\Carbon::parse($deadlineDekat->tanggal);
        $hari = now()->diffInDays($tgl, false);

        if ($hari < 0) {
            $deadlineColor = 'success';
            $iconClass     = 'deadline-icon-green';
            $countClass    = 'countdown-gray';
            $hariLabel     = 'Sudah lewat';
        } elseif ($hari == 0) {
            $deadlineColor = '';
            $iconClass     = 'deadline-icon-red';
            $countClass    = 'countdown-red';
            $hariLabel     = 'Hari ini!';
        } elseif ($hari <= 3) {
            $deadlineColor = '';
            $iconClass     = 'deadline-icon-red';
            $countClass    = 'countdown-red';
            $hariLabel     = $hari . ' Hari Lagi';
        } elseif ($hari <= 7) {
            $deadlineColor = 'warning';
            $iconClass     = 'deadline-icon-yellow';
            $countClass    = 'countdown-yellow';
            $hariLabel     = $hari . ' Hari Lagi';
        } else {
            $deadlineColor = 'info';
            $iconClass     = 'deadline-icon-blue';
            $countClass    = 'countdown-blue';
            $hariLabel     = $hari . ' Hari Lagi';
        }
    }
@endphp

<div class="sitasi-wrap">

    {{-- HERO --}}
    <div class="hero-card">
        <div class="hero-body">
            <h1>Sistem Bimbingan Tugas Akhir Mahasiswa<br>Sistem Informasi</h1>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('panduan-ta.mahasiswa') }}" class="btn-hero-primary">Pelajari Alur TA</a>
                <a href="{{ asset('pedoman/Pengarahan Pelaksanaan TA1 - 151025.pdf') }}" target="_blank" download class="btn-hero-outline">Unduh Pedoman</a>
            </div>
        </div>
    </div>

    {{-- STATS --}}
    <div class="stat-row">
        <div class="stat-item">
            <div class="stat-icon-wrap">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
            </div>
            <div>
                <div class="stat-number">{{ $totalPengajuan }}</div>
                <div class="stat-label">Total Pengajuan Judul</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-wrap">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/>
                    <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
            </div>
            <div>
                <div class="stat-number">{{ $totalProposal }}</div>
                <div class="stat-label">Total Upload Proposal</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-wrap">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div>
                <div class="stat-number">{{ $totalBimbinganVal }}</div>
                <div class="stat-label">Total Bimbingan</div>
                <div class="bimbingan-progress-wrap">
                    <div class="bimbingan-progress-track">
                        <div class="bimbingan-progress-fill" style="width:{{ $persenBimbingan }}%;"></div>
                    </div>
                    <span class="bimbingan-progress-label">{{ $persenBimbingan }}% dari target ({{ $targetBimbinganVal }}x)</span>
                </div>
            </div>
        </div>
    </div>

    {{-- JUDUL AKTIF + AKTIVITAS --}}
    <div class="middle-row">
        <div class="proposal-card">
            <div class="proposal-label">Judul Aktif</div>
            @if($judulAktif)
                <div class="judul-status-badge">Disetujui</div>
                <div class="proposal-title">{{ $judulAktif }}</div>
            @else
                <div class="proposal-title-empty">Belum ada judul yang disetujui.</div>
            @endif
            <div class="dosen-row">
                <div class="dosen-item">
                    <div class="dosen-avatar">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-1.516"/>
                        </svg>
                    </div>
                    <div>
                        <div class="dosen-role">Dosen Pembimbing 1</div>
                        @if(!empty($namaDosen1))
                            <div class="dosen-name">{{ $namaDosen1 }}</div>
                        @else
                            <div class="dosen-name-empty">Belum ditentukan</div>
                        @endif
                    </div>
                </div>
                <div class="dosen-item">
                    <div class="dosen-avatar">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-1.516"/>
                        </svg>
                    </div>
                    <div>
                        <div class="dosen-role">Dosen Pembimbing 2</div>
                        @if(!empty($namaDosen2))
                            <div class="dosen-name">{{ $namaDosen2 }}</div>
                        @else
                            <div class="dosen-name-empty">Belum ditentukan</div>
                        @endif
                    </div>
                </div>
            </div>
            @if($updatedAt)
                <div class="proposal-time">
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                    </svg>
                    Terakhir diperbarui: {{ \Carbon\Carbon::parse($updatedAt)->diffForHumans() }}
                </div>
            @endif
        </div>

        <div class="aktivitas-card">
            <h6>Aktivitas Terbaru</h6>
            @forelse($aktivitas as $i => $item)
                <div class="aktivitas-item">
                    <div class="aktivitas-bar" style="background:{{ $i === 0 ? '#FACC15' : '#e2e8f0' }};"></div>
                    <div>
                        <div class="aktivitas-text">{{ $item->teks }}</div>
                        <div class="aktivitas-time">{{ \Carbon\Carbon::parse($item->waktu)->diffForHumans() }}</div>
                    </div>
                </div>
            @empty
                <div class="aktivitas-empty">Belum ada aktivitas.</div>
            @endforelse
        </div>
    </div>

    {{-- ALUR KEMAJUAN TA --}}
    <div class="alur-section">
        <h6>Alur Kemajuan Tugas Akhir</h6>
        <div class="alur-steps">
            @php
                $stepList = [
                    'pengajuan_judul'       => 'Pengajuan<br>Judul',
                    'upload_proposal'       => 'Upload Proposal<br>& Usulan Pembimbing',
                    'verifikasi_pembimbing' => 'Verifikasi<br>Pembimbing',
                    'review_proposal'       => 'Review<br>Proposal',
                    'proses_bimbingan'      => 'Proses<br>Bimbingan',
                    'seminar_proposal'      => 'Seminar<br>Proposal',
                ];
                $activeFound = false;
            @endphp
            @foreach($stepList as $key => $label)
                @php
                    $isDone   = $steps[$key] ?? false;
                    $isActive = !$isDone && !$activeFound;
                    if ($isActive) $activeFound = true;
                    $stepNo = $loop->iteration;
                @endphp
                <div class="alur-step {{ $isDone ? 'done' : ($isActive ? 'active' : '') }}">
                    <div class="step-circle">
                        @if($isDone) ✓ @elseif(!$isActive) {{ $stepNo }} @endif
                    </div>
                    <div class="step-label">{!! $label !!}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ══ INFORMASI TANGGAL PENTING ══ --}}
    <div class="tanggal-section">
        <div class="tanggal-header">
            <h6>Informasi Tanggal Penting</h6>
            <span class="tanggal-header-user">
                Mahasiswa: {{ session('user')->nama ?? '-' }}
                ({{ session('user')->nim_nid ?? '-' }})
            </span>
        </div>

        {{-- Alert deadline terdekat --}}
        @if(isset($deadlineDekat) && $deadlineDekat)
        <div class="deadline-alert {{ $deadlineColor }}">
            <div class="deadline-alert-left">
                <div class="deadline-icon {{ $iconClass }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                </div>
                <div>
                    <div class="deadline-name">{{ $deadlineDekat->nama_kegiatan }}</div>
                    <div class="deadline-date">
                        {{ \Carbon\Carbon::parse($deadlineDekat->tanggal)->translatedFormat('l, j F Y') }}
                        @if($deadlineDekat->waktu) • {{ \Carbon\Carbon::parse($deadlineDekat->waktu)->format('H:i') }} WIB @endif
                    </div>
                </div>
            </div>
            <span class="deadline-countdown {{ $countClass }}">{{ $hariLabel }}</span>
        </div>
        @endif

        {{-- Grid semua jadwal --}}
        @if(isset($jadwalList) && $jadwalList->count() > 0)
        <div class="jadwal-grid">
            @foreach($jadwalList as $jadwal)
            @php
                $tglJadwal   = \Carbon\Carbon::parse($jadwal->tanggal);
                $sudahLewat  = $tglJadwal->isPast() && $tglJadwal->toDateString() !== now()->toDateString();
                $hari        = now()->diffInDays($tglJadwal, false);
                $katLower    = strtolower($jadwal->kategori ?? '');

                $statusBadgeClass = $sudahLewat ? 'status-selesai-badge' : ($hari <= 7 ? 'status-aktif' : 'status-mendatang');
                $statusLabel      = $sudahLewat ? 'Sudah lewat' : ($hari == 0 ? 'Hari ini' : ($hari <= 7 ? 'Segera' : 'Mendatang'));

                if ($jadwal->status === 'Selesai') {
                    $statusBadgeClass = 'status-selesai-badge';
                    $statusLabel      = 'Selesai';
                }
            @endphp
            <div class="jadwal-item {{ $getKatClass($jadwal->kategori) }} {{ $sudahLewat || $jadwal->status === 'Selesai' ? 'status-selesai' : '' }}">
                <div class="jadwal-kategori {{ $getKatTextClass($jadwal->kategori) }}">
                    <span class="jadwal-kategori-dot {{ $getKatDotClass($jadwal->kategori) }}"></span>
                    {{ ucfirst($jadwal->kategori ?? 'Lainnya') }}
                </div>
                <div class="jadwal-nama">{{ $jadwal->nama_kegiatan }}</div>
                @if($jadwal->sub_judul)
                <div class="jadwal-sub">{{ $jadwal->sub_judul }}</div>
                @endif
                <div class="jadwal-tanggal">
                    <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    {{ $tglJadwal->translatedFormat('j M Y') }}
                    @if($jadwal->tanggal_selesai && $jadwal->tanggal_selesai !== $jadwal->tanggal)
                        – {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->translatedFormat('j M Y') }}
                    @endif
                </div>
                @if($jadwal->waktu)
                <div class="jadwal-waktu">
                    <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                    </svg>
                    {{ \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') }} WIB
                    @if($jadwal->lokasi) • {{ $jadwal->lokasi }} @endif
                </div>
                @endif
                <span class="jadwal-status-badge {{ $statusBadgeClass }}">{{ $statusLabel }}</span>
            </div>
            @endforeach
        </div>
        @else
        <div class="tanggal-empty">
            <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#d1d5db" stroke-width="1.5" style="margin:0 auto 10px;display:block;">
                <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            Belum ada jadwal yang tersedia.
        </div>
        @endif
    </div>

    {{-- MENU CARDS --}}
    <div class="menu-section">
        <div class="menu-grid">
            <a href="{{ session('user') ? route('pengajuan.mahasiswa') : '/login' }}" class="menu-card-item">
                @if(($notifJudulMahasiswa ?? 0) > 0)<span class="card-badge-notif"></span>@endif
                <div class="card-icon-wrap"><img src="{{ asset('images/ta.jpeg') }}" alt="Pengajuan Judul"></div>
                <div class="card-text"><h6>Pengajuan Judul</h6><p>Ajukan judul dan topik Tugas Akhir</p></div>
            </a>
            <a href="{{ route('proposal.mahasiswa') }}" class="menu-card-item">
                @if(($notifProposalMahasiswa ?? 0) > 0)<span class="card-badge-notif"></span>@endif
                <div class="card-icon-wrap"><img src="{{ asset('images/proposal.jpeg') }}" alt="Upload Proposal"></div>
                <div class="card-text"><h6>Upload Proposal</h6><p>Kirim proposal untuk di review</p></div>
            </a>
            <a href="{{ url('/bimbingan') }}" class="menu-card-item">
                @if(($notifBimbinganMahasiswa ?? 0) > 0)<span class="card-badge-notif"></span>@endif
                <div class="card-icon-wrap"><img src="{{ asset('images/bimbingan.jpeg') }}" alt="Bimbingan"></div>
                <div class="card-text"><h6>Riwayat Bimbingan</h6><p>Riwayat Bimbingan TA1</p></div>
            </a>
            <a href="{{ route('jadwal.index') }}" class="menu-card-item">
                <div class="card-icon-wrap"><img src="{{ asset('images/jadwal.jpeg') }}" alt="Jadwal"></div>
                <div class="card-text"><h6>Jadwal</h6><p>Pelaksanaan TA & Seminar</p></div>
            </a>
            <a href="{{ route('seminar.daftar') }}" class="menu-card-item">
                @if(($notifSeminarMahasiswa ?? 0) > 0)<span class="card-badge-notif"></span>@endif
                <div class="card-icon-wrap"><img src="{{ asset('images/seminar.jpeg') }}" alt="Daftar Seminar"></div>
                <div class="card-text"><h6>Daftar Seminar</h6><p>Ajukan Pendaftaran Seminar</p></div>
            </a>
            <a href="{{ route('mahasiswa.hasil.penilaian') }}" class="menu-card-item">
                @if(($notifNilaiMahasiswa ?? 0) > 0)<span class="card-badge-notif"></span>@endif
                <div class="card-icon-wrap"><img src="{{ asset('images/nilai.jpeg') }}" alt="Nilai"></div>
                <div class="card-text"><h6>Nilai</h6><p>Lihat Hasil Penilaian TA dan Seminar</p></div>
            </a>
        </div>
    </div>

</div>

<script>
    window.addEventListener("load", function () {
        document.getElementById("loadingOverlay").style.display = "none";
    });
</script>

@endsection