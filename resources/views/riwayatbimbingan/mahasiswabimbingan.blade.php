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
    .filter-group input {
        width: 100%;
        border: 1.5px solid #e5e7eb; border-radius: 8px;
        padding: 9px 12px 9px 34px; font-size: 0.85rem; outline: none;
        background: #fff; color: #374151; transition: border-color .2s;
    }
    .filter-group input::placeholder { color: #c4c4c4; }
    .filter-group input:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245,158,11,.1);
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
        padding: 11px 16px; font-size: 0.7rem; font-weight: 700;
        color: #9ca3af; text-transform: uppercase; letter-spacing: .5px;
        white-space: nowrap; text-align: left; border: none;
    }
    .table-card thead th:first-child { text-align: center; }
    .table-card thead th:last-child  { text-align: center; }
    .table-card tbody tr { border-bottom: 1px solid #f3f4f6; transition: background .15s; }
    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody tr:hover { background: #fffbeb; }
    .table-card tbody td { padding: 16px 16px; color: #374151; vertical-align: middle; border: none; }

    .td-no       { text-align: center; color: #9ca3af; font-size: 0.82rem; font-weight: 600; width: 50px; }
    .td-nim      { font-size: 0.85rem; color: #374151; font-weight: 500; }
    .td-nama     { font-weight: 700; color: #111827; }
    .td-angkatan { color: #374151; font-size: 0.85rem; }
    .td-aksi     { text-align: center; }

    .btn-lihat {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 6px 14px; border-radius: 8px;
        font-size: 0.78rem; font-weight: 600;
        background: #fff; color: #374151;
        border: 1.5px solid #d1d5db;
        text-decoration: none; transition: all .2s;
        white-space: nowrap;
    }
    .btn-lihat:hover { background: #fef3c7; border-color: #f59e0b; color: #92400e; }

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
            <rect x="260" y="90" width="160" height="130" rx="12" fill="#f59e0b"/>
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
    <a href="{{ route('dosen.riwayat-bimbingan.index') }}" class="tab-btn inactive">
        📋 Proposal Bimbingan
    </a>
    <a href="{{ route('dosen.riwayat-bimbingan.mahasiswa') }}" class="tab-btn active">
        👥 Mahasiswa Bimbingan
    </a>
</div>

{{-- ═══════════ FILTER ═══════════ --}}
<div class="filter-bar">
    <div class="filter-group" style="flex:2; min-width:220px; max-width:420px">
        <label>Pencarian Mahasiswa</label>
        <div class="filter-input-wrap">
            <span class="search-icon">🔍</span>
            <input type="text" id="searchInput"
                   placeholder="Cari nama mahasiswa atau NIM...">
        </div>
    </div>

    <button id="resetFilter" class="btn-reset">
        🔄 Reset Filter
    </button>
</div>

{{-- ═══════════ TABLE ═══════════ --}}
<div class="table-card">
    <table id="tabelMahasiswa">
        <thead>
            <tr>
                <th>NO.</th>
                <th>NIM</th>
                <th>NAMA MAHASISWA</th>
                <th>ANGKATAN</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody id="tabelBody">

            {{-- ✅ FIX 1: $mahasiswas → $mahasiswaList --}}
            @forelse($mahasiswaList as $mhs)
            <tr data-search="{{ strtolower($mhs->nim . ' ' . $mhs->nama) }}">
                <td class="td-no">{{ $loop->iteration }}.</td>
                <td class="td-nim">{{ $mhs->nim ?? '—' }}</td>
                <td class="td-nama">{{ $mhs->nama ?? '—' }}</td>
                <td class="td-angkatan">{{ $mhs->angkatan ?? '—' }}</td>
                <td class="td-aksi">
                    {{-- ✅ FIX 2: $mhs->id → $mhs->nim (sesuai parameter controller) --}}
                    <a href="{{ route('dosen.riwayat-bimbingan.detail', $mhs->nim) }}"
                       class="btn-lihat">
                        Lihat Riwayat ›
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="empty-state">
                        <div class="icon">📭</div>
                        <p>Belum ada mahasiswa bimbingan.</p>
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

    {{-- ✅ FIX 3: $mahasiswas → $mahasiswaList --}}
    @if(isset($mahasiswaList) && $mahasiswaList->hasPages())
    <div class="pagination-wrap">
        <span class="page-info">
            Halaman {{ $mahasiswaList->currentPage() }} dari {{ $mahasiswaList->lastPage() }}
            &nbsp;·&nbsp; Total: {{ $mahasiswaList->total() }} data
        </span>
        <div>{{ $mahasiswaList->withQueryString()->links() }}</div>
    </div>
    @endif
</div>

<script>
const searchInputEl = document.getElementById('searchInput');
searchInputEl.addEventListener('input', filterTabel);

document.getElementById('resetFilter').addEventListener('click', function () {
    searchInputEl.value = '';
    filterTabel();
});

function filterTabel() {
    const search = searchInputEl.value.toLowerCase();
    const rows   = document.querySelectorAll('#tabelBody tr[data-search]');
    let adaData  = false;

    rows.forEach(row => {
        const cocok = search === '' || row.getAttribute('data-search').includes(search);
        row.style.display = cocok ? '' : 'none';
        if (cocok) adaData = true;
    });

    document.getElementById('pesanKosong').classList.toggle('d-none', adaData);
}
</script>

@endsection