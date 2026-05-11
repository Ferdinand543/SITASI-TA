@extends('layouts.app')

@section('content')

<style>
    #cardAjukan {
        background-image: url("{{ asset('images/bg_ajukan.jpeg') }}");
        background-size: cover;
        background-position: center;
        min-height: 160px;
        border-radius: 12px;
    }

    #tabelPengajuan,
    #tabelPengajuan th,
    #tabelPengajuan td {
        border-color: #000 !important;
    }

    .link-detail {
        background: #F5E6B8;
        color: #B86A00;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        padding: 10px 18px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        border: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        min-width: 190px;
        justify-content: center;
    }

    .link-detail:hover {
        background: #efd89a;
        color: #9c5800;
        transform: translateY(-1px);
    }

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

@php
$iconEye = '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16" style="vertical-align:-2px;margin-right:4px;">
    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
</svg>';

$iconReset = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" style="vertical-align:-2px;margin-right:3px;">
    <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
    <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
</svg>';

$iconSearch = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z" />
</svg>';

$iconCheck = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#FFC107" viewBox="0 0 16 16" style="flex-shrink:0; margin-top:1px;">
    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
    <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z" />
</svg>';
@endphp

<!-- SUB-MENU -->
<div class="container">
    <div style="display:flex; gap:8px; padding:10px 0 14px 0;">
        <a href="/dashboard/mahasiswa" class="sub-menu">Beranda</a>
        <a href="#" class="sub-menu">Aktivitas</a>
        <a href="#" class="sub-menu">Dosen Pembimbing</a>
        <a href="#" class="sub-menu">Panduan TA</a>
    </div>
</div>

<div class="container mt-4">
    <br>
    {{-- ── NAV MINI ──────────────────────────────────────────── --}}


    {{-- Tampilkan pesan sukses --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div id="cardAjukan" class="card pengajuan-card mb-4 overflow-hidden border-0">
        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center py-4">
            <h3 class="fw-bold mb-1">Pengajuan Judul TA-1</h3>
            <p class="text-muted mb-3">Ajukan Judul Tugas Akhir Anda</p>
            <button class="btn btn-warning px-4 fw-semibold" style="border-radius:8px;"
                data-bs-toggle="modal" data-bs-target="#modalInfo">
                Ajukan Judul
            </button>
        </div>
    </div>

    <div class="d-flex justify-content-end align-items-center gap-2 mb-3 flex-wrap">
        <select id="filterStatus" class="form-select form-select-sm"
            style="width:auto; min-width:155px; border-radius:8px;">
            <option value="">Semua Status</option>
            <option value="disetujui">Disetujui</option>
            <option value="menunggu verifikasi">Menunggu</option>
            <option value="ditolak">Ditolak</option>
        </select>

        <div class="input-group input-group-sm" style="width:auto;">
            <input type="text" id="searchInput" class="form-control"
                placeholder="Cari Judul" style="border-radius:8px 0 0 8px;">
            <span class="input-group-text" style="border-radius:0 8px 8px 0; background:#fff;">
                {!! $iconSearch !!}
            </span>
        </div>

        <button class="btn btn-sm btn-outline-secondary d-flex align-items-center"
            id="btnResetFilter" style="border-radius:8px; gap:4px;">
            {!! $iconReset !!} Reset filter
        </button>
    </div>

    <div class="card p-3 border-0 shadow-sm" style="border-radius:12px;">
        <table class="table table-bordered align-middle text-center mb-0" id="tabelPengajuan">
            <thead>
                <tr>
                    <th style="background:#FFC107;color:#000;border-color:#000;">No.</th>
                    <th style="background:#FFC107;color:#000;border-color:#000;">Tanggal Pengajuan</th>
                    <th style="background:#FFC107;color:#000;border-color:#000;">Usulan Judul</th>
                    <th style="background:#FFC107;color:#000;border-color:#000;">Status</th>
                    <th style="background:#FFC107;color:#000;border-color:#000;">Detail</th>
                </tr>
            </thead>
            <tbody>

                @forelse($pengajuanList as $index => $item)
                <tr data-status="{{ strtolower($item->status) }}"
                    data-search="{{ strtolower($item->judul_1 . ' ' . $item->nim_nid) }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d M Y') }}</td>
                    <td class="text-start">
                        {{ \Illuminate\Support\Str::limit($item->judul_1, 35) }}
                        <br><small class="text-muted">+ 2 lainnya</small>
                    </td>
                    <td>
                        @php $st = strtolower($item->status); @endphp
                        @if($st === 'disetujui')
                        <span class="badge rounded-pill px-3 py-2" style="background:#d4edda;color:#28a745;border:1px solid #b7dfbb;">Disetujui</span>
                        @elseif(str_contains($st, 'menunggu'))
                        <span class="badge rounded-pill px-3 py-2" style="background:#fff3cd;color:#856404;border:1px solid #ffd96a;">Menunggu</span>
                        @elseif($st === 'ditolak')
                        <span class="badge rounded-pill px-3 py-2" style="background:#f8d7da;color:#dc3545;border:1px solid #f1aeb5;">Ditolak</span>
                        @else
                        <span class="badge rounded-pill px-3 py-2 bg-secondary">{{ $item->status }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('pengajuan.detail', $item->id) }}" class="link-detail">
                            {!! $iconEye !!} Lihat Pengajuan
                        </a>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">Belum ada pengajuan.</td>
                </tr>
                @endforelse

            </tbody>
        </table>
        <div id="noResult" class="text-center text-muted py-3 d-none">Tidak ada data yang sesuai filter.</div>
    </div>

</div>


{{-- ── MODAL 1: Info Pengajuan ── --}}
<div class="modal fade" id="modalInfo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:0; padding: 28px 24px 20px;">

            <h2 class="fw-bold mb-1" style="font-size:1.6rem;">Pengajuan Judul TA</h2>
            <p class="text-muted mb-4" style="font-size:0.93rem;">
                Ajukan judul tugas akhir sesuai minat dan topik penelitianmu.
            </p>

            <div class="mb-4 p-3" style="border:1.5px solid #ddd; border-radius:12px;">
                <p class="fw-bold mb-3 text-center" style="font-size:0.95rem;">Informasi Pengajuan</p>

                <div class="d-flex gap-2 mb-3 align-items-center">
                    {!! $iconCheck !!}
                    <span style="font-size:0.88rem;">Mahasiswa wajib mengajukan 3 judul Tugas Akhir</span>
                </div>
                <div class="d-flex gap-2 mb-3">
                    {!! $iconCheck !!}
                    <span style="font-size:0.88rem;">Judul pertama merupakan judul yang paling kamu inginkan untuk diteliti</span>
                </div>
                <div class="d-flex gap-2 mb-3">
                    {!! $iconCheck !!}
                    <span style="font-size:0.88rem;">Topik setiap judul boleh sama maupun berbeda</span>
                </div>
                <div class="d-flex gap-2">
                    {!! $iconCheck !!}
                    <span style="font-size:0.88rem;">Pastikan judul yang diajukan jelas, spesifik, dan relevan dengan bidang penelitian</span>
                </div>
            </div>

            <button class="btn btn-warning w-100 fw-bold py-2 mb-2" style="border-radius:10px; font-size:1rem;"
                id="btnLanjutKeForm">
                Silahkan ajukan judul
            </button>

            <div class="text-center">
                <a href="#" class="text-muted" style="font-size:0.88rem; text-decoration:none;"
                    data-bs-dismiss="modal">Kembali</a>
            </div>

        </div>
    </div>
</div>


{{-- ── MODAL 2: Form Pengajuan (scrollable) ── --}}
<div class="modal fade" id="modalFormTA" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:16px; border:0;">

            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div>
                    <h2 class="fw-bold mb-1" style="font-size:1.5rem;">Form Pengajuan Judul TA</h2>
                    <p class="text-muted mb-0" style="font-size:0.88rem;">Lengkapi data berikut untuk mengajukan judul TA</p>
                </div>
            </div>

            <div class="modal-body px-4 pt-2 pb-2">
                <div id="errorAlertTA" class="alert alert-danger d-none">
                    Semua field wajib diisi, Mitra Penelitian Opsional!
                </div>

                {{-- Form pakai action ke route store --}}
                <form id="formPengajuanTA" action="{{ route('pengajuan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.88rem;">NIM</label>
                        <input type="text" name="nim_nid" id="ta_nim" class="form-control" style="border-radius:8px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.88rem;">Nama</label>
                        <input type="text" name="nama" id="ta_nama" class="form-control" style="border-radius:8px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.88rem;">Tanggal Pengajuan</label>
                        <input type="date" name="tanggal_pengajuan" id="ta_tanggal" class="form-control" style="border-radius:8px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.88rem;">Topik Penelitian 1</label>
                        <input type="text" name="topik_1" id="ta_topik1" class="form-control" style="border-radius:8px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.88rem;">Judul 1</label>
                        <input type="text" name="judul_1" id="ta_judul1" class="form-control" style="border-radius:8px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.88rem;">Topik Penelitian 2</label>
                        <input type="text" name="topik_2" id="ta_topik2" class="form-control" style="border-radius:8px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.88rem;">Judul 2</label>
                        <input type="text" name="judul_2" id="ta_judul2" class="form-control" style="border-radius:8px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.88rem;">Topik Penelitian 3</label>
                        <input type="text" name="topik_3" id="ta_topik3" class="form-control" style="border-radius:8px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.88rem;">Judul 3</label>
                        <input type="text" name="judul_3" id="ta_judul3" class="form-control" style="border-radius:8px;">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold" style="font-size:0.88rem;">
                            Mitra Penelitian <span class="text-muted fw-normal">(Opsional)</span>
                        </label>
                        <input type="text" name="mitra_penelitian" id="ta_mitra" class="form-control" style="border-radius:8px;">
                    </div>
                </form>
            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-2">
                <div class="d-flex gap-2 w-100">
                    <button type="button" class="btn btn-outline-secondary w-50 fw-semibold py-2"
                        style="border-radius:10px;" id="btnKembaliKeInfo">
                        Kembali
                    </button>
                    <button type="submit" form="formPengajuanTA" class="btn btn-warning w-50 fw-bold py-2"
                        style="border-radius:10px;">
                        Ajukan Judul
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {

        // ── Filter & Search ──
        const filterStatus = document.getElementById("filterStatus");
        const searchInput = document.getElementById("searchInput");
        const btnReset = document.getElementById("btnResetFilter");
        const noResult = document.getElementById("noResult");
        const rows = document.querySelectorAll("#tabelPengajuan tbody tr");

        function applyFilter() {
            const status = filterStatus.value.toLowerCase();
            const keyword = searchInput.value.toLowerCase().trim();
            let visible = 0;

            rows.forEach(row => {
                const rowStatus = (row.dataset.status || "").toLowerCase();
                const rowSearch = (row.dataset.search || "").toLowerCase();

                if (row.dataset.status) {
                    // cocokkan "menunggu" dengan "menunggu verifikasi"
                    const statusMatch = !status || rowStatus.includes(status);
                    const searchMatch = !keyword || rowSearch.includes(keyword);
                    const ok = statusMatch && searchMatch;
                    row.style.display = ok ? "" : "none";
                    if (ok) visible++;
                } else {
                    row.style.display = (!status && !keyword) ? "" : "none";
                }
            });

            noResult.classList.toggle("d-none", visible > 0 || (!status && !keyword));
        }

        filterStatus.addEventListener("change", applyFilter);
        searchInput.addEventListener("input", applyFilter);
        btnReset.addEventListener("click", function() {
            filterStatus.value = "";
            searchInput.value = "";
            applyFilter();
        });

        // ── Modal Info → Modal Form ──
        document.getElementById("btnLanjutKeForm").addEventListener("click", function() {
            const modalInfo = bootstrap.Modal.getInstance(document.getElementById("modalInfo"));
            modalInfo.hide();
            document.getElementById("modalInfo").addEventListener("hidden.bs.modal", function handler() {
                new bootstrap.Modal(document.getElementById("modalFormTA")).show();
                this.removeEventListener("hidden.bs.modal", handler);
            });
        });

        // ── Tombol Kembali di Form → balik ke Modal Info ──
        document.getElementById("btnKembaliKeInfo").addEventListener("click", function() {
            const modalForm = bootstrap.Modal.getInstance(document.getElementById("modalFormTA"));
            modalForm.hide();
            document.getElementById("modalFormTA").addEventListener("hidden.bs.modal", function handler() {
                new bootstrap.Modal(document.getElementById("modalInfo")).show();
                this.removeEventListener("hidden.bs.modal", handler);
            });
        });

        // ── Validasi sebelum submit ──
        const formTA = document.getElementById("formPengajuanTA");
        const alertBox = document.getElementById("errorAlertTA");
        const wajibTA = ["ta_nim", "ta_nama", "ta_tanggal", "ta_topik1", "ta_judul1",
            "ta_topik2", "ta_judul2", "ta_topik3", "ta_judul3"
        ];

        formTA.addEventListener("submit", function(e) {
            let isValid = true;
            wajibTA.forEach(id => document.getElementById(id).classList.remove("is-invalid"));

            wajibTA.forEach(id => {
                const el = document.getElementById(id);
                if (!el.value.trim()) {
                    el.classList.add("is-invalid");
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault(); // cegah submit kalau tidak valid
                alertBox.classList.remove("d-none");
                return;
            }

            alertBox.classList.add("d-none");
            // biarkan form submit normal ke Laravel
        });

    });
</script>

@endsection