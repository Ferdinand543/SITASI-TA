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

body { background: var(--bg); }

/* ── BACK BUTTON ── */
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 18px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    background: var(--white);
    border: 1.5px solid var(--border);
    color: var(--muted);
    text-decoration: none;
    transition: .2s;
    margin-bottom: 20px;
}

.btn-back:hover { border-color: var(--gold); color: var(--gold); }

/* ── INFO ROW ── */
.info-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 20px;
}

/* INFO CARD - MAHASISWA */
.info-card {
    background: var(--white);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    padding: 22px 26px;
    display: flex;
    align-items: center;
    gap: 20px;
    border-left: 4px solid var(--gold);
}

.info-avatar {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: var(--gold-lt);
    border: 2px solid var(--gold-border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 800;
    color: var(--gold);
    flex-shrink: 0;
}

.info-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 4px;
}

.info-nama {
    font-size: 20px;
    font-weight: 800;
    color: var(--neutral);
    margin-bottom: 3px;
}

.info-nim {
    font-size: 13px;
    color: var(--muted);
    font-weight: 600;
}

/* STAT CARD */
.stat-card {
    background: var(--white);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    padding: 22px 26px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.stat-icon-box {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: var(--gold-lt);
    border: 1.5px solid var(--gold-border);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-number {
    font-size: 32px;
    font-weight: 900;
    color: var(--gold);
    line-height: 1;
    margin-bottom: 3px;
}

.stat-label-sm {
    font-size: 11px;
    color: var(--muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
}

/* ── KELAYAKAN CARD ── */
.kelayakan-card {
    background: var(--white);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    padding: 22px 26px;
    margin-bottom: 20px;
}

.kelayakan-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
    flex-wrap: wrap;
    gap: 8px;
}

.kelayakan-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--neutral);
}

.badge-layak {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 14px;
    border-radius: 99px;
    font-size: 12px;
    font-weight: 700;
}

.badge-layak.yes    { background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; }
.badge-layak.warn   { background: #FEF9EC; color: #B45309; border: 1px solid var(--gold-border); }
.badge-layak.danger { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }

.kelayakan-desc {
    font-size: 12.5px;
    color: var(--muted);
    margin-bottom: 14px;
}

.progress-wrap {
    background: #F3F4F6;
    border-radius: 99px;
    height: 10px;
    overflow: hidden;
    margin-bottom: 6px;
}

.progress-bar {
    height: 100%;
    border-radius: 99px;
    transition: width .6s ease, background .4s ease;
}

.progress-info {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    font-weight: 700;
    color: var(--neutral);
    margin-top: 6px;
}

.progress-pct {
    font-size: 13px;
    font-weight: 700;
}

/* ── TABEL CARD ── */
.tabel-card {
    background: var(--white);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    overflow: hidden;
}

.tabel-header {
    padding: 18px 22px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}

.tabel-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--neutral);
}

.filter-row { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }

.search-wrap { position: relative; }

.search-wrap i {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: #9CA3AF;
    font-size: 12px;
    pointer-events: none;
}

.search-sm {
    height: 38px;
    padding: 0 12px 0 32px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-size: 13px;
    outline: none;
    background: #FAFAFA;
    font-family: inherit;
    transition: border .2s;
    width: 210px;
}

.search-sm:focus { border-color: var(--gold); background: #fff; }
.search-sm::placeholder { color: #9CA3AF; }

.filter-select-sm {
    height: 38px;
    padding: 0 32px 0 12px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-size: 13px;
    outline: none;
    background: #FAFAFA url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 10px center;
    font-family: inherit;
    cursor: pointer;
    appearance: none;
    min-width: 140px;
    transition: border .2s;
}

.filter-select-sm:focus { border-color: var(--gold); background-color: #fff; }

.btn-reset-sm {
    height: 38px;
    padding: 0 14px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-size: 12px;
    font-weight: 600;
    background: #fff;
    color: var(--muted);
    cursor: pointer;
    transition: .2s;
    font-family: inherit;
    white-space: nowrap;
}

.btn-reset-sm:hover { border-color: var(--gold); color: var(--gold); }

/* TABLE */
.tabel-scroll { overflow-x: auto; }

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 820px;
}

thead tr { background: #F8FAFC; }

thead th {
    padding: 12px 16px;
    font-size: 11px;
    font-weight: 700;
    color: var(--muted);
    text-align: left;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
    text-transform: uppercase;
    letter-spacing: .4px;
}

tbody tr { border-bottom: 1px solid #F3F4F6; transition: background .15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #FFFDF5; }
tbody td { padding: 14px 16px; font-size: 13px; color: var(--neutral); vertical-align: middle; }

.badge-ke {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: var(--gold-lt);
    border: 1.5px solid var(--gold-border);
    border-radius: 8px;
    font-size: 13px;
    font-weight: 800;
    color: var(--gold);
}

/* DOKUMENTASI THUMBNAIL */
.thumb {
    width: 52px;
    height: 52px;
    border-radius: 8px;
    object-fit: cover;
    border: 1.5px solid var(--border);
    cursor: pointer;
    transition: transform .2s;
    display: block;
}

.thumb:hover { transform: scale(1.08); }

/* ── BADGE STATUS VALIDASI ── */
.badge-validasi {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 99px; font-size: 11px; font-weight: 700;
    white-space: nowrap;
}

/* ── CATATAN DI TABEL ── */
.catatan-text {
    font-size: 12px; color: var(--muted); max-width: 160px;
    display: block; line-height: 1.45; white-space: normal;
}
.empty-dash { color: #9CA3AF; font-size: 12px; }

/* ── KLIK BARIS UNTUK MODAL ── */
tbody tr.clickable-row { cursor: pointer; }
tbody tr.clickable-row:hover { background: #F0F4FF; }

/* EMPTY ROW */
.empty-row td {
    text-align: center;
    padding: 48px;
    color: var(--muted);
    font-size: 14px;
}

/* MODAL FOTO */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.5);
    z-index: 2300;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.modal-overlay.show { display: flex; }

.modal-foto {
    background: #fff;
    border-radius: 18px;
    padding: 20px;
    max-width: 440px;
    width: 100%;
    box-shadow: 0 20px 60px rgba(0,0,0,.25);
}

.modal-foto img {
    width: 100%;
    border-radius: 12px;
    object-fit: cover;
}

/* ══ MODAL VALIDASI ══ */
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

.mv-section { margin-bottom: 16px; }
.mv-section-label {
    font-size: 10.5px; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: .4px; margin-bottom: 7px;
}

.mv-judul-value { font-size: 14px; font-weight: 800; color: var(--neutral); line-height: 1.4; }

.mv-topik-box {
    background: var(--gold-lt); border: 1px solid var(--gold-border);
    border-radius: 10px; padding: 12px 14px; font-size: 13px; color: var(--neutral);
    font-style: italic; line-height: 1.55;
}

.mv-col-card { border: 1px solid var(--border); border-radius: 12px; padding: 14px; }
.mv-empty-text { color: #9CA3AF; font-size: 12px; }

.mv-dok-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; }
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
.mv-dok-caption { font-size: 11px; color: var(--muted); margin-top: 6px; }

.mv-action-row { display: flex; justify-content: flex-end; gap: 10px; margin-bottom: 14px; }
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

.mv-kirim-row { display: flex; justify-content: flex-end; }
.mv-btn-kirim {
    padding: 10px 22px; border-radius: 10px; border: none;
    background: var(--gold); color: #fff; font-size: 13px; font-weight: 800;
    cursor: pointer; transition: background .2s; letter-spacing: .3px;
    display: inline-flex; align-items: center; gap: 6px;
}
.mv-btn-kirim:hover { background: #B5901E; }

/* ══ MODAL KONFIRMASI ══ */
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
    margin: 0 auto 16px; font-size: 22px; font-weight: 800; color: var(--gold);
}
.mk-title { font-size: 19px; font-weight: 800; color: var(--neutral); margin-bottom: 10px; }
.mk-desc { font-size: 13px; color: var(--muted); line-height: 1.6; margin-bottom: 22px; }
.mk-action-row { display: flex; gap: 10px; }
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

@media (max-width: 768px) {
    .info-row { grid-template-columns: 1fr; }
    .mv-info-right { text-align: left; }
    .mv-tgl-value { justify-content: flex-start; }
}
</style>

{{-- BACK --}}
<a href="{{ route('admin.bimbingan.dosen', $dosen->nim_nid) }}" class="btn-back">
    <i class="fa-solid fa-arrow-left" style="font-size:12px;"></i>
    Kembali
</a>

{{-- ══ INFO ROW ══ --}}
<div class="info-row">

    {{-- Info Mahasiswa --}}
    <div class="info-card">
        <div class="info-avatar">{{ strtoupper(substr($mahasiswa->nama ?? 'M', 0, 1)) }}</div>
        <div>
            <div class="info-label">Informasi Mahasiswa</div>
            <div class="info-nama">{{ $mahasiswa->nama ?? '-' }}</div>
            <div class="info-nim">NIM: {{ $mahasiswa->nim_nid ?? '-' }}</div>
        </div>
    </div>

    {{-- Total Bimbingan --}}
    <div class="stat-card">
        <div class="stat-icon-box">
            <i class="fa-solid fa-book-open" style="font-size:22px; color:var(--gold);"></i>
        </div>
        <div>
            <div class="stat-label-sm">Total Bimbingan</div>
            <div class="stat-number">{{ $totalBimbingan }}</div>
        </div>
    </div>

</div>

{{-- ══ KELAYAKAN SEMINAR ══ --}}
@php
    $pct      = $minBimbingan > 0 ? ($totalBimbingan / $minBimbingan) * 100 : 0;
    $pctCap   = min(100, $pct);

    if ($pctCap >= 100) {
        $badgeClass = 'yes';
        $barColor   = '#16A34A';
        $pctColor   = '#16A34A';
        $badgeIcon  = 'fa-solid fa-circle-check';
        $badgeText  = 'Layak Seminar';
    } elseif ($pctCap >= 50) {
        $badgeClass = 'warn';
        $barColor   = '#C9A227';
        $pctColor   = '#C9A227';
        $badgeIcon  = 'fa-regular fa-clock';
        $badgeText  = 'Belum Memenuhi';
    } else {
        $badgeClass = 'danger';
        $barColor   = '#DC2626';
        $pctColor   = '#DC2626';
        $badgeIcon  = 'fa-solid fa-circle-xmark';
        $badgeText  = 'Belum Memenuhi';
    }
@endphp

<div class="kelayakan-card">
    <div class="kelayakan-top">
        <div class="kelayakan-title">Status Kelayakan Seminar</div>
        <span class="badge-layak {{ $badgeClass }}">
            <i class="{{ $badgeIcon }}" style="font-size:11px;"></i>
            {{ $badgeText }}
        </span>
    </div>
    <div class="kelayakan-desc">
        Minimal {{ $minBimbingan }} kali bimbingan sebagai persyaratan seminar tugas akhir.
    </div>
    <div class="progress-wrap">
        <div class="progress-bar" style="width: {{ $pctCap }}%; background: {{ $barColor }};"></div>
    </div>
    <div class="progress-info">
        <span>{{ $totalBimbingan }} / {{ $minBimbingan }} Bimbingan</span>
        <span class="progress-pct" style="color: {{ $pctColor }};">{{ round($pctCap) }}%</span>
    </div>
</div>

{{-- ══ TABEL RIWAYAT ══ --}}
<div class="tabel-card">
    <div class="tabel-header">
        <div class="tabel-title">Riwayat Bimbingan</div>
        <div class="filter-row">
            <div class="search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" class="search-sm" id="searchTopik"
                    placeholder="Cari topik bimbingan..."
                    oninput="filterDetail()">
            </div>
            <select class="filter-select-sm" id="filterStatusDetail" onchange="filterDetail()">
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
                    <th style="width:46px;">No</th>
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
                        $showMax    = 2;
                        $total      = count($dokList);
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
                            for ($pad = $total; $pad < 3; $pad++) {
                                $dokumentasiHtml .= '<div></div>';
                            }
                        }
                        $dokumentasiHtml .= '</div>';
                        $dokumentasiHtml .= '<div class="mv-dok-caption">Klik gambar untuk memperbesar</div>';
                    } else {
                        $dokumentasiHtml = '<span class="mv-empty-text">Tidak ada dokumentasi</span>';
                    }

                    $statusValidasi = $b->status_validasi ?? 'Validasi Bimbingan';
                    if ($statusValidasi === 'Valid') {
                        $svBg = '#F0FDF4'; $svBorder = '#BBF7D0'; $svText = '#15803D'; $svIcon = 'fa-solid fa-circle-check';
                    } elseif ($statusValidasi === 'Tidak Valid') {
                        $svBg = '#FEF2F2'; $svBorder = '#FECACA'; $svText = '#991B1B'; $svIcon = 'fa-solid fa-circle-xmark';
                    } else {
                        $svBg = '#FFFBEB'; $svBorder = '#FDE68A'; $svText = '#92400E'; $svIcon = 'fa-regular fa-clock';
                    }
                @endphp
                <tr class="clickable-row"
                    onclick="bukaModalValidasi(this)"
                    data-topik="{{ strtolower($b->topik_bimbingan) }}"
                    data-status="{{ $b->status ?? '' }}"
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
                    <td style="color:#94a3b8; font-weight:600;">{{ $i + 1 }}</td>

                    <td style="white-space:nowrap; font-size:12.5px; color:var(--muted);">
                        {{ \Carbon\Carbon::parse($b->tanggal_bimbingan)->translatedFormat('d M Y') }}
                    </td>

                    <td><span class="badge-ke">{{ $b->pertemuan_ke }}</span></td>

                    <td style="max-width:160px; font-size:12px; line-height:1.4;">
                        {{ Str::limit($judulTA, 40) }}
                    </td>

                    <td style="max-width:200px; font-size:12.5px; line-height:1.4;">
                        {{ $b->topik_bimbingan }}
                    </td>

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

                    {{-- STATUS VALIDASI --}}
                    <td>
                        <span class="badge-validasi" style="background:{{ $svBg }};color:{{ $svText }};border:1px solid {{ $svBorder }};">
                            <i class="{{ $svIcon }}" style="font-size:10px;"></i> {{ $statusValidasi }}
                        </span>
                    </td>

                    {{-- CATATAN --}}
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
                        <i class="fa-regular fa-clipboard" style="font-size:32px; display:block; margin-bottom:8px; color:#d1d5db;"></i>
                        Belum ada riwayat bimbingan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL FOTO --}}
<div class="modal-overlay" id="modalFoto" onclick="tutupFoto(event)">
    <div class="modal-foto">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
            <span style="font-size:15px; font-weight:800; color:var(--neutral);">Dokumentasi Bimbingan</span>
            <button onclick="document.getElementById('modalFoto').classList.remove('show')"
                style="width:28px;height:28px;border-radius:50%;border:none;background:#F3F4F6;
                       cursor:pointer;font-size:14px;color:var(--muted);">×</button>
        </div>
        <img id="fotoImg" src="" alt="Foto">
    </div>
</div>

{{-- MODAL VALIDASI --}}
<div class="modal-validasi-overlay" id="modalValidasi" onclick="tutupModalValidasiOverlay(event)">
    <div class="modal-validasi">

        <div class="mv-header">
            <span class="mv-title">Detail Validasi Riwayat Bimbingan</span>
            <button type="button" class="mv-close" onclick="tutupModalValidasi()">×</button>
        </div>

        <div class="mv-info-row">
            <div class="mv-info-left">
                <div class="mv-nama" id="mvNama">-</div>
                <div class="mv-nim">NIM: <span id="mvNim">-</span></div>
                <span class="mv-badge-ke">📅 Bimbingan Ke- <span id="mvKe">-</span></span>
            </div>
            <div class="mv-info-right">
                <div class="mv-tgl-label">Tanggal Bimbingan</div>
                <div class="mv-tgl-value">
                    <i class="fa-regular fa-calendar"></i>
                    <span id="mvTanggal">-</span>
                </div>
            </div>
        </div>

        <div class="mv-section">
            <div class="mv-section-label">Judul Tugas Akhir</div>
            <div class="mv-judul-value" id="mvJudul">-</div>
        </div>

        <div class="mv-section">
            <div class="mv-section-label">💬 Topik Bimbingan</div>
            <div class="mv-topik-box" id="mvTopik">-</div>
        </div>

        <div class="mv-section">
            <div class="mv-section-label">🖼️ Dokumentasi</div>
            <div class="mv-col-card">
                <div id="mvDokumentasi"></div>
            </div>
        </div>

        {{-- AREA INI: form validasi ATAU tampilan hasil (diisi oleh JS) --}}
        <div id="areaValidasi"></div>

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
    let formActionUrl = '';

    // ===== MODAL FOTO =====
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
        const row = el.tagName === 'TR' ? el : el.closest('tr');

        document.getElementById('mvNama').textContent    = row.dataset.nama;
        document.getElementById('mvNim').textContent     = row.dataset.nim;
        document.getElementById('mvKe').textContent      = row.dataset.ke;
        document.getElementById('mvTanggal').textContent = row.dataset.tanggal;
        document.getElementById('mvJudul').textContent   = row.dataset.judul;
        document.getElementById('mvTopik').textContent   = '"' + row.dataset.topikFull + '"';

        document.getElementById('mvDokumentasi').innerHTML = decodeHTMLEntities(row.dataset.dokumentasiHtml);

        const statusAwal   = row.dataset.statusValidasi;
        const sudahFinal   = statusAwal === 'Valid' || statusAwal === 'Tidak Valid';
        const areaValidasi = document.getElementById('areaValidasi');

        if (sudahFinal) {
            // ── Sudah divalidasi (oleh dosen ATAU admin): tampilkan hasil saja, tidak bisa diedit ──
            const isValid     = statusAwal === 'Valid';
            const warnaBg     = isValid ? '#F0FDF4' : '#FEF2F2';
            const warnaBorder = isValid ? '#BBF7D0' : '#FECACA';
            const warnaText   = isValid ? '#15803D' : '#991B1B';
            const icon        = isValid ? '✓' : '✕';
            const catatan     = row.dataset.catatan;

            areaValidasi.innerHTML = `
                <div style="border:1.5px solid ${warnaBorder};background:${warnaBg};border-radius:12px;padding:16px 18px;margin-bottom:12px;">
                    <div style="font-size:10.5px;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:.4px;margin-bottom:8px;">Hasil Validasi</div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="font-size:20px;">${icon}</span>
                        <span style="font-size:15px;font-weight:800;color:${warnaText};">${statusAwal}</span>
                    </div>
                    ${catatan ? `<div style="margin-top:10px;font-size:12.5px;color:#6B7280;line-height:1.5;border-top:1px solid ${warnaBorder};padding-top:10px;"><strong>Catatan:</strong> ${catatan}</div>` : ''}
                </div>
                <div style="font-size:11.5px;color:#9CA3AF;text-align:right;">Validasi sudah dikirim dan tidak dapat diubah.</div>
            `;
        } else {
            // ── Belum divalidasi: tampilkan form ──
            formActionUrl = '/admin/bimbingan/validasi/' + row.dataset.id;
            selectedStatusValidasi = null;

            areaValidasi.innerHTML = `
                <div class="mv-action-row">
                    <button type="button" class="mv-btn-tv" id="btnTidakValid" onclick="setStatusValidasi('Tidak Valid')">
                        ✕ Tidak Valid
                    </button>
                    <button type="button" class="mv-btn-v" id="btnValid" onclick="setStatusValidasi('Valid')">
                        ✓ Valid
                    </button>
                </div>
                <div class="mv-catatan-wrap" id="catatanWrap">
                    <label class="mv-catatan-label">Catatan Admin</label>
                    <textarea class="mv-catatan-textarea" id="catatanDosenInput"
                        placeholder="Tambahkan catatan hasil validasi riwayat bimbingan..."></textarea>
                </div>
                <div class="mv-kirim-row">
                    <button type="button" class="mv-btn-kirim" onclick="submitValidasi()">➤ Kirim</button>
                </div>
            `;
        }

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

        const btnValid      = document.getElementById('btnValid');
        const btnTidakValid = document.getElementById('btnTidakValid');
        const catatanWrap   = document.getElementById('catatanWrap');

        if (btnValid)      btnValid.classList.toggle('active', status === 'Valid');
        if (btnTidakValid) btnTidakValid.classList.toggle('active', status === 'Tidak Valid');
        if (catatanWrap)   catatanWrap.style.display = (status === 'Tidak Valid') ? 'block' : 'none';
    }

    function submitValidasi() {
        if (!selectedStatusValidasi) {
            alert('Silakan pilih status "Valid" atau "Tidak Valid" terlebih dahulu.');
            return;
        }

        const catatan = document.getElementById('catatanDosenInput') ? document.getElementById('catatanDosenInput').value.trim() : '';

        if (selectedStatusValidasi === 'Tidak Valid' && !catatan) {
            alert('Catatan wajib diisi jika status "Tidak Valid".');
            return;
        }

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
        const catatan = document.getElementById('catatanDosenInput') ? document.getElementById('catatanDosenInput').value.trim() : '';

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = formActionUrl;

        const tokenInput = document.createElement('input');
        tokenInput.type  = 'hidden';
        tokenInput.name  = '_token';
        tokenInput.value = '{{ csrf_token() }}';
        form.appendChild(tokenInput);

        const statusInput = document.createElement('input');
        statusInput.type  = 'hidden';
        statusInput.name  = 'status_validasi';
        statusInput.value = selectedStatusValidasi;
        form.appendChild(statusInput);

        const catatanInput = document.createElement('input');
        catatanInput.type  = 'hidden';
        catatanInput.name  = 'catatan_dosen';
        catatanInput.value = selectedStatusValidasi === 'Tidak Valid' ? catatan : '';
        form.appendChild(catatanInput);

        document.body.appendChild(form);
        form.submit();
    }
</script>

@endsection