@extends('layouts.app')

@section('content')

@php
$nimSesi = session('user')->nim_nid;
$rolesDb = \Illuminate\Support\Facades\DB::table('dosen_roles')
    ->where('nim_nid', $nimSesi)
    ->pluck('role_dosen')
    ->toArray();
$isAdmin    = strtolower(trim(session('user')->role)) === 'admin';
$isReviewer = in_array('reviewer', $rolesDb) || $isAdmin;

// Tab aktif — default ke 'pembimbing', bisa dari query string ?tab=reviewer
$activeTab = request('tab', 'pembimbing');

// Siapkan data reviewer untuk modal (jumlah proposal per reviewer)
$reviewerDataJs = $reviewerListDropdown->map(function($rv) {
    $jumlah = \Illuminate\Support\Facades\DB::table('proposal')
        ->where('nim_nid_reviewer', $rv->nim_nid)
        ->whereIn('status', ['menunggu_review', 'selesai'])
        ->count();
    return [
        'nim_nid' => $rv->nim_nid,
        'nama'    => $rv->nama,
        'jumlah'  => $jumlah,
    ];
});
@endphp

<style>
    .page-wrap { padding: 0 0 48px; }

    /* ── HERO ── */
    .hero-section {
        background-image: url('/images/1.jpeg');
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        padding: 40px 48px;
        margin-bottom: 24px;
        min-height: 200px;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }
    .hero-section h2 { font-size: 2rem; font-weight: 800; color: #7a4f00; margin-bottom: 8px; }
    .hero-section p  { font-size: 0.88rem; color: #a07030; margin-bottom: 20px; max-width: 480px; }
    .hero-btn-group  { display: flex; gap: 10px; flex-wrap: wrap; }

    .btn-hero-primary {
        background: #FFE083; color: #6C5700; border: none; border-radius: 10px;
        padding: 10px 20px; font-size: 0.84rem; font-weight: 700;
        text-decoration: none; display: inline-flex; align-items: center; gap: 7px; transition: 0.2s;
        cursor: pointer;
    }
    .btn-hero-primary:hover { background: #fdd835; color: #6C5700; }

    .btn-hero-outline {
        background: transparent; color: #4a3000; border: 1.5px solid #d4a01e;
        border-radius: 10px; padding: 10px 20px; font-size: 0.84rem; font-weight: 700;
        text-decoration: none; display: inline-flex; align-items: center; gap: 7px; transition: 0.2s;
        cursor: pointer;
    }
    .btn-hero-outline:hover { background: rgba(212,160,30,0.1); color: #4a3000; }

    /* ── TABS (hidden — switching tetap jalan lewat tombol hero) ── */
    .tab-nav {
        display: none !important;
    }

    .tab-content { display: none; }
    .tab-content.active { display: block; }

    /* ── STAT CARDS ── */
    .stat-row {
        display: grid; gap: 1px; background: #e5e7eb;
        border-radius: 16px; overflow: hidden; margin-bottom: 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .stat-row.cols-3 { grid-template-columns: repeat(3, 1fr); }
    .stat-row.cols-4 { grid-template-columns: repeat(4, 1fr); }
    .stat-card { background: #fff; padding: 20px 24px; display: flex; align-items: center; gap: 14px; }
    .stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .stat-icon.blue   { background: #eff6ff; }
    .stat-icon.yellow { background: #fefce8; }
    .stat-icon.purple { background: #f3e8ff; }
    .stat-icon.green  { background: #f0fdf4; }
    .stat-num   { font-size: 1.8rem; font-weight: 800; color: #111; line-height: 1; margin-bottom: 4px; }
    .stat-label { font-size: 0.74rem; color: #64748b; font-weight: 500; line-height: 1.3; text-transform: uppercase; letter-spacing: 0.04em; }

    /* ── FILTER BAR ── */
    .filter-bar { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; flex-wrap: wrap; }
    .search-wrap { position: relative; flex: 1; min-width: 240px; }
    .search-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #aaa; pointer-events: none; }
    .search-wrap input {
        width: 100%; padding: 10px 12px 10px 36px; border: 1px solid #e5e7eb; border-radius: 10px;
        font-size: 0.85rem; background: #fff; outline: none; transition: border-color 0.15s; box-sizing: border-box;
    }
    .search-wrap input:focus { border-color: #FACC15; box-shadow: 0 0 0 3px rgba(250,204,21,0.15); }
    .filter-right { display: flex; gap: 10px; align-items: center; }
    .filter-select {
        padding: 10px 32px 10px 12px; border-radius: 10px; border: 1px solid #e5e7eb;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%23888' d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") no-repeat right 10px center;
        font-size: 0.85rem; color: #333; cursor: pointer; outline: none;
        -webkit-appearance: none; appearance: none; min-width: 200px; height: 42px;
    }
    .filter-select:focus { border-color: #FACC15; }
    .btn-reset {
        padding: 10px 16px; border-radius: 10px; border: 1px solid #e5e7eb;
        background: #fff; color: #666; font-size: 0.84rem; cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px; white-space: nowrap; transition: 0.15s;
    }
    .btn-reset:hover { background: #f5f5f5; }

    /* ── TABLE ── */
    .table-card {
        background: #fff; border-radius: 16px; border: 1px solid #f0f0f0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow-x: auto;
    }
    .tbl { width: 100%; border-collapse: collapse; min-width: 1200px; }
    .tbl thead tr { background: #fafafa; border-bottom: 1px solid #f0f0f0; }
    .tbl th { padding: 12px 14px; font-size: 0.75rem; font-weight: 700; color: #64748b; text-align: left; text-transform: uppercase; letter-spacing: 0.04em; white-space: nowrap; }
    .tbl th.center { text-align: center; }
    .tbl td { padding: 14px 14px; font-size: 0.83rem; color: #333; border-bottom: 1px solid #f5f5f5; vertical-align: middle; }
    .tbl tbody tr:last-child td { border-bottom: none; }
    .tbl tbody tr:hover td { background: #fffde7; transition: 0.1s; }
    .tbl-reviewer { min-width: 700px; }

    .file-pill {
        display: inline-flex; align-items: center; gap: 6px;
        background: #fff1f2; border: 1px solid #fecdd3; border-radius: 8px;
        padding: 5px 10px; font-size: 0.78rem; font-weight: 600; color: #be123c;
        text-decoration: none; transition: 0.15s;
    }
    .file-pill:hover { background: #ffe4e6; color: #9f1239; }

    .dosen-name  { font-weight: 700; font-size: 0.82rem; color: #111; }
    .dosen-nidn  { font-size: 0.72rem; color: #94a3b8; margin-top: 1px; }
    .role-badge  { display: inline-block; margin-top: 4px; font-size: 0.66rem; padding: 2px 8px; border-radius: 5px; font-weight: 700; }
    .rb-pembimbing { background: #e0f2fe; color: #0369a1; }
    .rb-usulan     { background: #fef9c3; color: #854d0e; }

    /* STATUS PILLS */
    .status-pill { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 20px; font-size: 0.74rem; font-weight: 700; white-space: nowrap; }
    .sp-menunggu-verifikasi { background: #fff3cd; color: #856404; border: 1px solid #ffd96a; }
    .sp-menunggu-review     { background: #CCE5FF; color: #004085; border: 1px solid #b8daff; }
    .sp-selesai  { background: #d4edda; color: #28a745; border: 1px solid #b7dfbb; }
    .sp-ditolak  { background: #f8d7da; color: #dc3545; border: 1px solid #f1aeb5; }
    .sp-sudah-ditugaskan { background: #d4edda; color: #28a745; border: 1px solid #b7dfbb; }
    .sp-belum-ditugaskan { background: #fff3cd; color: #856404; border: 1px solid #ffd96a; }

    /* ACTION BUTTONS */
    .btn-verifikasi {
        background: #FFE083; color: #6C5700; border: none; border-radius: 8px;
        padding: 7px 16px; font-size: 0.8rem; font-weight: 700; text-decoration: none;
        display: inline-flex; align-items: center; gap: 5px; transition: 0.15s; white-space: nowrap;
    }
    .btn-verifikasi:hover { background: #fdd835; color: #6C5700; }

    .btn-detail {
        background: #fff; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px;
        padding: 7px 16px; font-size: 0.8rem; font-weight: 700; text-decoration: none;
        display: inline-flex; align-items: center; gap: 5px; transition: 0.15s; white-space: nowrap;
    }
    .btn-detail:hover { background: #f8fafc; border-color: #FACC15; color: #333; }

    .btn-tetapkan {
        background: #FACC15; color: #333; border: none; border-radius: 8px;
        padding: 7px 16px; font-size: 0.8rem; font-weight: 700; cursor: pointer;
        display: inline-flex; align-items: center; gap: 5px; transition: 0.15s; white-space: nowrap;
        text-decoration: none;
    }
    .btn-tetapkan:hover { background: #e6b800; color: #333; }

    .btn-kelola {
        background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px;
        padding: 7px 16px; font-size: 0.8rem; font-weight: 700; cursor: pointer;
        display: inline-flex; align-items: center; gap: 5px; transition: 0.15s; white-space: nowrap;
        text-decoration: none;
    }
    .btn-kelola:hover { background: #e2e8f0; color: #333; }

    .avatar-inisial {
        width: 36px; height: 36px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.8rem; font-weight: 700; color: #fff; flex-shrink: 0;
    }

    .section-label { font-size: 0.95rem; font-weight: 800; color: #111; margin-bottom: 4px; }
    .section-desc  { font-size: 0.82rem; color: #888; margin-bottom: 14px; }

    .warning-box {
        display: flex; align-items: flex-start; gap: 10px;
        background: #fffbe6; border: 1px solid #ffe082; border-radius: 12px;
        padding: 14px 18px; margin-bottom: 14px;
    }
    .warning-box-icon  { color: #f59e0b; font-size: 1rem; flex-shrink: 0; margin-top: 2px; }
    .warning-box-title { font-size: 0.85rem; font-weight: 700; color: #856404; margin-bottom: 2px; }
    .warning-box-desc  { font-size: 0.78rem; color: #856404; }

    .empty-state-wrap  { padding: 60px 20px; text-align: center; }
    .empty-state-inner { display: inline-flex; flex-direction: column; align-items: center; gap: 12px; }
    .empty-state-icon  { width: 64px; height: 64px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .empty-state-icon svg { color: #94a3b8; }
    .empty-state-title { font-size: 0.95rem; font-weight: 700; color: #475569; }
    .empty-state-sub   { font-size: 0.82rem; color: #94a3b8; }

    /* ══════════════════════════════════
       MODAL BARU — Tetapkan Reviewer dari Mahasiswa
    ══════════════════════════════════ */
    .modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,0.5); z-index: 9999;
        align-items: center; justify-content: center;
    }
    .modal-overlay.active { display: flex; }

    .modal-rv-box {
        background: #fff; border-radius: 20px;
        width: 100%; max-width: 600px; margin: 0 16px;
        box-shadow: 0 16px 48px rgba(0,0,0,0.2);
        position: relative; display: flex; flex-direction: column;
        max-height: 90vh;
    }

    .modal-rv-head {
        padding: 24px 28px 18px;
        border-bottom: 1px solid #f0f0f0; flex-shrink: 0;
    }
    .modal-rv-head-title { font-size: 1.15rem; font-weight: 800; color: #111; margin-bottom: 4px; }
    .modal-rv-head-sub   { font-size: 0.82rem; color: #888; }
    .modal-rv-close {
        position: absolute; top: 18px; right: 18px;
        background: #f1f5f9; border: none; border-radius: 50%;
        width: 32px; height: 32px; font-size: 1rem; color: #555;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
    }
    .modal-rv-close:hover { background: #e2e8f0; }

    .modal-rv-mhs-card {
        margin: 16px 28px;
        background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 12px;
        padding: 16px 20px;
        display: grid; grid-template-columns: 1fr 1fr; gap: 8px 20px;
        flex-shrink: 0;
    }
    .modal-rv-mhs-card .field-lbl  { font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px; }
    .modal-rv-mhs-card .field-val  { font-size: 0.88rem; font-weight: 700; color: #111; }
    .modal-rv-mhs-card .field-judul { grid-column: 1 / -1; }
    .modal-rv-mhs-card .field-judul .field-val { font-style: italic; font-weight: 600; color: #374151; }

    .modal-rv-section-head {
        padding: 0 28px 10px;
        display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;
    }
    .modal-rv-section-title {
        font-size: 0.88rem; font-weight: 800; color: #111;
        display: flex; align-items: center; gap: 7px;
    }
    .modal-rv-search-wrap { position: relative; }
    .modal-rv-search-wrap svg { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #aaa; pointer-events: none; }
    .modal-rv-search {
        padding: 8px 12px 8px 32px; border: 1px solid #e5e7eb; border-radius: 10px;
        font-size: 0.82rem; outline: none; width: 200px; background: #fff;
    }
    .modal-rv-search:focus { border-color: #FACC15; }

    .modal-rv-table-wrap { flex: 1; overflow-y: auto; padding: 0 28px; }
    .modal-rv-tbl { width: 100%; border-collapse: collapse; }
    .modal-rv-tbl thead tr { background: #f8f9fa; }
    .modal-rv-tbl th {
        padding: 10px 12px; font-size: 0.7rem; font-weight: 700; color: #64748b;
        text-align: left; text-transform: uppercase; letter-spacing: 0.04em;
        border-bottom: 1px solid #e5e7eb; white-space: nowrap;
    }
    .modal-rv-tbl td {
        padding: 12px 12px; font-size: 0.84rem; color: #333;
        border-bottom: 1px solid #f5f5f5; vertical-align: middle;
    }
    .modal-rv-tbl tbody tr:last-child td { border-bottom: none; }
    .modal-rv-tbl tbody tr:hover td { background: #fffde7; cursor: pointer; }
    .modal-rv-tbl tbody tr.selected td { background: #fffbe6; }

    .radio-custom {
        width: 18px; height: 18px; border-radius: 50%;
        border: 2px solid #d1d5db; background: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: 0.15s; cursor: pointer;
    }
    .radio-custom.checked { border-color: #FACC15; background: #FACC15; }
    .radio-custom.checked::after {
        content: ''; width: 7px; height: 7px; border-radius: 50%; background: #fff;
    }

    .badge-jumlah {
        background: #f1f5f9; border: 1px solid #e2e8f0;
        border-radius: 20px; padding: 3px 10px;
        font-size: 0.75rem; font-weight: 700; color: #475569;
        white-space: nowrap;
    }

    .modal-rv-preview {
        margin: 12px 28px 0;
        flex-shrink: 0;
    }
    .modal-rv-preview-lbl {
        font-size: 0.7rem; font-weight: 700; color: #94a3b8;
        text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;
    }
    .modal-rv-preview-card {
        background: #fff; border: 1.5px solid #FACC15; border-radius: 12px;
        padding: 14px 18px; display: flex; align-items: center; gap: 16px;
    }
    .modal-rv-preview-card .pv-nama { font-size: 0.88rem; font-weight: 700; color: #111; }
    .modal-rv-preview-card .pv-nid  { font-size: 0.75rem; color: #94a3b8; margin-top: 2px; font-family: monospace; }
    .modal-rv-preview-card .pv-stat { font-size: 0.75rem; font-weight: 700; color: #856404; background: #fffbe6; border: 1px solid #ffe082; border-radius: 20px; padding: 3px 10px; white-space: nowrap; }
    .modal-rv-preview-empty {
        background: #f8f9fa; border: 1.5px dashed #e2e8f0; border-radius: 12px;
        padding: 14px 18px; text-align: center; font-size: 0.82rem; color: #94a3b8;
    }

    .modal-rv-footer {
        padding: 16px 28px; border-top: 1px solid #f0f0f0;
        display: flex; align-items: center; justify-content: space-between;
        flex-shrink: 0; gap: 12px;
    }
    .btn-rv-batal {
        padding: 10px 24px; border-radius: 10px; border: 1.5px solid #e5e7eb;
        background: #fff; color: #555; font-size: 0.9rem; font-weight: 600; cursor: pointer;
    }
    .btn-rv-batal:hover { background: #f5f5f5; }
    .btn-rv-simpan {
        padding: 10px 24px; border-radius: 10px; border: none;
        background: #FACC15; color: #333; font-size: 0.9rem; font-weight: 700; cursor: pointer;
        display: inline-flex; align-items: center; gap: 7px;
    }
    .btn-rv-simpan:hover { background: #e6b800; }
    .btn-rv-simpan:disabled { background: #e5e7eb; color: #aaa; cursor: not-allowed; }

    /* POPUP */
    .popup-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 99999; align-items: center; justify-content: center; }
    .popup-overlay.active { display: flex; }
    .popup-box { background: #fff; border-radius: 20px; padding: 40px 32px 32px; width: 100%; max-width: 400px; margin: 0 16px; box-shadow: 0 12px 40px rgba(0,0,0,0.2); text-align: center; }
    .popup-icon-wrap { width: 80px; height: 80px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; font-size: 2.2rem; }
    .popup-icon-wrap.success { background: #e8f5e9; border: 3px solid #66bb6a; color: #28a745; }
    .popup-icon-wrap.error   { background: #fdecea; border: 3px solid #ef9a9a; color: #dc3545; }
    .popup-title { font-size: 1.5rem; font-weight: 800; color: #222; margin-bottom: 10px; }
    .popup-msg   { font-size: 0.92rem; color: #555; margin-bottom: 28px; line-height: 1.5; }
    .popup-btn-row { display: flex; gap: 12px; justify-content: center; }
    .popup-btn { padding: 11px 32px; border-radius: 10px; font-size: 0.95rem; font-weight: 700; cursor: pointer; border: none; transition: 0.2s; }
    .popup-btn.ok { background: #FACC15; color: #333; min-width: 120px; }
    .popup-btn.ok:hover { background: #e6b800; }
</style>

<div class="container-fluid px-4 page-wrap">

    {{-- HERO --}}
    <div class="hero-section mb-4">
        <div>
            <h2>Pengajuan Proposal TA-1</h2>
            @if($activeTab === 'reviewer')
                <p>Kelola penugasan reviewer proposal tugas akhir kepada dosen reviewer secara terstruktur dan merata.</p>
            @else
                <p>Verifikasi dan tetapkan dosen pembimbing tugas akhir mahasiswa untuk menjamin kualitas akademik.</p>
            @endif
            <div class="hero-btn-group">
                {{-- Tombol Penetapan Dosen Pembimbing --}}
                <a href="javascript:void(0)"
                   onclick="gantiTab('pembimbing')"
                   id="hero-btn-pembimbing"
                   class="{{ $activeTab === 'pembimbing' ? 'btn-hero-primary' : 'btn-hero-outline' }}">
                    <i class="fa fa-user-check"></i>
                    Penetapan Dosen Pembimbing
                </a>

                {{-- Tombol Penetapan Reviewer --}}
                <a href="javascript:void(0)"
                   onclick="gantiTab('reviewer')"
                   id="hero-btn-reviewer"
                   class="{{ $activeTab === 'reviewer' ? 'btn-hero-primary' : 'btn-hero-outline' }}">
                    <i class="fa fa-user-shield"></i>
                    Penetapan Reviewer
                    @if($mahasiswaBelumReviewer->count() > 0)
                        <span style="background:#ef4444;color:#fff;border-radius:20px;font-size:0.7rem;padding:1px 7px;font-weight:700;">
                            {{ $mahasiswaBelumReviewer->count() }}
                        </span>
                    @endif
                </a>

                {{-- Tombol Review Proposal (hanya untuk reviewer/admin) --}}
                @if($isReviewer)
                <a href="{{ route('reviewer.proposal') }}" class="btn-hero-outline">
                    <i class="fa fa-file-circle-check"></i>
                    Review Proposal
                </a>
                @endif
            </div>
        </div>
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

    {{-- TAB NAVIGATION — disembunyikan, switching tetap jalan lewat tombol hero di atas --}}
    <div class="tab-nav" style="display:none;">
        <button class="tab-btn {{ $activeTab === 'pembimbing' ? 'active' : '' }}" onclick="gantiTab('pembimbing')">
            <i class="fa fa-user-check"></i>
            Penetapan Dosen Pembimbing
        </button>
        <button class="tab-btn {{ $activeTab === 'reviewer' ? 'active' : '' }}" onclick="gantiTab('reviewer')">
            <i class="fa fa-user-shield"></i>
            Penetapan Reviewer
            @if($mahasiswaBelumReviewer->count() > 0)
                <span style="background:#ef4444;color:#fff;border-radius:20px;font-size:0.7rem;padding:1px 7px;font-weight:700;">
                    {{ $mahasiswaBelumReviewer->count() }}
                </span>
            @endif
        </button>
    </div>

    {{-- TAB 1: PENETAPAN DOSEN PEMBIMBING --}}
    <div class="tab-content {{ $activeTab === 'pembimbing' ? 'active' : '' }}" id="tab-pembimbing">

        @php
        $total          = $proposals->count();
        $menungguVerif  = $proposals->filter(fn($p) => strtolower($p->status) === 'menunggu_verifikasi')->count();
        $menungguReview = $proposals->filter(fn($p) => strtolower($p->status) === 'menunggu_review')->count();
        $selesai        = $proposals->filter(fn($p) => strtolower($p->status) === 'selesai')->count();
        $sudahVerif     = $proposals->filter(fn($p) => in_array(strtolower($p->status), ['menunggu_review','selesai','ditolak']))->count();
        @endphp

        <div class="stat-row mb-4 {{ $isReviewer ? 'cols-4' : 'cols-3' }}">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#2563eb" viewBox="0 0 16 16"><path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/></svg>
                </div>
                <div><div class="stat-num">{{ $total }}</div><div class="stat-label">Total Proposal</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon yellow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#ca8a04" viewBox="0 0 16 16"><path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/></svg>
                </div>
                <div><div class="stat-num">{{ $menungguVerif }}</div><div class="stat-label">Menunggu Verifikasi</div></div>
            </div>
            @if($isReviewer)
            <div class="stat-card">
                <div class="stat-icon purple">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#7c3aed" viewBox="0 0 16 16"><path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/></svg>
                </div>
                <div><div class="stat-num">{{ $menungguReview }}</div><div class="stat-label">Menunggu Review</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#16a34a" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg>
                </div>
                <div><div class="stat-num">{{ $selesai }}</div><div class="stat-label">Selesai</div></div>
            </div>
            @else
            <div class="stat-card">
                <div class="stat-icon green">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#16a34a" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg>
                </div>
                <div><div class="stat-num">{{ $sudahVerif }}</div><div class="stat-label">Sudah Diverifikasi</div></div>
            </div>
            @endif
        </div>

        @if($isReviewer)
        <form method="GET" action="{{ url('/proposal') }}">
            <input type="hidden" name="tab" value="pembimbing">
            <div class="filter-bar mb-4">
                <div class="search-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/></svg>
                    <input type="text" name="search" id="searchInputServer" placeholder="Masukkan NIM, nama, atau kata kunci judul..." value="{{ request('search') }}">
                </div>
                <div class="filter-right">
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="menunggu_verifikasi" {{ request('status')=='menunggu_verifikasi'?'selected':'' }}>Menunggu Verifikasi</option>
                        <option value="menunggu_review"     {{ request('status')=='menunggu_review'?'selected':'' }}>Menunggu Review</option>
                        <option value="selesai"             {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
                        
                    </select>
                    <button type="button" class="btn-reset" onclick="window.location.href='{{ url('/proposal?tab=pembimbing') }}'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/><path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/></svg>
                        Reset Filter
                    </button>
                </div>
            </div>
        </form>
        @else
        <div class="filter-bar mb-4">
            <div class="search-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/></svg>
                <input type="text" id="searchInput" placeholder="Masukkan NIM, nama, atau kata kunci judul...">
            </div>
            <div class="filter-right">
                <select id="filterStatus" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                    <option value="selesai">Selesai</option>
                    
                </select>
                <button type="button" class="btn-reset" onclick="resetFilter()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/><path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/></svg>
                    Reset Filter
                </button>
            </div>
        </div>
        @endif

        <div class="table-card" id="tableCard">
            <table class="tbl" id="mainTable">
                <thead>
                    <tr>
                        <th class="center" style="width:48px;">No.</th>
                        <th>Tanggal</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Judul</th>
                        <th>Proposal</th>
                        <th>Pembimbing 1</th>
                        <th>Pembimbing 2</th>
                        <th class="center">Status</th>
                        <th class="center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabelBody">
                    @forelse($proposals as $i => $p)
                    @php $status = strtolower(trim($p->status)); @endphp
                    <tr data-status="{{ $status }}"
                        data-search="{{ strtolower($p->nim_nid . ' ' . $p->nama . ' ' . $p->judul) }}">
                        <td class="center" style="color:#94a3b8;font-weight:600;">{{ $i+1 }}</td>
                        <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d M Y') }}</td>
                        <td style="font-family:monospace;font-size:0.82rem;">{{ $p->nim_nid }}</td>
                        <td style="font-weight:600;">{{ $p->nama }}</td>
                        <td style="max-width:200px;line-height:1.5;">{{ $p->judul }}</td>
                        <td>
                            @if($p->file_proposal)
                            <a href="{{ asset('storage/'.$p->file_proposal) }}" target="_blank" class="file-pill">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/></svg>
                                Lihat PDF
                            </a>
                            @else
                            <span style="color:#cbd5e1;">-</span>
                            @endif
                        </td>
                        <td style="min-width:180px;">
                            @if($p->dosen1_nama)
                                <div class="dosen-name">{{ $p->dosen1_nama }}</div>
                                <div class="dosen-nidn">NIDN. {{ $p->dosen1_nidn }}</div>
                                <span class="role-badge rb-pembimbing">PEMBIMBING TA</span>
                            @elseif($p->usulan_dosen1_nama)
                                <div class="dosen-name">{{ $p->usulan_dosen1_nama }}</div>
                                <div class="dosen-nidn">NIDN. {{ $p->usulan_dosen1_nidn }}</div>
                                <span class="role-badge rb-usulan">USULAN PEMBIMBING</span>
                            @else
                                <span style="color:#cbd5e1;">-</span>
                            @endif
                        </td>
                        <td style="min-width:180px;">
                            @if($p->dosen2_nama)
                                <div class="dosen-name">{{ $p->dosen2_nama }}</div>
                                <div class="dosen-nidn">NIDN. {{ $p->dosen2_nidn }}</div>
                                <span class="role-badge rb-pembimbing">PEMBIMBING TA</span>
                            @elseif($p->usulan_dosen2_nama)
                                <div class="dosen-name">{{ $p->usulan_dosen2_nama }}</div>
                                <div class="dosen-nidn">NIDN. {{ $p->usulan_dosen2_nidn }}</div>
                                <span class="role-badge rb-usulan">USULAN PEMBIMBING</span>
                            @else
                                <span style="color:#cbd5e1;">-</span>
                            @endif
                        </td>
                        <td class="center">
                            @if($status==='menunggu_verifikasi')
                                <span class="status-pill sp-menunggu-verifikasi">Menunggu Verifikasi</span>
                            @elseif($status==='menunggu_review')
                                <span class="status-pill sp-menunggu-review">Menunggu Review</span>
                            @elseif($status==='selesai')
                                <span class="status-pill sp-selesai">Selesai</span>
                            @elseif($status==='ditolak')
                                <span class="status-pill sp-ditolak">Ditolak</span>
                            @else
                                <span style="color:#94a3b8;font-size:0.82rem;">{{ $p->status }}</span>
                            @endif
                        </td>
                        <td class="center">
                            @if($status==='menunggu_verifikasi')
                                <a href="{{ url('/proposal/'.$p->id.'/verifikasi') }}" class="btn-verifikasi">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg>
                                    Verifikasi
                                </a>
                            @else
                                <a href="{{ url('/proposal/'.$p->id) }}" class="btn-detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>
                                    Detail
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr id="rowKosongDefault">
                        <td colspan="10">
                            <div class="empty-state-wrap">
                                <div class="empty-state-inner">
                                    <div class="empty-state-icon"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#94a3b8" viewBox="0 0 16 16"><path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/></svg></div>
                                    <div class="empty-state-title">Belum ada data</div>
                                    <div class="empty-state-sub">Data akan muncul setelah proses dilakukan.</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="noSearchResult" style="display:none;">
            <div class="table-card">
                <div class="empty-state-wrap">
                    <div class="empty-state-inner">
                        <div class="empty-state-icon"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#94a3b8" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/></svg></div>
                        <div class="empty-state-title">Data tidak ditemukan</div>
                        <div class="empty-state-sub">Coba gunakan kata kunci atau filter yang berbeda.</div>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- end tab pembimbing --}}

    {{-- TAB 2: PENETAPAN REVIEWER --}}
    <div class="tab-content {{ $activeTab === 'reviewer' ? 'active' : '' }}" id="tab-reviewer">

        @php
        $totalReviewer      = $dosenReviewerList->count();
        $reviewerSudah      = $dosenReviewerList->where('sudah_ditugaskan', true)->count();
        $totalBelumReviewer = $mahasiswaBelumReviewer->count();
        @endphp

        <div class="stat-row cols-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#2563eb" viewBox="0 0 16 16"><path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/><path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/></svg>
                </div>
                <div><div class="stat-num">{{ $totalReviewer }}</div><div class="stat-label">Total Dosen Reviewer</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon yellow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#ca8a04" viewBox="0 0 16 16"><path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/></svg>
                </div>
                <div><div class="stat-num">{{ $totalBelumReviewer }}</div><div class="stat-label">Mahasiswa Belum Ditugaskan</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#16a34a" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg>
                </div>
                <div><div class="stat-num">{{ $reviewerSudah }}</div><div class="stat-label">Dosen Memiliki Penugasan</div></div>
            </div>
        </div>

        <div class="section-label">Daftar Penetapan Reviewer Proposal</div>
        <div class="section-desc">Kelola penugasan proposal ke masing-masing dosen reviewer.</div>

        <div class="filter-bar mb-3">
            <div class="search-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/></svg>
                <input type="text" id="searchReviewer" placeholder="Cari NID atau nama dosen reviewer...">
            </div>
            <div class="filter-right">
                <select id="filterReviewerStatus" class="filter-select" style="min-width:160px;">
                    <option value="">Semua Status</option>
                    <option value="sudah">Memiliki Penugasan</option>
                    <option value="belum">Belum Ditugaskan</option>
                </select>
            </div>
        </div>

        <div class="table-card mb-4">
            <table class="tbl tbl-reviewer" id="tabelReviewer">
                <thead>
                    <tr>
                        <th>NID</th>
                        <th>Nama Dosen</th>
                        <th class="center">Jumlah Mahasiswa Review</th>
                        <th class="center">Status Penugasan</th>
                        <th class="center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dosenReviewerList as $dr)
                    @php
                        $inisial  = strtoupper(substr($dr->nama, 0, 1));
                        $colors   = ['#F59E0B','#10B981','#3B82F6','#8B5CF6','#EF4444','#EC4899','#14B8A6'];
                        $colorIdx = crc32($dr->nim_nid) % count($colors);
                        $bgColor  = $colors[abs($colorIdx)];
                    @endphp
                    <tr data-reviewer-search="{{ strtolower($dr->nim_nid . ' ' . $dr->nama) }}"
                        data-reviewer-status="{{ $dr->sudah_ditugaskan ? 'sudah' : 'belum' }}">
                        <td style="font-family:monospace;font-size:0.82rem;color:#64748b;">{{ $dr->nim_nid }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span class="avatar-inisial" style="background:{{ $bgColor }};">{{ $inisial }}</span>
                                <div class="dosen-name">{{ $dr->nama }}</div>
                            </div>
                        </td>
                        <td class="center">
                            <span style="background:#f1f5f9;border:1px solid #e2e8f0;border-radius:20px;padding:4px 14px;font-size:0.8rem;font-weight:700;color:#475569;">
                                {{ $dr->jumlah_proposal }} Mahasiswa
                            </span>
                        </td>
                        <td class="center">
                            @if($dr->sudah_ditugaskan)
                                <span class="status-pill sp-sudah-ditugaskan">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg>
                                    Memiliki Penugasan
                                </span>
                            @else
                                <span class="status-pill sp-belum-ditugaskan">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg>
                                    Belum Ditugaskan
                                </span>
                            @endif
                        </td>
                        <td class="center">
                            <a href="{{ url('/proposal/reviewer/'.$dr->nim_nid.'/kelola') }}" class="{{ $dr->sudah_ditugaskan ? 'btn-kelola' : 'btn-tetapkan' }}">
                                @if($dr->sudah_ditugaskan)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/></svg>
                                    Kelola Penugasan
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
                                    Tetapkan Reviewer
                                @endif
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state-wrap">
                                <div class="empty-state-inner">
                                    <div class="empty-state-title">Belum ada dosen reviewer</div>
                                    <div class="empty-state-sub">Tambahkan dosen dengan role reviewer terlebih dahulu.</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mahasiswa belum punya reviewer --}}
        @if($mahasiswaBelumReviewer->count() > 0)
        <div class="warning-box">
            <span class="warning-box-icon">⚠</span>
            <div>
                <div class="warning-box-title">Mahasiswa yang Memerlukan Penetapan Reviewer</div>
                <div class="warning-box-desc">Mahasiswa berikut belum memiliki reviewer dan memerlukan penetapan oleh koordinator.</div>
            </div>
        </div>

        <div class="table-card">
            <table class="tbl tbl-reviewer">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Judul Tugas Akhir</th>
                        <th class="center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mahasiswaBelumReviewer as $mhs)
                    @php
                        $inisialM  = strtoupper(substr($mhs->nama, 0, 1));
                        $colorIdxM = crc32($mhs->nim_nid) % count($colors);
                        $bgColorM  = $colors[abs($colorIdxM)];
                    @endphp
                    <tr>
                        <td style="font-family:monospace;font-size:0.82rem;color:#64748b;">{{ $mhs->nim_nid }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span class="avatar-inisial" style="background:{{ $bgColorM }};font-size:0.75rem;">{{ $inisialM }}</span>
                                <div style="font-weight:600;font-size:0.85rem;">{{ $mhs->nama }}</div>
                            </div>
                        </td>
                        <td style="max-width:300px;line-height:1.5;font-size:0.83rem;color:#555;">"{{ $mhs->judul }}"</td>
                        <td class="center">
                            <button type="button" class="btn-tetapkan"
                                onclick="bukaModalTetapkan({{ $mhs->id }},'{{ $mhs->nim_nid }}','{{ addslashes($mhs->nama) }}','{{ addslashes($mhs->judul) }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
                                 Tetapkan Reviewer
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="table-card">
            <div class="empty-state-wrap">
                <div class="empty-state-inner">
                    <div class="empty-state-icon"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#94a3b8" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg></div>
                    <div class="empty-state-title">Semua mahasiswa sudah punya reviewer</div>
                    <div class="empty-state-sub">Tidak ada proposal yang perlu ditetapkan reviewer.</div>
                </div>
            </div>
        </div>
        @endif

    </div>{{-- end tab reviewer --}}

</div>

{{-- ══════════════════════════════════════════════
     MODAL: Tetapkan Dosen Reviewer (dari sisi mahasiswa)
════════════════════════════════════════════════ --}}
<div id="modalTetapkanMhs" class="modal-overlay">
    <div class="modal-rv-box">

        {{-- Head --}}
        <div class="modal-rv-head">
            <div class="modal-rv-head-title">Tetapkan Dosen Reviewer</div>
            <div class="modal-rv-head-sub">Pilih dosen reviewer yang akan melakukan review proposal mahasiswa.</div>
            <button class="modal-rv-close" onclick="tutupModalMhs()">✕</button>
        </div>

        {{-- Info Mahasiswa --}}
        <div class="modal-rv-mhs-card">
            <div>
                <div class="field-lbl">NIM</div>
                <div class="field-val" id="rv-nim">—</div>
            </div>
            <div>
                <div class="field-lbl">Nama Mahasiswa</div>
                <div class="field-val" id="rv-nama">—</div>
            </div>
            <div class="field-judul">
                <div class="field-lbl">Judul Proposal</div>
                <div class="field-val" id="rv-judul">—</div>
            </div>
        </div>

        {{-- Section head: judul + search --}}
        <div class="modal-rv-section-head">
            <div class="modal-rv-section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#6C5700" viewBox="0 0 16 16"><path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/><path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/></svg>
                Daftar Dosen Reviewer
            </div>
            <div class="modal-rv-search-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/></svg>
                <input type="text" class="modal-rv-search" id="searchModalRv" placeholder="Cari nama dosen atau NID..." oninput="filterModalRv()">
            </div>
        </div>

        {{-- Tabel dosen reviewer --}}
        <div class="modal-rv-table-wrap">
            <table class="modal-rv-tbl" id="tabelModalRv">
                <thead>
                    <tr>
                        <th style="width:48px;">Pilih</th>
                        <th>NID</th>
                        <th>Nama Dosen</th>
                        <th class="center">Jumlah Mahasiswa Review</th>
                    </tr>
                </thead>
                <tbody id="tbodyModalRv">
                    @foreach($reviewerListDropdown as $rv)
                    @php
                        $jumlahRv = \Illuminate\Support\Facades\DB::table('proposal')
                            ->where('nim_nid_reviewer', $rv->nim_nid)
                            ->whereIn('status', ['menunggu_review', 'selesai'])
                            ->count();
                        $inisialRv = strtoupper(substr($rv->nama, 0, 1));
                        $colorsRv  = ['#F59E0B','#10B981','#3B82F6','#8B5CF6','#EF4444','#EC4899','#14B8A6'];
                        $bgRv      = $colorsRv[abs(crc32($rv->nim_nid)) % count($colorsRv)];
                    @endphp
                    <tr data-rv-nim="{{ $rv->nim_nid }}"
                        data-rv-nama="{{ $rv->nama }}"
                        data-rv-jumlah="{{ $jumlahRv }}"
                        data-rv-search="{{ strtolower($rv->nim_nid . ' ' . $rv->nama) }}"
                        onclick="pilihReviewer('{{ $rv->nim_nid }}','{{ addslashes($rv->nama) }}',{{ $jumlahRv }})"
                        style="cursor:pointer;">
                        <td>
                            <div class="radio-custom" id="radio-{{ $rv->nim_nid }}"></div>
                        </td>
                        <td style="font-family:monospace;font-size:0.82rem;color:#64748b;">{{ $rv->nim_nid }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span style="width:32px;height:32px;border-radius:50%;background:{{ $bgRv }};display:inline-flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;color:#fff;flex-shrink:0;">{{ $inisialRv }}</span>
                                <span style="font-weight:600;font-size:0.85rem;">{{ $rv->nama }}</span>
                            </div>
                        </td>
                        <td class="center">
                            <span class="badge-jumlah">{{ $jumlahRv }} Mahasiswa</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Preview reviewer terpilih --}}
        <div class="modal-rv-preview">
            <div class="modal-rv-preview-lbl">Reviewer Terpilih</div>
            <div id="previewReviewerEmpty" class="modal-rv-preview-empty">
                Belum ada reviewer yang dipilih
            </div>
            <div id="previewReviewerCard" class="modal-rv-preview-card" style="display:none;">
                <div style="flex:1;">
                    <div class="pv-nama" id="pv-nama">—</div>
                    <div class="pv-nid" id="pv-nid">—</div>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
                    <div style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.04em;">Total Mahasiswa</div>
                    <span class="pv-stat" id="pv-jumlah">0 Mahasiswa</span>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="modal-rv-footer">
            <button class="btn-rv-batal" onclick="tutupModalMhs()">Batal</button>
            <form id="formTetapkanMhs" method="POST" action="" style="margin:0;">
                @csrf
                <input type="hidden" name="redirect_to_list" value="1">
                <input type="hidden" name="nim_nid_reviewer" id="inputReviewerTerpilih" value="">
                <button type="submit" class="btn-rv-simpan" id="btnSimpanRv" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg>
                    Tetapkan Reviewer
                </button>
            </form>
        </div>

    </div>
</div>

{{-- POPUP BERHASIL --}}
<div id="popupBerhasil" class="popup-overlay">
    <div class="popup-box">
        <div class="popup-icon-wrap success">✓</div>
        <div class="popup-title">Berhasil!</div>
        <div class="popup-msg">Reviewer berhasil ditetapkan.</div>
        <div class="popup-btn-row">
            <button class="popup-btn ok" onclick="window.location.href='{{ url('/proposal?tab=reviewer') }}'">OK</button>
        </div>
    </div>
</div>

<div id="popupGagal" class="popup-overlay">
    <div class="popup-box">
        <div class="popup-icon-wrap error">✕</div>
        <div class="popup-title">Gagal!</div>
        <div class="popup-msg">Gagal menetapkan reviewer. Silakan coba lagi.</div>
        <div class="popup-btn-row">
            <button class="popup-btn ok" onclick="tutupPopup('popupGagal')">OK</button>
        </div>
    </div>
</div>

<script>
// ── TAB SWITCHING — dipakai oleh tombol hero dan tab nav (hidden) ──
function gantiTab(tab) {
    // Sembunyikan semua konten tab, tampilkan yang dipilih
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');

    // Update active state tab-btn (hidden, tapi tetap dikelola untuk konsistensi)
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(btn => {
        if (btn.getAttribute('onclick') && btn.getAttribute('onclick').includes("'" + tab + "'")) {
            btn.classList.add('active');
        }
    });

    // Update tampilan tombol hero: aktif = primary, tidak aktif = outline
    var btnPembimbing = document.getElementById('hero-btn-pembimbing');
    var btnReviewer   = document.getElementById('hero-btn-reviewer');
    if (tab === 'pembimbing') {
        btnPembimbing.className = 'btn-hero-primary';
        btnReviewer.className   = 'btn-hero-outline';
    } else {
        btnPembimbing.className = 'btn-hero-outline';
        btnReviewer.className   = 'btn-hero-primary';
    }

    // Update URL tanpa reload
    history.replaceState(null, '', '{{ url("/proposal") }}?tab=' + tab);
}

// ── CLIENT-SIDE FILTER TAB PEMBIMBING ──
@if(!$isReviewer)
const searchInput  = document.getElementById('searchInput');
const filterStatus = document.getElementById('filterStatus');
const tabelBody    = document.getElementById('tabelBody');
const tableCard    = document.getElementById('tableCard');
const noSearchResult = document.getElementById('noSearchResult');

function applyFilter() {
    const search = searchInput.value.toLowerCase().trim();
    const status = filterStatus.value.toLowerCase();
    const rows   = tabelBody.querySelectorAll('tr[data-status]');
    let visible  = 0;
    rows.forEach(function(row) {
        const rowSearch = (row.getAttribute('data-search') || '').toLowerCase();
        const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();
        let statusCocok = false;
        if (!status) { statusCocok = true; }
        else if (status === 'selesai') { statusCocok = (rowStatus === 'selesai' || rowStatus === 'menunggu_review'); }
        else { statusCocok = (rowStatus === status); }
        const searchCocok = !search || rowSearch.includes(search);
        const tampil = statusCocok && searchCocok;
        row.style.display = tampil ? '' : 'none';
        if (tampil) visible++;
    });
    const rowDefault = document.getElementById('rowKosongDefault');
    if (rowDefault) rowDefault.style.display = 'none';
    if (visible === 0 && rows.length > 0) {
        tableCard.style.display = 'none';
        noSearchResult.style.display = 'block';
    } else {
        tableCard.style.display = '';
        noSearchResult.style.display = 'none';
    }
}
searchInput.addEventListener('input', applyFilter);
filterStatus.addEventListener('change', applyFilter);
function resetFilter() { searchInput.value = ''; filterStatus.value = ''; applyFilter(); }
@else
let timeout = null;
document.getElementById('searchInputServer').addEventListener('input', function() {
    clearTimeout(timeout);
    timeout = setTimeout(() => { this.form.submit(); }, 500);
});
@endif

// ── FILTER TAB REVIEWER ──
const searchReviewer  = document.getElementById('searchReviewer');
const filterRevStatus = document.getElementById('filterReviewerStatus');
function applyReviewerFilter() {
    const search = searchReviewer ? searchReviewer.value.toLowerCase().trim() : '';
    const status = filterRevStatus ? filterRevStatus.value : '';
    const rows   = document.querySelectorAll('#tabelReviewer tbody tr[data-reviewer-search]');
    rows.forEach(function(row) {
        const rowSearch = (row.getAttribute('data-reviewer-search') || '').toLowerCase();
        const rowStatus = row.getAttribute('data-reviewer-status') || '';
        const searchCocok = !search || rowSearch.includes(search);
        const statusCocok = !status || rowStatus === status;
        row.style.display = searchCocok && statusCocok ? '' : 'none';
    });
}
if (searchReviewer)  searchReviewer.addEventListener('input', applyReviewerFilter);
if (filterRevStatus) filterRevStatus.addEventListener('change', applyReviewerFilter);

// ── POPUP ──
function bukaPopup(id) { document.getElementById(id).classList.add('active'); }
function tutupPopup(id) { document.getElementById(id).classList.remove('active'); }

// ── MODAL TETAPKAN REVIEWER ──
let selectedReviewerNim = '';

function bukaModalTetapkan(proposalId, nim, nama, judul) {
    selectedReviewerNim = '';
    document.getElementById('rv-nim').textContent   = nim;
    document.getElementById('rv-nama').textContent  = nama;
    document.getElementById('rv-judul').textContent = '"' + judul + '"';
    document.getElementById('inputReviewerTerpilih').value = '';
    document.getElementById('btnSimpanRv').disabled = true;
    document.getElementById('searchModalRv').value  = '';

    document.querySelectorAll('.radio-custom').forEach(el => el.classList.remove('checked'));
    document.querySelectorAll('#tabelModalRv tbody tr').forEach(r => {
        r.classList.remove('selected');
        r.style.display = '';
    });

    document.getElementById('previewReviewerEmpty').style.display = 'block';
    document.getElementById('previewReviewerCard').style.display  = 'none';

    document.getElementById('formTetapkanMhs').action = '{{ url("/proposal") }}/' + proposalId + '/assign-reviewer';

    document.getElementById('modalTetapkanMhs').classList.add('active');
}

function tutupModalMhs() {
    document.getElementById('modalTetapkanMhs').classList.remove('active');
}

function pilihReviewer(nim, nama, jumlah) {
    selectedReviewerNim = nim;

    document.querySelectorAll('.radio-custom').forEach(el => el.classList.remove('checked'));
    document.querySelectorAll('#tabelModalRv tbody tr').forEach(r => r.classList.remove('selected'));

    const radio = document.getElementById('radio-' + nim);
    if (radio) radio.classList.add('checked');

    const row = document.querySelector('#tabelModalRv tbody tr[data-rv-nim="' + nim + '"]');
    if (row) row.classList.add('selected');

    document.getElementById('pv-nama').textContent   = nama;
    document.getElementById('pv-nid').textContent    = 'NID: ' + nim;
    document.getElementById('pv-jumlah').textContent = jumlah + ' Mahasiswa';
    document.getElementById('previewReviewerEmpty').style.display = 'none';
    document.getElementById('previewReviewerCard').style.display  = 'flex';

    document.getElementById('inputReviewerTerpilih').value = nim;
    document.getElementById('btnSimpanRv').disabled = false;
}

function filterModalRv() {
    const q = document.getElementById('searchModalRv').value.toLowerCase().trim();
    document.querySelectorAll('#tbodyModalRv tr').forEach(r => {
        const s = (r.getAttribute('data-rv-search') || '').toLowerCase();
        r.style.display = !q || s.includes(q) ? '' : 'none';
    });
}

// Tutup modal kalau klik overlay
document.getElementById('modalTetapkanMhs').addEventListener('click', function(e) {
    if (e.target === this) tutupModalMhs();
});
</script>

@endsection