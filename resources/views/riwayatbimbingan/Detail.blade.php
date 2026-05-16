@extends('layouts.app')

@section('content')

<style>
    .riwayat-hero {
        background: #ffffff;
        border-radius: 16px;
        padding: 36px 40px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        min-height: 160px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .riwayat-hero::before {
        content: '';
        position: absolute;
        top: 10px; left: 10px;
        width: 80px; height: 80px;
        background-image: radial-gradient(circle, #f0d080 1.5px, transparent 1.5px);
        background-size: 10px 10px;
        opacity: 0.5;
        pointer-events: none;
    }
    .riwayat-hero::after {
        content: '';
        position: absolute;
        right: 0; top: 0; bottom: 0;
        width: 42%;
        background:
            linear-gradient(135deg, transparent 55%, #f59e0b 55%),
            radial-gradient(ellipse 180px 260px at 85% 50%, #fef3c7 0%, transparent 70%);
        border-radius: 0 16px 16px 0;
        pointer-events: none;
    }
    .hero-deco {
        position: absolute;
        right: 0; top: 0; bottom: 0;
        width: 42%;
        pointer-events: none;
        border-radius: 0 16px 16px 0;
        overflow: hidden;
    }
    .hero-deco svg {
        width: 100%; height: 100%;
        position: absolute; top: 0; left: 0;
    }
    .riwayat-hero h1 {
        font-size: 1.75rem; font-weight: 800;
        color: #111827; margin-bottom: 4px;
        position: relative; z-index: 1;
    }
    .riwayat-hero p {
        color: #6b7280; font-size: 0.875rem;
        margin: 0; position: relative; z-index: 1;
    }

    .btn-back {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 14px; border-radius: 8px;
        font-size: 0.82rem; font-weight: 600;
        background: #fff; color: #374151;
        border: 1.5px solid #d1d5db;
        text-decoration: none; transition: all .2s;
        margin-bottom: 16px;
    }
    .btn-back:hover { background: #f3f4f6; color: #111827; }

    .info-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 1px 6px rgba(0,0,0,.08);
        padding: 24px 28px;
        margin-bottom: 16px;
        display: flex;
        align-items: flex-start;
        gap: 24px;
        flex-wrap: wrap;
    }
    .info-card-left { flex: 1; min-width: 200px; }
    .info-card-left .label {
        font-size: 0.72rem; font-weight: 700;
        color: #9ca3af; text-transform: uppercase;
        letter-spacing: .5px; margin-bottom: 4px;
    }
    .info-card-left h2 {
        font-size: 1.4rem; font-weight: 800;
        color: #111827; margin: 0 0 4px;
    }
    .info-card-left .nim {
        font-size: 0.85rem; color: #6b7280; font-weight: 500;
    }
    .info-card-left .judul-label {
        font-size: 0.72rem; font-weight: 700;
        color: #9ca3af; text-transform: uppercase;
        letter-spacing: .5px; margin-top: 12px; margin-bottom: 3px;
    }
    .info-card-left .judul-text {
        font-size: 0.85rem; color: #374151;
        font-style: italic; line-height: 1.4;
    }

    .info-card-stats {
        display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-start;
    }
    .stat-box {
        background: #f9fafb;
        border-radius: 10px;
        padding: 14px 20px;
        text-align: center;
        min-width: 90px;
        border: 1.5px solid #f3f4f6;
    }
    .stat-box .stat-val {
        font-size: 1.6rem; font-weight: 800; color: #111827; line-height: 1;
    }
    .stat-box .stat-lbl {
        font-size: 0.68rem; color: #9ca3af; font-weight: 600;
        text-transform: uppercase; letter-spacing: .4px; margin-top: 4px;
    }

    .kelayakan-box {
        background: #f9fafb;
        border-radius: 10px;
        padding: 14px 20px;
        min-width: 200px;
        border: 1.5px solid #f3f4f6;
    }
    .kelayakan-box .kel-header {
        display: flex; align-items: center;
        justify-content: space-between; margin-bottom: 8px;
    }
    .kelayakan-box .kel-title {
        font-size: 0.72rem; font-weight: 700;
        color: #9ca3af; text-transform: uppercase; letter-spacing: .4px;
    }
    .badge-layak { background: #dcfce7; color: #166534; padding: 3px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; }
    .badge-belum { background: #fee2e2; color: #991b1b; padding: 3px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; }
    .kel-progress-wrap {
        background: #e5e7eb; border-radius: 99px; height: 8px; overflow: hidden;
    }
    .kel-progress-bar {
        height: 100%; border-radius: 99px;
        background: linear-gradient(90deg, #f59e0b, #fbbf24);
        transition: width .5s ease;
    }
    .kel-count {
        font-size: 0.78rem; color: #374151; font-weight: 600; margin-top: 6px;
    }

    .filter-bar {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }
    .filter-group {
        display: flex; flex-direction: column; gap: 4px;
        flex: 1; min-width: 160px;
    }
    .filter-group label {
        font-size: 0.78rem; font-weight: 600; color: #374151;
    }
    .filter-input-wrap {
        position: relative; display: flex; align-items: center;
    }
    .filter-input-wrap .search-icon {
        position: absolute; left: 10px; color: #9ca3af;
        font-size: 0.85rem; pointer-events: none; z-index: 1;
    }
    .filter-group input {
        width: 100%;
        border: 1.5px solid #e5e7eb; border-radius: 8px;
        padding: 9px 12px; font-size: 0.85rem; outline: none;
        background: #fff; color: #374151; transition: border-color .2s;
    }
    .filter-group input.with-icon { padding-left: 34px; }
    .filter-group input::placeholder { color: #c4c4c4; }
    .filter-group input:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245,158,11,.1);
    }
    .btn-reset {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 16px; border-radius: 8px;
        font-size: 0.82rem; font-weight: 600;
        background: #fff; color: #374151;
        border: 1.5px solid #d1d5db;
        cursor: pointer; white-space: nowrap;
        transition: all .2s; align-self: flex-end;
    }
    .btn-reset:hover { background: #f3f4f6; }

    .table-card {
        background: #fff; border-radius: 14px;
        box-shadow: 0 1px 6px rgba(0,0,0,.08); overflow: hidden;
    }
    .table-card table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    .table-card thead tr { background: #f5f5f5; border-bottom: 1px solid #eeeeee; }
    .table-card thead th {
        padding: 11px 14px; font-size: 0.7rem; font-weight: 700;
        color: #9ca3af; text-transform: uppercase; letter-spacing: .5px;
        white-space: nowrap; text-align: left; border: none;
    }
    .table-card thead th:first-child { text-align: center; }
    .table-card tbody tr { border-bottom: 1px solid #f3f4f6; transition: background .15s; }
    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody tr:hover { background: #fffbeb; }
    .table-card tbody td { padding: 14px 14px; color: #374151; vertical-align: middle; border: none; }

    .td-no      { text-align: center; font-weight: 600; color: #9ca3af; font-size: 0.82rem; width: 44px; }
    .td-tanggal { white-space: nowrap; color: #6b7280; font-size: 0.82rem; }
    .td-ke      { text-align: center; font-weight: 700; color: #374151; width: 60px; }
    .td-judul   {
        max-width: 200px; color: #374151; font-size: 0.85rem;
        display: -webkit-box; -webkit-line-clamp: 2;
        -webkit-box-orient: vertical; overflow: hidden;
    }
    .td-topik   {
        max-width: 220px; color: #374151; font-size: 0.85rem;
        display: -webkit-box; -webkit-line-clamp: 2;
        -webkit-box-orient: vertical; overflow: hidden;
    }

    .file-chip {
        display: inline-flex; flex-direction: column; gap: 1px;
        background: #fff1f2; border: 1px solid #fecdd3; border-radius: 6px;
        padding: 5px 9px; text-decoration: none; transition: background .2s;
        max-width: 150px;
    }
    .file-chip:hover { background: #ffe4e6; }
    .file-chip-name {
        display: flex; align-items: center; gap: 4px;
        font-size: 0.78rem; font-weight: 600; color: #be123c;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .file-chip-sub {
        font-size: 0.68rem; color: #9ca3af; padding-left: 2px;
    }
    .file-null { color: #d1d5db; font-style: italic; font-size: 0.8rem; }

    .empty-state { text-align: center; padding: 60px 20px; color: #9ca3af; }
    .empty-state .icon { font-size: 2.8rem; margin-bottom: 12px; }
    .empty-state p { margin: 0; font-size: 0.9rem; }
</style>

{{-- ═══════════ HERO ═══════════ --}}
<div class="riwayat-hero">
    <div class="hero-deco" aria-hidden="true">
        <svg viewBox="0 0 400 200" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
            <rect x="260" y="90" width="160" height="130" rx="12" fill="#f59e0b"/>
            <path d="M 80 -20 Q 200 100 80 220"  stroke="#fde68a" stroke-width="38" fill="none" opacity="0.6"/>
            <path d="M 160 -30 Q 310 100 160 230" stroke="#fef3c7" stroke-width="50" fill="none" opacity="0.7"/>
            <path d="M 240 -10 Q 360 100 240 210" stroke="#fde68a" stroke-width="22" fill="none" opacity="0.5"/>
        </svg>
    </div>
    <h1>Detail Riwayat Bimbingan</h1>
    <p>Lihat seluruh riwayat bimbingan mahasiswa</p>
</div>

{{-- ═══════════ BACK ═══════════ --}}
<a href="{{ route('dosen.riwayat-bimbingan.mahasiswa') }}" class="btn-back">
    ← Kembali ke Daftar Mahasiswa
</a>

{{-- ═══════════ INFO CARD ═══════════ --}}
@php
    $minBimbingan = 6;
    $persen       = min(100, round(($totalPertemuan / $minBimbingan) * 100));
    $layak        = $totalPertemuan >= $minBimbingan;
@endphp

<div class="info-card">
    <div class="info-card-left">
        <div class="label">Informasi Mahasiswa</div>
        <h2>{{ $mahasiswa->nama ?? '—' }}</h2>
        <div class="nim">NIM: {{ $mahasiswa->nim ?? '—' }}</div>
        <div class="judul-label">Judul Proposal</div>
        <div class="judul-text">{{ $mahasiswa->judul ?? '—' }}</div>
    </div>

    <div class="info-card-stats">
        <div class="stat-box">
            <div class="stat-val">{{ $totalPertemuan }}</div>
            <div class="stat-lbl">Total<br>Bimbingan</div>
        </div>

        <div class="kelayakan-box">
            <div class="kel-header">
                <div class="kel-title">Status Kelayakan</div>
                @if($layak)
                    <span class="badge-layak">Layak Seminar</span>
                @else
                    <span class="badge-belum">Belum Memenuhi</span>
                @endif
            </div>
            <div class="kel-progress-wrap">
                <div class="kel-progress-bar" style="width: {{ $persen }}%"></div>
            </div>
            <div class="kel-count">{{ $totalPertemuan }} / {{ $minBimbingan }} Bimbingan &nbsp;·&nbsp; {{ $persen }}%</div>
        </div>
    </div>
</div>

{{-- ═══════════ FILTER ═══════════ --}}
<div class="filter-bar">
    <div class="filter-group" style="flex:2; min-width:220px">
        <label>Cari Topik</label>
        <div class="filter-input-wrap">
            <span class="search-icon">🔍</span>
            <input type="text" id="searchInput" class="with-icon"
                   placeholder="Cari topik bimbingan...">
        </div>
    </div>
    <button id="resetFilter" class="btn-reset">🔄 Reset</button>
</div>

{{-- ═══════════ TABLE ═══════════ --}}
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>NO.</th>
                <th>TANGGAL</th>
                <th>KE-</th>
                <th>JUDUL PROPOSAL</th>
                <th>TOPIK BIMBINGAN</th>
                <th>DOKUMENTASI</th>
            </tr>
        </thead>
        <tbody id="tabelBody">
            @forelse($bimbinganList as $i => $row)
            <tr data-search="{{ strtolower(trim($row->topik_bimbingan ?? '')) }}">
                <td class="td-no">{{ $i + 1 }}</td>

                <td class="td-tanggal">
                    {{ $row->tanggal_bimbingan
                        ? \Carbon\Carbon::parse($row->tanggal_bimbingan)->translatedFormat('d M Y')
                        : '—' }}
                </td>

                <td class="td-ke">{{ $row->pertemuan_ke ?? '—' }}</td>

                <td>
                    <div class="td-judul" title="{{ $mahasiswa->judul }}">
                        {{ $mahasiswa->judul ?? '—' }}
                    </div>
                </td>

                <td>
                    <div class="td-topik" title="{{ $row->topik_bimbingan }}">
                        {{ $row->topik_bimbingan ?? '—' }}
                    </div>
                </td>

                <td>
                    @if (!empty($row->dokumentasi))
                        <a href="{{ asset('storage/' . $row->dokumentasi) }}"
                           class="file-chip" target="_blank">
                            <span class="file-chip-name">
                                📄 {{ basename($row->dokumentasi) }}
                            </span>
                            <span class="file-chip-sub">👁 Preview</span>
                        </a>
                    @else
                        <span class="file-null">— Belum ada —</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <div class="icon">📭</div>
                        <p>Belum ada riwayat bimbingan.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div id="pesanKosong" class="empty-state d-none">
        <div class="icon">📭</div>
        <p>Data tidak ditemukan.</p>
    </div>
</div>

<script>
const searchInputEl = document.getElementById('searchInput');
searchInputEl.addEventListener('input', filterTabel);

document.getElementById('resetFilter').addEventListener('click', function () {
    searchInputEl.value = '';
    filterTabel();
});

function filterTabel() {
    const search = searchInputEl.value.toLowerCase().trim();
    const rows   = document.querySelectorAll('#tabelBody tr[data-search]');
    let adaData  = false;

    rows.forEach(row => {
        const cocok = search === '' || row.getAttribute('data-search').includes(search);
        row.style.display = cocok ? '' : 'none';
        if (cocok) adaData = true;
    });

    document.getElementById('pesanKosong').classList.toggle('d-none', adaData);
}
</script>

@endsection