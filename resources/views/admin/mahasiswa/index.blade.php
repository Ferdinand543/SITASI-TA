@extends('layouts.app')

@section('content')

<style>
    .page-header-card {
        background-color: #FACC15;
        background-image: url('{{ asset("images/psi.jpeg") }}');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: right center;
        border-radius: 24px;
        padding: 40px 48px;
        position: relative;
        overflow: hidden;
        border: none;
        min-height: 180px;
        display: flex;
        align-items: center;
    }

    .page-header-card::before {
        content: '';
        position: absolute;
        top: -20px; left: -20px;
        width: 160px; height: 160px;
        background-image: radial-gradient(#d2a800 1.5px, transparent 1.5px);
        background-size: 18px 18px;
        opacity: 0.25;
        z-index: 1;
    }

    .header-left {
        position: relative;
        z-index: 2;
        flex: 1;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 800;
        color: #735C00;
        margin-bottom: 6px;
    }

    .page-subtitle {
        color: #8a6d00;
        font-size: 0.95rem;
        margin-bottom: 20px;
    }

    .btn-add {
        background: #FFE083;
        color: #735C00;
        border: none;
        border-radius: 14px;
        padding: 10px 20px;
        font-weight: 700;
        font-size: 0.88rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: .2s;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .btn-add:hover {
        background: #f5f5f5;
        transform: translateY(-1px);
    }

    @media (max-width: 576px) {
        .page-header-card { padding: 28px 24px; }
    }

    .custom-card {
        border: none;
        border-radius: 22px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 6px 24px rgba(15,23,42,0.06);
    }

    .table-modern thead th {
        background: #FFF8DC;
        border: none;
        padding: 16px;
        font-size: 0.83rem;
        font-weight: 800;
        color: #735C00;
    }

    .table-modern tbody td {
        padding: 18px 16px;
        vertical-align: middle;
    }

    .number-badge {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: #FFF4C2;
        color: #735C00;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin: auto;
    }

    .mhs-name { font-weight: 700; color: #1e293b; }
    .mhs-email { font-size: 0.76rem; color: #94a3b8; }

    .btn-action {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: .2s;
    }

    .btn-action:hover { opacity: .8; transform: translateY(-1px); }
    .btn-detail { background: #DBEAFE; color: #1D4ED8; }
    .btn-edit   { background: #FEF3C7; color: #B45309; }
    .btn-delete { background: #FEE2E2; color: #DC2626; }

    .modal-content { border: none; border-radius: 24px; }
    .modal-header  { background: #FFF8DC; }

    .form-control,
    .form-select { border-radius: 14px; padding: 12px; }

    .btn-submit {
        background: #FACC15;
        border: none;
        border-radius: 14px;
        padding: 13px;
        font-weight: 700;
        color: #735C00;
        width: 100%;
    }

    /* ── TOGGLE PASSWORD ── */
    .pw-wrap {
        position: relative;
    }

    .pw-wrap .form-control {
        padding-right: 46px;
    }

    .pw-toggle {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #94a3b8;
        padding: 0;
        font-size: 16px;
        line-height: 1;
    }

    .pw-toggle:hover {
        color: #735C00;
    }
</style>

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="page-header-card mb-4">
        <div class="header-left">
            <div class="page-title">Kelola Mahasiswa</div>
            <p class="page-subtitle">Kelola data mahasiswa tugas akhir.</p>
            <button
                class="btn-add"
                data-bs-toggle="modal"
                data-bs-target="#modalMahasiswa">
                <i class="fa-solid fa-plus"></i>
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
                            <div class="number-badge">{{ $key + 1 }}</div>
                        </td>
                        <td>
                            <strong>{{ $mhs->nim_nid }}</strong>
                        </td>
                        <td class="text-start">
                            <div class="mhs-name">{{ $mhs->nama }}</div>
                            <div class="mhs-email">{{ $mhs->email }}</div>
                        </td>
                        <td>{{ $mhs->angkatan }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">

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
                                                <h5 class="modal-title fw-bold text-danger">Hapus Mahasiswa</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center py-4">
                                                <div class="mb-3">
                                                    <i class="fa-solid fa-trash-can" style="font-size:60px;color:#ef4444;"></i>
                                                </div>
                                                <h5 class="fw-bold mb-2">Yakin ingin menghapus?</h5>
                                                <p class="text-muted mb-0">
                                                    Data mahasiswa <strong>{{ $mhs->nama }}</strong> akan dihapus permanen.
                                                </p>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('mahasiswa.destroy', $mhs->nim_nid) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger rounded-3 px-4">
                                                        <i class="fa-solid fa-trash me-2"></i>Hapus
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
                    <div class="modal fade" id="editModal{{ $mhs->nim_nid }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">Edit Mahasiswa</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form
                                        action="{{ route('mahasiswa.update', $mhs->nim_nid) }}"
                                        method="POST"
                                        data-current-nim="{{ $mhs->nim_nid }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label class="form-label">NIM</label>
                                            <input type="text" class="form-control" value="{{ $mhs->nim_nid }}" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama Mahasiswa</label>
                                            <input type="text" name="nama" class="form-control" value="{{ $mhs->nama }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ $mhs->email }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Angkatan</label>
                                            <input type="text" name="angkatan" class="form-control" value="{{ $mhs->angkatan }}">
                                        </div>
                                        
                                        <button type="submit" class="btn-submit">Update Mahasiswa</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="6">Data mahasiswa belum tersedia</td>
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
                <h5 class="modal-title">Tambah Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('mahasiswa.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" name="nim_nid" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Mahasiswa</label>
                        <input type="text" name="nama" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Angkatan</label>
                        <input type="text" name="angkatan" class="form-control">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="pw-wrap">
                            <input type="password" name="password" class="form-control">
                            <button type="button" class="pw-toggle" onclick="togglePw(this)">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit">Simpan Mahasiswa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// ── TOGGLE SHOW/HIDE PASSWORD ──
function togglePw(btn) {
    const input = btn.closest('.pw-wrap').querySelector('input');
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

document.addEventListener('DOMContentLoaded', function () {

    const existingNim   = @json($mahasiswa->pluck('nim_nid'));
    const existingEmail = @json($mahasiswa->pluck('email'));
    const emailByNim    = @json($mahasiswa->pluck('email', 'nim_nid'));

    // ══════════════════════════════
    // VALIDASI FORM TAMBAH
    // ══════════════════════════════
    const formTambah = document.querySelector('#modalMahasiswa form');
    formTambah.addEventListener('submit', function (e) {
        const nim      = formTambah.querySelector('[name="nim_nid"]').value.trim();
        const email    = formTambah.querySelector('[name="email"]').value.trim();
        const password = formTambah.querySelector('[name="password"]').value;

        if (existingNim.includes(nim)) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'NIM Sudah Terdaftar!',
                text: 'NIM ' + nim + ' sudah digunakan oleh mahasiswa lain.',
                confirmButtonColor: '#FACC15',
                confirmButtonText: 'OK',
            });
            return;
        }

        if (existingEmail.includes(email)) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Email Sudah Terdaftar!',
                text: 'Email ' + email + ' sudah digunakan oleh mahasiswa lain.',
                confirmButtonColor: '#FACC15',
                confirmButtonText: 'OK',
            });
            return;
        }

        if (password.length < 6) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Password Terlalu Pendek!',
                text: 'Password minimal 6 karakter.',
                confirmButtonColor: '#FACC15',
                confirmButtonText: 'OK',
            });
            return;
        }

        if (password.length > 10) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Password Terlalu Panjang!',
                text: 'Password maksimal 10 karakter.',
                confirmButtonColor: '#FACC15',
                confirmButtonText: 'OK',
            });
            return;
        }
    });

    // ══════════════════════════════
    // VALIDASI FORM EDIT
    // ══════════════════════════════
    document.querySelectorAll('[id^="editModal"] form').forEach(function (formEdit) {
        formEdit.addEventListener('submit', function (e) {
            const currentNim = formEdit.dataset.currentNim;
            const email      = formEdit.querySelector('[name="email"]').value.trim();
            const password   = formEdit.querySelector('[name="password"]').value;

            for (const [nim, em] of Object.entries(emailByNim)) {
                if (em === email && nim !== currentNim) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Email Sudah Digunakan!',
                        text: 'Email ' + email + ' sudah digunakan oleh mahasiswa lain.',
                        confirmButtonColor: '#FACC15',
                        confirmButtonText: 'OK',
                    });
                    return;
                }
            }

            if (password.length > 0 && password.length < 6) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Password Terlalu Pendek!',
                    text: 'Password minimal 6 karakter.',
                    confirmButtonColor: '#FACC15',
                    confirmButtonText: 'OK',
                });
                return;
            }

            if (password.length > 10) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Password Terlalu Panjang!',
                    text: 'Password maksimal 10 karakter.',
                    confirmButtonColor: '#FACC15',
                    confirmButtonText: 'OK',
                });
                return;
            }
        });
    });

});
</script>

@endsection