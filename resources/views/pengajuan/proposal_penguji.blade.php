@extends('layouts.app')

@section('content')

<style>
:root {
    --navy:        #111C2D;
    --brown-dark:  #4D4632;
    --gold:        #735C00;
    --border:      #E5DFD0;
    --border-soft: #F0EBE0;
    --bg-input:    #F7F5F0;
}

/* ── HERO ── */
.hero-section {
    min-height: 200px;
    padding: 44px 48px;
    display: flex;
    align-items: center;
    background-image: url('{{ asset('images/bg.jpeg') }}');
    background-size: cover;
    background-position: center;
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 24px;
}
.hero-content h2 { font-size: 2rem; color: #735C00; margin-bottom: 6px; font-weight: 800; }
.hero-content p  { color: #735C00; font-size: 0.95rem; margin-bottom: 0; }

/* ── BADGE INFO READ ONLY ── */
.badge-readonly {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #EFF6FF;
    border: 1px solid #BFDBFE;
    color: #1D4ED8;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 16px;
}

/* ── FILTER BAR ── */
.filter-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}
.search-wrap {
    position: relative;
    flex: 1;
    min-width: 240px;
}
.search-wrap svg {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
    pointer-events: none;
}
.search-wrap input {
    width: 100%;
    padding: 10px 12px 10px 36px;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    font-size: 0.85rem;
    background: #fff;
    outline: none;
    transition: border-color 0.15s;
    box-sizing: border-box;
}
.search-wrap input:focus {
    border-color: #FACC15;
    box-shadow: 0 0 0 3px rgba(250, 204, 21, 0.15);
}
.filter-select {
    padding: 10px 32px 10px 12px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%23888' d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") no-repeat right 10px center;
    font-size: 0.85rem;
    color: #333;
    cursor: pointer;
    outline: none;
    -webkit-appearance: none;
    appearance: none;
    min-width: 180px;
    height: 42px;
}
.filter-select:focus { border-color: #FACC15; }
.btn-reset {
    padding: 10px 16px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    background: #fff;
    color: #666;
    font-size: 0.84rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    transition: 0.15s;
}
.btn-reset:hover { background: #f5f5f5; }

/* ── TABLE ── */
.table-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #f0f0f0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    overflow-x: auto;
}
.tbl {
    width: 100%;
    border-collapse: collapse;
    min-width: 900px;
}
.tbl thead tr { background: #fafafa; border-bottom: 1px solid #f0f0f0; }
.tbl th {
    padding: 12px 14px;
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    white-space: nowrap;
}
.tbl th.center { text-align: center; }
.tbl td {
    padding: 14px;
    font-size: 0.83rem;
    color: #333;
    border-bottom: 1px solid #f5f5f5;
    vertical-align: middle;
}
.tbl tbody tr:last-child td { border-bottom: none; }
.tbl tbody tr:hover td { background: #fffde7; transition: 0.1s; }

/* FILE PILL */
.file-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff1f2;
    border: 1px solid #fecdd3;
    border-radius: 8px;
    padding: 5px 10px;
    font-size: 0.78rem;
    font-weight: 600;
    color: #be123c;
    text-decoration: none;
    transition: 0.15s;
}
.file-pill:hover { background: #ffe4e6; color: #9f1239; }

/* DOSEN CELL */
.dosen-name { font-weight: 700; font-size: 0.82rem; color: #111; }
.dosen-nidn { font-size: 0.72rem; color: #94a3b8; margin-top: 1px; }

/* STATUS PILLS */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.74rem;
    font-weight: 700;
    white-space: nowrap;
}
.sp-menunggu-verifikasi { background: #fff3cd; color: #856404; border: 1px solid #ffd96a; }
.sp-menunggu-review     { background: #CCE5FF; color: #004085; border: 1px solid #b8daff; }
.sp-selesai             { background: #d4edda; color: #28a745; border: 1px solid #b7dfbb; }
.sp-ditolak             { background: #f8d7da; color: #dc3545; border: 1px solid #f1aeb5; }

/* EMPTY STATE */
.empty-state-wrap { padding: 60px 20px; text-align: center; }
.empty-state-inner { display: inline-flex; flex-direction: column; align-items: center; gap: 12px; }
.empty-state-icon { width: 64px; height: 64px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
.empty-state-title { font-size: 0.95rem; font-weight: 700; color: #475569; }
.empty-state-sub   { font-size: 0.82rem; color: #94a3b8; }
</style>

<div class="container-fluid px-4">

    {{-- HERO --}}
    <div class="hero-section">
        <div>
            <h2>Proposal Tugas Akhir</h2>
            <p>Lihat daftar proposal mahasiswa sebagai referensi sebelum seminar.</p>
        </div>
    </div>

    {{-- BADGE READ ONLY --}}
    <div class="badge-readonly">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
        </svg>
        Mode Lihat Saja — Anda tidak dapat melakukan verifikasi atau review
    </div>

    {{-- FILTER --}}
    <div class="filter-bar">
        <div class="search-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari NIM, nama, atau judul proposal...">
        </div>
        <select id="filterStatus" class="filter-select">
            <option value="">Semua Status</option>
            <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
            <option value="menunggu_review">Menunggu Review</option>
            <option value="selesai">Selesai</option>
            <option value="ditolak">Ditolak</option>
        </select>
        <button type="button" class="btn-reset" onclick="resetFilter()">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
            </svg>
            Reset Filter
        </button>
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
                </tr>
            </thead>
            <tbody id="tabelBody">
                @forelse($proposals as $i => $p)
                @php $status = strtolower(trim($p->status)); @endphp
                <tr
                    data-status="{{ $status }}"
                    data-search="{{ strtolower($p->nim_nid . ' ' . $p->nama . ' ' . $p->judul) }}">

                    <td class="center" style="color:#94a3b8; font-weight:600;">{{ $i + 1 }}</td>

                    <td style="white-space:nowrap;">
                        {{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d M Y') }}
                    </td>

                    <td style="font-family:monospace; font-size:0.82rem;">{{ $p->nim_nid }}</td>

                    <td style="font-weight:600;">{{ $p->nama }}</td>

                    <td style="max-width:200px; line-height:1.5;">{{ $p->judul }}</td>

                    <td>
                        @if($p->file_proposal)
                        <a href="{{ asset('storage/' . $p->file_proposal) }}" target="_blank" class="file-pill">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                            </svg>
                            Lihat PDF
                        </a>
                        @else
                        <span style="color:#cbd5e1;">-</span>
                        @endif
                    </td>

                    <td style="min-width:160px;">
                        @if($p->dosen1_nama)
                        <div class="dosen-name">{{ $p->dosen1_nama }}</div>
                        <div class="dosen-nidn">NIDN. {{ $p->dosen1_nidn }}</div>
                        @else
                        <span style="color:#cbd5e1;">-</span>
                        @endif
                    </td>

                    <td style="min-width:160px;">
                        @if($p->dosen2_nama)
                        <div class="dosen-name">{{ $p->dosen2_nama }}</div>
                        <div class="dosen-nidn">NIDN. {{ $p->dosen2_nidn }}</div>
                        @else
                        <span style="color:#cbd5e1;">-</span>
                        @endif
                    </td>

                    <td class="center">
                        @if($status === 'menunggu_verifikasi')
                        <span class="status-pill sp-menunggu-verifikasi">Menunggu Verifikasi</span>
                        @elseif($status === 'menunggu_review')
                        <span class="status-pill sp-menunggu-review">Menunggu Review</span>
                        @elseif($status === 'selesai')
                        <span class="status-pill sp-selesai">Selesai</span>
                        @elseif($status === 'ditolak')
                        <span class="status-pill sp-ditolak">Ditolak</span>
                        @else
                        <span style="color:#94a3b8; font-size:0.82rem;">{{ $p->status }}</span>
                        @endif
                    </td>

                </tr>
                @empty
                <tr id="rowKosongDefault">
                    <td colspan="9">
                        <div class="empty-state-wrap">
                            <div class="empty-state-inner">
                                <div class="empty-state-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#94a3b8" viewBox="0 0 16 16">
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                    </svg>
                                </div>
                                <div class="empty-state-title">Belum ada data</div>
                                <div class="empty-state-sub">Belum ada proposal yang tersedia.</div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Empty state filter tidak nemu --}}
    <div id="noSearchResult" style="display:none;">
        <div class="table-card">
            <div class="empty-state-wrap">
                <div class="empty-state-inner">
                    <div class="empty-state-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#94a3b8" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
                        </svg>
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
            const cocok = (!search || rowSearch.includes(search))
                       && (!status || rowStatus === status);
            row.style.display = cocok ? '' : 'none';
            if (cocok) visible++;
        });

        const rowDefault = document.getElementById('rowKosongDefault');
        if (rowDefault) rowDefault.style.display = 'none';

        if (visible === 0 && rows.length > 0) {
            tableCard.style.display    = 'none';
            noSearchResult.style.display = 'block';
        } else {
            tableCard.style.display    = '';
            noSearchResult.style.display = 'none';
        }
    }

    searchInput.addEventListener('input', applyFilter);
    filterStatus.addEventListener('change', applyFilter);

    function resetFilter() {
        searchInput.value  = '';
        filterStatus.value = '';
        applyFilter();
    }
</script>

@endsection