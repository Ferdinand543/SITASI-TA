@extends('layouts.app')

@section('content')

@php

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$nimSesi      = session('user')->nim_nid;

/*
|--------------------------------------------------------------------------
| STATISTIK
|--------------------------------------------------------------------------
*/

$totalMahasiswa = DB::table('users')
    ->where('role', 'mahasiswa')
    ->count();

$totalDosen = DB::table('users')
    ->where('role', 'dosen')
    ->count();

$totalPengajuan = DB::table('pengajuan_judul')->count();

$totalProposal = DB::table('proposal')->count();

$totalBimbingan = DB::table('bimbingan')->count();

$totalSeminar = DB::table('jadwal_akademik')
    ->where('kategori', 'Seminar')
    ->count();


/*
|--------------------------------------------------------------------------
| PENGAJUAN TERBARU
|--------------------------------------------------------------------------
*/

$pengajuanTerbaru = DB::table('pengajuan_judul as pj')
    ->join('users as u', 'pj.nim_nid', '=', 'u.nim_nid')
    ->select(
        'pj.*',
        'u.nama'
    )
    ->orderBy('pj.created_at', 'desc')
    ->limit(5)
    ->get();


/*
|--------------------------------------------------------------------------
| PROPOSAL TERBARU
|--------------------------------------------------------------------------
*/

$proposalTerbaru = DB::table('proposal as p')

    ->join('users as u', 'p.nim_nid', '=', 'u.nim_nid')

    ->leftJoin('dosen_pembimbing as dp1', function ($join) {
        $join->on('p.id', '=', 'dp1.proposal_id')
             ->where('dp1.urutan', 1);
    })

    ->leftJoin('users as dosen1', 'dp1.nim_nid_dosen', '=', 'dosen1.nim_nid')

    ->leftJoin('dosen_pembimbing as dp2', function ($join) {
        $join->on('p.id', '=', 'dp2.proposal_id')
             ->where('dp2.urutan', 2);
    })

    ->leftJoin('users as dosen2', 'dp2.nim_nid_dosen', '=', 'dosen2.nim_nid')

    ->select(
        'p.*',
        'u.nama as nama_mahasiswa',
        'dosen1.nama as pembimbing1',
        'dosen2.nama as pembimbing2'
    )

    ->orderBy('p.created_at', 'desc')
    ->limit(5)
    ->get();


/*
|--------------------------------------------------------------------------
| JADWAL SEMINAR
|--------------------------------------------------------------------------
*/

$jadwalSeminar = DB::table('jadwal_akademik')
    ->where('kategori', 'Seminar')
    ->orderBy('tanggal', 'asc')
    ->limit(5)
    ->get();


/*
|--------------------------------------------------------------------------
| AKTIVITAS TERBARU
|--------------------------------------------------------------------------
*/

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
        ->limit(5)
        ->get();

/*
|--------------------------------------------------------------------------
| NOTIFIKASI
|--------------------------------------------------------------------------
*/

$adaPengajuanBaru = DB::table('pengajuan_judul')
    ->where('status', 'menunggu verifikasi')
    ->exists();

$adaProposalBaru = DB::table('proposal')
    ->where('status', 'menunggu_verifikasi')
    ->exists();

@endphp


<div class="container-fluid py-4">

    {{-- HERO --}}
    <div class="hero-section mb-4">

        <div class="hero-overlay"></div>

        <div class="hero-content">

            <h1>
                Dashboard Administrator
            </h1>

            <p>
                Sistem Informasi Tugas Akhir Mahasiswa
                Program Studi Sistem Informasi
            </p>

        </div>

    </div>


    {{-- STATISTIK --}}
    <div class="row g-4 mb-4">

        <div class="col-md-2">

            <div class="stat-card">

                <div class="stat-icon bg-primary-subtle">
                    <i class="bi bi-people-fill"></i>
                </div>

                <div>
                    <h3>{{ $totalMahasiswa }}</h3>
                    <span>Mahasiswa</span>
                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="stat-card">

                <div class="stat-icon bg-success-subtle">
                    <i class="bi bi-person-badge-fill"></i>
                </div>

                <div>
                    <h3>{{ $totalDosen }}</h3>
                    <span>Dosen</span>
                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="stat-card">

                <div class="stat-icon bg-warning-subtle">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>

                <div>
                    <h3>{{ $totalPengajuan }}</h3>
                    <span>Pengajuan</span>
                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="stat-card">

                <div class="stat-icon bg-info-subtle">
                    <i class="bi bi-folder-fill"></i>
                </div>

                <div>
                    <h3>{{ $totalProposal }}</h3>
                    <span>Proposal</span>
                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="stat-card">

                <div class="stat-icon bg-danger-subtle">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>

                <div>
                    <h3>{{ $totalBimbingan }}</h3>
                    <span>Bimbingan</span>
                </div>

            </div>

        </div>

        <div class="col-md-2">

            <div class="stat-card">

                <div class="stat-icon bg-secondary-subtle">
                    <i class="bi bi-calendar-event-fill"></i>
                </div>

                <div>
                    <h3>{{ $totalSeminar }}</h3>
                    <span>Seminar</span>
                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        {{-- LEFT CONTENT --}}
        <div class="col-lg-8">

            {{-- PENGAJUAN --}}
            <div class="card dashboard-card mb-4">

                <div class="card-header-custom">

                    <h5>
                        Monitoring Pengajuan Judul
                    </h5>

                    @if($adaPengajuanBaru)
                        <span class="badge bg-danger">
                            Baru
                        </span>
                    @endif

                </div>

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>
                                <th>Mahasiswa</th>
                                <th>Judul</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($pengajuanTerbaru as $item)

                                <tr>

                                    <td>
                                        {{ $item->nama }}
                                    </td>

                                    <td>
                                        {{ $item->judul_1 }}
                                    </td>

                                    <td>

                                        @if($item->status == 'disetujui')

                                            <span class="badge bg-success">
                                                Disetujui
                                            </span>

                                        @elseif($item->status == 'ditolak')

                                            <span class="badge bg-danger">
                                                Ditolak
                                            </span>

                                        @else

                                            <span class="badge bg-warning text-dark">
                                                Menunggu
                                            </span>

                                        @endif

                                    </td>

                                    <td>
                                        {{ Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4" class="text-center">
                                        Tidak ada data
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- PROPOSAL --}}
            <div class="card dashboard-card mb-4">

                <div class="card-header-custom">

                    <h5>
                        Monitoring Proposal
                    </h5>

                    @if($adaProposalBaru)
                        <span class="badge bg-danger">
                            Baru
                        </span>
                    @endif

                </div>

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>
                                <th>Mahasiswa</th>
                                <th>Judul</th>
                                <th>Pembimbing</th>
                                <th>Status</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($proposalTerbaru as $item)

                                <tr>

                                    <td>
                                        {{ $item->nama_mahasiswa }}
                                    </td>

                                    <td>
                                        {{ $item->judul }}
                                    </td>

                                    <td>

                                        <div>
                                            <small>
                                                {{ $item->pembimbing1 ?? '-' }}
                                            </small>
                                        </div>

                                        <div>
                                            <small>
                                                {{ $item->pembimbing2 ?? '-' }}
                                            </small>
                                        </div>

                                    </td>

                                    <td>

                                        @if($item->status == 'selesai')

                                            <span class="badge bg-success">
                                                Selesai
                                            </span>

                                        @elseif($item->status == 'ditolak')

                                            <span class="badge bg-danger">
                                                Ditolak
                                            </span>

                                        @elseif($item->status == 'menunggu_review')

                                            <span class="badge bg-info">
                                                Review
                                            </span>

                                        @else

                                            <span class="badge bg-warning text-dark">
                                                Verifikasi
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4" class="text-center">
                                        Tidak ada data
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- SEMINAR --}}
            <div class="card dashboard-card">

                <div class="card-header-custom">

                    <h5>
                        Jadwal Seminar
                    </h5>

                </div>

                <div class="table-responsive">

                    <table class="table align-middle">

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

                                    <td>
                                        {{ $item->nama_kegiatan }}
                                    </td>

                                    <td>
                                        {{ Carbon::parse($item->tanggal)->format('d M Y') }}
                                    </td>

                                    <td>
                                        {{ $item->lokasi }}
                                    </td>

                                    <td>

                                        @if($item->status == 'Selesai')

                                            <span class="badge bg-success">
                                                Selesai
                                            </span>

                                        @elseif($item->status == 'Berlangsung')

                                            <span class="badge bg-primary">
                                                Berlangsung
                                            </span>

                                        @elseif($item->status == 'Ditutup')

                                            <span class="badge bg-danger">
                                                Ditutup
                                            </span>

                                        @else

                                            <span class="badge bg-warning text-dark">
                                                Akan Datang
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4" class="text-center">
                                        Tidak ada jadwal
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- RIGHT SIDEBAR --}}
        <div class="col-lg-4">

            <div class="card dashboard-card h-100">

                <div class="card-header-custom">
                    <h5>Aktivitas Terbaru</h5>
                </div>

                <div class="activity-wrapper">

                    @forelse($aktivitasTerbaru as $item)

                        <div class="activity-item">

                            <div class="activity-line"></div>

                            <div class="activity-dot"></div>

                            <div class="activity-content">

                                <div class="activity-title">

                                    @if($item->tipe == 'proposal')
                                        Upload Proposal
                                    @else
                                        Pengajuan Judul
                                    @endif

                                </div>

                                <div class="activity-desc">
                                    {{ $item->nama }}
                                </div>

                                <div class="activity-sub">
                                    {{ $item->keterangan }}
                                </div>

                                <div class="activity-time">
                                    {{ Carbon::parse($item->created_at)->diffForHumans() }}
                                </div>

                            </div>

                        </div>

                    @empty

                        <p class="text-muted">
                            Tidak ada aktivitas
                        </p>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>



<style>

body{
    background:#f5f7fb;
    font-family:'Hanken Grotesk', sans-serif;
}

.hero-section{
    position:relative;
    overflow:hidden;
    border-radius:24px;
    background-image:url('{{ asset('images/bg.jpeg') }}');
    background-size:cover;
    background-position:center;
    min-height:250px;
    padding:50px;
    display:flex;
    align-items:center;
}


.hero-content{
    position:relative;
    z-index:2;
    color:#735C00; 
}

.hero-content h1{
    font-size:42px;
    font-weight:800;
}

.hero-content p{
    max-width:600px;
    margin-top:15px;
    font-size:15px;
}

.stat-card{
    background:#fff;
    border-radius:18px;
    padding:20px;
    display:flex;
    align-items:center;
    gap:15px;
    height:100%;
    box-shadow:0 4px 15px rgba(0,0,0,0.04);
}

.stat-icon{
    width:55px;
    height:55px;
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
}

.dashboard-card{
    border:none;
    border-radius:20px;
    padding:25px;
    box-shadow:0 4px 20px rgba(0,0,0,0.04);
}

.card-header-custom{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.card-header-custom h5{
    margin:0;
    font-weight:700;
}

.table th{
    border:none;
    color:#64748b;
    font-size:14px;
}

.table td{
    vertical-align:middle;
    border-color:#f1f5f9;
}

.activity-wrapper{
    position:relative;
}

.activity-item{
    position:relative;
    padding-left:35px;
    margin-bottom:30px;
}

.activity-dot{
    position:absolute;
    left:0;
    top:6px;
    width:14px;
    height:14px;
    border-radius:50%;
    background:#facc15;
    z-index:2;
}

.activity-line{
    position:absolute;
    left:6px;
    top:20px;
    width:2px;
    height:100%;
    background:#e2e8f0;
}

.activity-title{
    font-weight:700;
    font-size:14px;
}

.activity-desc{
    font-size:14px;
    color:#0f172a;
}

.activity-sub{
    font-size:13px;
    color:#64748b;
    margin-top:2px;
}

.activity-time{
    font-size:12px;
    color:#94a3b8;
    margin-top:5px;
}

</style>

@endsection