@extends('layouts.app')

@section('title', 'Detail Pengajuan Proposal')

@section('content')
<style>
    :root{
        --gold:#C9A227;
        --gold-light:#FEF9EC;
        --gold-border:#F5D97A;
        --neutral:#1E293B;
        --muted:#6B7280;
        --border:#E5E7EB;
        --bg:#F5F6FA;
        --white:#ffffff;
        --radius:18px;
    }

    .detail-wrap{
        background:var(--bg);
        min-height:100vh;
    }

    .hero{
        background-image:url('{{ asset("images/bg.jpeg") }}');
        background-size:cover;
        background-position:center;
        border-radius:24px;
        padding:36px 40px;
        position:relative;
        overflow:hidden;
        margin-bottom:24px;
    }

    .hero::before{
        content:'';
        position:absolute;
        right:-60px;
        top:-60px;
        width:220px;
        height:220px;
        background:rgba(201,162,39,.12);
        border-radius:50%;
    }

    .hero-title{
        font-size:30px;
        font-weight:800;
        color:#735C00;
        margin-bottom:8px;
    }

    .hero-sub{
        font-size:14px;
        color:#92400E;
        max-width:700px;
    }

    .grid{
        display:grid;
        grid-template-columns: 1.2fr .8fr;
        gap:24px;
    }

    .card-box{
        background:var(--white);
        border-radius:var(--radius);
        border:1px solid var(--border);
        box-shadow:0 2px 10px rgba(0,0,0,.04);
        overflow:hidden;
    }

    .card-header{
        padding:18px 24px;
        border-bottom:1px solid var(--border);
        display:flex;
        align-items:center;
        justify-content:space-between;
    }

    .card-title{
        font-size:16px;
        font-weight:800;
        color:var(--neutral);
    }

    .card-body{
        padding:24px;
    }

    .info-group{
        margin-bottom:22px;
    }

    .info-label{
        font-size:11px;
        font-weight:700;
        text-transform:uppercase;
        letter-spacing:.5px;
        color:var(--muted);
        margin-bottom:8px;
    }

    .info-value{
        font-size:14px;
        color:var(--neutral);
        line-height:1.7;
        font-weight:500;
    }

    .mhs-wrap{
        display:flex;
        align-items:center;
        gap:14px;
    }

    .avatar{
        width:52px;
        height:52px;
        border-radius:14px;
        background:var(--gold-light);
        border:2px solid var(--gold-border);
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:20px;
        font-weight:800;
        color:var(--gold);
    }

    .mhs-name{
        font-size:16px;
        font-weight:800;
        color:var(--neutral);
        margin-bottom:2px;
    }

    .mhs-sub{
        font-size:13px;
        color:var(--muted);
    }

    .badge{
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:7px 14px;
        border-radius:999px;
        font-size:12px;
        font-weight:700;
    }

    .badge-pending{
        background:#FEF9EC;
        color:#B45309;
        border:1px solid var(--gold-border);
    }

    .badge-review{
        background:#DBEAFE;
        color:#1D4ED8;
        border:1px solid #BFDBFE;
    }

    .badge-approved{
        background:#ECFDF5;
        color:#047857;
        border:1px solid #A7F3D0;
    }

    .badge-rejected{
        background:#FEF2F2;
        color:#DC2626;
        border:1px solid #FECACA;
    }

    .proposal-box{
        background:#FAFAFA;
        border:1px solid var(--border);
        border-radius:14px;
        padding:18px;
    }

    .proposal-title{
        font-size:16px;
        font-weight:700;
        color:var(--neutral);
        line-height:1.6;
    }

    .file-card{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        padding:16px;
        border-radius:14px;
        border:1px solid #FECACA;
        background:#FEF2F2;
    }

    .file-left{
        display:flex;
        align-items:center;
        gap:12px;
    }

    .file-icon{
        width:42px;
        height:42px;
        border-radius:12px;
        background:#fff;
        display:flex;
        align-items:center;
        justify-content:center;
        color:#DC2626;
        font-size:18px;
    }

    .file-name{
        font-size:13px;
        font-weight:700;
        color:#991B1B;
    }

    .file-size{
        font-size:11px;
        color:#B91C1C;
        margin-top:2px;
    }

    .btn-preview{
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding:10px 18px;
        border-radius:10px;
        border:none;
        background:var(--gold);
        color:#fff;
        font-size:13px;
        font-weight:700;
        text-decoration:none;
        transition:.2s;
    }

    .btn-preview:hover{
        background:#B68F18;
        color:#fff;
    }

    .review-box{
        background:#FFFBEB;
        border:1px solid #FDE68A;
        border-radius:14px;
        padding:18px;
    }

    .review-text{
        font-size:13px;
        line-height:1.8;
        color:#78350F;
    }

    .action-wrap{
        display:flex;
        gap:12px;
        flex-wrap:wrap;
        margin-top:18px;
    }

    .btn-action{
        padding:11px 20px;
        border-radius:10px;
        border:none;
        font-size:13px;
        font-weight:700;
        cursor:pointer;
        transition:.2s;
    }

    .btn-approve{
        background:#16A34A;
        color:#fff;
    }

    .btn-approve:hover{
        background:#15803D;
    }

    .btn-reject{
        background:#DC2626;
        color:#fff;
    }

    .btn-reject:hover{
        background:#B91C1C;
    }

    .btn-back{
        display:inline-flex;
        align-items:center;
        gap:8px;
        margin-bottom:20px;
        padding:10px 18px;
        border-radius:10px;
        background:#fff;
        border:1px solid var(--border);
        text-decoration:none;
        color:var(--neutral);
        font-size:13px;
        font-weight:700;
    }

    .btn-back:hover{
        border-color:var(--gold);
        color:var(--gold);
    }

    @media(max-width:900px){
        .grid{
            grid-template-columns:1fr;
        }

        .hero{
            padding:28px 24px;
        }

        .hero-title{
            font-size:24px;
        }
    }
</style>

<div class="detail-wrap">

    <a href="{{ url('/admin/proposal') }}" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i>
        Kembali ke Riwayat Proposal
    </a>

    <div class="hero">
        <div class="hero-title">Detail Pengajuan Proposal</div>
        <div class="hero-sub">
            Informasi lengkap proposal tugas akhir mahasiswa beserta status review proposal.
        </div>
    </div>

    <div class="grid">

        {{-- LEFT --}}
        <div>

            <div class="card-box">

                <div class="card-header">
                    <div class="card-title">Informasi Mahasiswa</div>

                    @if($proposal->status == 'pending')
                        <span class="badge badge-pending">
                            ⏳ Menunggu Review
                        </span>

                    @elseif($proposal->status == 'direview')
                        <span class="badge badge-review">
                            📄 Sedang Direview
                        </span>

                    @elseif($proposal->status == 'disetujui')
                        <span class="badge badge-approved">
                            ✓ Disetujui
                        </span>

                    @elseif($proposal->status == 'ditolak')
                        <span class="badge badge-rejected">
                            ✕ Ditolak
                        </span>
                    @endif
                </div>

                <div class="card-body">

                    <div class="mhs-wrap">
                        <div class="avatar">
                            {{ strtoupper(substr($proposal->nama_mahasiswa,0,1)) }}
                        </div>

                        <div>
                            <div class="mhs-name">
                                {{ $proposal->nama_mahasiswa }}
                            </div>

                            <div class="mhs-sub">
                                {{ $proposal->nim_nid }}
                            </div>
                        </div>
                    </div>

                    <hr style="margin:24px 0;border-color:#F3F4F6;">

                    <div class="info-group">
                        <div class="info-label">Tanggal Pengajuan</div>
                        <div class="info-value">
                            {{ \Carbon\Carbon::parse($proposal->tanggal_pengajuan)->translatedFormat('d F Y') }}
                        </div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Judul Proposal</div>

                        <div class="proposal-box">
                            <div class="proposal-title">
                                {{ $proposal->judul }}
                            </div>
                        </div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Deskripsi Proposal</div>

                        <div class="info-value">
                            {{ $proposal->deskripsi ?? 'Belum ada deskripsi proposal.' }}
                        </div>
                    </div>

                </div>

            </div>

        </div>

        {{-- RIGHT --}}
        <div>

            <div class="card-box">

                <div class="card-header">
                    <div class="card-title">File Proposal</div>
                </div>

                <div class="card-body">

                    <div class="file-card">

                        <div class="file-left">

                            <div class="file-icon">
                                <i class="fa-solid fa-file-pdf"></i>
                            </div>

                            <div>
                                <div class="file-name">
                                    {{ $proposal->file_proposal }}
                                </div>

                                <div class="file-size">
                                    File Proposal Mahasiswa
                                </div>
                            </div>

                        </div>

                        <a href="{{ route('admin.proposal.lihat', $proposal->id) }}"
                           target="_blank"
                           class="btn-preview">
                            <i class="fa-solid fa-eye"></i>
                            Preview
                        </a>

                    </div>

                    <div class="info-group" style="margin-top:24px;">
                        <div class="info-label">Catatan Reviewer</div>

                        <div class="review-box">
                            <div class="review-text">
                                {{ $proposal->catatan ?? 'Belum ada catatan reviewer untuk proposal ini.' }}
                            </div>
                        </div>
                    </div>

                    <div class="action-wrap">

                        <button class="btn-action btn-approve">
                            <i class="fa-solid fa-check"></i>
                            Setujui Proposal
                        </button>

                        <button class="btn-action btn-reject">
                            <i class="fa-solid fa-xmark"></i>
                            Tolak Proposal
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
@endsection