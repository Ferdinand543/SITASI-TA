@extends('layouts.app')

@section('title', 'Daftar Seminar')

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

    .seminar-wrap {
        background: var(--bg);
        min-height: 100vh;
        padding-bottom: 40px;
    }

    /* HERO */
    .seminar-hero {
        background-image: url('{{ asset("images/1.jpeg") }}');
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        padding: 36px 40px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        min-height: 160px;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-title {
        font-size: 28px;
        font-weight: 800;
        color: #735C00;
        margin-bottom: 6px;
    }

    .hero-sub {
        font-size: 13px;
        color: #92400E;
        margin-bottom: 16px;
        max-width: 500px;
    }

    .btn-ajukan {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: var(--gold);
        color: #fff;
        padding: 10px 22px;
        border-radius: 99px;
        font-size: 13px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: background .2s, transform .15s;
    }

    .btn-ajukan:hover {
        background: #b8911f;
        transform: translateY(-1px);
        color: #fff;
    }

    /* STAT CARDS */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--white);
        border-radius: var(--radius);
        padding: 18px 20px;
        border: 1px solid var(--border);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
    }

    .stat-icon.gold {
        background: var(--gold-lt);
    }

    .stat-icon.green {
        background: #F0FDF4;
    }

    .stat-icon.gray {
        background: #F9FAFB;
    }

    .stat-label {
        font-size: 11.5px;
        color: var(--muted);
        font-weight: 600;
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 15px;
        font-weight: 800;
        color: var(--neutral);
    }

    .stat-sub {
        font-size: 11px;
        color: var(--muted);
        margin-top: 3px;
    }

    .progress-bar-wrap {
        background: #F3F4F6;
        border-radius: 99px;
        height: 5px;
        margin-top: 6px;
        width: 100%;
    }

    .progress-bar-fill {
        background: var(--gold);
        border-radius: 99px;
        height: 100%;
    }

    /* BADGES */
    .badge-sm {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 99px;
    }

    .badge-lolos {
        background: #F0FDF4;
        color: #15803D;
        border: 1px solid #BBF7D0;
    }

    .badge-tidak {
        background: #FEF2F2;
        color: #991B1B;
        border: 1px solid #FECACA;
    }

    .badge-menunggu {
        background: #FFFBEB;
        color: #92400E;
        border: 1px solid #FDE68A;
    }

    .badge-terdaftar {
        background: #EFF6FF;
        color: #1D4ED8;
        border: 1px solid #BFDBFE;
    }

    .badge-na {
        background: #F9FAFB;
        color: #9CA3AF;
        border: 1px solid #E5E7EB;
    }

    .badge-draft {
        background: #F1F5F9;
        color: #475569;
        border: 1px solid #CBD5E1;
    }

    /* SEARCH */
    .search-wrap {
        background: var(--white);
        border-radius: var(--radius);
        padding: 14px 16px;
        margin-bottom: 20px;
        border: 1px solid var(--border);
        position: relative;
    }

    .search-wrap svg {
        position: absolute;
        left: 28px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
    }

    .search-input {
        width: 100%;
        padding: 9px 12px 9px 38px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        outline: none;
        font-family: inherit;
        transition: border .2s;
        background: #FAFAFA;
    }

    .search-input:focus {
        border-color: var(--gold);
        background: #fff;
    }

    /* TABLE */
    .tabel-card {
        background: var(--white);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
        overflow: hidden;
    }

    .tabel-title {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        font-size: 15px;
        font-weight: 800;
        color: var(--neutral);
    }

    .tabel-scroll {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }

    thead th {
        padding: 11px 16px;
        font-size: 11.5px;
        font-weight: 700;
        color: var(--muted);
        text-align: left;
        background: #FAFAFA;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    tbody tr {
        border-bottom: 1px solid #F3F4F6;
        transition: background .15s;
    }

    tbody tr:last-child {
        border-bottom: none;
    }

    tbody tr:hover {
        background: #FAFBFF;
    }

    tbody td {
        padding: 14px 16px;
        font-size: 13px;
        color: var(--neutral);
        vertical-align: middle;
    }

    .judul-ta {
        font-weight: 600;
        font-size: 13px;
        line-height: 1.4;
        max-width: 240px;
    }

    .tgl-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--neutral);
    }

    .tgl-sub {
        font-size: 11px;
        color: var(--muted);
        margin-top: 1px;
    }

    .progress-cell .persen {
        font-size: 11.5px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .bar-wrap {
        background: #F3F4F6;
        border-radius: 99px;
        height: 5px;
        width: 110px;
    }

    .bar-fill {
        border-radius: 99px;
        height: 100%;
    }

    .bar-green {
        background: #16A34A;
    }

    .bar-gold {
        background: var(--gold);
    }

    .bar-red {
        background: #DC2626;
    }

    .status-col {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .jadwal-info .tanggal {
        font-weight: 700;
        font-size: 13px;
    }

    .jadwal-info .row {
        display: flex;
        align-items: center;
        gap: 5px;
        color: var(--muted);
        font-size: 11.5px;
        margin-top: 2px;
    }

    /* BUTTONS */
    .btn-daftar {
        background: var(--neutral);
        color: #fff;
        padding: 9px 16px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
        text-align: center;
        display: inline-block;
        transition: background .2s;
        line-height: 1.4;
    }

    .btn-daftar:hover {
        background: #334155;
        color: #fff;
    }

    .btn-lihat {
        font-size: 12px;
        font-weight: 700;
        color: var(--neutral);
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-lihat:hover {
        color: var(--gold);
    }

    .btn-perbaiki {
        background: #FFE083;
        color: #6C5700;
        padding: 9px 16px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
        text-align: center;
        display: inline-block;
        line-height: 1.4;
    }

    .btn-perbaiki:hover {
        background: #fdd835;
        color: #6C5700;
    }

    .empty-state {
        text-align: center;
        padding: 56px 20px;
        color: var(--muted);
    }

    .empty-state .icon {
        font-size: 40px;
        margin-bottom: 10px;
    }

    /* POPUP */
    .popup-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .5);
        z-index: 1060;
        align-items: center;
        justify-content: center;
    }

    .popup-overlay.show {
        display: flex;
    }

    .popup-box {
        background: #fff;
        border-radius: 20px;
        padding: 32px;
        max-width: 400px;
        width: 90%;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .15);
    }

    .popup-icon {
        font-size: 48px;
        margin-bottom: 12px;
    }

    .popup-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--neutral);
        margin-bottom: 8px;
    }

    .popup-sub {
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 24px;
        line-height: 1.6;
    }

    .popup-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .btn-popup-cancel {
        padding: 10px 24px;
        border-radius: 99px;
        font-size: 13px;
        font-weight: 600;
        background: #F3F4F6;
        color: var(--muted);
        border: none;
        cursor: pointer;
    }

    .btn-popup-ok {
        padding: 10px 24px;
        border-radius: 99px;
        font-size: 13px;
        font-weight: 700;
        background: #FFE083;
        color: #6C5700;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-popup-ok:hover {
        background: #fdd835;
        color: #6C5700;
    }

    @media (max-width: 768px) {
        .stat-grid {
            grid-template-columns: 1fr;
        }
    }

    .badge-draft {
        background: #FEF9C3;
        color: #6C5700;
        border: 1px solid #FDE68A;
    }
</style>

<div class="seminar-wrap">

    {{-- HERO --}}
    <div class="seminar-hero">
        <div class="hero-content">
            <div class="hero-title">Daftar Seminar</div>
            <div class="hero-sub">Pantau progres administrasi dan status pengajuan seminar tugas akhir Anda melalui dashboard terintegrasi.</div>
            @php
            $adaPengajuanAktif = $pengajuans->contains(fn($p) =>
            $p->is_draft == 0 &&
            in_array($p->status_administrasi, ['Menunggu Verifikasi', 'Lolos Administrasi'])
            );
            $adaDraft = $pengajuans->contains(fn($p) => $p->is_draft == 1);
            @endphp

            @if($adaPengajuanAktif)
            <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.85);color:#92400E;padding:10px 20px;border-radius:99px;font-size:13px;font-weight:700;border:1px solid #FDE68A;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
                Pengajuan sedang diproses
            </div>
            @elseif($adaDraft)
            <a href="{{ route('seminar.edit', $pengajuans->where('is_draft', 1)->first()->id) }}" class="btn-ajukan">
                Lanjutkan Draft
            </a>
            @else
            <a href="{{ route('seminar.create') }}" class="btn-ajukan">+ Ajukan Seminar</a>
            @endif
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon gold">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#C9A227" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                </svg>
            </div>
            <div style="flex:1;">
                <div class="stat-label">Progress Administrasi</div>
                <div class="stat-value">{{ $progressPersen }}%</div>
                <div class="stat-sub">{{ round($progressAdm) }} dari {{ round($totalDokumen) }} berkas terkumpul</div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar-fill" style="width:{{ $progressPersen }}%;"></div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#16A34A" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <div class="stat-label">Status Administrasi</div>
                <div style="margin-top:6px;">
                    @if($statusAdministrasi === 'Lolos Administrasi')
                    <span class="badge-sm badge-lolos">Lolos</span>
                    @elseif($statusAdministrasi === 'Tidak Administrasi')
                    <span class="badge-sm badge-tidak">Tidak Lolos</span>
                    @else
                    <span class="badge-sm badge-menunggu">Menunggu</span>
                    @endif
                </div>
                <div class="stat-sub" style="margin-top:4px;">Terverifikasi Akademik</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon gray">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#6B7280" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
            </div>
            <div>
                <div class="stat-label">Status Seminar</div>
                <div style="margin-top:6px;">
                    @if($sudahDaftar)
                    <span class="badge-sm badge-terdaftar">Terdaftar</span>
                    @else
                    <span class="badge-sm badge-na">N/A</span>
                    @endif
                </div>
                <div class="stat-sub" style="margin-top:4px;">
                    @if($sudahDaftar) Sudah Daftar Seminar @else Belum Daftar Seminar @endif
                </div>
            </div>
        </div>
    </div>

    {{-- SEARCH --}}
    <form method="GET" action="{{ route('seminar.daftar') }}">
        <div class="search-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.35-4.35" />
            </svg>
            <input type="text" name="search" class="search-input"
                placeholder="Cari seminar atau judul proposal..."
                value="{{ request('search') }}">
        </div>
    </form>

    {{-- TABEL --}}
    <div class="tabel-card">
        <div class="tabel-title">Daftar Pengajuan Seminar</div>
        <div class="tabel-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Judul Tugas Akhir</th>
                        <th>Progress</th>
                        <th>Status Administrasi</th>
                        <th>Jadwal Seminar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $p)
                    @php
                    $persen = $p->total_dokumen > 0 ? round(($p->progress_dokumen / $p->total_dokumen) * 100) : 0;
                    $barClass = $persen >= 100 ? 'bar-green' : ($persen >= 50 ? 'bar-gold' : 'bar-red');
                    @endphp
                    <tr>
                        <td>
                            <div class="tgl-label">{{ \Carbon\Carbon::parse($p->created_at)->format('d M Y') }}</div>
                            <div class="tgl-sub">{{ \Carbon\Carbon::parse($p->created_at)->format('h:i A') }}</div>
                        </td>
                        <td>
                            <div class="judul-ta">{{ $p->judul_ta ?? '—' }}</div>
                        </td>
                        <td>
                            <div class="progress-cell">
                                <div class="persen">{{ $persen }}% {{ $p->progress_dokumen }}/{{ $p->total_dokumen }} Dokumen</div>
                                <div class="bar-wrap">
                                    <div class="bar-fill {{ $barClass }}" style="width:{{ $persen }}%;"></div>
                                </div>
                            </div>
                        </td>

                        {{-- STATUS ADMINISTRASI --}}
                        <td>
                            <div class="status-col">
                                @if($p->is_draft == 1)
                                <span class="badge-sm badge-draft">Draft</span>
                                @elseif($p->status_administrasi === 'Lolos Administrasi')
                                <span class="badge-sm badge-lolos">Lolos Administrasi</span>
                                @elseif($p->status_administrasi === 'Tidak Administrasi')
                                <span class="badge-sm badge-tidak">Tidak Administrasi</span>
                                @else
                                <span class="badge-sm badge-menunggu">Menunggu Verifikasi</span>
                                @endif
                                <span style="font-size:11px;color:var(--muted);">{{ $p->status_seminar }}</span>
                            </div>
                        </td>

                        {{-- JADWAL SEMINAR --}}
                        <td>
                            @if($p->tanggal_seminar)
                            <div class="jadwal-info">
                                <div class="tanggal">{{ \Carbon\Carbon::parse($p->tanggal_seminar)->format('d M Y') }}</div>
                                @if($p->waktu_mulai)
                                <div class="row">
                                    <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 6v6l4 2" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($p->waktu_mulai)->format('H:i') }} — {{ $p->waktu_selesai ? \Carbon\Carbon::parse($p->waktu_selesai)->format('H:i') : '?' }} WIB
                                </div>
                                @endif
                                @if($p->ruang)
                                <div class="row">
                                    <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                    {{ $p->ruang }}
                                </div>
                                @endif
                            </div>
                            @else
                            <div style="display:flex;flex-direction:column;gap:4px;">
                                <span style="font-size:11.5px;color:var(--muted);">
                                    <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="margin-right:3px;">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 6v6l4 2" />
                                    </svg> —
                                </span>
                                <span style="font-size:11.5px;color:var(--muted);">
                                    <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="margin-right:3px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg> —
                                </span>
                            </div>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td>
                            @if($p->is_draft == 1)
                            <a href="{{ route('seminar.edit', $p->id) }}" class="btn-perbaiki">Lanjutkan<br>Administrasi</a>
                            @elseif($p->status_administrasi === 'Tidak Administrasi')
                            <a href="{{ route('seminar.show', $p->id) }}" class="btn-perbaiki">Lihat<br>Detail</a>

                            @elseif($p->status_administrasi === 'Lolos Administrasi' && $p->status_seminar === 'Belum Daftar Seminar')
                            <button class="btn-daftar" onclick="showPopupDaftar('{{ route('seminar.formDaftar', $p->id) }}')">
                                Daftar<br>Seminar
                            </button>

                            @elseif(in_array($p->status_seminar, ['Menunggu Jadwal', 'Jadwal ditetapkan', 'Selesai']))
                            <a href="{{ route('seminar.show', $p->id) }}" class="btn-lihat">Lihat Seminar</a>

                            @else
                            <a href="{{ route('seminar.show', $p->id) }}" class="btn-lihat">Lihat Detail</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="icon">📋</div>
                                <p>Belum ada pengajuan seminar.<br>Klik <strong>+ Ajukan Seminar</strong> untuk memulai.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- POPUP KONFIRMASI DAFTAR SEMINAR --}}
<div class="popup-overlay" id="popupDaftar">
    <div class="popup-box">
        <div class="popup-icon">🎓</div>
        <div class="popup-title">Daftar Seminar Sekarang?</div>
        <div class="popup-sub">
            Administrasi Anda telah <strong>Lolos Verifikasi</strong>.<br>
            Lanjutkan untuk mengisi form pendaftaran seminar tugas akhir.
        </div>
        <div class="popup-actions">
            <button class="btn-popup-cancel" onclick="closePopup()">Nanti Saja</button>
            <a href="#" id="btnLanjutDaftar" class="btn-popup-ok">Lanjut Daftar</a>
        </div>
    </div>
</div>

<script>
    function showPopupDaftar(url) {
        document.getElementById('btnLanjutDaftar').href = url;
        document.getElementById('popupDaftar').classList.add('show');
    }

    function closePopup() {
        document.getElementById('popupDaftar').classList.remove('show');
    }

    document.getElementById('popupDaftar').addEventListener('click', function(e) {
        if (e.target === this) closePopup();
    });

    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            document.querySelectorAll('.toast').forEach(t => t.classList.remove('show'));
        }, 3500);
    });
</script>

@endsection