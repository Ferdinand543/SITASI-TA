@extends('layouts.app')

@section('content')

<style>
    .page-header-card{
        background: linear-gradient(135deg, #FACC15 0%, #FFE083 100%);
        border-radius: 24px;
        padding: 32px;
        position: relative;
        overflow: hidden;
        border: none;
    }

    .page-title{
        font-size:2rem;
        font-weight:800;
        color:#735C00;
    }

    .page-subtitle{
        color:#8a6d00;
        font-size:0.95rem;
    }

    .btn-add{
        background:#fff;
        color:#735C00;
        border:none;
        border-radius:14px;
        padding:12px 22px;
        font-weight:700;
    }

    .custom-card{
        border:none;
        border-radius:22px;
        overflow:hidden;
        background:#fff;
        box-shadow:0 6px 24px rgba(15,23,42,0.06);
    }

    .table-modern thead th{
        background:#FFF8DC;
        border:none;
        padding:16px;
        font-size:0.83rem;
        font-weight:800;
        color:#735C00;
    }

    .table-modern tbody td{
        padding:18px 16px;
        vertical-align:middle;
    }

    .number-badge{
        width:32px;
        height:32px;
        border-radius:10px;
        background:#FFF4C2;
        color:#735C00;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:700;
        margin:auto;
    }

    .mhs-name{
        font-weight:700;
        color:#1e293b;
    }

    .mhs-email{
        font-size:0.76rem;
        color:#94a3b8;
    }

    .badge-active{
        background:#DCFCE7;
        color:#166534;
        border-radius:999px;
        padding:6px 12px;
        font-size:0.75rem;
        font-weight:700;
    }

    .badge-nonactive{
        background:#FEE2E2;
        color:#B91C1C;
        border-radius:999px;
        padding:6px 12px;
        font-size:0.75rem;
        font-weight:700;
    }

    .btn-action{
        width:36px;
        height:36px;
        border:none;
        border-radius:10px;
        display:flex;
        align-items:center;
        justify-content:center;
    }

    .btn-detail{
        background:#DBEAFE;
        color:#1D4ED8;
    }

    .btn-edit{
        background:#FEF3C7;
        color:#B45309;
    }

    .btn-delete{
        background:#FEE2E2;
        color:#DC2626;
    }

    .modal-content{
        border:none;
        border-radius:24px;
    }

    .modal-header{
        background:#FFF8DC;
    }

    .form-control,
    .form-select{
        border-radius:14px;
        padding:12px;
    }

    .btn-submit{
        background:#FACC15;
        border:none;
        border-radius:14px;
        padding:13px;
        font-weight:700;
        color:#735C00;
    }
</style>

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="page-header-card mb-4">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <div class="page-title">
                    Kelola Mahasiswa
                </div>

                <p class="page-subtitle">
                    Kelola data mahasiswa tugas akhir.
                </p>

            </div>

            <button
                class="btn-add"
                data-bs-toggle="modal"
                data-bs-target="#modalMahasiswa">

                <i class="fa-solid fa-plus me-2"></i>
                Tambah Mahasiswa

            </button>

        </div>

    </div>

    {{-- ALERT --}}
    @if(session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger rounded-4 border-0 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger rounded-4 border-0 shadow-sm">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- TABLE --}}
    <div class="custom-card">

        <div class="table-responsive">

            <table class="table table-modern align-middle text-center">

                <thead>

                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Mahasiswa</th>
                        <th>Angkatan</th>
                        <th width="180">Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($mahasiswa as $key => $mhs)

                    <tr>

                        <td>
                            <div class="number-badge">
                                {{ $key + 1 }}
                            </div>
                        </td>

                        <td>
                            <strong>{{ $mhs->nim_nid }}</strong>
                        </td>

                        <td class="text-start">

                            <div class="mhs-name">
                                {{ $mhs->nama }}
                            </div>

                            <div class="mhs-email">
                                {{ $mhs->email }}
                            </div>

                        </td>

                        <td>
                            {{ $mhs->angkatan }}
                        </td>

                        <td>

                            <div class="d-flex justify-content-center gap-2">

                                {{-- DETAIL --}}
                                <a href="{{ route('mahasiswa.show', $mhs->nim_nid) }}"
                                   class="btn-action btn-detail">

                                    <i class="fa-solid fa-eye"></i>

                                </a>

                                {{-- EDIT --}}
                                <button
                                    class="btn-action btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $mhs->nim_nid }}">

                                    <i class="fa-solid fa-pen"></i>

                                </button>

                                {{-- DELETE --}}
                                <button
                                    type="button"
                                    class="btn-action btn-delete"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $mhs->nim_nid }}">

                                    <i class="fa-solid fa-trash"></i>

                                </button>

                                {{-- MODAL DELETE --}}
                                <div class="modal fade"
                                    id="deleteModal{{ $mhs->nim_nid }}"
                                    tabindex="-1"
                                    aria-hidden="true">

                                    <div class="modal-dialog modal-dialog-centered">

                                        <div class="modal-content border-0 rounded-4">

                                            <div class="modal-header border-0 pb-0">

                                                <h5 class="modal-title fw-bold text-danger">
                                                    Hapus Mahasiswa
                                                </h5>

                                                <button type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal">
                                                </button>

                                            </div>

                                            <div class="modal-body text-center py-4">

                                                <div class="mb-3">

                                                    <i class="fa-solid fa-trash-can"
                                                    style="font-size:60px;color:#ef4444;">
                                                    </i>

                                                </div>

                                                <h5 class="fw-bold mb-2">
                                                    Yakin ingin menghapus?
                                                </h5>

                                                <p class="text-muted mb-0">
                                                    Data mahasiswa
                                                    <strong>{{ $mhs->nama }}</strong>
                                                    akan dihapus permanen.
                                                </p>

                                            </div>

                                            <div class="modal-footer border-0 pt-0">

                                                <button type="button"
                                                        class="btn btn-light rounded-3 px-4"
                                                        data-bs-dismiss="modal">

                                                    Batal

                                                </button>

                                                <form action="{{ route('mahasiswa.destroy', $mhs->nim_nid) }}"
                                                    method="POST">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-danger rounded-3 px-4">

                                                        <i class="fa-solid fa-trash me-2"></i>
                                                        Hapus

                                                    </button>

                                                </form>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </td>

                    </tr>

                    {{-- MODAL EDIT --}}
                    <div class="modal fade"
                         id="editModal{{ $mhs->nim_nid }}"
                         tabindex="-1">

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content">

                                <div class="modal-header border-0">

                                    <h5 class="modal-title">
                                        Edit Mahasiswa
                                    </h5>

                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal">
                                    </button>

                                </div>

                                <div class="modal-body">

                                    <form action="{{ route('mahasiswa.update', $mhs->nim_nid) }}"
                                          method="POST">

                                        @csrf
                                        @method('PUT')

                                        {{-- NIM --}}
                                        <div class="mb-3">

                                            <label class="form-label">
                                                NIM
                                            </label>

                                            <input type="text"
                                                   class="form-control"
                                                   value="{{ $mhs->nim_nid }}"
                                                   disabled>

                                        </div>

                                        {{-- NAMA --}}
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Nama Mahasiswa
                                            </label>

                                            <input type="text"
                                                   name="nama"
                                                   class="form-control"
                                                   value="{{ $mhs->nama }}">

                                        </div>

                                        {{-- EMAIL --}}
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Email
                                            </label>

                                            <input type="email"
                                                   name="email"
                                                   class="form-control"
                                                   value="{{ $mhs->email }}">

                                        </div>

                                        {{-- ANGKATAN --}}
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Angkatan
                                            </label>

                                            <input type="text"
                                                   name="angkatan"
                                                   class="form-control"
                                                   value="{{ $mhs->angkatan }}">

                                        </div>

                                        {{-- PASSWORD --}}
                                        <div class="mb-4">

                                            <label class="form-label">
                                                Password Baru
                                            </label>

                                            <input type="password"
                                                   name="password"
                                                   class="form-control"
                                                   placeholder="Kosongkan jika tidak diganti">

                                        </div>

                                        <button type="submit"
                                                class="btn-submit w-100">

                                            Update Mahasiswa

                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                    @empty

                    <tr>

                        <td colspan="6">
                            Data mahasiswa belum tersedia
                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalMahasiswa" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header border-0">

                <h5 class="modal-title">
                    Tambah Mahasiswa
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form action="{{ route('mahasiswa.store') }}"
                      method="POST">

                    @csrf

                    {{-- NIM --}}
                    <div class="mb-3">

                        <label class="form-label">
                            NIM
                        </label>

                        <input type="text"
                               name="nim_nid"
                               class="form-control">

                    </div>

                    {{-- NAMA --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Nama Mahasiswa
                        </label>

                        <input type="text"
                               name="nama"
                               class="form-control">

                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control">

                    </div>

                    {{-- ANGKATAN --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Angkatan
                        </label>

                        <input type="text"
                               name="angkatan"
                               class="form-control">

                    </div>

                    {{-- PASSWORD --}}
                    <div class="mb-4">

                        <label class="form-label">
                            Password
                        </label>

                        <input type="password"
                               name="password"
                               class="form-control">

                    </div>

                    <button type="submit"
                            class="btn-submit w-100">

                        Simpan Mahasiswa

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection