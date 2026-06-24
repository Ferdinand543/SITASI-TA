@extends('layouts.app')

@section('title', 'Riwayat Bimbingan')

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
    }

    .bimb-wrap { background: var(--bg); min-height: 100vh; }

    .bimb-hero {
        background-image: url('{{ asset("images/bg.jpeg") }}');
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        padding: 36px 40px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .bimb-hero::before {
        content: '';
        position: absolute;
        right: -40px; top: -40px;
        width: 200px; height: 200px;
        background: rgba(201,162,39,.12);
        border-radius: 50%;
    }

    .bimb-hero::after {
        content: '';
        position: absolute;
        right: 60px; bottom: -60px;
        width: 150px; height: 150px;
        background: rgba(201,162,39,.08);
        border-radius: 50%;
    }

    .bimb-hero-title { font-size: 26px; font-weight: 800; color: #735C00; margin-bottom: 6px; }
    .bimb-hero-sub   { font-size: 13px; color: #92400E; margin-bottom: 20px; }

    .btn-tambah {
        display: inline-flex; align-items: center; gap: 8px;
        background: #FFE083; color: #6C5700; border: none;
        border-radius: 10px; padding: 10px 20px;
        font-size: 13.5px; font-weight: 700; cursor: pointer;
        transition: .2s; text-decoration: none;
    }
    .btn-tambah:hover { background: #f5d040; color: #6C5700; }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(3,1fr);
        gap: 14px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: var(--white); border-radius: var(--radius);
        padding: 18px 20px; border: 1px solid var(--border);
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
        display: flex; align-items: flex-start; gap: 14px;
    }

    .stat-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .stat-icon.gold  { background: var(--gold-lt); }
    .stat-icon.green { background: #F0FDF4; }
    .stat-icon.blue  { background: #EFF6FF; }

    .stat-label { font-size: 11.5px; color: var(--muted); font-weight: 600; margin-bottom: 4px; }
    .stat-value { font-size: 22px; font-weight: 800; color: var(--neutral); }
    .stat-sub   { font-size: 11px; color: var(--muted); margin-top: 2px; }

    .banner-layak {
        background: #F0FDF4; border: 1.5px solid #86EFAC;
        border-radius: 14px; padding: 16px 20px; margin-bottom: 20px;
        display: flex; align-items: center; justify-content: space-between;
        gap: 16px; flex-wrap: wrap;
    }
    .banner-layak-left  { display: flex; align-items: flex-start; gap: 12px; flex: 1; }
    .banner-layak-icon  { width: 40px; height: 40px; border-radius: 10px; background: #DCFCE7; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .banner-layak-badge { display: inline-flex; align-items: center; gap: 5px; background: #16A34A; color: #fff; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 99px; margin-bottom: 4px; text-transform: uppercase; letter-spacing: .5px; }
    .banner-layak-title { font-size: 14px; font-weight: 800; color: #14532D; margin-bottom: 3px; }
    .banner-layak-sub   { font-size: 12px; color: #166534; line-height: 1.5; }
    .banner-layak-actions { display: flex; gap: 8px; flex-shrink: 0; }

    .btn-lihat-surat {
        padding: 9px 18px; border-radius: 10px; font-size: 13px; font-weight: 700;
        border: 1.5px solid #16A34A; background: #fff; color: #16A34A;
        cursor: pointer; text-decoration: none; white-space: nowrap;
        transition: .2s; display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-lihat-surat:hover { background: #F0FDF4; color: #15803D; }

    .btn-unduh-pdf {
        padding: 9px 18px; border-radius: 10px; font-size: 13px; font-weight: 700;
        border: none; background: #FFE083; color: #6C5700;
        cursor: pointer; text-decoration: none; white-space: nowrap;
        transition: .2s; display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-unduh-pdf:hover { background: #f5d040; color: #6C5700; }

    .banner-belum {
        background: #FFFBEB; border: 1px solid #FDE68A;
        border-radius: 12px; padding: 14px 18px; margin-bottom: 20px;
        display: flex; align-items: flex-start; gap: 10px;
        font-size: 13px; color: #92400E;
    }
    .banner-belum svg { flex-shrink: 0; margin-top: 1px; }

    .bimb-grid {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 20px;
        align-items: start;
    }

    .upload-card {
        background: var(--white); border-radius: var(--radius);
        padding: 22px; box-shadow: 0 2px 10px rgba(0,0,0,.05);
        border: 1px solid var(--border); position: sticky; top: 20px;
    }

    .upload-card-title { font-size: 14px; font-weight: 700; color: var(--neutral); margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    .form-group { margin-bottom: 14px; }
    .form-label { font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 5px; display: block; }
    .form-label-opt { font-size: 11px; color: #9CA3AF; font-weight: 400; margin-left: 4px; }

    .form-control {
        width: 100%; padding: 9px 12px;
        border: 1.5px solid var(--border); border-radius: 8px;
        font-size: 13px; color: var(--neutral); background: #FAFAFA;
        outline: none; transition: border .2s; font-family: inherit; box-sizing: border-box;
    }
    .form-control:focus { border-color: var(--gold); background: #fff; }
    .form-control[readonly] { color: var(--muted); cursor: not-allowed; }

    .dropzone {
        border: 2px dashed var(--gold-border); border-radius: 10px;
        background: var(--gold-lt); padding: 20px; text-align: center;
        cursor: pointer; transition: .2s; position: relative;
    }
    .dropzone:hover { border-color: var(--gold); background: #FEF3C7; }
    .dropzone-icon { font-size: 28px; margin-bottom: 6px; }
    .dropzone-text { font-size: 12px; color: var(--muted); font-weight: 500; }
    .dropzone-hint { font-size: 11px; color: #9CA3AF; margin-top: 3px; line-height: 1.5; }

    .file-list { margin-top: 8px; display: flex; flex-direction: column; gap: 5px; }
    .file-item { display: flex; align-items: center; gap: 8px; background: #F9FAFB; border: 1px solid var(--border); border-radius: 7px; padding: 7px 10px; font-size: 11.5px; color: var(--neutral); }
    .file-item-icon { font-size: 14px; flex-shrink: 0; }
    .file-item-name { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .file-item-remove { width: 18px; height: 18px; border-radius: 50%; border: none; background: #FEE2E2; color: #EF4444; font-size: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; line-height: 1; padding: 0; font-family: inherit; }
    .file-item-remove:hover { background: #FECACA; }

    .link-list { display: flex; flex-direction: column; gap: 6px; margin-bottom: 6px; }
    .link-row { display: flex; align-items: center; gap: 6px; }
    .link-row .form-control { margin: 0; font-size: 12px; padding: 8px 10px; }

    .btn-remove-link { width: 28px; height: 28px; border-radius: 7px; border: 1.5px solid #FEE2E2; background: #FFF5F5; color: #EF4444; font-size: 15px; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-family: inherit; transition: .2s; padding: 0; }
    .btn-remove-link:hover { background: #FEE2E2; }

    .btn-add-link { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; font-weight: 600; color: var(--gold); background: none; border: 1.5px dashed var(--gold-border); border-radius: 7px; padding: 6px 12px; cursor: pointer; font-family: inherit; transition: .2s; width: 100%; justify-content: center; }
    .btn-add-link:hover { background: var(--gold-lt); border-color: var(--gold); }

    .btn-kirim { width: 100%; background: #FFE083; color: #6C5700; border: none; border-radius: 10px; padding: 11px; font-size: 14px; font-weight: 700; cursor: pointer; margin-top: 4px; transition: .2s; font-family: inherit; }
    .btn-kirim:hover { background: #f5d040; color: #6C5700; }

    .tabel-card { background: var(--white); border-radius: var(--radius); box-shadow: 0 2px 10px rgba(0,0,0,.05); border: 1px solid var(--border); overflow: hidden; }

    .tabel-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
    .tabel-title  { font-size: 15px; font-weight: 700; color: var(--neutral); white-space: nowrap; }
    .tabel-search { display: flex; align-items: center; gap: 8px; flex: 1; flex-wrap: wrap; min-width: 0; max-width: 480px; }

    .search-input {
        flex: 1; min-width: 140px;
        padding: 8px 12px 8px 34px;
        border: 1.5px solid var(--border); border-radius: 8px;
        font-size: 13px; outline: none; font-family: inherit; transition: border .2s;
        background: #FAFAFA url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='none' viewBox='0 0 24 24' stroke='%236B7280' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 10px center;
    }
    .search-input:focus { border-color: var(--gold); background-color: #fff; }

    .filter-select { padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 13px; outline: none; background: #FAFAFA; font-family: inherit; cursor: pointer; white-space: nowrap; }
    .filter-select:focus { border-color: var(--gold); }

    .btn-reset { padding: 8px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 12px; font-weight: 600; background: #fff; color: var(--muted); cursor: pointer; transition: .2s; font-family: inherit; display: inline-flex; align-items: center; gap: 5px; white-space: nowrap; flex-shrink: 0; }
    .btn-reset:hover { border-color: var(--gold); color: var(--gold); }

    .tabel-scroll { overflow-x: auto; }

    table { width: 100%; border-collapse: collapse; min-width: 900px; }
    thead th { padding: 12px 16px; font-size: 12px; font-weight: 700; color: var(--muted); text-align: left; background: #FAFAFA; border-bottom: 1px solid var(--border); white-space: nowrap; }
    tbody tr { border-bottom: 1px solid #F3F4F6; transition: background .15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #FAFBFF; }
    tbody td { padding: 13px 16px; font-size: 13px; color: var(--neutral); vertical-align: middle; }

    .badge-pertemuan { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: var(--gold-lt); border: 1.5px solid var(--gold-border); border-radius: 8px; font-size: 13px; font-weight: 800; color: var(--gold); }

    .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 99px; font-size: 11.5px; font-weight: 600; }
    .status-dilihat     { background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; }
    .status-tidak-valid { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }
    .status-menunggu    { background: #F9FAFB; color: #6B7280; border: 1px solid #E5E7EB; }

    .btn-aksi { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; border: 1.5px solid var(--border); background: #fff; color: var(--muted); cursor: pointer; transition: .2s; text-decoration: none; }
    .btn-aksi:hover { border-color: var(--gold); color: var(--gold); background: var(--gold-lt); }

    .empty-row td { text-align: center; padding: 48px; color: var(--muted); font-size: 14px; }

    .catatan-cell { max-width: 160px; font-size: 12px; color: #6B7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* ── MODAL ── */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 1000; align-items: center; justify-content: center; padding: 20px; }
    .modal-overlay.show { display: flex; }

    .modal-box { background: #fff; border-radius: 20px; width: 100%; max-width: 560px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,.2); animation: modalIn .25s ease; }

    @keyframes modalIn {
        from { transform: scale(.95) translateY(10px); opacity: 0; }
        to   { transform: scale(1) translateY(0); opacity: 1; }
    }

    .modal-head { padding: 22px 24px 16px; border-bottom: 2px dashed var(--gold-border); position: relative; }
    .modal-head h5 { font-size: 17px; font-weight: 800; color: var(--neutral); margin-bottom: 3px; }
    .modal-head p  { font-size: 12.5px; color: var(--muted); margin: 0; }

    .modal-close { position: absolute; top: 18px; right: 20px; width: 30px; height: 30px; border-radius: 50%; border: none; background: #F3F4F6; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--muted); transition: .2s; }
    .modal-close:hover { background: #E5E7EB; color: var(--neutral); }

    .modal-body { padding: 20px 24px; }
    .modal-row  { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .modal-row.full { grid-template-columns: 1fr; }

    .modal-foot { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; }

    .btn-batal  { padding: 10px 22px; border: 1.5px solid var(--border); border-radius: 10px; background: #fff; font-size: 13.5px; font-weight: 600; color: var(--muted); cursor: pointer; font-family: inherit; transition: .2s; }
    .btn-batal:hover { border-color: #D1D5DB; background: #F9FAFB; }

    .btn-simpan { padding: 10px 22px; border: none; border-radius: 10px; background: #FFE083; font-size: 13.5px; font-weight: 700; color: #6C5700; cursor: pointer; font-family: inherit; transition: .2s; }
    .btn-simpan:hover { background: #f5d040; color: #6C5700; }

    .modal-dropzone { border: 2px dashed var(--gold-border); border-radius: 10px; background: var(--gold-lt); padding: 22px; text-align: center; cursor: pointer; position: relative; }
    .modal-dropzone input { position: absolute; inset: 0; opacity: 0; width: 100%; height: 100%; cursor: pointer; }
    .modal-dropzone-icon { font-size: 30px; }
    .modal-dropzone-text { font-size: 12.5px; color: var(--muted); margin-top: 6px; font-weight: 500; }
    .modal-dropzone-hint { font-size: 11px; color: #9CA3AF; margin-top: 3px; }

    /* ── TOAST ── */
    .toast-notif { position: fixed; bottom: 36px; left: 50%; transform: translateX(-50%) translateY(16px); z-index: 9999; display: flex; align-items: flex-start; gap: 12px; padding: 14px 20px; border-radius: 14px; box-shadow: 0 8px 32px rgba(0,0,0,.18); font-size: 13.5px; font-weight: 600; max-width: 480px; width: max-content; opacity: 0; transition: opacity .3s ease, transform .3s ease; pointer-events: none; }
    .toast-notif.show { opacity: 1; transform: translateX(-50%) translateY(0); pointer-events: auto; }
    .toast-notif.toast-warning { background: #FFFBEB; border: 1.5px solid #FDE68A; color: #92400E; }
    .toast-notif-icon  { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
    .toast-notif-close { margin-left: auto; background: none; border: none; font-size: 16px; cursor: pointer; color: #92400E; padding: 0; line-height: 1; flex-shrink: 0; }

    .riwayat-file-link { font-size: 12px; color: var(--gold); text-decoration: none; display: flex; align-items: center; gap: 4px; }
    .riwayat-file-link:hover { text-decoration: underline; }

    .tabel-proposal table { min-width: 700px; }

    /* ── TOGGLE COLLAPSIBLE ── */
    .btn-toggle-proposal { width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 12px; background: #fff; border-radius: var(--radius); padding: 16px 20px; cursor: pointer; font-family: inherit; text-align: left; box-shadow: 0 2px 10px rgba(0,0,0,.05); border: 1px solid var(--border); transition: background .2s, border-color .2s; }
    .btn-toggle-proposal:hover { background: var(--gold-lt); border-color: var(--gold-border); }
    .btn-toggle-proposal.is-open { border-radius: var(--radius) var(--radius) 0 0; }

    .btn-toggle-proposal-left { display: flex; align-items: center; gap: 10px; min-width: 0; }
    .btn-toggle-proposal-icon  { width: 36px; height: 36px; border-radius: 9px; background: var(--gold-lt); display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 16px; }
    .btn-toggle-proposal-text  { font-size: 14px; font-weight: 700; color: var(--neutral); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .btn-toggle-proposal-count { font-size: 12px; font-weight: 600; color: var(--muted); }
    .btn-toggle-proposal-chevron { flex-shrink: 0; color: var(--muted); transition: transform .25s ease; display: flex; align-items: center; }
    .btn-toggle-proposal.is-open .btn-toggle-proposal-chevron { transform: rotate(180deg); color: var(--gold); }

    .proposal-collapse { overflow: hidden; max-height: 0; transition: max-height .3s ease; background: #fff; border: 1px solid var(--border); border-top: none; border-radius: 0 0 var(--radius) var(--radius); box-shadow: 0 2px 10px rgba(0,0,0,.05); }
    .proposal-collapse.is-open { max-height: 2000px; }
    .proposal-collapse-inner .tabel-header { border-top: none; }

    @media (max-width: 900px) {
        .bimb-grid { grid-template-columns: 1fr; }
        .upload-card { position: static; }
        .stat-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="bimb-wrap">

    {{-- ══ HERO ══ --}}
    <div class="bimb-hero">
        <div>
            <div class="bimb-hero-title">Riwayat Bimbingan</div>
            <div class="bimb-hero-sub">Kelola dan lihat riwayat bimbingan Anda</div>
            <button class="btn-tambah" onclick="bukaModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Riwayat Bimbingan
            </button>
        </div>
    </div>

    {{-- ══ STAT CARDS ══ --}}
    @php
        $totalBimbingan = $bimbingan->where('status_validasi', 'Valid')->count();
        $namaDP1 = optional($dosenList->first())->nama ?? '—';
        $namaDP2 = $dosenList->count() > 1 ? optional($dosenList->get(1))->nama : '—';
    @endphp
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon gold">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#C9A227" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
            </div>
            <div>
                <div class="stat-label">TOTAL BIMBINGAN</div>
                <div class="stat-value">{{ $totalBimbingan }} Kali</div>
                <div class="stat-sub">Bimbingan Valid</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#16A34A" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-1.342m-7.482 0c.956-.261 1.935-.5 2.94-.714a50.717 50.717 0 0 1 7.841 1.056" />
                </svg>
            </div>
            <div>
                <div class="stat-label">PEMBIMBING 1</div>
                <div class="stat-value" style="font-size:15px;margin-top:2px;">{{ $namaDP1 }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#1D4ED8" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-1.342m-7.482 0c.956-.261 1.935-.5 2.94-.714a50.717 50.717 0 0 1 7.841 1.056" />
                </svg>
            </div>
            <div>
                <div class="stat-label">PEMBIMBING 2</div>
                <div class="stat-value" style="font-size:15px;margin-top:2px;">{{ $namaDP2 }}</div>
            </div>
        </div>
    </div>

    {{-- ══ BANNER KELAYAKAN ══ --}}
    @php
        $seminarAktif = $seminar ?? null;
        $ttd1Layak  = $seminarAktif && ($seminarAktif->status_pembimbing1 ?? '') === 'layak';
        $ttd2Layak  = $seminarAktif && ($seminarAktif->status_pembimbing2 ?? '') === 'layak';
        $sudahLayak = $ttd1Layak;
        $nimMhs     = $user->nim_nid ?? null;
    @endphp

    @if($sudahLayak)
    <div class="banner-layak">
        <div class="banner-layak-left">
            <div class="banner-layak-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#16A34A" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div>
                <div style="margin-bottom:4px;">
                    <span class="banner-layak-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        BARU
                    </span>
                </div>
                <div class="banner-layak-title">Anda Dinyatakan Layak Mengikuti Seminar TA-1</div>
                <div class="banner-layak-sub">
                    Seluruh persyaratan bimbingan telah terpenuhi dan dosen pembimbing telah memberikan persetujuan untuk mengikuti Seminar Tugas Akhir 1.
                    @if(!$ttd2Layak)
                    <span style="color:#92400E;font-style:italic;"> (Pembimbing 2 belum TTD – surat sementara)</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="banner-layak-actions">
            <a href="{{ route('surat.pembimbing.download', $nimMhs) }}?preview=1" target="_blank" class="btn-lihat-surat">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                Lihat Surat
            </a>
            <a href="{{ route('surat.pembimbing.download', $nimMhs) }}" class="btn-unduh-pdf">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Unduh PDF
            </a>
        </div>
    </div>
    @endif

    {{-- ══ BANNER INFO ══ --}}
    <div class="banner-belum">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#D97706" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
        </svg>
        <span>Unggah proposal terlebih dahulu untuk ditinjau dosen pembimbing sebelum mengisi riwayat bimbingan. Pastikan file dalam format PDF untuk memudahkan proses preview oleh dosen.</span>
    </div>

    {{-- ══ GRID ══ --}}
    <div class="bimb-grid">

        {{-- ── UPLOAD DOKUMEN CARD ── --}}
        <div class="upload-card">
            <div class="upload-card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#C9A227" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                </svg>
                Upload Dokumen Pra Bimbingan
            </div>

            @if(session('proposal_success'))
            <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:8px;padding:10px 12px;font-size:12px;color:#15803D;margin-bottom:12px;">
                ✅ {{ session('proposal_success') }}
            </div>
            @endif

            <form action="{{ route('bimbingan.proposal.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">NIM</label>
                    <input type="text" class="form-control" value="{{ $user->nim_nid }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Mahasiswa</label>
                    <input type="text" class="form-control" value="{{ $user->nama ?? $user->name ?? '-' }}" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal_pengajuan" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Judul</label>
                    <input type="text" name="judul" class="form-control"
                        placeholder="Masukkan judul proposal lengkap..."
                        value="{{ $judulTA ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Dosen Pembimbing</label>
                    <select name="dosen_nid" class="form-control" required>
                        <option value="">-- Pilih Pembimbing --</option>
                        @forelse($dosenList as $dosen)
                        <option value="{{ $dosen->nim_nid_dosen }}">{{ $dosen->nama ?? $dosen->nim_nid_dosen }}</option>
                        @empty
                        <option value="" disabled>Belum ada dosen pembimbing ditetapkan</option>
                        @endforelse
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        File Dokumen
                        <span class="form-label-opt">(opsional, bisa lebih dari satu)</span>
                    </label>
                    <div class="dropzone" id="dropzoneMulti" onclick="document.getElementById('inputFileDokumen').click()">
                        <input type="file" id="inputFileDokumen" name="file_dokumen[]"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip,.rar"
                            multiple style="display:none;" onchange="handleMultiFile(this)">
                        <div class="dropzone-icon">☁️</div>
                        <div class="dropzone-text">Klik untuk unggah atau seret file</div>
                        <div class="dropzone-hint">PDF, Word, Excel, PPT, Foto, ZIP • Bisa pilih beberapa sekaligus<br>Maksimal ukuran file 10MB</div>
                    </div>
                    <div class="file-list" id="fileList"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        Link Dokumen
                        <span class="form-label-opt">(opsional, bisa lebih dari satu)</span>
                    </label>
                    <div class="link-list" id="linkList">
                        <div class="link-row">
                            <input type="text" name="links[]" class="form-control" placeholder="https://drive.google.com/... atau link lainnya">
                        </div>
                    </div>
                    <button type="button" class="btn-add-link" onclick="tambahLink()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Link
                    </button>
                </div>
                <button type="submit" class="btn-kirim">Kirim Dokumen</button>
            </form>
        </div>

        {{-- ── TABEL RIWAYAT BIMBINGAN ── --}}
        <div class="tabel-card">
            <div class="tabel-header">
                <div class="tabel-title">Riwayat Bimbingan</div>
                <div class="tabel-search">
                    <input type="text" class="search-input" id="searchInput"
                        placeholder="Cari topik, dosen, atau judul..."
                        oninput="filterTabel()">
                    <select class="filter-select" id="filterStatus" onchange="filterTabel()">
                        <option value="">Semua Status</option>
                        <option value="Valid">Valid</option>
                        <option value="Tidak Valid">Tidak Valid</option>
                        <option value="Validasi Bimbingan">Menunggu</option>
                    </select>
                    <button class="btn-reset" onclick="resetFilter()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        Reset
                    </button>
                </div>
            </div>
            <div class="tabel-scroll">
                <table id="tabelBimbingan">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Bimbingan Ke-</th>
                            <th>Tanggal</th>
                            <th>Judul</th>
                            <th>Topik Bimbingan</th>
                            <th>Dosen Pembimbing</th>
                            <th>Aksi</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bimbingan as $i => $b)
                        @php
                            $namaDosenRow = $dosenList->firstWhere('nim_nid_dosen', (string)$b->dosen_nid);
                            $searchData   = strtolower(
                                ($b->topik_bimbingan ?? '') . ' ' .
                                ($namaDosenRow->nama ?? '') . ' ' .
                                ($judulTA ?? '') . ' ' .
                                ($b->catatan_dosen ?? '')
                            );
                        @endphp
                        <tr data-search="{{ $searchData }}"
                            data-status="{{ $b->status_validasi }}"
                            data-foto="{{ $b->dokumentasi }}">
                            <td>{{ $i + 1 }}</td>
                            <td><span class="badge-pertemuan">{{ str_pad($b->pertemuan_ke, 2, '0', STR_PAD_LEFT) }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($b->tanggal_bimbingan)->translatedFormat('d M Y') }}</td>
                            <td style="max-width:160px;font-size:12px;">{{ Str::limit($judulTA, 40) }}</td>
                            <td style="max-width:180px;font-size:12px;">{{ Str::limit($b->topik_bimbingan, 50) }}</td>
                            <td style="font-size:12px;">{{ $namaDosenRow->nama ?? '—' }}</td>
                            <td>
                                <a href="#" class="btn-aksi" title="Lihat Foto"
                                    onclick="lihatDetail('{{ $b->dokumentasi }}'); return false;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </a>
                            </td>
                            <td>
                                @if($b->status_validasi == 'Valid')
                                <span class="status-badge status-dilihat">● Valid</span>
                                @elseif($b->status_validasi == 'Tidak Valid')
                                <span class="status-badge status-tidak-valid">● Tidak Valid</span>
                                @else
                                <span class="status-badge status-menunggu">● Menunggu</span>  {{-- tampil "Menunggu" tapi data-status = "Validasi Bimbingan" ✅ --}}
                                @endif
                            </td>
                            <td class="catatan-cell" title="{{ $b->catatan_dosen ?? '' }}">
                                {{ $b->catatan_dosen ?? '—' }}
                            </td>
                        </tr>
                        @empty
                        <tr class="empty-row">
                            <td colspan="9">
                                <div style="font-size:32px;margin-bottom:8px;">📋</div>
                                Belum ada riwayat bimbingan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ══ TOGGLE RIWAYAT UPLOAD DOKUMEN ══ --}}
    <div style="margin-top:20px;">
        <button type="button" class="btn-toggle-proposal" id="btnToggleProposal"
            onclick="toggleRiwayatProposal()" aria-expanded="false" aria-controls="collapseProposal">
            <span class="btn-toggle-proposal-left">
                <span class="btn-toggle-proposal-icon">📁</span>
                <span class="btn-toggle-proposal-text">Riwayat Upload Dokumen</span>
                <span class="btn-toggle-proposal-count">({{ $riwayatProposal->count() }})</span>
            </span>
            <span class="btn-toggle-proposal-chevron">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </span>
        </button>

        <div class="proposal-collapse" id="collapseProposal">
            <div class="proposal-collapse-inner">
                <div class="tabel-card tabel-proposal" style="border:none;border-radius:0;box-shadow:none;">
                    <div class="tabel-header">
                        <div class="tabel-title">
                            Riwayat Upload Dokumen Pra Bimbingan
                            <span style="font-size:12px;font-weight:600;color:var(--muted);margin-left:6px;">({{ $riwayatProposal->count() }} dokumen)</span>
                        </div>
                        <div class="tabel-search">
                            <input type="text" class="search-input" id="searchProposal"
                                placeholder="Cari judul atau dosen..."
                                oninput="filterProposal()">
                            <select class="filter-select" id="filterStatusProposal" onchange="filterProposal()">
                                <option value="">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                            <button class="btn-reset" onclick="resetFilterProposal()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                Reset
                            </button>
                        </div>
                    </div>
                    <div class="tabel-scroll">
                        <table id="tabelProposal" style="min-width:700px;">
                            <thead>
                                <tr>
                                    <th style="width:48px;">No.</th>
                                    <th style="width:110px;">Tanggal</th>
                                    <th>Judul</th>
                                    <th style="width:180px;">Dosen Pembimbing</th>
                                    <th style="width:110px;">Status</th>
                                    <th>File / Link</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatProposal as $idx => $rp)
                                @php
                                    $fileData    = json_decode($rp->file_proposal, true) ?? [];
                                    $namaDosenRP = $dosenList->firstWhere('nim_nid_dosen', (string)$rp->dosen_nid);
                                    $statusClass = match($rp->status) {
                                        'disetujui' => 'status-dilihat',
                                        'ditolak'   => 'status-tidak-valid',
                                        default     => 'status-menunggu',
                                    };
                                    $statusLabel = match($rp->status) {
                                        'disetujui' => 'Disetujui',
                                        'ditolak'   => 'Ditolak',
                                        default     => 'Pending',
                                    };
                                @endphp
                                <tr data-judul="{{ strtolower($rp->judul) }}"
                                    data-dosen="{{ strtolower($namaDosenRP->nama ?? '') }}"
                                    data-status="{{ $rp->status }}">
                                    <td>{{ $idx + 1 }}</td>
                                    <td style="white-space:nowrap;font-size:12px;">
                                        {{ \Carbon\Carbon::parse($rp->tanggal_pengajuan)->translatedFormat('d M Y') }}
                                    </td>
                                    <td style="font-size:12px;">{{ $rp->judul }}</td>
                                    <td style="font-size:12px;">{{ $namaDosenRP->nama ?? '—' }}</td>
                                    <td><span class="status-badge {{ $statusClass }}">● {{ $statusLabel }}</span></td>
                                    <td>
                                        <div style="display:flex;flex-direction:column;gap:4px;">
                                            @foreach($fileData['files'] ?? [] as $f)
                                            <a href="{{ asset('uploads/proposal/'.$f) }}" target="_blank" class="riwayat-file-link">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                </svg>
                                                {{ Str::limit($f, 35) }}
                                            </a>
                                            @endforeach
                                            @foreach($fileData['links'] ?? [] as $l)
                                            <a href="{{ $l }}" target="_blank" class="riwayat-file-link">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                                                </svg>
                                                {{ Str::limit($l, 40) }}
                                            </a>
                                            @endforeach
                                            @if(empty($fileData['files']) && empty($fileData['links']))
                                            <span style="font-size:12px;color:var(--muted);">—</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr class="empty-row">
                                    <td colspan="6">
                                        <div style="font-size:32px;margin-bottom:8px;">📁</div>
                                        Belum ada riwayat upload dokumen
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TOAST --}}
    <div class="toast-notif toast-warning" id="toastNotif">
        <span class="toast-notif-icon">⚠️</span>
        <span id="toastMsg">Pesan notifikasi</span>
        <button class="toast-notif-close" onclick="tutupToast()">×</button>
    </div>

</div>

{{-- ══ MODAL TAMBAH BIMBINGAN ══ --}}
<div class="modal-overlay" id="modalOverlay" onclick="tutupModalLuar(event)">
    <div class="modal-box">
        <div class="modal-head">
            <button class="modal-close" onclick="tutupModal()">×</button>
            <h5>Tambah Riwayat Bimbingan</h5>
            <p>Lengkapi data bimbingan tugas akhir Anda.</p>
        </div>
        <form action="{{ route('bimbingan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="modal-row" style="margin-bottom:14px;">
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Nama Mahasiswa</label>
                        <input type="text" class="form-control" value="{{ $user->nama ?? $user->name ?? '-' }}" readonly>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" value="{{ $user->nim_nid }}" readonly>
                    </div>
                </div>
                <div class="modal-row" style="margin-bottom:14px;">
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Tanggal Bimbingan</label>
                        <input type="date" name="tanggal_bimbingan" class="form-control" required>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Bimbingan Ke-</label>
                        <input type="number" name="pertemuan_ke" class="form-control" placeholder="Contoh: 3" min="1" required>
                    </div>
                </div>
                <div class="modal-row full" style="margin-bottom:14px;">
                    <div>
                        <label class="form-label">Dosen Pembimbing</label>
                        <select name="dosen_nid" class="form-control" required>
                            <option value="">-- Pilih Pembimbing --</option>
                            @forelse($semuaDosen as $dosen)
                            <option value="{{ $dosen->nim_nid_dosen }}">{{ $dosen->nama }}</option>
                            @empty
                            <option value="" disabled>Belum ada dosen pembimbing ditetapkan</option>
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="modal-row full" style="margin-bottom:14px;">
                    <div>
                        <label class="form-label">Judul Tugas Akhir</label>
                        <input type="text" class="form-control" value="{{ $judulTA }}" readonly style="background:#F9FAFB;">
                    </div>
                </div>
                <div class="modal-row full" style="margin-bottom:14px;">
                    <div>
                        <label class="form-label">Topik Bimbingan</label>
                        <textarea name="topik_bimbingan" class="form-control" rows="3"
                            placeholder="Jelaskan poin-poin diskusi bimbingan..." required style="resize:vertical;"></textarea>
                    </div>
                </div>
                <div class="modal-row full" style="margin-bottom:0;">
                    <div>
                        <label class="form-label">Dokumentasi Foto</label>
                        <div class="modal-dropzone" id="modalDropzone">
                            <input type="file" name="dokumentasi" accept=".jpg,.jpeg,.png"
                                onchange="updateDropzone(this,'modalDropzone')">
                            <div class="modal-dropzone-icon">🖼️</div>
                            <div class="modal-dropzone-text" id="modalDropzoneText">Klik atau seret foto dokumentasi untuk diunggah</div>
                            <div class="modal-dropzone-hint">Format: JPG, PNG (Maks. 5MB)</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn-batal" onclick="tutupModal()">Batal</button>
                <button type="submit" class="btn-simpan">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ══ MODAL LIHAT FOTO ══ --}}
<div class="modal-overlay" id="modalFoto" onclick="tutupModalFoto(event)">
    <div class="modal-box" style="max-width:420px;">
        <div class="modal-head">
            <button class="modal-close" onclick="document.getElementById('modalFoto').classList.remove('show'); document.body.style.overflow='';">×</button>
            <h5>Dokumentasi Bimbingan</h5>
            <p>Foto dokumentasi saat bimbingan berlangsung.</p>
        </div>
        <div class="modal-body" style="text-align:center;padding:24px;">
            <img id="fotoPreview" src="" alt="Foto Dokumentasi"
                style="width:100%;border-radius:12px;object-fit:cover;display:none;">
            <div id="noFotoMsg" style="display:none;padding:32px 0;">
                <div style="font-size:48px;margin-bottom:12px;">📷</div>
                <p style="color:#9CA3AF;font-size:14px;margin:0;">Tidak ada dokumentasi foto untuk bimbingan ini.</p>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn-simpan"
                onclick="document.getElementById('modalFoto').classList.remove('show'); document.body.style.overflow='';"
                style="display:inline-flex;align-items:center;gap:7px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali
            </button>
        </div>
    </div>
</div>

<script>
    function bukaModal() {
        document.getElementById('modalOverlay').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function tutupModal() {
        document.getElementById('modalOverlay').classList.remove('show');
        document.body.style.overflow = '';
    }

    function tutupModalLuar(e) {
        if (e.target === document.getElementById('modalOverlay')) tutupModal();
    }

    function tutupModalFoto(e) {
        if (e.target === document.getElementById('modalFoto')) {
            document.getElementById('modalFoto').classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    function lihatDetail(foto) {
        const modal = document.getElementById('modalFoto');
        const img   = document.getElementById('fotoPreview');
        const noMsg = document.getElementById('noFotoMsg');
        if (foto && foto !== '' && foto !== 'null') {
            img.src = '/uploads/bimbingan/' + foto;
            img.style.display = 'block';
            noMsg.style.display = 'none';
        } else {
            img.style.display = 'none';
            noMsg.style.display = 'block';
        }
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function updateDropzone(input, id) {
        const el = document.getElementById(id === 'dropzoneUpload' ? 'dropzoneUploadText' : 'modalDropzoneText');
        if (input.files.length > 0) el.textContent = '✅ ' + input.files[0].name;
    }

    // ── FILTER TABEL BIMBINGAN (FIXED) ──
    function filterTabel() {
        const q      = document.getElementById('searchInput').value.toLowerCase().trim();
        const status = document.getElementById('filterStatus').value;
        document.querySelectorAll('#tabelBimbingan tbody tr:not(.empty-row)').forEach(function(row) {
            const matchQ = !q || (row.dataset.search || '').includes(q);
            const matchS = !status || row.dataset.status === status;
            row.style.display = (matchQ && matchS) ? '' : 'none';
        });
    }

    function resetFilter() {
        document.getElementById('searchInput').value  = '';
        document.getElementById('filterStatus').value = '';
        filterTabel();
    }

    // ── FILTER TABEL PROPOSAL (FIXED) ──
    function filterProposal() {
        const q      = document.getElementById('searchProposal').value.toLowerCase().trim();
        const status = document.getElementById('filterStatusProposal').value;
        document.querySelectorAll('#tabelProposal tbody tr:not(.empty-row)').forEach(function(row) {
            const searchStr = ((row.dataset.judul || '') + ' ' + (row.dataset.dosen || ''));
            const matchQ = !q || searchStr.includes(q);
            const matchS = !status || row.dataset.status === status;
            row.style.display = (matchQ && matchS) ? '' : 'none';
        });
    }

    function resetFilterProposal() {
        document.getElementById('searchProposal').value        = '';
        document.getElementById('filterStatusProposal').value  = '';
        filterProposal();
    }

    function toggleRiwayatProposal() {
        const btn      = document.getElementById('btnToggleProposal');
        const collapse = document.getElementById('collapseProposal');
        const isOpen   = collapse.classList.contains('is-open');
        if (isOpen) {
            collapse.classList.remove('is-open');
            btn.classList.remove('is-open');
            btn.setAttribute('aria-expanded', 'false');
        } else {
            collapse.classList.add('is-open');
            btn.classList.add('is-open');
            btn.setAttribute('aria-expanded', 'true');
        }
    }

    function tampilToast(pesan) {
        const toast = document.getElementById('toastNotif');
        document.getElementById('toastMsg').textContent = pesan;
        toast.classList.add('show');
        clearTimeout(window._toastTimer);
        window._toastTimer = setTimeout(function() { toast.classList.remove('show'); }, 6000);
    }

    function tutupToast() {
        document.getElementById('toastNotif').classList.remove('show');
    }

    let dt = new DataTransfer();

    function getFileIcon(name) {
        const ext = name.split('.').pop().toLowerCase();
        const map = { pdf:'📄', doc:'📝', docx:'📝', xls:'📊', xlsx:'📊', ppt:'📑', pptx:'📑', jpg:'🖼️', jpeg:'🖼️', png:'🖼️', zip:'🗜️', rar:'🗜️' };
        return map[ext] || '📎';
    }

    function renderFileList() {
        const list = document.getElementById('fileList');
        list.innerHTML = '';
        Array.from(dt.files).forEach(function(file, idx) {
            const item = document.createElement('div');
            item.className = 'file-item';
            item.innerHTML = `
                <span class="file-item-icon">${getFileIcon(file.name)}</span>
                <span class="file-item-name" title="${file.name}">${file.name}</span>
                <button type="button" class="file-item-remove" onclick="hapusFile(${idx})" title="Hapus">×</button>
            `;
            list.appendChild(item);
        });
        document.getElementById('inputFileDokumen').files = dt.files;
    }

    function handleMultiFile(input) {
        const maxSize = 10 * 1024 * 1024;
        let adaYangGede = false;
        Array.from(input.files).forEach(function(f) {
            if (f.size > maxSize) { adaYangGede = true; }
            else { dt.items.add(f); }
        });
        if (adaYangGede) tampilToast('Beberapa file melebihi 10MB dan tidak ditambahkan.');
        renderFileList();
    }

    function hapusFile(idx) {
        const dtBaru = new DataTransfer();
        Array.from(dt.files).forEach(function(f, i) { if (i !== idx) dtBaru.items.add(f); });
        dt = dtBaru;
        renderFileList();
    }

    function tambahLink() {
        const list = document.getElementById('linkList');
        const row  = document.createElement('div');
        row.className = 'link-row';
        row.innerHTML = `
            <input type="text" name="links[]" class="form-control" placeholder="https://drive.google.com/... atau link lainnya">
            <button type="button" class="btn-remove-link" onclick="hapusLink(this)" title="Hapus">×</button>
        `;
        list.appendChild(row);
        row.querySelector('input').focus();
    }

    function hapusLink(btn) {
        btn.closest('.link-row').remove();
    }
</script>

@endsection