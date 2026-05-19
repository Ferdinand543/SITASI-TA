@extends('layouts.app')

@section('title', 'Jadwal dan Timeline TA')

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

    .jadwal-wrap { background: var(--bg); min-height: 100vh; }

    .jadwal-hero {
        background-image: url('{{ asset("images/bg.jpeg") }}');
        background-size: cover; background-position: center;
        border-radius: 20px; padding: 36px 40px; margin-bottom: 24px;
        position: relative; overflow: hidden;
    }
    .jadwal-hero::before { content:''; position:absolute; right:-40px; top:-40px; width:220px; height:220px; background:rgba(201,162,39,.12); border-radius:50%; }
    .jadwal-hero::after  { content:''; position:absolute; right:60px; bottom:-60px; width:160px; height:160px; background:rgba(201,162,39,.08); border-radius:50%; }
    .jadwal-hero-title { font-size:26px; font-weight:800; color:#735C00; margin-bottom:6px; position:relative; }
    .jadwal-hero-sub   { font-size:13px; color:#92400E; position:relative; }

    .jadwal-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 20px;
        align-items: start;
    }

    /* TIMELINE */
    .timeline-card {
        background: var(--white); border-radius: var(--radius);
        padding: 22px; box-shadow: 0 2px 10px rgba(0,0,0,.05);
        border: 1px solid var(--border); margin-bottom: 20px;
    }
    .timeline-card-title {
        font-size: 15px; font-weight: 800; color: var(--neutral);
        margin-bottom: 6px; display: flex; align-items: center; justify-content: space-between;
    }
    .progress-persen {
        font-size: 12px; font-weight: 700; color: var(--gold);
        background: var(--gold-lt); border: 1px solid var(--gold-border);
        padding: 3px 10px; border-radius: 99px;
    }
    .progress-bar-wrap { background: #F3F4F6; border-radius: 99px; height: 6px; margin-bottom: 20px; }
    .progress-bar-fill { background: var(--gold); border-radius: 99px; height: 100%; transition: width .4s ease; }

    .timeline-list { list-style: none; padding: 0; margin: 0; position: relative; }
    .timeline-list::before {
        content: ''; position: absolute; left: 10px; top: 0; bottom: 0;
        width: 2px; background: #E5E7EB; z-index: 0;
    }
    .timeline-item { display: flex; align-items: flex-start; gap: 14px; padding: 0 0 18px 0; position: relative; z-index: 1; }
    .timeline-item:last-child { padding-bottom: 0; }
    .tl-dot {
        width: 22px; height: 22px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 700; margin-top: 1px; position: relative; z-index: 2;
    }
    .tl-dot.selesai { background: var(--gold); color: #fff; border: 2px solid var(--gold); }
    .tl-dot.aktif   { background: #fff; color: var(--gold); border: 2.5px solid var(--gold); box-shadow: 0 0 0 3px rgba(201,162,39,.15); }
    .tl-dot.belum   { background: #fff; color: #D1D5DB; border: 2px solid #E5E7EB; }
    .tl-label { font-size: 13px; font-weight: 600; color: var(--neutral); line-height: 1.4; }
    .tl-sub   { font-size: 11.5px; color: var(--muted); margin-top: 2px; }
    .tl-badge { display: inline-block; font-size: 10.5px; font-weight: 700; padding: 2px 8px; border-radius: 99px; margin-top: 4px; }
    .tl-badge.selesai { background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; }
    .tl-badge.aktif   { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }
    .tl-badge.belum   { background: #F9FAFB; color: #9CA3AF; border: 1px solid #E5E7EB; }

    /* TERDEKAT */
    .terdekat-card {
        background: linear-gradient(135deg, #C9A227 0%, #E8C547 100%);
        border-radius: var(--radius); padding: 18px 20px; color: #fff;
        margin-bottom: 20px; position: relative; overflow: hidden;
    }
    .terdekat-card::after { content: ''; position: absolute; right: -20px; bottom: -20px; width: 100px; height: 100px; background: rgba(255,255,255,.1); border-radius: 50%; }
    .terdekat-label { font-size: 11px; font-weight: 700; opacity: .8; margin-bottom: 6px; letter-spacing: .5px; text-transform: uppercase; }
    .terdekat-nama  { font-size: 17px; font-weight: 800; margin-bottom: 4px; line-height: 1.3; }
    .terdekat-hari  { font-size: 22px; font-weight: 900; margin-bottom: 2px; }
    .terdekat-tgl   { font-size: 12px; opacity: .85; display: flex; align-items: center; gap: 5px; }

    /* FILTER */
    .filter-card {
        background: var(--white); border-radius: var(--radius); padding: 16px 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,.05); border: 1px solid var(--border); margin-bottom: 20px;
    }
    .filter-row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .filter-label { font-size: 12px; font-weight: 700; color: var(--muted); white-space: nowrap; }
    .filter-input {
        flex: 1; min-width: 160px; padding: 8px 12px 8px 34px;
        border: 1.5px solid var(--border); border-radius: 8px; font-size: 13px;
        outline: none; font-family: inherit; transition: border .2s;
        background: #FAFAFA url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='none' viewBox='0 0 24 24' stroke='%236B7280' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 10px center;
    }
    .filter-input:focus { border-color: var(--gold); background-color: #fff; }
    .filter-select {
        padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 8px;
        font-size: 13px; outline: none; background: #FAFAFA; font-family: inherit; cursor: pointer;
    }
    .filter-select:focus { border-color: var(--gold); }

    /* TABEL */
    .jadwal-tabel-card {
        background: var(--white); border-radius: var(--radius);
        box-shadow: 0 2px 10px rgba(0,0,0,.05); border: 1px solid var(--border); overflow: hidden;
    }
    .jadwal-tabel-title { padding: 16px 20px; border-bottom: 1px solid var(--border); font-size: 15px; font-weight: 800; color: var(--neutral); }
    .tabel-scroll { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 560px; }
    thead th { padding: 11px 16px; font-size: 11.5px; font-weight: 700; color: var(--muted); text-align: left; background: #FAFAFA; border-bottom: 1px solid var(--border); white-space: nowrap; }
    tbody tr { border-bottom: 1px solid #F3F4F6; transition: background .15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #FAFBFF; }
    tbody td { padding: 13px 16px; font-size: 13px; color: var(--neutral); vertical-align: middle; }

    .kat-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 700; }
    .kat-seminar      { background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; }
    .kat-administrasi { background: #F5F3FF; color: #6D28D9; border: 1px solid #DDD6FE; }
    .kat-bimbingan    { background: var(--gold-lt); color: #92400E; border: 1px solid var(--gold-border); }

    .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 99px; font-size: 11px; font-weight: 700; }
    .status-akan    { background: #F0F9FF; color: #0369A1; border: 1px solid #BAE6FD; }
    .status-berlang { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }
    .status-selesai { background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; }
    .status-ditutup { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }

    .empty-row td { text-align: center; padding: 48px; color: var(--muted); font-size: 14px; }

    .ringkasan-card {
        background: var(--white); border-radius: var(--radius); padding: 18px 20px;
        border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(0,0,0,.05);
    }

    @media (max-width: 900px) {
        .jadwal-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="jadwal-wrap">

    <div class="jadwal-hero">
        <div class="jadwal-hero-title">Jadwal dan Timeline Tugas Akhir</div>
        <div class="jadwal-hero-sub">Pantau seluruh jadwal dan tahapan tugas akhir Anda secara terstruktur.</div>
    </div>

    <div class="jadwal-grid">

        {{-- KIRI --}}
        <div>

            {{-- TIMELINE: hanya mahasiswa --}}
            @if($role === 'mahasiswa')
            <div class="timeline-card">
                <div class="timeline-card-title">
                    Timeline Progress
                    <span class="progress-persen">{{ $progressPersen }}% Selesai</span>
                </div>
                <div class="progress-bar-wrap" style="margin-top:10px;">
                    <div class="progress-bar-fill" style="width:{{ $progressPersen }}%;"></div>
                </div>
                <ul class="timeline-list">
                    @foreach($timeline as $tl)
                    <li class="timeline-item">
                        <div class="tl-dot {{ $tl['status'] }}">
                            @if($tl['status'] === 'selesai')
                                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @elseif($tl['status'] === 'aktif')
                                <div style="width:6px;height:6px;background:var(--gold);border-radius:50%;"></div>
                            @endif
                        </div>
                        <div>
                            <div class="tl-label">{{ $tl['label'] }}</div>
                            @if($tl['keterangan'])
                            <div class="tl-sub">{{ $tl['keterangan'] }}</div>
                            @endif
                            @if($tl['tanggal'])
                            <div class="tl-sub">{{ \Carbon\Carbon::parse($tl['tanggal'])->translatedFormat('d M Y') }}</div>
                            @endif
                            <span class="tl-badge {{ $tl['status'] }}">
                                @if($tl['status'] === 'selesai') Selesai
                                @elseif($tl['status'] === 'aktif') Sedang Berlangsung
                                @else Belum Dimulai
                                @endif
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- FILTER --}}
            <form method="GET" action="{{ route('jadwal.index') }}">
                <div class="filter-card">
                    <div class="filter-row">
                        <span class="filter-label">Cari Jadwal</span>
                        <input type="text" name="search" class="filter-input"
                            placeholder="Cari jadwal atau kegiatan..."
                            value="{{ request('search') }}">
                        <select name="bulan" class="filter-select" onchange="this.form.submit()">
                            <option value="">Semua Bulan</option>
                            @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            {{-- TABEL --}}
            <div class="jadwal-tabel-card">
                <div class="jadwal-tabel-title">Jadwal Akademik</div>
                <div class="tabel-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwal as $j)
                            <tr>
                                <td>
                                    <div style="font-weight:600; font-size:13px;">{{ $j->nama_kegiatan }}</div>
                                    @if($j->sub_judul)
                                    <div style="font-size:11.5px; color:var(--muted); margin-top:2px;">{{ $j->sub_judul }}</div>
                                    @endif
                                    <span class="kat-badge kat-{{ strtolower($j->kategori) }}" style="margin-top:4px;">
                                        {{ $j->kategori }}
                                    </span>
                                </td>
                                <td style="white-space:nowrap; font-size:12.5px;">
                                    {{ \Carbon\Carbon::parse($j->tanggal)->translatedFormat('d M Y') }}
                                </td>
                                <td style="white-space:nowrap; font-size:12.5px;">
                                    {{ $j->waktu ? \Carbon\Carbon::parse($j->waktu)->format('H:i') . ' WIB' : '—' }}
                                </td>
                                <td style="font-size:12.5px;">{{ $j->lokasi ?? '—' }}</td>
                                <td>
                                    @php
                                        $st = $j->status ?? 'Akan Datang';
                                        $stClass = match($st) {
                                            'Berlangsung' => 'status-berlang',
                                            'Selesai'     => 'status-selesai',
                                            'Ditutup'     => 'status-ditutup',
                                            default       => 'status-akan',
                                        };
                                    @endphp
                                    <span class="status-badge {{ $stClass }}">
                                        <svg width="7" height="7" viewBox="0 0 10 10" fill="currentColor"><circle cx="5" cy="5" r="5"/></svg>
                                        {{ $st }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr class="empty-row">
                                <td colspan="5">
                                    <div style="font-size:32px;margin-bottom:8px;">📅</div>
                                    Belum ada jadwal akademik
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- KANAN --}}
        <div>

            @if($terdekat)
            @php
                $hariLagi = \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($terdekat->tanggal)->startOfDay());
            @endphp
            <div class="terdekat-card">
                <div class="terdekat-label">⚡ Terdekat</div>
                <div class="terdekat-nama">{{ $terdekat->nama_kegiatan }}</div>
                <div class="terdekat-hari">
                    @if($hariLagi == 0) Hari Ini!
                    @elseif($hariLagi == 1) Besok
                    @else {{ $hariLagi }} Hari Lagi
                    @endif
                </div>
                <div class="terdekat-tgl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    {{ \Carbon\Carbon::parse($terdekat->tanggal)->translatedFormat('l, d F Y') }}
                </div>
            </div>
            @endif

            {{-- RINGKASAN --}}
            <div class="ringkasan-card">
                <div style="font-size:13px; font-weight:800; color:var(--neutral); margin-bottom:14px;">Ringkasan Jadwal</div>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 14px; background:#F9FAFB; border-radius:10px;">
                        <span style="font-size:12.5px; color:var(--muted); font-weight:600;">Total Jadwal</span>
                        <span style="font-size:14px; font-weight:800; color:var(--neutral);">{{ $totalJadwal }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 14px; background:#F0F9FF; border-radius:10px;">
                        <span style="font-size:12.5px; color:#0369A1; font-weight:600;">Akan Datang</span>
                        <span style="font-size:14px; font-weight:800; color:#0369A1;">{{ $akanDatang }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 14px; background:#FFFBEB; border-radius:10px;">
                        <span style="font-size:12.5px; color:#92400E; font-weight:600;">Berlangsung</span>
                        <span style="font-size:14px; font-weight:800; color:#92400E;">{{ $berlangsung }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 14px; background:#F0FDF4; border-radius:10px;">
                        <span style="font-size:12.5px; color:#15803D; font-weight:600;">Selesai</span>
                        <span style="font-size:14px; font-weight:800; color:#15803D;">{{ $selesaiCount }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

@endsection