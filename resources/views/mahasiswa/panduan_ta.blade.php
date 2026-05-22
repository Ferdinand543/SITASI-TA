@extends('layouts.app')

@section('title', 'Panduan Tugas Akhir')

@section('content')

<style>
    .page-title { font-size: 28px; font-weight: 700; color: #C9A227; margin-bottom: 8px; }
    .page-subtitle { font-size: 14px; color: #6B7280; margin-bottom: 32px; max-width: 560px; }
    .card-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
    @media (max-width: 992px) { .card-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .card-grid { grid-template-columns: 1fr; } }
    .empty-state { display: flex; min-height: 420px; margin-top: 8px; }
    .empty-left-border { width: 4px; background: #C9A227; border-radius: 4px; flex-shrink: 0; }
    .empty-inner { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 48px 24px; background: #fff; border: 1px solid #E5E7EB; border-left: none; }
    .empty-img { width: 200px; height: 200px; object-fit: contain; margin-bottom: 8px; }
    .empty-title { font-size: 24px; font-weight: 700; color: #111827; margin: 12px 0 8px; }
    .empty-desc { font-size: 14px; color: #6B7280; text-align: center; line-height: 1.7; margin: 0; }
</style>

<h1 class="page-title">Panduan Tugas Akhir</h1>
<p class="page-subtitle">
    Dokumen referensi dan formulir untuk mendukung proses Tugas Akhir kamu.
</p>

@if($dokumen->count() === 0)
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
    <div class="card-grid">
        @foreach($dokumen as $doc)
            @include('partials._card_dokumen', ['doc' => (array) $doc, 'isAdmin' => false])
        @endforeach
    </div>
@endif

@endsection