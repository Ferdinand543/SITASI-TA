@extends('layouts.app')

@section('title', 'Riwayat Bimbingan')

@section('content')

<style>
    :root {
        --gold: #C9A227;
        --gold-lt: #FEF9EC;
        --gold-border: #F5D97A;
        --neutral: #1E293B;
        --muted: #6B7280;
        --border: #E5E7EB;
        --white: #ffffff;
        --bg: #F5F6FA;
        --radius: 16px;
    }

    .bimb-wrap {
        background: var(--bg);
        min-height: 100vh;
    }

    .info-banner {
        background: #FFFBEB;
        border: 1px solid #FDE68A;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 13px;
        color: #92400E;
    }

    .info-banner svg {
        flex-shrink: 0;
        margin-top: 1px;
    }

    .bimb-hero {
        background-image: url('{{ asset("images/bg.jpeg") }}');
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        padding: 36px 40px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .bimb-hero::before {
        content: '';
        position: absolute;
        right: -40px;
        top: -40px;
        width: 200px;
        height: 200px;
        background: rgba(201, 162, 39, .12);
        border-radius: 50%;
    }

    .bimb-hero::after {
        content: '';
        position: absolute;
        right: 60px;
        bottom: -60px;
        width: 150px;
        height: 150px;
        background: rgba(201, 162, 39, .08);
        border-radius: 50%;
    }

    .bimb-hero-title {
        font-size: 26px;
        font-weight: 800;
        color: #735C00;
        margin-bottom: 6px;
    }

    .bimb-hero-sub {
        font-size: 13px;
        color: #92400E;
        margin-bottom: 20px;
    }

    .btn-tambah {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--gold);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s;
        text-decoration: none;
    }

    .btn-tambah:hover {
        background: #b08a1e;
        color: #fff;
    }

    .bimb-grid {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 20px;
        align-items: start;
    }

    .upload-card {
        background: var(--white);
        border-radius: var(--radius);
        padding: 22px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
        border: 1px solid var(--border);
        position: sticky;
        top: 20px;
    }

    .upload-card-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--neutral);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-group {
        margin-bottom: 14px;
    }

    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
        margin-bottom: 5px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 9px 12px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        color: var(--neutral);
        background: #FAFAFA;
        outline: none;
        transition: border .2s;
        font-family: inherit;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: var(--gold);
        background: #fff;
    }

    .form-control[readonly] {
        color: var(--muted);
        cursor: not-allowed;
    }

    .dropzone {
        border: 2px dashed var(--gold-border);
        border-radius: 10px;
        background: var(--gold-lt);
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: .2s;
        position: relative;
    }

    .dropzone:hover {
        border-color: var(--gold);
        background: #FEF3C7;
    }

    .dropzone input[type=file] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    .dropzone-icon {
        font-size: 28px;
        margin-bottom: 6px;
    }

    .dropzone-text {
        font-size: 12px;
        color: var(--muted);
        font-weight: 500;
    }

    .dropzone-hint {
        font-size: 11px;
        color: #9CA3AF;
        margin-top: 3px;
    }

    .btn-kirim {
        width: 100%;
        background: var(--gold);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 11px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        margin-top: 4px;
        transition: .2s;
        font-family: inherit;
    }

    .btn-kirim:hover {
        background: #b08a1e;
    }

    .tabel-card {
        background: var(--white);
        border-radius: var(--radius);
        box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .tabel-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .tabel-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--neutral);
        white-space: nowrap;
    }

    .tabel-search {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        flex-wrap: wrap;
        min-width: 0;
        max-width: 480px;
    }

    .search-input {
        flex: 1;
        min-width: 140px;
        padding: 8px 12px 8px 34px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        outline: none;
        background: #FAFAFA url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='none' viewBox='0 0 24 24' stroke='%236B7280' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 10px center;
        font-family: inherit;
        transition: border .2s;
    }

    .search-input:focus {
        border-color: var(--gold);
        background-color: #fff;
    }

    .filter-select {
        padding: 8px 12px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        outline: none;
        background: #FAFAFA;
        font-family: inherit;
        cursor: pointer;
        white-space: nowrap;
    }

    .filter-select:focus {
        border-color: var(--gold);
    }

    .btn-reset {
        padding: 8px 14px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        background: #fff;
        color: var(--muted);
        cursor: pointer;
        transition: .2s;
        font-family: inherit;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .btn-reset:hover {
        border-color: var(--gold);
        color: var(--gold);
    }

    .tabel-scroll {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }

    thead th {
        padding: 12px 16px;
        font-size: 12px;
        font-weight: 700;
        color: var(--muted);
        text-align: left;
        background: #FAFAFA;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    tbody tr {
        border-bottom: 1px solid #F3F4F6;
        transition: background .15s;
    }

    tbody tr:last-child {
        border-bottom: none;
    }

    tbody tr:hover {
        background: #FAFBFF;
    }

    tbody td {
        padding: 13px 16px;
        font-size: 13px;
        color: var(--neutral);
        vertical-align: middle;
    }

    .badge-pertemuan {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: var(--gold-lt);
        border: 1.5px solid var(--gold-border);
        border-radius: 8px;
        font-size: 13px;
        font-weight: 800;
        color: var(--gold);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 99px;
        font-size: 11.5px;
        font-weight: 600;
    }

    .status-baru {
        background: #EFF6FF;
        color: #1D4ED8;
        border: 1px solid #BFDBFE;
    }

    .status-dilihat {
        background: #F0FDF4;
        color: #15803D;
        border: 1px solid #BBF7D0;
    }

    .btn-aksi {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1.5px solid var(--border);
        background: #fff;
        color: var(--muted);
        cursor: pointer;
        transition: .2s;
        text-decoration: none;
    }

    .btn-aksi:hover {
        border-color: var(--gold);
        color: var(--gold);
        background: var(--gold-lt);
    }

    .empty-row td {
        text-align: center;
        padding: 48px;
        color: var(--muted);
        font-size: 14px;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .45);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-box {
        background: #fff;
        border-radius: 20px;
        width: 100%;
        max-width: 560px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
        animation: modalIn .25s ease;
    }

    @keyframes modalIn {
        from {
            transform: scale(.95) translateY(10px);
            opacity: 0;
        }

        to {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    .modal-head {
        padding: 22px 24px 16px;
        border-bottom: 2px dashed var(--gold-border);
        position: relative;
    }

    .modal-head h5 {
        font-size: 17px;
        font-weight: 800;
        color: var(--neutral);
        margin-bottom: 3px;
    }

    .modal-head p {
        font-size: 12.5px;
        color: var(--muted);
        margin: 0;
    }

    .modal-close {
        position: absolute;
        top: 18px;
        right: 20px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: none;
        background: #F3F4F6;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--muted);
        transition: .2s;
    }

    .modal-close:hover {
        background: #E5E7EB;
        color: var(--neutral);
    }

    .modal-body {
        padding: 20px 24px;
    }

    .modal-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .modal-row.full {
        grid-template-columns: 1fr;
    }

    .modal-foot {
        padding: 16px 24px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-batal {
        padding: 10px 22px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        background: #fff;
        font-size: 13.5px;
        font-weight: 600;
        color: var(--muted);
        cursor: pointer;
        font-family: inherit;
        transition: .2s;
    }

    .btn-batal:hover {
        border-color: #D1D5DB;
        background: #F9FAFB;
    }

    .btn-simpan {
        padding: 10px 22px;
        border: none;
        border-radius: 10px;
        background: var(--gold);
        font-size: 13.5px;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        font-family: inherit;
        transition: .2s;
    }

    .btn-simpan:hover {
        background: #b08a1e;
    }

    .modal-dropzone {
        border: 2px dashed var(--gold-border);
        border-radius: 10px;
        background: var(--gold-lt);
        padding: 22px;
        text-align: center;
        cursor: pointer;
        position: relative;
    }

    .modal-dropzone input {
        position: absolute;
        inset: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .modal-dropzone-icon {
        font-size: 30px;
    }

    .modal-dropzone-text {
        font-size: 12.5px;
        color: var(--muted);
        margin-top: 6px;
        font-weight: 500;
    }

    .modal-dropzone-hint {
        font-size: 11px;
        color: #9CA3AF;
        margin-top: 3px;
    }

    @media (max-width: 900px) {
        .bimb-grid {
            grid-template-columns: 1fr;
        }

        .upload-card {
            position: static;
        }
    }

    @media (max-width: 600px) {
        .tabel-search {
            max-width: 100%;
            width: 100%;
        }

        .tabel-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="bimb-wrap">

    <div class="bimb-hero">
        <div>
            <div class="bimb-hero-title">Riwayat Bimbingan</div>
            <div class="bimb-hero-sub">Kelola dan lihat riwayat bimbingan Anda</div>
            <button class="btn-tambah" onclick="bukaModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Riwayat Bimbingan
            </button>
        </div>
    </div>

    {{-- INFO BANNER --}}
    <div class="info-banner">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#D97706" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
        </svg>
        <span>Unggah proposal terlebih dahulu untuk ditinjau dosen pembimbing sebelum mengisi riwayat bimbingan. Pastikan file dalam format PDF untuk memudahkan proses <em>preview</em> oleh dosen.</span>
    </div>

    <div class="bimb-grid">

        {{-- UPLOAD CARD --}}
        <div class="upload-card">
            <div class="upload-card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#C9A227" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                </svg>
                Upload Proposal
            </div>
            <form action="{{ route('bimbingan.proposal.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">NIM</label>
                    <input type="text" class="form-control" value="{{ $user->nim_nid }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Mahasiswa</label>
                    <input type="text" class="form-control" value="{{ $user->nama ?? $user->name ?? '-' }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal_pengajuan" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Judul</label>
                    <input type="text" name="judul" class="form-control" placeholder="Masukkan judul proposal lengkap..." required>
                </div>

                {{-- ✅ FIX: Dosen pembimbing sekarang muncul dari $dosenList yang sudah difix --}}
                <div class="form-group">
                    <label class="form-label">Dosen Pembimbing</label>
                    <select name="dosen_nid" class="form-control">
                        <option value="">Pilih Pembimbing</option>
                        @forelse($dosenList as $dosen)
                            <option value="{{ $dosen->nim_nid_dosen }}">{{ $dosen->nama ?? $dosen->nim_nid_dosen }}</option>
                        @empty
                            <option value="" disabled>Belum ada dosen pembimbing ditetapkan</option>
                        @endforelse
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">File Proposal (PDF/DOC/DOCX)</label>
                    <div class="dropzone" id="dropzoneUpload">
                        <input type="file" name="file_proposal" accept=".pdf,.doc,.docx" onchange="updateDropzone(this,'dropzoneUpload')">
                        <div class="dropzone-icon">☁️</div>
                        <div class="dropzone-text" id="dropzoneUploadText">Klik untuk unggah atau seret file</div>
                        <div class="dropzone-hint">Maksimal ukuran file 10MB</div>
                    </div>
                </div>
                <button type="submit" class="btn-kirim">Kirim Proposal</button>
            </form>
        </div>

        {{-- TABEL --}}
        <div class="tabel-card">
            <div class="tabel-header">
                <div class="tabel-title">Data Riwayat Bimbingan</div>
                <div class="tabel-search">
                    <input type="text" class="search-input" id="searchInput" placeholder="Cari topik atau dosen..." oninput="filterTabel()">
                    <select class="filter-select" id="filterStatus" onchange="filterTabel()">
                        <option value="">Semua Status</option>
                        <option value="Baru Dikirim">Baru Dikirim</option>
                        <option value="Sudah Dilihat">Sudah Dilihat</option>
                    </select>
                    <button class="btn-reset" onclick="resetFilter()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        Reset
                    </button>
                </div>
            </div>

            <div class="tabel-scroll">
                <table id="tabelBimbingan">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Bimbingan Ke-</th>
                            <th>Tanggal</th>
                            <th>Judul</th>
                            <th>Topik Bimbingan</th>
                            <th>Dosen Pembimbing</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bimbingan as $i => $b)
                        <tr data-topik="{{ strtolower($b->topik_bimbingan) }}" data-status="{{ $b->status }}" data-foto="{{ $b->dokumentasi }}">
                            <td>{{ $i + 1 }}</td>
                            <td><span class="badge-pertemuan">{{ str_pad($b->pertemuan_ke, 2, '0', STR_PAD_LEFT) }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($b->tanggal_bimbingan)->translatedFormat('d M Y') }}</td>
                            <td style="max-width:160px; font-size:12px;">{{ Str::limit($judulTA, 40) }}</td>
                            <td style="max-width:180px; font-size:12px;">{{ Str::limit($b->topik_bimbingan, 50) }}</td>
                            <td style="font-size:12px;">
                                @php $namaD = $dosenList->firstWhere('nim_nid_dosen', (string) $b->dosen_nid); @endphp
                                {{ $namaD->nama ?? '—' }}
                            </td>
                            <td>
                                <a href="#" class="btn-aksi" title="Lihat Foto" onclick="lihatDetail('{{ $b->dokumentasi }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr class="empty-row">
                            <td colspan="7">
                                <div style="font-size:32px;margin-bottom:8px;">📋</div>
                                Belum ada riwayat bimbingan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- MODAL TAMBAH BIMBINGAN --}}
<div class="modal-overlay" id="modalOverlay" onclick="tutupModalLuar(event)">
    <div class="modal-box">
        <div class="modal-head">
            <button class="modal-close" onclick="tutupModal()">×</button>
            <h5>Tambah Riwayat Bimbingan</h5>
            <p>Lengkapi data bimbingan tugas akhir Anda.</p>
        </div>
        <form action="{{ route('bimbingan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="modal-row" style="margin-bottom:14px;">
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Nama Mahasiswa</label>
                        <input type="text" class="form-control" value="{{ $user->nama ?? $user->name ?? '-' }}" readonly>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" value="{{ $user->nim_nid }}" readonly>
                    </div>
                </div>
                <div class="modal-row" style="margin-bottom:14px;">
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Tanggal Bimbingan</label>
                        <input type="date" name="tanggal_bimbingan" class="form-control" required>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Bimbingan Ke-</label>
                        <input type="number" name="pertemuan_ke" class="form-control" placeholder="Contoh: 3" min="1" required>
                    </div>
                </div>
                <div class="form-group modal-row full" style="margin-bottom:14px;">
                    <div>
                        <label class="form-label">Dosen Pembimbing</label>
                        {{-- ✅ FIX: pakai $semuaDosen dari controller (= $dosenList yang sudah difix) --}}
                        <select name="dosen_nid" class="form-control">
                            <option value="">-- Pilih Pembimbing --</option>
                            @forelse($semuaDosen as $dosen)
                                <option value="{{ $dosen->nim_nid_dosen }}">{{ $dosen->nama }}</option>
                            @empty
                                <option value="" disabled>Belum ada dosen pembimbing ditetapkan</option>
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="form-group modal-row full" style="margin-bottom:14px;">
                    <div>
                        <label class="form-label">Judul Tugas Akhir</label>
                        <input type="text" class="form-control" value="{{ $judulTA }}" readonly style="background:#F9FAFB;">
                    </div>
                </div>
                <div class="form-group modal-row full" style="margin-bottom:14px;">
                    <div>
                        <label class="form-label">Topik Bimbingan</label>
                        <textarea name="topik_bimbingan" class="form-control" rows="3" placeholder="Jelaskan poin-poin diskusi bimbingan..." required style="resize:vertical;"></textarea>
                    </div>
                </div>
                <div class="form-group modal-row full" style="margin-bottom:0;">
                    <div>
                        <label class="form-label">Dokumentasi Foto</label>
                        <div class="modal-dropzone" id="modalDropzone">
                            <input type="file" name="dokumentasi" accept=".jpg,.jpeg,.png" onchange="updateDropzone(this,'modalDropzone')">
                            <div class="modal-dropzone-icon">🖼️</div>
                            <div class="modal-dropzone-text" id="modalDropzoneText">Klik atau seret foto dokumentasi untuk diunggah</div>
                            <div class="modal-dropzone-hint">Format: JPG, PNG (Maks. 5MB)</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn-batal" onclick="tutupModal()">Batal</button>
                <button type="submit" class="btn-simpan">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL LIHAT FOTO --}}
<div class="modal-overlay" id="modalFoto" onclick="tutupModalFoto(event)">
    <div class="modal-box" style="max-width:420px;">
        <div class="modal-head">
            <button class="modal-close" onclick="document.getElementById('modalFoto').classList.remove('show')">×</button>
            <h5>Dokumentasi Bimbingan</h5>
            <p>Foto dokumentasi saat bimbingan berlangsung.</p>
        </div>
        <div class="modal-body" style="text-align:center; padding:24px;">
            <img id="fotoPreview" src="" alt="Foto Dokumentasi"
                style="width:100%; border-radius:12px; object-fit:cover; display:none;">
            <div id="noFotoMsg" style="display:none; padding:32px 0;">
                <div style="font-size:48px; margin-bottom:12px;">📷</div>
                <p style="color:#9CA3AF; font-size:14px; margin:0;">Tidak ada dokumentasi foto untuk bimbingan ini.</p>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn-simpan" onclick="document.getElementById('modalFoto').classList.remove('show'); document.body.style.overflow='';"
                style="display:inline-flex; align-items:center; gap:7px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali
            </button>
        </div>
    </div>
</div>

<script>
    function bukaModal() {
        document.getElementById('modalOverlay').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function tutupModal() {
        document.getElementById('modalOverlay').classList.remove('show');
        document.body.style.overflow = '';
    }

    function tutupModalLuar(e) {
        if (e.target === document.getElementById('modalOverlay')) tutupModal();
    }

    function tutupModalFoto(e) {
        if (e.target === document.getElementById('modalFoto')) {
            document.getElementById('modalFoto').classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    function lihatDetail(foto) {
        const modal = document.getElementById('modalFoto');
        const img = document.getElementById('fotoPreview');
        const noMsg = document.getElementById('noFotoMsg');

        if (foto && foto !== '' && foto !== 'null') {
            img.src = '/uploads/bimbingan/' + foto;
            img.style.display = 'block';
            noMsg.style.display = 'none';
        } else {
            img.style.display = 'none';
            noMsg.style.display = 'block';
        }
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function updateDropzone(input, id) {
        const el = document.getElementById(id === 'dropzoneUpload' ? 'dropzoneUploadText' : 'modalDropzoneText');
        if (input.files.length > 0) el.textContent = '✅ ' + input.files[0].name;
    }

    function filterTabel() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        const status = document.getElementById('filterStatus').value;
        document.querySelectorAll('#tabelBimbingan tbody tr:not(.empty-row)').forEach(row => {
            const match = (!q || (row.dataset.topik || '').includes(q)) && (!status || row.dataset.status === status);
            row.style.display = match ? '' : 'none';
        });
    }

    function resetFilter() {
        document.getElementById('searchInput').value = '';
        document.getElementById('filterStatus').value = '';
        filterTabel();
    }
</script>

@endsection
