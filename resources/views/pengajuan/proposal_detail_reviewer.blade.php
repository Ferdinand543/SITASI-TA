@extends('layouts.app')

@section('content')


<div class="container-fluid px-4 pb-5">

    {{-- KEMBALI --}}
    <a href="{{ route('reviewer.proposal') }}" class="btn-kembali">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>

    {{-- PAGE TITLE --}}
    <div class="page-title-wrap">
        <div class="page-title">Detail Review Proposal Mahasiswa</div>
        <div class="page-sub">Informasi detail hasil review dan evaluasi proposal mahasiswa.</div>
    </div>

    @php
        $tanggalPengajuan = $proposal->tanggal_pengajuan
            ? \Carbon\Carbon::parse($proposal->tanggal_pengajuan)->translatedFormat('d F Y')
            : '-';
        $tanggalTinjauan  = $proposal->tanggal_tinjauan
            ? \Carbon\Carbon::parse($proposal->tanggal_tinjauan)->translatedFormat('d F Y')
            : '-';
        $tglDsn1 = $proposal->tgl_dsn1
            ? \Carbon\Carbon::parse($proposal->tgl_dsn1)->translatedFormat('d F Y')
            : null;
        $tglDsn2 = $proposal->tgl_dsn2
            ? \Carbon\Carbon::parse($proposal->tgl_dsn2)->translatedFormat('d F Y')
            : null;
        $namaFile = $proposal->file_proposal ? basename($proposal->file_proposal) : null;
        $namaFileTinjauan = $proposal->file_tinjauan ? basename($proposal->file_tinjauan) : null;
    @endphp

    {{-- ROW 1: Tanggal Pengajuan --}}
    <div class="detail-card mb-14">
        <div class="detail-label">TANGGAL PENGAJUAN</div>
        <div class="detail-value">{{ $tanggalPengajuan }}</div>
    </div>

    {{-- ROW 2: NIM & Nama --}}
    <div class="detail-row2 mb-14">
        <div class="detail-card">
            <div class="detail-label">NIM</div>
            <div class="detail-value">{{ $proposal->nim_nid }}</div>
        </div>
        <div class="detail-card" style="flex:2;">
            <div class="detail-label">NAMA MAHASISWA</div>
            <div class="detail-value">{{ $proposal->nama }}</div>
        </div>
    </div>

    {{-- ROW 3: Status + Proposal File | Judul --}}
    <div class="detail-row2 mb-14" style="align-items:stretch;">

        {{-- Kiri: Status + Judul --}}
        <div style="display:flex; flex-direction:column; gap:14px; flex:1;">
            <div class="detail-card">
                <div class="detail-label">STATUS</div>
                <div style="margin-top:6px;">
                    <span class="badge-selesai-detail">
                        <i class="fa fa-circle-check" style="margin-right:5px;"></i>Selesai
                    </span>
                </div>
            </div>
            <div class="detail-card" style="flex:1;">
                <div class="detail-label">JUDUL</div>
                <div class="detail-value" style="line-height:1.6; margin-top:6px;">{{ $proposal->judul }}</div>
            </div>
        </div>

        {{-- Kanan: File Proposal --}}
        <div class="detail-card" style="flex:2;">
            <div class="detail-label">PROPOSAL MAHASISWA</div>
            @if($namaFile)
                <a href="{{ asset('storage/' . $proposal->file_proposal) }}"
                   target="_blank"
                   class="file-box-link">
                    <div class="file-box-inner">
                        <div class="file-icon-wrap">
                            <i class="fa fa-file-pdf" style="color:#e53e3e; font-size:1.3rem;"></i>
                        </div>
                        <div class="file-info">
                            <div class="file-name">{{ $namaFile }}</div>
                            <div class="file-meta">
                                Diunggah pada {{ $tanggalPengajuan }}
                            </div>
                        </div>
                        <div class="file-download-btn">
                            <i class="fa fa-download"></i>
                        </div>
                    </div>
                </a>
            @else
                <div style="color:#aaa; font-size:0.88rem; margin-top:10px;">
                    <i class="fa fa-inbox me-1"></i> Tidak ada file proposal
                </div>
            @endif
        </div>

    </div>

    {{-- DOSEN PEMBIMBING --}}
    <div class="section-title-wrap mb-10">
        <div class="section-title">Dosen Pembimbing</div>
        <div class="section-sub">Dosen Pembimbing yang telah ditetapkan untuk Tugas Akhir mahasiswa ini.</div>
    </div>

    <div class="detail-row2 mb-14">
        {{-- Dosen 1 --}}
        <div class="dosen-card">
            <div class="dosen-avatar">
                <i class="fa fa-user"></i>
            </div>
            <div class="dosen-info">
                <div class="dosen-nama">{{ $proposal->nama_dsn1 ?? '-' }}</div>
                <div class="dosen-nidn">NIDN. {{ $proposal->nidn_dsn1 ?? '-' }}</div>
                @if($tglDsn1)
                    <div class="dosen-tgl">
                        <i class="fa fa-calendar" style="color:#735C00; margin-right:4px;"></i>
                        Ditetapkan pada {{ $tglDsn1 }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Dosen 2 --}}
        <div class="dosen-card">
            <div class="dosen-avatar">
                <i class="fa fa-user"></i>
            </div>
            <div class="dosen-info">
                <div class="dosen-nama">{{ $proposal->nama_dsn2 ?? '-' }}</div>
                <div class="dosen-nidn">NIDN. {{ $proposal->nidn_dsn2 ?? '-' }}</div>
                @if($tglDsn2)
                    <div class="dosen-tgl">
                        <i class="fa fa-calendar" style="color:#735C00; margin-right:4px;"></i>
                        Ditetapkan pada {{ $tglDsn2 }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- TINJAUAN PROPOSAL --}}
    <div class="section-title-wrap mb-10">
        <div class="section-title">Tinjauan Proposal</div>
        <div class="section-sub">Informasi hasil review dan evaluasi proposal oleh tim penguji.</div>
    </div>

    @if($proposal->tinjauan_id)
        {{-- Catatan Review --}}
        <div class="tinjauan-catatan-box mb-14">
            <div class="tinjauan-catatan-icon">
                <i class="fa fa-comment-dots"></i>
            </div>
            <div class="tinjauan-catatan-text">
                "{{ $proposal->catatan }}"
            </div>
        </div>

        {{-- File Tinjauan + Tanggal --}}
        <div class="tinjauan-bottom-row mb-14">
            {{-- File Tinjauan --}}
            <div style="flex:1;">
                @if($namaFileTinjauan)
                    <a href="{{ asset('storage/' . $proposal->file_tinjauan) }}"
                       target="_blank"
                       class="file-tinjauan-link">
                        <div class="file-tinjauan-inner">
                            <i class="fa fa-file-pdf" style="color:#e53e3e; font-size:1.2rem; margin-right:10px; flex-shrink:0;"></i>
                            <div>
                                <div class="file-name" style="margin-bottom:2px;">{{ $namaFileTinjauan }}</div>
                                <div class="file-meta">Klik untuk membuka file</div>
                            </div>
                        </div>
                    </a>
                @else
                    <div class="file-tinjauan-empty">
                        <i class="fa fa-file-circle-xmark me-2" style="color:#aaa;"></i>
                        Tidak ada file tinjauan
                    </div>
                @endif
            </div>

            {{-- Tanggal Review --}}
            <div class="tinjauan-tgl-wrap">
                <div class="tinjauan-tgl-label">Review Selesai Pada</div>
                <div class="tinjauan-tgl-val">{{ $tanggalTinjauan }}</div>
            </div>
        </div>

    @else
        <div class="tinjauan-empty-box">
            <i class="fa fa-clock me-2"></i> Belum ada tinjauan untuk proposal ini.
        </div>
    @endif

</div>

<style>
/* ============================================================
   GLOBAL
   ============================================================ */
.mb-14 { margin-bottom: 14px; }
.mb-10 { margin-bottom: 10px; }


/* ============================================================
   KEMBALI
   ============================================================ */
.btn-kembali {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #735C00;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    margin-bottom: 14px;
    transition: 0.2s;
}
.btn-kembali:hover { color: #4D4632; }

/* ============================================================
   PAGE TITLE
   ============================================================ */
.page-title-wrap { margin-bottom: 22px; }
.page-title {
    font-size: 1.25rem;
    font-weight: 800;
    color: #111C2D;
    margin-bottom: 3px;
}
.page-sub { font-size: 0.85rem; color: #6C5700; }

/* ============================================================
   SECTION TITLE
   ============================================================ */
.section-title-wrap { }
.section-title {
    font-size: 1rem;
    font-weight: 700;
    color: #111C2D;
    margin-bottom: 2px;
}
.section-sub { font-size: 0.82rem; color: #6C5700; }

/* ============================================================
   DETAIL CARD
   ============================================================ */
.detail-card {
    background: #fff;
    border: 1.5px solid #E5DFD0;
    border-radius: 14px;
    padding: 18px 20px;
}
.detail-label {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.8px;
    color: #888;
    text-transform: uppercase;
    margin-bottom: 8px;
}
.detail-value {
    font-size: 0.97rem;
    font-weight: 600;
    color: #111C2D;
}

/* ============================================================
   ROW 2 KOLOM
   ============================================================ */
.detail-row2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}

/* ============================================================
   BADGE SELESAI
   ============================================================ */
.badge-selesai-detail {
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
    background: #DCFCE7;
    color: #166534;
    border: 1px solid #bbf7d0;
    border-radius: 20px;
    font-size: 0.83rem;
    font-weight: 700;
}

/* ============================================================
   FILE BOX (Proposal)
   ============================================================ */
.file-box-link {
    display: block;
    text-decoration: none;
    margin-top: 12px;
    border-radius: 10px;
    overflow: hidden;
    transition: 0.2s;
}
.file-box-link:hover .file-box-inner { background: #F0EBE0; }
.file-box-inner {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #F7F5F0;
    border: 1.5px solid #E5DFD0;
    border-radius: 10px;
    padding: 12px 14px;
    transition: 0.2s;
}
.file-icon-wrap {
    width: 40px;
    height: 40px;
    background: #fff;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #E5DFD0;
    flex-shrink: 0;
}
.file-info { flex: 1; }
.file-name {
    font-size: 0.88rem;
    font-weight: 600;
    color: #111C2D;
    word-break: break-all;
}
.file-meta { font-size: 0.75rem; color: #888; margin-top: 2px; }
.file-download-btn {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: #fff;
    border: 1.5px solid #D1C6AB;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #735C00;
    flex-shrink: 0;
    font-size: 0.9rem;
    transition: 0.2s;
}
.file-box-link:hover .file-download-btn { background: #FEF3C7; border-color: #735C00; }

/* ============================================================
   DOSEN CARD
   ============================================================ */
.dosen-card {
    background: #fff;
    border: 1.5px solid #E5DFD0;
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    align-items: flex-start;
    gap: 14px;
}
.dosen-avatar {
    width: 44px;
    height: 44px;
    background: #FEF3C7;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #735C00;
    font-size: 1.1rem;
    flex-shrink: 0;
    border: 1.5px solid #FFE083;
}
.dosen-info { flex: 1; }
.dosen-nama {
    font-size: 0.97rem;
    font-weight: 700;
    color: #111C2D;
    margin-bottom: 3px;
}
.dosen-nidn {
    font-size: 0.82rem;
    color: #6C5700;
    margin-bottom: 6px;
}
.dosen-tgl {
    font-size: 0.78rem;
    color: #735C00;
    font-weight: 600;
}

/* ============================================================
   TINJAUAN
   ============================================================ */
.tinjauan-catatan-box {
    background: #fff;
    border: 1.5px solid #E5DFD0;
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    align-items: flex-start;
    gap: 14px;
}
.tinjauan-catatan-icon {
    width: 38px;
    height: 38px;
    background: #F7F5F0;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #735C00;
    font-size: 1.1rem;
    flex-shrink: 0;
    border: 1px solid #E5DFD0;
}
.tinjauan-catatan-text {
    font-size: 0.92rem;
    color: #333;
    line-height: 1.7;
    font-style: italic;
}

.tinjauan-bottom-row {
    display: flex;
    align-items: center;
    gap: 20px;
}

.file-tinjauan-link {
    display: block;
    text-decoration: none;
    border-radius: 10px;
    overflow: hidden;
    transition: 0.2s;
}
.file-tinjauan-inner {
    display: flex;
    align-items: center;
    background: #F7F5F0;
    border: 1.5px solid #E5DFD0;
    border-radius: 10px;
    padding: 12px 16px;
    transition: 0.2s;
}
.file-tinjauan-link:hover .file-tinjauan-inner {
    background: #F0EBE0;
    border-color: #D1C6AB;
}

.file-tinjauan-empty {
    background: #F7F5F0;
    border: 1.5px solid #E5DFD0;
    border-radius: 10px;
    padding: 14px 16px;
    font-size: 0.85rem;
    color: #aaa;
}

.tinjauan-tgl-wrap {
    text-align: right;
    flex-shrink: 0;
    white-space: nowrap;
}
.tinjauan-tgl-label {
    font-size: 0.75rem;
    color: #888;
    margin-bottom: 3px;
}
.tinjauan-tgl-val {
    font-size: 0.95rem;
    font-weight: 700;
    color: #111C2D;
}

.tinjauan-empty-box {
    background: #F7F5F0;
    border: 1.5px solid #E5DFD0;
    border-radius: 14px;
    padding: 24px 20px;
    font-size: 0.9rem;
    color: #aaa;
    text-align: center;
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 640px) {
    .detail-row2     { grid-template-columns: 1fr; }
    .tinjauan-bottom-row { flex-direction: column; align-items: flex-start; }
    .tinjauan-tgl-wrap   { text-align: left; }
}
</style>

@endsection