@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <br>

    <!-- NAV MINI -->
    <div class="menu-bottom-wrapper mb-4">
        <a href="#" class="menu-bottom">Beranda</a>
        <a href="#" class="menu-bottom active">Aktivitas</a>
        <a href="#" class="menu-bottom">Dosen Pembimbing</a>
        <a href="#" class="menu-bottom">Panduan TA</a>
    </div>

    <!-- CARD AJUKAN -->
    <div class="card pengajuan-card text-center mb-4">
        <h3 class="fw-bold">Ajukan Judul Tugas Akhir Anda</h3>
        <p class="text-muted">Mulai pengajuan judul untuk progres Tugas Akhir</p>

        <div class="d-flex justify-content-center">
            <button class="btn btn-warning px-4" data-bs-toggle="modal" data-bs-target="#modalPengajuan">
                Ajukan judul
            </button>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card p-3 border-0">
        <table class="table table-bordered align-middle text-center">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Topik</th>
                    <th>Judul TA</th>
                    <th>Mitra Penelitian</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td><span class="badge bg-success">disetujui</span></td>
                </tr>

                <tr>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td><span class="badge bg-danger">ditolak</span></td>
                </tr>

                <tr>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td><span class="badge bg-warning text-dark">Menunggu</span></td>
                </tr>

            </tbody>
        </table>
    </div>

</div>


<!-- MODAL -->
<div class="modal fade" id="modalPengajuan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- CLOSE -->
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- TITLE -->
            <div class="text-center px-4 mb-3">
                <h3 class="fw-bold">Form Pengajuan Judul TA</h3>
                <p class="text-muted small">
                    Silakan ajukan judul tugas akhir Anda melalui form di bawah ini
                </p>
            </div>

            <!-- FORM -->
            <div class="modal-body pt-0">
                <div id="errorAlert" class="alert alert-danger d-none">
                    Semua field wajib diisi, Mitra Penelitian Opsional!
                </div>
                <form id="formPengajuan">

                    <div class="mb-3">
                        <label>Nama & NIM</label>
                        <input type="text" id="nama" class="form-control" placeholder="Masukan nama dan nim">
                    </div>

                    <div class="mb-3">
                        <label>Topik</label>
                        <input type="text" id="topik" class="form-control" placeholder="Topik penelitian">
                    </div>

                    <div class="mb-3">
                        <label>Judul TA</label>
                        <textarea id="judul" class="form-control" placeholder="Masukkan judul yang akan diajukan"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Mitra Penelitian</label>
                        <input type="text" id="mitra" class="form-control" placeholder="Isi jika penelitian bekerja sama dengan instansi tertentu">
                    </div>

                    <button type="submit" class="btn btn-warning w-100">
                        Ajukan Judul
                    </button>

                </form>

            </div>

        </div>
    </div>
</div>


<!-- SCRIPT -->
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const form = document.getElementById("formPengajuan");
        const alertBox = document.getElementById("errorAlert");

        const nama = document.getElementById("nama");
        const topik = document.getElementById("topik");
        const judul = document.getElementById("judul");

        form.addEventListener("submit", function(e) {

            let isValid = true;

            // reset style dulu
            [nama, topik, judul].forEach(input => {
                input.classList.remove("is-invalid");
            });

            if (nama.value.trim() === "") {
                nama.classList.add("is-invalid");
                isValid = false;
            }

            if (topik.value.trim() === "") {
                topik.classList.add("is-invalid");
                isValid = false;
            }

            if (judul.value.trim() === "") {
                judul.classList.add("is-invalid");
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                alertBox.classList.remove("d-none");
                return;
            }

            // kalau valid
            e.preventDefault();
            alertBox.classList.add("d-none");

            // reset form
            form.reset();

            // hapus error merah
            [nama, topik, judul].forEach(input => {
                input.classList.remove("is-invalid");
            });

            // tutup modal
            let modal = bootstrap.Modal.getInstance(document.getElementById('modalPengajuan'));
            modal.hide();
        });

    });
</script>

@endsection