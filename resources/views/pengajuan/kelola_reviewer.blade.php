@extends('layouts.app')

@section('content')

@php
    $roleSesi   = strtolower(trim(session('user')->role ?? ''));
    $urlKembali = $roleSesi === 'admin'
        ? url('/admin/proposal?tab=reviewer')
        : url('/proposal?tab=reviewer');
@endphp

<style>
    /* ── BACK BUTTON ── */
    .btn-kembali {
        display: inline-flex; align-items: center; gap: 7px;
        color: #6C5700; font-size: 0.85rem; font-weight: 700;
        text-decoration: none; margin-bottom: 20px;
        padding: 8px 16px; border-radius: 10px; border: 1.5px solid #e5e7eb;
        background: #fff; transition: 0.15s;
    }
    .btn-kembali:hover { background: #fffde7; border-color: #FACC15; color: #6C5700; }

    /* ── HERO CARD ── */
    .hero-card {
        background-image: url('/images/1.jpeg');
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        padding: 36px 48px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }
    .hero-card h2 { font-size: 1.9rem; font-weight: 800; color: #7a4f00; margin-bottom: 6px; }
    .hero-card p  { font-size: 0.86rem; color: #a07030; max-width: 500px; }

    /* ── DOSEN INFO CARD ── */
    .dosen-info-card {
        background: #fff; border-radius: 16px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 24px 28px;
        display: flex; align-items: center; gap: 20px;
        margin-bottom: 28px; flex-wrap: wrap;
    }
    .dosen-avatar-lg {
        width: 64px; height: 64px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; font-weight: 800; color: #fff; flex-shrink: 0;
    }
    .dosen-info-main { flex: 1; min-width: 200px; }
    .dosen-info-main .nama { font-size: 1.15rem; font-weight: 800; color: #111; }
    .dosen-info-main .nid  { font-size: 0.82rem; color: #64748b; margin-top: 2px; font-family: monospace; }
    .dosen-stat-box {
        background: #fffbe6; border: 1.5px solid #FACC15; border-radius: 14px;
        padding: 16px 28px; text-align: center;
    }
    .dosen-stat-box .angka { font-size: 2rem; font-weight: 800; color: #6C5700; line-height: 1; }
    .dosen-stat-box .label { font-size: 0.72rem; color: #92703a; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; margin-top: 4px; }

    /* ── SECTION HEADER ── */
    .section-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 16px; flex-wrap: wrap; gap: 12px;
    }
    .section-title { font-size: 1rem; font-weight: 800; color: #111; }
    .section-sub   { font-size: 0.8rem; color: #94a3b8; margin-top: 2px; }

    /* ── TOMBOL TAMBAH ── */
    .btn-tambah-mhs {
        background: #FACC15; color: #333; border: none; border-radius: 10px;
        padding: 10px 20px; font-size: 0.85rem; font-weight: 700;
        display: inline-flex; align-items: center; gap: 8px; cursor: pointer;
        transition: 0.15s; text-decoration: none;
    }
    .btn-tambah-mhs:hover { background: #e6b800; color: #333; }

    /* ── SEARCH INLINE ── */
    .inline-search-wrap { position: relative; }
    .inline-search-wrap svg { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #aaa; pointer-events: none; }
    .inline-search-wrap input {
        padding: 9px 12px 9px 34px; border: 1px solid #e5e7eb; border-radius: 10px;
        font-size: 0.84rem; width: 240px; outline: none; background: #fff;
    }
    .inline-search-wrap input:focus { border-color: #FACC15; box-shadow: 0 0 0 3px rgba(250,204,21,0.15); }

    /* ── TABLE ── */
    .table-card {
        background: #fff; border-radius: 16px; border: 1px solid #f0f0f0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow-x: auto;
    }
    .tbl { width: 100%; border-collapse: collapse; min-width: 600px; }
    .tbl thead tr { background: #fafafa; border-bottom: 1px solid #f0f0f0; }
    .tbl th { padding: 12px 16px; font-size: 0.73rem; font-weight: 700; color: #64748b; text-align: left; text-transform: uppercase; letter-spacing: 0.04em; white-space: nowrap; }
    .tbl th.center { text-align: center; }
    .tbl td { padding: 14px 16px; font-size: 0.84rem; color: #333; border-bottom: 1px solid #f5f5f5; vertical-align: middle; }
    .tbl tbody tr:last-child td { border-bottom: none; }
    .tbl tbody tr:hover td { background: #fffde7; transition: 0.1s; }

    .avatar-sm {
        width: 34px; height: 34px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 700; color: #fff; flex-shrink: 0;
    }

    /* STATUS */
    .badge-review { background: #CCE5FF; color: #004085; border: 1px solid #b8daff; border-radius: 20px; padding: 4px 12px; font-size: 0.73rem; font-weight: 700; white-space: nowrap; }
    .badge-selesai { background: #d4edda; color: #28a745; border: 1px solid #b7dfbb; border-radius: 20px; padding: 4px 12px; font-size: 0.73rem; font-weight: 700; white-space: nowrap; }

    /* TOMBOL HAPUS */
    .btn-hapus {
        background: #fff; color: #dc3545; border: 1px solid #f1aeb5; border-radius: 8px;
        padding: 6px 14px; font-size: 0.78rem; font-weight: 700; cursor: pointer;
        display: inline-flex; align-items: center; gap: 5px; transition: 0.15s;
    }
    .btn-hapus:hover { background: #f8d7da; }

    /* ── EMPTY STATE ── */
    .empty-wrap { padding: 80px 20px; text-align: center; }
    .empty-inner { display: inline-flex; flex-direction: column; align-items: center; gap: 14px; }
    .empty-img { width: 80px; opacity: 0.4; }
    .empty-title { font-size: 1.1rem; font-weight: 800; color: #475569; }
    .empty-sub   { font-size: 0.82rem; color: #94a3b8; max-width: 320px; line-height: 1.5; }
    .badge-count { background: #fffbe6; border: 1.5px solid #FACC15; border-radius: 20px; font-size: 0.75rem; font-weight: 700; color: #856404; padding: 2px 10px; }

    /* ── MODAL TAMBAH MAHASISWA ── */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; }
    .modal-overlay.active { display: flex; }
    .modal-box {
        background: #fff; border-radius: 20px; width: 100%; max-width: 620px;
        margin: 0 16px; box-shadow: 0 16px 48px rgba(0,0,0,0.2); position: relative;
        max-height: 85vh; display: flex; flex-direction: column;
    }
    .modal-head {
        padding: 24px 28px 16px;
        border-bottom: 1px solid #f0f0f0;
        flex-shrink: 0;
    }
    .modal-head-title { font-size: 1.1rem; font-weight: 800; color: #111; margin-bottom: 4px; }
    .modal-head-sub   { font-size: 0.82rem; color: #888; }
    .modal-close {
        position: absolute; top: 18px; right: 18px;
        background: #f1f5f9; border: none; border-radius: 50%; width: 32px; height: 32px;
        font-size: 1rem; color: #555; cursor: pointer; display: flex; align-items: center; justify-content: center;
    }
    .modal-close:hover { background: #e2e8f0; }
    .modal-search-wrap {
        padding: 14px 28px;
        border-bottom: 1px solid #f0f0f0;
        flex-shrink: 0;
    }
    .modal-search {
        width: 100%; padding: 10px 14px 10px 38px;
        border: 1.5px solid #e5e7eb; border-radius: 10px;
        font-size: 0.88rem; outline: none; box-sizing: border-box;
    }
    .modal-search:focus { border-color: #FACC15; }
    .modal-search-icon { position: absolute; left: 42px; top: 50%; transform: translateY(-50%); color: #aaa; pointer-events: none; }
    .modal-list { flex: 1; overflow-y: auto; padding: 8px 0; }
    .modal-mhs-row {
        display: flex; align-items: center; gap: 14px; padding: 12px 28px;
        cursor: pointer; transition: 0.1s; border-bottom: 1px solid #f9fafb;
    }
    .modal-mhs-row:hover { background: #fffde7; }
    .modal-mhs-row input[type=checkbox] {
        width: 17px; height: 17px; accent-color: #FACC15; cursor: pointer; flex-shrink: 0;
    }
    .modal-mhs-info { flex: 1; min-width: 0; }
    .modal-mhs-nama { font-size: 0.88rem; font-weight: 700; color: #111; }
    .modal-mhs-nim  { font-size: 0.75rem; color: #94a3b8; font-family: monospace; }
    .modal-mhs-judul { font-size: 0.76rem; color: #555; margin-top: 2px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 340px; }
    .modal-footer {
        padding: 16px 28px;
        border-top: 1px solid #f0f0f0;
        display: flex; align-items: center; justify-content: space-between;
        flex-shrink: 0; gap: 12px;
    }
    .modal-count { font-size: 0.85rem; color: #555; }
    .modal-count span { font-weight: 700; color: #111; }
    .btn-modal-batal {
        padding: 10px 22px; border-radius: 10px; border: 1.5px solid #e5e7eb;
        background: #fff; color: #555; font-size: 0.9rem; font-weight: 600; cursor: pointer;
    }
    .btn-modal-batal:hover { background: #f5f5f5; }
    .btn-modal-simpan {
        padding: 10px 22px; border-radius: 10px; border: none;
        background: #FACC15; color: #333; font-size: 0.9rem; font-weight: 700; cursor: pointer;
        display: inline-flex; align-items: center; gap: 7px;
    }
    .btn-modal-simpan:hover { background: #e6b800; }
    .btn-modal-simpan:disabled { background: #ccc; cursor: not-allowed; }

    /* MODAL EMPTY */
    .modal-empty { padding: 40px 20px; text-align: center; color: #94a3b8; font-size: 0.85rem; }

    /* POPUP */
    .popup-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 99999; align-items: center; justify-content: center; }
    .popup-overlay.active { display: flex; }
    .popup-box { background: #fff; border-radius: 20px; padding: 40px 32px 32px; width: 100%; max-width: 380px; margin: 0 16px; box-shadow: 0 12px 40px rgba(0,0,0,0.2); text-align: center; }
    .popup-icon-wrap { width: 72px; height: 72px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px; font-size: 2rem; }
    .popup-icon-wrap.success { background: #e8f5e9; border: 3px solid #66bb6a; color: #28a745; }
    .popup-icon-wrap.error   { background: #fdecea; border: 3px solid #ef9a9a; color: #dc3545; }
    .popup-title { font-size: 1.3rem; font-weight: 800; color: #222; margin-bottom: 8px; }
    .popup-msg   { font-size: 0.88rem; color: #555; margin-bottom: 24px; line-height: 1.5; }
    .popup-btn { padding: 10px 32px; border-radius: 10px; font-size: 0.95rem; font-weight: 700; cursor: pointer; border: none; background: #FACC15; color: #333; }
    .popup-btn:hover { background: #e6b800; }

    /* KONFIRMASI HAPUS */
    .konfirm-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 99999; align-items: center; justify-content: center; }
    .konfirm-overlay.active { display: flex; }
    .konfirm-box { background: #fff; border-radius: 20px; padding: 36px 28px; width: 100%; max-width: 380px; margin: 0 16px; box-shadow: 0 12px 40px rgba(0,0,0,0.2); text-align: center; }
    .konfirm-title { font-size: 1.1rem; font-weight: 800; color: #111; margin-bottom: 8px; }
    .konfirm-msg   { font-size: 0.85rem; color: #666; margin-bottom: 24px; line-height: 1.5; }
    .konfirm-btns  { display: flex; gap: 12px; justify-content: center; }
    .konfirm-batal { padding: 10px 28px; border-radius: 10px; border: 1.5px solid #e5e7eb; background: #fff; color: #555; font-size: 0.9rem; font-weight: 600; cursor: pointer; }
    .konfirm-hapus { padding: 10px 28px; border-radius: 10px; border: none; background: #dc3545; color: #fff; font-size: 0.9rem; font-weight: 700; cursor: pointer; }
    .konfirm-hapus:hover { background: #bb2d3b; }

    /* INFO PAGING */
    .paging-info { padding: 10px 20px 14px; font-size: 0.75rem; color: #94a3b8; }
</style>

<div class="container-fluid px-4 pb-5">

    {{-- KEMBALI --}}
    <a href="{{ $urlKembali }}" class="btn-kembali">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
        </svg>
        Kembali ke Penetapan Reviewer
    </a>

    {{-- HERO --}}
    <div class="hero-card mb-4">
        <h2>Kelola Penugasan Reviewer Proposal</h2>
        <p>Kelola mahasiswa yang ditugaskan kepada dosen reviewer untuk proses review proposal tugas akhir.</p>
    </div>

    @if(session('success'))
    <div style="background:#d4edda;color:#28a745;border:1px solid #b7dfbb;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:0.9rem;">
        <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div style="background:#f8d7da;color:#dc3545;border:1px solid #f1aeb5;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:0.9rem;">
        <i class="fa fa-times-circle me-1"></i> {{ session('error') }}
    </div>
    @endif

    {{-- DOSEN INFO CARD --}}
    @php
        $inisialDosen = strtoupper(substr($dosen->nama, 0, 1));
        $colors = ['#F59E0B','#10B981','#3B82F6','#8B5CF6','#EF4444','#EC4899','#14B8A6'];
        $bgDosen = $colors[abs(crc32($dosen->nim_nid)) % count($colors)];
    @endphp
    <div class="dosen-info-card">
        <span class="dosen-avatar-lg" style="background:{{ $bgDosen }};">{{ $inisialDosen }}</span>
        <div class="dosen-info-main">
            <div class="nama">{{ $dosen->nama }}</div>
            <div class="nid">NID: {{ $dosen->nim_nid }}</div>
        </div>
        <div class="dosen-stat-box">
            <div class="angka">{{ $mahasiswaReviewer->count() }}</div>
            <div class="label">Total Mahasiswa Direview</div>
        </div>
    </div>

    {{-- SECTION: DAFTAR MAHASISWA --}}
    <div class="section-header">
        <div>
            <div class="section-title">
                Daftar Mahasiswa Reviewer
                @if($mahasiswaReviewer->count() > 0)
                <span class="badge-count">{{ $mahasiswaReviewer->count() }} mahasiswa</span>
                @endif
            </div>
            <div class="section-sub">Menampilkan semua mahasiswa aktif yang ditinjau oleh reviewer ini.</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <div class="inline-search-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#aaa;pointer-events:none;">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
                </svg>
                <input type="text" id="searchMahasiswa" placeholder="Cari nama atau NIM..." oninput="filterMahasiswa()">
            </div>
            <button class="btn-tambah-mhs" onclick="bukaModalTambah()">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16"><path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/></svg>
                + Tambah Mahasiswa
            </button>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-card">
        @if($mahasiswaReviewer->count() > 0)
        <table class="tbl" id="tabelMahasiswa">
            <thead>
                <tr>
                    <th style="width:48px;" class="center">No.</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Judul Tugas Akhir</th>
                    <th class="center">Status Proposal</th>
                    <th class="center">Aksi</th>
                </tr>
            </thead>
            <tbody id="tbodyMahasiswa">
                @foreach($mahasiswaReviewer as $i => $mhs)
                @php
                    $inisialM = strtoupper(substr($mhs->nama, 0, 1));
                    $bgM = $colors[abs(crc32($mhs->nim_nid)) % count($colors)];
                    $statusMhs = strtolower($mhs->status ?? '');
                @endphp
                <tr data-search="{{ strtolower($mhs->nim_nid . ' ' . $mhs->nama) }}">
                    <td class="center" style="color:#94a3b8;font-weight:600;">{{ $i+1 }}</td>
                    <td style="font-family:monospace;font-size:0.82rem;color:#64748b;">{{ $mhs->nim_nid }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <span class="avatar-sm" style="background:{{ $bgM }};">{{ $inisialM }}</span>
                            <span style="font-weight:600;">{{ $mhs->nama }}</span>
                        </div>
                    </td>
                    <td style="max-width:320px;line-height:1.5;color:#555;font-size:0.82rem;">
                        {{ $mhs->judul }}
                    </td>
                    <td class="center">
                        @if($statusMhs === 'selesai')
                            <span class="badge-selesai">Selesai</span>
                        @else
                            <span class="badge-review">Menunggu Review</span>
                        @endif
                    </td>
                    <td class="center">
                        <button class="btn-hapus"
                            onclick="konfirmasiHapus({{ $mhs->id }}, '{{ addslashes($mhs->nama) }}')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>
                            Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="noMhsResult" style="display:none;" class="modal-empty">
            Mahasiswa tidak ditemukan.
        </div>
        @else
        {{-- EMPTY STATE --}}
        <div class="empty-wrap">
            <div class="empty-inner">
                <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" fill="#e2e8f0" viewBox="0 0 16 16">
                    <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM6.354 9.854a.5.5 0 0 1-.707-.707l2-2a.5.5 0 0 1 .707 0l2 2a.5.5 0 0 1-.707.707L9 8.707V12.5a.5.5 0 0 1-1 0V8.707L6.354 9.854z"/>
                </svg>
                <div class="empty-title">Belum Ada Mahasiswa Ditugaskan</div>
                <div class="badge-count" style="margin:0;">0 Mahasiswa Direview</div>
                <div class="empty-sub">Reviewer ini belum memiliki mahasiswa yang ditugaskan. Klik tombol "Tambah Mahasiswa" untuk mulai menetapkan mahasiswa yang akan direview.</div>
                <button class="btn-tambah-mhs" onclick="bukaModalTambah()" style="margin-top:8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16"><path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/></svg>
                    + Tambah Mahasiswa
                </button>
            </div>
        </div>
        @endif
    </div>

</div>

{{-- ══════════════════════════════════════════════
     MODAL: TAMBAH MAHASISWA
════════════════════════════════════════════════ --}}
<div id="modalTambah" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-head">
            <div class="modal-head-title">Tambah Mahasiswa</div>
            <div class="modal-head-sub">Pilih mahasiswa yang belum memiliki reviewer untuk ditugaskan ke dosen ini.</div>
            <button class="modal-close" onclick="tutupModalTambah()">✕</button>
        </div>
        <div class="modal-search-wrap" style="position:relative;">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16" class="modal-search-icon">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
            </svg>
            <input type="text" class="modal-search" id="searchModal" placeholder="Cari mahasiswa..." oninput="filterModal()">
        </div>
        <div class="modal-list" id="modalList">
            @if($mahasiswaBelumReviewer->count() > 0)
                {{-- Header checklist all --}}
                <div style="padding:8px 28px 4px;display:flex;align-items:center;gap:10px;">
                    <input type="checkbox" id="checkAll" onchange="toggleAll(this)" style="width:17px;height:17px;accent-color:#FACC15;cursor:pointer;">
                    <label for="checkAll" style="font-size:0.78rem;color:#64748b;font-weight:600;cursor:pointer;">Pilih Semua</label>
                </div>
                @foreach($mahasiswaBelumReviewer as $mhs)
                @php
                    $inisialMd = strtoupper(substr($mhs->nama, 0, 1));
                    $bgMd = $colors[abs(crc32($mhs->nim_nid)) % count($colors)];
                @endphp
                <div class="modal-mhs-row" data-modal-search="{{ strtolower($mhs->nim_nid . ' ' . $mhs->nama . ' ' . $mhs->judul) }}" onclick="toggleCheck(this)">
                    <input type="checkbox" class="mhs-checkbox" value="{{ $mhs->id }}" onclick="event.stopPropagation();updateCount();">
                    <span class="avatar-sm" style="background:{{ $bgMd }};font-size:0.7rem;">{{ $inisialMd }}</span>
                    <div class="modal-mhs-info">
                        <div class="modal-mhs-nama">{{ $mhs->nama }}</div>
                        <div class="modal-mhs-nim">{{ $mhs->nim_nid }}</div>
                        <div class="modal-mhs-judul" title="{{ $mhs->judul }}">{{ $mhs->judul }}</div>
                    </div>
                </div>
                @endforeach
                <div class="paging-info" id="pagingInfo">Menampilkan {{ $mahasiswaBelumReviewer->count() }} mahasiswa unassigned</div>
            @else
                <div class="modal-empty">
                    Semua mahasiswa sudah memiliki reviewer yang ditugaskan.
                </div>
            @endif
        </div>
        <div class="modal-footer">
            <div class="modal-count"><span id="countTerpilih">0</span> Terpilih</div>
            <div style="display:flex;gap:10px;">
                <button class="btn-modal-batal" onclick="tutupModalTambah()">Batal</button>
                <button class="btn-modal-simpan" id="btnSimpanTambah" onclick="submitTambahMahasiswa()" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/></svg>
                    Tambahkan Mahasiswa
                </button>
            </div>
        </div>
    </div>
</div>

{{-- POPUP BERHASIL --}}
<div id="popupBerhasil" class="popup-overlay">
    <div class="popup-box">
        <div class="popup-icon-wrap success">✓</div>
        <div class="popup-title">Berhasil!</div>
        <div class="popup-msg" id="popupBerhasilMsg">Mahasiswa berhasil ditambahkan ke reviewer.</div>
        <button class="popup-btn" onclick="window.location.reload()">OK</button>
    </div>
</div>

{{-- POPUP GAGAL --}}
<div id="popupGagal" class="popup-overlay">
    <div class="popup-box">
        <div class="popup-icon-wrap error">✕</div>
        <div class="popup-title">Gagal!</div>
        <div class="popup-msg">Terjadi kesalahan. Silakan coba lagi.</div>
        <button class="popup-btn" onclick="tutupPopup('popupGagal')" style="background:#dc3545;color:#fff;">OK</button>
    </div>
</div>

{{-- KONFIRMASI HAPUS --}}
<div id="konfirmHapus" class="konfirm-overlay">
    <div class="konfirm-box">
        <div style="font-size:2.5rem;margin-bottom:12px;">⚠️</div>
        <div class="konfirm-title">Hapus Penugasan?</div>
        <div class="konfirm-msg" id="konfirmMsg">Mahasiswa ini akan dilepas dari reviewer. Anda dapat menetapkan reviewer baru nanti.</div>
        <div class="konfirm-btns">
            <button class="konfirm-batal" onclick="tutupKonfirm()">Batal</button>
            <button class="konfirm-hapus" onclick="eksekusiHapus()">Ya, Hapus</button>
        </div>
    </div>
</div>

<script>
const nimReviewer = '{{ $dosen->nim_nid }}';
let hapusProposalId = null;

// ── FILTER TABEL UTAMA ──
function filterMahasiswa() {
    const q = document.getElementById('searchMahasiswa').value.toLowerCase().trim();
    const rows = document.querySelectorAll('#tbodyMahasiswa tr');
    let visible = 0;
    rows.forEach(r => {
        const s = (r.getAttribute('data-search') || '').toLowerCase();
        const show = !q || s.includes(q);
        r.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    const noResult = document.getElementById('noMhsResult');
    if (noResult) noResult.style.display = visible === 0 ? 'block' : 'none';
}

// ── MODAL TAMBAH ──
function bukaModalTambah() {
    document.getElementById('searchModal').value = '';
    filterModal();
    document.querySelectorAll('.mhs-checkbox').forEach(cb => cb.checked = false);
    const checkAll = document.getElementById('checkAll');
    if (checkAll) checkAll.checked = false;
    updateCount();
    document.getElementById('modalTambah').classList.add('active');
}
function tutupModalTambah() { document.getElementById('modalTambah').classList.remove('active'); }
document.getElementById('modalTambah').addEventListener('click', function(e) {
    if (e.target === this) tutupModalTambah();
});

function filterModal() {
    const q = document.getElementById('searchModal').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.modal-mhs-row');
    rows.forEach(r => {
        const s = (r.getAttribute('data-modal-search') || '').toLowerCase();
        r.style.display = !q || s.includes(q) ? '' : 'none';
    });
}

function toggleCheck(row) {
    const cb = row.querySelector('.mhs-checkbox');
    if (cb) { cb.checked = !cb.checked; updateCount(); }
}

function toggleAll(master) {
    document.querySelectorAll('.mhs-checkbox').forEach(cb => {
        const row = cb.closest('.modal-mhs-row');
        if (row && row.style.display !== 'none') cb.checked = master.checked;
    });
    updateCount();
}

function updateCount() {
    const checked = document.querySelectorAll('.mhs-checkbox:checked').length;
    document.getElementById('countTerpilih').textContent = checked;
    const btn = document.getElementById('btnSimpanTambah');
    btn.disabled = checked === 0;
}

function submitTambahMahasiswa() {
    const checkedIds = [];
    document.querySelectorAll('.mhs-checkbox:checked').forEach(cb => checkedIds.push(cb.value));
    if (checkedIds.length === 0) return;

    const btn = document.getElementById('btnSimpanTambah');
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('nim_nid_reviewer', nimReviewer);
    checkedIds.forEach(id => formData.append('proposal_ids[]', id));

    fetch('{{ url("/proposal/reviewer/".$dosen->nim_nid."/tambah-mahasiswa") }}', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => {
        tutupModalTambah();
        if (res.ok) {
            document.getElementById('popupBerhasilMsg').textContent =
                checkedIds.length + ' mahasiswa berhasil ditambahkan ke reviewer.';
            bukaPopup('popupBerhasil');
        } else {
            bukaPopup('popupGagal');
        }
    })
    .catch(() => { tutupModalTambah(); bukaPopup('popupGagal'); });
}

// ── HAPUS ──
function konfirmasiHapus(proposalId, namaMhs) {
    hapusProposalId = proposalId;
    document.getElementById('konfirmMsg').textContent =
        '"' + namaMhs + '" akan dilepas dari reviewer ini. Anda dapat menetapkan reviewer baru nanti.';
    document.getElementById('konfirmHapus').classList.add('active');
}
function tutupKonfirm() { document.getElementById('konfirmHapus').classList.remove('active'); }

function eksekusiHapus() {
    if (!hapusProposalId) return;

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'DELETE');

    fetch('{{ url("/proposal") }}/' + hapusProposalId + '/remove-reviewer', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => {
        tutupKonfirm();
        if (res.ok) {
            document.getElementById('popupBerhasilMsg').textContent = 'Penugasan berhasil dihapus.';
            bukaPopup('popupBerhasil');
        } else {
            bukaPopup('popupGagal');
        }
    })
    .catch(() => { tutupKonfirm(); bukaPopup('popupGagal'); });
}

// ── POPUP ──
function bukaPopup(id) { document.getElementById(id).classList.add('active'); }
function tutupPopup(id) { document.getElementById(id).classList.remove('active'); }
</script>

@endsection