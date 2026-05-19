@extends('layouts.app')

@section('title', 'Pengajuan Judul')

@section('content')

<style>
    :root{
        --gold:#C9A227;
        --gold-light:#FFF9E8;
        --border:#ECECEC;
        --bg:#F5F6FA;
        --text:#1E293B;
        --muted:#6B7280;
    }

    body{
        background:var(--bg);
    }

    .hero{
        background:url('{{ asset("images/bg.jpeg") }}');
        background-size:cover;
        background-position:center;
        border-radius:24px;
        padding:40px;
        margin-bottom:24px;
    }

    .hero h1{
        font-size:36px;
        font-weight:800;
        color:#7C5C00;
        line-height:1.2;
    }

    .hero p{
        margin-top:10px;
        color:#8B6B00;
        font-size:14px;
    }

    .stats{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
        gap:18px;
        margin-bottom:24px;
    }

    .stat-card{
        background:white;
        border-radius:18px;
        padding:20px;
        border:1px solid var(--border);
    }

    .stat-number{
        font-size:28px;
        font-weight:800;
    }

    .stat-label{
        font-size:13px;
        color:var(--muted);
    }

    .card{
        background:white;
        border-radius:22px;
        border:1px solid var(--border);
        overflow:hidden;
    }

    .filter-bar{
        padding:20px;
        display:flex;
        gap:14px;
        flex-wrap:wrap;
        border-bottom:1px solid var(--border);
    }

    .search-input,
    .filter-select{
        height:44px;
        border-radius:12px;
        border:1px solid var(--border);
        padding:0 14px;
    }

    .search-input{
        flex:1;
        min-width:280px;
    }

    table{
        width:100%;
        border-collapse:collapse;
    }

    thead{
        background:#FAFAFA;
    }

    th{
        padding:14px;
        font-size:12px;
        color:#6B7280;
    }

    td{
        padding:16px 14px;
        border-top:1px solid #F3F4F6;
        font-size:13px;
    }

    .badge{
        padding:6px 12px;
        border-radius:999px;
        font-size:11px;
        font-weight:700;
    }

    .badge-menunggu{
        background:#FEF3C7;
        color:#B45309;
    }

    .badge-disetujui{
        background:#DCFCE7;
        color:#15803D;
    }

    .badge-ditolak{
        background:#FEE2E2;
        color:#DC2626;
    }

    .btn-detail{
        padding:8px 14px;
        border-radius:10px;
        background:var(--gold-light);
        color:var(--gold);
        text-decoration:none;
        font-size:12px;
        font-weight:700;
    }
</style>

<div class="hero">

    <h1>
        Pengajuan Judul Tugas Akhir Mahasiswa
    </h1>

    <p>
        Monitoring dan verifikasi pengajuan judul tugas akhir mahasiswa.
    </p>

</div>

<div class="stats">

    <div class="stat-card">
        <div class="stat-number">
            {{ $totalPengajuan }}
        </div>

        <div class="stat-label">
            Total Pengajuan
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-number">
            {{ $menunggu }}
        </div>

        <div class="stat-label">
            Menunggu Verifikasi
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-number">
            {{ $disetujui }}
        </div>

        <div class="stat-label">
            Disetujui
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-number">
            {{ $ditolak }}
        </div>

        <div class="stat-label">
            Ditolak
        </div>
    </div>

</div>

<div class="card">

    <form method="GET" class="filter-bar">

        <input
            type="text"
            name="search"
            class="search-input"
            placeholder="Masukkan NIM, nama, atau judul..."
            value="{{ request('search') }}"
        >

        <select name="status" class="filter-select">

            <option value="">
                Semua Status
            </option>

            <option value="menunggu">
                Menunggu
            </option>

            <option value="disetujui">
                Disetujui
            </option>

            <option value="ditolak">
                Ditolak
            </option>

        </select>

        <button class="btn-detail">
            Filter
        </button>

    </form>

    <table>

        <thead>

            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>NIM</th>
                <th>Mahasiswa</th>
                <th>Judul</th>
                <th>Status</th>
                <th>Detail</th>
            </tr>

        </thead>

        <tbody>

            @forelse($pengajuanJudul as $i => $p)

            <tr>

                <td>
                    {{ $i + 1 }}
                </td>

                <td>
                    {{ \Carbon\Carbon::parse($p->created_at)->format('d M Y') }}
                </td>

                <td>
                    {{ $p->nim_nid }}
                </td>

                <td>
                    {{ $p->nama_mahasiswa }}
                </td>

                <td>
                    <div class="fw-semibold">
                        {{ $p->judul_1 }}
                    </div>

                    <small class="text-muted d-block">
                        {{ $p->judul_2 }}
                    </small>

                    <small class="text-muted d-block">
                        {{ $p->judul_3 }}
                    </small>
                </td>

                <td>

                    @if($p->status == 'menunggu')

                        <span class="badge badge-menunggu">
                            Menunggu
                        </span>

                    @elseif($p->status == 'disetujui')

                        <span class="badge badge-disetujui">
                            Disetujui
                        </span>

                    @elseif($p->status == 'ditolak')

                        <span class="badge badge-ditolak">
                            Ditolak
                        </span>

                    @endif

                </td>

                <td>

                    <a href="#" class="btn-detail">
                        Verifikasi
                    </a>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="7" align="center">

                    Belum ada pengajuan judul.

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection