@extends('layouts.app')

@section('content')

<style>
    body { background: #f4f6f9; }

    .wrapper { max-width: 900px; margin: auto; padding: 32px 20px; }

    .page-title { font-size: 1.8rem; font-weight: 800; color: #111; margin-bottom: 4px; }

    .page-sub { font-size: 0.9rem; color: #888; margin-bottom: 24px; }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 24px;
    }

    .info-box {
        border: 1px solid #e5e5e5;
        border-radius: 14px;
        padding: 16px 18px;
        background: #fff;
    }

    .info-box .lbl {
        font-size: 0.82rem;
        color: #888;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .info-box .val {
        font-size: 1rem;
        font-weight: 700;
        color: #111;
    }

    .val.selesai  { color: #28a745; }
    .val.menunggu { color: #b8860b; }
    .val.ditolak  { color: #dc3545; }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #111;
        margin-bottom: 2px;
    }

    .section-sub {
        font-size: 0.83rem;
        color: #888;
        margin-bottom: 14px;
    }

    .dosen-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 24px;
    }

    .dosen-card {
        border: 1px solid #e5e5e5;
        border-radius: 14px;
        padding: 18px;
        background: #fff;
        position: relative;
    }

    .dosen-card .dosen-label {
        font-size: 0.8rem;
        color: #888;
        margin-bottom: 8px;
    }

    .dosen-card .dosen-nama {
        font-size: 1rem;
        font-weight: 700;
        color: #111;
        margin-bottom: 2px;
    }

    .dosen-card .dosen-nidn {
        font-size: 0.85rem;
        color: #555;
        margin-bottom: 8px;
    }

    .dosen-card .dosen-tanggal {
        font-size: 0.8rem;
        color: #888;
    }

    .badge-disetujui {
        background: #d4edda;
        color: #28a745;
        border: 1px solid #b7dfbb;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-block;
    }

    .badge-menunggu {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffe082;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-block;
        text-decoration: none;
        cursor: pointer;
    }

    .badge-menunggu:hover {
        background: #ffe69c;
        color: #856404;
    }

    .badge-ditolak {
        background: #f8d7da;
        color: #dc3545;
        border: 1px solid #f1aeb5;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-block;
    }

    .proposal-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .proposal-box .file-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: #111;
    }

    .proposal-box .file-meta {
        font-size: 0.78rem;
        color: #888;
    }

    .footer-btn {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        margin-top: 30px;
    }

    .btn-kembali {
        background: #e0e0e0;
        color: #333;
        border: none;
        padding: 10px 22px;
        border-radius: 20px;
        font-size: 0.9rem;
        text-decoration: none;
    }
    
    .btn-kembali:hover {
    background: #c8c8c8;
    color: #111;
    transition: 0.2s;
    }

    .icon-user {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 2px solid #ccc;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
        color: #888;
        font-size: 16px;
        vertical-align: middle;
        flex-shrink: 0;
    }
</style>

<div class="wrapper">

    {{-- JUDUL --}}
    <div class="page-title">Verifikasi dan Tetapkan Dosen Pembimbing Mahasiswa</div>
    <div class="page-sub">Kelola verifikasi dan penetapan dosen pembimbing tugas akhir mahasiswa.</div>

    {{-- INFO GRID --}}
    <div class="info-grid">

        {{-- Tanggal --}}
        <div class="info-box">
            <div class="lbl"><i class="fa fa-calendar"></i> Tanggal Pengajuan</div>
            <div class="val">
                {{ \Carbon\Carbon::parse($proposal->tanggal_pengajuan)->translatedFormat('d M Y') }}
            </div>
        </div>

        {{-- NIM --}}
        <div class="info-box">
            <div class="lbl"><i class="fa fa-user"></i> NIM</div>
            <div class="val">{{ $proposal->nim_nid }}</div>
        </div>

        {{-- Status --}}
        <div class="info-box">
            <div class="lbl"><i class="fa fa-clock"></i> Status</div>
            @php $st = strtolower(trim($proposal->status)); @endphp
            <div class="val {{ str_contains($st, 'menunggu') ? 'menunggu' : ($st == 'selesai' ? 'selesai' : 'ditolak') }}">
                {{ ucwords(str_replace('_', ' ', $proposal->status)) }}
            </div>
        </div>

        {{-- Nama --}}
        <div class="info-box">
            <div class="lbl"><i class="fa fa-users"></i> Nama</div>
            <div class="val">{{ $proposal->nama }}</div>
        </div>

        {{-- Judul --}}
        <div class="info-box">
            <div class="lbl">Judul</div>
            <div class="val" style="font-size:0.95rem;">{{ $proposal->judul }}</div>
        </div>

        {{-- Proposal --}}
        <div class="info-box">
            <div class="lbl">Proposal</div>
            @if($proposal->file_proposal)
                <div class="proposal-box mt-2">
                    <div>
                        <div class="file-name">
                            <i class="fa fa-file-pdf text-danger me-1"></i>
                            {{ basename($proposal->file_proposal) }}
                        </div>
                        <div class="file-meta">
                            Diunggah pada {{ \Carbon\Carbon::parse($proposal->tanggal_pengajuan)->translatedFormat('d M') }}
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $proposal->file_proposal) }}"
                       target="_blank" class="text-dark">
                        <i class="fa fa-download"></i>
                    </a>
                </div>
            @else
                <div class="val text-muted" style="font-size:0.9rem;">Belum ada file proposal</div>
            @endif
        </div>

    </div>

    {{-- USULAN PEMBIMBING --}}
    <div class="section-title">Usulan Pembimbing</div>
    <div class="section-sub">Daftar dosen yang Anda ajukan</div>

    <div class="dosen-grid">

        {{-- Usulan 1 --}}
        <div class="dosen-card">
            <div class="dosen-label">Usulan Pembimbing 1</div>
            @if($proposal->usulan_dosen1_nama)
                <div style="display:flex; align-items:center; margin-bottom:4px;">
                    <span class="icon-user"><i class="fa fa-user"></i></span>
                    <div>
                        <div class="dosen-nama">{{ $proposal->usulan_dosen1_nama }}</div>
                        <div class="dosen-nidn">NIDN. {{ $proposal->usulan_dosen1_nidn }}</div>
                    </div>
                </div>
                <div class="dosen-tanggal mb-2">
                    Diusulkan pada<br>
                    {{ $proposal->usulan_dosen1_tanggal
                        ? \Carbon\Carbon::parse($proposal->usulan_dosen1_tanggal)->translatedFormat('d M Y')
                        : '-' }}
                </div>
                @php $s1 = strtolower($proposal->usulan_dosen1_status ?? 'menunggu'); @endphp
                @if($s1 == 'disetujui')
                    <span class="badge-disetujui">Disetujui</span>
                @elseif($s1 == 'ditolak')
                    <span class="badge-ditolak">Ditolak</span>
                @else
                    {{-- Badge klikable → ke form penetapan (dibuat temen lo) --}}
                    <a href="{{ url('/proposal/' . $proposal->id . '/tetapkan/1') }}"
                       class="badge-menunggu">
                        Menunggu Verifikasi
                    </a>
                @endif
            @else
                <span class="text-muted">-</span>
            @endif
        </div>

        {{-- Usulan 2 --}}
        <div class="dosen-card">
            <div class="dosen-label">Usulan Pembimbing 2</div>
            @if($proposal->usulan_dosen2_nama)
                <div style="display:flex; align-items:center; margin-bottom:4px;">
                    <span class="icon-user"><i class="fa fa-user"></i></span>
                    <div>
                        <div class="dosen-nama">{{ $proposal->usulan_dosen2_nama }}</div>
                        <div class="dosen-nidn">NIDN. {{ $proposal->usulan_dosen2_nidn }}</div>
                    </div>
                </div>
                <div class="dosen-tanggal mb-2">
                    Diusulkan pada<br>
                    {{ $proposal->usulan_dosen2_tanggal
                        ? \Carbon\Carbon::parse($proposal->usulan_dosen2_tanggal)->translatedFormat('d M Y')
                        : '-' }}
                </div>
                @php $s2 = strtolower($proposal->usulan_dosen2_status ?? 'menunggu'); @endphp
                @if($s2 == 'disetujui')
                    <span class="badge-disetujui">Disetujui</span>
                @elseif($s2 == 'ditolak')
                    <span class="badge-ditolak">Ditolak</span>
                @else
                    {{-- Badge klikable → ke form penetapan (dibuat temen lo) --}}
                    <a href="{{ url('/proposal/' . $proposal->id . '/tetapkan/2') }}"
                       class="badge-menunggu">
                        Menunggu Verifikasi
                    </a>
                @endif
            @else
                <span class="text-muted">-</span>
            @endif
        </div>

    </div>

    {{-- DOSEN PEMBIMBING (PENETAPAN) --}}
    <div class="section-title">Dosen Pembimbing</div>
    <div class="section-sub">Dosen Pembimbing yang telah ditetapkan untuk Tugas Akhir Anda</div>

    <div class="dosen-grid">

        {{-- Pembimbing 1 --}}
        <div class="dosen-card">
            <div class="dosen-label">Pembimbing 1</div>
            @if($proposal->dosen1_nama)
                <div style="display:flex; align-items:center; margin-bottom:4px;">
                    <span class="icon-user"><i class="fa fa-user"></i></span>
                    <div>
                        <div class="dosen-nama">{{ $proposal->dosen1_nama }}</div>
                        <div class="dosen-nidn">NIDN. {{ $proposal->dosen1_nidn }}</div>
                    </div>
                </div>
                <div class="dosen-tanggal">
                    Ditetapkan pada<br>
                    {{ $proposal->dosen1_tanggal
                        ? \Carbon\Carbon::parse($proposal->dosen1_tanggal)->translatedFormat('d M Y')
                        : '-' }}
                </div>
            @else
                <div style="display:flex; align-items:center; margin-bottom:8px;">
                    <span class="icon-user"><i class="fa fa-user"></i></span>
                    <span class="text-muted" style="font-size:0.9rem;">[Menunggu verifikasi]</span>
                </div>
                <div class="dosen-tanggal">Ditetapkan pada<br>-</div>
            @endif
        </div>

        {{-- Pembimbing 2 --}}
        <div class="dosen-card">
            <div class="dosen-label">Pembimbing 2</div>
            @if($proposal->dosen2_nama)
                <div style="display:flex; align-items:center; margin-bottom:4px;">
                    <span class="icon-user"><i class="fa fa-user"></i></span>
                    <div>
                        <div class="dosen-nama">{{ $proposal->dosen2_nama }}</div>
                        <div class="dosen-nidn">NIDN. {{ $proposal->dosen2_nidn }}</div>
                    </div>
                </div>
                <div class="dosen-tanggal">
                    Ditetapkan pada<br>
                    {{ $proposal->dosen2_tanggal
                        ? \Carbon\Carbon::parse($proposal->dosen2_tanggal)->translatedFormat('d M Y')
                        : '-' }}
                </div>
            @else
                <div style="display:flex; align-items:center; margin-bottom:8px;">
                    <span class="icon-user"><i class="fa fa-user"></i></span>
                    <span class="text-muted" style="font-size:0.9rem;">[Menunggu verifikasi]</span>
                </div>
                <div class="dosen-tanggal">Ditetapkan pada<br>-</div>
            @endif
        </div>

    </div>

    {{-- FOOTER --}}
    <div class="footer-btn">
        <a href="/proposal" class="btn-kembali">Kembali</a>
    </div>

</div>

@endsection