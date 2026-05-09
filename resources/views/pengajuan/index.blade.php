@extends('layouts.app')

@section('content')

<!-- HERO -->
<div class="hero-section mb-4">
    <div class="hero-content" style="width:100%; text-align:center;">
        <h2 class="fw-bold" style="text-align:center;">Pengajuan Judul TA-1</h2>
        <p class="text-muted" style="text-align:center;">Tinjau dan verifikasi pengajuan judul TA-1 mahasiswa</p>
    </div>
</div>

<div class="container">

    <!-- FILTER & SEARCH -->
    <div class="d-flex justify-content-end align-items-center gap-2 mb-3 flex-wrap">

        {{-- FILTER STATUS --}}
        <select id="filterStatus" class="form-select" style="width:180px;">
            <option value="">Semua Status</option>
            <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
            <option value="Disetujui">Disetujui</option>
            <option value="Ditolak">Ditolak</option>
        </select>

        {{-- SEARCH --}}
        <div class="input-group" style="width:260px;">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari nama, NIM, Judul">
            <span class="input-group-text"><i class="fa fa-search"></i></span>
        </div>

        {{-- RESET --}}
        <button id="resetFilter" class="btn btn-outline-secondary d-flex align-items-center gap-1">
            <i class="fa fa-rotate-right"></i> Reset filter
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

                {{-- ================================================================
                     TODO: GANTI BAGIAN INI DENGAN DATA DARI DATABASE
                     Struktur data dari DB yang dibutuhkan per mahasiswa:
                     - $item->id                → ID pengajuan
                     - $item->nim               → NIM mahasiswa
                     - $item->nama              → Nama mahasiswa
                     - $item->status            → "Disetujui" / "Menunggu Verifikasi" / "Ditolak"
                     - $item->judul_1           → Judul pilihan pertama
                     - $item->judul_2           → Judul pilihan kedua
                     - $item->judul_3           → Judul pilihan ketiga
                     - $item->judul_disetujui   → Judul yang dipilih koor (hanya ada kalau Disetujui)
                     - $item->tanggal_pengajuan → Tanggal pengajuan

                     Logika kolom Judul:
                     → Jika status = "Disetujui"            : tampilkan $item->judul_disetujui saja
                     → Jika status = "Menunggu" / "Ditolak" : tampilkan $item->judul_1 + teks "+2 lainnya"

                     @forelse($pengajuans as $index => $item)
                     <tr data-status="{{ $item->status }}"
                         data-search="{{ strtolower($item->nim . ' ' . $item->nama . ' ' . $item->judul_1 . ' ' . $item->judul_2 . ' ' . $item->judul_3) }}">
                         <td>{{ $index + 1 }}</td>
                         <td>{{ $item->nim }}</td>
                         <td>{{ $item->nama }}</td>
                         <td class="text-start">
                             @if($item->status === 'Disetujui')
                                 {{ $item->judul_disetujui }}
                             @else
                                 {{ $item->judul_1 }}
                                 <div class="text-muted" style="font-size:0.78rem;">+2 lainnya</div>
                             @endif
                         </td>
                         <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
                         <td>
                             @if($item->status === 'Disetujui')
                                 <span class="badge-status badge-disetujui">Disetujui</span>
                             @elseif($item->status === 'Menunggu Verifikasi')
                                 <span class="badge-status badge-menunggu">Menunggu</span>
                             @else
                                 <span class="badge-status badge-ditolak">Ditolak</span>
                             @endif
                         </td>
                         <td class="d-flex gap-1 justify-content-center">
                             <a href="{{ route('pengajuan.detail', $item->id) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                             @if($item->status === 'Menunggu Verifikasi')
                                 <button class="btn btn-sm btn-warning text-white fw-bold btn-verifikasi"
                                     data-id="{{ $item->id }}"
                                     data-nim="{{ $item->nim }}"
                                     data-nama="{{ $item->nama }}">
                                     Verifikasi
                                 </button>
                             @endif
                         </td>
                     </tr>
                     @empty
                     @endforelse
                     ================================================================ --}}

                {{-- ROW 1: DISETUJUI → tampil judul yang disetujui saja --}}
                <tr data-status="Disetujui"
                    data-search="235092820 budi santoso perancangan sistem informasi manajemen berbasis web">
                    <td>1</td>
                    <td>235092820</td>
                    <td>Budi Santoso</td>
                    <td class="text-start">
                        Perancangan Sistem Informasi Manajemen Berbasis Web
                    </td>
                    <td>20 Mei 2026</td>
                    <td><span class="badge-status badge-disetujui">Disetujui</span></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-outline-secondary">Detail</a>
                    </td>
                </tr>

                {{-- ROW 2: MENUNGGU → tampil judul_1 + "+2 lainnya" --}}
                <tr data-status="Menunggu Verifikasi"
                    data-search="235092821 siti rahayu analisis keamanan data pada aplikasi e-commerce">
                    <td>2</td>
                    <td>235092821</td>
                    <td>Siti Rahayu</td>
                    <td class="text-start">
                        Analisis Keamanan Data pada Aplikasi E-Commerce
                        <div class="text-muted" style="font-size:0.78rem; margin-top:2px;">+2 lainnya</div>
                    </td>
                    <td>21 Mei 2026</td>
                    <td><span class="badge-status badge-menunggu">Menunggu</span></td>
                    <td class="d-flex gap-1 justify-content-center">
                        <a href="#" class="btn btn-sm btn-outline-secondary">Detail</a>
                        <button class="btn btn-sm btn-warning text-white fw-bold btn-verifikasi"
                            data-id="2"
                            data-nim="235092821"
                            data-nama="Siti Rahayu">
                            Verifikasi
                        </button>
                    </td>
                </tr>

                {{-- ROW 3: DITOLAK → tampil judul_1 + "+2 lainnya" --}}
                <tr data-status="Ditolak"
                    data-search="235092822 ahmad fauzi implementasi machine learning untuk prediksi penjualan">
                    <td>3</td>
                    <td>235092822</td>
                    <td>Ahmad Fauzi</td>
                    <td class="text-start">
                        Implementasi Machine Learning untuk Prediksi Penjualan
                        <div class="text-muted" style="font-size:0.78rem; margin-top:2px;">+2 lainnya</div>
                    </td>
                    <td>22 Mei 2026</td>
                    <td><span class="badge-status badge-ditolak">Ditolak</span></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-outline-secondary">Detail</a>
                    </td>
                </tr>

                <!-- Baris kosong tampilan (hapus setelah konek DB) -->
                <tr><td colspan="7" style="height:48px; border-color:#e9ecef;"></td></tr>
                <tr><td colspan="7" style="height:48px; border-color:#e9ecef;"></td></tr>
                <tr><td colspan="7" style="height:48px; border-color:#e9ecef;"></td></tr>
                <tr><td colspan="7" style="height:48px; border-color:#e9ecef;"></td></tr>

            </tbody>
        </table>
    </div>

    <!-- PESAN KOSONG -->
    <div id="pesanKosong" class="text-center text-muted py-4 d-none">
        <i class="fa fa-inbox me-2"></i> Belum ada pengajuan judul
    </div>

</div>

<!-- MODAL INFORMASI SEBELUM VERIFIKASI -->
<div class="modal fade" id="modalInfoVerifikasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3" style="border-radius:15px;">
            <div class="modal-body text-center">
                <h5 class="fw-bold mb-3">Informasi</h5>
                <p class="text-muted small text-start">Harap baca informasi berikut sebelum melakukan verifikasi judul tugas akhir mahasiswa.</p>

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
                        <p class="small mb-0">Jika tidak ada judul yang sesuai, koordinator dapat menolak semua usulan yang diajukan mahasiswa.</p>
                    </div>

                    <div class="d-flex gap-2 mb-3 align-items-start">
                        <span style="color:#f4b400; font-size:1.1rem; flex-shrink:0; margin-top:1px;">
                            <i class="fa-regular fa-circle-check"></i>
                        </span>
                        <p class="small mb-0">Keputusan yang sudah disubmit tidak dapat diubah. Pastikan keputusan telah sesuai sebelum menyimpan.</p>
                    </div>

                    <div class="d-flex gap-2 align-items-start">
                        <span style="color:#f4b400; font-size:1.1rem; flex-shrink:0; margin-top:1px;">
                            <i class="fa-regular fa-circle-check"></i>
                        </span>
                        <p class="small mb-0">Pastikan judul yang disetujui relevan dengan topik penelitian, layak untuk diteliti, dan sesuai dengan ketentuan akademik.</p>
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
    /* ── KECILKAN HERO ── */
    .hero-section {
        min-height: 160px !important;
        padding: 30px 60px !important;
        justify-content: center !important;
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

    /* ── BADGE STATUS ── */
    .badge-status {
        display: inline-block;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 600;
    }

    .badge-disetujui {
        background: #d4edda;
        color: #28a745;
    }

    .badge-menunggu {
        background: #fff3cd;
        color: #856404;
    }

    .badge-ditolak {
        background: #f8d7da;
        color: #dc3545;
    }

    #tabelPengajuan th {
        vertical-align: middle;
        white-space: nowrap;
    }

    #tabelPengajuan td {
        vertical-align: middle;
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
            const rowStatus = row.getAttribute('data-status').toLowerCase();
            const rowSearch = row.getAttribute('data-search').toLowerCase();
            const cocokStatus = status === '' || rowStatus === status;
            const cocokSearch = search === '' || rowSearch.includes(search);

            if (cocokStatus && cocokSearch) {
                row.style.display = '';
                adaData = true;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('pesanKosong').classList.toggle('d-none', adaData);
    }

    document.querySelectorAll('.btn-verifikasi').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            document.getElementById('btnTinjau').href = '/pengajuan/detail/' + id;
            const modal = new bootstrap.Modal(document.getElementById('modalInfoVerifikasi'));
            modal.show();
        });
    });
</script>

@endsection