@extends('layouts.app')

@section('title', 'Detail Pengajuan Judul')

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

    .detail-wrapper{
        padding:10px 0 30px;
    }

    .hero{
        background:url('{{ asset("images/bg.jpeg") }}');
        background-size:cover;
        background-position:center;
        border-radius:24px;
        padding:40px;
        margin-bottom:24px;
        position:relative;
        overflow:hidden;
    }

    .hero h1{
        font-size:34px;
        font-weight:800;
        color:#7C5C00;
        line-height:1.2;
        margin-bottom:10px;
    }

    .hero p{
        color:#8B6B00;
        font-size:14px;
        margin:0;
    }

    .content-grid{
        display:grid;
        grid-template-columns:1.3fr .7fr;
        gap:24px;
    }

    .card-box{
        background:white;
        border-radius:24px;
        border:1px solid var(--border);
        overflow:hidden;
    }

    .card-header{
        padding:22px 24px;
        border-bottom:1px solid #F1F1F1;
        display:flex;
        justify-content:space-between;
        align-items:center;
    }

    .card-title{
        font-size:18px;
        font-weight:800;
        color:var(--text);
    }

    .card-body{
        padding:24px;
    }

    .student-box{
        display:flex;
        align-items:center;
        gap:16px;
        margin-bottom:24px;
    }

    .avatar{
        width:60px;
        height:60px;
        border-radius:18px;
        background:var(--gold-light);
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:24px;
        font-weight:800;
        color:var(--gold);
    }

    .student-name{
        font-size:18px;
        font-weight:800;
        color:var(--text);
    }

    .student-sub{
        font-size:13px;
        color:var(--muted);
    }

    .info-group{
        margin-bottom:22px;
    }

    .info-label{
        font-size:12px;
        font-weight:700;
        color:var(--muted);
        margin-bottom:8px;
        text-transform:uppercase;
        letter-spacing:.4px;
    }

    .judul-box{
        background:#FAFAFA;
        border:1px solid #EFEFEF;
        border-radius:16px;
        padding:18px;
    }

    .judul-item{
        padding:14px 0;
        border-bottom:1px dashed #E5E7EB;
    }

    .judul-item:last-child{
        border-bottom:none;
        padding-bottom:0;
    }

    .judul-label{
        font-size:12px;
        color:var(--muted);
        margin-bottom:6px;
    }

    .judul-text{
        font-size:15px;
        font-weight:700;
        color:var(--text);
        line-height:1.6;
    }

    .badge{
        padding:8px 14px;
        border-radius:999px;
        font-size:12px;
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

    .side-box{
        background:white;
        border-radius:24px;
        border:1px solid var(--border);
        overflow:hidden;
    }

    .side-body{
        padding:24px;
    }

    .status-card{
        border-radius:18px;
        padding:20px;
        background:#FAFAFA;
        border:1px solid #EFEFEF;
        margin-bottom:20px;
    }

    .status-title{
        font-size:13px;
        color:var(--muted);
        margin-bottom:10px;
    }

    .status-value{
        font-size:20px;
        font-weight:800;
    }

    .btn-action{
        width:100%;
        height:48px;
        border:none;
        border-radius:14px;
        font-weight:700;
        font-size:14px;
        transition:.2s;
    }

    .btn-success-custom{
        background:#16A34A;
        color:white;
        margin-bottom:12px;
    }

    .btn-success-custom:hover{
        background:#15803D;
    }

    .btn-danger-custom{
        background:#DC2626;
        color:white;
    }

    .btn-danger-custom:hover{
        background:#B91C1C;
    }

    .btn-back{
        display:inline-flex;
        align-items:center;
        gap:10px;
        padding:12px 18px;
        border-radius:14px;
        background:white;
        border:1px solid var(--border);
        text-decoration:none;
        color:var(--text);
        font-size:13px;
        font-weight:700;
        margin-bottom:20px;
    }

    .btn-back:hover{
        border-color:var(--gold);
        color:var(--gold);
    }

    @media(max-width:992px){
        .content-grid{
            grid-template-columns:1fr;
        }

        .hero{
            padding:30px 24px;
        }

        .hero h1{
            font-size:28px;
        }
    }
</style>

<div class="detail-wrapper">

    <a href="{{ url()->previous() }}" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i>
        Kembali
    </a>

    <div class="hero">

        <h1>
            Detail Pengajuan Judul
        </h1>

        <p>
            Informasi lengkap pengajuan judul tugas akhir mahasiswa.
        </p>

    </div>

    <div class="content-grid">

        {{-- LEFT --}}
        <div class="card-box">

            <div class="card-header">

                <div class="card-title">
                    Informasi Mahasiswa
                </div>

                @if($pengajuan->status == 'menunggu verifikasi')

                    <span class="badge badge-menunggu">
                        Menunggu Verifikasi
                    </span>

                @elseif($pengajuan->status == 'disetujui')

                    <span class="badge badge-disetujui">
                        Disetujui
                    </span>

                @elseif($pengajuan->status == 'ditolak')

                    <span class="badge badge-ditolak">
                        Ditolak
                    </span>

                @endif

            </div>

            <div class="card-body">

                <div class="student-box">

                    <div class="avatar">
                        {{ strtoupper(substr($pengajuan->nama_mahasiswa,0,1)) }}
                    </div>

                    <div>

                        <div class="student-name">
                            {{ $pengajuan->nama_mahasiswa }}
                        </div>

                        <div class="student-sub">
                            {{ $pengajuan->nim_nid }}
                        </div>

                    </div>

                </div>

                <div class="info-group">

                    <div class="info-label">
                        Tanggal Pengajuan
                    </div>

                    <div class="fw-semibold">
                        {{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y') }}
                    </div>

                </div>

                <div class="info-group">

                    <div class="info-label">
                        Judul yang Diajukan
                    </div>

                    <div class="judul-box">

                        <div class="judul-item">

                            <div class="judul-label">
                                Judul 1
                            </div>

                            <div class="judul-text">
                                {{ $pengajuan->judul_1 }}
                            </div>

                        </div>

                        <div class="judul-item">

                            <div class="judul-label">
                                Judul 2
                            </div>

                            <div class="judul-text">
                                {{ $pengajuan->judul_2 }}
                            </div>

                        </div>

                        <div class="judul-item">

                            <div class="judul-label">
                                Judul 3
                            </div>

                            <div class="judul-text">
                                {{ $pengajuan->judul_3 }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- RIGHT --}}
        <div class="side-box">

            <div class="card-header">

                <div class="card-title">
                    Status Verifikasi
                </div>

            </div>

            <div class="side-body">

                <div class="status-card">

                    <div class="status-title">
                        Status Saat Ini
                    </div>

                    <div class="status-value">

                        @if($pengajuan->status == 'menunggu verifikasi')

                            <span style="color:#B45309">
                                Menunggu Verifikasi
                            </span>

                        @elseif($pengajuan->status == 'disetujui')

                            <span style="color:#15803D">
                                Disetujui
                            </span>

                        @elseif($pengajuan->status == 'ditolak')

                            <span style="color:#DC2626">
                                Ditolak
                            </span>

                        @endif

                    </div>

                </div>

                @if($pengajuan->status == 'menunggu verifikasi')

                    {{-- SETUJUI --}}
                    <form action="{{ route('admin.judul.proses', $pengajuan->id) }}"
                        method="POST">

                        @csrf

                        <input type="hidden"
                            name="status"
                            value="disetujui">

                        <input type="hidden"
                            name="judul_disetujui"
                            value="{{ $pengajuan->judul_1 }}">

                        <button type="submit"
                                class="btn-action btn-success-custom">

                            <i class="fa-solid fa-check me-2"></i>
                            Setujui Judul

                        </button>

                    </form>

                    {{-- TOLAK --}}
                    <form action="{{ route('admin.judul.proses', $pengajuan->id) }}"
                        method="POST">

                        @csrf

                        <input type="hidden"
                            name="status"
                            value="ditolak">

                        <button type="submit"
                                class="btn-action btn-danger-custom">

                            <i class="fa-solid fa-xmark me-2"></i>
                            Tolak Pengajuan

                        </button>

                    </form>

                @else

                    <div class="alert alert-light border rounded-4 text-center mb-0">

                        Pengajuan ini sudah selesai diverifikasi.

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection