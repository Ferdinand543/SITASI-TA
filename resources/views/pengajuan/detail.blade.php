@extends('layouts.app')

@section('content')

<style>
    body {
        background: #f4f6f9;
    }

    .detail-wrapper {
        max-width: 950px;
        margin: auto;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        color: #222;
        text-decoration: none;
        padding: 11px 18px;
        border-radius: 14px;
        font-size: 0.92rem;
        font-weight: 600;
        border: 1px solid #ececec;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        transition: all .2s ease;
        margin-bottom: 22px;
    }

    .back-link:hover {
        transform: translateY(-2px);
        background: #FFC107;
        color: #000;
    }

    .info-card {
        background: #fff;
        border-radius: 24px;
        padding: 30px;
        border: 1px solid #ececec;
        box-shadow:
            0 4px 14px rgba(0, 0, 0, 0.05),
            0 1px 3px rgba(0, 0, 0, 0.04);
        margin-bottom: 24px;
    }

    .page-title {
        font-size: 1.9rem;
        font-weight: 800;
        color: #111;
        margin-bottom: 6px;
    }

    .page-subtitle {
        font-size: 0.93rem;
        color: #777;
    }

    .info-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        margin-top: 24px;
    }

    .info-box {
        border: 1px solid #ececec;
        border-radius: 18px;
        padding: 18px;
        background: #fafafa;
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    .info-box .label {
        font-size: 0.82rem;
        color: #888;
        margin-bottom: 4px;
    }

    .info-box .value {
        font-size: 1rem;
        font-weight: 700;
        color: #111;
    }

    .value.menunggu {
        color: #B8860B;
    }

    .value.disetujui {
        color: #28a745;
    }

    .value.ditolak {
        color: #dc3545;
    }

    .usulan-header {
        background: #fff;
        border-radius: 20px;
        padding: 18px 22px;
        border: 1px solid #ececec;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
        margin-bottom: 20px;
    }

    .usulan-header .title {
        font-size: 1rem;
        font-weight: 700;
        color: #111;
        margin-bottom: 2px;
    }

    .usulan-header .sub {
        color: #888;
        font-size: 0.84rem;
    }

    .judul-list-wrapper {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .judul-card {
        background: #fff;
        border-radius: 22px;
        padding: 24px;
        border: 1px solid #ececec;
        box-shadow:
            0 4px 14px rgba(0, 0, 0, 0.05),
            0 1px 3px rgba(0, 0, 0, 0.04);
        position: relative;
        transition: all .2s ease;
    }

    .judul-card:hover {
        transform: translateY(-3px);
        box-shadow:
            0 10px 24px rgba(0, 0, 0, 0.08),
            0 3px 8px rgba(0, 0, 0, 0.05);
    }

    .judul-label {
        font-size: 0.8rem;
        color: #999;
        margin-bottom: 7px;
    }

    .judul-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #111;
        line-height: 1.5;
        margin-bottom: 20px;
        padding-right: 170px;
    }

    .meta-row {
        display: grid;
        grid-template-columns: 170px 1fr;
        gap: 10px 18px;
        font-size: 0.9rem;
    }

    .meta-label {
        color: #888;
    }

    .meta-value {
        color: #222;
        font-weight: 600;
    }

    .badge-status-pill {
        position: absolute;
        top: 22px;
        right: 22px;
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .badge-menunggu {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffe082;
    }

    .badge-disetujui {
        background: #d4edda;
        color: #28a745;
        border: 1px solid #b7dfbb;
    }

    .badge-ditolak {
        background: #f8d7da;
        color: #dc3545;
        border: 1px solid #f1aeb5;
    }

    @media(max-width:768px) {
        .info-row {
            grid-template-columns: 1fr;
        }

        .judul-title {
            padding-right: 0;
        }

        .badge-status-pill {
            position: static;
            display: inline-block;
            margin-bottom: 14px;
        }

        .meta-row {
            grid-template-columns: 1fr;
        }

        .info-card {
            padding: 22px;
        }

        .page-title {
            font-size: 1.5rem;
        }
    }
</style>

<div class="container py-4">
    <div class="detail-wrapper">

        {{-- TOP HEADER CARD --}}
        <div class="info-card">

            <a href="{{ url()->previous() }}" class="back-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                </svg>
                Kembali
            </a>

            <div>
                <h2 class="page-title">Detail Pengajuan Judul TA-1</h2>
                <p class="page-subtitle mb-0">Informasi lengkap mengenai pengajuan judul tugas akhir mahasiswa.</p>
            </div>

            <div class="info-row">

                {{-- TANGGAL --}}
                <div class="info-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#555" viewBox="0 0 16 16" style="margin-top:2px;flex-shrink:0;">
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                    </svg>
                    <div>
                        <div class="label">Tanggal Pengajuan</div>
                        <div class="value">
                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d M Y') }}
                        </div>
                    </div>
                </div>

                {{-- NIM --}}
                <div class="info-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#555" viewBox="0 0 16 16" style="margin-top:2px;flex-shrink:0;">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                    </svg>
                    <div>
                        <div class="label">NIM Mahasiswa</div>
                        <div class="value">{{ $pengajuan->nim_nid }}</div>
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="info-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#555" viewBox="0 0 16 16" style="margin-top:2px;flex-shrink:0;">
                        <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                    </svg>
                    <div>
                        <div class="label">Status Pengajuan</div>
                        @php $st = strtolower($pengajuan->status); @endphp
                        @if($st === 'disetujui')
                        <div class="value disetujui">Disetujui</div>
                        @elseif($st === 'ditolak')
                        <div class="value ditolak">Ditolak</div>
                        @else
                        <div class="value menunggu">Menunggu Verifikasi</div>
                        @endif
                    </div>
                </div>

                {{-- MITRA --}}
                <div class="info-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#555" viewBox="0 0 16 16" style="margin-top:2px;flex-shrink:0;">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                        <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z" />
                        <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z" />
                    </svg>
                    <div>
                        <span class="meta-label">Nama Mahasiswa</span><br>
                        <span class="meta-value">{{ session('user')->nama ?? '-' }}</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- HEADER USULAN --}}
        <div class="usulan-header">
            <div class="title">Usulan Judul</div>
            <div class="sub">Berikut adalah 3 usulan judul yang diajukan</div>
        </div>

        {{-- LIST JUDUL DARI DATABASE --}}
        @php

        $judulList = [
        [
        'label' => 'Judul 1',
        'judul' => $pengajuan->judul_1,
        'topik' => $pengajuan->topik_1,
        ],
        [
        'label' => 'Judul 2',
        'judul' => $pengajuan->judul_2,
        'topik' => $pengajuan->topik_2,
        ],
        [
        'label' => 'Judul 3',
        'judul' => $pengajuan->judul_3,
        'topik' => $pengajuan->topik_3,
        ],
        ];

        $statusUtama = strtolower($pengajuan->status);
        $judulDisetujui = $pengajuan->judul_disetujui ?? null;

        @endphp

        <div class="judul-list-wrapper">

            @foreach($judulList as $item)

            @php

            // DEFAULT
            $statusCard = 'menunggu';

            // JIKA SEMUA DITOLAK
            if ($statusUtama === 'ditolak') {

            $statusCard = 'ditolak';

            }

            // JIKA ADA YANG DISETUJUI
            elseif ($statusUtama === 'disetujui') {

            // HANYA 1 JUDUL YANG HIJAU
            if ($item['judul'] === $judulDisetujui) {

            $statusCard = 'disetujui';

            } else {

            // SISANYA MERAH
            $statusCard = 'ditolak';
            }
            }

            @endphp

            <div class="judul-card">

                {{-- STATUS BADGE --}}
                @if($statusCard === 'disetujui')

                <span class="badge-status-pill badge-disetujui">
                    Disetujui
                </span>

                @elseif($statusCard === 'ditolak')

                <span class="badge-status-pill badge-ditolak">
                    Ditolak
                </span>

                @else

                <span class="badge-status-pill badge-menunggu">
                    Menunggu Verifikasi
                </span>

                @endif


                {{-- LABEL --}}
                <div class="judul-label">
                    {{ $item['label'] }}
                </div>


                {{-- JUDUL --}}
                <div class="judul-title">
                    {{ $item['judul'] }}
                </div>


                {{-- DETAIL --}}
                <div class="meta-row">

                    <span class="meta-label">
                        Topik Penelitian
                    </span>

                    <span class="meta-value">
                        {{ $item['topik'] }}
                    </span>


                    <span class="meta-label">
                        Mitra Penelitian
                    </span>

                    <span class="meta-value">
                        {{ $pengajuan->mitra_penelitian ?? '-' }}
                    </span>

                </div>

            </div>

            @endforeach

        </div>

    </div>
</div>

@endsection