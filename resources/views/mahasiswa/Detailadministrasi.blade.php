@extends('layouts.app')

@section('title', 'Detail Administrasi Seminar')

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

    .detail-wrap {
        background: var(--bg);
        min-height: 100vh;
        padding-bottom: 60px;
    }

    .detail-hero {
        background-image: url('{{ asset("images/1.jpeg") }}');
        background-size: cover;
        background-position: center right;
        border-radius: 20px;
        padding: 36px 40px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        min-height: 160px;
        display: flex;
        align-items: center;
    }

    .detail-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(255, 251, 230, .95) 50%, rgba(255, 251, 230, .6) 80%, transparent 100%);
        border-radius: 20px;
    }

    .detail-hero::after {
        content: '';
        position: absolute;
        right: -30px;
        top: -30px;
        width: 200px;
        height: 200px;
        background: rgba(201, 162, 39, .12);
        border-radius: 50%;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-title {
        font-size: 28px;
        font-weight: 800;
        color: #7C5C00;
        margin-bottom: 6px;
    }

    .hero-sub {
        font-size: 13px;
        color: #92400E;
        max-width: 500px;
    }

    .status-bar {
        background: var(--white);
        border-radius: var(--radius);
        padding: 20px 24px;
        margin-bottom: 20px;
        border: 1px solid var(--border);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .status-bar-label {
        font-size: 11.5px;
        color: var(--muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .status-bar-tgl {
        font-size: 13px;
        font-weight: 700;
        color: var(--neutral);
    }

    .status-bar-progress {
        font-size: 11.5px;
        color: var(--muted);
        margin-top: 4px;
    }

    .progress-bar-wrap {
        background: #F3F4F6;
        border-radius: 99px;
        height: 6px;
        width: 220px;
        margin-top: 6px;
    }

    .progress-bar-fill {
        background: var(--gold);
        border-radius: 99px;
        height: 100%;
        transition: width .4s;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 99px;
    }

    .badge-menunggu {
        background: #FFFBEB;
        color: #92400E;
        border: 1px solid #FDE68A;
    }

    .badge-lolos {
        background: #F0FDF4;
        color: #15803D;
        border: 1px solid #BBF7D0;
    }

    .badge-tidak {
        background: #FEF2F2;
        color: #991B1B;
        border: 1px solid #FECACA;
    }

    .catatan-admin-box {
        background: #FFF7ED;
        border: 1px solid #FED7AA;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .catatan-admin-box svg {
        flex-shrink: 0;
        margin-top: 2px;
        color: #D97706;
    }

    .catatan-admin-title {
        font-size: 13px;
        font-weight: 700;
        color: #92400E;
        margin-bottom: 4px;
    }

    .catatan-admin-text {
        font-size: 13px;
        color: #92400E;
        line-height: 1.6;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .info-card {
        background: var(--white);
        border-radius: var(--radius);
        padding: 20px 22px;
        border: 1px solid var(--border);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
    }

    .info-card-head {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        color: var(--neutral);
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #F3F4F6;
    }

    .info-card-head svg {
        color: var(--gold);
    }

    .info-row {
        display: flex;
        flex-direction: column;
        margin-bottom: 12px;
    }

    .info-row:last-child {
        margin-bottom: 0;
    }

    .info-label {
        font-size: 11px;
        color: var(--muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .3px;
        margin-bottom: 3px;
    }

    .info-val {
        font-size: 13px;
        font-weight: 600;
        color: var(--neutral);
        line-height: 1.4;
    }

    .info-val.muted {
        font-weight: 400;
        color: var(--muted);
    }

    .dospem-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        border-bottom: 1px solid #F9FAFB;
    }

    .dospem-item:last-child {
        border-bottom: none;
    }

    .dospem-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--gold-lt);
        border: 2px solid var(--gold-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 800;
        color: var(--gold);
        flex-shrink: 0;
    }

    .dospem-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--neutral);
    }

    .dospem-role {
        font-size: 11px;
        color: var(--muted);
    }

    /* === JADWAL SEMINAR CARD === */
    .jadwal-card {
        background: linear-gradient(135deg, var(--gold-lt) 0%, #FFFBEB 100%);
        border: 1px solid var(--gold-border);
        border-radius: var(--radius);
        padding: 20px 24px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(201, 162, 39, .1);
    }

    .jadwal-card-head {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        font-weight: 700;
        color: #7C5C00;
        margin-bottom: 16px;
    }

    .jadwal-card-head .jadwal-icon-wrap {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #FFF1C2;
        border: 1px solid var(--gold-border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gold);
        flex-shrink: 0;
    }

    .jadwal-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px 24px;
    }

    .jadwal-item {
        display: flex;
        flex-direction: column;
    }

    .jadwal-item.full {
        grid-column: 1 / -1;
    }

    .jadwal-label {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        color: #92400E;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .3px;
        margin-bottom: 4px;
    }

    .jadwal-val {
        font-size: 14.5px;
        font-weight: 700;
        color: #7C5C00;
    }

    @media (max-width: 900px) {
        .jadwal-grid {
            grid-template-columns: 1fr;
        }
    }
    /* === END JADWAL SEMINAR CARD === */

    .dokumen-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }

    .dok-card {
        background: var(--white);
        border-radius: var(--radius);
        padding: 20px 22px;
        border: 1px solid var(--border);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
    }

    .dok-card-head {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        color: var(--neutral);
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #F3F4F6;
    }

    .dok-item {
        padding: 12px;
        background: #FAFAFA;
        border-radius: 10px;
        border: 1px solid var(--border);
        margin-bottom: 10px;
    }

    .dok-item:last-child {
        margin-bottom: 0;
    }

    .dok-item.dok-tolak {
        background: #FEF2F2;
        border-color: #FECACA;
    }

    .dok-item.dok-ok {
        background: #F0FDF4;
        border-color: #BBF7D0;
    }

    .dok-item-top {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .dok-icon-wrap {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: var(--gold-lt);
        border: 1px solid var(--gold-border);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--gold);
    }

    .dok-info {
        flex: 1;
        min-width: 0;
    }

    .dok-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--neutral);
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .dok-size {
        font-size: 11px;
        color: var(--muted);
    }

    .dok-badge {
        display: inline-block;
        font-size: 10.5px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 99px;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .dok-badge.verified {
        background: #F0FDF4;
        color: #15803D;
        border: 1px solid #BBF7D0;
    }

    .dok-badge.rejected {
        background: #FEF2F2;
        color: #991B1B;
        border: 1px solid #FECACA;
    }

    .dok-badge.pending {
        background: #FFFBEB;
        color: #92400E;
        border: 1px solid #FDE68A;
    }

    .dok-badge.belum {
        background: #F9FAFB;
        color: #9CA3AF;
        border: 1px solid #E5E7EB;
    }

    .dok-actions {
        display: flex;
        gap: 6px;
        margin-top: 6px;
    }

    .btn-preview {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 600;
        color: var(--muted);
        background: var(--white);
        border: 1px solid var(--border);
        padding: 4px 10px;
        border-radius: 6px;
        text-decoration: none;
        transition: all .15s;
    }

    .btn-preview:hover {
        border-color: var(--gold);
        color: var(--gold);
    }

    .btn-download {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 600;
        color: #1D4ED8;
        background: #EFF6FF;
        border: 1px solid #BFDBFE;
        padding: 4px 10px;
        border-radius: 6px;
        text-decoration: none;
        transition: all .15s;
    }

    .btn-download:hover {
        background: #DBEAFE;
    }

    .dok-tolak-note {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-top: 10px;
        padding: 10px 12px;
        background: #FEF2F2;
        border-radius: 8px;
        font-size: 12px;
        color: #991B1B;
        line-height: 1.5;
    }

    .dok-tolak-note svg {
        flex-shrink: 0;
        margin-top: 1px;
    }

    .btn-upload-ulang {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 8px;
        background: #FEF2F2;
        color: #991B1B;
        border: 1px solid #FECACA;
        text-decoration: none;
        margin-top: 8px;
        transition: background .15s;
    }

    .btn-upload-ulang:hover {
        background: #FEE2E2;
        color: #991B1B;
    }

    .footer-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--white);
        border-radius: var(--radius);
        padding: 16px 24px;
        border: 1px solid var(--border);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
        position: sticky;
        bottom: 0;
    }

    .btn-kembali {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        background: #F3F4F6;
        color: var(--neutral);
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: background .2s;
    }

    .btn-kembali:hover {
        background: #E5E7EB;
        color: var(--neutral);
    }

    .btn-daftar-seminar {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 24px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        background: #FFE083;
        color: #7C5C00;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: background .2s;
    }

    .btn-daftar-seminar:hover {
        background: #ffd84d;
        color: #7C5C00;
    }

    @media (max-width: 900px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .dokumen-section {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="detail-wrap">

    <div class="detail-hero">
        <div class="hero-content">
            <div class="hero-title">Detail Administrasi Seminar TA-1</div>
            <div class="hero-sub">Pantau status verifikasi dan kelengkapan administrasi seminar tugas akhir Anda.</div>
        </div>
    </div>

    @php
    $statusAdm = $pengajuan->status_administrasi ?? 'Menunggu Verifikasi';
    $catatanAdmin = $pengajuan->catatan_admin ?? null;
    $statusDokumen = $pengajuan->status_dokumen ? json_decode($pengajuan->status_dokumen, true) : [];
    $catatanDokumen = $pengajuan->catatan_dokumen ? json_decode($pengajuan->catatan_dokumen, true) : [];
    $progressAdm = $pengajuan->progress_dokumen ?? 0;
    $totalDok = $pengajuan->total_dokumen ?? 7;
    $persen = $totalDok > 0 ? round(($progressAdm / $totalDok) * 100) : 0;

    // Status per dokumen — MURNI dari kolom status_dokumen, tidak ikut status global
    $getDokStatus = fn($key) => $statusDokumen[$key] ?? 'menunggu';

    // Apakah jadwal seminar sudah ditetapkan? (dari File 1)
    $sudahDijadwalkan = in_array($pengajuan->status_seminar, ['Jadwal ditetapkan', 'Sudah Dijadwalkan', 'Selesai']);
    @endphp

    {{-- STATUS BAR --}}
    <div class="status-bar">
        <div>
            <div class="status-bar-label">Status Administrasi</div>
            <div class="status-bar-tgl">Tanggal Pengajuan: {{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d M Y') }}</div>
            <div class="status-bar-progress">{{ $progressAdm }} dari {{ $totalDok }} dokumen diverifikasi</div>
            <div class="progress-bar-wrap">
                <div class="progress-bar-fill" style="width:{{ $persen }}%;"></div>
            </div>
        </div>
        <div>
            @if($statusAdm === 'Lolos Administrasi')
            <span class="badge-status badge-lolos">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                Lolos Verifikasi
            </span>
            @elseif($statusAdm === 'Tidak Administrasi')
            <span class="badge-status badge-tidak">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
                Tidak Lolos
            </span>
            @else
            <span class="badge-status badge-menunggu">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 6v6l4 2" />
                </svg>
                Menunggu Verifikasi
            </span>
            @endif
        </div>
    </div>

    {{-- CATATAN UMUM ADMIN --}}
    @if(!empty($catatanAdmin))
    <div class="catatan-admin-box">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
        </svg>
        <div>
            <div class="catatan-admin-title">Catatan Umum dari Admin</div>
            <div class="catatan-admin-text">{{ $catatanAdmin }}</div>
        </div>
    </div>
    @endif

    {{-- INFO GRID --}}
    <div class="info-grid">
        <div class="info-card">
            <div class="info-card-head">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                Informasi Mahasiswa
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div class="info-row"><span class="info-label">Nama Lengkap</span><span class="info-val">{{ $mahasiswa->nama ?? '-' }}</span></div>
                <div class="info-row"><span class="info-label">NIM</span><span class="info-val">{{ $mahasiswa->nim_nid ?? '-' }}</span></div>
                <div class="info-row"><span class="info-label">Email</span><span class="info-val" style="font-size:12px;">{{ $mahasiswa->email ?? '-' }}</span></div>
                <div class="info-row"><span class="info-label">Semester</span><span class="info-val">{{ $draftData['semester'] ?? $pengajuan->semester ?? '-' }}</span></div>
                <div class="info-row" style="grid-column:1/-1;"><span class="info-label">Dosen Wali</span><span class="info-val">{{ $draftData['dosen_wali'] ?? $pengajuan->dosen_wali ?? '-' }}</span></div>
            </div>
        </div>

        <div class="info-card">
            <div class="info-card-head">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-1.516" />
                </svg>
                Informasi Akademik
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div class="info-row"><span class="info-label">IPK</span><span class="info-val">{{ $draftData['ipk'] ?? $pengajuan->ipk ?? '-' }} / 4.00</span></div>
                <div class="info-row"><span class="info-label">Total SKS (KHS)</span><span class="info-val">{{ $draftData['total_sks'] ?? $pengajuan->total_sks ?? '-' }} SKS</span></div>
                <div class="info-row"><span class="info-label">SKS Semester</span><span class="info-val">{{ $draftData['sks_semester'] ?? $pengajuan->sks_semester ?? '-' }} SKS</span></div>
                <div class="info-row"><span class="info-label">Total SKS (KHS+KRS)</span><span class="info-val">{{ $draftData['total_sks_akumulasi'] ?? $pengajuan->total_sks_akumulasi ?? '-' }} SKS</span></div>
                <div class="info-row"><span class="info-label">SKS Nilai D</span><span class="info-val">{{ $draftData['sks_nilai_d'] ?? $pengajuan->sks_nilai_d ?? '0' }} SKS</span></div>
                <div class="info-row"><span class="info-label">MK Nilai D</span><span class="info-val {{ empty($draftData['mk_nilai_d'] ?? $pengajuan->mk_nilai_d) ? 'muted' : '' }}">{{ $draftData['mk_nilai_d'] ?? $pengajuan->mk_nilai_d ?? 'Tidak ada' }}</span></div>
            </div>
        </div>

        <div class="info-card">
            <div class="info-card-head">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                Informasi Tugas Akhir
            </div>
            <div class="info-row" style="margin-bottom:14px;">
                <span class="info-label">Judul Tugas Akhir</span>
                <span class="info-val" style="font-size:12.5px;line-height:1.6;">"{{ $pengajuanJudul->judul_disetujui ?? $pengajuanJudul->judul_1 ?? '-' }}"</span>
            </div>
            <div class="info-label" style="margin-bottom:8px;">Pembimbing</div>
            @if($namaDospem1 !== '-')
            <div class="dospem-item">
                <div class="dospem-avatar">{{ strtoupper(substr($namaDospem1, 0, 1)) }}</div>
                <div>
                    <div class="dospem-name">{{ $namaDospem1 }}</div>
                    <div class="dospem-role">Pembimbing 1</div>
                </div>
            </div>
            @endif
            @if($namaDospem2 !== '-')
            <div class="dospem-item">
                <div class="dospem-avatar">{{ strtoupper(substr($namaDospem2, 0, 1)) }}</div>
                <div>
                    <div class="dospem-name">{{ $namaDospem2 }}</div>
                    <div class="dospem-role">Pembimbing 2</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- JADWAL SEMINAR (dari File 1 — muncul kalau seminar sudah dijadwalkan) --}}
    @if($sudahDijadwalkan)
    <div class="jadwal-card">
        <div class="jadwal-card-head">
            <span class="jadwal-icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h12a2.25 2.25 0 0 1 2.25 2.25v11.25m-16.5 0A2.25 2.25 0 0 0 6 21h12a2.25 2.25 0 0 0 2.25-2.25m-16.5 0v-7.5A2.25 2.25 0 0 1 6 9h12a2.25 2.25 0 0 1 2.25 2.25v7.5" />
                </svg>
            </span>
            Jadwal Seminar TA-1
        </div>
        <div class="jadwal-grid">
            <div class="jadwal-item">
                <span class="jadwal-label">Tanggal Seminar</span>
                <span class="jadwal-val">
                    {{ $pengajuan->tanggal_seminar ? \Carbon\Carbon::parse($pengajuan->tanggal_seminar)->translatedFormat('d M Y') : '-' }}
                </span>
            </div>
            <div class="jadwal-item">
                <span class="jadwal-label">Ruangan</span>
                <span class="jadwal-val">{{ $pengajuan->ruang ?? '-' }}</span>
            </div>
            <div class="jadwal-item full">
                <span class="jadwal-label">Jam</span>
                <span class="jadwal-val">
                    {{ $pengajuan->waktu_mulai ? \Carbon\Carbon::parse($pengajuan->waktu_mulai)->format('H:i') : '-' }} - {{ $pengajuan->waktu_selesai ? \Carbon\Carbon::parse($pengajuan->waktu_selesai)->format('H:i') : '-' }} WIB
                </span>
            </div>
        </div>
    </div>
    @endif

    {{-- DOKUMEN --}}
    @php
    $svgFile = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
    </svg>';

    $grupDokumen = [
    'Dokumen Akademik' => [
    ['key' => 'khs', 'file_key' => 'file_khs', 'label' => 'Transkrip Nilai (KHS)'],
    ['key' => 'krs', 'file_key' => 'file_krs', 'label' => 'Kartu Rencana Studi (KRS)'],
    ['key' => 'spp', 'file_key' => 'file_spp', 'label' => 'Bukti Lunas SPP'],
    ],
    'Dokumen Bimbingan' => [
    ['key' => 'bimbingan', 'file_key' => 'file_bimbingan', 'label' => 'Kartu Bimbingan'],
    ['key' => 'persetujuan', 'file_key' => 'file_persetujuan', 'label' => 'Lembar Persetujuan Pembimbing'],
    ['key' => 'laporan_doc', 'file_key' => 'file_laporan_doc', 'label' => 'Laporan TA 1 (Docx)'],
    ['key' => 'laporan_pdf', 'file_key' => 'file_laporan_pdf', 'label' => 'Laporan TA 1 (PDF)'],
    ],
    ];
    @endphp

    <div class="dokumen-section">
        @foreach($grupDokumen as $grupLabel => $dokList)
        <div class="dok-card">
            <div class="dok-card-head">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#C9A227" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                {{ $grupLabel }}
            </div>

            @foreach($dokList as $dok)
            @php
            $filePath = $pengajuan->{$dok['file_key']} ?? ($draftData[$dok['file_key']] ?? null);
            $status = $getDokStatus($dok['key']);
            $itemClass = match($status) {
            'setujui' => 'dok-item dok-ok',
            'tolak' => 'dok-item dok-tolak',
            default => 'dok-item',
            };
            $catatanIni = $catatanDokumen[$dok['key']] ?? null;
            @endphp
            <div class="{{ $itemClass }}">
                <div class="dok-item-top">
                    <div class="dok-icon-wrap">{!! $svgFile !!}</div>
                    <div class="dok-info">
                        <div class="dok-name">{{ $dok['label'] }}</div>
                        @if($filePath)
                        <div class="dok-size">{{ basename($filePath) }}</div>
                        <div class="dok-actions">
                            <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="btn-preview">Preview</a>
                            <a href="{{ asset('storage/' . $filePath) }}" download class="btn-download">Download</a>
                        </div>
                        @else
                        <div class="dok-size" style="color:#dc2626;">Belum diupload</div>
                        @endif
                    </div>
                    @if($filePath)
                    @if($status === 'setujui')
                    <span class="dok-badge verified">✓ Terverifikasi</span>
                    @elseif($status === 'tolak')
                    <span class="dok-badge rejected">✕ Ditolak</span>
                    @else
                    <span class="dok-badge pending">⏳ Menunggu</span>
                    @endif
                    @else
                    <span class="dok-badge belum">Belum Upload</span>
                    @endif
                </div>

                {{-- Catatan penolakan per dokumen --}}
                @if($status === 'tolak')
                <div class="dok-tolak-note">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <div>
                        <strong>Dokumen Ditolak.</strong><br>
                        Catatan: {{ !empty($catatanIni) ? $catatanIni : 'Silakan upload ulang dokumen yang sesuai.' }}
                    </div>
                </div>
                @if($statusAdm === 'Tidak Administrasi')
                <a href="{{ route('seminar.edit', $pengajuan->id) }}" class="btn-upload-ulang">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                    </svg>
                    Upload Ulang
                </a>
                @endif
                @endif
            </div>
            @endforeach
        </div>
        @endforeach
    </div>

    {{-- FOOTER --}}
    <div class="footer-actions">
        <a href="{{ route('seminar.daftar') }}" class="btn-kembali">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali
        </a>
        @if($statusAdm === 'Lolos Administrasi' && $pengajuan->status_seminar === 'Belum Daftar Seminar')
        <a href="{{ route('seminar.formDaftar', $pengajuan->id) }}" class="btn-daftar-seminar">
            Daftar Seminar
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
        </a>
        @elseif($statusAdm === 'Tidak Administrasi')
        <span style="font-size:12px;color:#991B1B;font-style:italic;font-weight:600;">↑ Upload Ulang File yang Ditolak</span>
        @elseif($statusAdm === 'Lolos Administrasi' && $pengajuan->status_seminar === 'Menunggu Jadwal')
        <span style="font-size:12px;color:#92400E;font-style:italic;font-weight:600;">✓ Administrasi Lolos — Menunggu penjadwalan seminar ...</span>
        @elseif($statusAdm === 'Lolos Administrasi' && $sudahDijadwalkan)
        {{-- Dari File 2: tombol Lihat Jadwal Seminar --}}
        @else
        <span style="font-size:12px;color:var(--muted);font-style:italic;">Menunggu verifikasi dari admin...</span>
        @endif
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            document.querySelectorAll('.toast').forEach(t => t.classList.remove('show'));
        }, 3500);
    });
</script>

@endsection