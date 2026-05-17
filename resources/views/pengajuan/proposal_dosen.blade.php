@extends('layouts.app')

@section('content')

@php
    $nimSesi    = session('user')->nim_nid;
    $rolesDb    = \Illuminate\Support\Facades\DB::table('dosen_roles')
                    ->where('nim_nid', $nimSesi)
                    ->pluck('role_dosen')
                    ->toArray();
    $isReviewer = in_array('reviewer', $rolesDb);
@endphp

<style>
    .page-wrap { padding: 0 0 48px; }

    .hero-section {
        background-image: url('/images/bg_ajukan.jpeg');
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
        background: #FACC15; color: #4a3000; border: none; border-radius: 10px;
        padding: 10px 20px; font-size: 0.84rem; font-weight: 700;
        text-decoration: none; display: inline-flex; align-items: center; gap: 7px; transition: 0.2s;
    }
    .btn-hero-primary:hover { background: #e0b800; color: #4a3000; }

    .btn-hero-outline {
        background: transparent; color: #4a3000; border: 1.5px solid #d4a01e;
        border-radius: 10px; padding: 10px 20px; font-size: 0.84rem; font-weight: 700;
        text-decoration: none; display: inline-flex; align-items: center; gap: 7px; transition: 0.2s;
    }
    .btn-hero-outline:hover { background: rgba(212,160,30,0.1); color: #4a3000; }

    .stat-row {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 1px; background: #e5e7eb; border-radius: 16px;
        overflow: hidden; margin-bottom: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .stat-card { background: #fff; padding: 20px 24px; display: flex; align-items: center; gap: 14px; }
    .stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .stat-icon.blue   { background: #eff6ff; }
    .stat-icon.yellow { background: #fefce8; }
    .stat-icon.green  { background: #f0fdf4; }
    .stat-num   { font-size: 1.8rem; font-weight: 800; color: #111; line-height: 1; margin-bottom: 4px; }
    .stat-label { font-size: 0.74rem; color: #64748b; font-weight: 500; line-height: 1.3; text-transform: uppercase; letter-spacing: 0.04em; }

    /* ── FILTER BAR ── */
    .filter-bar  { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; flex-wrap: wrap; }
    .search-wrap { position: relative; flex: 1; min-width: 240px; }
    .search-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #aaa; pointer-events: none; }
    .search-wrap input {
        width: 100%; padding: 10px 12px 10px 36px;
        border: 1px solid #e5e7eb; border-radius: 10px;
        font-size: 0.85rem; background: #fff; outline: none;
        transition: border-color 0.15s; box-sizing: border-box;
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
    .table-card { background: #fff; border-radius: 16px; border: 1px solid #f0f0f0; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow-x: auto; }
    .tbl { width: 100%; border-collapse: collapse; min-width: 1200px; }
    .tbl thead tr { background: #fafafa; border-bottom: 1px solid #f0f0f0; }
    .tbl th { padding: 12px 14px; font-size: 0.75rem; font-weight: 700; color: #64748b; text-align: left; text-transform: uppercase; letter-spacing: 0.04em; white-space: nowrap; }
    .tbl th.center { text-align: center; }
    .tbl td { padding: 14px 14px; font-size: 0.83rem; color: #333; border-bottom: 1px solid #f5f5f5; vertical-align: middle; }
    .tbl tbody tr:last-child td { border-bottom: none; }
    .tbl tbody tr:hover td { background: #fffde7; transition: 0.1s; }

    .file-pill { display: inline-flex; align-items: center; gap: 6px; background: #fff1f2; border: 1px solid #fecdd3; border-radius: 8px; padding: 5px 10px; font-size: 0.78rem; font-weight: 600; color: #be123c; text-decoration: none; transition: 0.15s; }
    .file-pill:hover { background: #ffe4e6; color: #9f1239; }

    .dosen-name { font-weight: 700; font-size: 0.82rem; color: #111; }
    .dosen-nidn { font-size: 0.72rem; color: #94a3b8; margin-top: 1px; }
    .role-badge { display: inline-block; margin-top: 4px; font-size: 0.66rem; padding: 2px 8px; border-radius: 5px; font-weight: 700; }
    .rb-pembimbing { background: #e0f2fe; color: #0369a1; }
    .rb-usulan     { background: #fef9c3; color: #854d0e; }

    .status-pill { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 20px; font-size: 0.74rem; font-weight: 700; white-space: nowrap; }
    .sp-menunggu-verifikasi { background: #fff3cd; color: #856404; border: 1px solid #ffd96a; }
    .sp-selesai             { background: #d4edda; color: #28a745; border: 1px solid #b7dfbb; }
    .sp-ditolak             { background: #f8d7da; color: #dc3545; border: 1px solid #f1aeb5; }

    .btn-verifikasi { background: #FACC15; color: #4a3000; border: none; border-radius: 8px; padding: 7px 16px; font-size: 0.8rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: 0.15s; white-space: nowrap; }
    .btn-verifikasi:hover { background: #e0b800; color: #4a3000; }
    .btn-detail { background: #fff; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; padding: 7px 16px; font-size: 0.8rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: 0.15s; white-space: nowrap; }
    .btn-detail:hover { background: #f8fafc; border-color: #FACC15; color: #333; }

    /* ── EMPTY STATES ── */
    .empty-state-wrap {
        padding: 60px 20px; text-align: center;
    }
    .empty-state-inner {
        display: inline-flex; flex-direction: column; align-items: center; gap: 12px;
    }
    .empty-state-icon {
        width: 64px; height: 64px; background: #f1f5f9; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }
    .empty-state-icon i { font-size: 1.8rem; color: #94a3b8; }
    .empty-state-title { font-size: 0.95rem; font-weight: 700; color: #475569; }
    .empty-state-sub   { font-size: 0.82rem; color: #94a3b8; }
</style>

<div class="container-fluid px-4 page-wrap">

    {{-- HERO --}}
    <div class="hero-section mb-4">
        <div>
            <h2>Pengajuan Proposal TA-1</h2>
            <p>Verifikasi dan tetapkan dosen pembimbing tugas akhir mahasiswa untuk menjamin kualitas akademik.</p>
            <div class="hero-btn-group">
                <a href="{{ url('/proposal') }}" class="btn-hero-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4z" />
                    </svg>
                    Penetapan Dosen Pembimbing
                </a>
                @if($isReviewer)
                <a href="{{ route('reviewer.proposal') }}" class="btn-hero-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z" />
                    </svg>
                    Review Proposal
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    @php
        $total           = $proposals->count();
        $menungguVerif   = $proposals->filter(fn($p) => strtolower($p->status) === 'menunggu_verifikasi')->count();
        $sudahVerifikasi = $proposals->filter(fn($p) => in_array(strtolower($p->status), ['menunggu_review', 'selesai', 'ditolak']))->count();
    @endphp

    <div class="stat-row mb-4">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#2563eb" viewBox="0 0 16 16">
                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                </svg>
            </div>
            <div>
                <div class="stat-num">{{ $total }}</div>
                <div class="stat-label">Total Proposal</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#ca8a04" viewBox="0 0 16 16">
                    <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                </svg>
            </div>
            <div>
                <div class="stat-num">{{ $menungguVerif }}</div>
                <div class="stat-label">Menunggu Verifikasi Dosen Koordinator</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#16a34a" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                </svg>
            </div>
            <div>
                <div class="stat-num">{{ $sudahVerifikasi }}</div>
                <div class="stat-label">Sudah Diverifikasi Dosen Koordinator</div>
            </div>
        </div>
    </div>

    {{-- FILTER (client-side, bukan form submit) --}}
    <div class="filter-bar mb-4">
        <div class="search-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z" />
            </svg>
            <input type="text" id="searchInput" placeholder="Masukkan NIM, nama, atau kata kunci judul...">
        </div>
        <div class="filter-right">
            <select id="filterStatus" class="filter-select">
                <option value="">Semua Status</option>
                <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                <option value="selesai">Selesai</option>
                <option value="ditolak">Ditolak</option>
            </select>
            <button type="button" class="btn-reset" onclick="resetFilter()">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                    <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                </svg>
                Reset Filter
            </button>
        </div>
    </div>

    {{-- TABLE --}}
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
                <tr
                    data-status="{{ $status }}"
                    data-search="{{ strtolower($p->nim_nid . ' ' . $p->nama . ' ' . $p->judul) }}"
                >
                    <td class="center" style="color:#94a3b8; font-weight:600;">{{ $i + 1 }}</td>
                    <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d M Y') }}</td>
                    <td style="font-family:monospace; font-size:0.82rem;">{{ $p->nim_nid }}</td>
                    <td style="font-weight:600;">{{ $p->nama }}</td>
                    <td style="max-width:200px; line-height:1.5;">{{ $p->judul }}</td>

                    <td>
                        @if($p->file_proposal)
                        <a href="{{ asset('storage/' . $p->file_proposal) }}" target="_blank" class="file-pill">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                            </svg>
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
                        @if($status === 'menunggu_verifikasi')
                            <span class="status-pill sp-menunggu-verifikasi">Menunggu Verifikasi</span>
                        @elseif($status === 'menunggu_review' || $status === 'selesai')
                            <span class="status-pill sp-selesai">Selesai</span>
                        @elseif($status === 'ditolak')
                            <span class="status-pill sp-ditolak">Ditolak</span>
                        @else
                            <span style="color:#94a3b8; font-size:0.82rem;">{{ $p->status }}</span>
                        @endif
                    </td>

                    <td class="center">
                        @if($status === 'menunggu_verifikasi')
                            <a href="{{ url('/proposal/' . $p->id . '/verifikasi') }}" class="btn-verifikasi">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                </svg>
                                Verifikasi
                            </a>
                        @else
                            <a href="{{ url('/proposal/' . $p->id) }}" class="btn-detail">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                </svg>
                                Detail
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                {{-- DB kosong total → inbox --}}
                <tr id="rowKosongDefault">
                    <td colspan="10">
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

    {{-- Filter/search tidak nemu hasil → magnifier --}}
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

<script>
    const searchInput    = document.getElementById('searchInput');
    const filterStatus   = document.getElementById('filterStatus');
    const tabelBody      = document.getElementById('tabelBody');
    const tableCard      = document.getElementById('tableCard');
    const noSearchResult = document.getElementById('noSearchResult');

    function applyFilter() {
        const search = searchInput.value.toLowerCase().trim();
        const status = filterStatus.value.toLowerCase();
        const rows   = tabelBody.querySelectorAll('tr[data-status]');
        let visible  = 0;

        rows.forEach(function(row) {
            const rowSearch = (row.getAttribute('data-search') || '').toLowerCase();
            const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();

            // Status "selesai" juga cocok dengan "menunggu_review"
            let statusCocok = false;
            if (!status) {
                statusCocok = true;
            } else if (status === 'selesai') {
                statusCocok = (rowStatus === 'selesai' || rowStatus === 'menunggu_review');
            } else {
                statusCocok = (rowStatus === status);
            }

            const searchCocok = !search || rowSearch.includes(search);
            const tampil      = statusCocok && searchCocok;

            row.style.display = tampil ? '' : 'none';
            if (tampil) visible++;
        });

        // Sembunyikan row default kosong kalau ada data asli
        const rowDefault = document.getElementById('rowKosongDefault');
        if (rowDefault) rowDefault.style.display = 'none';

        if (visible === 0 && rows.length > 0) {
            // Ada data di DB tapi filter ga nemu → magnifier
            tableCard.style.display      = 'none';
            noSearchResult.style.display = 'block';
        } else {
            // Nemu data atau emang DB kosong → tampilkan tabel normal
            tableCard.style.display      = '';
            noSearchResult.style.display = 'none';
        }
    }

    searchInput.addEventListener('input',    applyFilter);
    filterStatus.addEventListener('change',  applyFilter);

    function resetFilter() {
        searchInput.value  = '';
        filterStatus.value = '';
        applyFilter();
    }
</script>

@endsection