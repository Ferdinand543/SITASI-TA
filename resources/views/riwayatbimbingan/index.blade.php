@extends('layouts.app')

@section('content')

<style>
    .riwayat-hero {
        background: #ffffff;
        border-radius: 16px;
        padding: 36px 40px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        min-height: 160px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .riwayat-hero::before {
        content: '';
        position: absolute;
        top: 10px; left: 10px;
        width: 80px; height: 80px;
        background-image: radial-gradient(circle, #f0d080 1.5px, transparent 1.5px);
        background-size: 10px 10px;
        opacity: 0.5;
        pointer-events: none;
    }
    .riwayat-hero::after {
        content: '';
        position: absolute;
        right: 0; top: 0; bottom: 0;
        width: 42%;
        background:
            linear-gradient(135deg, transparent 55%, #f59e0b 55%),
            radial-gradient(ellipse 180px 260px at 85% 50%, #fef3c7 0%, transparent 70%);
        border-radius: 0 16px 16px 0;
        pointer-events: none;
    }
    .hero-deco {
        position: absolute;
        right: 0; top: 0; bottom: 0;
        width: 42%;
        pointer-events: none;
        border-radius: 0 16px 16px 0;
        overflow: hidden;
    }
    .hero-deco svg {
        width: 100%; height: 100%;
        position: absolute; top: 0; left: 0;
    }
    .riwayat-hero h1 {
        font-size: 1.75rem; font-weight: 800;
        color: #111827; margin-bottom: 4px;
        position: relative; z-index: 1;
    }
    .riwayat-hero p {
        color: #6b7280; font-size: 0.875rem;
        margin: 0; position: relative; z-index: 1;
    }

    .tab-bar {
        display: flex; gap: 8px; margin-bottom: 18px;
    }
    .tab-btn {
        padding: 8px 16px; border-radius: 8px;
        font-size: 0.82rem; font-weight: 600;
        text-decoration: none; transition: all .2s;
        display: inline-flex; align-items: center; gap: 6px;
        cursor: pointer; line-height: 1;
    }
    .tab-btn.active {
        background: #f59e0b; color: #fff; border: none;
        box-shadow: 0 2px 8px rgba(245,158,11,.3);
    }
    .tab-btn.inactive {
        background: #fff; color: #374151;
        border: 1.5px solid #d1d5db;
    }
    .tab-btn.inactive:hover { background: #f9fafb; }

    .filter-bar {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }
    .filter-group {
        display: flex; flex-direction: column; gap: 4px;
        flex: 1; min-width: 160px;
    }
    .filter-group label {
        font-size: 0.78rem; font-weight: 600; color: #374151;
    }
    .filter-input-wrap {
        position: relative; display: flex; align-items: center;
    }
    .filter-input-wrap .search-icon {
        position: absolute; left: 10px; color: #9ca3af;
        font-size: 0.85rem; pointer-events: none; z-index: 1;
    }
    .filter-group input,
    .filter-group select {
        width: 100%;
        border: 1.5px solid #e5e7eb; border-radius: 8px;
        padding: 9px 12px; font-size: 0.85rem; outline: none;
        background: #fff; color: #374151; transition: border-color .2s;
        -webkit-appearance: none; appearance: none;
    }
    .filter-group input.with-icon { padding-left: 34px; }
    .filter-group input::placeholder { color: #c4c4c4; }
    .filter-group input:focus,
    .filter-group select:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245,158,11,.1);
    }
    .select-wrap { position: relative; }
    .select-wrap::after {
        content: '▾'; position: absolute; right: 10px; top: 50%;
        transform: translateY(-50%); pointer-events: none;
        color: #6b7280; font-size: 0.75rem;
    }
    .btn-reset {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 16px; border-radius: 8px;
        font-size: 0.82rem; font-weight: 600;
        background: #fff; color: #374151;
        border: 1.5px solid #d1d5db;
        cursor: pointer; text-decoration: none;
        white-space: nowrap; transition: all .2s;
        align-self: flex-end;
    }
    .btn-reset:hover { background: #f3f4f6; }

    .table-card {
        background: #fff; border-radius: 14px;
        box-shadow: 0 1px 6px rgba(0,0,0,.08); overflow: hidden;
    }
    .table-card table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    .table-card thead tr { background: #f5f5f5; border-bottom: 1px solid #eeeeee; }
    .table-card thead th {
        padding: 11px 14px; font-size: 0.7rem; font-weight: 700;
        color: #9ca3af; text-transform: uppercase; letter-spacing: .5px;
        white-space: nowrap; text-align: left; border: none;
    }
    .table-card thead th:first-child { text-align: center; }
    .table-card tbody tr { border-bottom: 1px solid #f3f4f6; transition: background .15s; }
    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody tr:hover { background: #fffbeb; }
    .table-card tbody td { padding: 14px 14px; color: #374151; vertical-align: middle; border: none; }

    .td-no      { text-align: center; font-weight: 600; color: #9ca3af; font-size: 0.82rem; width: 44px; }
    .td-nim     { font-size: 0.85rem; color: #374151; font-weight: 500; }
    .td-nama    { font-weight: 700; color: #111827; }
    .td-tanggal { white-space: nowrap; color: #6b7280; font-size: 0.82rem; }
    .td-judul   {
        max-width: 220px; color: #374151; font-size: 0.85rem;
        display: -webkit-box; -webkit-line-clamp: 2;
        -webkit-box-orient: vertical; overflow: hidden;
    }

    .file-chip {
        display: inline-flex; flex-direction: column; gap: 1px;
        background: #fff1f2; border: 1px solid #fecdd3; border-radius: 6px;
        padding: 5px 9px; text-decoration: none; transition: background .2s;
        max-width: 150px;
    }
    .file-chip:hover { background: #ffe4e6; }
    .file-chip-name {
        display: flex; align-items: center; gap: 4px;
        font-size: 0.78rem; font-weight: 600; color: #be123c;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .file-chip-sub {
        font-size: 0.68rem; color: #9ca3af; padding-left: 2px;
        display: flex; align-items: center; gap: 3px;
    }
    .file-null { color: #d1d5db; font-style: italic; font-size: 0.8rem; }

    .badge-status {
        display: inline-block;
        padding: 4px 10px; border-radius: 6px;
        font-size: 0.68rem; font-weight: 700;
        letter-spacing: .2px; white-space: nowrap; line-height: 1.4;
    }
    .badge-baru  { background: #fef9c3; color: #92400e; }
    .badge-sudah { background: #dbeafe; color: #1e40af; }

    .empty-state { text-align: center; padding: 60px 20px; color: #9ca3af; }
    .empty-state .icon { font-size: 2.8rem; margin-bottom: 12px; }
    .empty-state p { margin: 0; font-size: 0.9rem; }

    .pagination-wrap {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 16px; border-top: 1px solid #f3f4f6;
        flex-wrap: wrap; gap: 10px;
    }
    .page-info { font-size: 0.8rem; color: #9ca3af; }
    .pagination { margin: 0; }
    .pagination .page-link {
        border-radius: 6px !important; margin: 0 2px;
        border: 1.5px solid #e5e7eb; color: #374151;
        font-size: 0.82rem; padding: 5px 11px;
    }
    .pagination .page-item.active .page-link { background: #f59e0b; border-color: #f59e0b; color: #fff; }
    .pagination .page-link:hover { background: #fef3c7; border-color: #f59e0b; color: #92400e; }
</style>

{{-- ═══════════ HERO ═══════════ --}}
<div class="riwayat-hero">
    <div class="hero-deco" aria-hidden="true">
        <svg viewBox="0 0 400 200" preserveAspectRatio="xMidYMid slice"
             xmlns="http://www.w3.org/2000/svg">
            <rect x="260" y="90" width="160" height="130" rx="12" fill="#f59e0b" opacity="1"/>
            <path d="M 80 -20 Q 200 100 80 220" stroke="#fde68a" stroke-width="38" fill="none" opacity="0.6"/>
            <path d="M 160 -30 Q 310 100 160 230" stroke="#fef3c7" stroke-width="50" fill="none" opacity="0.7"/>
            <path d="M 240 -10 Q 360 100 240 210" stroke="#fde68a" stroke-width="22" fill="none" opacity="0.5"/>
        </svg>
    </div>
    <h1>Riwayat Bimbingan Mahasiswa</h1>
    <p>Kelola dan lihat riwayat bimbingan Mahasiswa</p>
</div>

{{-- ═══════════ TABS ═══════════ --}}
<div class="tab-bar">
    <a href="{{ route('dosen.riwayat-bimbingan.index') }}" class="tab-btn active">
        📋 Proposal Bimbingan
    </a>
    <a href="{{ route('dosen.riwayat-bimbingan.mahasiswa') }}" class="tab-btn inactive">
        👥 Mahasiswa Bimbingan
    </a>
</div>

{{-- ═══════════ FILTER ═══════════ --}}
<div class="filter-bar">
    <div class="filter-group" style="flex:2; min-width:220px">
        <label>Pencarian</label>
        <div class="filter-input-wrap">
            <span class="search-icon">🔍</span>
            <input type="text" id="searchInput" class="with-icon"
                   placeholder="Cari NIM, nama mahasiswa atau judul proposal...">
        </div>
    </div>

    <div class="filter-group" style="min-width:160px">
        <label>Status Bimbingan</label>
        <div class="select-wrap">
            <select id="filterStatus">
                <option value="">Semua Status</option>
                <option value="baru dikirim" {{ strtolower($status) === 'baru dikirim' ? 'selected' : '' }}>Baru Dikirim</option>
                <option value="sudah dilihat" {{ strtolower($status) === 'sudah dilihat' ? 'selected' : '' }}>Sudah Dilihat</option>
            </select>
        </div>
    </div>

    <button id="resetFilter" class="btn-reset">
        🔄 Reset Filter
    </button>
</div>

{{-- ═══════════ TABLE ═══════════ --}}
<div class="table-card">
    <table id="tabelPengajuan">
        <thead>
            <tr>
                <th>NO.</th>
                <th>NIM</th>
                <th>NAMA MAHASISWA</th>
                <th>TANGGAL<br>UPLOAD</th>
                <th>JUDUL PROPOSAL</th>
                <th>FILE PROPOSAL</th>
                <th>STATUS BIMBINGAN</th>
            </tr>
        </thead>
        <tbody id="tabelBody">
            @forelse($proposals as $i => $row)
            <tr
                data-status="{{ strtolower(trim($row->status ?? '')) }}"
                data-search="{{ strtolower($row->nim . ' ' . $row->nama . ' ' . $row->judul) }}"
            >
                <td class="td-no">{{ $proposals->firstItem() + $i }}</td>
                <td class="td-nim">{{ $row->nim ?? '—' }}</td>
                <td class="td-nama">{{ $row->nama ?? '—' }}</td>
                <td class="td-tanggal">
                    {{ $row->tanggal_pengajuan
                        ? \Carbon\Carbon::parse($row->tanggal_pengajuan)->translatedFormat('d M Y')
                        : '—' }}
                </td>
                <td>
                    <div class="td-judul" title="{{ $row->judul }}">
                        {{ $row->judul ?? '—' }}
                    </div>
                </td>
                <td>
                    @if (!empty($row->file_proposal))
                        <a href="{{ route('dosen.proposal.lihat', $row->id) }}"
                           class="file-chip" target="_blank">
                            <span class="file-chip-name">
                                📄 {{ basename($row->file_proposal) }}
                            </span>
                            <span class="file-chip-sub">
                                👁 Preview
                            </span>
                        </a>
                    @else
                        <span class="file-null">— Belum ada —</span>
                    @endif
                </td>
                <td>
                    @php
                        $s = strtolower($row->status ?? '');
                        if ($s === 'sudah dilihat') { $cls = 'badge-sudah'; $lbl = 'SUDAH' . "\nDILIHAT"; }
                        else                        { $cls = 'badge-baru';  $lbl = 'BARU'  . "\nDIKIRIM"; }
                    @endphp
                    <span class="badge-status {{ $cls }}" style="white-space:pre-line; text-align:center;">{{ $lbl }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <div class="icon">📭</div>
                        <p>Belum ada proposal bimbingan ditemukan.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div id="pesanKosong" class="empty-state d-none">
        <div class="icon">📭</div>
        <p>Data tidak ditemukan.</p>
    </div>

    @if(isset($proposals) && $proposals->hasPages())
    <div class="pagination-wrap">
        <span class="page-info">
            Halaman {{ $proposals->currentPage() }} dari {{ $proposals->lastPage() }}
            &nbsp;·&nbsp; Total: {{ $proposals->total() }} data
        </span>
        <div>{{ $proposals->withQueryString()->links() }}</div>
    </div>
    @endif
</div>

<script>
const filterStatusEl = document.getElementById('filterStatus');
const searchInputEl  = document.getElementById('searchInput');

// Set nilai awal dari URL (server-side filter)
searchInputEl.value  = '{{ $search }}';

filterStatusEl.addEventListener('change', kirimFilter);
searchInputEl.addEventListener('input',   filterLokal);

document.getElementById('resetFilter').addEventListener('click', function () {
    window.location.href = '{{ route("dosen.riwayat-bimbingan.index") }}';
});

// Filter status → kirim ke server (supaya hasil akurat dari DB)
function kirimFilter() {
    const status = filterStatusEl.value;
    const search = searchInputEl.value;
    const url    = new URL(window.location.href);
    url.searchParams.set('status', status);
    url.searchParams.set('search', search);
    window.location.href = url.toString();
}

// Filter search → lokal saja (cepat, tidak reload)
function filterLokal() {
    const status = filterStatusEl.value.toLowerCase();
    const search = searchInputEl.value.toLowerCase();
    const rows   = document.querySelectorAll('#tabelBody tr[data-status]');
    let adaData  = false;

    rows.forEach(row => {
        const cocokStatus = status === '' || row.getAttribute('data-status') === status;
        const cocokSearch = search === '' || row.getAttribute('data-search').includes(search);
        if (cocokStatus && cocokSearch) { row.style.display = ''; adaData = true; }
        else                            { row.style.display = 'none'; }
    });

    document.getElementById('pesanKosong').classList.toggle('d-none', adaData);
}
</script>

@endsection