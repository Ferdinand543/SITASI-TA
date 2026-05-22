@extends('layouts.app')

@section('content')

<!-- HERO -->
<div class="hero-section mb-4">
    <div class="hero-content">
        <h2 class="fw-bold">Pengajuan Judul Tugas Akhir Mahasiswa</h2>
        <p>Monitoring dan verifikasi pengajuan judul tugas akhir mahasiswa.</p>
    </div>
</div>

<div class="container">

    <!-- SUMMARY CARDS -->
    @php
        $total      = $pengajuans->count();
        $menunggu   = $pengajuans->where('status', 'menunggu verifikasi')->count();
        $disetujui  = $pengajuans->where('status', 'disetujui')->count();
        $ditolak    = $pengajuans->where('status', 'ditolak')->count();
    @endphp

    <div class="summary-grid mb-4">
        <div class="summary-card">
            <div class="summary-label">Total Pengajuan</div>
            <div class="summary-value text-dark">{{ $total }}</div>
        </div>
        <div class="summary-card border-menunggu">
            <div class="summary-label">Menunggu Verifikasi</div>
            <div class="summary-value text-menunggu">{{ $menunggu }}</div>
        </div>
        <div class="summary-card border-disetujui">
            <div class="summary-label">Disetujui</div>
            <div class="summary-value text-disetujui">{{ $disetujui }}</div>
        </div>
        <div class="summary-card border-ditolak">
            <div class="summary-label">Ditolak</div>
            <div class="summary-value text-ditolak">{{ $ditolak }}</div>
        </div>
    </div>

    <!-- FILTER & SEARCH -->
    <div class="filter-bar mb-3">
        <div class="filter-search-wrap">
            <label class="filter-label">Cari Mahasiswa atau Judul</label>
            <div class="input-group search-group">
                <span class="input-group-text search-icon">
                    <i class="fa fa-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control" placeholder="Masukkan NIM, nama, atau kata kunci judul...">
            </div>
        </div>

        <div class="filter-right-wrap">
            <div>
                <label class="filter-label">Status</label>
                <div class="d-flex gap-2">
                    <select id="filterStatus" class="form-select filter-select">
                        <option value="">Semua Status</option>
                        <option value="menunggu verifikasi">Menunggu Verifikasi</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                    <button id="resetFilter" class="btn btn-reset d-flex align-items-center gap-1">
                        <i class="fa fa-rotate-right"></i>
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL -->
    <div class="table-responsive" id="tabelWrapper">
        <table class="table table-bordered align-middle text-center" id="tabelPengajuan">
            <thead>
                <tr class="tabel-header">
                    <th style="width:50px;">No.</th>
                    <th>Tanggal Pengajuan</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th class="text-start">Judul yang Diajukan</th>
                    <th>Status</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody id="tabelBody">

                @forelse($pengajuans as $index => $item)
                <tr
                    data-status="{{ strtolower($item->status) }}"
                    data-search="{{ strtolower($item->nim_nid . ' ' . $item->nama . ' ' . $item->judul_1 . ' ' . $item->judul_2 . ' ' . $item->judul_3) }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
                    <td>{{ $item->nim_nid }}</td>
                    <td>{{ $item->nama }}</td>
                    <td class="text-start">
                        @if(strtolower($item->status) === 'disetujui')
                            {{ $item->judul_disetujui }}
                        @else
                            {{ $item->judul_1 }}
                            <div class="text-muted" style="font-size:0.78rem; margin-top:2px;">+2 lainnya</div>
                        @endif
                    </td>
                    <td>
                        @if(strtolower($item->status) === 'disetujui')
                            <span class="badge-status badge-disetujui">Disetujui</span>
                        @elseif(strtolower($item->status) === 'menunggu verifikasi')
                            <span class="badge-status badge-menunggu">Menunggu<br>Verifikasi</span>
                        @else
                            <span class="badge-status badge-ditolak">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        @if(strtolower($item->status) === 'menunggu verifikasi')
                            <button
                                class="btn-verifikasi"
                                data-id="{{ $item->id }}"
                                data-nim="{{ $item->nim_nid }}"
                                data-nama="{{ $item->nama }}">
                                <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 8h9M8 5l3 3-3 3"/></svg>
                                Verifikasi
                            </button>
                        @else
                            <a href="{{ route('pengajuan.verifikasi', $item->id) }}" class="btn-detail-pill">
                                Detail
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr id="rowKosongDefault">
                    <td colspan="7" style="padding: 60px 20px; text-align: center; border: none;">
                        <div style="display:inline-flex; flex-direction:column; align-items:center; gap:12px;">
                            <div style="width:64px; height:64px; background:#f1f5f9; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                <i class="fa fa-inbox" style="font-size:1.8rem; color:#94a3b8;"></i>
                            </div>
                            <div style="font-size:0.95rem; font-weight:700; color:#475569;">Belum ada data</div>
                            <div style="font-size:0.82rem; color:#94a3b8;">Data akan muncul setelah proses dilakukan.</div>
                        </div>
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    {{-- Filter/search tidak nemu hasil --}}
    <div id="pesanKosong" class="d-none">
        <table class="table table-bordered text-center">
            <tbody>
                <tr>
                    <td colspan="7" style="padding: 60px 20px; text-align: center; border: none;">
                        <div style="display:inline-flex; flex-direction:column; align-items:center; gap:12px;">
                            <div style="width:64px; height:64px; background:#f1f5f9; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                <i class="fa fa-magnifying-glass" style="font-size:1.8rem; color:#94a3b8;"></i>
                            </div>
                            <div style="font-size:0.95rem; font-weight:700; color:#475569;">Data tidak ditemukan</div>
                            <div style="font-size:0.82rem; color:#94a3b8;">Coba gunakan kata kunci atau filter yang berbeda.</div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<!-- MODAL INFORMASI -->
<div class="modal fade" id="modalInfoVerifikasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3" style="border-radius:15px;">
            <div class="modal-body text-center">

                <h5 class="fw-bold mb-3">Informasi</h5>
                <p class="text-muted small text-start">
                    Harap baca informasi berikut sebelum melakukan verifikasi judul tugas akhir mahasiswa.
                </p>

                <div class="mb-3 p-3 text-start" style="background:#f9f9f9; border-radius:10px; border:1px solid #e0e0e0;">
                    <div class="d-flex gap-2 mb-3 align-items-start">
                        <span style="color:#f4b400; font-size:1.1rem; flex-shrink:0; margin-top:1px;"><i class="fa-regular fa-circle-check"></i></span>
                        <p class="small mb-0">Koordinator hanya dapat menyetujui maksimal 1 judul dari beberapa usulan yang diajukan oleh mahasiswa.</p>
                    </div>
                    <div class="d-flex gap-2 mb-3 align-items-start">
                        <span style="color:#f4b400; font-size:1.1rem; flex-shrink:0; margin-top:1px;"><i class="fa-regular fa-circle-check"></i></span>
                        <p class="small mb-0">Jika tidak ada judul yang sesuai, koordinator dapat menolak semua usulan.</p>
                    </div>
                    <div class="d-flex gap-2 mb-3 align-items-start">
                        <span style="color:#f4b400; font-size:1.1rem; flex-shrink:0; margin-top:1px;"><i class="fa-regular fa-circle-check"></i></span>
                        <p class="small mb-0">Keputusan yang sudah disubmit tidak dapat diubah.</p>
                    </div>
                    <div class="d-flex gap-2 align-items-start">
                        <span style="color:#f4b400; font-size:1.1rem; flex-shrink:0; margin-top:1px;"><i class="fa-regular fa-circle-check"></i></span>
                        <p class="small mb-0">Pastikan judul yang disetujui relevan dengan topik penelitian.</p>
                    </div>
                </div>

                <a href="#" id="btnTinjau" class="btn w-100 fw-bold mb-2"
                    style="background:#FEF9C3; color:#6C5700; border:1px solid #FFE083;">
                    Tinjau dan verifikasi judul mahasiswa
                </a>
                <button class="btn btn-link text-muted" data-bs-dismiss="modal">Kembali</button>

            </div>
        </div>
    </div>
</div>

<style>
    /* ── HERO ── */
    .hero-section {
        min-height: 220px !important;
        padding: 40px 60px !important;
        display: flex !important;
        align-items: center !important;
        background-image: url('/images/1.jpeg') !important;
        background-size: contain !important;
        background-position: right center !important;
        background-color: #FFFBEA !important;
        background-repeat: no-repeat !important;
        border-radius: 20px !important;
    }
    .hero-section .hero-content { max-width: 50%; }
    .hero-section .hero-content h2 { color: #735C00; font-size: 1.8rem; margin-bottom: 6px; }
    .hero-section .hero-content p  { color: #92400E; font-size: 0.9rem; margin: 0; }

    /* ── SUMMARY CARDS ── */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    .summary-card {
        background: #fff;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        padding: 18px 22px;
        border-left: 4px solid #E5E7EB;
    }
    .summary-card.border-menunggu { border-left-color: #F59E0B; }
    .summary-card.border-disetujui { border-left-color: #22C55E; }
    .summary-card.border-ditolak  { border-left-color: #EF4444; }
    .summary-label {
        font-size: 0.82rem;
        color: #6B7280;
        margin-bottom: 6px;
        font-weight: 500;
    }
    .summary-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
    }
    .text-menunggu  { color: #D97706; }
    .text-disetujui { color: #16A34A; }
    .text-ditolak   { color: #DC2626; }

    /* ── FILTER BAR ── */
    .filter-bar {
        background: #fff;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        padding: 18px 22px;
        display: flex;
        align-items: flex-end;
        gap: 16px;
        flex-wrap: wrap;
    }
    .filter-search-wrap { flex: 1; min-width: 220px; }
    .filter-right-wrap  { display: flex; align-items: flex-end; }
    .filter-label {
        display: block;
        font-size: 0.82rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }
    .search-group .search-icon {
        background: #fff;
        border-right: none;
        color: #9CA3AF;
    }
    .search-group .form-control {
        border-left: none;
        padding-left: 0;
    }
    .search-group .form-control:focus {
        box-shadow: none;
        border-color: #ced4da;
    }
    .filter-select { min-width: 160px; }
    .btn-reset {
        background: #fff;
        border: 1px solid #D1D5DB;
        color: #374151;
        font-size: 0.88rem;
        white-space: nowrap;
    }
    .btn-reset:hover { background: #F9FAFB; }

    /* ── TABLE ── */
    .tabel-header th {
        background: #F9FAFB;
        color: #374151;
        font-weight: 600;
        font-size: 0.85rem;
        vertical-align: middle;
        white-space: nowrap;
        border-bottom: 2px solid #E5E7EB;
    }
    #tabelPengajuan td { vertical-align: middle; font-size: 0.88rem; }
    #tabelPengajuan { border-color: #E5E7EB; }

    /* ── BADGES ── */
    .badge-status {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        line-height: 1.4;
    }
    .badge-disetujui { background: #DCFCE7; color: #15803D; }
    .badge-menunggu  { background: #FEF3C7; color: #92400E; }
    .badge-ditolak   { background: #FEE2E2; color: #B91C1C; }

    /* ── BUTTONS ── */
    .btn-verifikasi {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #FEF9C3;
        color: #6C5700;
        border: 1px solid #FDE047;
        border-radius: 8px;
        padding: 5px 16px;
        font-size: 0.82rem;
        font-weight: 600;
        transition: background 0.15s, transform 0.1s;
    }
    .btn-verifikasi:hover {
        background: #FDE68A;
        color: #6C5700;
        border-color: #FACC15;
        transform: scale(0.98);
    }
    .btn-detail-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fff;
        color: #374151;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        padding: 5px 16px;
        font-size: 0.82rem;
        font-weight: 500;
        text-decoration: none;
        transition: background 0.15s, border-color 0.15s, transform 0.1s;
    }
    .btn-detail-pill:hover {
        background: #F9FAFB;
        border-color: #9CA3AF;
        color: #111827;
        transform: scale(0.98);
    }

    @media (max-width: 768px) {
        .summary-grid { grid-template-columns: repeat(2, 1fr); }
        .filter-bar   { flex-direction: column; align-items: stretch; }
        .filter-right-wrap { flex-direction: column; gap: 8px; }
    }
</style>

<script>
    const filterStatusEl = document.getElementById('filterStatus');
    const searchInputEl  = document.getElementById('searchInput');

    filterStatusEl.addEventListener('change', filterTabel);
    searchInputEl.addEventListener('input', filterTabel);

    document.getElementById('resetFilter').addEventListener('click', function () {
        filterStatusEl.value = '';
        searchInputEl.value  = '';
        filterTabel();
    });

    function filterTabel() {
        const status = filterStatusEl.value.toLowerCase();
        const search = searchInputEl.value.toLowerCase();
        const rows   = document.querySelectorAll('#tabelBody tr[data-status]');
        let adaData  = false;

        rows.forEach(row => {
            const cocokStatus = status === '' || row.getAttribute('data-status').toLowerCase() === status;
            const cocokSearch = search === '' || row.getAttribute('data-search').toLowerCase().includes(search);

            if (cocokStatus && cocokSearch) {
                row.style.display = '';
                adaData = true;
            } else {
                row.style.display = 'none';
            }
        });

        const tabel   = document.getElementById('tabelPengajuan');
        const pesanEl = document.getElementById('pesanKosong');

        if (!adaData && rows.length > 0) {
            tabel.classList.add('d-none');
            pesanEl.classList.remove('d-none');
        } else {
            tabel.classList.remove('d-none');
            pesanEl.classList.add('d-none');
        }
    }

    document.querySelectorAll('.btn-verifikasi').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            document.getElementById('btnTinjau').href = '/pengajuan/verifikasi/' + id;
            const modal = new bootstrap.Modal(document.getElementById('modalInfoVerifikasi'));
            modal.show();
        });
    });
</script>

@endsection