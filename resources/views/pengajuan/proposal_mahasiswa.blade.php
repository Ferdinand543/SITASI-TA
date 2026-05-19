@extends('layouts.app')

@section('content')

<style>
    * {
        box-sizing: border-box;
    }

    .page-wrapper {
        padding: 1.5rem 0 3rem;
    }

    .sub-menu {
        text-decoration: none;
        color: #666;
        padding: 7px 18px;
        border-radius: 20px;
        font-size: 0.84rem;
        font-weight: 500;
        transition: 0.2s;
    }

    .sub-menu:hover { background: #FFF3CD; color: #7a4f00; }
    .sub-menu.active { background: #FFC107; color: #4a3000; font-weight: 700; }

    .hero-card {
        background: url("{{ asset('images/psi.jpeg') }}") center / 100% 100% no-repeat;
        border-radius: 16px;
        padding: 2.5rem 2.5rem 2rem;
        margin-bottom: 1rem;
        position: relative;
        overflow: hidden;
        min-height: 160px;
    }

    .hero-card h1 {
        font-size: 2rem;
        font-weight: 800;
        color: #7a4f00;
        margin-bottom: 0.35rem;
        line-height: 1.2;
        position: relative;
        z-index: 1;
    }

    .hero-card p {
        font-size: 0.9rem;
        color: #a07030;
        margin-bottom: 1.3rem;
        position: relative;
        z-index: 1;
    }

    .btn-upload-hero {
        background: #FACC15;
        color: #6C5700;
        border: none;
        border-radius: 9px;
        padding: 10px 24px;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        position: relative;
        z-index: 1;
        transition: background 0.15s;
    }

    .btn-upload-hero:hover { background: #e09518; }

    .info-box {
        background: #FFE083;
        border: 1px solid #FFE083;
        border-left: 4px solid #735C00;
        color: #4D4632;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 0.84rem;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 1.5rem;
    }

    .alert { border-radius: 10px; font-size: 0.88rem; }

    .filter-bar {
        display: flex;
        gap: 12px;
        align-items: flex-end;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .filter-group label {
        display: block;
        font-size: 0.8rem;
        color: #888;
        margin-bottom: 4px;
        font-weight: 500;
    }

    .search-wrap {
        position: relative;
        min-width: 220px;
        flex: 1;
    }

    .search-wrap svg {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
        pointer-events: none;
    }

    .search-wrap input {
        width: 100%;
        padding: 9px 12px 9px 34px;
        border: 1px solid #e0e0e0;
        border-radius: 9px;
        font-size: 0.85rem;
        background: #fff;
        color: #333;
        outline: none;
        transition: border-color 0.15s;
    }

    .search-wrap input:focus {
        border-color: #FFC107;
        box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.15);
    }

    .filter-select {
        padding: 9px 32px 9px 12px;
        border-radius: 9px;
        border: 1px solid #e0e0e0;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%23888' d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") no-repeat right 10px center;
        color: #333;
        font-size: 0.85rem;
        cursor: pointer;
        outline: none;
        -webkit-appearance: none;
        appearance: none;
        min-width: 170px;
        transition: border-color 0.15s;
    }

    .filter-select:focus { border-color: #FFC107; }

    .btn-reset-filter {
        padding: 9px 16px;
        border-radius: 9px;
        border: 1px solid #e0e0e0;
        background: #fff;
        color: #666;
        font-size: 0.84rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.15s;
        white-space: nowrap;
    }

    .btn-reset-filter:hover { background: #f5f5f5; }

    .table-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    #tabelProposal { width: 100%; border-collapse: collapse; }
    #tabelProposal thead tr { background: #DEE8FF; }

    #tabelProposal th {
        padding: 12px 14px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #4a3000;
        text-align: left;
        border-bottom: 2px solid #fff;
        white-space: nowrap;
    }

    #tabelProposal th:first-child { text-align: center; width: 52px; }

    #tabelProposal td {
        padding: 12px 14px;
        font-size: 0.82rem;
        color: #333;
        border-bottom: 1px solid #f5f5f5;
        vertical-align: top;
    }

    #tabelProposal td:first-child { text-align: center; font-weight: 600; color: #999; }
    #tabelProposal tbody tr:last-child td { border-bottom: none; }
    #tabelProposal tbody tr:hover td { background: #FFFDE7; transition: background 0.1s; }

    .file-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #735C00;
        font-size: 0.8rem;
        text-decoration: none;
        font-weight: 600;
        background: #fff5f5;
        border-radius: 7px;
        padding: 5px 9px;
        border: 1px solid #f5c6cb;
        transition: background 0.15s;
        word-break: break-all;
        line-height: 1.3;
    }

    .file-link:hover { background: #ffe5e5; color: #a93226; }

    .dosbing-name { font-weight: 700; font-size: 0.82rem; color: #222; line-height: 1.35; }
    .dosbing-nidn { font-size: 0.74rem; color: #999; margin-top: 1px; }

    .dosbing-badge {
        display: inline-block;
        margin-top: 4px;
        font-size: 0.68rem;
        padding: 2px 8px;
        border-radius: 5px;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .badge-pembimbing { background: #E3F2FD; color: #1565C0; }
    .badge-usulan { background: #FFF8E1; color: #F57F17; }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 11px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        white-space: nowrap;
        letter-spacing: 0.01em;
    }

    .s-menunggu-verifikasi { background: #FFF3CD; color: #856404; border: 1px solid #ffd96a; }
    .s-menunggu-review { background: #CCE5FF; color: #004085; border: 1px solid #b8daff; }
    .s-selesai { background: #D4EDDA; color: #155724; border: 1px solid #c3e6cb; }
    .s-ditolak { background: #F8D7DA; color: #721c24; border: 1px solid #f1aeb5; }

    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 14px;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        background: #fff;
        color: #B86A00;
        font-size: 0.8rem;
        font-weight: 700;
        text-decoration: none;
        transition: background 0.15s, border-color 0.15s;
    }

    .btn-detail:hover { background: #FFF8E1; border-color: #FFC107; color: #7a4f00; }

    /* ── EMPTY STATE ── */
    .empty-state-wrap {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state-inner {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }
    .empty-state-icon {
        width: 64px;
        height: 64px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .empty-state-icon i { font-size: 1.8rem; color: #94a3b8; }
    .empty-state-title { font-size: 0.95rem; font-weight: 700; color: #475569; }
    .empty-state-sub   { font-size: 0.82rem; color: #94a3b8; }

    /* MODAL */
    .modal-content { border-radius: 20px; border: 0; }
    .modal-label {
        font-size: 0.82rem;
        font-weight: 700;
        color: #444;
        display: block;
        margin-bottom: 5px;
    }

    .modal-input {
        width: 100%;
        padding: 9px 13px;
        border-radius: 9px;
        border: 1px solid #e0e0e0;
        font-size: 0.84rem;
        color: #333;
        background: #fff;
        outline: none;
        transition: border-color 0.15s;
        -webkit-appearance: none;
        appearance: none;
    }

    .modal-input:focus {
        border-color: #FFC107;
        box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.15);
    }

    .modal-input.is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15);
    }

    .btn-modal-cancel {
        flex: 1;
        padding: 11px;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        background: #fff;
        color: #555;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s;
    }

    .btn-modal-cancel:hover { background: #f5f5f5; }

    .btn-modal-submit {
        flex: 1;
        padding: 11px;
        border-radius: 10px;
        border: none;
        background: #FFC107;
        color: #4a3000;
        font-size: 0.88rem;
        font-weight: 800;
        cursor: pointer;
        transition: background 0.15s;
    }

    .btn-modal-submit:hover { background: #e0a800; }

    .drop-zone {
        border: 2px dashed #e2c97e;
        border-radius: 12px;
        padding: 32px 20px;
        text-align: center;
        cursor: pointer;
        background: #fffdf5;
        transition: border-color 0.2s, background 0.2s;
    }

    .drop-zone:hover {
        border-color: #FACC15;
        background: #fffde7;
    }

    .drop-zone.dragover {
        border-color: #FACC15;
        background: #fffde7;
    }

    .drop-zone.has-file {
        border-color: #FACC15;
        background: #fffde7;
    }
</style>

<div class="container page-wrapper">

    {{-- ALERTS --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- HERO --}}
    <div class="hero-card mb-4">
        <br>
        <h1>Upload Proposal Tugas Akhir</h1>
        <p>Unggah proposal tugas akhir setelah judul disetujui</p>
        <button class="btn-upload-hero" data-bs-toggle="modal" data-bs-target="#modalUpload">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
            </svg>
            Upload Proposal
        </button>
    </div>

    {{-- INFO BOX --}}
    <div class="info-box">
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="#735C00" viewBox="0 0 16 16">
            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
        </svg>
        <span>Unggah proposal tugas akhir setelah judul disetujui. Pastikan dokumen sudah sesuai dengan pedoman penulisan akademik.</span>
    </div>

    {{-- FILTER BAR --}}
    <div class="filter-bar">
        <div class="filter-group">
            <label>Cari</label>
            <div class="search-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Cari">
            </div>
        </div>
        <div class="filter-group">
            <label>Status</label>
            <select id="filterStatus" class="filter-select">
                <option value="">Semua Status</option>
                <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                <option value="menunggu_review">Menunggu Review</option>
                <option value="selesai">Selesai</option>
                <option value="ditolak">Ditolak</option>
            </select>
        </div>
        <div class="filter-group">
            <label>&nbsp;</label>
            <button class="btn-reset-filter" id="btnReset">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                    <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                </svg>
                Reset Filter
            </button>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-card" id="tableCard">
        <table id="tabelProposal">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Judul</th>
                    <th>Proposal</th>
                    <th>Pembimbing 1</th>
                    <th>Pembimbing 2</th>
                    <th>Status</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody id="tabelBody">
                @forelse($proposalList as $index => $item)
                @php
                    $st = strtolower($item->status);
                    $usulan1 = $item->usulanPembimbing->where('urutan', 1)->first();
                    $usulan2 = $item->usulanPembimbing->where('urutan', 2)->first();
                    $final1  = $item->dosenPembimbing->where('urutan', 1)->first();
                    $final2  = $item->dosenPembimbing->where('urutan', 2)->first();
                    $namaUsulan1 = $usulan1 ? (\DB::table('users')->where('nim_nid', $usulan1->nim_nid_dosen)->value('nama') ?? '') : '';
                    $namaUsulan2 = $usulan2 ? (\DB::table('users')->where('nim_nid', $usulan2->nim_nid_dosen)->value('nama') ?? '') : '';
                    $namaFinal1  = $final1  ? (\DB::table('users')->where('nim_nid', $final1->nim_nid_dosen)->value('nama')  ?? '') : '';
                    $namaFinal2  = $final2  ? (\DB::table('users')->where('nim_nid', $final2->nim_nid_dosen)->value('nama')  ?? '') : '';
                @endphp
                <tr data-status="{{ $st }}"
                    data-search="{{ strtolower($item->judul . ' ' . $item->nim_nid . ' ' . ($usulan1->nim_nid_dosen ?? '') . ' ' . $namaUsulan1 . ' ' . ($usulan2->nim_nid_dosen ?? '') . ' ' . $namaUsulan2 . ' ' . ($final1->nim_nid_dosen ?? '') . ' ' . $namaFinal1 . ' ' . ($final2->nim_nid_dosen ?? '') . ' ' . $namaFinal2 . ' ' . basename($item->file_proposal ?? '')) }}">

                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d M Y') }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($item->judul, 40) }}</td>

                    <td>
                        @if($item->file_proposal)
                        <a href="{{ asset('storage/' . $item->file_proposal) }}" target="_blank" class="file-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                            </svg>
                            {{ \Illuminate\Support\Str::limit(basename($item->file_proposal), 22) }}
                        </a>
                        @else
                        <span style="color:#bbb;font-size:0.82rem;">-</span>
                        @endif
                    </td>

                    <td>
                        @php $d1 = \DB::table('users')->where('nim_nid', $final1->nim_nid_dosen ?? $usulan1->nim_nid_dosen ?? '')->first(); @endphp
                        @if($d1)
                            <div class="dosbing-name">{{ $d1->nama }}</div>
                            <div class="dosbing-nidn">NIDN: {{ $final1->nim_nid_dosen ?? $usulan1->nim_nid_dosen ?? '-' }}</div>
                            <span class="dosbing-badge {{ $final1 ? 'badge-pembimbing' : 'badge-usulan' }}">
                                {{ $final1 ? 'PEMBIMBING TA' : 'USULAN PEMBIMBING' }}
                            </span>
                        @else
                            <span style="color:#bbb;">-</span>
                        @endif
                    </td>

                    <td>
                        @php $d2 = \DB::table('users')->where('nim_nid', $final2->nim_nid_dosen ?? $usulan2->nim_nid_dosen ?? '')->first(); @endphp
                        @if($d2)
                            <div class="dosbing-name">{{ $d2->nama }}</div>
                            <div class="dosbing-nidn">NIDN: {{ $final2->nim_nid_dosen ?? $usulan2->nim_nid_dosen ?? '-' }}</div>
                            <span class="dosbing-badge {{ $final2 ? 'badge-pembimbing' : 'badge-usulan' }}">
                                {{ $final2 ? 'PEMBIMBING TA' : 'USULAN PEMBIMBING' }}
                            </span>
                        @else
                            <span style="color:#bbb;">-</span>
                        @endif
                    </td>

                    <td>
                        @if($st === 'selesai')
                            <span class="status-badge s-selesai">Selesai</span>
                        @elseif($st === 'menunggu_review')
                            <span class="status-badge s-menunggu-review">Menunggu Review</span>
                        @elseif($st === 'ditolak')
                            <span class="status-badge s-ditolak">Ditolak</span>
                        @else
                            <span class="status-badge s-menunggu-verifikasi">Menunggu Verifikasi</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('proposal.mahasiswa.detail', $item->id) }}" class="btn-detail">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                            </svg>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                {{-- DB kosong total → inbox icon --}}
                <tr id="rowKosongDefault">
                    <td colspan="8">
                        <div class="empty-state-wrap">
                            <div class="empty-state-inner">
                                <div class="empty-state-icon">
                                    <i class="fa fa-inbox"></i>
                                </div>
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

    {{-- Filter/search tidak nemu hasil → magnifier icon --}}
    <div id="noSearchResult" style="display:none;">
        <div class="table-card">
            <div class="empty-state-wrap">
                <div class="empty-state-inner">
                    <div class="empty-state-icon">
                        <i class="fa fa-magnifying-glass"></i>
                    </div>
                    <div class="empty-state-title">Data tidak ditemukan</div>
                    <div class="empty-state-sub">Coba gunakan kata kunci atau filter yang berbeda.</div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- MODAL UPLOAD --}}
<div class="modal fade" id="modalUpload" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:680px;">
        <div class="modal-content">

            <div class="modal-header" style="padding:24px 28px 12px; border-bottom:1px solid #f0f0f0;">
                <div>
                    <h2 style="font-size:1.2rem; font-weight:800; color:#1e293b; margin:0 0 4px;">Upload Proposal TA-1</h2>
                    <p style="font-size:0.82rem; color:#94a3b8; margin:0;">Lengkapi data berikut untuk mengunggah proposal tugas akhir Anda.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="padding:20px 28px;">

                <div id="errorAlert" class="alert alert-danger d-none mb-3" style="font-size:0.84rem;">
                    Semua field wajib diisi!
                </div>

                <form id="formUpload" action="{{ route('proposal.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- NIM | NAMA | TANGGAL --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="modal-label">NIM</label>
                            <input type="text" class="modal-input"
                                   value="{{ session('user')->nim_nid ?? '-' }}"
                                   readonly style="background:#f8fafc; color:#64748b;">
                        </div>
                        <div class="col-md-4">
                            <label class="modal-label">Mahasiswa</label>
                            <input type="text" class="modal-input"
                                   value="{{ session('user')->nama ?? '-' }}"
                                   readonly style="background:#f8fafc; color:#64748b;">
                        </div>
                        <div class="col-md-4">
                            <label class="modal-label">Tanggal</label>
                            <input type="date" name="tanggal_pengajuan" id="up_tanggal"
                                   class="modal-input" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    {{-- JUDUL --}}
                    <div class="mb-3">
                        <label class="modal-label">Judul Proposal</label>
                        <textarea name="judul" id="up_judul" class="modal-input"
                                  rows="3" placeholder="Masukkan judul proposal"
                                  style="resize:none;"></textarea>
                    </div>

                    {{-- DRAG DROP --}}
                    <div class="mb-3">
                        <label class="modal-label">Proposal</label>
                        <div id="dropZone" class="drop-zone" onclick="document.getElementById('up_file').click()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="#d4a01e" viewBox="0 0 16 16" style="margin-bottom:10px;">
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                <path d="M8 6.5a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 10.793V7a.5.5 0 0 1 .5-.5z"/>
                            </svg>
                            <div id="dropText" style="font-size:0.85rem; font-weight:600; color:#64748b;">
                                Klik atau seret file proposal untuk diunggah
                            </div>
                            <div style="font-size:0.75rem; color:#94a3b8; margin-top:4px;">Maksimal ukuran file: 10MB</div>
                        </div>
                        <input type="file" name="file_proposal" id="up_file" accept=".pdf" style="display:none;">
                    </div>

                    {{-- PEMBIMBING --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="modal-label">Usulan Pembimbing 1</label>
                            <select name="pembimbing_1" id="up_dosbing1" class="modal-input">
                                <option value="">Pilih dosen pembimbing</option>
                                @foreach($dosenList as $dosen)
                                <option value="{{ $dosen->nim_nid }}">{{ $dosen->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="modal-label">Usulan Pembimbing 2</label>
                            <select name="pembimbing_2" id="up_dosbing2" class="modal-input">
                                <option value="">Pilih dosen pembimbing</option>
                                @foreach($dosenList as $dosen)
                                <option value="{{ $dosen->nim_nid }}">{{ $dosen->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer" style="padding:12px 28px 24px; border-top:1px solid #f0f0f0;">
                <div class="d-flex gap-2 w-100">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="formUpload" class="btn-modal-submit">Upload Proposal</button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const filterStatus   = document.getElementById("filterStatus");
    const searchInput    = document.getElementById("searchInput");
    const btnReset       = document.getElementById("btnReset");
    const tableCard      = document.getElementById("tableCard");
    const noSearchResult = document.getElementById("noSearchResult");
    const tabelBody      = document.getElementById("tabelBody");

    function applyFilter() {
        const status  = filterStatus.value.toLowerCase();
        const keyword = searchInput.value.toLowerCase().trim();
        const rows    = tabelBody.querySelectorAll("tr[data-status]");
        let visible   = 0;

        rows.forEach(row => {
            const rowStatus = (row.dataset.status || "").toLowerCase();
            const rowSearch = (row.dataset.search || "").toLowerCase();
            const ok = (!status || rowStatus === status) && (!keyword || rowSearch.includes(keyword));
            row.style.display = ok ? "" : "none";
            if (ok) visible++;
        });

        // Sembunyikan row default kosong kalau ada data asli
        const rowDefault = document.getElementById("rowKosongDefault");
        if (rowDefault) rowDefault.style.display = "none";

        if (visible === 0 && rows.length > 0) {
            // Ada data tapi filter ga nemu → magnifier
            tableCard.style.display      = "none";
            noSearchResult.style.display = "block";
        } else {
            // Nemu data atau DB emang kosong → tabel normal
            tableCard.style.display      = "";
            noSearchResult.style.display = "none";
        }
    }

    filterStatus.addEventListener("change", applyFilter);
    searchInput.addEventListener("input", applyFilter);
    btnReset.addEventListener("click", function () {
        filterStatus.value = "";
        searchInput.value  = "";
        applyFilter();
    });

    // ── VALIDASI FORM ──
    const formUpload = document.getElementById("formUpload");
    const alertBox   = document.getElementById("errorAlert");
    const wajib      = ["up_judul", "up_tanggal", "up_dosbing1", "up_dosbing2"];

    formUpload.addEventListener("submit", function (e) {
        let isValid = true;
        wajib.forEach(id => document.getElementById(id).classList.remove("is-invalid"));

        wajib.forEach(id => {
            const el = document.getElementById(id);
            if (!el.value.trim()) {
                el.classList.add("is-invalid");
                isValid = false;
            }
        });

        const file = document.getElementById("up_file");
        if (!file.files.length) {
            document.getElementById("dropZone").style.borderColor = "#dc3545";
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            alertBox.classList.remove("d-none");
            return;
        }

        alertBox.classList.add("d-none");
    });

    // ── DRAG & DROP ──
    const dropZone  = document.getElementById("dropZone");
    const fileInput = document.getElementById("up_file");
    const dropText  = document.getElementById("dropText");

    fileInput.addEventListener("change", function () {
        if (this.files.length) {
            dropText.textContent = this.files[0].name;
            dropZone.classList.add("has-file");
        }
    });

    dropZone.addEventListener("dragover", function (e) {
        e.preventDefault();
        this.classList.add("dragover");
    });

    dropZone.addEventListener("dragleave", function () {
        this.classList.remove("dragover");
    });

    dropZone.addEventListener("drop", function (e) {
        e.preventDefault();
        this.classList.remove("dragover");
        const file = e.dataTransfer.files[0];
        if (file && file.type === "application/pdf") {
            fileInput.files = e.dataTransfer.files;
            dropText.textContent = file.name;
            this.classList.add("has-file");
        } else {
            alert("Hanya file PDF yang diperbolehkan!");
        }
    });
});
</script>

@endsection