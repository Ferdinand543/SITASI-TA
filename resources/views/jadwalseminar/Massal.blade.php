@extends('layouts.app')

@section('title', 'Tetapkan Jadwal Seminar Massal')

@section('content')

<style>
    :root {
        --gold: #C9A227;
        --gold-lt: #FEF9EC;
        --gold-border: #F5D97A;
        --neutral: #1E293B;
        --muted: #6B7280;
        --border: #E5E7EB;
        --white: #ffffff;
        --bg: #F5F6FA;
        --radius: 16px;
        --danger: #EF4444;
        --danger-lt: #FEF2F2;
        --danger-border: #FECACA;
        --green: #15803D;
        --green-lt: #F0FDF4;
        --green-border: #BBF7D0;
    }

    .wrap { background: var(--bg); min-height: 100vh; }

    .hero {
        background-image: url('{{ asset("images/bg.jpeg") }}');
        background-size: cover; background-position: center;
        border-radius: 20px; padding: 32px 40px; margin-bottom: 24px;
        position: relative; overflow: hidden;
    }
    .hero::before {
        content: ''; position: absolute; right: -40px; top: -40px;
        width: 220px; height: 220px; background: rgba(201,162,39,.12); border-radius: 50%;
    }
    .hero-title { font-size: 24px; font-weight: 800; color: #735C00; margin-bottom: 6px; position: relative; }
    .hero-sub { font-size: 13px; color: #92400E; position: relative; max-width: 500px; line-height: 1.6; }

    .btn-back {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 16px; background: #fff; border: 1.5px solid var(--border);
        border-radius: 10px; font-size: 13px; font-weight: 600; color: var(--neutral);
        text-decoration: none; transition: all .2s; margin-bottom: 20px;
    }
    .btn-back:hover { border-color: var(--gold); color: var(--gold); }

    .card {
        background: var(--white); border-radius: var(--radius);
        border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(0,0,0,.05);
        padding: 28px; margin-bottom: 20px;
    }
    .card-title {
        font-size: 14px; font-weight: 800; color: var(--neutral);
        margin-bottom: 18px; display: flex; align-items: center; gap: 8px;
    }
    .card-title svg { color: var(--gold); }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 12px; font-weight: 700; color: var(--neutral); margin-bottom: 6px; }
    .form-input {
        width: 100%; padding: 10px 12px; border: 1.5px solid var(--border);
        border-radius: 10px; font-size: 13px; outline: none; font-family: inherit;
        transition: border .2s; box-sizing: border-box; background: #FAFAFA;
    }
    .form-input:focus { border-color: var(--gold); background: #fff; }

    /* Peserta */
    .peserta-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 14px;
    }
    .peserta-title { font-size: 14px; font-weight: 800; color: var(--neutral); display: flex; align-items: center; gap: 8px; }
    .btn-tambah {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px; background: var(--gold); color: #fff;
        border: none; border-radius: 10px; font-size: 13px; font-weight: 700;
        cursor: pointer; font-family: inherit; transition: background .2s;
    }
    .btn-tambah:hover { background: #b8911f; }

    .peserta-item {
        border: 1.5px solid var(--border); border-radius: 12px;
        padding: 18px; margin-bottom: 12px; background: #FAFBFF;
        position: relative; transition: border .2s;
    }
    .peserta-item.conflict { border-color: var(--danger-border); background: var(--danger-lt); }
    .peserta-item-header {
        display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;
    }
    .peserta-label { font-size: 13px; font-weight: 800; color: var(--neutral); }
    .btn-hapus-peserta {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 5px 10px; background: #fff; border: 1px solid var(--danger-border);
        border-radius: 8px; font-size: 12px; font-weight: 600; color: var(--danger);
        cursor: pointer; font-family: inherit; transition: all .2s;
    }
    .btn-hapus-peserta:hover { background: var(--danger-lt); }

    .peserta-grid { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 12px; }
    .nama-input-wrap { position: relative; }
    .nama-display {
        width: 100%; padding: 10px 12px; border: 1.5px solid var(--border);
        border-radius: 10px; font-size: 13px; font-family: inherit;
        background: #F3F4F6; color: var(--neutral); box-sizing: border-box;
        cursor: default;
    }
    .nama-display.filled { background: var(--green-lt); border-color: var(--green-border); font-weight: 600; }

    .conflict-msg {
        margin-top: 8px; padding: 8px 12px; background: var(--danger-lt);
        border: 1px solid var(--danger-border); border-radius: 8px;
        font-size: 12px; color: var(--danger); display: flex; align-items: center; gap: 6px;
    }
    .conflict-global {
        padding: 12px 16px; background: var(--danger-lt); border: 1px solid var(--danger-border);
        border-radius: 10px; font-size: 13px; color: var(--danger);
        display: flex; align-items: center; gap: 8px; margin-bottom: 16px;
    }

    .empty-peserta {
        text-align: center; padding: 40px 20px; border: 2px dashed var(--border);
        border-radius: 12px; color: var(--muted);
    }
    .empty-peserta-icon { font-size: 32px; margin-bottom: 8px; }
    .empty-peserta-text { font-size: 13px; font-weight: 600; margin-bottom: 4px; color: var(--neutral); }
    .empty-peserta-sub { font-size: 12px; }

    .form-actions {
        display: flex; gap: 12px; justify-content: flex-end; margin-top: 8px;
    }
    .btn-submit {
        padding: 11px 28px; background: var(--gold); color: #fff;
        border: none; border-radius: 10px; font-size: 14px; font-weight: 700;
        cursor: pointer; font-family: inherit; transition: background .2s;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-submit:hover { background: #b8911f; }
    .btn-submit:disabled { background: #D1D5DB; cursor: not-allowed; }
    .btn-cancel-form {
        padding: 11px 20px; background: #fff; color: var(--muted);
        border: 1.5px solid var(--border); border-radius: 10px; font-size: 14px;
        font-weight: 600; cursor: pointer; font-family: inherit;
        text-decoration: none; display: inline-flex; align-items: center;
        transition: all .2s;
    }
    .btn-cancel-form:hover { border-color: var(--danger); color: var(--danger); }

    /* MODAL POPUP */
    .modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45);
        z-index: 9999; align-items: center; justify-content: center;
    }
    .modal-overlay.active { display: flex; }
    .modal-box {
        background: #fff; border-radius: 20px; padding: 0;
        width: 100%; max-width: 560px; box-shadow: 0 20px 60px rgba(0,0,0,.18);
        overflow: hidden; max-height: 85vh; display: flex; flex-direction: column;
    }
    .modal-head {
        padding: 22px 24px 16px; border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;
    }
    .modal-head-title { font-size: 16px; font-weight: 800; color: var(--neutral); }
    .btn-close-modal {
        width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--border);
        background: #fff; cursor: pointer; display: flex; align-items: center;
        justify-content: center; color: var(--muted); transition: all .2s;
    }
    .btn-close-modal:hover { border-color: var(--danger); color: var(--danger); background: var(--danger-lt); }
    .modal-search-wrap { padding: 14px 24px; border-bottom: 1px solid var(--border); flex-shrink: 0; }
    .modal-search {
        width: 100%; padding: 9px 12px 9px 36px; border: 1.5px solid var(--border);
        border-radius: 10px; font-size: 13px; outline: none; font-family: inherit;
        background: #FAFAFA url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='none' viewBox='0 0 24 24' stroke='%236B7280' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 10px center;
        box-sizing: border-box; transition: border .2s;
    }
    .modal-search:focus { border-color: var(--gold); background-color: #fff; }
    .modal-list { overflow-y: auto; flex: 1; }
    .modal-table { width: 100%; border-collapse: collapse; }
    .modal-table thead th {
        padding: 10px 16px; font-size: 11px; font-weight: 700; color: var(--muted);
        text-align: left; background: #FAFAFA; border-bottom: 1px solid var(--border);
        text-transform: uppercase; letter-spacing: .3px; position: sticky; top: 0;
    }
    .modal-table tbody tr { border-bottom: 1px solid #F3F4F6; transition: background .15s; cursor: pointer; }
    .modal-table tbody tr:hover { background: #FAFBFF; }
    .modal-table tbody tr.selected { background: var(--gold-lt); }
    .modal-table tbody td { padding: 12px 16px; font-size: 13px; color: var(--neutral); vertical-align: middle; }
    .modal-table .nim { font-weight: 700; font-size: 12.5px; }
    .modal-table .judul { font-size: 12px; color: var(--muted); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .modal-foot {
        padding: 14px 24px; border-top: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;
        background: #fff;
    }
    .modal-selected-count { font-size: 13px; color: var(--muted); }
    .modal-selected-count span { font-weight: 800; color: var(--neutral); }
    .btn-tambahkan {
        padding: 9px 20px; background: var(--gold); color: #fff;
        border: none; border-radius: 10px; font-size: 13px; font-weight: 700;
        cursor: pointer; font-family: inherit; transition: background .2s;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-tambahkan:hover { background: #b8911f; }
    .btn-batal-modal {
        padding: 9px 16px; background: #fff; color: var(--muted);
        border: 1.5px solid var(--border); border-radius: 10px; font-size: 13px;
        font-weight: 600; cursor: pointer; font-family: inherit; transition: all .2s;
    }
    .btn-batal-modal:hover { border-color: var(--danger); color: var(--danger); }

    .modal-empty { text-align: center; padding: 40px 20px; color: var(--muted); font-size: 13px; }
    .modal-loading { text-align: center; padding: 30px; color: var(--muted); font-size: 13px; }

    .checkbox-custom {
        width: 16px; height: 16px; border-radius: 4px; border: 1.5px solid var(--border);
        cursor: pointer; accent-color: var(--gold);
    }

    @media (max-width: 700px) {
        .peserta-grid { grid-template-columns: 1fr; }
        .form-row { grid-template-columns: 1fr; }
    }
</style>

<div class="wrap">

    <div class="hero">
        <div class="hero-title">Tetapkan Jadwal Seminar Massal</div>
        <div class="hero-sub">Tetapkan jadwal seminar untuk beberapa mahasiswa sekaligus dalam satu tanggal dan ruangan yang sama.</div>
    </div>

    <a href="{{ route('admin.seminar.index') }}" class="btn-back">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
        </svg>
        Kembali
    </a>

    <form method="POST" action="{{ route('jadwalseminar.massal.simpan') }}" id="formMassal">
        @csrf

        {{-- INFO SEMINAR --}}
        <div class="card">
            <div class="card-title">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                </svg>
                Informasi Seminar
            </div>
            <div class="form-row">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Tanggal Seminar <span style="color:var(--danger)">*</span></label>
                    <input type="date" name="tanggal_seminar" id="inputTanggal" class="form-input" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Ruangan Seminar <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="ruang" id="inputRuang" class="form-input" placeholder="contoh: Ruang Rapat Utama - Gedung A" required>
                </div>
            </div>
        </div>

        {{-- PESERTA SEMINAR --}}
        <div class="card">
            <div class="peserta-header">
                <div class="peserta-title">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:var(--gold)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                    </svg>
                    Peserta Seminar
                </div>
                <button type="button" class="btn-tambah" onclick="bukaModal()">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Tambah Mahasiswa
                </button>
            </div>

            <div id="conflictGlobal" class="conflict-global" style="display:none;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                </svg>
                <span id="conflictGlobalMsg"></span>
            </div>

            <div id="pesertaList">
                <div class="empty-peserta" id="emptyPeserta">
                    <div class="empty-peserta-icon">👥</div>
                    <div class="empty-peserta-text">Belum ada peserta ditambahkan</div>
                    <div class="empty-peserta-sub">Klik tombol "+ Tambah Mahasiswa" untuk menambahkan peserta seminar</div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.seminar.index') }}" class="btn-cancel-form">Batal</a>
            <button type="submit" class="btn-submit" id="btnSubmit" disabled>
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                Simpan Jadwal Seminar
            </button>
        </div>

    </form>

</div>

{{-- MODAL TAMBAH MAHASISWA --}}
<div class="modal-overlay" id="modalTambah">
    <div class="modal-box">
        <div class="modal-head">
            <div class="modal-head-title">Tambah Mahasiswa</div>
            <button type="button" class="btn-close-modal" onclick="tutupModal()">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="modal-search-wrap">
            <input type="text" id="modalSearch" class="modal-search" placeholder="Cari mahasiswa...">
        </div>
        <div class="modal-list" id="modalList">
            <div class="modal-loading">Memuat data...</div>
        </div>
        <div class="modal-foot">
            <div class="modal-selected-count"><span id="selectedCount">0</span> Terpilih</div>
            <div style="display:flex;gap:8px;">
                <button type="button" class="btn-batal-modal" onclick="tutupModal()">Batal</button>
                <button type="button" class="btn-tambahkan" onclick="tambahkanPeserta()">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
                    </svg>
                    Tambahkan Mahasiswa
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // ============================================================
    // STATE
    // ============================================================
    let allMahasiswa = [];      // data dari API
    let selected = new Set();   // id yang dipilih di modal
    let pesertaData = [];       // peserta yang sudah ditambahkan ke form
    let searchTimer;

    // ============================================================
    // FETCH DATA MAHASISWA BELUM DIJADWAL
    // ============================================================
    async function fetchMahasiswa(search = '') {
        const url = '{{ route("jadwalseminar.mahasiswa") }}' + (search ? '?search=' + encodeURIComponent(search) : '');
        try {
            const res = await fetch(url);
            allMahasiswa = await res.json();
        } catch (e) {
            allMahasiswa = [];
        }
    }

    // ============================================================
    // MODAL
    // ============================================================
    async function bukaModal() {
        document.getElementById('modalTambah').classList.add('active');
        document.getElementById('modalSearch').value = '';
        selected = new Set();
        updateSelectedCount();
        renderModalList('');
        await fetchMahasiswa();
        renderModalList('');
    }

    function tutupModal() {
        document.getElementById('modalTambah').classList.remove('active');
    }

    function renderModalList(search) {
        const list = document.getElementById('modalList');
        // filter: belum ada di pesertaData
        const sudahAdaIds = new Set(pesertaData.map(p => p.id));
        const filtered = allMahasiswa.filter(m => {
            const notAdded = !sudahAdaIds.has(m.id);
            const matchSearch = !search ||
                m.nama.toLowerCase().includes(search.toLowerCase()) ||
                m.mahasiswa_id.toLowerCase().includes(search.toLowerCase());
            return notAdded && matchSearch;
        });

        if (allMahasiswa.length === 0) {
            list.innerHTML = '<div class="modal-loading">Memuat data...</div>';
            return;
        }
        if (filtered.length === 0) {
            list.innerHTML = '<div class="modal-empty">Tidak ada mahasiswa ditemukan</div>';
            return;
        }

        list.innerHTML = `
            <table class="modal-table">
                <thead>
                    <tr>
                        <th style="width:36px;"></th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Judul Proposal</th>
                    </tr>
                </thead>
                <tbody>
                    ${filtered.map(m => `
                        <tr class="${selected.has(m.id) ? 'selected' : ''}" onclick="toggleSelect(${m.id})">
                            <td><input type="checkbox" class="checkbox-custom" ${selected.has(m.id) ? 'checked' : ''} onclick="event.stopPropagation();toggleSelect(${m.id})"></td>
                            <td class="nim">${m.mahasiswa_id}</td>
                            <td style="font-weight:600;">${m.nama}</td>
                            <td class="judul">${m.judul_ta || '-'}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
    }

    function toggleSelect(id) {
        if (selected.has(id)) selected.delete(id);
        else selected.add(id);
        updateSelectedCount();
        renderModalList(document.getElementById('modalSearch').value);
    }

    function updateSelectedCount() {
        document.getElementById('selectedCount').textContent = selected.size;
    }

    function tambahkanPeserta() {
        if (selected.size === 0) return;
        const toAdd = allMahasiswa.filter(m => selected.has(m.id));
        toAdd.forEach(m => {
            if (!pesertaData.find(p => p.id === m.id)) {
                pesertaData.push({ id: m.id, mahasiswa_id: m.mahasiswa_id, nama: m.nama, waktu_mulai: '', waktu_selesai: '' });
            }
        });
        selected = new Set();
        tutupModal();
        renderPesertaList();
        updateSubmitBtn();
    }

    // Modal search
    document.getElementById('modalSearch').addEventListener('input', function () {
        clearTimeout(searchTimer);
        const val = this.value;
        searchTimer = setTimeout(async () => {
            await fetchMahasiswa(val);
            renderModalList(val);
        }, 350);
    });

    document.getElementById('modalTambah').addEventListener('click', function (e) {
        if (e.target === this) tutupModal();
    });

    // ============================================================
    // RENDER PESERTA DI FORM
    // ============================================================
    function renderPesertaList() {
        const container = document.getElementById('pesertaList');
        const empty = document.getElementById('emptyPeserta');

        if (pesertaData.length === 0) {
            container.innerHTML = `
                <div class="empty-peserta" id="emptyPeserta">
                    <div class="empty-peserta-icon">👥</div>
                    <div class="empty-peserta-text">Belum ada peserta ditambahkan</div>
                    <div class="empty-peserta-sub">Klik tombol "+ Tambah Mahasiswa" untuk menambahkan peserta seminar</div>
                </div>`;
            return;
        }

        container.innerHTML = pesertaData.map((p, i) => `
            <div class="peserta-item" id="peserta-item-${i}">
                <input type="hidden" name="peserta[${i}][id]" value="${p.id}">
                <div class="peserta-item-header">
                    <div class="peserta-label">Mahasiswa ${i + 1}</div>
                    <button type="button" class="btn-hapus-peserta" onclick="hapusPeserta(${i})">
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                        </svg>
                        Hapus
                    </button>
                </div>
                <div class="peserta-grid">
                    <div>
                        <label style="display:block;font-size:12px;font-weight:700;color:var(--neutral);margin-bottom:6px;">Nama Mahasiswa</label>
                        <div class="nama-display filled">${p.nama} <span style="color:var(--muted);font-weight:400;font-size:11px;">(${p.mahasiswa_id})</span></div>
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:700;color:var(--neutral);margin-bottom:6px;">Jam Mulai</label>
                        <input type="time" name="peserta[${i}][waktu_mulai]" class="form-input waktu-input"
                            value="${p.waktu_mulai}" data-index="${i}" data-type="mulai"
                            onchange="updateWaktu(${i}, 'mulai', this.value)" required>
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:700;color:var(--neutral);margin-bottom:6px;">Jam Selesai</label>
                        <input type="time" name="peserta[${i}][waktu_selesai]" class="form-input waktu-input"
                            value="${p.waktu_selesai}" data-index="${i}" data-type="selesai"
                            onchange="updateWaktu(${i}, 'selesai', this.value)" required>
                    </div>
                </div>
                <div id="conflict-msg-${i}" style="display:none;" class="conflict-msg">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                    </svg>
                    <span></span>
                </div>
            </div>
        `).join('');

        checkConflicts();
    }

    function hapusPeserta(index) {
        pesertaData.splice(index, 1);
        renderPesertaList();
        updateSubmitBtn();
    }

    function updateWaktu(index, type, value) {
        if (type === 'mulai') pesertaData[index].waktu_mulai = value;
        else pesertaData[index].waktu_selesai = value;
        checkConflicts();
        updateSubmitBtn();
    }

    // ============================================================
    // CEK KONFLIK WAKTU
    // ============================================================
    function timeToMinutes(t) {
        if (!t) return null;
        const [h, m] = t.split(':').map(Number);
        return h * 60 + m;
    }

    function checkConflicts() {
        let hasConflict = false;
        const conflicts = new Array(pesertaData.length).fill(false);

        for (let i = 0; i < pesertaData.length; i++) {
            const mulaiI = timeToMinutes(pesertaData[i].waktu_mulai);
            const selesaiI = timeToMinutes(pesertaData[i].waktu_selesai);
            if (!mulaiI || !selesaiI) continue;

            for (let j = i + 1; j < pesertaData.length; j++) {
                const mulaiJ = timeToMinutes(pesertaData[j].waktu_mulai);
                const selesaiJ = timeToMinutes(pesertaData[j].waktu_selesai);
                if (!mulaiJ || !selesaiJ) continue;

                // overlap check
                if (mulaiI < selesaiJ && selesaiI > mulaiJ) {
                    conflicts[i] = true;
                    conflicts[j] = true;
                    hasConflict = true;
                }
            }
        }

        // Update UI per peserta
        pesertaData.forEach((p, i) => {
            const item = document.getElementById(`peserta-item-${i}`);
            const msgEl = document.getElementById(`conflict-msg-${i}`);
            if (!item || !msgEl) return;

            if (conflicts[i]) {
                item.classList.add('conflict');
                msgEl.style.display = 'flex';
                msgEl.querySelector('span').textContent = 'Sesi ini tumpang tindih dengan sesi lain.';
            } else {
                item.classList.remove('conflict');
                msgEl.style.display = 'none';
            }
        });

        // Global conflict banner
        const globalEl = document.getElementById('conflictGlobal');
        const globalMsg = document.getElementById('conflictGlobalMsg');
        if (hasConflict) {
            globalEl.style.display = 'flex';
            globalMsg.textContent = 'Deteksi Tabrakan Waktu: Beberapa sesi memiliki jadwal yang bersinggungan. Harap perbaiki sebelum menyimpan.';
        } else {
            globalEl.style.display = 'none';
        }

        return hasConflict;
    }

    // ============================================================
    // SUBMIT BUTTON STATE
    // ============================================================
    function updateSubmitBtn() {
        const btn = document.getElementById('btnSubmit');
        btn.disabled = pesertaData.length === 0;
    }

    // ============================================================
    // PREVENT SUBMIT SAAT ADA KONFLIK
    // ============================================================
    document.getElementById('formMassal').addEventListener('submit', function (e) {
        if (checkConflicts()) {
            e.preventDefault();
            alert('Terdapat konflik waktu antar sesi. Harap perbaiki terlebih dahulu.');
        }
    });
</script>

@endsection