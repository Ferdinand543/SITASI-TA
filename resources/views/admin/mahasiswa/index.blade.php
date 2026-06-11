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
    .header-left { position: relative; z-index: 2; flex: 1; }
    .page-title { font-size: 2rem; font-weight: 800; color: #735C00; margin-bottom: 6px; }
    .page-subtitle { color: #8a6d00; font-size: 0.95rem; margin-bottom: 20px; }
    .btn-add {
        background: #FFE083; color: #735C00; border: none; border-radius: 14px;
        padding: 10px 20px; font-weight: 700; font-size: 0.88rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: .2s; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px; white-space: nowrap; text-decoration: none;
    }
    .btn-add:hover { background: #f5f5f5; transform: translateY(-1px); }
    .btn-import {
        background: #fff; color: #735C00; border: 2px solid #FFE083; border-radius: 14px;
        padding: 10px 20px; font-weight: 700; font-size: 0.88rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: .2s; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px; white-space: nowrap; text-decoration: none;
    }
    .btn-import:hover { background: #FFF8DC; transform: translateY(-1px); }
    @media (max-width: 576px) { .page-header-card { padding: 28px 24px; } }
    .custom-card { border: none; border-radius: 22px; overflow: hidden; background: #fff; box-shadow: 0 6px 24px rgba(15,23,42,0.06); }
    .table-modern thead th { background: #FFF8DC; border: none; padding: 16px; font-size: 0.83rem; font-weight: 800; color: #735C00; }
    .table-modern tbody td { padding: 18px 16px; vertical-align: middle; }
    .number-badge { width: 32px; height: 32px; border-radius: 10px; background: #FFF4C2; color: #735C00; display: flex; align-items: center; justify-content: center; font-weight: 700; margin: auto; }
    .mhs-name  { font-weight: 700; color: #1e293b; }
    .mhs-email { font-size: 0.76rem; color: #94a3b8; }
    .btn-action { width: 36px; height: 36px; border: none; border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: .2s; }
    .btn-action:hover { opacity: .8; transform: translateY(-1px); }
    .btn-edit   { background: #FEF3C7; color: #B45309; }
    .btn-delete { background: #FEE2E2; color: #DC2626; }
    .modal-content { border: none; border-radius: 24px; }
    .modal-header  { background: #FFF8DC; }
    .form-control, .form-select { border-radius: 14px; padding: 12px; }
    .btn-submit { background: #FACC15; border: none; border-radius: 14px; padding: 13px; font-weight: 700; color: #735C00; width: 100%; }
    .pw-wrap { position: relative; }
    .pw-wrap .form-control { padding-right: 46px; }
    .pw-toggle { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; padding: 0; font-size: 16px; line-height: 1; }
    .pw-toggle:hover { color: #735C00; }

    /* ── SEARCH BAR ── */
    .search-bar-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 220px;
    }
    .search-input-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.85rem;
        pointer-events: none;
    }
    .search-input {
        width: 100%;
        height: 42px;
        border: 1.5px solid #E5E7EB;
        border-radius: 12px;
        padding: 0 14px 0 38px;
        font-size: 0.85rem;
        font-family: inherit;
        color: #1E293B;
        background: #fff;
        outline: none;
        transition: border-color .2s;
    }
    .search-input:focus { border-color: #FACC15; }
    .btn-reset {
        height: 42px;
        padding: 0 18px;
        border-radius: 12px;
        border: 1.5px solid #E5E7EB;
        background: #fff;
        color: #6B7280;
        font-size: 0.83rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: .2s;
        white-space: nowrap;
        font-family: inherit;
    }
    .btn-reset:hover { background: #FEE2E2; border-color: #FECACA; color: #DC2626; }
    .search-info {
        font-size: 0.82rem;
        color: #6B7280;
        padding: 0 4px;
    }
    .highlight { background: #FFF3A3; border-radius: 3px; padding: 0 2px; }

    /* ── IMPORT MODAL ── */
    .import-drop-area { border: 2.5px dashed #FACC15; border-radius: 18px; padding: 36px 20px; text-align: center; background: #FFFBEB; cursor: pointer; transition: .2s; position: relative; }
    .import-drop-area:hover, .import-drop-area.drag-over { background: #FFF8DC; border-color: #C9A227; }
    .import-drop-area input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .import-icon { width: 56px; height: 56px; background: #FFE083; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 22px; color: #735C00; }
    .import-file-name { font-size: 0.85rem; font-weight: 600; color: #735C00; margin-top: 8px; display: none; }
    .import-tip { background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 12px; padding: 12px 16px; font-size: 0.82rem; color: #15803D; margin-top: 14px; }
    .import-tip ul { margin: 0; padding-left: 18px; }
    .btn-download-template { display: inline-flex; align-items: center; gap: 6px; background: #F0FDF4; color: #15803D; border: 1.5px solid #BBF7D0; border-radius: 10px; padding: 8px 16px; font-size: 0.83rem; font-weight: 700; text-decoration: none; transition: .2s; margin-bottom: 16px; }
    .btn-download-template:hover { background: #DCFCE7; color: #15803D; }
</style>

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="page-header-card mb-4">
        <div class="header-left">
            <div class="page-title">Kelola Mahasiswa</div>
            <p class="page-subtitle">Kelola data mahasiswa tugas akhir.</p>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn-add" data-bs-toggle="modal" data-bs-target="#modalMahasiswa">
                    <i class="fa-solid fa-plus"></i> Tambah Mahasiswa
                </button>
                <button class="btn-import" data-bs-toggle="modal" data-bs-target="#modalImport">
                    <i class="fa-solid fa-file-excel"></i> Import Excel
                </button>
            </div>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
    </div>
    @endif
    @if(session('warning'))
    <div class="alert alert-warning rounded-4 border-0 shadow-sm">
        <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('warning') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger rounded-4 border-0 shadow-sm">
        <i class="fa-solid fa-circle-xmark me-2"></i>{{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger rounded-4 border-0 shadow-sm">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- SEARCH BAR --}}
    <div class="search-bar-wrap">
        <div class="search-input-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" class="search-input" id="searchInput"
                placeholder="Cari NIM, nama, atau email..."
                oninput="filterMahasiswa()">
        </div>
        <button class="btn-reset" id="btnReset" onclick="resetSearch()" style="display:none;">
            <i class="fa-solid fa-xmark"></i> Reset
        </button>
        <span class="search-info" id="searchInfo"></span>
    </div>

    {{-- TABLE --}}
    <div class="custom-card">
        <div class="table-responsive">
            <table class="table table-modern align-middle text-center" id="tableMhs">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Mahasiswa</th>
                        <th>Angkatan</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($mahasiswa as $key => $mhs)
                    <tr class="mhs-row"
                        data-nim="{{ strtolower($mhs->nim_nid) }}"
                        data-nama="{{ strtolower($mhs->nama) }}"
                        data-email="{{ strtolower($mhs->email) }}">
                        <td><div class="number-badge row-num">{{ $key + 1 }}</div></td>
                        <td><strong class="mhs-nim">{{ $mhs->nim_nid }}</strong></td>
                        <td class="text-start">
                            <div class="mhs-name mhs-nama-text">{{ $mhs->nama }}</div>
                            <div class="mhs-email mhs-email-text">{{ $mhs->email }}</div>
                        </td>
                        <td>{{ $mhs->angkatan }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn-action btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $mhs->nim_nid }}">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button type="button" class="btn-action btn-delete"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $mhs->nim_nid }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>

                                {{-- MODAL DELETE --}}
                                <div class="modal fade" id="deleteModal{{ $mhs->nim_nid }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 rounded-4">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold text-danger">Hapus Mahasiswa</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center py-4">
                                                <div class="mb-3"><i class="fa-solid fa-trash-can" style="font-size:60px;color:#ef4444;"></i></div>
                                                <h5 class="fw-bold mb-2">Yakin ingin menghapus?</h5>
                                                <p class="text-muted mb-0">Data mahasiswa <strong>{{ $mhs->nama }}</strong> akan dihapus permanen.</p>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('mahasiswa.destroy', $mhs->nim_nid) }}" method="POST">
                                                    @csrf @method('DELETE')
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
                                    <form action="{{ route('mahasiswa.update', $mhs->nim_nid) }}" method="POST" data-current-nim="{{ $mhs->nim_nid }}">
                                        @csrf @method('PUT')
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
                    <tr id="emptyRow">
                        <td colspan="5" class="py-5 text-muted">Data mahasiswa belum tersedia</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Empty state saat search tidak ketemu --}}
            <div id="noResult" style="display:none;text-align:center;padding:48px 24px;">
                <div style="font-size:2.5rem;margin-bottom:12px;">🔍</div>
                <div style="font-weight:700;color:#1E293B;font-size:1rem;margin-bottom:6px;">Tidak ditemukan</div>
                <div style="font-size:0.85rem;color:#94a3b8;">Tidak ada mahasiswa yang cocok dengan pencarian "<span id="noResultKeyword"></span>"</div>
                <button class="btn-reset mt-3" style="display:inline-flex;margin:12px auto 0;" onclick="resetSearch()">
                    <i class="fa-solid fa-xmark"></i> Reset Pencarian
                </button>
            </div>
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

{{-- MODAL IMPORT --}}
<div class="modal fade" id="modalImport" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-file-excel me-2" style="color:#16A34A;"></i>
                    Import Data Mahasiswa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <a href="{{ route('mahasiswa.import.template') }}" class="btn-download-template">
                    <i class="fa-solid fa-download"></i> Download Template Excel
                </a>
                <form action="{{ route('mahasiswa.import') }}" method="POST" enctype="multipart/form-data" id="formImport">
                    @csrf
                    <div class="import-drop-area" id="dropArea">
                        <input type="file" name="file_excel" id="fileExcel" accept=".xlsx,.xls">
                        <div class="import-icon"><i class="fa-solid fa-file-arrow-up"></i></div>
                        <div style="font-weight:700;color:#735C00;font-size:0.95rem;">Klik atau drag file Excel di sini</div>
                        <div style="font-size:0.8rem;color:#92a0b0;margin-top:4px;">Format: .xlsx atau .xls — Maks. 10MB</div>
                        <div class="import-file-name" id="fileName">
                            <i class="fa-solid fa-file-excel me-1" style="color:#16A34A;"></i>
                            <span id="fileNameText"></span>
                        </div>
                    </div>
                    <div class="import-tip mt-3">
                        <strong>Panduan:</strong>
                        <ul class="mt-1">
                            <li>Download template, isi data, lalu upload kembali</li>
                            <li>Kolom wajib: NIM, Nama, Email, Angkatan, Password</li>
                            <li>NIM dan Email harus unik</li>
                            <li>Maksimal 2000 baris per file</li>
                        </ul>
                    </div>
                    <button type="submit" class="btn-submit mt-3" id="btnImport" disabled>
                        <i class="fa-solid fa-upload me-2"></i> Import Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// ── TOGGLE PASSWORD ──
function togglePw(btn) {
    const input = btn.closest('.pw-wrap').querySelector('input');
    const icon  = btn.querySelector('i');
    if (input.type === 'password') { input.type = 'text'; icon.classList.replace('fa-eye', 'fa-eye-slash'); }
    else { input.type = 'password'; icon.classList.replace('fa-eye-slash', 'fa-eye'); }
}

// ── SEARCH & RESET ──
function filterMahasiswa() {
    const q        = document.getElementById('searchInput').value.trim().toLowerCase();
    const rows     = document.querySelectorAll('.mhs-row');
    const btnReset = document.getElementById('btnReset');
    const info     = document.getElementById('searchInfo');
    const noResult = document.getElementById('noResult');
    const keyword  = document.getElementById('noResultKeyword');

    btnReset.style.display = q ? 'inline-flex' : 'none';

    let visible = 0;
    let num = 1;

    rows.forEach(row => {
        const nim   = row.dataset.nim   || '';
        const nama  = row.dataset.nama  || '';
        const email = row.dataset.email || '';
        const match = nim.includes(q) || nama.includes(q) || email.includes(q);

        if (match || !q) {
            row.style.display = '';
            // Update nomor urut
            row.querySelector('.row-num').textContent = num++;
            // Highlight
            if (q) {
                highlightText(row.querySelector('.mhs-nim'),        row.dataset.nim.toUpperCase().includes(q.toUpperCase()) ? q : '', row.querySelector('.mhs-nim').textContent);
                highlightText(row.querySelector('.mhs-nama-text'),  nama.includes(q) ? q : '', row.querySelector('.mhs-nama-text').textContent);
                highlightText(row.querySelector('.mhs-email-text'), email.includes(q) ? q : '', row.querySelector('.mhs-email-text').textContent);
            } else {
                clearHighlight(row);
            }
            visible++;
        } else {
            row.style.display = 'none';
        }
    });

    if (q) {
        info.textContent = visible + ' hasil ditemukan';
        noResult.style.display = visible === 0 ? 'block' : 'none';
        keyword.textContent = q;
        document.getElementById('tableMhs').style.display = visible === 0 ? 'none' : '';
    } else {
        info.textContent = '';
        noResult.style.display = 'none';
        document.getElementById('tableMhs').style.display = '';
    }
}

function highlightText(el, q, original) {
    if (!el || !q) return;
    const regex = new RegExp('(' + q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
    el.innerHTML = original.replace(regex, '<mark class="highlight">$1</mark>');
}

function clearHighlight(row) {
    row.querySelectorAll('.mhs-nim, .mhs-nama-text, .mhs-email-text').forEach(el => {
        el.innerHTML = el.textContent;
    });
}

function resetSearch() {
    document.getElementById('searchInput').value = '';
    filterMahasiswa();
}

// ── FILE INPUT IMPORT ──
document.getElementById('fileExcel').addEventListener('change', function () {
    const file = this.files[0];
    const nameEl = document.getElementById('fileName');
    const nameText = document.getElementById('fileNameText');
    const btnImport = document.getElementById('btnImport');
    if (file) { nameText.textContent = file.name; nameEl.style.display = 'block'; btnImport.disabled = false; }
    else { nameEl.style.display = 'none'; btnImport.disabled = true; }
});

// ── DRAG & DROP ──
const dropArea = document.getElementById('dropArea');
dropArea.addEventListener('dragover',  e => { e.preventDefault(); dropArea.classList.add('drag-over'); });
dropArea.addEventListener('dragleave', () => dropArea.classList.remove('drag-over'));
dropArea.addEventListener('drop', e => {
    e.preventDefault();
    dropArea.classList.remove('drag-over');
    const input = document.getElementById('fileExcel');
    if (e.dataTransfer.files.length) { input.files = e.dataTransfer.files; input.dispatchEvent(new Event('change')); }
});

// ── LOADING IMPORT ──
document.getElementById('formImport').addEventListener('submit', function () {
    const btn = document.getElementById('btnImport');
    btn.disabled  = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Memproses...';
});

document.addEventListener('DOMContentLoaded', function () {
    const existingNim   = @json($mahasiswa->pluck('nim_nid'));
    const existingEmail = @json($mahasiswa->pluck('email'));
    const emailByNim    = @json($mahasiswa->pluck('email', 'nim_nid'));

    // VALIDASI TAMBAH
    const formTambah = document.querySelector('#modalMahasiswa form');
    formTambah.addEventListener('submit', function (e) {
        const nim      = formTambah.querySelector('[name="nim_nid"]').value.trim();
        const email    = formTambah.querySelector('[name="email"]').value.trim();
        const password = formTambah.querySelector('[name="password"]').value;
        if (existingNim.includes(nim)) { e.preventDefault(); Swal.fire({ icon:'error', title:'NIM Sudah Terdaftar!', text:'NIM ' + nim + ' sudah digunakan.', confirmButtonColor:'#FACC15', confirmButtonText:'OK' }); return; }
        if (existingEmail.includes(email)) { e.preventDefault(); Swal.fire({ icon:'error', title:'Email Sudah Terdaftar!', text:'Email ' + email + ' sudah digunakan.', confirmButtonColor:'#FACC15', confirmButtonText:'OK' }); return; }
        if (password.length < 6) { e.preventDefault(); Swal.fire({ icon:'warning', title:'Password Terlalu Pendek!', text:'Password minimal 6 karakter.', confirmButtonColor:'#FACC15', confirmButtonText:'OK' }); return; }
        if (password.length > 10) { e.preventDefault(); Swal.fire({ icon:'warning', title:'Password Terlalu Panjang!', text:'Password maksimal 10 karakter.', confirmButtonColor:'#FACC15', confirmButtonText:'OK' }); return; }
    });

    // VALIDASI EDIT
    document.querySelectorAll('[id^="editModal"] form').forEach(function (formEdit) {
        formEdit.addEventListener('submit', function (e) {
            const currentNim = formEdit.dataset.currentNim;
            const email      = formEdit.querySelector('[name="email"]').value.trim();
            const password   = formEdit.querySelector('[name="password"]') ? formEdit.querySelector('[name="password"]').value : '';
            for (const [nim, em] of Object.entries(emailByNim)) {
                if (em === email && nim !== currentNim) { e.preventDefault(); Swal.fire({ icon:'error', title:'Email Sudah Digunakan!', text:'Email ' + email + ' sudah digunakan mahasiswa lain.', confirmButtonColor:'#FACC15', confirmButtonText:'OK' }); return; }
            }
            if (password.length > 0 && password.length < 6) { e.preventDefault(); Swal.fire({ icon:'warning', title:'Password Terlalu Pendek!', text:'Password minimal 6 karakter.', confirmButtonColor:'#FACC15', confirmButtonText:'OK' }); return; }
            if (password.length > 10) { e.preventDefault(); Swal.fire({ icon:'warning', title:'Password Terlalu Panjang!', text:'Password maksimal 10 karakter.', confirmButtonColor:'#FACC15', confirmButtonText:'OK' }); return; }
        });
    });
});
</script>

@endsection