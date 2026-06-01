@extends('layouts.app')

@section('content')

@php

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$nimSesi = session('user')->nim_nid;

$totalMahasiswa = DB::table('users')->where('role', 'mahasiswa')->count();
$totalDosen     = DB::table('users')->where('role', 'dosen')->count();
$totalPengajuan = DB::table('pengajuan_judul')->count();
$totalProposal  = DB::table('proposal')->count();
$totalBimbingan = DB::table('bimbingan')->distinct('nim_nid')->count('nim_nid');
$totalSeminar   = DB::table('jadwal_akademik')->where('kategori', 'Seminar')->count();

$pengajuanTerbaru = DB::table('pengajuan_judul as pj')
    ->join('users as u', 'pj.nim_nid', '=', 'u.nim_nid')
    ->select('pj.*', 'u.nama')
    ->orderBy('pj.created_at', 'desc')
    ->limit(5)
    ->get();

$proposalTerbaru = DB::table('proposal as p')
    ->join('users as u', 'p.nim_nid', '=', 'u.nim_nid')
    ->leftJoin('dosen_pembimbing as dp1', function ($join) {
        $join->on('p.id', '=', 'dp1.proposal_id')->where('dp1.urutan', 1);
    })
    ->leftJoin('users as dosen1', 'dp1.nim_nid_dosen', '=', 'dosen1.nim_nid')
    ->leftJoin('dosen_pembimbing as dp2', function ($join) {
        $join->on('p.id', '=', 'dp2.proposal_id')->where('dp2.urutan', 2);
    })
    ->leftJoin('users as dosen2', 'dp2.nim_nid_dosen', '=', 'dosen2.nim_nid')
    ->select('p.*', 'u.nama as nama_mahasiswa', 'dosen1.nama as pembimbing1', 'dosen2.nama as pembimbing2')
    ->orderBy('p.created_at', 'desc')
    ->limit(5)
    ->get();

$jadwalSeminar = DB::table('jadwal_akademik')
    ->where('kategori', 'Seminar')
    ->orderBy('tanggal', 'asc')
    ->limit(5)
    ->get();

$aktivitasTerbaru = DB::table('proposal as p')
    ->join('users as u', 'p.nim_nid', '=', 'u.nim_nid')
    ->select(
        DB::raw("'proposal' as tipe"),
        'u.nama',
        DB::raw("CONVERT(p.judul USING utf8mb4) COLLATE utf8mb4_unicode_ci as keterangan"),
        'p.created_at'
    )
    ->unionAll(
        DB::table('pengajuan_judul as pj')
            ->join('users as u', 'pj.nim_nid', '=', 'u.nim_nid')
            ->select(
                DB::raw("'pengajuan' as tipe"),
                'u.nama',
                DB::raw("CONVERT(pj.judul_1 USING utf8mb4) COLLATE utf8mb4_unicode_ci as keterangan"),
                'pj.created_at'
            )
    )
    ->orderByDesc('created_at')
    ->limit(8)
    ->get();

$adaPengajuanBaru = DB::table('pengajuan_judul')->where('status', 'menunggu verifikasi')->exists();
$adaProposalBaru  = DB::table('proposal')->where('status', 'menunggu_verifikasi')->exists();

@endphp

<style>
body {
    background: #f5f7fb;
    font-family: 'Hanken Grotesk', sans-serif;
}

/* ── HERO ── */
.hero-section {
    position: relative;
    overflow: hidden;
    border-radius: 20px;
    background-image: url('{{ asset('images/bg.jpeg') }}');
    background-size: cover;
    background-position: center;
    min-height: 220px;
    padding: 48px 48px;
    display: flex;
    align-items: center;
    margin-bottom: 28px;
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, rgba(255,255,255,0.55) 40%, rgba(255,255,255,0.05));
    z-index: 1;
}

.hero-content {
    position: relative;
    z-index: 2;
    color: #735C00;
}

.hero-content h1 {
    font-size: 36px;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 8px;
}

.hero-content p {
    font-size: 14px;
    color: #8a7000;
    margin: 0;
}

/* ── STAT CARDS ── */
.stat-row {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 14px;
    margin-bottom: 28px;
}

.stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 18px 16px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.stat-label {
    font-size: 11px;
    color: #94a3b8;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    line-height: 1.2;
}

.stat-number {
    font-size: 28px;
    font-weight: 800;
    color: #1e293b;
    line-height: 1;
}

/* ── DASHBOARD CARD ── */
.dash-card {
    background: #fff;
    border-radius: 18px;
    padding: 22px 24px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.05);
    margin-bottom: 22px;
    border: none;
}

.dash-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 10px;
}

.dash-card-header h5 {
    margin: 0;
    font-weight: 700;
    font-size: 15px;
    color: #1e293b;
}

.dash-card-header .header-right {
    display: flex;
    align-items: center;
    gap: 8px;
}

.search-box {
    display: flex;
    align-items: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 6px 12px;
    gap: 6px;
    font-size: 13px;
    color: #94a3b8;
}

.search-box input {
    border: none;
    background: transparent;
    outline: none;
    font-size: 13px;
    color: #1e293b;
    width: 130px;
    font-family: 'Hanken Grotesk', sans-serif;
}

.search-box input::placeholder {
    color: #94a3b8;
}

.btn-lihat-semua {
    background: #FACC15;
    color: #735C00;
    border: none;
    border-radius: 10px;
    padding: 6px 16px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
    transition: background 0.2s;
}

.btn-lihat-semua:hover {
    background: #f0bc00;
    color: #735C00;
}

/* ── TABLE ── */
.dash-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.dash-table thead tr {
    background: #f8fafc;
}

.dash-table thead th {
    padding: 10px 14px;
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    border: none;
    white-space: nowrap;
}

.dash-table tbody td {
    padding: 11px 14px;
    color: #334155;
    border-top: 1px solid #f1f5f9;
    vertical-align: middle;
}

.dash-table tbody tr:hover {
    background: #fafbff;
}

/* ── BADGE ── */
.badge-status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
    display: inline-block;
}

.badge-menunggu   { background: #fef9c3; color: #854d0e; }
.badge-disetujui  { background: #dcfce7; color: #166534; }
.badge-ditolak    { background: #fee2e2; color: #991b1b; }
.badge-review     { background: #dbeafe; color: #1e40af; }
.badge-selesai    { background: #dcfce7; color: #166534; }
.badge-berlangsung{ background: #dbeafe; color: #1e40af; }
.badge-akandatang { background: #fef9c3; color: #854d0e; }
.badge-baru       { background: #fee2e2; color: #991b1b; font-size: 11px; }

/* ── ACTIVITY ── */
.activity-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.activity-item {
    position: relative;
    padding: 0 0 24px 36px;
}

.activity-item:last-child {
    padding-bottom: 0;
}

.activity-item:last-child .activity-line {
    display: none;
}

.activity-dot {
    position: absolute;
    left: 0;
    top: 4px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #FACC15;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #FACC15;
    z-index: 2;
}

.activity-dot.dot-proposal  { background: #FACC15; box-shadow: 0 0 0 2px #FACC15; }
.activity-dot.dot-pengajuan { background: #60a5fa; box-shadow: 0 0 0 2px #60a5fa; }

.activity-line {
    position: absolute;
    left: 6px;
    top: 18px;
    width: 2px;
    height: calc(100% - 14px);
    background: #e2e8f0;
    z-index: 1;
}

.activity-time {
    font-size: 11px;
    color: #94a3b8;
    margin-bottom: 3px;
    font-weight: 600;
}

.activity-title {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 2px;
}

.activity-desc {
    font-size: 13px;
    color: #475569;
    line-height: 1.4;
}

.activity-sub {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 3px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.activity-link {
    font-size: 12px;
    color: #FACC15;
    font-weight: 700;
    text-decoration: none;
    margin-top: 4px;
    display: inline-block;
}

.activity-link:hover { color: #d4a00e; }

/* ── RESPONSIVE ── */
@media (max-width: 992px) {
    .stat-row { grid-template-columns: repeat(3, 1fr); }
}

@media (max-width: 576px) {
    .stat-row { grid-template-columns: repeat(2, 1fr); }
    .hero-content h1 { font-size: 24px; }
    .hero-section { padding: 32px 24px; }
}
</style>

<div class="container-fluid py-3">

    {{-- ══ HERO ══ --}}
    <div class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Dashboard Administrator<br>SITASI-TA</h1>
            <p>Sistem Informasi Tugas Akhir · Program Studi Sistem Informasi Unjani</p>
        </div>
    </div>

    {{-- ══ STATISTIK ══ --}}
    <div class="stat-row">

        <div class="stat-card">
            <div class="stat-icon bg-primary-subtle">
                <i class="fa-solid fa-users" style="color:#3b82f6;"></i>
            </div>
            <div class="stat-label">Total<br>Mahasiswa</div>
            <div class="stat-number">{{ $totalMahasiswa }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-success-subtle">
                <i class="fa-solid fa-chalkboard-user" style="color:#22c55e;"></i>
            </div>
            <div class="stat-label">Total<br>Dosen</div>
            <div class="stat-number">{{ $totalDosen }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;">
                <i class="fa-solid fa-file-lines" style="color:#d97706;"></i>
            </div>
            <div class="stat-label">Pengajuan<br>Judul Aktif</div>
            <div class="stat-number">{{ $totalPengajuan }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-info-subtle">
                <i class="fa-solid fa-folder-open" style="color:#0ea5e9;"></i>
            </div>
            <div class="stat-label">Proposal Menunggu<br>Review</div>
            <div class="stat-number">{{ $totalProposal }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-danger-subtle">
                <i class="fa-solid fa-comments" style="color:#ef4444;"></i>
            </div>
            <div class="stat-label">Mahasiswa Bimbingan<br>Aktif</div>
            <div class="stat-number">{{ $totalBimbingan }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-secondary-subtle">
                <i class="fa-solid fa-calendar-check" style="color:#6366f1;"></i>
            </div>
            <div class="stat-label">Pendaftaran Seminar<br>Aktif</div>
            <div class="stat-number">{{ $totalSeminar }}</div>
        </div>

    </div>


    <div class="row g-4">

        {{-- ══ LEFT CONTENT ══ --}}
        <div class="col-lg-8">

            {{-- PENGAJUAN --}}
            <div class="dash-card">
                <div class="dash-card-header">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <h5>Monitoring Pengajuan Judul</h5>
                        @if($adaPengajuanBaru)
                            <span class="badge-status badge-baru">Baru</span>
                        @endif
                    </div>
                    <div class="header-right">
                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass" style="font-size:12px;"></i>
                            <input type="text" placeholder="Cari judul..." id="searchPengajuan" onkeyup="filterTable('tablePengajuan','searchPengajuan')">
                        </div>
                        <a href="/admin/judul" class="btn-lihat-semua">Lihat Semua</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="dash-table" id="tablePengajuan">
                        <thead>
                            <tr>
                                <th>Nama Mahasiswa</th>
                                <th>Judul</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengajuanTerbaru as $item)
                            <tr>
                                <td style="font-weight:600;">{{ $item->nama }}</td>

                                @php
                                    $judulTampil = $item->status === 'disetujui'
                                        ? $item->judul_disetujui
                                        : $item->judul_1;
                                @endphp
                                <td style="max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"
                                    title="{{ $judulTampil }}">
                                    {{ $judulTampil }}
                                </td>

                                <td>
                                    @if($item->status == 'disetujui')
                                        <span class="badge-status badge-disetujui">Disetujui</span>
                                    @elseif($item->status == 'ditolak')
                                        <span class="badge-status badge-ditolak">Ditolak</span>
                                    @else
                                        <span class="badge-status badge-menunggu">Menunggu verifikasi</span>
                                    @endif
                                </td>
                                <td style="color:#94a3b8; font-size:12px;">{{ Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center" style="color:#94a3b8; padding:30px;">Tidak ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- PROPOSAL --}}
            <div class="dash-card">
                <div class="dash-card-header">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <h5>Monitoring Proposal</h5>
                        @if($adaProposalBaru)
                            <span class="badge-status badge-baru">Baru</span>
                        @endif
                    </div>
                    <div class="header-right">
                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass" style="font-size:12px;"></i>
                            <input type="text" placeholder="Cari judul..." id="searchProposal" onkeyup="filterTable('tableProposal','searchProposal')">
                        </div>
                        <a href="{{ route('admin.proposal.index') }}" class="btn-lihat-semua">Lihat Semua</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="dash-table" id="tableProposal">
                        <thead>
                            <tr>
                                <th>Nama Mahasiswa</th>
                                <th>Judul</th>
                                <th>Pembimbing 1</th>
                                <th>Pembimbing 2</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($proposalTerbaru as $item)
                            <tr>
                                <td style="font-weight:600;">{{ $item->nama_mahasiswa }}</td>
                                <td style="max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $item->judul }}">{{ $item->judul }}</td>
                                <td style="font-size:12px;">{{ $item->pembimbing1 ?? '-' }}</td>
                                <td style="font-size:12px;">{{ $item->pembimbing2 ?? '-' }}</td>
                                <td>
                                    @if($item->status == 'selesai')
                                        <span class="badge-status badge-selesai">Selesai</span>
                                    @elseif($item->status == 'ditolak')
                                        <span class="badge-status badge-ditolak">Ditolak</span>
                                    @elseif($item->status == 'menunggu_review')
                                        <span class="badge-status badge-review">Menunggu Review</span>
                                    @else
                                        <span class="badge-status badge-menunggu">Menunggu verifikasi</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center" style="color:#94a3b8; padding:30px;">Tidak ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SEMINAR --}}
            <div class="dash-card">
                <div class="dash-card-header">
                    <h5>Monitoring Pendaftaran Seminar</h5>
                    <div class="header-right">
                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass" style="font-size:12px;"></i>
                            <input type="text" placeholder="Cari nama..." id="searchSeminar" onkeyup="filterTable('tableSeminar','searchSeminar')">
                        </div>
                        <a href="{{ route('admin.seminar.index') }}" class="btn-lihat-semua">Lihat Semua</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="dash-table" id="tableSeminar">
                        <thead>
                            <tr>
                                <th>Kegiatan</th>
                                <th>Tanggal</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwalSeminar as $item)
                            <tr>
                                <td style="font-weight:600;">{{ $item->nama_kegiatan }}</td>
                                <td style="color:#94a3b8; font-size:12px;">{{ Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                <td style="font-size:12px;">{{ $item->lokasi }}</td>
                                <td>
                                    @if($item->status == 'Selesai')
                                        <span class="badge-status badge-selesai">Selesai</span>
                                    @elseif($item->status == 'Berlangsung')
                                        <span class="badge-status badge-berlangsung">Berlangsung</span>
                                    @elseif($item->status == 'Ditutup')
                                        <span class="badge-status badge-ditolak">Ditutup</span>
                                    @else
                                        <span class="badge-status badge-akandatang">Akan Datang</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center" style="color:#94a3b8; padding:30px;">Tidak ada jadwal</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>


        {{-- ══ RIGHT: AKTIVITAS TERBARU ══ --}}
        <div class="col-lg-4">
            <div class="dash-card" style="position:sticky; top:80px;">
                <div class="dash-card-header" style="margin-bottom:20px;">
                    <h5>Aktivitas Terbaru</h5>
                </div>

                <div class="activity-list">
                    @forelse($aktivitasTerbaru as $item)
                    <div class="activity-item">
                        <div class="activity-line"></div>
                        <div class="activity-dot {{ $item->tipe == 'proposal' ? 'dot-proposal' : 'dot-pengajuan' }}"></div>
                        <div class="activity-time">{{ Carbon::parse($item->created_at)->diffForHumans() }}</div>
                        <div class="activity-title">
                            @if($item->tipe == 'proposal')
                                {{ $item->nama }} mengunggah proposal
                            @else
                                {{ $item->nama }} mengajukan judul baru
                            @endif
                        </div>
                        <div class="activity-sub">{{ $item->keterangan }}</div>
                        @if($item->tipe == 'proposal')
                            <a href="{{ route('admin.proposal.index') }}" class="activity-link">Lihat Berkas</a>
                        @endif
                    </div>
                    @empty
                    <p style="color:#94a3b8; font-size:13px;">Tidak ada aktivitas terbaru.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function filterTable(tableId, inputId) {
    const input  = document.getElementById(inputId).value.toLowerCase();
    const rows   = document.getElementById(tableId).getElementsByTagName('tr');
    for (let i = 1; i < rows.length; i++) {
        const text = rows[i].textContent.toLowerCase();
        rows[i].style.display = text.includes(input) ? '' : 'none';
    }
}
</script>

@endsection