@extends('layouts.app')

@section('title', 'Detail Bimbingan - ' . ($dosen->nama ?? '-'))

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

    body {
        background: var(--bg);
    }

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

    .btn-back:hover {
        border-color: var(--gold);
        color: var(--gold);
    }

    /* INFO CARD */
    .info-card {
        background: var(--white);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
        padding: 22px 26px;
        margin-bottom: 20px;
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

    /* TABEL CARD */
    .tabel-card {
        background: var(--white);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
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

    .search-wrap {
        position: relative;
    }

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
        width: 220px;
    }

    .search-sm:focus {
        border-color: var(--gold);
        background: #fff;
    }

    .search-sm::placeholder {
        color: #9CA3AF;
    }

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
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .btn-reset-sm:hover {
        border-color: var(--gold);
        color: var(--gold);
    }

    /* TABLE */
    .tabel-scroll {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 550px;
    }

    thead tr {
        background: #F8FAFC;
    }

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

    tbody tr {
        border-bottom: 1px solid #F3F4F6;
        transition: background .15s;
    }

    tbody tr:last-child {
        border-bottom: none;
    }

    tbody tr:hover {
        background: #FFFDF5;
    }

    tbody td {
        padding: 14px 16px;
        font-size: 13px;
        color: var(--neutral);
        vertical-align: middle;
    }

    .mhs-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .avatar {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: var(--gold-lt);
        border: 1.5px solid var(--gold-border);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
        color: var(--gold);
        flex-shrink: 0;
    }

    .mhs-nama {
        font-weight: 700;
        color: var(--neutral);
    }

    .jumlah-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--gold-lt);
        border: 1.5px solid var(--gold-border);
        color: var(--gold);
        border-radius: 8px;
        padding: 4px 14px;
        font-size: 14px;
        font-weight: 800;
    }

    .btn-lihat {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 16px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 700;
        background: #fff;
        color: var(--neutral);
        border: 1.5px solid var(--border);
        text-decoration: none;
        transition: .2s;
    }

    .btn-lihat:hover {
        border-color: var(--gold);
        color: var(--gold);
        background: var(--gold-lt);
    }

    .empty-row td {
        text-align: center;
        padding: 60px;
        color: var(--muted);
        font-size: 14px;
    }
</style>

{{-- BACK --}}
<a href="{{ route('admin.bimbingan.index') }}?tab=mahasiswa" class="btn-back">
    <i class="fa-solid fa-arrow-left" style="font-size:12px;"></i>
    Kembali
</a>

{{-- INFO DOSEN --}}
<div class="info-card">
    <div class="info-avatar">{{ strtoupper(substr($dosen->nama ?? 'D', 0, 1)) }}</div>
    <div>
        <div class="info-label">Informasi Dosen Pembimbing</div>
        <div class="info-nama">{{ $dosen->nama ?? '-' }}</div>
        <div class="info-nim">NIDN: {{ $dosen->nim_nid ?? '-' }}</div>
    </div>
</div>

{{-- TABEL MAHASISWA --}}
<div class="tabel-card">
    <div class="tabel-header">
        <div class="tabel-title">
            Daftar Mahasiswa Bimbingan
            <span style="font-size:13px; font-weight:500; color:var(--muted); margin-left:8px;">
                ({{ $mahasiswaList->count() }} mahasiswa)
            </span>
        </div>
        <div style="display:flex; gap:8px; align-items:center;">
            <div class="search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" class="search-sm" id="searchMhs"
                    placeholder="Cari nama atau NIM..."
                    oninput="filterMhs()">
            </div>
            <button class="btn-reset-sm" onclick="resetMhs()">
                <i class="fa-solid fa-rotate-right" style="font-size:11px;"></i>
                Reset
            </button>
        </div>
    </div>

    <div class="tabel-scroll">
        <table id="tabelMhs">
            <thead>
                <tr>
                    <th style="width:46px;">No.</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Angkatan</th>
                    <th>Jumlah Bimbingan</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswaList as $i => $m)
                <tr data-nama="{{ strtolower($m->nama_mahasiswa) }}" data-nim="{{ $m->nim_nid }}">

                    <td style="color:#94a3b8; font-weight:600;">{{ $i + 1 }}.</td>

                    <td style="font-size:12.5px; font-weight:600; color:var(--muted); white-space:nowrap;">
                        {{ $m->nim_nid }}
                    </td>

                    <td>
                        <div class="mhs-info">
                            <div class="avatar">{{ strtoupper(substr($m->nama_mahasiswa, 0, 1)) }}</div>
                            <span class="mhs-nama">{{ $m->nama_mahasiswa }}</span>
                        </div>
                    </td>

                    <td style="font-size:13px; font-weight:600;">
                        {{ $m->angkatan ?? '-' }}
                    </td>

                    <td>
                        <span class="jumlah-chip">{{ $m->total_bimbingan }}</span>
                    </td>

                    <td style="text-align:right;">
                        <a href="{{ route('admin.bimbingan.detail', [$m->nim_nid, $dosen->nim_nid]) }}" class="btn-lihat">
                            Lihat Riwayat
                            <i class="fa-solid fa-arrow-right" style="font-size:11px;"></i>
                        </a>
                    </td>

                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="6">
                        <i class="fa-solid fa-user-graduate" style="font-size:36px; display:block; margin-bottom:10px; color:#d1d5db;"></i>
                        Belum ada mahasiswa bimbingan untuk dosen ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function filterMhs() {
        const q = document.getElementById('searchMhs').value.toLowerCase();
        document.querySelectorAll('#tabelMhs tbody tr:not(.empty-row)').forEach(row => {
            const match = !q || row.dataset.nama.includes(q) || row.dataset.nim.includes(q);
            row.style.display = match ? '' : 'none';
        });
    }

    function resetMhs() {
        document.getElementById('searchMhs').value = '';
        filterMhs();
    }
</script>

@endsection