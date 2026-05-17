@extends('layouts.app')

@section('content')

<style>
    .hero { background: linear-gradient(135deg, #f5c842 0%, #fff9e6 60%, #fff 100%); border-radius: 16px; padding: 40px 48px; margin-bottom: 28px; position: relative; overflow: hidden; min-height: 140px; display: flex; align-items: center; }
    .hero-dots { position: absolute; right: 40px; top: 20px; display: grid; grid-template-columns: repeat(8, 10px); gap: 7px; opacity: 0.25; }
    .hero-dots span { width: 5px; height: 5px; background: #b8860b; border-radius: 50%; display: block; }
    .hero h1 { font-size: 26px; font-weight: 800; color: #735C00; margin: 0; }
    .hero p  { color: #9a7c00; margin-top: 6px; font-size: 13px; }
    .layout { display: grid; grid-template-columns: 1fr 240px; gap: 20px; align-items: start; }
    .card-jadwal { background: #fff; border-radius: 12px; padding: 20px 24px; box-shadow: 0 1px 6px rgba(0,0,0,0.06); margin-bottom: 20px; }
    .card-title { font-size: 14px; font-weight: 700; color: #333; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; }
    .persen-badge { font-size: 11px; background: #f5c842; color: #7a6000; padding: 2px 10px; border-radius: 20px; font-weight: 600; }

    /* ── Timeline ── */
    .timeline { display: flex; flex-direction: column; }
    .timeline-item { display: flex; gap: 14px; padding-bottom: 22px; }
    .timeline-item:last-child { padding-bottom: 0; }
    .dot-wrap { display: flex; flex-direction: column; align-items: center; flex-shrink: 0; }
    .dot { width: 14px; height: 14px; border-radius: 50%; border: 2px solid #e0e0e0; background: #fff; margin-top: 2px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
    .dot.selesai     { background: #f5c842; border-color: #f5c842; }
    .dot.selesai::after { content: '✓'; font-size: 8px; color: #fff; font-weight: 900; }
    .dot.berlangsung { background: #f5c842; border-color: #f5c842; box-shadow: 0 0 0 3px #fdf3c0; }
    .dot.belum-mulai { background: #f0f0f0; border-color: #e0e0e0; }
    .dot-line { width: 2px; flex: 1; background: #f0f0f0; margin-top: 4px; }
    .tl-content { flex: 1; padding-top: 0; }
    .tl-nama-selesai { font-size: 13px; font-weight: 700; color: #222; margin-bottom: 4px; }
    .tl-badge-row { display: flex; align-items: center; gap: 8px; }
    .tl-badge-selesai { display: inline-block; font-size: 10px; padding: 2px 10px; border-radius: 20px; background: #e6f4ea; color: #2e7d32; font-weight: 600; }
    .tl-tgl-selesai { font-size: 11px; color: #bbb; }
    .tl-card-aktif { background: #fffdf0; border: 1.5px solid #f5c842; border-radius: 10px; padding: 10px 14px; flex: 1; }
    .tl-nama-aktif { font-size: 13px; font-weight: 700; color: #222; margin-bottom: 5px; }
    .tl-badge-berlangsung { display: inline-block; font-size: 10px; padding: 2px 10px; border-radius: 20px; background: #fff3cd; color: #d48800; font-weight: 600; margin-bottom: 4px; }
    .tl-ket { font-size: 11px; color: #999; margin-top: 4px; }
    .tl-tgl-aktif { font-size: 11px; color: #b8860b; margin-top: 6px; }
    .tl-nama-belum { font-size: 13px; font-weight: 600; color: #bbb; margin-bottom: 3px; }
    .tl-label-belum { font-size: 11px; color: #ccc; }

    /* ── Kanan ── */
    .right-col { display: flex; flex-direction: column; gap: 14px; }
    .search-input { width: 100%; padding: 8px 12px; border: 1px solid #eee; border-radius: 8px; font-size: 12px; margin-bottom: 8px; background: #fafafa; color: #555; outline: none; box-sizing: border-box; }
    .filter-label { font-size: 11px; color: #aaa; margin-bottom: 4px; display: block; }
    .filter-select { width: 100%; padding: 8px 12px; border: 1px solid #eee; border-radius: 8px; font-size: 12px; background: #fafafa; color: #555; outline: none; box-sizing: border-box; }

    /* ── Button Reset Filter ── */
    .btn-reset-filter {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        margin-top: 10px;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        box-sizing: border-box;
        appearance: none;
        -webkit-appearance: none;
        border: 1.5px solid #e0e0e0;
        background: #f7f7f7;
        color: #999;
        font-family: inherit;
        line-height: 1;
        text-align: center;
        transition: background 0.15s, color 0.15s, border-color 0.15s;
    }
    .btn-reset-filter:hover {
        background: #fff0f0;
        border-color: #f5a0a0;
        color: #e05555;
    }
    .btn-reset-filter.aktif {
        border-color: #f5c842;
        background: #fff9e0;
        color: #a07800;
    }
    .btn-reset-filter.aktif:hover {
        background: #fff0f0;
        border-color: #f5a0a0;
        color: #e05555;
    }

    /* ── Card Terdekat ── */
    .card-terdekat {
        background: #fde68a;
        border-radius: 18px;
        padding: 18px;
        position: relative;
        overflow: hidden;
        box-shadow: none;
    }
    .card-terdekat::after {
        content: '📅';
        font-size: 70px;
        position: absolute;
        right: -4px;
        bottom: -10px;
        opacity: 0.15;
        line-height: 1;
    }
    .card-terdekat .trd-label {
        font-size: 10px;
        font-weight: 700;
        color: #92400e;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .card-terdekat .trd-nama {
        font-size: 16px;
        font-weight: 800;
        color: #1a1a1a;
        line-height: 1.3;
        margin-bottom: 2px;
    }
    .card-terdekat .trd-hari {
        font-size: 13px;
        color: #1a1a1a;
        font-weight: 700;
        margin-bottom: 12px;
    }
    .card-terdekat .trd-tgl {
        font-size: 11px;
        color: #78350f;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* ── Tabel ── */
    .tabel-jadwal { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,0.06); }
    .tabel-jadwal thead th { background: #fff; font-size: 11px; color: #bbb; text-align: left; padding: 12px 16px; border-bottom: 1px solid #f5f5f5; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .tabel-jadwal tbody td { padding: 14px 16px; font-size: 13px; border-bottom: 1px solid #f9f9f9; vertical-align: middle; color: #333; }
    .tabel-jadwal tbody tr:last-child td { border-bottom: none; }
    .tabel-jadwal tbody tr:hover { background: #fffdf5; }
    .tabel-jadwal tbody tr td:first-child { border-left: 3px solid #f5c842; }
    .nama-kegiatan { font-weight: 700; font-size: 13px; color: #222; }
    .sub-judul { font-size: 11px; color: #ccc; margin-top: 2px; }
    .badge-st { display: inline-block; font-size: 11px; padding: 3px 12px; border-radius: 6px; font-weight: 600; }
    .st-akan-datang { background: #fff3cd; color: #d48800; }
    .st-berlangsung { background: #dbeafe; color: #1d4ed8; }
    .st-selesai     { background: #dcfce7; color: #16a34a; }
</style>

{{-- Hero --}}
<div class="hero">
    <div>
        <h1>Jadwal dan Timeline Tugas Akhir</h1>
        <p>Pantau seluruh jadwal dan tahapan tugas akhir Anda secara terstruktur.</p>
    </div>
    <div class="hero-dots">
        @for($r = 0; $r < 5; $r++)
            @for($c = 0; $c < 8; $c++)
                <span></span>
            @endfor
        @endfor
    </div>
</div>

<div class="layout">

    {{-- KIRI --}}
    <div>

        {{-- Timeline Progress --}}
        <div class="card-jadwal">
            <div class="card-title">
                Timeline Progress
                @php
                    $totalSelesai = $jadwals->where('status', 'Selesai')->count();
                    $totalSemua   = $jadwals->count();
                    $persen       = $totalSemua > 0 ? round(($totalSelesai / $totalSemua) * 100) : 0;
                @endphp
                <span class="persen-badge">{{ $persen }}% Selesai</span>
            </div>

            <div class="timeline">
                @forelse($jadwals as $jadwal)
                @php
                    $tgl      = \Carbon\Carbon::parse($jadwal->tanggal)->toDateString();
                    $sekarang = now()->toDateString();

                    if ($sekarang < $tgl) {
                        $dotStatus = 'belum-mulai';
                    } elseif ($sekarang === $tgl) {
                        $dotStatus = 'berlangsung';
                    } else {
                        $dotStatus = 'selesai';
                    }
                @endphp

                <div class="timeline-item">
                    <div class="dot-wrap">
                        <div class="dot {{ $dotStatus }}"></div>
                        @if(!$loop->last)
                            <div class="dot-line"></div>
                        @endif
                    </div>

                    @if($dotStatus === 'selesai')
                    <div class="tl-content">
                        <div class="tl-nama-selesai">{{ $jadwal->nama_kegiatan }}</div>
                        <div class="tl-badge-row">
                            <span class="tl-badge-selesai">Selesai</span>
                            <span class="tl-tgl-selesai">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</span>
                        </div>
                    </div>

                    @elseif($dotStatus === 'berlangsung')
                    <div class="tl-card-aktif">
                        <div class="tl-nama-aktif">{{ $jadwal->nama_kegiatan }}</div>
                        <span class="tl-badge-berlangsung">Sedang Berlangsung</span>
                        @if($jadwal->deskripsi)
                            <div class="tl-ket">{{ $jadwal->deskripsi }}</div>
                        @endif
                        <div class="tl-tgl-aktif">📅 {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</div>
                    </div>

                    @else
                    <div class="tl-content">
                        <div class="tl-nama-belum">{{ $jadwal->nama_kegiatan }}</div>
                        <div class="tl-label-belum">Belum Dimulai</div>
                    </div>
                    @endif

                </div>
                @empty
                <p style="color:#ccc; font-size:13px; text-align:center; padding: 20px 0;">
                    Belum ada data timeline.
                </p>
                @endforelse
            </div>
        </div>

        {{-- Tabel Jadwal Akademik --}}
        <p style="font-size:15px; font-weight:700; color:#333; margin-bottom:12px;">Jadwal Akademik</p>
        <table class="tabel-jadwal">
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
                @forelse($jadwals as $jadwal)
                @php
                    $tgl      = \Carbon\Carbon::parse($jadwal->tanggal)->toDateString();
                    $sekarang = now()->toDateString();

                    if ($sekarang < $tgl) {
                        $statusTabel = 'Akan Datang';
                        $badgeTabel  = 'st-akan-datang';
                    } elseif ($sekarang === $tgl) {
                        $statusTabel = 'Berlangsung';
                        $badgeTabel  = 'st-berlangsung';
                    } else {
                        $statusTabel = 'Selesai';
                        $badgeTabel  = 'st-selesai';
                    }
                @endphp
                <tr>
                    <td>
                        <div class="nama-kegiatan">{{ $jadwal->nama_kegiatan }}</div>
                        @if($jadwal->sub_judul)
                            <div class="sub-judul">{{ $jadwal->sub_judul }}</div>
                        @endif
                    </td>
                    <td style="color:#555;">
                        {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                    </td>
                    <td style="color:#555;">
                        {{ $jadwal->waktu ? \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') . ' WIB' : '-' }}
                    </td>
                    <td style="color:#555;">{{ $jadwal->lokasi ?? '-' }}</td>
                    <td>
                        <span class="badge-st {{ $badgeTabel }}">{{ $statusTabel }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; color:#ccc; padding:32px;">
                        Belum ada data jadwal akademik.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    {{-- KANAN --}}
    <div class="right-col">

        {{-- Search & Filter --}}
        <div class="card-jadwal" style="padding: 16px 18px;">
            @php $adaFilter = request('cari') || request('bulan'); @endphp
            <form method="GET" action="{{ url()->current() }}">
                <input type="text" name="cari" class="search-input"
                    placeholder="🔍  Cari jadwal atau kegiatan..."
                    value="{{ request('cari') }}"
                    onkeydown="if(event.key==='Enter') this.form.submit()">

                <span class="filter-label">Filter Bulan</span>
                <select class="filter-select" name="bulan"
                    onchange="this.form.submit()">
                    @php
                        $tahunSekarang = now()->year;
                        $filterAktif   = request('bulan');
                    @endphp
                    <option value="">Semua Bulan</option>
                    @for($b = 1; $b <= 12; $b++)
                        @php
                            $val = $tahunSekarang . '-' . str_pad($b, 2, '0', STR_PAD_LEFT);
                        @endphp
                        <option value="{{ $val }}"
                            {{ $filterAktif === $val ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create($tahunSekarang, $b, 1)->translatedFormat('F Y') }}
                        </option>
                    @endfor
                </select>

                <button
                    type="button"
                    class="btn-reset-filter {{ $adaFilter ? 'aktif' : '' }}"
                    onclick="window.location.href='{{ url()->current() }}'">
                    ↺ Reset Filter
                </button>
            </form>
        </div>

        {{-- Card Terdekat --}}
        @if($terdekat)
        <div class="card-terdekat">
            <div class="trd-label">🔔 Jadwal Terdekat</div>
            <div class="trd-nama">{{ $terdekat->nama_kegiatan }}</div>
            <div class="trd-hari">
                @if($hariLagi == 0)
                    Hari ini!
                @elseif($hariLagi == 1)
                    Besok
                @else
                    {{ $hariLagi }} Hari Lagi
                @endif
            </div>
            <div class="trd-tgl">
                📅 {{ \Carbon\Carbon::parse($terdekat->tanggal)->translatedFormat('l, d F Y') }}
            </div>
        </div>
        @else
        <div class="card-jadwal" style="text-align:center; padding: 20px;">
            <p style="color:#ccc; font-size:12px; margin:0;">Tidak ada jadwal mendatang.</p>
        </div>
        @endif

    </div>

</div>

@endsection