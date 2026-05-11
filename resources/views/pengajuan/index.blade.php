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

        <!-- FILTER STATUS -->
        <select id="filterStatus" class="form-select" style="width:180px;">
            <option value="">Semua Status</option>
            <option value="menunggu verifikasi">Menunggu Verifikasi</option>
            <option value="disetujui">Disetujui</option>
            <option value="ditolak">Ditolak</option>
        </select>

        <!-- SEARCH -->
        <div class="input-group" style="width:260px;">
            <input type="text"
                   id="searchInput"
                   class="form-control"
                   placeholder="Cari nama, NIM, Judul">

            <span class="input-group-text">
                <i class="fa fa-search"></i>
            </span>
        </div>

        <!-- RESET -->
        <button id="resetFilter"
                class="btn btn-outline-secondary d-flex align-items-center gap-1">
            <i class="fa fa-rotate-right"></i>
            Reset filter
        </button>

    </div>

    <!-- TABEL -->
    <div class="table-responsive">

        <table class="table table-bordered align-middle text-center"
               id="tabelPengajuan">

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
                    data-search="{{ strtolower(
                        $item->nim_nid . ' ' .
                        $item->nama . ' ' .
                        $item->judul_1 . ' ' .
                        $item->judul_2 . ' ' .
                        $item->judul_3
                    ) }}"
                >

                    <!-- NO -->
                    <td>{{ $index + 1 }}</td>

                    <!-- NIM -->
                    <td>{{ $item->nim_nid }}</td>

                    <!-- NAMA -->
                    <td>{{ $item->nama }}</td>

                    <!-- JUDUL -->
                    <td class="text-start">

                        @if(strtolower($item->status) === 'disetujui')

                            {{-- tampilkan judul yang disetujui --}}
                            {{ $item->judul_disetujui }}

                        @else

                            {{-- tampilkan judul pertama --}}
                            {{ $item->judul_1 }}

                            <div class="text-muted"
                                 style="font-size:0.78rem; margin-top:2px;">
                                +2 lainnya
                            </div>

                        @endif

                    </td>

                    <!-- TANGGAL -->
                    <td>
                        {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d F Y') }}
                    </td>

                    <!-- STATUS -->
                    <td>

                        @if(strtolower($item->status) === 'disetujui')

                            <span class="badge-status badge-disetujui">
                                Disetujui
                            </span>

                        @elseif(strtolower($item->status) === 'menunggu verifikasi')

                            <span class="badge-status badge-menunggu">
                                Menunggu
                            </span>

                        @else

                            <span class="badge-status badge-ditolak">
                                Ditolak
                            </span>

                        @endif

                    </td>

                    <!-- BUTTON -->
                    <td>

                        {{-- JIKA BELUM DIVERIFIKASI --}}
                        @if(strtolower($item->status) === 'menunggu verifikasi')

                            <button
                                class="btn btn-sm btn-warning text-white fw-bold btn-verifikasi"
                                data-id="{{ $item->id }}"
                                data-nim="{{ $item->nim_nid }}"
                                data-nama="{{ $item->nama }}"
                            >
                                Verifikasi
                            </button>

                        {{-- JIKA SUDAH DIVERIFIKASI --}}
                        @else

                            <a href="{{ route('pengajuan.detail', $item->id) }}"
                               class="btn btn-sm btn-outline-secondary">
                                Detail
                            </a>

                        @endif

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="7"
                        class="text-center text-muted py-4">

                        <i class="fa fa-inbox me-2"></i>
                        Belum ada pengajuan judul

                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <!-- PESAN KOSONG -->
    <div id="pesanKosong"
         class="text-center text-muted py-4 d-none">

        <i class="fa fa-inbox me-2"></i>
        Data tidak ditemukan

    </div>

</div>


<!-- MODAL INFORMASI -->
<div class="modal fade"
     id="modalInfoVerifikasi"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content p-3"
             style="border-radius:15px;">

            <div class="modal-body text-center">

                <h5 class="fw-bold mb-3">
                    Informasi
                </h5>

                <p class="text-muted small text-start">
                    Harap baca informasi berikut sebelum melakukan
                    verifikasi judul tugas akhir mahasiswa.
                </p>

                <div class="mb-3 p-3 text-start"
                     style="
                        background:#f9f9f9;
                        border-radius:10px;
                        border:1px solid #e0e0e0;
                     ">

                    <div class="d-flex gap-2 mb-3 align-items-start">

                        <span style="
                            color:#f4b400;
                            font-size:1.1rem;
                            flex-shrink:0;
                            margin-top:1px;
                        ">
                            <i class="fa-regular fa-circle-check"></i>
                        </span>

                        <p class="small mb-0">
                            Koordinator hanya dapat menyetujui maksimal
                            1 judul dari beberapa usulan yang diajukan
                            oleh mahasiswa.
                        </p>

                    </div>

                    <div class="d-flex gap-2 mb-3 align-items-start">

                        <span style="
                            color:#f4b400;
                            font-size:1.1rem;
                            flex-shrink:0;
                            margin-top:1px;
                        ">
                            <i class="fa-regular fa-circle-check"></i>
                        </span>

                        <p class="small mb-0">
                            Jika tidak ada judul yang sesuai,
                            koordinator dapat menolak semua usulan.
                        </p>

                    </div>

                    <div class="d-flex gap-2 mb-3 align-items-start">

                        <span style="
                            color:#f4b400;
                            font-size:1.1rem;
                            flex-shrink:0;
                            margin-top:1px;
                        ">
                            <i class="fa-regular fa-circle-check"></i>
                        </span>

                        <p class="small mb-0">
                            Keputusan yang sudah disubmit
                            tidak dapat diubah.
                        </p>

                    </div>

                    <div class="d-flex gap-2 align-items-start">

                        <span style="
                            color:#f4b400;
                            font-size:1.1rem;
                            flex-shrink:0;
                            margin-top:1px;
                        ">
                            <i class="fa-regular fa-circle-check"></i>
                        </span>

                        <p class="small mb-0">
                            Pastikan judul yang disetujui relevan
                            dengan topik penelitian.
                        </p>

                    </div>

                </div>

                <a href="#"
                   id="btnTinjau"
                   class="btn btn-warning w-100 fw-bold mb-2">

                    Tinjau dan verifikasi judul mahasiswa

                </a>

                <button class="btn btn-link text-muted"
                        data-bs-dismiss="modal">

                    Kembali

                </button>

            </div>

        </div>

    </div>

</div>


<style>

.hero-section{
    min-height:160px !important;
    padding:30px 60px !important;
    justify-content:center !important;
}

.hero-section .hero-content{
    width:100% !important;
    text-align:center !important;
    display:flex !important;
    flex-direction:column !important;
    align-items:center !important;
}

.hero-section .hero-content h2,
.hero-section .hero-content p{
    text-align:center !important;
    width:100%;
}

.badge-status{
    display:inline-block;
    padding:5px 14px;
    border-radius:20px;
    font-size:0.82rem;
    font-weight:600;
}

.badge-disetujui{
    background:#d4edda;
    color:#28a745;
}

.badge-menunggu{
    background:#fff3cd;
    color:#856404;
}

.badge-ditolak{
    background:#f8d7da;
    color:#dc3545;
}

#tabelPengajuan th{
    vertical-align:middle;
    white-space:nowrap;
}

#tabelPengajuan td{
    vertical-align:middle;
}

</style>


<script>

const filterStatusEl = document.getElementById('filterStatus');
const searchInputEl  = document.getElementById('searchInput');

filterStatusEl.addEventListener('change', filterTabel);
searchInputEl.addEventListener('input', filterTabel);


// RESET FILTER
document.getElementById('resetFilter')
.addEventListener('click', function () {

    filterStatusEl.value = '';
    searchInputEl.value  = '';

    filterTabel();

});


// FILTER
function filterTabel(){

    const status = filterStatusEl.value.toLowerCase();
    const search = searchInputEl.value.toLowerCase();

    const rows = document.querySelectorAll(
        '#tabelBody tr[data-status]'
    );

    let adaData = false;

    rows.forEach(row => {

        const rowStatus = row
            .getAttribute('data-status')
            .toLowerCase();

        const rowSearch = row
            .getAttribute('data-search')
            .toLowerCase();

        const cocokStatus =
            status === '' || rowStatus === status;

        const cocokSearch =
            search === '' || rowSearch.includes(search);

        if(cocokStatus && cocokSearch){

            row.style.display = '';
            adaData = true;

        }else{

            row.style.display = 'none';

        }

    });

    document.getElementById('pesanKosong')
        .classList.toggle('d-none', adaData);
}


// BUTTON VERIFIKASI
document.querySelectorAll('.btn-verifikasi')
.forEach(btn => {

    btn.addEventListener('click', function () {

        const id = this.getAttribute('data-id');

        document.getElementById('btnTinjau').href =
            '/pengajuan/detail/' + id;

        const modal = new bootstrap.Modal(
            document.getElementById('modalInfoVerifikasi')
        );

        modal.show();

    });

});

</script>

@endsection