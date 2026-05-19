@extends('layouts.app')

@section('title', 'Panduan Tugas Akhir')

@section('content')

<style>
    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #C9A227;
        margin-bottom: 8px;
    }

    .page-subtitle {
        font-size: 14px;
        color: #6B7280;
        margin-bottom: 32px;
        max-width: 560px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #111827;
    }

    .card-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }

    @media (max-width: 992px) {
        .card-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .card-grid {
            grid-template-columns: 1fr;
        }
    }

    .empty-state {
        display: flex;
        min-height: 420px;
        margin-top: 8px;
    }

    .empty-left-border {
        width: 4px;
        background: #C9A227;
        border-radius: 4px;
        flex-shrink: 0;
    }

    .empty-inner {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 48px 24px;
        background: #fff;
        border: 1px solid #E5E7EB;
        border-left: none;
    }

    .empty-img {
        width: 200px;
        height: 200px;
        object-fit: contain;
        margin-bottom: 8px;
    }

    .empty-title {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin: 12px 0 8px;
    }

    .empty-desc {
        font-size: 14px;
        color: #6B7280;
        text-align: center;
        line-height: 1.7;
        margin: 0;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #C9A227;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        margin-bottom: 20px;
    }

    .back-link:hover {
        opacity: .75;
    }

    .back-link:hover {
        opacity: .75;
    }
</style>

<a href="{{ url('mahasiswa') }}" class="back-link">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
        stroke="currentColor" width="16" height="16">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
    </svg>
    Kembali
</a>

<h1 class="page-title">Panduan Tugas Akhir</h1>
<p class="page-subtitle">
    Informasi dan panduan lengkap mengenai proses akhir mahasiswa untuk mendukung kelancaran Tugas Akhir Anda.
</p>

@if($shared->count() === 0 && $khusus->count() === 0)
<div class="empty-state">
    <div class="empty-left-border"></div>
    <div class="empty-inner">
        <img src="{{ asset('images/panduan.jpeg') }}" alt="No Content Yet" class="empty-img">
        <h2 class="empty-title">Belum Ada Data</h2>
        <p class="empty-desc">Data akan muncul setelah proses dilakukan.</p>
        <p class="empty-desc">Silakan tunggu hingga informasi tersedia pada halaman ini.</p>
    </div>
</div>

@else
@if($shared->count())
<h2 class="section-title">Dokumen Panduan</h2>
<div class="card-grid">
    @foreach($shared as $doc)
    @include('partials._card_dokumen', ['doc' => (array) $doc])
    @endforeach
</div>
@endif

@if($khusus->count())
<h2 class="section-title" style="margin-top: 48px;">Template & Berkas Mahasiswa</h2>
<div class="card-grid">
    @foreach($khusus as $doc)
    @include('partials._card_dokumen', ['doc' => (array) $doc])
    @endforeach
</div>
@endif
@endif

@endsection