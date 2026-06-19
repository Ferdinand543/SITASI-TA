@extends('layouts.app')

@section('title', 'Jadwal Seminar Mahasiswa - Admin')

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

    .jms-wrap { background: var(--bg); min-height: 100vh; }

    .jms-hero {
        background-image: url('{{ asset("images/bg.jpeg") }}');
        background-size: cover; background-position: center;
        border-radius: 20px; padding: 36px 40px; margin-bottom: 24px;
        position: relative; overflow: hidden;
    }
    .jms-hero::before {
        content: ''; position: absolute; right: -40px; top: -40px;
        width: 220px; height: 220px; background: rgba(201,162,39,.12); border-radius: 50%;
    }
    .jms-hero-title { font-size: 26px; font-weight: 800; color: #735C00; margin-bottom: 6px; position: relative; }
    .jms-hero-sub { font-size: 13px; color: #92400E; position: relative; max-width: 560px; line-height: 1.6; }

    .jms-grid {
        display: grid;
        grid-template-columns: 1fr 260px;
        gap: 20px;
        align-items: start;
    }
    .jms-grid > * { min-width: 0; }

    .stat-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 20px; }
    .stat-card {
        background: var(--white); border-radius: var(--radius); padding: 20px 22px;
        border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(0,0,0,.05);
    }
    .stat-label { font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
    .stat-num { font-size: 26px; font-weight: 900; color: var(--neutral); line-height: 1; margin-bottom: 4px; }
    .stat-desc { font-size: 12px; color: var(--muted); }

    .filter-card {
        background: var(--white); border-radius: var(--radius); padding: 14px 18px;
        border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(0,0,0,.05);
        margin-bottom: 16px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    }
    .filter-search {
        flex: 1; min-width: 0; padding: 9px 12px 9px 36px;
        border: 1.5px solid var(--border); border-radius: 8px; font-size: 13px;
        outline: none; font-family: inherit; transition: border .2s; box-sizing: border-box;
        background: #FAFAFA url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='none' viewBox='0 0 24 24' stroke='%236B7280' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 10px center;
    }
    .filter-search:focus { border-color: var(--gold); background-color: #fff; }
    .filter-select {
        padding: 9px 12px; border: 1.5px solid var(--border); border-radius: 8px;
        font-size: 13px; outline: none; background: #FAFAFA; font-family: inherit; cursor: pointer;
    }
    .filter-select:focus { border-color: var(--gold); }

    .tabel-card { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(0,0,0,.05); overflow: hidden; min-width: 0; width: 100%; }
    .tabel-card-title { padding: 16px 20px; border-bottom: 1px solid var(--border); font-size: 15px; font-weight: 800; color: var(--neutral); }
    .tabel-card-sub { padding: 0 20px 16px; margin-top: -10px; font-size: 12.5px; color: var(--muted); }
    .tabel-scroll { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 800px; }
    thead th { padding: 11px 14px; font-size: 11px; font-weight: 700; color: var(--muted); text-align: left; background: #FAFAFA; border-bottom: 1px solid var(--border); white-space: nowrap; letter-spacing: .3px; text-transform: uppercase; }
    tbody tr { border-bottom: 1px solid #F3F4F6; transition: background .15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #FAFBFF; }
    tbody td { padding: 14px; font-size: 13px; color: var(--neutral); vertical-align: middle; }

    .badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 99px; font-size: 11px; font-weight: 700; white-space: nowrap; }
    .badge-menunggu    { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }
    .badge-dijadwalkan { background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; }
    .badge-selesai     { background: #F3F4F6; color: #374151; border: 1px solid #E5E7EB; }

    .dosen-list { display: flex; flex-direction: column; gap: 3px; }
    .dosen-item { font-size: 12px; color: var(--neutral); display: flex; align-items: flex-start; gap: 4px; }
    .dosen-urutan {
        font-size: 10px; font-weight: 700; color: var(--gold);
        background: var(--gold-lt); border: 1px solid var(--gold-border);
        border-radius: 4px; padding: 1px 5px; flex-shrink: 0; margin-top: 1px;
    }

    .btn-file {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 11px; border-radius: 8px; font-size: 11.5px; font-weight: 700;
        cursor: pointer; transition: all .2s; text-decoration: none; white-space: nowrap;
        border: 1px solid var(--gold-border); background: var(--gold-lt); color: #92400E;
    }
    .btn-file:hover { background: var(--gold); color: #fff; border-color: var(--gold); }
    .btn-file.disabled {
        background: #F3F4F6; color: #9CA3AF; border-color: var(--border);
        cursor: not-allowed; pointer-events: none;
    }

    .empty-state { text-align: center; padding: 60px 20px; color: var(--muted); }
    .empty-state-icon { font-size: 40px; margin-bottom: 10px; }
    .empty-state-text { font-size: 14px; font-weight: 600; color: var(--neutral); margin-bottom: 4px; }
    .empty-state-sub { font-size: 12px; }

    .sidebar-card {
        background: var(--white); border-radius: var(--radius); padding: 18px;
        border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(0,0,0,.05);
    }
    .sidebar-title { font-size: 13px; font-weight: 800; color: var(--neutral); margin-bottom: 14px; }
    .sidebar-item { display: flex; gap: 12px; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid #F3F4F6; }
    .sidebar-item:last-child { border-bottom: none; padding-bottom: 0; }
    .sidebar-date-badge {
        min-width: 36px; text-align: center; background: var(--gold-lt);
        border: 1px solid var(--gold-border); border-radius: 8px; padding: 4px 6px; flex-shrink: 0;
    }
    .sidebar-date-month { font-size: 9px; font-weight: 700; color: var(--gold); text-transform: uppercase; }
    .sidebar-date-day   { font-size: 16px; font-weight: 900; color: var(--gold); line-height: 1; }
    .sidebar-nama   { font-size: 12.5px; font-weight: 700; color: var(--neutral); margin-bottom: 2px; }
    .sidebar-detail { font-size: 11px; color: var(--muted); }
    .sidebar-empty  { text-align: center; padding: 20px; font-size: 12px; color: var(--muted); }

    .btn-back {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 16px; background: #fff; border: 1.5px solid var(--border);
        border-radius: 10px; font-size: 13px; font-weight: 600; color: var(--neutral);
        text-decoration: none; transition: all .2s; margin-top: 20px;
    }
    .btn-back:hover { border-color: var(--gold); color: var(--gold); }

    @media (max-width: 1100px) { .jms-grid { grid-template-columns: 1fr 200px; } }
    @media (max-width: 980px)  { .jms-grid { grid-template-columns: 1fr; } }
    @media (max-width: 700px)  { .stat-row { grid-template-columns: 1fr; } }
</style>

<div class="jms-wrap">

    <div class="jms-hero">
        <div class="jms-hero-title">Jadwal Seminar Mahasiswa</div>
        <div class="jms-hero-sub">Pantau jadwal seminar seluruh mahasiswa yang telah lolos administrasi seminar tugas akhir.</div>
    </div>

    <div class="jms-grid">

        <div>

            <div class="stat-row">
                <div class="stat-card">
                    <div class="stat-label">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                        </svg>
                        Hari Ini
                    </div>
                    <div class="stat-num">{{ $totalHariIni }}</div>
                    <div class="stat-desc">Seminar Hari Ini</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5"/>
                        </svg>
                        Total
                    </div>
                    <div class="stat-num">{{ $totalJadwalSeminar }}</div>
                    <div class="stat-desc">Total Mahasiswa Seminar</div>
                </div>
            </div>

            <div class="filter-card">
                <input type="text" id="filterSearch" class="filter-search"
                    placeholder="Cari NIM, nama mahasiswa, atau judul tugas akhir..."
                    value="{{ request('search') }}">
                <select id="filterStatus" class="filter-select">
                    <option value="Semua Status" {{ request('status', 'Semua Status') === 'Semua Status' ? 'selected' : '' }}>Semua Status</option>
                    <option value="Menunggu Jadwal"    {{ request('status') === 'Menunggu Jadwal'    ? 'selected' : '' }}>Menunggu Jadwal</option>
                    <option value="Sudah Dijadwalkan"  {{ request('status') === 'Sudah Dijadwalkan'  ? 'selected' : '' }}>Sudah Dijadwalkan</option>
                    <option value="Selesai"            {{ request('status') === 'Selesai'            ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="tabel-card">
                <div class="tabel-card-title">Daftar Jadwal Seminar Mahasiswa</div>
                <div class="tabel-card-sub">Menampilkan seluruh mahasiswa seminar beserta jadwal, dosen pembimbing, dan dosen penguji.</div>
                <div class="tabel-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Mahasiswa</th>
                                <th>Judul Tugas Akhir</th>
                                <th>Proposal</th>
                                <th>Pembimbing</th>
                                <th>Tim Penguji</th>
                                <th>Jadwal</th>
                                <th>Ruangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($seminars as $s)
                            <tr>
                                <td style="font-weight:700;font-size:12.5px;">{{ $s->mahasiswa_id }}</td>
                                <td>
                                    <div style="font-weight:600;">{{ $s->nama }}</div>
                                    <div style="font-size:11px;color:var(--muted);">{{ $s->angkatan ?? '' }}</div>
                                </td>
                                <td style="max-width:200px;">
                                    <div style="font-size:12.5px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;">
                                        {{ $s->judul_ta ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    @if($s->file_proposal)
                                        <a href="{{ asset('storage/'.$s->file_proposal) }}" target="_blank" class="btn-file">
                                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                            </svg>
                                            Preview
                                        </a>
                                    @else
                                        <span class="btn-file disabled">Belum Ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($s->pembimbing))
                                        <div class="dosen-list">
                                            @foreach($s->pembimbing as $d)
                                            <div class="dosen-item">
                                                <span class="dosen-urutan">P{{ $d['urutan'] }}</span>
                                                <span>{{ $d['nama'] }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span style="color:var(--muted);font-size:12px;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($s->penguji))
                                        <div class="dosen-list">
                                            @foreach($s->penguji as $d)
                                            <div class="dosen-item">
                                                <span class="dosen-urutan">P{{ $d['urutan'] }}</span>
                                                <span>{{ $d['nama'] }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span style="color:var(--muted);font-size:12px;">Belum Ditentukan</span>
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
                                        @php
                                            $st = $s->status_seminar ?? 'Menunggu Jadwal';
                                            $badgeClass = match($st) {
                                                'Sudah Dijadwalkan' => 'badge-dijadwalkan',
                                                'Selesai'           => 'badge-selesai',
                                                default             => 'badge-menunggu',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}" style="margin-top:4px;">
                                            <svg width="6" height="6" viewBox="0 0 10 10" fill="currentColor"><circle cx="5" cy="5" r="5"/></svg>
                                            {{ $st }}
                                        </span>
                                    @else
                                        <span class="badge badge-menunggu">
                                            <svg width="6" height="6" viewBox="0 0 10 10" fill="currentColor"><circle cx="5" cy="5" r="5"/></svg>
                                            Menunggu Jadwal
                                        </span>
                                    @endif
                                </td>
                                <td style="font-size:12.5px;">{{ $s->ruang ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">📅</div>
                                        <div class="empty-state-text">Belum ada jadwal seminar mahasiswa</div>
                                        <div class="empty-state-sub">Data akan muncul setelah mahasiswa lolos administrasi seminar</div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <a href="{{ route('jadwal-akademik.index') }}" class="btn-back">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke Jadwal
            </a>

        </div>

        {{-- SIDEBAR --}}
        <div>
            <div class="sidebar-card">
                <div class="sidebar-title">Seminar Hari Ini</div>
                @forelse($seminarHariIni as $sh)
                @php
                    $bulan = \Carbon\Carbon::parse($sh->tanggal_seminar)->translatedFormat('M');
                    $hari  = \Carbon\Carbon::parse($sh->tanggal_seminar)->format('d');
                @endphp
                <div class="sidebar-item">
                    <div class="sidebar-date-badge">
                        <div class="sidebar-date-month">{{ strtoupper($bulan) }}</div>
                        <div class="sidebar-date-day">{{ $hari }}</div>
                    </div>
                    <div>
                        <div class="sidebar-nama">{{ $sh->nama }}</div>
                        <div class="sidebar-detail">{{ $sh->ruang ?? '-' }}</div>
                        <div class="sidebar-detail">
                            {{ $sh->waktu_mulai ? \Carbon\Carbon::parse($sh->waktu_mulai)->format('H:i') : '' }}
                            @if($sh->waktu_selesai) - {{ \Carbon\Carbon::parse($sh->waktu_selesai)->format('H:i') }} WIB @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="sidebar-empty">Tidak ada seminar hari ini</div>
                @endforelse
            </div>
        </div>

    </div>
</div>

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
</script>

@endsection