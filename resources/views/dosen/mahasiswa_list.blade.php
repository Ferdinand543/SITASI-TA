@extends('layouts.app')

@section('content')

@php
    $semuaAngkatan = $angkatanList->sort()->values();
    $angkatanMin   = $semuaAngkatan->first();
    $angkatanMax   = $semuaAngkatan->last();
    $rentang       = $angkatanMin && $angkatanMax ? $angkatanMin . '–' . $angkatanMax : '-';

    $angkatanTerbanyak = \Illuminate\Support\Facades\DB::table('users')
        ->where('role', 'mahasiswa')
        ->whereNotNull('angkatan')
        ->select('angkatan', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
        ->groupBy('angkatan')
        ->orderByDesc('total')
        ->first();
@endphp

<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --gold: #A16207;
        --gold-mid: #FACC15;
        --gold-light: #FFE083;
        --neutral: #1E293B;
        --muted: #6B7280;
        --border: #E5E7EB;
        --white: #ffffff;
        --bg: #F5F6FA;
    }

    .mhs-wrap * { font-family: 'Hanken Grotesk', sans-serif !important; box-sizing: border-box; }
    .mhs-wrap { background: var(--bg); min-height: 100vh; }

    /* ── HERO ── */
    .mhs-hero {
        background-image: url('{{ asset('images/bg_ajukan.jpeg') }}');
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        padding: 44px 48px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
        min-height: 200px;
        display: flex;
        align-items: center;
    }
    .mhs-hero-body { position: relative; z-index: 2; }
    .mhs-hero h1 {
        font-size: 1.9rem; font-weight: 800;
        color: #735C00; margin-bottom: 6px; line-height: 1.2;
    }
    .mhs-hero p {
        font-size: 0.88rem; color: #9A7C1A;
        margin: 0; font-weight: 500;
    }

    /* ── STATS ── */
    .mhs-stats { display: flex; gap: 20px; margin-bottom: 28px; }

    .mhs-stat {
        background: var(--white); border-radius: 16px;
        padding: 22px 28px; border-left: 4px solid var(--gold);
        box-shadow: 0 2px 8px rgba(0,0,0,0.04); min-width: 180px;
    }
    .mhs-stat-label {
        font-size: 0.72rem; font-weight: 600; color: var(--muted);
        text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px;
    }
    .mhs-stat-number { font-size: 2.4rem; font-weight: 800; color: var(--neutral); line-height: 1; }

    /* Sebaran Angkatan */
    .mhs-stat-angkatan {
        background: var(--white); border-radius: 16px;
        padding: 22px 28px; border-left: 4px solid var(--gold);
        box-shadow: 0 2px 8px rgba(0,0,0,0.04); min-width: 280px;
    }
    .sa-label {
        font-size: 0.68rem; font-weight: 700; color: #9CA3AF;
        text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;
    }
    .sa-rentang {
        font-size: 2.1rem; font-weight: 800;
        color: var(--gold); line-height: 1.1; margin-bottom: 10px;
    }
    .sa-terbanyak-row {
        display: flex; align-items: center; gap: 8px; margin-bottom: 5px;
    }
    .sa-icon-wrap {
        width: 32px; height: 32px; border-radius: 8px;
        background: #FEF9C3;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .sa-terbanyak-text {
        font-size: 0.85rem; font-weight: 700; color: var(--neutral);
    }
    .sa-terbanyak-text span { color: var(--gold); }
    .sa-sub { font-size: 0.72rem; color: #9CA3AF; font-weight: 500; }

    /* ── FILTER ── */
    .mhs-filter-section {
        background: var(--white); border-radius: 16px;
        padding: 20px 24px; margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .mhs-filter-labels { display: flex; gap: 14px; margin-bottom: 8px; }
    .mhs-filter-label-text { font-size: 0.8rem; font-weight: 600; color: var(--neutral); }
    .mhs-filter-label-text.search-lbl { flex: 1; }
    .mhs-filter-label-text.angkatan-lbl { flex: 0 0 200px; }

    .mhs-filter-inputs { display: flex; gap: 14px; align-items: center; }

    .mhs-search-wrap { flex: 1; position: relative; }
    .mhs-search-wrap .svg-icon {
        position: absolute; left: 13px; top: 50%;
        transform: translateY(-50%); pointer-events: none;
    }
    .mhs-search-input {
        width: 100%; padding: 11px 14px 11px 40px;
        border: 1.5px solid var(--border); border-radius: 10px;
        font-size: 0.85rem; color: var(--neutral);
        outline: none; background: #fff; transition: 0.2s;
    }
    .mhs-search-input:focus { border-color: var(--gold); }
    .mhs-search-input::placeholder { color: #9CA3AF; }

    .mhs-select-wrap { position: relative; flex: 0 0 200px; }
    .mhs-select-wrap .svg-icon {
        position: absolute; right: 12px; top: 50%;
        transform: translateY(-50%); pointer-events: none;
    }
    .mhs-select {
        width: 100%; padding: 11px 36px 11px 14px;
        border: 1.5px solid var(--border); border-radius: 10px;
        font-size: 0.85rem; color: var(--neutral);
        outline: none; background: #fff;
        cursor: pointer; appearance: none; -webkit-appearance: none; transition: 0.2s;
    }
    .mhs-select:focus { border-color: var(--gold); }

    .mhs-btn-reset {
        padding: 11px 18px;
        border: 1.5px solid var(--border); border-radius: 10px;
        font-size: 0.82rem; font-weight: 600;
        color: var(--muted); background: var(--white);
        cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
        transition: 0.2s; white-space: nowrap;
    }
    .mhs-btn-reset:hover { border-color: var(--gold); color: var(--neutral); text-decoration: none; }

    /* ── TABLE ── */
    .mhs-table-card {
        background: var(--white); border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04); overflow: hidden;
    }
    .mhs-table { width: 100%; border-collapse: collapse; }
    .mhs-table thead th {
        padding: 14px 20px; font-size: 0.7rem; font-weight: 700;
        color: var(--muted); text-transform: uppercase; letter-spacing: 1px;
        border-bottom: 1px solid var(--border); background: #FAFAFA; white-space: nowrap;
    }
    .mhs-table tbody tr { border-bottom: 1px solid #F3F4F6; transition: background 0.15s; }
    .mhs-table tbody tr:last-child { border-bottom: none; }
    .mhs-table tbody tr:hover { background: #FFFDF5; }
    .mhs-table tbody td { padding: 16px 20px; vertical-align: middle; }

    .mhs-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        background: #FFE083; border: 2px solid #F4E28A;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem; font-weight: 700; color: #735C00;
        flex-shrink: 0; overflow: hidden;
    }
    .mhs-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .mhs-name { font-size: 0.88rem; font-weight: 700; color: var(--neutral); line-height: 1.3; }
    .mhs-email { font-size: 0.74rem; color: var(--muted); margin-top: 2px; }
    .mhs-nim { font-size: 0.82rem; font-weight: 600; color: var(--neutral); }

    .mhs-angkatan-badge {
        display: inline-block; padding: 4px 12px;
        background: #EFF6FF; color: #1D4ED8;
        border-radius: 20px; font-size: 0.72rem; font-weight: 700;
    }

    .mhs-progress-wrap { display: flex; align-items: center; gap: 10px; }
    .mhs-progress-bar-bg {
        flex: 1; height: 8px; background: #F1F5F9;
        border-radius: 99px; overflow: hidden; min-width: 80px;
    }
    .mhs-progress-bar-fill {
        height: 100%; border-radius: 99px;
        background: linear-gradient(90deg, #FACC15, #A16207);
        transition: width 0.5s ease;
    }
    .mhs-progress-pct { font-size: 0.75rem; font-weight: 700; color: var(--muted); min-width: 34px; text-align: right; }

    .mhs-empty { text-align: center; padding: 60px 20px; }
    .mhs-empty p { color: var(--muted); font-size: 0.88rem; margin: 0; }

    @media (max-width: 768px) {
        .mhs-hero { padding: 28px 24px; min-height: unset; }
        .mhs-hero h1 { font-size: 1.4rem; }
        .mhs-stats { flex-direction: column; }
        .mhs-filter-inputs { flex-direction: column; align-items: stretch; }
        .mhs-select-wrap { flex: unset; }
        .mhs-table thead { display: none; }
        .mhs-table tbody tr { display: block; padding: 14px; }
        .mhs-table tbody td { display: block; padding: 4px 0; border: none; }
    }
</style>

<div class="mhs-wrap">

    {{-- HERO --}}
    <div class="mhs-hero">
        <div class="mhs-hero-body">
            <h1>Data Mahasiswa TA</h1>
            <p>Pantau data progress mahasiswa tugas akhir</p>
        </div>
    </div>

    {{-- STATS --}}
    <div class="mhs-stats">

        <div class="mhs-stat">
            <div class="mhs-stat-label">Total Mahasiswa</div>
            <div class="mhs-stat-number">{{ $totalMahasiswa }}</div>
        </div>

        <div class="mhs-stat-angkatan">
            <div class="sa-label">Sebaran Angkatan</div>
            <div class="sa-rentang">{{ $rentang }}</div>
            @if($angkatanTerbanyak)
            <div class="sa-terbanyak-row">
                <div class="sa-icon-wrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="#A16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div class="sa-terbanyak-text">
                    Terbanyak: <span>{{ $angkatanTerbanyak->angkatan }} ({{ $angkatanTerbanyak->total }})</span>
                </div>
            </div>
            @endif
            <div class="sa-sub">Mahasiswa TA aktif dari berbagai angkatan</div>
        </div>

    </div>

    {{-- FILTER --}}
    <form method="GET" action="{{ url('/dosen/mahasiswa') }}">
        <div class="mhs-filter-section">

            <div class="mhs-filter-labels">
                <div class="mhs-filter-label-text search-lbl">Cari Mahasiswa</div>
                <div class="mhs-filter-label-text angkatan-lbl">Angkatan</div>
                <div style="flex:0 0 130px;"></div>
            </div>

            <div class="mhs-filter-inputs">

                <div class="mhs-search-wrap">
                    <span class="svg-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                             stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </span>
                    <input type="text" name="search" class="mhs-search-input"
                           placeholder="Cari nama mahasiswa atau NIM.."
                           value="{{ $search }}">
                </div>

                <div class="mhs-select-wrap">
                    <select name="angkatan" class="mhs-select" onchange="this.form.submit()">
                        <option value="">Semua Angkatan</option>
                        @foreach($angkatanList->sortDesc() as $thn)
                            <option value="{{ $thn }}" {{ $angkatan == $thn ? 'selected' : '' }}>
                                {{ $thn }}
                            </option>
                        @endforeach
                    </select>
                    <span class="svg-icon">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                             stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </span>
                </div>

                <a href="{{ url('/dosen/mahasiswa') }}" class="mhs-btn-reset">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 4 23 10 17 10"/>
                        <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                    </svg>
                    Reset Filter
                </a>

            </div>
        </div>
        <button type="submit" style="display:none;"></button>
    </form>

    {{-- TABLE --}}
    <div class="mhs-table-card">
        @if($mahasiswaList->isEmpty())
            <div class="mhs-empty">
                <p>Tidak ada data mahasiswa ditemukan</p>
            </div>
        @else
            <table class="mhs-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>NIM</th>
                        <th>Angkatan</th>
                        <th>Progres TA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mahasiswaList as $mhs)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div class="mhs-avatar">
                                    @if($mhs->foto)
                                        <img src="{{ asset('storage/' . $mhs->foto) }}" alt="{{ $mhs->nama }}">
                                    @else
                                        {{ strtoupper(substr($mhs->nama, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="mhs-name">{{ $mhs->nama }}</div>
                                    <div class="mhs-email">{{ $mhs->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="mhs-nim">{{ $mhs->nim_nid }}</span></td>
                        <td>
                            @if($mhs->angkatan)
                                <span class="mhs-angkatan-badge">{{ $mhs->angkatan }}</span>
                            @else
                                <span style="color:#9CA3AF; font-size:0.8rem;">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="mhs-progress-wrap">
                                <div class="mhs-progress-bar-bg">
                                    <div class="mhs-progress-bar-fill" style="width: {{ $mhs->progress }}%;"></div>
                                </div>
                                <span class="mhs-progress-pct">{{ $mhs->progress }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>

@endsection