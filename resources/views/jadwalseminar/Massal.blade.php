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

    .peserta-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 14px;
    }
    .peserta-title { font-size: 14px; font-weight: 800; color: var(--neutral); display: flex; align-items: center; gap: 8px; }
    .btn-tambah {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px; background: #FDE047; color: #713F12;
        border: 1px solid #FACC15; border-radius: 10px; font-size: 13px; font-weight: 700;
        cursor: pointer; font-family: inherit; transition: background .2s;
    }
    .btn-tambah:hover { background: #FACC15; color: #713F12; }

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
    .nama-display {
        width: 100%; padding: 10px 12px; border: 1.5px solid var(--border);
        border-radius: 10px; font-size: 13px; font-family: inherit;
        background: #F3F4F6; color: var(--neutral); box-sizing: border-box;
        cursor: default;
    }
    .nama-display.filled { background: var(--green-lt); border-color: var(--green-border); font-weight: 600; }

    .conflict-msg {
        margin-top: 8px; padding: 10px 12px; background: var(--danger-lt);
        border: 1px solid var(--danger-border); border-radius: 8px;
        font-size: 12px; color: var(--danger); display: flex; align-items: flex-start; gap: 6px;
        line-height: 1.5;
    }
    .conflict-msg svg { flex-shrink: 0; margin-top: 1px; }

    .conflict-global {
        padding: 12px 16px; background: var(--danger-lt); border: 1px solid var(--danger-border);
        border-radius: 10px; font-size: 13px; color: var(--danger);
        display: flex; align-items: flex-start; gap: 8px; margin-bottom: 16px;
        line-height: 1.5;
    }
    .conflict-global svg { flex-shrink: 0; margin-top: 1px; }
    .conflict-global-list { margin-top: 6px; padding-left: 4px; }
    .conflict-global-list li { margin-bottom: 2px; font-size: 12px; }

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
        padding: 11px 28px; background: #FDE047; color: #713F12;
        border: 1px solid #FACC15; border-radius: 10px; font-size: 14px; font-weight: 700;
        cursor: pointer; font-family: inherit; transition: background .2s;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-submit:hover { background: #FACC15; color: #713F12; }
    .btn-submit:disabled { background: #D1D5DB; color: #9CA3AF; border-color: #D1D5DB; cursor: not-allowed; }
    .btn-cancel-form {
        padding: 11px 20px; background: #fff; color: var(--muted);
        border: 1.5px solid var(--border); border-radius: 10px; font-size: 14px;
        font-weight: 600; cursor: pointer; font-family: inherit;
        text-decoration: none; display: inline-flex; align-items: center;
        transition: all .2s;
    }
    .btn-cancel-form:hover { border-color: var(--danger); color: var(--danger); }

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
        padding: 9px 20px; background: #FDE047; color: #713F12;
        border: 1px solid #FACC15; border-radius: 10px; font-size: 13px; font-weight: 700;
        cursor: pointer; font-family: inherit; transition: background .2s;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-tambahkan:hover { background: #FACC15; color: #713F12; }
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

    .popup-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.50);
        z-index: 99999; align-items: center; justify-content: center;
    }
    .popup-overlay.active { display: flex; }
    .popup-box {
        background: #fff; border-radius: 24px; padding: 40px 36px 32px;
        width: 100%; max-width: 360px; text-align: center;
        box-shadow: 0 24px 64px rgba(0,0,0,.18); animation: popupIn .25s ease;
    }
    @keyframes popupIn {
        from { transform: scale(.88); opacity: 0; }
        to   { transform: scale(1);   opacity: 1; }
    }
    .popup-icon {
        width: 80px; height: 80px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px;
    }
    .popup-icon.confirm { background: #FEF3C7; border: 3px solid #F5D97A; color: #C9A227; font-size: 40px; }
    .popup-icon.success { background: #F0FDF4; border: 3px solid #BBF7D0; }
    .popup-icon.error   { background: #FEF2F2; border: 3px solid #FECACA; }
    .popup-icon.success svg { width: 40px; height: 40px; stroke: #16A34A; stroke-width: 2.5; fill: none; }
    .popup-icon.error svg   { width: 40px; height: 40px; stroke: #EF4444; stroke-width: 2.5; fill: none; }
    .popup-title { font-size: 20px; font-weight: 800; color: var(--neutral); margin-bottom: 10px; }
    .popup-msg { font-size: 13px; color: var(--muted); line-height: 1.6; margin-bottom: 28px; }
    .popup-target-name {
        font-size: 14px; font-weight: 700; color: var(--neutral);
        background: #F3F4F6; border-radius: 8px; padding: 8px 14px;
        margin: -12px 0 20px; display: inline-block;
    }
    .popup-cause {
        font-size: 12px; color: var(--danger);
        background: var(--danger-lt); border: 1px solid var(--danger-border);
        border-radius: 8px; padding: 8px 12px; margin-bottom: 20px;
        text-align: left; display: none;
    }
    .popup-actions { display: flex; gap: 10px; justify-content: center; }
    .btn-popup-ok {
        padding: 10px 40px; background: var(--gold); color: #fff;
        border: none; border-radius: 10px; font-size: 14px; font-weight: 700;
        cursor: pointer; font-family: inherit; transition: background .2s;
    }
    .btn-popup-ok:hover { background: #b8911f; }
    .btn-popup-batal {
        padding: 10px 24px; background: #F3F4F6; color: var(--muted);
        border: none; border-radius: 10px; font-size: 14px; font-weight: 600;
        cursor: pointer; font-family: inherit; transition: background .2s;
    }
    .btn-popup-batal:hover { background: #E5E7EB; }
    .btn-popup-simpan {
        padding: 10px 24px; background: var(--gold); color: #fff;
        border: none; border-radius: 10px; font-size: 14px; font-weight: 700;
        cursor: pointer; font-family: inherit; transition: background .2s;
    }
    .btn-popup-simpan:hover { background: #b8911f; }
    .btn-popup-hapus {
        padding: 10px 24px; background: var(--danger); color: #fff;
        border: none; border-radius: 10px; font-size: 14px; font-weight: 700;
        cursor: pointer; font-family: inherit; transition: background .2s;
    }
    .btn-popup-hapus:hover { background: #DC2626; }

    @media (max-width: 700px) {
        .peserta-grid { grid-template-columns: 1fr; }
        .form-row { grid-template-columns: 1fr; }
        .popup-box { margin: 0 16px; padding: 32px 24px 24px; }
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
                    <input type="date" name="tanggal_seminar" id="inputTanggal" class="form-input" required onchange="updateSubmitBtn()">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Ruangan Seminar <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="ruang" id="inputRuang" class="form-input" placeholder="contoh: Ruang Rapat Utama - Gedung A" required oninput="updateSubmitBtn()">
                </div>
            </div>
        </div>

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

            {{-- Banner konflik global --}}
            <div id="conflictGlobal" class="conflict-global" style="display:none;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                </svg>
                <div>
                    <div style="font-weight:700;margin-bottom:4px;">Deteksi Tabrakan Waktu</div>
                    <div id="conflictGlobalList"></div>
                </div>
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
            <button type="button" class="btn-submit" id="btnSubmit" disabled onclick="bukaPopupKonfirmasi()">
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

{{-- POPUP KONFIRMASI SIMPAN --}}
<div class="popup-overlay" id="popupKonfirmasi">
    <div class="popup-box">
        <div class="popup-icon confirm">?</div>
        <div class="popup-title">Konfirmasi</div>
        <div class="popup-msg">Apakah Anda yakin ingin menyimpan jadwal seminar?</div>
        <div class="popup-actions">
            <button type="button" class="btn-popup-batal" onclick="tutupPopupKonfirmasi()">Batal</button>
            <button type="button" class="btn-popup-simpan" onclick="submitForm()">Simpan Jadwal</button>
        </div>
    </div>
</div>

{{-- POPUP BERHASIL SIMPAN --}}
<div class="popup-overlay" id="popupBerhasil">
    <div class="popup-box">
        <div class="popup-icon success">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
        </div>
        <div class="popup-title">Berhasil!</div>
        <div class="popup-msg">Jadwal seminar berhasil disimpan.</div>
        <div class="popup-actions">
            <button type="button" class="btn-popup-ok" onclick="popupBerhasilOk()">OK</button>
        </div>
    </div>
</div>

{{-- POPUP GAGAL SIMPAN --}}
<div class="popup-overlay" id="popupGagal">
    <div class="popup-box">
        <div class="popup-icon error">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <div class="popup-title">Gagal!</div>
        <div class="popup-msg">Jadwal seminar gagal disimpan.</div>
        <div class="popup-cause" id="popupGagalCause"></div>
        <div class="popup-actions">
            <button type="button" class="btn-popup-ok" onclick="tutupPopupGagal()">Ok</button>
        </div>
    </div>
</div>

{{-- POPUP KONFIRMASI HAPUS PESERTA --}}
<div class="popup-overlay" id="popupKonfirmasiHapus">
    <div class="popup-box">
        <div class="popup-icon confirm">?</div>
        <div class="popup-title">Hapus Peserta?</div>
        <div class="popup-msg">Anda akan menghapus peserta berikut dari daftar seminar:</div>
        <div class="popup-target-name" id="popupHapusNama">—</div>
        <div class="popup-actions">
            <button type="button" class="btn-popup-batal" onclick="tutupPopupKonfirmasiHapus()">Batal</button>
            <button type="button" class="btn-popup-simpan" onclick="konfirmasiHapusPeserta()">Hapus</button>
        </div>
    </div>
</div>

{{-- POPUP BERHASIL HAPUS PESERTA --}}
<div class="popup-overlay" id="popupBerhasilHapus">
    <div class="popup-box">
        <div class="popup-icon success">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
        </div>
        <div class="popup-title">Berhasil!</div>
        <div class="popup-msg">Peserta berhasil dihapus dari daftar seminar.</div>
        <div class="popup-actions">
            <button type="button" class="btn-popup-ok" onclick="tutupPopupBerhasilHapus()">OK</button>
        </div>
    </div>
</div>

{{-- POPUP GAGAL HAPUS PESERTA --}}
<div class="popup-overlay" id="popupGagalHapus">
    <div class="popup-box">
        <div class="popup-icon error">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <div class="popup-title">Gagal!</div>
        <div class="popup-msg">Peserta gagal dihapus dari daftar seminar.</div>
        <div class="popup-cause" id="popupGagalHapusCause"></div>
        <div class="popup-actions">
            <button type="button" class="btn-popup-ok" onclick="tutupPopupGagalHapus()">Ok</button>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('popupBerhasil').classList.add('active');
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        showPopupGagal(@json(session('error')));
    });
</script>
@endif

<script>
    let allMahasiswa     = [];
    let selected         = new Set();
    let pesertaData      = [];
    let searchTimer;
    let hapusTargetIndex = null;

    async function fetchMahasiswa(search = '') {
        const url = '{{ route("jadwalseminar.mahasiswa") }}' + (search ? '?search=' + encodeURIComponent(search) : '');
        try {
            const res = await fetch(url);
            allMahasiswa = await res.json();
        } catch (e) {
            allMahasiswa = [];
        }
    }

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
        const sudahAdaIds = new Set(pesertaData.map(p => p.id));
        const filtered = allMahasiswa.filter(m => {
            const notAdded    = !sudahAdaIds.has(m.id);
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

    function renderPesertaList() {
        const container = document.getElementById('pesertaList');
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
                    <button type="button" class="btn-hapus-peserta" onclick="bukaPopupKonfirmasiHapus(${i})">
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
                {{-- Pesan konflik per mahasiswa --}}
                <div id="conflict-msg-${i}" style="display:none;" class="conflict-msg">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                    </svg>
                    <span></span>
                </div>
            </div>
        `).join('');

        checkConflicts();
    }

    function updateWaktu(index, type, value) {
        if (type === 'mulai') pesertaData[index].waktu_mulai = value;
        else pesertaData[index].waktu_selesai = value;
        checkConflicts();
        updateSubmitBtn();
    }

    function timeToMinutes(t) {
        if (!t) return null;
        const [h, m] = t.split(':').map(Number);
        return h * 60 + m;
    }

    // ✅ FUNGSI CONFLICT YANG DIPERBAIKI
    // - Hanya item yang tubrukan yang dapat peringatan merah
    // - Pesan per item menyebutkan nama mahasiswa yang tubrukan
    // - Banner global menampilkan daftar pasangan yang tubrukan
    function checkConflicts() {
        let hasConflict = false;

        // conflictWith[i] = array nama mahasiswa yang tubrukan dengan i
        const conflictWith = pesertaData.map(() => []);
        // pasangan yang tubrukan untuk banner global
        const conflictPairs = [];

        for (let i = 0; i < pesertaData.length; i++) {
            const mulaiI   = timeToMinutes(pesertaData[i].waktu_mulai);
            const selesaiI = timeToMinutes(pesertaData[i].waktu_selesai);
            if (!mulaiI || !selesaiI) continue;

            for (let j = i + 1; j < pesertaData.length; j++) {
                const mulaiJ   = timeToMinutes(pesertaData[j].waktu_mulai);
                const selesaiJ = timeToMinutes(pesertaData[j].waktu_selesai);
                if (!mulaiJ || !selesaiJ) continue;

                if (mulaiI < selesaiJ && selesaiI > mulaiJ) {
                    hasConflict = true;
                    // ✅ catat siapa yang tubrukan dengan siapa
                    conflictWith[i].push(pesertaData[j].nama);
                    conflictWith[j].push(pesertaData[i].nama);
                    conflictPairs.push(
                        `Mahasiswa ${i + 1} (${pesertaData[i].nama}) ↔ Mahasiswa ${j + 1} (${pesertaData[j].nama})`
                    );
                }
            }
        }

        // Update tampilan per mahasiswa
        pesertaData.forEach((p, i) => {
            const item  = document.getElementById(`peserta-item-${i}`);
            const msgEl = document.getElementById(`conflict-msg-${i}`);
            if (!item || !msgEl) return;

            if (conflictWith[i].length > 0) {
                // ✅ hanya yang tubrukan yang merah
                item.classList.add('conflict');
                msgEl.style.display = 'flex';
                // ✅ sebutkan nama mahasiswa yang tubrukan
                const namaList = conflictWith[i].join(', ');
                msgEl.querySelector('span').textContent =
                    'Jadwal sesi ini bertabrakan dengan: ' + namaList + '. Harap sesuaikan jam mulai atau selesai.';
            } else {
                // ✅ yang tidak tubrukan tetap normal
                item.classList.remove('conflict');
                msgEl.style.display = 'none';
            }
        });

        // Update banner global
        const globalEl   = document.getElementById('conflictGlobal');
        const globalList = document.getElementById('conflictGlobalList');
        if (hasConflict) {
            globalEl.style.display = 'flex';
            // ✅ tampilkan daftar pasangan yang tubrukan
            globalList.innerHTML =
                '<ul class="conflict-global-list">' +
                conflictPairs.map(p => `<li>${p}</li>`).join('') +
                '</ul>';
        } else {
            globalEl.style.display = 'none';
            globalList.innerHTML = '';
        }

        return hasConflict;
    }

    function updateSubmitBtn() {
        const btn     = document.getElementById('btnSubmit');
        const tanggal = document.getElementById('inputTanggal').value.trim();
        const ruang   = document.getElementById('inputRuang').value.trim();

        if (pesertaData.length === 0) { btn.disabled = true; return; }
        if (!tanggal || !ruang) { btn.disabled = true; return; }

        const semuaWaktuLengkap = pesertaData.every(p => p.waktu_mulai !== '' && p.waktu_selesai !== '');
        if (!semuaWaktuLengkap) { btn.disabled = true; return; }

        if (checkConflicts()) { btn.disabled = true; return; }

        btn.disabled = false;
    }

    // POPUP KONFIRMASI SIMPAN
    function bukaPopupKonfirmasi() {
        if (checkConflicts()) {
            showPopupGagal('Terdapat konflik waktu antar sesi. Harap perbaiki terlebih dahulu.');
            return;
        }
        document.getElementById('popupKonfirmasi').classList.add('active');
    }

    function tutupPopupKonfirmasi() {
        document.getElementById('popupKonfirmasi').classList.remove('active');
    }

    document.getElementById('popupKonfirmasi').addEventListener('click', function (e) {
        if (e.target === this) tutupPopupKonfirmasi();
    });

    function submitForm() {
        tutupPopupKonfirmasi();
        const form     = document.getElementById('formMassal');
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(async res => {
            const data = await res.json().catch(() => ({}));
            if (res.ok && data.success !== false) {
                document.getElementById('popupBerhasil').classList.add('active');
            } else {
                showPopupGagal(data.message || data.error || 'Terjadi kesalahan pada server.');
            }
        })
        .catch(() => {
            showPopupGagal('Tidak dapat terhubung ke server. Periksa koneksi Anda.');
        });
    }

    function popupBerhasilOk() {
        document.getElementById('popupBerhasil').classList.remove('active');
        window.location.href = '{{ route("admin.seminar.index") }}';
    }

    document.getElementById('popupBerhasil').addEventListener('click', function (e) {
        if (e.target === this) popupBerhasilOk();
    });

    function showPopupGagal(cause) {
        const causeEl = document.getElementById('popupGagalCause');
        causeEl.textContent   = cause || '';
        causeEl.style.display = cause ? 'block' : 'none';
        document.getElementById('popupGagal').classList.add('active');
    }

    function tutupPopupGagal() {
        document.getElementById('popupGagal').classList.remove('active');
    }

    document.getElementById('popupGagal').addEventListener('click', function (e) {
        if (e.target === this) tutupPopupGagal();
    });

    // POPUP KONFIRMASI HAPUS PESERTA
    function bukaPopupKonfirmasiHapus(index) {
        hapusTargetIndex = index;
        const nama = pesertaData[index].nama + ' (' + pesertaData[index].mahasiswa_id + ')';
        document.getElementById('popupHapusNama').textContent = nama;
        document.getElementById('popupKonfirmasiHapus').classList.add('active');
    }

    function tutupPopupKonfirmasiHapus() {
        document.getElementById('popupKonfirmasiHapus').classList.remove('active');
        hapusTargetIndex = null;
    }

    document.getElementById('popupKonfirmasiHapus').addEventListener('click', function (e) {
        if (e.target === this) tutupPopupKonfirmasiHapus();
    });

    function konfirmasiHapusPeserta() {
        const targetIndex = hapusTargetIndex;
        tutupPopupKonfirmasiHapus();

        try {
            if (targetIndex === null || targetIndex < 0 || targetIndex >= pesertaData.length) {
                throw new Error('Index tidak valid.');
            }
            pesertaData.splice(targetIndex, 1);
            renderPesertaList();
            updateSubmitBtn();
            document.getElementById('popupBerhasilHapus').classList.add('active');
        } catch (err) {
            showPopupGagalHapus('Gagal menghapus peserta: ' + err.message);
        }
    }

    function tutupPopupBerhasilHapus() {
        document.getElementById('popupBerhasilHapus').classList.remove('active');
    }

    document.getElementById('popupBerhasilHapus').addEventListener('click', function (e) {
        if (e.target === this) tutupPopupBerhasilHapus();
    });

    function showPopupGagalHapus(cause) {
        const causeEl = document.getElementById('popupGagalHapusCause');
        causeEl.textContent   = cause || '';
        causeEl.style.display = cause ? 'block' : 'none';
        document.getElementById('popupGagalHapus').classList.add('active');
    }

    function tutupPopupGagalHapus() {
        document.getElementById('popupGagalHapus').classList.remove('active');
    }

    document.getElementById('popupGagalHapus').addEventListener('click', function (e) {
        if (e.target === this) tutupPopupGagalHapus();
    });
</script>

@endsection