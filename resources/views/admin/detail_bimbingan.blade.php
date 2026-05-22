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

.badge-layak.yes { background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; }
.badge-layak.no  { background: #FEF9EC; color: #B45309; border: 1px solid var(--gold-border); }

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
    background: var(--gold);
    transition: width .6s ease;
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
    color: var(--gold);
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
    min-width: 650px;
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
    z-index: 2000;
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

@media (max-width: 768px) {
    .info-row { grid-template-columns: 1fr; }
}
</style>

{{-- BACK --}}
<a href="{{ route('admin.bimbingan.index') }}" class="btn-back">
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
<div class="kelayakan-card">
    <div class="kelayakan-top">
        <div class="kelayakan-title">Status Kelayakan Seminar</div>
        @if($totalBimbingan >= $minBimbingan)
            <span class="badge-layak yes">
                <i class="fa-solid fa-circle-check" style="font-size:11px;"></i>
                Layak Seminar
            </span>
        @else
            <span class="badge-layak no">
                <i class="fa-regular fa-clock" style="font-size:11px;"></i>
                Belum Memenuhi
            </span>
        @endif
    </div>
    <div class="kelayakan-desc">
        Minimal {{ $minBimbingan }} kali bimbingan sebagai persyaratan seminar tugas akhir.
    </div>
    <div class="progress-wrap">
        <div class="progress-bar" style="width: {{ min(100, ($totalBimbingan / $minBimbingan) * 100) }}%"></div>
    </div>
    <div class="progress-info">
        <span>{{ $totalBimbingan }} / {{ $minBimbingan }} Bimbingan</span>
        <span class="progress-pct">{{ round(min(100, ($totalBimbingan / $minBimbingan) * 100)) }}%</span>
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
                </tr>
            </thead>
            <tbody>
                @forelse($bimbingan as $i => $b)
                <tr data-topik="{{ strtolower($b->topik_bimbingan) }}" data-status="{{ $b->status ?? '' }}">

                    <td style="color:#94a3b8; font-weight:600;">{{ $i + 1 }}</td>

                    <td style="white-space:nowrap; font-size:12.5px; color:var(--muted);">
                        {{ \Carbon\Carbon::parse($b->tanggal_bimbingan)->translatedFormat('d M Y') }}
                    </td>

                    <td>
                        <span class="badge-ke">{{ $b->pertemuan_ke }}</span>
                    </td>

                    <td style="max-width:160px; font-size:12px; line-height:1.4;">
                        {{ Str::limit($judulTA, 40) }}
                    </td>

                    <td style="max-width:200px; font-size:12.5px; line-height:1.4;">
                        {{ $b->topik_bimbingan }}
                    </td>

                    <td>
                        @if($b->dokumentasi)
                            <img src="{{ asset('uploads/bimbingan/' . $b->dokumentasi) }}"
                                 class="thumb"
                                 alt="Dokumentasi"
                                 onclick="lihatFoto('{{ asset('uploads/bimbingan/' . $b->dokumentasi) }}')">
                        @else
                            <span style="color:#9CA3AF; font-size:12px;">—</span>
                        @endif
                    </td>

                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="6">
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

<script>
function lihatFoto(src) {
    document.getElementById('fotoImg').src = src;
    document.getElementById('modalFoto').classList.add('show');
}

function tutupFoto(e) {
    if (e.target === document.getElementById('modalFoto'))
        document.getElementById('modalFoto').classList.remove('show');
}

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
</script>

@endsection