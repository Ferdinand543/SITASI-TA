@extends('layouts.app')

@section('title', 'Pengajuan Judul')

@section('content')

<style>
:root {
    --gold: #C9A227;
    --gold-light: #FFF9E8;
    --border: #ECECEC;
    --bg: #F5F6FA;
    --text: #1E293B;
    --muted: #6B7280;
}

body { background: var(--bg); }

/* ── HERO ── */
.hero {
    position: relative;
    overflow: hidden;
    background: url('{{ asset("images/psi.jpeg") }}') right center/auto 100% no-repeat;
    background-color: #fffbe6;
    border-radius: 20px;
    padding: 44px 44px 48px;
    margin-bottom: 24px;
    min-height: 190px;
    display: flex;
    align-items: center;
}

.hero::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 320px; height: 320px;
    background: rgba(255,255,255,0.18);
    border-radius: 50%;
    pointer-events: none;
}

.hero::after {
    content: '';
    position: absolute;
    bottom: -80px; right: 120px;
    width: 240px; height: 240px;
    background: rgba(255,255,255,0.10);
    border-radius: 50%;
    pointer-events: none;
}

.hero-sparkle {
    position: absolute;
    pointer-events: none;
}
.hero-sparkle svg { fill: #FACC15; }

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-content h1 {
    font-size: 34px;
    font-weight: 800;
    color: #7C5C00;
    line-height: 1.2;
    margin-bottom: 8px;
}

.hero-content p {
    color: #8B6B00;
    font-size: 14px;
    margin: 0;
}

/* ── STAT ROW ── */
.stat-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    background: #fff;
    border-radius: 18px;
    border: 1px solid var(--border);
    overflow: hidden;
    margin-bottom: 24px;
}

.stat-item {
    padding: 20px 24px;
    border-right: 1px solid var(--border);
}

.stat-item:last-child { border-right: none; }

.stat-item .stat-label {
    font-size: 12px;
    color: var(--muted);
    font-weight: 500;
    margin-bottom: 6px;
}

.stat-item .stat-number {
    font-size: 32px;
    font-weight: 800;
    line-height: 1;
}

.stat-item.s-total  .stat-number { color: #1E293B; }
.stat-item.s-tunggu .stat-number { color: #D97706; }
.stat-item.s-setuju .stat-number { color: #16A34A; }
.stat-item.s-tolak  .stat-number { color: #DC2626; }
.stat-item.s-tunggu { border-left: 3px solid #F59E0B; }
.stat-item.s-setuju { border-left: 3px solid #22C55E; }
.stat-item.s-tolak  { border-left: 3px solid #EF4444; }

/* ── MAIN CARD ── */
.main-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid var(--border);
    overflow: hidden;
}

/* ── FILTER BAR ── */
.filter-bar {
    padding: 18px 20px;
    display: grid;
    grid-template-columns: 1fr auto auto auto;
    gap: 12px;
    align-items: end;
    border-bottom: 1px solid #F3F4F6;
}

.filter-group { display: flex; flex-direction: column; }

.filter-group label,
.filter-status-wrap label,
.filter-tanggal-wrap label {
    font-size: 11px;
    font-weight: 700;
    color: var(--muted);
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.search-wrap { position: relative; }

.search-wrap i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 13px;
    pointer-events: none;
}

.search-wrap input {
    width: 100%;
    height: 42px;
    border-radius: 12px;
    border: 1px solid var(--border);
    padding: 0 14px 0 38px;
    font-size: 13px;
    font-family: 'Hanken Grotesk', sans-serif;
    color: var(--text);
    background: #FAFAFA;
    outline: none;
    transition: border 0.2s;
}

.search-wrap input:focus {
    border-color: #FACC15;
    background: #fff;
}

.search-wrap input::placeholder { color: #94a3b8; }

.filter-status-wrap,
.filter-tanggal-wrap {
    display: flex;
    flex-direction: column;
}

.filter-status-wrap select,
.filter-tanggal-wrap input[type="date"] {
    height: 42px;
    border-radius: 12px;
    border: 1px solid var(--border);
    padding: 0 14px;
    font-size: 13px;
    font-family: 'Hanken Grotesk', sans-serif;
    color: var(--text);
    background: #FAFAFA;
    outline: none;
    cursor: pointer;
    transition: border 0.2s;
}

.filter-status-wrap select {
    padding-right: 36px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    appearance: none;
    min-width: 160px;
}

.filter-status-wrap select:focus,
.filter-tanggal-wrap input[type="date"]:focus {
    border-color: #FACC15;
    background-color: #fff;
}

.btn-reset {
    height: 42px;
    padding: 0 16px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: #FAFAFA;
    color: var(--muted);
    font-size: 13px;
    font-family: 'Hanken Grotesk', sans-serif;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    transition: 0.2s;
    text-decoration: none;
    align-self: flex-end;
}

.btn-reset:hover {
    background: #f1f5f9;
    color: var(--text);
}

/* ── ACTIVE FILTER CHIPS ── */
.active-filters {
    padding: 10px 20px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    border-bottom: 1px solid #F3F4F6;
    background: #FAFBFC;
}

.filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 999px;
    background: #FEF9C3;
    color: #854D0E;
    font-size: 12px;
    font-weight: 600;
}

.filter-chip a {
    color: #B45309;
    text-decoration: none;
    font-weight: 700;
    font-size: 13px;
    line-height: 1;
}

.filter-chip a:hover { color: #DC2626; }

/* ── TABLE ── */
.tbl-wrap { overflow-x: auto; }

table { width: 100%; border-collapse: collapse; }

thead tr { background: #FAFBFC; }

thead th {
    padding: 13px 16px;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #F1F5F9;
    white-space: nowrap;
}

tbody td {
    padding: 15px 16px;
    font-size: 13px;
    color: #334155;
    border-top: 1px solid #F8FAFC;
    vertical-align: middle;
}

tbody tr:hover { background: #FFFDF5; }

.judul-main {
    font-weight: 600;
    color: #1e293b;
    font-size: 13px;
    line-height: 1.4;
}

.judul-lainnya {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 3px;
    cursor: pointer;
    display: inline-block;
    transition: color 0.15s;
}

.judul-lainnya:hover { color: var(--gold); }

/* ── BADGE ── */
.badge {
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    display: inline-block;
    white-space: nowrap;
}

.badge-menunggu  { background: #FEF3C7; color: #B45309; }
.badge-disetujui { background: #DCFCE7; color: #15803D; }
.badge-ditolak   { background: #FEE2E2; color: #DC2626; }

/* ── BTN AKSI ── */
.btn-aksi {
    padding: 7px 16px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    display: inline-block;
    transition: 0.2s;
    cursor: pointer;
}

.btn-verifikasi {
    background: var(--gold-light);
    color: var(--gold);
    border: 1.5px solid #F0D060;
}

.btn-verifikasi:hover { background: #FACC15; color: #7C5C00; }

.btn-detail-outline {
    background: #fff;
    color: #475569;
    border: 1.5px solid #E2E8F0;
}

.btn-detail-outline:hover { background: #f1f5f9; color: var(--text); }

/* ── EMPTY STATE ── */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #94a3b8;
}

.empty-state i { font-size: 40px; margin-bottom: 14px; display: block; }
.empty-state p { font-size: 14px; margin: 0; }

/* ── PAGINATION ── */
.pagination-wrap {
    padding: 16px 20px;
    border-top: 1px solid #F3F4F6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.pagination-info { font-size: 13px; color: var(--muted); }
.pagination-wrap .pagination { margin: 0; }

/* ── MODAL ── */
.modal-judul-alt {
    font-size: 13px;
    color: #64748b;
    padding: 10px 14px;
    background: #f8fafc;
    border-radius: 10px;
    margin-bottom: 8px;
    line-height: 1.5;
}

/* ── RESPONSIVE ── */
@media(max-width:992px) { .filter-bar { grid-template-columns: 1fr 1fr; } }
@media(max-width:768px) {
    .filter-bar { grid-template-columns: 1fr; }
    .stat-row   { grid-template-columns: repeat(2,1fr); }
    .hero-content h1 { font-size: 24px; }
    .hero { padding: 32px 24px 36px; }
}
</style>

{{-- ══ HERO ══ --}}
<div class="hero">
    {{-- sparkles --}}
    <span class="hero-sparkle" style="top:22px;right:220px;">
        <svg width="18" height="18" viewBox="0 0 20 20"><path d="M10 0l1.5 8.5L20 10l-8.5 1.5L10 20l-1.5-8.5L0 10l8.5-1.5z"/></svg>
    </span>
    <span class="hero-sparkle" style="top:60px;right:100px;">
        <svg width="12" height="12" viewBox="0 0 20 20"><path d="M10 0l1.5 8.5L20 10l-8.5 1.5L10 20l-1.5-8.5L0 10l8.5-1.5z"/></svg>
    </span>
    <span class="hero-sparkle" style="bottom:28px;right:260px;">
        <svg width="10" height="10" viewBox="0 0 20 20"><path d="M10 0l1.5 8.5L20 10l-8.5 1.5L10 20l-1.5-8.5L0 10l8.5-1.5z"/></svg>
    </span>

    <div class="hero-content">
        <h1>Pengajuan Judul Tugas Akhir<br>Mahasiswa</h1>
        <p>Monitoring dan verifikasi pengajuan judul tugas akhir mahasiswa.</p>
    </div>
</div>

{{-- ══ STATISTIK ══ --}}
<div class="stat-row">
    <div class="stat-item s-total">
        <div class="stat-label">Total Pengajuan</div>
        <div class="stat-number">{{ $totalPengajuan }}</div>
    </div>
    <div class="stat-item s-tunggu">
        <div class="stat-label">Menunggu Verifikasi</div>
        <div class="stat-number">{{ $menunggu }}</div>
    </div>
    <div class="stat-item s-setuju">
        <div class="stat-label">Disetujui</div>
        <div class="stat-number">{{ $disetujui }}</div>
    </div>
    <div class="stat-item s-tolak">
        <div class="stat-label">Ditolak</div>
        <div class="stat-number">{{ $ditolak }}</div>
    </div>
</div>

{{-- ══ TABEL UTAMA ══ --}}
<div class="main-card">

    {{-- Filter Bar --}}
    <form method="GET" action="{{ url()->current() }}" id="filterForm">
        <div class="filter-bar">

            {{-- Search --}}
            <div class="filter-group">
                <label>Cari Mahasiswa atau Judul</label>
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input
                        type="text"
                        name="search"
                        id="inputSearch"
                        value="{{ request('search') }}"
                        placeholder="Masukkan NIM, nama, atau kata kunci judul..."
                        autocomplete="off"
                    >
                </div>
            </div>

            {{-- Status --}}
            <div class="filter-status-wrap">
                <label>Status</label>
                <select name="status" id="inputStatus" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua Status</option>
                    <option value="menunggu"  {{ request('status')=='menunggu'  ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="disetujui" {{ request('status')=='disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak"   {{ request('status')=='ditolak'   ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            {{-- Tanggal --}}
            <div class="filter-tanggal-wrap">
                <label>Tanggal Pengajuan</label>
                <input
                    type="date"
                    name="tanggal"
                    id="inputTanggal"
                    value="{{ request('tanggal') }}"
                    onchange="document.getElementById('filterForm').submit()"
                >
            </div>

            {{-- Reset --}}
            <a href="{{ url()->current() }}" class="btn-reset">
                <i class="fa-solid fa-rotate-right" style="font-size:11px;"></i>
                Reset
            </a>

        </div>
    </form>

    {{-- Active Filter Chips --}}
    @if(request('search') || request('status') || request('tanggal'))
    <div class="active-filters">
        <span style="font-size:12px;color:#94a3b8;font-weight:600;align-self:center;">Filter aktif:</span>

        @if(request('search'))
        <span class="filter-chip">
            <i class="fa-solid fa-magnifying-glass" style="font-size:10px;"></i>
            "{{ request('search') }}"
            <a href="{{ url()->current().'?'.http_build_query(array_merge(request()->except('search'),['page'=>1])) }}">×</a>
        </span>
        @endif

        @if(request('status'))
        <span class="filter-chip">
            <i class="fa-solid fa-tag" style="font-size:10px;"></i>
            Status: {{ request('status')=='menunggu' ? 'Menunggu Verifikasi' : ucfirst(request('status')) }}
            <a href="{{ url()->current().'?'.http_build_query(array_merge(request()->except('status'),['page'=>1])) }}">×</a>
        </span>
        @endif

        @if(request('tanggal'))
        <span class="filter-chip">
            <i class="fa-solid fa-calendar" style="font-size:10px;"></i>
            {{ \Carbon\Carbon::parse(request('tanggal'))->format('d M Y') }}
            <a href="{{ url()->current().'?'.http_build_query(array_merge(request()->except('tanggal'),['page'=>1])) }}">×</a>
        </span>
        @endif
    </div>
    @endif

    {{-- Tabel --}}
    <div class="tbl-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:46px;">No.</th>
                    <th>Tanggal Pengajuan</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Judul yang Diajukan</th>
                    <th>Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuanJudul as $i => $p)
                <tr>
                    <td style="color:#94a3b8;font-weight:600;">
                        {{ ($pengajuanJudul->currentPage()-1)*$pengajuanJudul->perPage()+$i+1 }}
                    </td>
                    <td style="white-space:nowrap;color:#64748b;font-size:12px;">
                        {{ \Carbon\Carbon::parse($p->created_at)->format('d M Y') }}
                    </td>
                    <td style="font-weight:600;white-space:nowrap;font-size:12px;">
                        {{ $p->nim_nid }}
                    </td>
                    <td style="white-space:nowrap;font-weight:500;">
                        {{ $p->nama_mahasiswa }}
                    </td>
                    <td style="max-width:260px;">
                        <div class="judul-main">{{ $p->judul_1 }}</div>
                        @php $extraCount = ($p->judul_2 ? 1 : 0)+($p->judul_3 ? 1 : 0); @endphp
                        @if($extraCount > 0)
                        <span class="judul-lainnya"
                            onclick="lihatSemuaJudul('{{ e($p->judul_1) }}','{{ e($p->judul_2) }}','{{ e($p->judul_3) }}')">
                            +{{ $extraCount }} lainnya
                        </span>
                        @endif
                    </td>
                    <td>
                        @if(in_array($p->status, ['menunggu','menunggu verifikasi']))
                            <span class="badge badge-menunggu">Menunggu Verifikasi</span>
                        @elseif($p->status == 'disetujui')
                            <span class="badge badge-disetujui">Disetujui</span>
                        @elseif($p->status == 'ditolak')
                            <span class="badge badge-ditolak">Ditolak</span>
                        @else
                            <span class="badge badge-menunggu">{{ ucfirst($p->status) }}</span>
                        @endif
                    </td>
                    <td style="text-align:right;">
                        @if(in_array($p->status, ['menunggu','menunggu verifikasi']))
                        <a href="{{ route('admin.judul.show', $p->id) }}" class="btn-aksi btn-verifikasi">
                            <i class="fa-solid fa-circle-check" style="font-size:11px;"></i> Verifikasi
                        </a>
                        @else
                        <a href="{{ route('admin.judul.show', $p->id) }}" class="btn-aksi btn-detail-outline">
                            <i class="fa-solid fa-eye" style="font-size:11px;"></i> Detail
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fa-regular fa-folder-open"></i>
                            <p>
                                @if(request('search') || request('status') || request('tanggal'))
                                    Tidak ada data yang sesuai dengan filter yang dipilih.
                                @else
                                    Belum ada pengajuan judul.
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($pengajuanJudul->hasPages())
    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan {{ $pengajuanJudul->firstItem() }}–{{ $pengajuanJudul->lastItem() }}
            dari {{ $pengajuanJudul->total() }} data
        </div>
        {{ $pengajuanJudul->appends(request()->query())->links() }}
    </div>
    @else
    <div class="pagination-wrap">
        <div class="pagination-info">Total {{ $pengajuanJudul->total() }} data</div>
    </div>
    @endif

</div>

{{-- ══ MODAL SEMUA JUDUL ══ --}}
<div class="modal fade" id="modalJudul" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:18px;border:none;padding:4px;">
            <div class="modal-header" style="border:none;padding:20px 24px 12px;">
                <h5 class="modal-title" style="font-weight:800;font-size:16px;color:#1e293b;">
                    <i class="fa-solid fa-file-lines" style="color:#FACC15;margin-right:8px;"></i>
                    Semua Judul Pengajuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:8px 24px 24px;">
                <p style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:12px;">
                    Pilihan Judul
                </p>
                <div id="modalJudulList"></div>
            </div>
        </div>
    </div>
</div>

<script>
function lihatSemuaJudul(j1, j2, j3) {
    const judul  = [j1, j2, j3].filter(j => j && j.trim() !== '');
    const labels = ['Judul 1', 'Judul 2', 'Judul 3'];
    let html = '';
    judul.forEach((j, i) => {
        html += `<div style="margin-bottom:12px;">
            <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:5px;">${labels[i]}</div>
            <div class="modal-judul-alt">${j}</div>
        </div>`;
    });
    document.getElementById('modalJudulList').innerHTML = html;
    new bootstrap.Modal(document.getElementById('modalJudul')).show();
}

// Auto-submit search dengan debounce 500ms
let searchTimeout = null;
document.getElementById('inputSearch').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 500);
});
</script>

@endsection