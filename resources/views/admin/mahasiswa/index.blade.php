@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <br>

    <!-- NAV MINI -->
    <div class="menu-bottom-wrapper mb-4">
        <a href="#" class="menu-bottom">Beranda</a>
        <a href="#" class="menu-bottom active">Aktivitas</a>
        <a href="#" class="menu-bottom">Panduan TA</a>
    </div>

    <!-- CARD -->
    <div class="card pengajuan-card text-center mb-4 p-4 border-0 shadow-sm">
        <h3 class="fw-bold">Kelola Mahasiswa</h3>
        <p class="text-muted">Mulai Kelola data mahasiswa</p>

        <div class="d-flex justify-content-center">
            <button 
                class="btn btn-warning px-4"
                data-bs-toggle="modal"
                data-bs-target="#modalPengajuan">
                Tambah Mahasiswa
            </button>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card p-3 border-0 shadow-sm">

        <div class="table-responsive">

            <table class="table table-bordered align-middle text-center">

                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Email</th>
                        <th>Angkatan</th>
                        <th>Status</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($mahasiswa as $key => $mhs)

                    <tr>

                        <td>{{ $key + 1 }}</td>

                        <td>{{ $mhs->nim }}</td>

                        <td>{{ $mhs->nama }}</td>

                        <td>{{ $mhs->email }}</td>

                        <td>{{ $mhs->angkatan }}</td>

                        <td>

                            @if($mhs->status == 'Aktif')
                                <span class="badge bg-success">
                                    {{ $mhs->status }}
                                </span>
                            @elseif($mhs->status == 'Nonaktif')
                                <span class="badge bg-danger">
                                    {{ $mhs->status }}
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    {{ $mhs->status }}
                                </span>
                            @endif

                        </td>

                        <td>

                            <div class="d-flex justify-content-center gap-2">

                                <!-- DETAIL -->
                                <button class="btn btn-info btn-sm">
                                    Detail
                                </button>

                                <!-- EDIT -->
                                <button class="btn btn-warning btn-sm">
                                    Edit
                                </button>

                                <!-- DELETE -->
                                <button class="btn btn-danger btn-sm">
                                    Hapus
                                </button>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="7">
                            Data mahasiswa belum tersedia
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalPengajuan" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    Tambah Mahasiswa
                </h5>

                <button 
                    type="button" 
                    class="btn-close" 
                    data-bs-dismiss="modal">
                </button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <!-- ALERT -->
                <div id="errorAlert" class="alert alert-danger d-none">
                    Semua field wajib diisi!
                </div>

                <!-- FORM -->
                <form id="formMahasiswa" action="{{ route('mahasiswa.store') }}" method="POST">

                @csrf

                <!-- NIM -->
                <div class="mb-3">
                    <label class="form-label">NIM</label>

                    <input 
                        type="text"
                        name="nim"
                        id="nim"
                        class="form-control"
                        placeholder="Masukkan NIM">
                </div>

                <!-- NAMA -->
                <div class="mb-3">
                    <label class="form-label">Nama Mahasiswa</label>

                    <input 
                        type="text"
                        name="name"
                        id="name"
                        class="form-control"
                        placeholder="Masukkan nama mahasiswa">
                </div>

                <!-- EMAIL -->
                <div class="mb-3">
                    <label class="form-label">Email</label>

                    <input 
                        type="email"
                        name="email"
                        id="email"
                        class="form-control"
                        placeholder="Masukkan email">
                </div>

                <!-- ANGKATAN -->
                <div class="mb-3">
                    <label class="form-label">Angkatan</label>

                    <input 
                        type="text"
                        name="angkatan"
                        id="angkatan"
                        class="form-control"
                        placeholder="Contoh: 2022">
                </div>

                <!-- PASSWORD -->
                <div class="mb-3">
                    <label class="form-label">Password</label>

                    <input 
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="Masukkan password">
                </div>

                <button type="submit" class="btn btn-warning w-100">
                    Simpan Mahasiswa
                </button>

            </form>

            </div>

        </div>

    </div>

</div>


<!-- SCRIPT VALIDASI -->
<script>

document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("formMahasiswa");

    const alertBox = document.getElementById("errorAlert");

    const nim = document.getElementById("nim");
    const nama = document.getElementById("nama");
    const email = document.getElementById("email");
    const angkatan = document.getElementById("angkatan");
    const status = document.getElementById("status");

    form.addEventListener("submit", function(e){

        let isValid = true;

        const inputs = [
            nim,
            nama,
            email,
            angkatan,
            status
        ];

        // reset error
        inputs.forEach(input => {
            input.classList.remove("is-invalid");
        });

        // validasi
        inputs.forEach(input => {

            if(input.value.trim() === ""){

                input.classList.add("is-invalid");

                isValid = false;
            }

        });

        // kalau gagal
        if(!isValid){

            e.preventDefault();

            alertBox.classList.remove("d-none");

            return;
        }

        // kalau sukses
        alertBox.classList.add("d-none");

    });

});

</script>

@endsection