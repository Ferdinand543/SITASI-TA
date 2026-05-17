@extends('layouts.app')

@section('content')


<!-- HERO -->
<div class="hero-section mb-4">
    <div class="hero-content" style="width:100%; text-align:center;">
        <h2 class="fw-bold" style="text-align:center;">Pengajuan Judul TA-1</h2>
        <p class="text-muted" style="text-align:center;">
            Tinjau dan verifikasi pengajuan judul TA-1 mahasiswa
        </p>
    </div>
</div>

<div class="container">

    <!-- FILTER & SEARCH -->
    <div class="d-flex justify-content-end align-items-center gap-2 mb-3 flex-wrap">

        <select id="filterStatus" class="form-select" style="width:180px;">
            <option value="">Semua Status</option>
            <option value="menunggu verifikasi">Menunggu Verifikasi</option>
            <option value="disetujui">Disetujui</option>
            <option value="ditolak">Ditolak</option>
        </select>

        <div class="input-group" style="width:260px;">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari nama, NIM, Judul">
            <span class="input-group-text">
                <i class="fa fa-search"></i>
            </span>
        </div>

        <button id="resetFilter" class="btn btn-outline-secondary d-flex align-items-center gap-1">
            <i class="fa fa-rotate-right"></i>
            Reset filter
        </button>

    </div>

    <!-- TABEL -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center" id="tabelPengajuan">
            <thead>
                <tr style="background:#f4b400; color:white;">
                    <th>No.</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Judul yang diajukan mahasiswa</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody id="tabelBody">

                @forelse($pengajuans as $index => $item)
                <tr
                    data-status="{{ strtolower($item->status) }}"
                    data-search="{{ strtolower($item->nim_nid . ' ' . $item->nama . ' ' . $item->judul_1 . ' ' . $item->judul_2 . ' ' . $item->judul_3) }}"
                >
                    <td>{{ $index + 1 }}</td>
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
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
                    <td>
                        @if(strtolower($item->status) === 'disetujui')
                            <span class="badge-status badge-disetujui">Disetujui</span>
                        @elseif(strtolower($item->status) === 'menunggu verifikasi')
                            <span class="badge-status badge-menunggu">Menunggu</span>
                        @else
                            <span class="badge-status badge-ditolak">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        @if(strtolower($item->status) === 'menunggu verifikasi')
                            <button
                                class="btn btn-sm btn-warning text-white fw-bold btn-verifikasi"
                                data-id="{{ $item->id }}"
                                data-nim="{{ $item->nim_nid }}"
                                data-nama="{{ $item->nama }}"
                            >
                                Verifikasi
                            </button>
                        @else
                            <a href="{{ route('pengajuan.verifikasi', $item->id) }}" class="btn btn-sm btn-outline-secondary">
                                Detail
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                {{-- Belum ada data dari DB sama sekali → inbox icon --}}
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

    {{-- Filter/search tidak nemu hasil → magnifier icon --}}
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
                        <span style="color:#f4b400; font-size:1.1rem; flex-shrink:0; margin-top:1px;">
                            <i class="fa-regular fa-circle-check"></i>
                        </span>
                        <p class="small mb-0">Koordinator hanya dapat menyetujui maksimal 1 judul dari beberapa usulan yang diajukan oleh mahasiswa.</p>
                    </div>

                    <div class="d-flex gap-2 mb-3 align-items-start">
                        <span style="color:#f4b400; font-size:1.1rem; flex-shrink:0; margin-top:1px;">
                            <i class="fa-regular fa-circle-check"></i>
                        </span>
                        <p class="small mb-0">Jika tidak ada judul yang sesuai, koordinator dapat menolak semua usulan.</p>
                    </div>

                    <div class="d-flex gap-2 mb-3 align-items-start">
                        <span style="color:#f4b400; font-size:1.1rem; flex-shrink:0; margin-top:1px;">
                            <i class="fa-regular fa-circle-check"></i>
                        </span>
                        <p class="small mb-0">Keputusan yang sudah disubmit tidak dapat diubah.</p>
                    </div>

                    <div class="d-flex gap-2 align-items-start">
                        <span style="color:#f4b400; font-size:1.1rem; flex-shrink:0; margin-top:1px;">
                            <i class="fa-regular fa-circle-check"></i>
                        </span>
                        <p class="small mb-0">Pastikan judul yang disetujui relevan dengan topik penelitian.</p>
                    </div>

                </div>

                <a href="#" id="btnTinjau" class="btn btn-warning w-100 fw-bold mb-2">
                    Tinjau dan verifikasi judul mahasiswa
                </a>
                <button class="btn btn-link text-muted" data-bs-dismiss="modal">Kembali</button>

            </div>
        </div>
    </div>
</div>

<style>
.hero-section {
    min-height: 220px !important;
    padding: 40px 60px !important;
    justify-content: center !important;
    background-image: url('/images/bg_ajukan.jpeg') !important;
    background-size: cover !important;
    background-position: center center !important;
    background-repeat: no-repeat !important;
}
.hero-section .hero-content {
    width: 100% !important;
    text-align: center !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
}
.hero-section .hero-content h2,
.hero-section .hero-content p {
    text-align: center !important;
    width: 100%;
}
.badge-status {
    display: inline-block;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 600;
}
.badge-disetujui { background: #d4edda; color: #28a745; }
.badge-menunggu  { background: #fff3cd; color: #856404; }
.badge-ditolak   { background: #f8d7da; color: #dc3545; }
#tabelPengajuan th { vertical-align: middle; white-space: nowrap; }
#tabelPengajuan td { vertical-align: middle; }
.sub-menu {
    text-decoration: none;
    color: #555;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 14px;
    transition: 0.2s;
}
.sub-menu:hover {
    background: #f7d27c;
    color: #000;
}
.sub-menu.active {
    background: #f7d27c;
    color: #000;
    font-weight: 500;
}
</style>

<script>
const filterStatusEl = document.getElementById('filterStatus');
const searchInputEl  = document.getElementById('searchInput');

filterStatusEl.addEventListener('change', filterTabel);
searchInputEl.addEventListener('input', filterTabel);

document.getElementById('resetFilter').addEventListener('click', function() {
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
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        document.getElementById('btnTinjau').href = '/pengajuan/verifikasi/' + id;
        const modal = new bootstrap.Modal(document.getElementById('modalInfoVerifikasi'));
        modal.show();
    });
});
</script>

@endsection