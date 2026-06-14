@extends('layouts.app')

@section('title', 'Detail Bimbingan - ' . ($mahasiswa->nama ?? '-'))

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

    .wrap { background: var(--bg); min-height: 100vh; }

    .info-card {
        background: var(--white); border-radius: var(--radius);
        border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(0,0,0,.05);
        padding: 24px 28px; margin-bottom: 20px;
        display: grid; grid-template-columns: 1fr auto auto; gap: 24px; align-items: center;
    }
    .info-label { font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
    .info-nama  { font-size: 20px; font-weight: 800; color: var(--neutral); margin-bottom: 4px; }
    .info-nim   { font-size: 13px; color: var(--muted); font-weight: 600; }

    .stat-box {
        background: var(--gold-lt); border: 1.5px solid var(--gold-border);
        border-radius: 12px; padding: 16px 22px; text-align: center; min-width: 110px;
    }
    .stat-number { font-size: 28px; font-weight: 900; color: var(--gold); line-height: 1; }
    .stat-label  { font-size: 11px; color: #92400E; font-weight: 600; margin-top: 4px; }

    .kelayakan-card {
        background: var(--white); border-radius: var(--radius);
        border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(0,0,0,.05);
        padding: 20px 28px; margin-bottom: 20px;
    }
    .kelayakan-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 10px; }
    .kelayakan-title  { font-size: 14px; font-weight: 700; color: var(--neutral); }
    .badge-layak { display: inline-flex; align-items: center; gap: 5px; padding: 5px 14px; border-radius: 99px; font-size: 12px; font-weight: 700; }
    .kelayakan-desc { font-size: 12.5px; color: var(--muted); margin-bottom: 12px; }
    .progress-wrap  { background: #F3F4F6; border-radius: 99px; height: 10px; overflow: hidden; }
    .progress-bar   { height: 100%; border-radius: 99px; transition: width .6s ease; }
    .progress-info  { display: flex; justify-content: space-between; font-size: 12px; color: var(--muted); margin-top: 6px; font-weight: 600; }

    .tabel-card {
        background: var(--white); border-radius: var(--radius);
        border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(0,0,0,.05);
        overflow: hidden;
    }
    .tabel-header {
        padding: 16px 20px; border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
    }
    .tabel-title { font-size: 15px; font-weight: 700; color: var(--neutral); }
    .filter-row  { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
    .search-wrap { position: relative; }
    .search-wrap svg { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); pointer-events: none; }
    .search-input {
        padding: 8px 12px 8px 32px; border: 1.5px solid var(--border); border-radius: 8px;
        font-size: 13px; outline: none; background: #FAFAFA; font-family: inherit;
        transition: border .2s; width: 200px;
    }
    .search-input:focus { border-color: var(--gold); background: #fff; }
    .filter-select {
        padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 8px;
        font-size: 13px; outline: none; background: #FAFAFA; font-family: inherit; cursor: pointer;
    }
    .filter-select:focus { border-color: var(--gold); }
    .btn-reset-sm {
        padding: 8px 14px; border: 1.5px solid var(--border); border-radius: 8px;
        font-size: 12px; font-weight: 600; background: #fff; color: var(--muted);
        cursor: pointer; transition: .2s; font-family: inherit; white-space: nowrap;
    }
    .btn-reset-sm:hover { border-color: var(--gold); color: var(--gold); }

    .tabel-scroll { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 760px; }
    thead th {
        padding: 12px 16px; font-size: 11px; font-weight: 700; color: var(--muted);
        text-align: left; background: #FAFAFA; border-bottom: 1px solid var(--border);
        white-space: nowrap; text-transform: uppercase; letter-spacing: .4px;
    }
    tbody tr { border-bottom: 1px solid #F3F4F6; transition: background .15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #FAFBFF; }
    tbody td { padding: 14px 16px; font-size: 13px; color: var(--neutral); vertical-align: middle; }

    .badge-ke {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; background: var(--gold-lt);
        border: 1.5px solid var(--gold-border); border-radius: 8px;
        font-size: 13px; font-weight: 800; color: var(--gold);
    }

    .thumb {
        width: 52px; height: 52px; border-radius: 8px; object-fit: cover;
        border: 1.5px solid var(--border); cursor: pointer;
        transition: transform .2s; display: block;
    }
    .thumb:hover { transform: scale(1.08); }

    .btn-back {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px; border-radius: 10px; font-size: 13px; font-weight: 700;
        background: var(--white); border: 1.5px solid var(--border); color: var(--muted);
        text-decoration: none; transition: .2s; margin-bottom: 20px;
    }
    .btn-back:hover { border-color: var(--gold); color: var(--gold); }

    .empty-row td { text-align: center; padding: 48px; color: var(--muted); font-size: 14px; }

    .empty-dash { color: #9CA3AF; font-size: 12px; }

    /* ===== BADGE STATUS VALIDASI (TABEL) ===== */
    .badge-validasi {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 99px; font-size: 11px; font-weight: 700;
        white-space: nowrap;
    }

    /* ===== CATATAN DI TABEL ===== */
    .catatan-text {
        font-size: 12px; color: var(--muted); max-width: 160px;
        display: block; line-height: 1.45; white-space: normal;
    }

    /* ===== KLIK BARIS UNTUK MODAL ===== */
    tbody tr.clickable-row { cursor: pointer; }
    tbody tr.clickable-row:hover { background: #F0F4FF; }

    /* ===== MODAL FOTO DOKUMENTASI ===== */
    .modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.5); z-index: 2000;
        align-items: center; justify-content: center; padding: 20px;
    }
    .modal-overlay.show { display: flex; }
    .modal-foto {
        background: #fff; border-radius: 16px; padding: 20px;
        max-width: 420px; width: 100%;
        box-shadow: 0 20px 60px rgba(0,0,0,.25);
    }
    .modal-foto img { width: 100%; border-radius: 10px; object-fit: cover; }

    /* ===== MODAL VALIDASI ===== */
    .modal-validasi-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.45); z-index: 2100;
        align-items: center; justify-content: center; padding: 20px;
        overflow-y: auto;
    }
    .modal-validasi-overlay.show { display: flex; }
    .modal-validasi {
        background: #fff; border-radius: 16px; padding: 24px 26px;
        max-width: 560px; width: 100%; max-height: 90vh; overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,.25);
        font-family: inherit;
    }

    /* HEADER */
    .mv-header {
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px solid var(--border);
    }
    .mv-title { font-size: 14px; font-weight: 800; color: var(--neutral); flex: 1; }
    .mv-close {
        width: 28px; height: 28px; border-radius: 50%; border: none;
        background: #F3F4F6; cursor: pointer; font-size: 16px; color: var(--muted);
        flex-shrink: 0; line-height: 1; display: flex; align-items: center; justify-content: center;
    }
    .mv-close:hover { background: #E5E7EB; }

    /* INFO: MAHASISWA + TANGGAL */
    .mv-info-row {
        display: flex; align-items: flex-start; justify-content: space-between;
        margin-bottom: 16px; gap: 12px;
    }
    .mv-info-left { flex: 1; }
    .mv-nama { font-size: 16px; font-weight: 800; color: var(--neutral); margin-bottom: 2px; }
    .mv-nim  { font-size: 12px; color: var(--muted); font-weight: 500; margin-bottom: 10px; }
    .mv-badge-ke {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 12px; border-radius: 99px; font-size: 11.5px; font-weight: 700;
        background: var(--gold-lt); border: 1px solid var(--gold-border); color: #92400E;
    }
    .mv-info-right { text-align: right; flex-shrink: 0; }
    .mv-tgl-label {
        font-size: 10px; font-weight: 700; color: var(--muted);
        text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px;
    }
    .mv-tgl-value {
        font-size: 13px; font-weight: 700; color: var(--neutral);
        display: flex; align-items: center; gap: 5px; justify-content: flex-end;
    }

    /* SECTION GENERIC */
    .mv-section { margin-bottom: 16px; }
    .mv-section-label {
        font-size: 10.5px; font-weight: 700; color: var(--muted);
        text-transform: uppercase; letter-spacing: .4px; margin-bottom: 7px;
    }

    /* JUDUL TA */
    .mv-judul-value { font-size: 14px; font-weight: 800; color: var(--neutral); line-height: 1.4; }

    /* TOPIK BOX */
    .mv-topik-box {
        background: var(--gold-lt); border: 1px solid var(--gold-border);
        border-radius: 10px; padding: 12px 14px; font-size: 13px; color: var(--neutral);
        font-style: italic; line-height: 1.55;
    }

    .mv-col-card {
        border: 1px solid var(--border); border-radius: 12px; padding: 14px;
    }
    .mv-empty-text { color: #9CA3AF; font-size: 12px; }

    /* DOKUMENTASI GRID */
    .mv-dok-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px;
    }
    .mv-dok-img {
        width: 100%; aspect-ratio: 1 / 1; object-fit: cover; border-radius: 8px;
        border: 1px solid var(--border); cursor: pointer; transition: opacity .2s; display: block;
    }
    .mv-dok-img:hover { opacity: .82; }
    .mv-dok-more {
        width: 100%; aspect-ratio: 1 / 1; border-radius: 8px;
        border: 1px solid var(--border); background: #F3F4F6;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 700; color: var(--muted); cursor: default;
    }
    .mv-dok-caption {
        font-size: 11px; color: var(--muted); margin-top: 6px;
    }

    /* ===== TOMBOL TIDAK VALID / VALID ===== */
    .mv-action-row {
        display: flex; justify-content: flex-end; gap: 10px; margin-bottom: 14px;
    }
    .mv-btn-tv {
        padding: 8px 18px; border-radius: 99px; font-size: 12.5px; font-weight: 700;
        cursor: pointer; transition: .2s; border: 1.5px solid #FECACA;
        background: #fff; color: #DC2626;
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    }
    .mv-btn-tv:hover { background: #FEF2F2; }
    .mv-btn-tv.active { background: #EF4444; color: #fff; border-color: #EF4444; }

    .mv-btn-v {
        padding: 8px 18px; border-radius: 99px; font-size: 12.5px; font-weight: 700;
        cursor: pointer; transition: .2s; border: 1.5px solid #BBF7D0;
        background: #fff; color: #16A34A;
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    }
    .mv-btn-v:hover { background: #F0FDF4; }
    .mv-btn-v.active { background: #22C55E; color: #fff; border-color: #22C55E; }

    /* CATATAN DOSEN */
    .mv-catatan-wrap { display: none; margin-bottom: 16px; }
    .mv-catatan-label {
        font-size: 10.5px; font-weight: 700; color: var(--muted);
        text-transform: uppercase; letter-spacing: .4px; margin-bottom: 6px; display: block;
    }
    .mv-catatan-textarea {
        width: 100%; min-height: 90px; padding: 11px 12px;
        border: 1.5px solid var(--border); border-radius: 10px;
        font-size: 13px; font-family: inherit; resize: vertical; outline: none;
        color: var(--neutral); box-sizing: border-box;
    }
    .mv-catatan-textarea::placeholder { color: #B0B7C3; }
    .mv-catatan-textarea:focus { border-color: var(--gold); }

    /* KIRIM */
    .mv-kirim-row {
        display: flex; justify-content: flex-end;
    }
    .mv-btn-kirim {
        padding: 10px 22px; border-radius: 10px; border: none;
        background: var(--gold); color: #fff; font-size: 13px; font-weight: 800;
        cursor: pointer; transition: background .2s; letter-spacing: .3px;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .mv-btn-kirim:hover { background: #B5901E; }

    @media (max-width: 768px) {
        .info-card { grid-template-columns: 1fr; }
        .mv-info-right { text-align: left; }
        .mv-tgl-value { justify-content: flex-start; }
    }

    /* ===== MODAL KONFIRMASI ===== */
    .modal-konfirmasi-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.45); z-index: 2200;
        align-items: center; justify-content: center; padding: 20px;
    }
    .modal-konfirmasi-overlay.show { display: flex; }
    .modal-konfirmasi {
        background: #fff; border-radius: 18px; padding: 32px 28px 24px;
        max-width: 360px; width: 100%; text-align: center;
        box-shadow: 0 20px 60px rgba(0,0,0,.25);
        font-family: inherit;
    }
    .mk-icon-wrap {
        width: 56px; height: 56px; border-radius: 50%;
        background: var(--gold-lt); border: 2px solid var(--gold-border);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px; font-size: 26px; font-weight: 800; color: var(--gold);
    }
    .mk-title {
        font-size: 19px; font-weight: 800; color: var(--neutral); margin-bottom: 10px;
    }
    .mk-desc {
        font-size: 13px; color: var(--muted); line-height: 1.6; margin-bottom: 22px;
    }
    .mk-action-row {
        display: flex; gap: 10px;
    }
    .mk-btn-batal {
        flex: 1; padding: 11px 10px; border-radius: 10px; font-size: 13px; font-weight: 700;
        cursor: pointer; transition: .2s; border: 1.5px solid var(--border);
        background: #fff; color: var(--muted);
    }
    .mk-btn-batal:hover { border-color: #D1D5DB; background: #F9FAFB; }
    .mk-btn-konfirmasi {
        flex: 1; padding: 11px 10px; border-radius: 10px; font-size: 13px; font-weight: 700;
        cursor: pointer; transition: .2s; border: none;
        background: var(--gold); color: #fff;
    }
    .mk-btn-konfirmasi:hover { background: #B5901E; }
</style>

<div class="wrap">

    <a href="{{ route('dosen.bimbingan.index') }}" class="btn-back">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
        </svg>
        Kembali
    </a>

    {{-- INFO MAHASISWA --}}
    <div class="info-card">
        <div>
            <div class="info-label">Informasi Mahasiswa</div>
            <div class="info-nama">{{ $mahasiswa->nama ?? '-' }}</div>
            <div class="info-nim">NIM: {{ $mahasiswa->nim_nid ?? '-' }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $totalBimbingan }}</div>
            <div class="stat-label">Total Bimbingan</div>
        </div>
    </div>

    {{-- STATUS KELAYAKAN --}}
    @php
        $persen = min(100, round(($totalBimbingan / $minBimbingan) * 100));

        if ($persen >= 100) {
            $barColor  = '#22C55E';
            $barLight  = '#F0FDF4';
            $barBorder = '#BBF7D0';
            $barText   = '#15803D';
            $barLabel  = 'Selesai';
            $badgeIcon = '✓';
            $badgeText = 'Layak Seminar';
        } elseif ($persen >= 50) {
            $barColor  = '#F59E0B';
            $barLight  = '#FFFBEB';
            $barBorder = '#FDE68A';
            $barText   = '#92400E';
            $barLabel  = 'Berlangsung';
            $badgeIcon = '⏳';
            $badgeText = 'Belum Mencukupi';
        } else {
            $barColor  = '#EF4444';
            $barLight  = '#FEF2F2';
            $barBorder = '#FECACA';
            $barText   = '#991B1B';
            $barLabel  = 'Awal';
            $badgeIcon = '✕';
            $badgeText = 'Belum Mencukupi';
        }
    @endphp

    <div class="kelayakan-card">
        <div class="kelayakan-header">
            <div class="kelayakan-title">Status Kelayakan Seminar</div>
            <span class="badge-layak" style="background:{{ $barLight }};color:{{ $barText }};border:1px solid {{ $barBorder }};">
                {{ $badgeIcon }} {{ $badgeText }}
            </span>
        </div>
        <div class="kelayakan-desc">
            Minimal {{ $minBimbingan }} kali bimbingan sebagai persyaratan seminar tugas akhir.
        </div>
        <div class="progress-wrap">
            <div class="progress-bar" style="width:{{ $persen }}%; background:{{ $barColor }};"></div>
        </div>
        <div class="progress-info">
            <div style="display:flex; align-items:center; gap:8px;">
                <span>{{ $totalBimbingan }} / {{ $minBimbingan }} Bimbingan</span>
                <span style="display:inline-flex;align-items:center;gap:4px;font-size:10.5px;font-weight:700;padding:2px 8px;border-radius:99px;background:{{ $barLight }};color:{{ $barText }};border:1px solid {{ $barBorder }};">
                    <span style="width:6px;height:6px;border-radius:50%;background:{{ $barColor }};display:inline-block;"></span>
                    {{ $barLabel }}
                </span>
            </div>
            <span style="color:{{ $barColor }}; font-weight:800;">{{ $persen }}%</span>
        </div>
    </div>

    {{-- TABEL RIWAYAT --}}
    <div class="tabel-card">
        <div class="tabel-header">
            <div class="tabel-title">Riwayat Bimbingan</div>
            <div class="filter-row">
                <div class="search-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#9CA3AF" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="text" class="search-input" id="searchTopik"
                        placeholder="Cari topik bimbingan..."
                        oninput="filterDetail()">
                </div>
                <select class="filter-select" id="filterStatusDetail" onchange="filterDetail()">
                    <option value="">Semua Status</option>
                    <option value="Baru Dikirim">Baru Dikirim</option>
                    <option value="Sudah Dilihat">Sudah Dilihat</option>
                </select>
                <button class="btn-reset-sm" onclick="resetDetail()">Reset</button>
            </div>
        </div>
        <div class="tabel-scroll">
            <table id="tabelDetail">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Ke-</th>
                        <th>Judul</th>
                        <th>Topik Bimbingan</th>
                        <th>Dokumentasi</th>
                        <th>Status</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bimbingan as $i => $b)
                    @php
                        // ===== OLAH DATA DOKUMENTASI =====
                        // Bisa multi-foto (array) atau 1 foto (string)
                        $dokList = [];
                        if (!empty($b->dokumentasi)) {
                            $dekDok = json_decode($b->dokumentasi, true);
                            if (is_array($dekDok)) {
                                $dokList = $dekDok;
                            } else {
                                $dokList = [$b->dokumentasi];
                            }
                        }

                        $dokumentasiHtml = '';
                        if (count($dokList) > 0) {
                            $showMax  = 2; // tampil 2 foto + 1 slot "+N More"
                            $total    = count($dokList);
                            $documented = array_slice($dokList, 0, $showMax);
                            $remaining  = $total - $showMax;

                            $dokumentasiHtml = '<div class="mv-dok-grid">';
                            foreach ($documented as $dok) {
                                $dokUrl = asset('uploads/bimbingan/' . $dok);
                                $dokumentasiHtml .= '<img src="'.$dokUrl.'" class="mv-dok-img" onclick="lihatFoto(\''.$dokUrl.'\')">';
                            }
                            if ($remaining > 0) {
                                $dokumentasiHtml .= '<div class="mv-dok-more">+' . $remaining . ' More</div>';
                            } elseif ($total < 3) {
                                // padding kosong agar grid rapi
                                for ($pad = $total; $pad < 3; $pad++) {
                                    $dokumentasiHtml .= '<div></div>';
                                }
                            }
                            $dokumentasiHtml .= '</div>';
                            $dokumentasiHtml .= '<div class="mv-dok-caption">Klik gambar untuk memperbesar</div>';
                        } else {
                            $dokumentasiHtml = '<span class="mv-empty-text">Tidak ada dokumentasi</span>';
                        }

                        // ===== STATUS VALIDASI =====
                        $statusValidasi = $b->status_validasi ?? 'Validasi Bimbingan';
                        if ($statusValidasi === 'Valid') {
                            $svBg = '#F0FDF4'; $svBorder = '#BBF7D0'; $svText = '#15803D'; $svIcon = '✓';
                        } elseif ($statusValidasi === 'Tidak Valid') {
                            $svBg = '#FEF2F2'; $svBorder = '#FECACA'; $svText = '#991B1B'; $svIcon = '✕';
                        } else {
                            $svBg = '#FFFBEB'; $svBorder = '#FDE68A'; $svText = '#92400E'; $svIcon = '⏳';
                        }
                    @endphp
                    <tr class="clickable-row"
                        onclick="bukaModalValidasi(this)"
                        data-topik="{{ strtolower($b->topik_bimbingan) }}"
                        data-status="{{ $b->status }}"
                        data-id="{{ $b->id }}"
                        data-nama="{{ $mahasiswa->nama ?? '-' }}"
                        data-nim="{{ $mahasiswa->nim_nid ?? '-' }}"
                        data-ke="{{ $b->pertemuan_ke }}"
                        data-tanggal="{{ \Carbon\Carbon::parse($b->tanggal_bimbingan)->translatedFormat('d M Y') }}"
                        data-judul="{{ $judulTA }}"
                        data-topik-full="{{ $b->topik_bimbingan }}"
                        data-catatan="{{ $b->catatan_dosen ?? '' }}"
                        data-status-validasi="{{ $statusValidasi }}"
                        data-dokumentasi-html="{{ htmlspecialchars($dokumentasiHtml, ENT_QUOTES, 'UTF-8') }}"
                    >
                        <td>{{ $i + 1 }}</td>
                        <td style="white-space:nowrap;font-size:12.5px;">
                            {{ \Carbon\Carbon::parse($b->tanggal_bimbingan)->translatedFormat('d M Y') }}
                        </td>
                        <td><span class="badge-ke">{{ $b->pertemuan_ke }}</span></td>
                        <td style="max-width:160px;font-size:12px;">{{ Str::limit($judulTA, 40) }}</td>
                        <td style="max-width:180px;font-size:12.5px;">{{ $b->topik_bimbingan }}</td>

                        {{-- DOKUMENTASI --}}
                        <td onclick="event.stopPropagation()">
                            @if(count($dokList) > 0)
                                <img src="{{ asset('uploads/bimbingan/' . $dokList[0]) }}"
                                     class="thumb"
                                     alt="Dokumentasi"
                                     onclick="lihatFoto('{{ asset('uploads/bimbingan/' . $dokList[0]) }}')">
                            @else
                                <span style="color:#9CA3AF;font-size:12px;">—</span>
                            @endif
                        </td>

                        {{-- STATUS --}}
                        <td>
                            <span class="badge-validasi" style="background:{{ $svBg }};color:{{ $svText }};border:1px solid {{ $svBorder }};">
                                {{ $statusValidasi }}
                            </span>
                        </td>

                        {{-- CATATAN: tampil jika ada --}}
                        <td>
                            @if(!empty($b->catatan_dosen))
                                <span class="catatan-text">{{ $b->catatan_dosen }}</span>
                            @else
                                <span class="empty-dash">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row">
                        <td colspan="8">
                            <div style="font-size:32px;margin-bottom:8px;">📋</div>
                            Belum ada riwayat bimbingan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- MODAL FOTO --}}
<div class="modal-overlay" id="modalFoto" onclick="tutupFoto(event)">
    <div class="modal-foto">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <span style="font-size:15px;font-weight:800;color:var(--neutral);">Dokumentasi Bimbingan</span>
            <button onclick="document.getElementById('modalFoto').classList.remove('show')"
                style="width:28px;height:28px;border-radius:50%;border:none;background:#F3F4F6;cursor:pointer;font-size:14px;color:var(--muted);">×</button>
        </div>
        <img id="fotoImg" src="" alt="Foto">
    </div>
</div>

{{-- MODAL VALIDASI --}}
<div class="modal-validasi-overlay" id="modalValidasi" onclick="tutupModalValidasiOverlay(event)">
    <div class="modal-validasi">

        {{-- HEADER --}}
        <div class="mv-header">
            <span class="mv-title">Detail Validasi Riwayat Bimbingan</span>
            <button type="button" class="mv-close" onclick="tutupModalValidasi()">×</button>
        </div>

        {{-- INFO: NAMA + TANGGAL --}}
        <div class="mv-info-row">
            <div class="mv-info-left">
                <div class="mv-nama" id="mvNama">-</div>
                <div class="mv-nim">NIM: <span id="mvNim">-</span></div>
                <span class="mv-badge-ke">📅 Bimbingan Ke- <span id="mvKe">-</span></span>
            </div>
            <div class="mv-info-right">
                <div class="mv-tgl-label">Tanggal Bimbingan</div>
                <div class="mv-tgl-value">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span id="mvTanggal">-</span>
                </div>
            </div>
        </div>

        {{-- JUDUL TUGAS AKHIR --}}
        <div class="mv-section">
            <div class="mv-section-label">Judul Tugas Akhir</div>
            <div class="mv-judul-value" id="mvJudul">-</div>
        </div>

        {{-- TOPIK BIMBINGAN --}}
        <div class="mv-section">
            <div class="mv-section-label">💬 Topik Bimbingan</div>
            <div class="mv-topik-box" id="mvTopik">-</div>
        </div>

        {{-- DOKUMENTASI --}}
        <div class="mv-section">
            <div class="mv-section-label">🖼️ Dokumentasi</div>
            <div class="mv-col-card">
                <div id="mvDokumentasi"></div>
            </div>
        </div>

        {{-- FORM VALIDASI --}}
        <form id="formValidasi" method="POST" onsubmit="return submitValidasi(event)">
            @csrf
            <input type="hidden" name="status_validasi" id="inputStatusValidasi" value="">
            <input type="hidden" name="catatan_dosen" id="inputCatatanDosen" value="">

            {{-- TOMBOL TIDAK VALID / VALID --}}
            <div class="mv-action-row">
                <button type="button" class="mv-btn-tv" id="btnTidakValid" onclick="setStatusValidasi('Tidak Valid')">
                    ✕ Tidak Valid
                </button>
                <button type="button" class="mv-btn-v" id="btnValid" onclick="setStatusValidasi('Valid')">
                    ✓ Valid
                </button>
            </div>

            {{-- CATATAN DOSEN (muncul jika Tidak Valid) --}}
            <div class="mv-catatan-wrap" id="catatanWrap">
                <label class="mv-catatan-label">Catatan Dosen</label>
                <textarea class="mv-catatan-textarea" id="catatanDosenInput"
                    placeholder="Tambahkan catatan hasil validasi riwayat bimbingan..."></textarea>
            </div>

            <div class="mv-kirim-row">
                <button type="submit" class="mv-btn-kirim">➤ Kirim</button>
            </div>
        </form>

    </div>
</div>

{{-- MODAL KONFIRMASI --}}
<div class="modal-konfirmasi-overlay" id="modalKonfirmasi" onclick="tutupModalKonfirmasiOverlay(event)">
    <div class="modal-konfirmasi">
        <div class="mk-icon-wrap" id="mkIcon">?</div>
        <div class="mk-title">Konfirmasi</div>
        <div class="mk-desc" id="mkDesc">
            Apakah Anda yakin ingin mengirim hasil validasi ini?
        </div>
        <div class="mk-action-row">
            <button type="button" class="mk-btn-batal" onclick="tutupModalKonfirmasi()">Batal</button>
            <button type="button" class="mk-btn-konfirmasi" onclick="konfirmasiKirim()">Konfirmasi</button>
        </div>
    </div>
</div>

<script>
    let selectedStatusValidasi = null;

    // ===== MODAL FOTO DOKUMENTASI =====
    function lihatFoto(src) {
        document.getElementById('fotoImg').src = src;
        document.getElementById('modalFoto').classList.add('show');
    }
    function tutupFoto(e) {
        if (e.target === document.getElementById('modalFoto'))
            document.getElementById('modalFoto').classList.remove('show');
    }

    // ===== FILTER TABEL =====
    function filterDetail() {
        const q      = document.getElementById('searchTopik').value.toLowerCase();
        const status = document.getElementById('filterStatusDetail').value;
        document.querySelectorAll('#tabelDetail tbody tr:not(.empty-row)').forEach(row => {
            const matchQ = !q || row.dataset.topik.includes(q);
            const matchS = !status || row.dataset.status === status;
            row.style.display = (matchQ && matchS) ? '' : 'none';
        });
    }
    function resetDetail() {
        document.getElementById('searchTopik').value = '';
        document.getElementById('filterStatusDetail').value = '';
        filterDetail();
    }

    // ===== MODAL VALIDASI =====
    function bukaModalValidasi(el) {
        // Bisa dipanggil dari <tr onclick> langsung, el sudah row
        const row = el.tagName === 'TR' ? el : el.closest('tr');

        document.getElementById('mvNama').textContent    = row.dataset.nama;
        document.getElementById('mvNim').textContent     = row.dataset.nim;
        document.getElementById('mvKe').textContent      = row.dataset.ke;
        document.getElementById('mvTanggal').textContent = row.dataset.tanggal;
        document.getElementById('mvJudul').textContent   = row.dataset.judul;
        document.getElementById('mvTopik').textContent   = '"' + row.dataset.topikFull + '"';

        // Decode HTML entities dari data-attribute
        document.getElementById('mvDokumentasi').innerHTML = decodeHTMLEntities(row.dataset.dokumentasiHtml);

        document.getElementById('catatanDosenInput').value = row.dataset.catatan || '';

        // Set form action
        document.getElementById('formValidasi').action = '/dosen/bimbingan/validasi/' + row.dataset.id;

        // Set status validasi awal
        const statusAwal = row.dataset.statusValidasi;
        setStatusValidasi(statusAwal === 'Valid' || statusAwal === 'Tidak Valid' ? statusAwal : null);

        document.getElementById('modalValidasi').classList.add('show');
    }

    function decodeHTMLEntities(str) {
        const txt = document.createElement('textarea');
        txt.innerHTML = str;
        return txt.value;
    }

    function tutupModalValidasi() {
        document.getElementById('modalValidasi').classList.remove('show');
    }
    function tutupModalValidasiOverlay(e) {
        if (e.target === document.getElementById('modalValidasi'))
            tutupModalValidasi();
    }

    function setStatusValidasi(status) {
        selectedStatusValidasi = status;

        document.getElementById('btnValid').classList.toggle('active', status === 'Valid');
        document.getElementById('btnTidakValid').classList.toggle('active', status === 'Tidak Valid');
        document.getElementById('inputStatusValidasi').value = status || '';

        document.getElementById('catatanWrap').style.display =
            (status === 'Tidak Valid') ? 'block' : 'none';
    }

    function submitValidasi(e) {
        e.preventDefault();

        if (!selectedStatusValidasi) {
            alert('Silakan pilih status "Valid" atau "Tidak Valid" terlebih dahulu.');
            return false;
        }
        if (selectedStatusValidasi === 'Tidak Valid') {
            const catatan = document.getElementById('catatanDosenInput').value.trim();
            if (!catatan) {
                alert('Catatan dosen wajib diisi jika status "Tidak Valid".');
                return false;
            }
            document.getElementById('inputCatatanDosen').value = catatan;
        } else {
            document.getElementById('inputCatatanDosen').value = '';
        }

        // Tampilkan modal konfirmasi sebelum benar-benar mengirim
        if (selectedStatusValidasi === 'Valid') {
            document.getElementById('mkIcon').textContent = '✓';
            document.getElementById('mkIcon').style.color = '#16A34A';
            document.getElementById('mkIcon').style.background = '#F0FDF4';
            document.getElementById('mkIcon').style.borderColor = '#BBF7D0';
            document.getElementById('mkDesc').innerHTML = 'Apakah Anda yakin ingin menandai riwayat bimbingan ini sebagai <strong>Valid</strong>?';
        } else {
            document.getElementById('mkIcon').textContent = '✕';
            document.getElementById('mkIcon').style.color = '#DC2626';
            document.getElementById('mkIcon').style.background = '#FEF2F2';
            document.getElementById('mkIcon').style.borderColor = '#FECACA';
            document.getElementById('mkDesc').innerHTML = 'Apakah Anda yakin ingin menandai riwayat bimbingan ini sebagai <strong>Tidak Valid</strong>?';
        }
        document.getElementById('modalKonfirmasi').classList.add('show');
        return false;
    }

    // ===== MODAL KONFIRMASI =====
    function tutupModalKonfirmasi() {
        document.getElementById('modalKonfirmasi').classList.remove('show');
    }
    function tutupModalKonfirmasiOverlay(e) {
        if (e.target === document.getElementById('modalKonfirmasi'))
            tutupModalKonfirmasi();
    }
    function konfirmasiKirim() {
        document.getElementById('modalKonfirmasi').classList.remove('show');
        document.getElementById('formValidasi').submit();
    }
</script>

@endsection