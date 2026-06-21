@extends('layouts.app')

@section('title', 'Administrasi Seminar TA-1')

@section('content')

<style>
    :root {
        --gold: #C9A227;
        --gold-lt: #FEF9EC;
        --gold-border: #F5D97A;
        --neutral: #1E293B;
        --muted: #6B7280;
        --border: #E5E7EB;
        --white: #ffffff;
        --bg: #F5F6FA;
        --radius: 16px;
    }

    .seminar-wrap {
        background: var(--bg);
        min-height: 100vh;
        padding-bottom: 40px;
    }

    .seminar-hero {
        background-image: url('{{ asset("images/1.jpeg") }}');
        background-size: cover;
        background-position: center right;
        border-radius: 20px;
        padding: 36px 40px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        min-height: 160px;
        display: flex;
        align-items: center;
    }

    .seminar-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(255, 251, 230, .95) 55%, rgba(255, 251, 230, .6) 80%, transparent 100%);
        border-radius: 20px;
    }

    .seminar-hero::after {
        content: '';
        position: absolute;
        right: -30px;
        top: -30px;
        width: 200px;
        height: 200px;
        background: rgba(201, 162, 39, .15);
        border-radius: 50%;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-title {
        font-size: 28px;
        font-weight: 800;
        color: #7C5C00;
        margin-bottom: 6px;
    }

    .hero-sub {
        font-size: 13px;
        color: #92400E;
        max-width: 500px;
        line-height: 1.6;
    }

    /* STAT CARDS */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--white);
        border-radius: var(--radius);
        padding: 20px 22px;
        border: 1px solid var(--border);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .stat-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .stat-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-card-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin: 0;
    }

    .stat-card-value {
        font-size: 32px;
        font-weight: 900;
        color: var(--neutral);
        line-height: 1;
        margin-bottom: 2px;
    }

    .stat-card-sub {
        font-size: 12px;
        color: var(--muted);
        font-weight: 500;
    }

    /* FILTER */
    .filter-card {
        background: var(--white);
        border-radius: var(--radius);
        padding: 16px 20px;
        border: 1px solid var(--border);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
        margin-bottom: 20px;
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-wrap {
        position: relative;
        flex: 1;
        min-width: 200px;
    }

    .search-wrap svg {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        padding: 9px 12px 9px 34px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        outline: none;
        font-family: inherit;
        transition: border .2s;
        background: #FAFAFA;
        box-sizing: border-box;
    }

    .search-input:focus {
        border-color: var(--gold);
        background: #fff;
    }

    .filter-select {
        padding: 9px 12px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        outline: none;
        background: #FAFAFA;
        font-family: inherit;
        cursor: pointer;
    }

    .filter-select:focus {
        border-color: var(--gold);
    }

    .btn-reset {
        padding: 9px 18px;
        background: #F3F4F6;
        color: var(--muted);
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        white-space: nowrap;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-reset:hover {
        background: #E5E7EB;
        color: var(--neutral);
    }

    /* TABLE */
    .tabel-card {
        background: var(--white);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
        overflow: hidden;
    }

    .tabel-title {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        font-size: 15px;
        font-weight: 800;
        color: var(--neutral);
    }

    .tabel-scroll {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    thead th {
        padding: 11px 16px;
        font-size: 11px;
        font-weight: 700;
        color: var(--muted);
        text-align: left;
        background: #FAFAFA;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    tbody tr {
        border-bottom: 1px solid #F3F4F6;
        transition: background .15s;
    }

    tbody tr:last-child {
        border-bottom: none;
    }

    tbody tr:hover {
        background: #FAFBFF;
    }

    tbody td {
        padding: 14px 16px;
        font-size: 13px;
        color: var(--neutral);
        vertical-align: middle;
    }

    .mhs-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .mhs-nama {
        font-weight: 700;
        color: var(--neutral);
        font-size: 13px;
    }

    .mhs-nim {
        font-size: 11.5px;
        color: var(--muted);
        font-weight: 600;
    }

    .tgl-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--neutral);
    }

    .tgl-sub {
        font-size: 11px;
        color: var(--muted);
        margin-top: 1px;
    }

    .prog-wrap {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .prog-text {
        font-size: 11.5px;
        font-weight: 700;
        color: var(--neutral);
    }

    .bar-outer {
        background: #F3F4F6;
        border-radius: 99px;
        height: 5px;
        width: 100px;
    }

    .bar-inner {
        border-radius: 99px;
        height: 100%;
    }

    .bar-green { background: #16A34A; }
    .bar-gold  { background: var(--gold); }
    .bar-red   { background: #DC2626; }

    .badge-sm {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 99px;
        white-space: nowrap;
    }

    .badge-lolos          { background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; }
    .badge-tidak          { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
    .badge-menunggu       { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }
    .badge-jadwal         { background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; }
    .badge-selesai        { background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; }
    .badge-belum          { background: #F9FAFB; color: #9CA3AF; border: 1px solid #E5E7EB; }
    .badge-menunggu-jadwal{ background: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; }

    .aksi-wrap {
        display: flex;
        gap: 6px;
        align-items: center;
    }

    .btn-detail {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        background: #F3F4F6;
        color: var(--neutral);
        border: 1px solid var(--border);
        text-decoration: none;
        transition: .15s;
        white-space: nowrap;
    }

    .btn-detail:hover {
        background: #E5E7EB;
        color: var(--neutral);
    }

    .btn-verif {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        background: #FFE083;
        color: #7C5C00;
        border: 1px solid #F5D97A;
        text-decoration: none;
        transition: .15s;
        cursor: pointer;
        white-space: nowrap;
        font-family: inherit;
    }

    .btn-verif:hover { background: #fdd835; }

    .empty-state {
        text-align: center;
        padding: 56px;
        color: var(--muted);
        font-size: 14px;
    }

    /* MODAL */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.45);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-box {
        background: #fff;
        border-radius: 18px;
        padding: 32px;
        width: 100%;
        max-width: 480px;
        box-shadow: 0 12px 40px rgba(0,0,0,.18);
        position: relative;
    }

    .modal-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--neutral);
        margin-bottom: 4px;
    }

    .modal-sub {
        font-size: 12.5px;
        color: var(--muted);
        margin-bottom: 24px;
        line-height: 1.5;
    }

    .modal-close {
        position: absolute;
        top: 16px;
        right: 16px;
        background: #F3F4F6;
        border: none;
        border-radius: 8px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 16px;
        color: var(--muted);
    }

    .modal-close:hover { background: #E5E7EB; }

    .upload-area {
        border: 2px dashed var(--gold-border);
        border-radius: 12px;
        background: var(--gold-lt);
        padding: 28px;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s, background .2s;
        margin-bottom: 16px;
        position: relative;
    }

    .upload-area:hover {
        border-color: var(--gold);
        background: #FEF3C7;
    }

    .upload-area input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    .upload-icon {
        font-size: 32px;
        margin-bottom: 8px;
    }

    .upload-label {
        font-size: 13px;
        font-weight: 700;
        color: #7C5C00;
        margin-bottom: 4px;
    }

    .upload-hint {
        font-size: 11.5px;
        color: var(--muted);
    }

    .file-preview {
        display: none;
        align-items: center;
        gap: 10px;
        background: #F0FDF4;
        border: 1px solid #BBF7D0;
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 16px;
    }

    .file-preview.show { display: flex; }

    .file-preview-name {
        font-size: 13px;
        font-weight: 700;
        color: #15803D;
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .file-preview-remove {
        background: none;
        border: none;
        color: #DC2626;
        cursor: pointer;
        font-size: 16px;
        font-weight: 700;
        padding: 0 4px;
        line-height: 1;
    }

    .file-error {
        display: none;
        font-size: 12px;
        color: #DC2626;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .file-error.show { display: block; }

    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 4px;
    }

    .btn-batal {
        padding: 10px 20px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: #F3F4F6;
        color: var(--muted);
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
    }

    .btn-batal:hover { background: #E5E7EB; }

    .btn-upload {
        padding: 10px 22px;
        border-radius: 8px;
        border: none;
        background: var(--gold);
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        opacity: .5;
        pointer-events: none;
        transition: opacity .2s;
    }

    .btn-upload.ready {
        opacity: 1;
        pointer-events: all;
    }

    .btn-upload:hover.ready { background: #b8911f; }

    @media (max-width: 900px) {
        .stat-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<div class="seminar-wrap">

    {{-- HERO --}}
    <div class="seminar-hero">
        <div class="hero-content">
            <div class="hero-title">Administrasi Seminar TA-1</div>
            <div class="hero-sub">Kelola berkas pendaftaran, verifikasi persyaratan administrasi, dan pantau progres pendaftaran seminar tugas akhir mahasiswa.</div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="stat-grid">

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-card-icon" style="background:#F3F4F6;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#6B7280" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
                    </svg>
                </div>
                <span class="stat-card-label" style="color:var(--muted);">Total</span>
            </div>
            <div class="stat-card-value">{{ $total }}</div>
            <div class="stat-card-sub">Total Pengajuan</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-card-icon" style="background:#FFFBEB;position:relative;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#92400E" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
                    </svg>
                    <span style="position:absolute;bottom:6px;right:6px;background:#FFFBEB;border-radius:50%;width:14px;height:14px;display:flex;align-items:center;justify-content:center;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#92400E" width="10" height="10">
                            <circle cx="12" cy="12" r="9" />
                            <path stroke-linecap="round" d="M12 7v5l3 2" />
                        </svg>
                    </span>
                </div>
                <span class="stat-card-label" style="color:#92400E;">Pending</span>
            </div>
            <div class="stat-card-value" style="color:#92400E;">{{ $pending }}</div>
            <div class="stat-card-sub">Menunggu Verifikasi</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-card-icon" style="background:#F0FDF4;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#15803D" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <span class="stat-card-label" style="color:#15803D;">Verified</span>
            </div>
            <div class="stat-card-value" style="color:#15803D;">{{ $lolos }}</div>
            <div class="stat-card-sub">Lolos Administrasi</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-card-icon" style="background:#EFF6FF;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#1D4ED8" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                </div>
                <span class="stat-card-label" style="color:#1D4ED8;">Scheduled</span>
            </div>
            <div class="stat-card-value" style="color:#1D4ED8;">{{ $dijadwalkan }}</div>
            <div class="stat-card-sub">Seminar Dijadwalkan</div>
        </div>

    </div>

    {{-- BERITA ACARA ALERT --}}
    @if($adaYangDijadwalkan)
    <div style="
        background: #FFFBEB;
        border: 1.5px solid #F5D97A;
        border-radius: 16px;
        padding: 18px 24px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    ">
        <div style="display:flex;align-items:center;gap:14px;">
            <div style="
                background: #FEF3C7;
                border-radius: 10px;
                width: 42px; height: 42px;
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0;
            ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.8" stroke="#92400E" width="22" height="22">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125
                        1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0
                        12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125
                        1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0
                        1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
            </div>
            <div>
                @if($beritaAcara)
                    {{-- SUDAH ADA BERITA ACARA --}}
                    <div style="font-weight:800;color:#15803D;font-size:14px;margin-bottom:3px;">
                        Berita Acara Seminar Siap Digunakan
                    </div>
                    <div style="font-size:12.5px;color:#92400E;line-height:1.5;">
                        Berita acara seminar telah tersedia dan siap digunakan untuk seluruh Seminar Tugas Akhir 1 yang telah dijadwalkan.<br>
                        <span style="color:var(--muted);">Terakhir diperbarui: {{ \Carbon\Carbon::parse($beritaAcara->created_at)->format('d M Y') }}</span>
                    </div>
                @else
                    {{-- BELUM ADA BERITA ACARA --}}
                    <div style="font-weight:800;color:#7C5C00;font-size:14px;margin-bottom:3px;">
                        Berita Acara Seminar Belum Tersedia
                    </div>
                    <div style="font-size:12.5px;color:#92400E;line-height:1.5;">
                        Mahasiswa telah memasuki tahap penjadwalan seminar.
                        Silakan unggah template berita acara yang akan digunakan
                        sebagai dokumen resmi pada pelaksanaan Seminar Tugas Akhir 1.
                    </div>
                @endif
            </div>
        </div>

        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            @if($beritaAcara)
                {{-- TOMBOL PERBARUI --}}
                <button type="button" onclick="bukaModalBeritaAcara()" style="
                    background: #fff;
                    color: #7C5C00;
                    border: 1.5px solid #F5D97A;
                    border-radius: 8px;
                    padding: 10px 18px;
                    font-size: 13px;
                    font-weight: 700;
                    cursor: pointer;
                    white-space: nowrap;
                    font-family: inherit;
                ">
                    Perbarui Berita Acara
                </button>
                {{-- TOMBOL LIHAT --}}
                <a href="{{ route('berita.acara.file', basename($beritaAcara->file_path)) }}" target="_blank" style="
                    background: #C9A227;
                    color: #fff;
                    border: none;
                    border-radius: 8px;
                    padding: 10px 18px;
                    font-size: 13px;
                    font-weight: 700;
                    cursor: pointer;
                    white-space: nowrap;
                    font-family: inherit;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                ">
                    Lihat Berita Acara
                </a>
            @else
                <button type="button" onclick="bukaModalBeritaAcara()" style="
                    background: #C9A227;
                    color: #fff;
                    border: none;
                    border-radius: 8px;
                    padding: 10px 18px;
                    font-size: 13px;
                    font-weight: 700;
                    cursor: pointer;
                    white-space: nowrap;
                    font-family: inherit;
                ">
                    ⬆ Unggah Berita Acara
                </button>
            @endif
        </div>
    </div>
    @endif

    {{-- FILTER --}}
    <div class="filter-card">
        <div class="search-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#9CA3AF" stroke-width="2">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.35-4.35" />
            </svg>
            <input type="text" id="searchInput" class="search-input"
                placeholder="Cari NIM atau Nama..." autocomplete="off">
        </div>
        <select id="filterAdm" class="filter-select" onchange="applyFilter()">
            <option value="">Semua Status Administrasi</option>
            <option value="menunggu verifikasi">Menunggu Verifikasi</option>
            <option value="lolos administrasi">Lolos Administrasi</option>
            <option value="tidak administrasi">Tidak Administrasi</option>
        </select>
        <select id="filterSeminar" class="filter-select" onchange="applyFilter()">
            <option value="">Semua Status Seminar</option>
            <option value="belum daftar seminar">Belum Daftar</option>
            <option value="menunggu jadwal">Menunggu Jadwal</option>
            <option value="sudah dijadwalkan">Sudah Dijadwalkan</option>
            <option value="selesai">Selesai</option>
        </select>
        <button type="button" class="btn-reset" onclick="resetFilter()">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            Reset
        </button>
    </div>

    {{-- TABEL --}}
    <div class="tabel-card">
        <div class="tabel-title">Daftar Pengajuan Seminar</div>
        <div class="tabel-scroll">
            <table id="mainTable">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>NIM</th>
                        <th>Mahasiswa</th>
                        <th>Prog. Dokumen</th>
                        <th>Status Adm.</th>
                        <th>Status Seminar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($pengajuans as $p)
                    @php
                    $persen = $p->total_dokumen > 0 ? round(($p->progress_dokumen / $p->total_dokumen) * 100) : 0;
                    $barClass = $persen >= 100 ? 'bar-green' : ($persen >= 50 ? 'bar-gold' : 'bar-red');
                    $progText = $persen >= 100 ? 'Lengkap' : $p->progress_dokumen.'/'.$p->total_dokumen.' Dokumen';
                    $sudahVerif = in_array($p->status_administrasi, ['Lolos Administrasi', 'Tidak Administrasi']);
                    @endphp
                    <tr
                        data-search="{{ strtolower($p->mahasiswa_id . ' ' . ($p->mahasiswa->nama ?? '')) }}"
                        data-adm="{{ strtolower($p->status_administrasi) }}"
                        data-seminar="{{ strtolower($p->status_seminar) }}">
                        <td>
                            <div class="tgl-label">{{ \Carbon\Carbon::parse($p->created_at)->format('d M Y') }}</div>
                            <div class="tgl-sub">{{ \Carbon\Carbon::parse($p->created_at)->format('H:i') }} WIB</div>
                        </td>
                        <td style="font-size:12.5px;font-weight:700;color:var(--muted);">
                            {{ $p->mahasiswa_id }}
                        </td>
                        <td>
                            <div class="mhs-info">
                                <span class="mhs-nama">{{ $p->mahasiswa->nama ?? '-' }}</span>
                                <span class="mhs-nim">{{ $p->mahasiswa->angkatan ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="prog-wrap">
                                <span class="prog-text">{{ $progText }}</span>
                                <div class="bar-outer">
                                    <div class="bar-inner {{ $barClass }}" style="width:{{ $persen }}%;"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($p->status_administrasi === 'Lolos Administrasi')
                            <span class="badge-sm badge-lolos">
                                <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                Lolos
                            </span>
                            @elseif($p->status_administrasi === 'Tidak Administrasi')
                            <span class="badge-sm badge-tidak">
                                <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                                Ditolak
                            </span>
                            @else
                            <span class="badge-sm badge-menunggu">
                                <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                                Menunggu
                            </span>
                            @endif
                        </td>
                        <td>
                            @if($p->status_seminar === 'Sudah Dijadwalkan')
                            <span class="badge-sm badge-jadwal">Sudah Dijadwalkan</span>
                            @elseif($p->status_seminar === 'Menunggu Jadwal')
                            <span class="badge-sm badge-menunggu-jadwal">Menunggu Jadwal</span>
                            @elseif($p->status_seminar === 'Selesai')
                            <span class="badge-sm badge-selesai">Selesai</span>
                            @else
                            <span class="badge-sm badge-belum">Belum Daftar</span>
                            @endif
                        </td>
                        <td>
                            <div class="aksi-wrap">
                                @if(!$sudahVerif)
                                <a href="{{ route('admin.seminar.show', $p->id) }}" class="btn-verif">
                                    Verifikasi
                                </a>
                                @else
                                <a href="{{ route('admin.seminar.show', $p->id) }}" class="btn-detail">Detail</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="7">
                            <div class="empty-state">
                                <div style="font-size:36px;margin-bottom:10px;">📋</div>
                                Belum ada pengajuan seminar
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div id="noResult" style="display:none;text-align:center;padding:56px;color:var(--muted);font-size:14px;">
                <div style="font-size:28px;margin-bottom:8px;">🔍</div>
                Tidak ada data yang sesuai filter
            </div>
        </div>
    </div>

</div>

{{-- MODAL UPLOAD BERITA ACARA --}}
<div class="modal-overlay" id="modalBeritaAcara">
    <div class="modal-box">
        <button class="modal-close" type="button" onclick="tutupModalBeritaAcara()">✕</button>
        <div class="modal-title">Unggah Berita Acara</div>
        <div class="modal-sub">Upload template berita acara seminar TA-1.<br>Format yang diterima: <strong>PDF, DOC, DOCX</strong> — maks. 10MB, hanya 1 file.</div>

        <form id="formBeritaAcara" action="{{ route('admin.seminar.upload-berita-acara') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- AREA UPLOAD --}}
            <div class="upload-area" id="uploadArea">
                <input type="file"
                    id="inputBeritaAcara"
                    name="file_berita_acara"
                    accept=".pdf,.doc,.docx"
                    onchange="handleFileChange(this)">
                <div class="upload-icon">📄</div>
                <div class="upload-label">Klik untuk memilih file</div>
                <div class="upload-hint">PDF, DOC, atau DOCX — maksimal 10MB</div>
            </div>

            {{-- PREVIEW FILE TERPILIH --}}
            <div class="file-preview" id="filePreview">
                <span style="font-size:20px;">📎</span>
                <span class="file-preview-name" id="filePreviewName"></span>
                <button type="button" class="file-preview-remove" onclick="hapusFile()" title="Hapus file">✕</button>
            </div>

            {{-- PESAN ERROR --}}
            <div class="file-error" id="fileError"></div>

            <div class="modal-actions">
                <button type="button" class="btn-batal" onclick="tutupModalBeritaAcara()">Batal</button>
                <button type="submit" class="btn-upload" id="btnUpload">⬆ Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
    // ====== FILTER TABLE ======
    const searchInput = document.getElementById('searchInput');
    const filterAdm   = document.getElementById('filterAdm');
    const filterSeminar = document.getElementById('filterSeminar');
    const tableBody   = document.getElementById('tableBody');
    const noResult    = document.getElementById('noResult');

    function applyFilter() {
        const q       = searchInput.value.toLowerCase().trim();
        const adm     = filterAdm.value.toLowerCase();
        const seminar = filterSeminar.value.toLowerCase();
        const rows    = tableBody.querySelectorAll('tr[data-search]');
        let visible   = 0;

        rows.forEach(row => {
            const matchSearch  = !q       || row.dataset.search.includes(q);
            const matchAdm     = !adm     || row.dataset.adm === adm;
            const matchSeminar = !seminar || row.dataset.seminar === seminar;

            if (matchSearch && matchAdm && matchSeminar) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        noResult.style.display = (visible === 0 && rows.length > 0) ? 'block' : 'none';
    }

    function resetFilter() {
        searchInput.value   = '';
        filterAdm.value     = '';
        filterSeminar.value = '';
        applyFilter();
    }

    let searchTimeout = null;
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilter, 200);
    });

    // ====== MODAL ======
    function bukaModalBeritaAcara() {
        document.getElementById('modalBeritaAcara').classList.add('active');
    }

    function tutupModalBeritaAcara() {
        document.getElementById('modalBeritaAcara').classList.remove('active');
        hapusFile();
    }

    // Tutup modal kalau klik di luar box
    document.getElementById('modalBeritaAcara').addEventListener('click', function (e) {
        if (e.target === this) tutupModalBeritaAcara();
    });

    // ====== HANDLE FILE ======
    const ALLOWED_TYPES = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    const ALLOWED_EXT   = ['.pdf', '.doc', '.docx'];
    const MAX_SIZE_MB   = 10;

    function handleFileChange(input) {
        const fileError   = document.getElementById('fileError');
        const filePreview = document.getElementById('filePreview');
        const previewName = document.getElementById('filePreviewName');
        const uploadArea  = document.getElementById('uploadArea');
        const btnUpload   = document.getElementById('btnUpload');

        fileError.classList.remove('show');
        fileError.textContent = '';

        if (!input.files || input.files.length === 0) {
            return;
        }

        // Hanya 1 file — ambil yang pertama saja
        const file = input.files[0];
        const ext  = '.' + file.name.split('.').pop().toLowerCase();

        // Validasi format
        if (!ALLOWED_EXT.includes(ext)) {
            fileError.textContent = '❌ Format tidak didukung. Gunakan PDF, DOC, atau DOCX.';
            fileError.classList.add('show');
            input.value = '';
            return;
        }

        // Validasi ukuran
        if (file.size > MAX_SIZE_MB * 1024 * 1024) {
            fileError.textContent = '❌ Ukuran file melebihi ' + MAX_SIZE_MB + 'MB.';
            fileError.classList.add('show');
            input.value = '';
            return;
        }

        // Tampilkan preview
        previewName.textContent = file.name;
        filePreview.classList.add('show');
        uploadArea.style.display = 'none';

        // Aktifkan tombol upload
        btnUpload.classList.add('ready');
    }

    function hapusFile() {
        const input       = document.getElementById('inputBeritaAcara');
        const filePreview = document.getElementById('filePreview');
        const uploadArea  = document.getElementById('uploadArea');
        const btnUpload   = document.getElementById('btnUpload');
        const fileError   = document.getElementById('fileError');

        input.value = '';
        filePreview.classList.remove('show');
        uploadArea.style.display = '';
        btnUpload.classList.remove('ready');
        fileError.classList.remove('show');
        fileError.textContent = '';
    }
</script>

@endsection