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
        <h3 class="fw-bold">Kelola Dosen</h3>
        <p class="text-muted">Mulai Kelola data dosen</p>

        <div class="d-flex justify-content-center">
            <button
                class="btn btn-warning px-4"
                data-bs-toggle="modal"
                data-bs-target="#modalDosen">
                Tambah Dosen
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
                        <th>NIDN</th>
                        <th>Nama Dosen</th>
                        <th>Email</th>
                        <th>Sub Role</th>
                        <th>Status</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($dosen as $key => $dsn)

                    <tr>

                        <td>{{ $key + 1 }}</td>

                        <td>{{ $dsn->nidn }}</td>

                        <td>{{ $dsn->name }}</td>

                        <td>{{ $dsn->email }}</td>

                        <td>

                            @php
                                $roles = [];

                                if($dsn->is_pembimbing) $roles[] = 'Pembimbing';
                                if($dsn->is_penguji) $roles[] = 'Penguji';
                                if($dsn->is_reviewer) $roles[] = 'Reviewer';
                                if($dsn->is_koordinator) $roles[] = 'Koordinator';
                            @endphp

                            @foreach($roles as $role)
                                <span class="badge bg-primary">
                                    {{ $role }}
                                </span>
                            @endforeach

                        </td>

                        <td>

                            @if($dsn->status == 'Aktif')
                                <span class="badge bg-success">
                                    {{ $dsn->status }}
                                </span>
                            @elseif($dsn->status == 'Nonaktif')
                                <span class="badge bg-danger">
                                    {{ $dsn->status }}
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    {{ $dsn->status }}
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
                            Data dosen belum tersedia
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- MODAL TAMBAH DOSEN -->
<div class="modal fade" id="modalDosen" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    Tambah Dosen
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
                <form id="formDosen" action="{{ route('dosen.store') }}" method="POST">

                    @csrf

                    <!-- NIDN -->
                    <div class="mb-3">
                        <label class="form-label">NIDN</label>

                        <input
                            type="text"
                            name="nidn"
                            id="nidn"
                            class="form-control"
                            placeholder="Masukkan NIDN">
                    </div>

                    <!-- NAMA -->
                    <div class="mb-3">
                        <label class="form-label">Nama Dosen</label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control"
                            placeholder="Masukkan nama dosen">
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

                    <!-- SUB ROLE -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Sub Role Dosen
                        </label>

                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="is_pembimbing"
                                id="pembimbing"
                                value="1">

                            <label class="form-check-label" for="pembimbing">
                                Pembimbing
                            </label>
                        </div>

                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="is_penguji"
                                id="penguji"
                                value="1">

                            <label class="form-check-label" for="penguji">
                                Penguji
                            </label>
                        </div>

                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="is_reviewer"
                                id="reviewer"
                                value="1">

                            <label class="form-check-label" for="reviewer">
                                Reviewer
                            </label>
                        </div>

                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="is_koordinator"
                                id="koordinator"
                                value="1">

                            <label class="form-check-label" for="koordinator">
                                Koordinator
                            </label>
                        </div>

                    </div>

                    <!-- STATUS -->
                    <div class="mb-3">
                        <label class="form-label">Status</label>

                        <select
                            name="status"
                            id="status"
                            class="form-select">

                            <option value="">-- Pilih Status --</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>

                        </select>
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
                        Simpan Dosen
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


<!-- SCRIPT VALIDASI -->
<script>

document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("formDosen");

    const alertBox = document.getElementById("errorAlert");

    const nidn = document.getElementById("nidn");
    const nama = document.getElementById("name");
    const email = document.getElementById("email");
    const status = document.getElementById("status");
    const password = document.getElementById("password");

    form.addEventListener("submit", function(e){

        let isValid = true;

        const inputs = [
            nidn,
            nama,
            email,
            status,
            password
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

        // validasi minimal 1 sub role dipilih
        const roles = document.querySelectorAll(
            'input[type="checkbox"]:checked'
        );

        if(roles.length === 0){

            isValid = false;

            alertBox.innerHTML =
                "Minimal pilih 1 sub role dosen!";
        }

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