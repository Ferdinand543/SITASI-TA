@extends('layouts.app')

@section('title', 'Kelola Jadwal Seminar TA-1')

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

    .seminar-wrap { background: var(--bg); min-height: 100vh; overflow-x: hidden; }

    .seminar-hero {
        background-image: url('{{ asset("images/bg.jpeg") }}');
        background-size: cover; background-position: center;
        border-radius: 20px; padding: 36px 40px; margin-bottom: 24px;
        position: relative; overflow: hidden;
        width: 100%; box-sizing: border-box;
    }
    .seminar-hero::before {
        content: ''; position: absolute; right: -40px; top: -40px;
        width: 220px; height: 220px; background: rgba(201,162,39,.12); border-radius: 50%;
    }
    .seminar-hero-title { font-size: 26px; font-weight: 800; color: #735C00; margin-bottom: 6px; position: relative; }
    .seminar-hero-sub { font-size: 13px; color: #92400E; position: relative; max-width: 500px; line-height: 1.6; }

    .stat-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px; min-width: 0; }
    .stat-card {
        background: var(--white); border-radius: var(--radius); padding: 20px 22px;
        border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(0,0,0,.05);
        min-width: 0;
    }
    .stat-label { font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
    .stat-num { font-size: 26px; font-weight: 900; color: var(--neutral); line-height: 1; margin-bottom: 4px; }
    .stat-desc { font-size: 12px; color: var(--muted); }

    .alert-banner {
        background: var(--gold-lt); border: 1px solid var(--gold-border);
        border-radius: 12px; padding: 14px 18px; margin-bottom: 20px;
        display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
    }
    .alert-banner-text { font-size: 13px; color: #92400E; display: flex; align-items: center; gap: 10px; }
    .alert-banner-btn {
        padding: 9px 18px; background: #FDE047; color: #713F12;
        border: 1px solid #FACC15;
        border-radius: 10px; font-size: 13px; font-weight: 700;
        text-decoration: none; white-space: nowrap; transition: background .2s;
    }
    .alert-banner-btn:hover { background: #FACC15; color: #713F12; }

    .filter-card {
        background: var(--white); border-radius: var(--radius); padding: 14px 18px;
        border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(0,0,0,.05);
        margin-bottom: 16px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    }
    .filter-search {
        flex: 1; min-width: 200px; padding: 9px 12px 9px 36px;
        border: 1.5px solid var(--border); border-radius: 8px; font-size: 13px;
        outline: none; font-family: inherit; transition: border .2s;
        background: #FAFAFA url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='none' viewBox='0 0 24 24' stroke='%236B7280' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 10px center;
    }
    .filter-search:focus { border-color: var(--gold); background-color: #fff; }
    .filter-select {
        padding: 9px 12px; border: 1.5px solid var(--border); border-radius: 8px;
        font-size: 13px; outline: none; background: #FAFAFA; font-family: inherit; cursor: pointer;
    }
    .filter-select:focus { border-color: var(--gold); }

    .tabel-card { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(0,0,0,.05); overflow: hidden; min-width: 0; width: 100%; }
    .tabel-scroll { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 700px; }
    thead th { padding: 11px 14px; font-size: 11px; font-weight: 700; color: var(--muted); text-align: left; background: #FAFAFA; border-bottom: 1px solid var(--border); white-space: nowrap; letter-spacing: .3px; text-transform: uppercase; }
    tbody tr { border-bottom: 1px solid #F3F4F6; transition: background .15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #FAFBFF; }
    tbody td { padding: 14px; font-size: 13px; color: var(--neutral); vertical-align: middle; }

    .badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 99px; font-size: 11px; font-weight: 700; white-space: nowrap; }
    .badge-menunggu { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }
    .badge-dijadwalkan { background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; }
    .badge-berlangsung { background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; }
    .badge-selesai { background: #F3F4F6; color: #374151; border: 1px solid #E5E7EB; }

    .btn-atur {
        padding: 6px 12px; background: var(--gold-lt); color: #92400E;
        border: 1px solid var(--gold-border); border-radius: 8px;
        font-size: 12px; font-weight: 700; cursor: pointer; font-family: inherit;
        transition: all .2s; white-space: nowrap; text-decoration: none;
        display: inline-flex; align-items: center;
    }
    .btn-atur:hover { background: var(--gold); color: #fff; border-color: var(--gold); }
    .btn-icon {
        width: 30px; height: 30px; border-radius: 8px; border: 1px solid var(--border);
        background: #fff; cursor: pointer; display: inline-flex; align-items: center;
        justify-content: center; transition: all .2s; color: var(--muted);
        text-decoration: none;
    }
    .btn-icon:hover { border-color: #f87171; color: #ef4444; background: #fef2f2; }
    .btn-icon.edit { color: var(--muted); }
    .btn-icon.edit:hover { border-color: var(--gold); color: var(--gold); background: var(--gold-lt); }

    .dosen-list { display: flex; flex-direction: column; gap: 3px; }
    .dosen-item { font-size: 12px; color: var(--neutral); display: flex; align-items: flex-start; gap: 4px; }
    .dosen-urutan {
        font-size: 10px; font-weight: 700; color: var(--gold);
        background: var(--gold-lt); border: 1px solid var(--gold-border);
        border-radius: 4px; padding: 1px 5px; flex-shrink: 0; margin-top: 1px;
    }

    .empty-state { text-align: center; padding: 60px 20px; color: var(--muted); }
    .empty-state-icon { font-size: 40px; margin-bottom: 10px; }
    .empty-state-text { font-size: 14px; font-weight: 600; color: var(--neutral); margin-bottom: 4px; }
    .empty-state-sub { font-size: 12px; }

    .btn-back {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 16px; background: #fff; border: 1.5px solid var(--border);
        border-radius: 10px; font-size: 13px; font-weight: 600; color: var(--neutral);
        text-decoration: none; transition: all .2s; margin-bottom: 20px;
    }
    .btn-back:hover { border-color: var(--gold); color: var(--gold); }

    .alert-success {
        background: #F0FDF4; border: 1px solid #BBF7D0; color: #15803D;
        border-radius: 10px; padding: 12px 16px; margin-bottom: 16px;
        font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;
    }

    /* ===================== POPUP ===================== */
    .popup-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.45); z-index: 9999;
        align-items: center; justify-content: center;
    }
    .popup-overlay.active { display: flex; }
    .popup-box {
        background: #fff; border-radius: 20px; padding: 40px 32px;
        width: 100%; max-width: 360px; text-align: center;
        box-shadow: 0 20px 60px rgba(0,0,0,.18); animation: popIn .2s ease;
    }
    @keyframes popIn {
        from { transform: scale(.85); opacity: 0; }
        to   { transform: scale(1);   opacity: 1; }
    }

    .popup-icon {
        width: 80px; height: 80px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 24px;
    }
    .popup-icon.berhasil  { background: #fff;     border: 2.5px solid #22C55E; }
    .popup-icon.konfirmasi { background: #FEF9EC; border: 3px solid #F5D97A; }
    .popup-icon.gagal     { background: #fff;     border: 2.5px solid #EF4444; }

    .popup-title { font-size: 22px; font-weight: 800; color: var(--neutral); margin-bottom: 10px; }
    .popup-msg   { font-size: 13.5px; color: var(--muted); margin-bottom: 28px; line-height: 1.6; }
    .popup-actions { display: flex; gap: 10px; justify-content: center; }
    .popup-btn {
        padding: 11px 32px; border-radius: 10px; font-size: 14px; font-weight: 700;
        cursor: pointer; font-family: inherit; border: none; transition: all .2s;
    }
    .popup-btn.ok     { background: var(--gold); color: #fff; min-width: 120px; }
    .popup-btn.ok:hover { background: #b8911f; }
    .popup-btn.batal  { background: #E5E7EB; color: #374151; }
    .popup-btn.batal:hover { background: #D1D5DB; }
    .popup-btn.konfirm { background: var(--gold); color: #fff; }
    .popup-btn.konfirm:hover { background: #b8911f; }

    @media (max-width: 900px) { .stat-row { grid-template-columns: 1fr; } }
</style>

<div class="seminar-wrap">

    <div class="seminar-hero">
        <div class="seminar-hero-title">Kelola Jadwal Seminar TA-1</div>
        <div class="seminar-hero-sub">Kelola penjadwalan seminar mahasiswa yang telah menyelesaikan proses administrasi dan siap mengikuti seminar tugas akhir.</div>
    </div>

    @if(session('success'))
    <div class="alert-success">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-label">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                Waiting
            </div>
            <div class="stat-num">{{ $totalMenunggu }}</div>
            <div class="stat-desc">Menunggu Penjadwalan</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                </svg>
                Scheduled
            </div>
            <div class="stat-num">{{ $totalDijadwalkan }}</div>
            <div class="stat-desc">Sudah Dijadwalkan</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5"/>
                </svg>
                Ongoing
            </div>
            <div class="stat-num">{{ $totalHariIni }}</div>
            <div class="stat-desc">Seminar Hari Ini</div>
        </div>
    </div>

    @if($totalMenunggu > 0)
    <div class="alert-banner">
        <div class="alert-banner-text">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#C9A227" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
            </svg>
            Terdapat <strong>{{ $totalMenunggu }} mahasiswa</strong> yang belum memiliki jadwal seminar. Gunakan fitur Jadwal Seminar Massal untuk mempercepat proses penjadwalan.
        </div>
        <a href="{{ route('jadwalseminar.massal.form') }}" class="alert-banner-btn">+ Tetapkan Jadwal Seminar</a>
    </div>
    @endif

    <div class="filter-card">
        <input type="text" id="filterSearch" class="filter-search"
            placeholder="Search by NIM, Name, or Thesis Title..."
            value="{{ request('search') }}">
        <select id="filterStatus" class="filter-select">
            <option value="Semua Status" {{ request('status', 'Semua Status') === 'Semua Status' ? 'selected' : '' }}>Semua Status</option>
            <option value="Menunggu Jadwal" {{ request('status') === 'Menunggu Jadwal' ? 'selected' : '' }}>Menunggu Jadwal</option>
            <option value="Sudah Dijadwalkan" {{ request('status') === 'Sudah Dijadwalkan' ? 'selected' : '' }}>Sudah Dijadwalkan</option>
            <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
    </div>

    <div class="tabel-card">
        <div class="tabel-scroll">
            <table>
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Angkatan</th>
                        <th>Judul Tugas Akhir</th>
                        <th>Pembimbing</th>
                        <th>Penguji</th>
                        <th>Jadwal Seminar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($seminars as $s)
                    <tr>
                        <td style="font-weight:700;font-size:12.5px;">{{ $s->mahasiswa_id }}</td>
                        <td style="font-weight:600;">{{ $s->nama }}</td>
                        <td style="font-size:12.5px;">{{ $s->angkatan ?? '-' }}</td>
                        <td style="max-width:180px;">
                            <div style="font-size:12.5px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                                {{ $s->judul_ta ?? '-' }}
                            </div>
                        </td>
                        <td>
                            @if(!empty($s->pembimbing))
                                <div class="dosen-list">
                                    @foreach($s->pembimbing as $i => $nama)
                                    <div class="dosen-item">
                                        <span class="dosen-urutan">P{{ $i + 1 }}</span>
                                        {{ $nama }}
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <span style="color:var(--muted);">-</span>
                            @endif
                        </td>
                        <td>
                            @if(!empty($s->penguji))
                                <div class="dosen-list">
                                    @foreach($s->penguji as $i => $nama)
                                    <div class="dosen-item">
                                        <span class="dosen-urutan">P{{ $i + 1 }}</span>
                                        {{ $nama }}
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <span style="color:var(--muted);">Belum Ditentukan</span>
                            @endif
                        </td>
                        <td style="font-size:12px;white-space:nowrap;">
                            @if($s->tanggal_seminar)
                                <div style="font-weight:600;">
                                    {{ \Carbon\Carbon::parse($s->tanggal_seminar)->translatedFormat('d M Y') }}
                                </div>
                                <div style="color:var(--muted);margin-top:2px;">
                                    {{ $s->waktu_mulai ? \Carbon\Carbon::parse($s->waktu_mulai)->format('H:i') : '' }}
                                    @if($s->waktu_selesai) — {{ \Carbon\Carbon::parse($s->waktu_selesai)->format('H:i') }} WIB @endif
                                </div>
                                @if($s->ruang)
                                <div style="color:var(--muted);margin-top:2px;">{{ $s->ruang }}</div>
                                @endif
                            @else
                                <span style="color:var(--muted);">—</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $st = $s->status_seminar ?? 'Menunggu Jadwal';
                                $badgeClass = match($st) {
                                    'Sudah Dijadwalkan' => 'badge-dijadwalkan',
                                    'Berlangsung'       => 'badge-berlangsung',
                                    'Selesai'           => 'badge-selesai',
                                    default             => 'badge-menunggu',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                <svg width="6" height="6" viewBox="0 0 10 10" fill="currentColor"><circle cx="5" cy="5" r="5"/></svg>
                                {{ $st }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:6px;">
                                @if(!$s->tanggal_seminar)
                                    <a href="{{ route('jadwalseminar.detail', $s->id) }}" class="btn-atur">
                                        Atur Jadwal
                                    </a>
                                @else
                                    <a href="{{ route('jadwalseminar.detail', $s->id) }}" class="btn-icon edit" title="Edit Jadwal">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                        </svg>
                                    </a>
                                    <button type="button" class="btn-icon" title="Hapus Jadwal"
                                        onclick="konfirmasiHapus({{ $s->id }}, '{{ addslashes($s->nama) }}')">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="empty-state-icon">📅</div>
                                <div class="empty-state-text">Belum ada mahasiswa</div>
                                <div class="empty-state-sub">Data akan muncul setelah menetapkan penguji</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:20px;">
        <a href="{{ route('jadwal.index') }}" class="btn-back">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            Kembali
        </a>
    </div>

</div>

{{-- ===================== POPUP KONFIRMASI HAPUS ===================== --}}
<div class="popup-overlay" id="popupKonfirmasi">
    <div class="popup-box">
        <div class="popup-icon konfirmasi">
            <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
                <text x="11" y="27" font-size="26" font-weight="900" fill="#EF4444" font-family="Arial">?</text>
            </svg>
        </div>
        <div class="popup-title">Konfirmasi</div>
        <div class="popup-msg" id="popupKonfirmasiMsg">Apakah Anda yakin ingin menghapus jadwal seminar ini?</div>
        <div class="popup-actions">
            <button class="popup-btn batal" onclick="tutupPopup('popupKonfirmasi')">Batal</button>
            <button class="popup-btn konfirm" onclick="eksekusiHapus()">Hapus</button>
        </div>
    </div>
</div>

{{-- ===================== POPUP BERHASIL ===================== --}}
<div class="popup-overlay" id="popupBerhasil">
    <div class="popup-box">
        <div class="popup-icon berhasil">
            <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#22C55E" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div class="popup-title">Berhasil!</div>
        <div class="popup-msg">Jadwal seminar mahasiswa berhasil dihapus.</div>
        <div class="popup-actions">
            <button class="popup-btn ok" onclick="tutupBerhasil()">OK</button>
        </div>
    </div>
</div>

{{-- ===================== POPUP GAGAL ===================== --}}
<div class="popup-overlay" id="popupGagal">
    <div class="popup-box">
        <div class="popup-icon gagal">
            <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#EF4444" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <div class="popup-title">Gagal!</div>
        <div class="popup-msg">Jadwal seminar gagal dihapus. Silakan coba lagi.</div>
        <div class="popup-actions">
            <button class="popup-btn ok" onclick="tutupPopup('popupGagal')">OK</button>
        </div>
    </div>
</div>

{{-- Form hapus tersembunyi --}}
<form id="formHapus" method="POST" style="display:none;">
    @csrf
</form>

@if(session('hapus_berhasil'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('popupBerhasil').classList.add('active');
    });
</script>
@endif

@if(session('hapus_gagal'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('popupGagal').classList.add('active');
    });
</script>
@endif

<script>
    let searchTimer;
    document.getElementById('filterSearch').addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => applyFilter(), 400);
    });
    document.getElementById('filterStatus').addEventListener('change', applyFilter);

    function applyFilter() {
        const search = document.getElementById('filterSearch').value;
        const status = document.getElementById('filterStatus').value;
        const url    = new URL(window.location.href);
        url.searchParams.set('search', search);
        url.searchParams.set('status', status);
        window.location.href = url.toString();
    }

    let hapusUrl = '';

    function konfirmasiHapus(id, nama) {
        hapusUrl = '/kelola-seminar/' + id + '/hapus';
        document.getElementById('popupKonfirmasiMsg').textContent =
            'Apakah Anda yakin ingin menghapus jadwal seminar ' + nama + '?';
        document.getElementById('popupKonfirmasi').classList.add('active');
    }

    function eksekusiHapus() {
        const form = document.getElementById('formHapus');
        form.action = hapusUrl;
        tutupPopup('popupKonfirmasi');
        form.submit();
    }

    function tutupPopup(id) {
        document.getElementById(id).classList.remove('active');
    }

    function tutupBerhasil() {
        tutupPopup('popupBerhasil');
    }

    document.querySelectorAll('.popup-overlay').forEach(function(overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('active');
        });
    });
</script>

@endsection