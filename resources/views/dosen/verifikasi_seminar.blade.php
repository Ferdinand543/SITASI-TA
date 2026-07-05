@extends('layouts.app')

@section('title', 'Verifikasi Kelayakan Akademik')

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

    .wrap { background: var(--bg); min-height: 100vh; padding-bottom: 40px; }

    .breadcrumb { font-size: 12px; color: var(--muted); margin-bottom: 24px; display: flex; align-items: center; gap: 6px; }
    .breadcrumb a { color: var(--gold); text-decoration: none; font-weight: 600; }
    .breadcrumb a:hover { text-decoration: underline; }

    .page-title { font-size: 22px; font-weight: 800; color: var(--neutral); margin-bottom: 6px; }
    .page-subtitle { font-size: 13px; color: var(--muted); margin-bottom: 28px; }

    .card {
        background: var(--white); border-radius: var(--radius);
        border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(0,0,0,.05);
        padding: 24px 28px; margin-bottom: 20px;
    }

    .card-label {
        font-size: 11px; font-weight: 700; color: var(--muted);
        text-transform: uppercase; letter-spacing: .5px;
        margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
    }
    .card-label svg { flex-shrink: 0; }

    .data-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px 32px; }
    .data-item-label { font-size: 11px; color: var(--muted); font-weight: 600; margin-bottom: 3px; text-transform: uppercase; letter-spacing: .3px; }
    .data-item-value { font-size: 14px; font-weight: 700; color: var(--neutral); }

    .judul-box {
        background: var(--gold-lt); border: 1px solid var(--gold-border);
        border-radius: 10px; padding: 14px 16px;
        font-size: 14px; font-weight: 700; color: var(--neutral);
        font-style: italic; line-height: 1.5;
    }

    .pernyataan-wrap { display: flex; align-items: flex-start; gap: 14px; padding: 4px 0; }
    .pernyataan-wrap input[type="checkbox"] { width: 18px; height: 18px; margin-top: 2px; accent-color: var(--gold); flex-shrink: 0; cursor: pointer; }
    .pernyataan-text { font-size: 13px; color: var(--muted); line-height: 1.65; }

    .action-row { display: flex; justify-content: flex-end; gap: 12px; margin-top: 28px; }

    .btn-back {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 11px 22px; border-radius: 10px; font-size: 13px; font-weight: 700;
        background: var(--white); border: 1.5px solid var(--border);
        color: var(--muted); text-decoration: none; transition: .2s; cursor: pointer;
    }
    .btn-back:hover { border-color: var(--gold); color: var(--gold); }

    .btn-submit {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 26px; border-radius: 10px; font-size: 13px; font-weight: 800;
        background: #FFE083; border: none; color: #6C5700;
        cursor: pointer; transition: background .2s; opacity: .5; pointer-events: none;
    }
    .btn-submit.active { opacity: 1; pointer-events: auto; }
    .btn-submit.active:hover { background: #f5d040; color: #6C5700; }

    /* ── MODAL OVERLAY (umum) ── */
    .modal-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .modal-overlay.show { display: flex; }

    /* ── MODAL BOX ── */
    .modal-box {
        background: #fff;
        border-radius: 20px;
        padding: 28px 28px 24px;
        width: 100%;
        max-width: 440px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.18);
        animation: slideUpModal .25s ease;
        position: relative;
    }
    @keyframes slideUpModal {
        from { transform: translateY(20px); opacity: 0; }
        to   { transform: translateY(0);    opacity: 1; }
    }

    .modal-close {
        position: absolute; top: 18px; right: 18px;
        width: 28px; height: 28px; border-radius: 50%;
        background: #F3F4F6; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; color: var(--muted); transition: .2s;
    }
    .modal-close:hover { background: #E5E7EB; color: var(--neutral); }

    .modal-title {
        font-size: 13px; font-weight: 800; color: var(--neutral);
        text-transform: uppercase; letter-spacing: .8px;
        margin-bottom: 18px;
    }

    /* ── INFO MAHASISWA DI MODAL PIN ── */
    .modal-mhs-card {
        background: #F8FAFC;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .modal-mhs-avatar {
        width: 44px; height: 44px; border-radius: 50%;
        background: var(--gold-lt); border: 2px solid var(--gold-border);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; color: var(--gold); font-size: 1.1rem;
    }
    .modal-mhs-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px 20px;
        flex: 1;
    }
    .modal-mhs-label { font-size: 10px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .3px; margin-bottom: 2px; }
    .modal-mhs-value { font-size: 13px; font-weight: 700; color: var(--neutral); }

    /* ── PIN INPUT ── */
    .pin-section-label {
        font-size: 11.5px; font-weight: 700; color: var(--gold);
        display: flex; align-items: center; gap: 6px;
        margin-bottom: 10px;
    }
    .pin-input-label {
        font-size: 12.5px; font-weight: 600; color: var(--neutral);
        margin-bottom: 10px;
    }
    .pin-inputs {
        display: flex;
        gap: 8px;
        margin-bottom: 10px;
    }
    .pin-inputs input {
        width: 44px; height: 50px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        text-align: center;
        font-size: 18px; font-weight: 800;
        color: var(--neutral);
        font-family: inherit;
        outline: none;
        transition: border-color .2s;
        background: #FAFAFA;
    }
    .pin-inputs input:focus { border-color: var(--gold); background: #fff; }
    .pin-inputs input.filled { border-color: var(--gold); background: var(--gold-lt); }

    .pin-hint {
        display: flex; align-items: flex-start; gap: 6px;
        font-size: 11.5px; color: var(--muted); line-height: 1.5;
        margin-bottom: 20px;
    }

    .pin-error-box {
        display: none;
        background: #FFF1F2; border: 1px solid #FECDD3;
        border-radius: 8px; padding: 10px 14px;
        font-size: 12.5px; color: #E11D48;
        margin-bottom: 16px;
    }

    .btn-modal-submit {
        width: 100%; padding: 13px;
        border-radius: 12px; border: none;
        background: #FFE083; color: #6C5700;
        font-size: 13px; font-weight: 800;
        cursor: pointer; transition: background .2s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        font-family: inherit;
        opacity: .5; pointer-events: none;
    }
    .btn-modal-submit.active { opacity: 1; pointer-events: auto; }
    .btn-modal-submit.active:hover { background: #f5d040; color: #6C5700; }
    .btn-modal-submit:disabled { opacity: .6; pointer-events: none; }

    /* ═══════════════════════════════════════════
       MODAL SUKSES — TANDA TANGAN BERHASIL
    ═══════════════════════════════════════════ */
    .modal-box-sukses { max-width: 700px; padding: 0; }

    .sukses-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 20px 28px; border-bottom: 1px solid var(--border);
    }
    .sukses-header h3 {
        font-size: 16px; font-weight: 800; color: var(--gold); margin: 0;
    }

    .sukses-body {
        padding: 26px 28px;
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 28px;
    }

    .qr-panel {
        background: var(--gold-lt);
        border: 1.5px solid var(--gold-border);
        border-radius: 14px;
        padding: 14px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    .qr-canvas-wrap {
        width: 160px; height: 160px;
        background: #fff;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
    }
    .qr-canvas-wrap canvas, .qr-canvas-wrap img { width: 100% !important; height: 100% !important; }
    .qr-caption {
        font-size: 11px; font-weight: 700; color: var(--neutral);
        text-align: center; line-height: 1.4;
    }

    .info-panel { display: flex; flex-direction: column; gap: 14px; }

    .status-pill {
        display: inline-flex; align-items: center; gap: 6px;
        background: #F0FDF4; color: #16A34A;
        border: 1px solid #BBF7D0;
        border-radius: 99px; padding: 4px 14px;
        font-size: 11px; font-weight: 800; letter-spacing: .4px;
        width: fit-content;
    }
    .status-pill::before { content:''; width:6px; height:6px; border-radius:50%; background:#16A34A; }

    .info-row-label { font-size: 11px; color: var(--muted); font-weight: 600; margin-bottom: 2px; }
    .info-row-value { font-size: 14px; font-weight: 800; color: var(--neutral); }

    .fingerprint-box {
        background: #F8FAFC; border: 1px solid var(--border);
        border-radius: 8px; padding: 10px 12px;
        font-family: 'Courier New', monospace;
        font-size: 10.5px; color: var(--muted);
        word-break: break-all; line-height: 1.5;
    }

    .sukses-footer {
        padding: 16px 28px 26px;
    }
    .info-banner {
        display: flex; align-items: flex-start; gap: 10px;
        background: var(--gold-lt); border: 1px solid var(--gold-border);
        border-radius: 10px; padding: 12px 14px;
        font-size: 12.5px; color: #735C00; line-height: 1.55;
        margin-bottom: 14px;
    }
    .info-banner svg { flex-shrink: 0; margin-top: 1px; }

    .btn-next-step {
        width: 100%; padding: 13px;
        border-radius: 10px; border: none;
        background: #FFE083; color: #6C5700;
        font-size: 13px; font-weight: 800;
        cursor: pointer; transition: background .2s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-next-step:hover { background: #f5d040; color: #6C5700; }

    @media (max-width: 640px) {
        .data-grid { grid-template-columns: 1fr; }
        .action-row { flex-direction: column-reverse; }
        .btn-back, .btn-submit { width: 100%; justify-content: center; }
        .modal-mhs-grid { grid-template-columns: 1fr; }
        .pin-inputs input { width: 40px; height: 46px; }
        .sukses-body { grid-template-columns: 1fr; }
        .qr-panel { margin: 0 auto; }
    }
</style>

<div class="wrap">

    {{-- BREADCRUMB --}}
    <div class="breadcrumb">
        <a href="{{ route('dosen.bimbingan.index') }}">TA Approvals</a>
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
        <a href="{{ route('dosen.bimbingan.detail', $nim) }}">Detail Bimbingan</a>
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
        <span>Konfirmasi Kelayakan Seminar</span>
    </div>

    <div class="page-title">Verifikasi Kelayakan Akademik</div>
    <div class="page-subtitle">Lakukan verifikasi data dan otentikasi PIN untuk penerbitan persetujuan seminar.</div>

    {{-- DATA IDENTITAS --}}
    <div class="card">
        <div class="card-label">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#C9A227" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
            Data Identitas Mahasiswa
            <span style="font-size:10px;color:var(--muted);font-weight:400;text-transform:none;">Profil akademik pemohon seminar hasil/akhir</span>
        </div>
        <div class="data-grid">
            <div class="data-item">
                <div class="data-item-label">Nama Lengkap</div>
                <div class="data-item-value">{{ $mahasiswa->nama ?? '-' }}</div>
            </div>
            <div class="data-item">
                <div class="data-item-label">Nomor Induk Mahasiswa</div>
                <div class="data-item-value">{{ $mahasiswa->nim_nid ?? '-' }}</div>
            </div>
            <div class="data-item">
                <div class="data-item-label">SKS</div>
                <div class="data-item-value">{{ $seminar->total_sks ?? '-' }}</div>
            </div>
            <div class="data-item">
                <div class="data-item-label">Nilai D/E</div>
                <div class="data-item-value">{{ $seminar->sks_nilai_d ?? '-' }}</div>
            </div>
            <div class="data-item">
                <div class="data-item-label">Tahun Akademik</div>
                <div class="data-item-value">{{ $mahasiswa->tahun_akademik ?? date('Y') . '/' . (date('Y') + 1) }}</div>
            </div>
        </div>
    </div>

    {{-- JUDUL TA --}}
    <div class="card">
        <div class="card-label">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#C9A227" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
            Judul Tugas Akhir / Skripsi
            <span style="font-size:10px;color:var(--muted);font-weight:400;text-transform:none;">Informasi topik penelitian yang diajukan</span>
        </div>
        <div class="judul-box">"{{ $judulTA }}"</div>
    </div>

    {{-- PERNYATAAN --}}
    <div class="card">
        <div class="card-label">Pernyataan Persetujuan Dosen</div>
        <div class="pernyataan-wrap">
            <input type="checkbox" id="checkPersetujuan" onchange="toggleSubmit(this)">
            <label for="checkPersetujuan" class="pernyataan-text">
                Dengan ini saya menyatakan bahwa berdasarkan hasil evaluasi proses bimbingan dan penelaahan terhadap
                laporan Tugas Akhir yang diajukan, mahasiswa yang bersangkutan telah memenuhi persyaratan akademik
                serta layak untuk diikutsertakan pada Seminar Tugas Akhir 1.
            </label>
        </div>
    </div>

    {{-- ACTION --}}
    <div class="action-row">
        <a href="{{ route('dosen.bimbingan.detail', $nim) }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            Kembali
        </a>
        <button type="button" class="btn-submit" id="btnSubmit" onclick="bukaModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
            Verifikasi &amp; Tandatangani
        </button>
    </div>

</div>

{{-- ═══════════════════════════════════════════
     MODAL VERIFIKASI PIN
═══════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalVerifikasi">
    <div class="modal-box">

        <button class="modal-close" onclick="tutupModal()">✕</button>

        <div class="modal-title">Persetujuan Kelayakan Seminar</div>

        {{-- Info Mahasiswa --}}
        <div class="modal-mhs-card">
            <div class="modal-mhs-avatar">
                @if(!empty($mahasiswa->foto))
                    <img src="{{ asset('storage/' . $mahasiswa->foto) }}"
                         style="width:44px;height:44px;border-radius:50%;object-fit:cover;">
                @else
                    <i class="fa-solid fa-user"></i>
                @endif
            </div>
            <div class="modal-mhs-grid">
                <div>
                    <div class="modal-mhs-label">Nama Lengkap</div>
                    <div class="modal-mhs-value">{{ $mahasiswa->nama ?? '-' }}</div>
                </div>
                <div>
                    <div class="modal-mhs-label">NIM</div>
                    <div class="modal-mhs-value">{{ $mahasiswa->nim_nid ?? '-' }}</div>
                </div>
                <div>
                    <div class="modal-mhs-label">SKS</div>
                    <div class="modal-mhs-value">{{ $seminar->total_sks ?? '-' }}</div>
                </div>
                <div>
                    <div class="modal-mhs-label">Nilai D/E</div>
                    <div class="modal-mhs-value">{{ $seminar->sks_nilai_d ?? '-' }}</div>
                </div>
                <div>
                    <div class="modal-mhs-label">Tahun Akademik</div>
                    <div class="modal-mhs-value">{{ $mahasiswa->tahun_akademik ?? date('Y') . '/' . (date('Y') + 1) }}</div>
                </div>
            </div>
        </div>

        {{-- Error box (diisi via JS kalau PIN salah / gagal) --}}
        <div class="pin-error-box" id="pinErrorBox"></div>

        {{-- PIN Input --}}
        <form id="formPin" onsubmit="return false;">
            <div class="pin-section-label">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                Verifikasi PIN
            </div>

            <div class="pin-input-label">Masukkan PIN Elektronik</div>

            <div class="pin-inputs" id="pinInputs">
                <input type="password" maxlength="1" inputmode="numeric" pattern="[0-9]" class="pin-digit" data-index="0">
                <input type="password" maxlength="1" inputmode="numeric" pattern="[0-9]" class="pin-digit" data-index="1">
                <input type="password" maxlength="1" inputmode="numeric" pattern="[0-9]" class="pin-digit" data-index="2">
                <input type="password" maxlength="1" inputmode="numeric" pattern="[0-9]" class="pin-digit" data-index="3">
                <input type="password" maxlength="1" inputmode="numeric" pattern="[0-9]" class="pin-digit" data-index="4">
                <input type="password" maxlength="1" inputmode="numeric" pattern="[0-9]" class="pin-digit" data-index="5">
            </div>

            <div class="pin-hint">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>
                PIN adalah kode rahasia 6 digit yang telah didaftarkan untuk akun dosen.
            </div>

            <button type="button" class="btn-modal-submit" id="btnModalSubmit" onclick="submitPin()">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <span id="btnModalSubmitText">Verifikasi &amp; Terbitkan QR Code TTD</span>
            </button>
        </form>

    </div>
</div>

{{-- ═══════════════════════════════════════════
     MODAL SUKSES — TANDA TANGAN DIGITAL
═══════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalSukses">
    <div class="modal-box modal-box-sukses">

        <div class="sukses-header">
            <h3>Tanda Tangan Berhasil Dibuat</h3>
            <button class="modal-close" onclick="tutupModalSukses()">✕</button>
        </div>

        <div class="sukses-body">

            {{-- Panel QR --}}
            <div class="qr-panel">
                <div class="qr-canvas-wrap" id="qrCanvasWrap"></div>
                <div class="qr-caption" id="qrCaption">Tanda Tangan Digital Dosen<br>Pembimbing</div>
            </div>

            {{-- Panel Info --}}
            <div class="info-panel">
                <span class="status-pill">STATUS: LAYAK</span>

                <div>
                    <div class="info-row-label">Nama Dosen</div>
                    <div class="info-row-value" id="infoNamaDosen">-</div>
                </div>

                <div>
                    <div class="info-row-label">NIP</div>
                    <div class="info-row-value" id="infoNip">-</div>
                </div>

                <div>
                    <div class="info-row-label">Tanggal</div>
                    <div class="info-row-value" id="infoTanggal">-</div>
                </div>

                <div>
                    <div class="info-row-label">Fingerprint (SHA-256)</div>
                    <div class="fingerprint-box" id="infoFingerprint">-</div>
                </div>
            </div>

        </div>

        <div class="sukses-footer">
            <div class="info-banner" id="suksesInfoBanner">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#735C00" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>
                <span id="suksesInfoText">Dokumen telah ditandatangani oleh Pembimbing 1. Langkah selanjutnya adalah meneruskan berkas ke Dosen Pembimbing 2 untuk persetujuan akhir.</span>
            </div>

            <button class="btn-next-step" id="btnNextStep" onclick="prosesLangkahSelanjutnya()">
                <span id="btnNextStepText">KIRIM SURAT KE PEMBIMBING 2</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </button>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
const NIM_MAHASISWA  = @json($nim);
const PROPOSAL_ID    = @json($proposal->id ?? 0);
const URL_SUBMIT_PIN = @json(route('dosen.bimbingan.verifikasi.seminar.submit', ['nim' => $nim, 'proposal_id' => $proposal->id ?? 0]));
const URL_DETAIL     = @json(route('dosen.bimbingan.detail', $nim));
const CSRF_TOKEN      = @json(csrf_token());

// ── Toggle tombol submit utama ──
function toggleSubmit(checkbox) {
    const btn = document.getElementById('btnSubmit');
    checkbox.checked ? btn.classList.add('active') : btn.classList.remove('active');
}

// ── Buka / tutup modal PIN ──
function bukaModal() {
    document.getElementById('modalVerifikasi').classList.add('show');
    document.getElementById('pinErrorBox').style.display = 'none';
    document.querySelectorAll('.pin-digit').forEach(inp => {
        inp.value = '';
        inp.classList.remove('filled');
    });
    document.getElementById('btnModalSubmit').classList.remove('active');
    document.querySelector('.pin-digit').focus();
}

function tutupModal() {
    document.getElementById('modalVerifikasi').classList.remove('show');
}

function tutupModalSukses() {
    document.getElementById('modalSukses').classList.remove('show');
}

document.getElementById('modalVerifikasi').addEventListener('click', function(e) {
    if (e.target === this) tutupModal();
});
document.getElementById('modalSukses').addEventListener('click', function(e) {
    if (e.target === this) tutupModalSukses();
});

// ── PIN Input Logic ──
document.querySelectorAll('.pin-digit').forEach(function(input, idx, all) {
    input.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value) {
            this.classList.add('filled');
            if (idx < all.length - 1) all[idx + 1].focus();
        } else {
            this.classList.remove('filled');
        }
        cekPinLengkap();
    });

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && !this.value && idx > 0) {
            all[idx - 1].focus();
            all[idx - 1].value = '';
            all[idx - 1].classList.remove('filled');
            cekPinLengkap();
        }
    });

    input.addEventListener('paste', function(e) {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
        pasted.split('').forEach((char, i) => {
            if (all[i]) {
                all[i].value = char;
                all[i].classList.add('filled');
            }
        });
        const next = Math.min(pasted.length, all.length - 1);
        all[next].focus();
        cekPinLengkap();
    });
});

function cekPinLengkap() {
    const digits = document.querySelectorAll('.pin-digit');
    const pinVal = Array.from(digits).map(d => d.value).join('');
    const btnModal = document.getElementById('btnModalSubmit');
    btnModal.classList.toggle('active', pinVal.length === 6);
}

function ambilPinValue() {
    return Array.from(document.querySelectorAll('.pin-digit')).map(d => d.value).join('');
}

// ── Submit PIN via AJAX ──
function submitPin() {
    const pin = ambilPinValue();
    if (pin.length !== 6) return;

    const btn = document.getElementById('btnModalSubmit');
    const btnText = document.getElementById('btnModalSubmitText');
    const errBox = document.getElementById('pinErrorBox');

    errBox.style.display = 'none';
    btn.disabled = true;
    btnText.textContent = 'Memverifikasi...';

    fetch(URL_SUBMIT_PIN, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ pin: pin })
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btnText.innerHTML = 'Verifikasi &amp; Terbitkan QR Code TTD';

        if (!data.success) {
            errBox.textContent = data.message || 'Verifikasi gagal. Silakan coba lagi.';
            errBox.style.display = 'block';
            document.querySelectorAll('.pin-digit').forEach(inp => {
                inp.value = '';
                inp.classList.remove('filled');
            });
            document.querySelector('.pin-digit').focus();
            cekPinLengkap();
            return;
        }

        tutupModal();
        tampilkanModalSukses(data);
    })
    .catch(() => {
        btn.disabled = false;
        btnText.innerHTML = 'Verifikasi &amp; Terbitkan QR Code TTD';
        errBox.textContent = 'Terjadi kesalahan koneksi. Silakan coba lagi.';
        errBox.style.display = 'block';
    });
}

// ── Tampilkan modal sukses dengan data dari server ──
function tampilkanModalSukses(data) {
    document.getElementById('infoNamaDosen').textContent  = data.nama_dosen || '-';
    document.getElementById('infoNip').textContent        = data.nip || '-';
    document.getElementById('infoTanggal').textContent    = data.tanggal || '-';
    document.getElementById('infoFingerprint').textContent = data.fingerprint || '-';

    const isPembimbing1 = data.urutan == 1;

    document.getElementById('qrCaption').innerHTML = isPembimbing1
        ? 'Tanda Tangan Digital Dosen<br>Pembimbing 1'
        : 'Tanda Tangan Digital Dosen<br>Pembimbing 2';

    document.getElementById('suksesInfoText').textContent = isPembimbing1
        ? 'Dokumen telah ditandatangani oleh Pembimbing 1. Langkah selanjutnya adalah meneruskan berkas ke Dosen Pembimbing 2 untuk persetujuan akhir.'
        : 'Dokumen telah ditandatangani oleh Pembimbing 2. Seluruh proses persetujuan kelayakan seminar telah selesai.';

    document.getElementById('btnNextStepText').textContent = isPembimbing1
        ? 'KIRIM SURAT KE PEMBIMBING 2'
        : 'SELESAI';

    // ── Render QR code dari qr_url ──
    const qrWrap = document.getElementById('qrCanvasWrap');
    qrWrap.innerHTML = '';
    new QRCode(qrWrap, {
        text: data.qr_url || '',
        width: 160,
        height: 160,
        colorDark: '#1E293B',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M
    });

    document.getElementById('modalSukses').classList.add('show');
}

// ── Tombol aksi di modal sukses ──
function prosesLangkahSelanjutnya() {
    const isPembimbing1 = document.getElementById('btnNextStepText').textContent.includes('PEMBIMBING 2');

    if (!isPembimbing1) {
        // Pembimbing 2 sudah TTD → tawarkan download PDF
        if (confirm('Seluruh TTD selesai! Download surat pernyataan sekarang?')) {
            window.open('/surat-pembimbing/{{ $nim }}/download', '_blank');
        }
    }
    window.location.href = URL_DETAIL;
}
</script>

@endsection