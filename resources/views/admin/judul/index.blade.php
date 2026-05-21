@extends('layouts.app')

@section('title', 'Pengajuan Judul')

@section('content')

<style>
    :root{
        --gold:#C9A227;
        --gold-soft:#FFF8E7;
        --border:#ECECEC;
        --bg:#F5F6FA;
        --text:#1E293B;
        --muted:#6B7280;
        --green:#22C55E;
        --red:#EF4444;
        --yellow:#FACC15;
    }

    body{
        background:var(--bg);
    }

    /* HERO */
    .hero{
        background:
            linear-gradient(to right, rgba(255,255,255,.95), rgba(255,255,255,.85)),
            url('{{ asset("images/bg.jpeg") }}');
        background-size:cover;
        background-position:center;
        border-radius:24px;
        padding:42px;
        margin-bottom:24px;
        position:relative;
        overflow:hidden;
        border:1px solid #F3E7BA;
    }

    .hero::after{
        content:'';
        position:absolute;
        right:-40px;
        top:-20px;
        width:280px;
        height:280px;
        background:linear-gradient(180deg,#FFD84D,#F6C400);
        border-radius:50%;
        opacity:.15;
    }

    .hero h1{
        font-size:38px;
        font-weight:800;
        color:#7C5C00;
        line-height:1.1;
        margin-bottom:10px;
        position:relative;
        z-index:2;
    }

    .hero p{
        color:#8B6B00;
        font-size:14px;
        position:relative;
        z-index:2;
    }

    /* STATS */
    .stats{
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:18px;
        margin-bottom:24px;
    }

    .stat-card{
        background:white;
        border-radius:16px;
        padding:20px;
        border:1px solid var(--border);
        position:relative;
    }

    .stat-card::before{
        content:'';
        position:absolute;
        left:0;
        top:18px;
        width:4px;
        height:42px;
        border-radius:20px;
    }

    .stat-card:nth-child(1)::before{
        background:var(--gold);
    }

    .stat-card:nth-child(2)::before{
        background:var(--yellow);
    }

    .stat-card:nth-child(3)::before{
        background:var(--green);
    }

    .stat-card:nth-child(4)::before{
        background:var(--red);
    }

    .stat-label{
        font-size:12px;
        color:var(--muted);
        margin-bottom:6px;
        padding-left:10px;
    }

    .stat-number{
        font-size:30px;
        font-weight:800;
        padding-left:10px;
    }

    /* CARD TABLE */
    .table-card{
        background:white;
        border-radius:22px;
        border:1px solid var(--border);
        overflow:hidden;
    }

    /* FILTER */
    .filter-bar{
        padding:18px 20px;
        display:flex;
        gap:12px;
        align-items:center;
        border-bottom:1px solid var(--border);
    }

    .search-box{
        flex:1;
        position:relative;
    }

    .search-box i{
        position:absolute;
        left:14px;
        top:50%;
        transform:translateY(-50%);
        color:#9CA3AF;
        font-size:13px;
    }

    .search-input{
        width:100%;
        height:42px;
        border-radius:10px;
        border:1px solid var(--border);
        padding:0 14px 0 38px;
        font-size:13px;
    }

    .filter-select{
        height:42px;
        border-radius:10px;
        border:1px solid var(--border);
        padding:0 14px;
        font-size:13px;
        min-width:170px;
        background:white;
    }
    .filter-select:focus,
    .search-input:focus,
    .btn-filter:focus{
        outline: none;
        box-shadow: none;
        border-color: var(--border);
    }

    .btn-filter{
        height:42px;
        padding:0 18px;
        border:none;
        border-radius:10px;
        background:#F9FAFB;
        font-size:13px;
        font-weight:600;
        color:#6B7280;
    }

    /* TABLE */
    table{
        width:100%;
        border-collapse:collapse;
    }

    thead{
        background:#F8F6EF;
    }

    th{
        padding:16px;
        font-size:12px;
        color:#6B7280;
        font-weight:700;
        text-align:left;
    }

    td{
        padding:18px 16px;
        border-top:1px solid #F3F4F6;
        font-size:13px;
        vertical-align:top;
    }

    tbody tr:hover{
        background:#FCFCFC;
    }

    .judul-main{
        font-weight:700;
        color:var(--text);
        margin-bottom:4px;
        line-height:1.5;
    }

    .judul-more{
        color:#9CA3AF;
        font-size:11px;
    }

    /* BADGE */
    .badge-status{
        padding:6px 12px;
        border-radius:999px;
        font-size:11px;
        font-weight:700;
        display:inline-block;
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

    /* BUTTON */
    .btn-verifikasi{
        border:none;
        background:#FFF7D6;
        color:#B78A00;
        font-size:11px;
        font-weight:700;
        padding:7px 16px;
        border-radius:8px;
        transition:.2s;
    }

    .btn-verifikasi:hover{
        background:var(--gold);
        color:white;
    }

    .btn-detail{
        border:none;
        background:#F3F4F6;
        color:#6B7280;
        font-size:11px;
        font-weight:700;
        padding:7px 16px;
        border-radius:8px;
    }

    @media(max-width:1100px){
        .stats{
            grid-template-columns:repeat(2,1fr);
        }
    }

    @media(max-width:768px){

        .hero{
            padding:28px;
        }

        .hero h1{
            font-size:28px;
        }

        .stats{
            grid-template-columns:1fr;
        }

        .filter-bar{
            flex-direction:column;
            align-items:stretch;
        }

        .filter-select,
        .btn-filter{
            width:100%;
        }

        table{
            min-width:900px;
        }

        .table-card{
            overflow-x:auto;
        }
    }
</style>

<div class="hero">

    <h1>
        Pengajuan Judul Tugas Akhir <br>
        Mahasiswa
    </h1>

    <p>
        Monitoring dan verifikasi pengajuan judul tugas akhir mahasiswa.
    </p>

</div>

<div class="stats">

    <div class="stat-card">
        <div class="stat-label">
            Total Pengajuan
        </div>

        <div class="stat-number">
            {{ $totalPengajuan }}
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">
            Menunggu Verifikasi
        </div>

        <div class="stat-number">
            {{ $menunggu }}
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">
            Disetujui
        </div>

        <div class="stat-number">
            {{ $disetujui }}
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">
            Ditolak
        </div>

        <div class="stat-number">
            {{ $ditolak }}
        </div>
    </div>

</div>

<div class="table-card">

    <form method="GET" class="filter-bar">

        <div class="search-box">

            <i class="fa-solid fa-magnifying-glass"></i>

            <input
                type="text"
                name="search"
                class="search-input"
                placeholder="Masukkan NIM, nama, atau kata kunci judul..."
                value="{{ request('search') }}"
            >

        </div>

        <select name="status" class="filter-select">

            <option value="">
                Semua Status
            </option>

            <option value="menunggu verifikasi">
                Menunggu Verifikasi
            </option>

            <option value="disetujui">
                Disetujui
            </option>

            <option value="ditolak">
                Ditolak
            </option>

        </select>

        <button type="submit" class="btn-filter">

            <i class="fa-solid fa-rotate-right me-1"></i>
            Reset Filter

        </button>

    </form>

    <table>

        <thead>

            <tr>
                <th>No.</th>
                <th>Tanggal Pengajuan</th>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Judul yang Diajukan</th>
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

                    <div class="judul-main">
                        {{ $p->judul_1 }}
                    </div>

                    <div class="judul-more">
                        +2 lainnya
                    </div>

                </td>

                <td>

                {{-- BADGE STATUS --}}
                @if($p->status == 'menunggu verifikasi')

                    <span class="badge-status badge-menunggu">
                        Menunggu
                    </span>

                @elseif($p->status == 'disetujui')

                    <span class="badge-status badge-disetujui">
                        Disetujui
                    </span>

                @elseif($p->status == 'ditolak')

                    <span class="badge-status badge-ditolak">
                        Ditolak
                    </span>

                @endif

            </td>

            <td>

                @if($p->status == 'menunggu verifikasi')

                    <div class="d-flex gap-2">

                        <button
                            type="button"
                            class="btn-verifikasi"
                            data-bs-toggle="modal"
                            data-bs-target="#verifikasiModal{{ $p->id }}">

                            Verifikasi

                        </button>

                        <a href="{{ route('admin.judul.show', $p->id) }}"
                            class="btn-detail text-decoration-none">

                            Detail

                        </a>

                    </div>

                @else

                    <a href="{{ route('admin.judul.show', $p->id) }}"
                        class="btn-detail text-decoration-none">

                        Detail

                    </a>

                @endif

            </td>

            </tr>

            {{-- MODAL VERIFIKASI --}}
            <div class="modal fade"
                id="verifikasiModal{{ $p->id }}"
                tabindex="-1"
                aria-hidden="true">

                <div class="modal-dialog modal-lg modal-dialog-centered">

                    <div class="modal-content border-0 rounded-4">

                        <div class="modal-header border-0">

                            <h5 class="fw-bold">
                                Verifikasi Pengajuan Judul
                            </h5>

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <div class="mb-4">

                                <div class="fw-bold fs-5">
                                    {{ $p->nama_mahasiswa }}
                                </div>

                                <div class="text-muted">
                                    {{ $p->nim_nid }}
                                </div>

                            </div>

                            <div class="mb-3">

                                <label class="fw-bold mb-2">
                                    Judul 1
                                </label>

                                <div class="border rounded-3 p-3 bg-light">
                                    {{ $p->judul_1 }}
                                </div>

                            </div>

                            <div class="mb-3">

                                <label class="fw-bold mb-2">
                                    Judul 2
                                </label>

                                <div class="border rounded-3 p-3 bg-light">
                                    {{ $p->judul_2 }}
                                </div>

                            </div>

                            <div class="mb-3">

                                <label class="fw-bold mb-2">
                                    Judul 3
                                </label>

                                <div class="border rounded-3 p-3 bg-light">
                                    {{ $p->judul_3 }}
                                </div>

                            </div>

                        </div>

                        <div class="modal-footer border-0 d-flex justify-content-between">

                            {{-- TOLAK --}}
                            <form action="{{ route('admin.judul.proses', $p->id) }}"
                                method="POST">

                                @csrf

                                <input type="hidden"
                                    name="status"
                                    value="ditolak">

                                <button type="submit"
                                        class="btn btn-danger rounded-3 px-4">

                                    Tolak

                                </button>

                            </form>

                            {{-- SETUJUI --}}
                            <form action="{{ route('admin.judul.proses', $p->id) }}"
                                method="POST"
                                class="d-flex align-items-center gap-2">

                                @csrf

                                <input type="hidden"
                                    name="status"
                                    value="disetujui">

                                {{-- PILIH JUDUL --}}
                                <select name="judul_disetujui"
                                        class="form-select"
                                        required>

                                    <option value="">
                                        Pilih Judul Disetujui
                                    </option>

                                    <option value="{{ $p->judul_1 }}">
                                        Judul 1 - {{ $p->judul_1 }}
                                    </option>

                                    <option value="{{ $p->judul_2 }}">
                                        Judul 2 - {{ $p->judul_2 }}
                                    </option>

                                    <option value="{{ $p->judul_3 }}">
                                        Judul 3 - {{ $p->judul_3 }}
                                    </option>

                                </select>

                                <button type="submit"
                                        class="btn btn-success rounded-3 px-4">

                                    Setujui

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

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