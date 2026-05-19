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

    .page-header-card::before{
        content:'';
        position:absolute;
        top:-30px;
        right:-30px;
        width:140px;
        height:140px;
        background:rgba(255,255,255,0.18);
        border-radius:50%;
    }

    .page-header-card::after{
        content:'';
        position:absolute;
        bottom:-40px;
        right:60px;
        width:110px;
        height:110px;
        background:rgba(255,255,255,0.12);
        border-radius:50%;
    }

    .page-title{
        font-size:2rem;
        font-weight:800;
        color:#735C00;
        margin-bottom:6px;
    }

    .page-subtitle{
        color:#8a6d00;
        font-size:0.95rem;
        margin-bottom:0;
    }

    .btn-add-dosen{
        background:#fff;
        color:#735C00;
        border:none;
        border-radius:14px;
        padding:12px 22px;
        font-weight:700;
        transition:0.2s;
        box-shadow:0 4px 14px rgba(0,0,0,0.08);
    }

    .btn-add-dosen:hover{
        transform:translateY(-2px);
        background:#fff8dc;
        color:#735C00;
    }

    .custom-card{
        border:none;
        border-radius:22px;
        overflow:hidden;
        background:#fff;
        box-shadow:0 6px 24px rgba(15,23,42,0.06);
    }

    .table-modern{
        margin:0;
    }

    .table-modern thead th{
        background:#FFF8DC;
        border:none;
        padding:16px;
        font-size:0.83rem;
        font-weight:800;
        color:#735C00;
        text-transform:uppercase;
        letter-spacing:0.4px;
        white-space:nowrap;
    }

    .table-modern tbody td{
        padding:18px 16px;
        border-color:#f1f5f9;
        vertical-align:middle;
        font-size:0.88rem;
        color:#334155;
    }

    .table-modern tbody tr:hover{
        background:#fffdf5;
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
        font-size:0.82rem;
    }

    .dosen-name{
        font-weight:700;
        color:#1e293b;
        margin-bottom:2px;
    }

    .dosen-email{
        font-size:0.76rem;
        color:#94a3b8;
    }

    .custom-badge{
        border-radius:999px;
        padding:6px 12px;
        font-size:0.72rem;
        font-weight:700;
        display:inline-flex;
        align-items:center;
        gap:5px;
    }

    .badge-role{
        background:#FEF3C7;
        color:#92400E;
        margin:2px;
    }

    .badge-active{
        background:#DCFCE7;
        color:#166534;
    }

    .badge-nonactive{
        background:#FEE2E2;
        color:#B91C1C;
    }

    .btn-action{
        width:36px;
        height:36px;
        border:none;
        border-radius:10px;
        display:flex;
        align-items:center;
        justify-content:center;
        transition:0.2s;
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

    .btn-action:hover{
        transform:translateY(-2px);
    }

    .modal-content{
        border:none;
        border-radius:24px;
        overflow:hidden;
        padding:0;
    }

    .modal-header{
        background:#FFF8DC;
        padding:22px 26px;
    }

    .modal-title{
        font-weight:800;
        color:#735C00;
    }

    .modal-body{
        padding:26px;
    }

    .form-label{
        font-size:0.86rem;
        font-weight:700;
        color:#475569;
        margin-bottom:8px;
    }

    .form-control,
    .form-select{
        border-radius:14px;
        border:1px solid #e2e8f0;
        padding:12px 14px;
        font-size:0.9rem;
        box-shadow:none !important;
    }

    .form-control:focus,
    .form-select:focus{
        border-color:#FACC15;
    }

    .role-box{
        background:#f8fafc;
        border-radius:16px;
        padding:14px 16px;
    }

    .form-check{
        margin-bottom:10px;
    }

    .form-check-input:checked{
        background-color:#FACC15;
        border-color:#FACC15;
    }

    .btn-submit{
        background:#FACC15;
        border:none;
        border-radius:14px;
        padding:13px;
        font-weight:800;
        color:#735C00;
        transition:0.2s;
    }

    .btn-submit:hover{
        background:#eab308;
        color:#735C00;
    }

    .empty-state{
        padding:50px 20px;
        text-align:center;
    }

    .empty-state i{
        font-size:3rem;
        color:#cbd5e1;
        margin-bottom:14px;
    }

    .empty-state h5{
        font-weight:700;
        color:#475569;
    }

    .empty-state p{
        color:#94a3b8;
        font-size:0.88rem;
    }

    @media(max-width:768px){

        .page-header-card{
            padding:24px;
        }

        .page-title{
            font-size:1.5rem;
        }

        .table-modern thead{
            display:none;
        }

        .table-modern,
        .table-modern tbody,
        .table-modern tr,
        .table-modern td{
            display:block;
            width:100%;
        }

        .table-modern tr{
            border-bottom:1px solid #e5e7eb;
            padding:14px 0;
        }

        .table-modern td{
            text-align:left !important;
            padding:8px 14px;
            border:none;
        }
    }
</style>

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="page-header-card mb-4">

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

            <div>
                <div class="page-title">
                    Kelola Dosen
                </div>

                <p class="page-subtitle">
                    Kelola data dosen, dan role dosen tugas akhir.
                </p>
            </div>

            <button
                class="btn-add-dosen"
                data-bs-toggle="modal"
                data-bs-target="#modalDosen">

                <i class="fa-solid fa-plus me-2"></i>
                Tambah Dosen

            </button>

        </div>

    </div>

    {{-- TABLE CARD --}}
    <div class="custom-card">

        <div class="table-responsive">

            <table class="table table-modern align-middle text-center">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIDN</th>
                        <th>Dosen</th>
                        <th>Sub Role</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($dosen as $key => $dsn)

                    <tr>

                        <td>
                            <div class="number-badge">
                                {{ $key + 1 }}
                            </div>
                        </td>

                        <td>
                            <strong>{{ $dsn->nim_nid }}</strong>
                        </td>

                        <td class="text-start">

                            <div class="dosen-name">
                                {{ $dsn->nama }}
                            </div>

                            <div class="dosen-email">
                                {{ $dsn->email }}
                            </div>

                        </td>

                        <td>

                            @php
                                $roles = DB::table('dosen_roles')
                                    ->where('nim_nid', $dsn->nim_nid)
                                    ->pluck('role_dosen');
                            @endphp

                            @if(count($roles) > 0)

                                @foreach($roles as $role)

                                    <span class="custom-badge badge-role">

                                        {{ ucfirst($role) }}

                                    </span>

                                @endforeach

                            @else

                                <span class="text-muted">
                                    Tidak ada role
                                </span>

                            @endif

                        </td>

                        <td>

                            <div class="d-flex justify-content-center gap-2">

                               {{-- DETAIL --}}
                                <a href="{{ route('dosen.show', $dsn->nim_nid) }}"
                                class="btn-action btn-detail">

                                    <i class="fa-solid fa-eye"></i>

                                </a>

                                {{-- EDIT --}}
                                <button
                                    class="btn-action btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $dsn->nim_nid }}">

                                    <i class="fa-solid fa-pen"></i>

                                </button>

                                {{-- DELETE --}}
                                <form action="{{ route('dosen.destroy', $dsn->nim_nid) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus dosen ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-action btn-delete">

                                        <i class="fa-solid fa-trash"></i>

                                    </button>

                                </form>
                            </div>

                        </td>

                    </tr>

                    {{-- MODAL EDIT DOSEN --}}
                    <div class="modal fade"
                        id="editModal{{ $dsn->nim_nid }}"
                        tabindex="-1">

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content">

                                <div class="modal-header border-0">

                                    <h5 class="modal-title">
                                        Edit Dosen
                                    </h5>

                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal">
                                    </button>

                                </div>

                                <div class="modal-body">

                                    <form action="{{ route('dosen.update', $dsn->nim_nid) }}"
                                        method="POST">

                                        @csrf
                                        @method('PUT')

                                        {{-- NIDN --}}
                                        <div class="mb-3">

                                            <label class="form-label">
                                                NIDN
                                            </label>

                                            <input type="text"
                                                class="form-control"
                                                value="{{ $dsn->nim_nid }}"
                                                disabled>

                                        </div>

                                        {{-- NAMA --}}
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Nama Dosen
                                            </label>

                                            <input type="text"
                                                name="nama"
                                                class="form-control"
                                                value="{{ $dsn->nama }}">

                                        </div>

                                        {{-- EMAIL --}}
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Email
                                            </label>

                                            <input type="email"
                                                name="email"
                                                class="form-control"
                                                value="{{ $dsn->email }}">

                                        </div>

                                        {{-- ROLE --}}
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Sub Role
                                            </label>

                                            <div class="role-box">

                                                @php
                                                    $roleDosen = DB::table('dosen_roles')
                                                        ->where('nim_nid', $dsn->nim_nid)
                                                        ->pluck('role_dosen')
                                                        ->toArray();
                                                @endphp

                                                <div class="form-check">

                                                    <input class="form-check-input"
                                                        type="checkbox"
                                                        name="is_pembimbing"
                                                        value="1"
                                                        {{ in_array('pembimbing', $roleDosen) ? 'checked' : '' }}>

                                                    <label class="form-check-label">
                                                        Pembimbing
                                                    </label>

                                                </div>

                                                <div class="form-check">

                                                    <input class="form-check-input"
                                                        type="checkbox"
                                                        name="is_penguji"
                                                        value="1"
                                                        {{ in_array('penguji', $roleDosen) ? 'checked' : '' }}>

                                                    <label class="form-check-label">
                                                        Penguji
                                                    </label>

                                                </div>

                                                <div class="form-check">

                                                    <input class="form-check-input"
                                                        type="checkbox"
                                                        name="is_reviewer"
                                                        value="1"
                                                        {{ in_array('reviewer', $roleDosen) ? 'checked' : '' }}>

                                                    <label class="form-check-label">
                                                        Reviewer
                                                    </label>

                                                </div>

                                                <div class="form-check">

                                                    <input class="form-check-input"
                                                        type="checkbox"
                                                        name="is_koordinator"
                                                        value="1"
                                                        {{ in_array('koordinator', $roleDosen) ? 'checked' : '' }}>

                                                    <label class="form-check-label">
                                                        Koordinator
                                                    </label>

                                                </div>

                                            </div>

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

                                            <i class="fa-solid fa-pen-to-square me-2"></i>
                                            Update Dosen

                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                    @empty

                    <tr>

                        <td colspan="6">

                            <div class="empty-state">

                                <i class="fa-solid fa-user-group"></i>

                                <h5>
                                    Data dosen belum tersedia
                                </h5>

                                <p>
                                    Tambahkan data dosen terlebih dahulu.
                                </p>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- MODAL --}}
<div class="modal fade" id="modalDosen" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header border-0">

                <h5 class="modal-title">
                    Tambah Dosen
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div id="errorAlert" class="alert alert-danger d-none">
                    Semua field wajib diisi!
                </div>

                <form id="formDosen" action="{{ route('dosen.store') }}" method="POST">

                    @csrf

                    {{-- NIDN --}}
                    <div class="mb-3">

                        <label class="form-label">
                            NIDN
                        </label>

                        <input
                            type="text"
                            name="nidn"
                            id="nidn"
                            class="form-control"
                            placeholder="Masukkan NIDN">

                    </div>

                    {{-- NAMA --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Nama Dosen
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control"
                            placeholder="Masukkan nama dosen">

                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control"
                            placeholder="Masukkan email">

                    </div>

                    {{-- SUB ROLE --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Sub Role Dosen
                        </label>

                        <div class="role-box">

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_pembimbing" id="pembimbing" value="1">
                                <label class="form-check-label" for="pembimbing">
                                    Pembimbing
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_penguji" id="penguji" value="1">
                                <label class="form-check-label" for="penguji">
                                    Penguji
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_reviewer" id="reviewer" value="1">
                                <label class="form-check-label" for="reviewer">
                                    Reviewer
                                </label>
                            </div>

                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" name="is_koordinator" id="koordinator" value="1">
                                <label class="form-check-label" for="koordinator">
                                    Koordinator
                                </label>
                            </div>

                        </div>

                    </div>

                    {{-- PASSWORD --}}
                    <div class="mb-4">

                        <label class="form-label">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Masukkan password">

                    </div>

                    <button type="submit" class="btn-submit w-100">

                        <i class="fa-solid fa-floppy-disk me-2"></i>
                        Simpan Dosen

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

{{-- VALIDASI --}}
<script>

document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("formDosen");

    const alertBox = document.getElementById("errorAlert");

    const nidn = document.getElementById("nidn");
    const nama = document.getElementById("name");
    const email = document.getElementById("email");
    const password = document.getElementById("password");

    form.addEventListener("submit", function(e){

        let isValid = true;

        const inputs = [
            nidn,
            nama,
            email,
            password
        ];

        inputs.forEach(input => {
            input.classList.remove("is-invalid");
        });

        inputs.forEach(input => {

            if(input.value.trim() === ""){

                input.classList.add("is-invalid");

                isValid = false;
            }

        });

        const roles = document.querySelectorAll(
            'input[type="checkbox"]:checked'
        );

        if(roles.length === 0){

            isValid = false;

            alertBox.innerHTML =
                "Minimal pilih 1 sub role dosen!";
        }

        if(!isValid){

            e.preventDefault();

            alertBox.classList.remove("d-none");

            return;
        }

        alertBox.classList.add("d-none");

    });

});

</script>

@endsection