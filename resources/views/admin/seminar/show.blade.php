@extends('layouts.app')

@section('title', 'Verifikasi Administrasi Seminar')

@section('content')

@php
$statusDokumenDB  = $pengajuan->status_dokumen
    ? (json_decode($pengajuan->status_dokumen, true) ?? [])
    : [];
$catatanDokumenDB = $pengajuan->catatan_dokumen
    ? (json_decode($pengajuan->catatan_dokumen, true) ?? [])
    : [];

// ✅ Ambil file dari draft_data, bukan dari kolom langsung
$draftArr   = $pengajuan->draft_data ? json_decode($pengajuan->draft_data, true) : [];
$sudahLolos = $pengajuan->status_administrasi === 'Lolos Administrasi';

$dokList = [
    ['key' => 'khs',         'file' => $draftArr['file_khs'] ?? null,         'label' => 'Transkrip Nilai (KHS)',         'meta' => 'PDF'],
    ['key' => 'krs',         'file' => $draftArr['file_krs'] ?? null,         'label' => 'Kartu Rencana Studi (KRS)',     'meta' => 'PDF'],
    ['key' => 'spp',         'file' => $draftArr['file_spp'] ?? null,         'label' => 'Bukti Lunas SPP',               'meta' => 'JPG/PDF'],
    ['key' => 'bimbingan',   'file' => $draftArr['file_bimbingan'] ?? null,   'label' => 'Kartu Bimbingan',               'meta' => 'PDF'],
    ['key' => 'persetujuan', 'file' => $draftArr['file_persetujuan'] ?? null, 'label' => 'Lembar Persetujuan Pembimbing', 'meta' => 'PDF'],
    ['key' => 'laporan_doc', 'file' => $draftArr['file_laporan_doc'] ?? null, 'label' => 'File Draft Laporan (DOCX)',     'meta' => 'DOCX'],
    ['key' => 'laporan_pdf', 'file' => $draftArr['file_laporan_pdf'] ?? null, 'label' => 'File Draft Laporan (PDF)',      'meta' => 'PDF'],
];

$dokByKey = collect($dokList)->keyBy('key');

$terkunciArr = $sudahLolos
    ? collect($dokList)->filter(fn($d) => !empty($d['file']))->pluck('key')->toArray()
    : collect($statusDokumenDB)->filter(fn($v) => $v === 'setujui')->keys()->toArray();

$grupDokumen = [
    'Dokumen Akademik'    => ['khs', 'krs', 'spp'],
    'Dokumen Bimbingan'   => ['bimbingan', 'persetujuan'],
    'Laporan Tugas Akhir' => ['laporan_doc', 'laporan_pdf'],
];

$allKeys    = ['khs','krs','spp','bimbingan','persetujuan','laporan_doc','laporan_pdf'];
$totalDok   = count($allKeys);
$sudahAda   = collect($dokList)->filter(fn($d) => !empty($d['file']))->count();
$belumAda   = $totalDok - $sudahAda;
$jmlSetujui = $sudahLolos ? $sudahAda : collect($statusDokumenDB)->filter(fn($v) => $v === 'setujui')->count();
$jmlTolak   = $sudahLolos ? 0 : collect($statusDokumenDB)->filter(fn($v) => $v === 'tolak')->count();
@endphp

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
    .verif-wrap { background: var(--bg); min-height: 100vh; padding-bottom: 60px; }
    .verif-hero {
        background-image: url('{{ asset("images/1.jpeg") }}');
        background-size: cover; background-position: center right;
        border-radius: 20px; padding: 32px 40px; margin-bottom: 24px;
        position: relative; overflow: hidden; min-height: 140px; display: flex; align-items: center;
    }
    .verif-hero::before { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, rgba(255,251,230,.96) 55%, rgba(255,251,230,.65) 80%, transparent 100%); border-radius: 20px; }
    .verif-hero::after  { content: ''; position: absolute; right: -30px; top: -30px; width: 180px; height: 180px; background: rgba(201,162,39,.12); border-radius: 50%; }
    .hero-content { position: relative; z-index: 2; }
    .hero-title { font-size: 24px; font-weight: 800; color: #7C5C00; margin-bottom: 4px; }
    .hero-sub   { font-size: 13px; color: #92400E; line-height: 1.6; max-width: 500px; }

    .btn-kembali-seminar {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700;
        background: var(--white); border: 1.5px solid var(--border);
        color: var(--muted); text-decoration: none; transition: .2s; cursor: pointer;
        margin-bottom: 20px;
    }
    .btn-kembali-seminar:hover { border-color: var(--gold); color: var(--gold); }

    .verif-grid { display: grid; grid-template-columns: 1fr 300px; gap: 20px; align-items: start; }

    .v-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,.04); }
    .v-card:last-child { margin-bottom: 0; }
    .v-card-head { display: flex; align-items: center; gap: 12px; margin-bottom: 18px; padding-bottom: 14px; border-bottom: 1px solid #F3F4F6; }
    .v-card-icon  { width: 36px; height: 36px; border-radius: 10px; background: #F8FAFC; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 16px; }
    .v-card-title { font-size: 15px; font-weight: 700; color: var(--neutral); }

    .mhs-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; }
    .mhs-field-label { font-size: 10.5px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
    .mhs-field-value { font-size: 13px; font-weight: 600; color: var(--neutral); }
    .mhs-field-value.mono { font-family: monospace; letter-spacing: .5px; }

    .akad-grid  { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; margin-bottom: 14px; }
    .akad-item  { background: #F8FAFC; border-radius: 10px; padding: 12px 14px; }
    .akad-label { font-size: 10.5px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; margin-bottom: 4px; }
    .akad-value { font-size: 18px; font-weight: 800; color: var(--neutral); }
    .akad-value.ok   { color: #15803D; }
    .akad-value.warn { color: #92400E; }
    .akad-value.info { color: #1D4ED8; }
    .akad-badge { display: inline-block; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 99px; margin-left: 6px; vertical-align: middle; }
    .akad-badge-ok   { background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; }
    .akad-badge-warn { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
    .akad-note { background: #FFF7ED; border: 1px solid #FED7AA; border-radius: 10px; padding: 10px 14px; font-size: 12.5px; color: #92400E; display: flex; align-items: flex-start; gap: 8px; }

    .ta-judul { background: #F8FAFC; border-radius: 10px; padding: 14px 16px; font-size: 14px; font-weight: 700; color: var(--neutral); margin-bottom: 14px; line-height: 1.5; font-style: italic; }
    .ta-dospem-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .ta-dospem { background: #F8FAFC; border-radius: 10px; padding: 12px 14px; }
    .ta-dospem-label { font-size: 10px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; margin-bottom: 4px; }
    .ta-dospem-name  { font-size: 13px; font-weight: 700; color: var(--neutral); }

    .dok-section-title { font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; margin: 18px 0 10px; }
    .dok-section-title:first-child { margin-top: 0; }

    .dok-item { display: flex; flex-direction: column; padding: 14px; border: 1px solid var(--border); border-radius: 12px; margin-bottom: 8px; transition: border-color .2s, background .2s; }
    .dok-item:last-child { margin-bottom: 0; }
    .dok-item.state-setujui { border-color: #15803D !important; background: #F0FDF4 !important; }
    .dok-item.state-tolak   { border-color: #DC2626 !important; background: #FEF2F2 !important; }
    .dok-item-top { display: flex; align-items: center; gap: 14px; }
    .dok-file-icon { width: 40px; height: 40px; border-radius: 8px; background: var(--gold-lt); display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--gold); }
    .dok-info { flex: 1; min-width: 0; }
    .dok-name { font-size: 13px; font-weight: 700; color: var(--neutral); margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .dok-meta { font-size: 11px; color: var(--muted); display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }

    .dok-prev-badge { display: inline-flex; align-items: center; gap: 3px; font-size: 10.5px; font-weight: 700; padding: 2px 8px; border-radius: 99px; white-space: nowrap; }
    .dpb-setujui { background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; }
    .dpb-tolak   { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }
    .dpb-ulang   { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }
    .dpb-menunggu{ background: #F9FAFB; color: #9CA3AF; border: 1px solid #E5E7EB; }

    .dok-actions { display: flex; gap: 6px; align-items: center; flex-shrink: 0; }
    .btn-lihat { padding: 5px 12px; border-radius: 7px; font-size: 11.5px; font-weight: 700; background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; text-decoration: none; white-space: nowrap; transition: .15s; }
    .btn-lihat:hover { background: #DBEAFE; }
    .btn-setujui { padding: 5px 12px; border-radius: 7px; font-size: 11.5px; font-weight: 700; background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; white-space: nowrap; transition: .15s; cursor: pointer; font-family: inherit; }
    .btn-setujui:hover { background: #DCFCE7; }
    .btn-setujui.active { background: #15803D; color: #fff; border-color: #15803D; }
    .btn-tolak-dok { padding: 5px 12px; border-radius: 7px; font-size: 11.5px; font-weight: 700; background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; white-space: nowrap; transition: .15s; cursor: pointer; font-family: inherit; }
    .btn-tolak-dok:hover { background: #FEE2E2; }
    .btn-tolak-dok.active { background: #DC2626; color: #fff; border-color: #DC2626; }
    .dok-status-badge { font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 99px; white-space: nowrap; }
    .dsb-none { background: #F9FAFB; color: #9CA3AF; border: 1px solid #E5E7EB; }

    .catatan-per-dok { display: none; margin-top: 10px; }
    .catatan-per-dok textarea { width: 100%; padding: 8px 10px; border: 1.5px solid #FECACA; border-radius: 8px; font-size: 12px; font-family: inherit; resize: vertical; min-height: 60px; outline: none; box-sizing: border-box; background: #fff; color: var(--neutral); transition: border .2s; }
    .catatan-per-dok textarea:focus { border-color: #DC2626; }
    .catatan-per-dok label { font-size: 11px; font-weight: 700; color: #DC2626; margin-bottom: 4px; display: block; }

    .sidebar-sticky { position: sticky; top: 20px; }
    .ringkasan-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,.04); }
    .ringkasan-title { font-size: 14px; font-weight: 800; color: var(--neutral); margin-bottom: 14px; }
    .rk-item { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #F3F4F6; font-size: 13px; }
    .rk-item:last-child { border-bottom: none; }
    .rk-label { color: var(--muted); font-weight: 500; }
    .rk-val { font-weight: 700; color: var(--neutral); }
    .rk-val.red   { color: #DC2626; }
    .rk-val.green { color: #15803D; }

    .catatan-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,.04); }
    .catatan-title { font-size: 14px; font-weight: 800; color: var(--neutral); margin-bottom: 12px; }
    .catatan-textarea { width: 100%; min-height: 80px; border: 1.5px solid var(--border); border-radius: 10px; padding: 10px 12px; font-size: 13px; font-family: inherit; resize: vertical; outline: none; color: var(--neutral); background: #FAFAFA; box-sizing: border-box; transition: border .2s; }
    .catatan-textarea:focus { border-color: var(--gold); background: #fff; }

    .btn-aksi { width: 100%; padding: 13px; border-radius: 12px; font-size: 14px; font-weight: 800; border: none; cursor: pointer; font-family: inherit; transition: .2s; margin-bottom: 8px; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .btn-aksi-lolos { background: #FFE083; color: #7C5C00; box-shadow: 0 4px 12px rgba(201,162,39,.25); }
    .btn-aksi-lolos:hover:not(:disabled) { background: #fdd835; box-shadow: 0 6px 16px rgba(201,162,39,.35); transform: translateY(-1px); }
    .btn-aksi-tolak { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }
    .btn-aksi-tolak:hover:not(:disabled) { background: #FEE2E2; }
    .btn-aksi:disabled { opacity: .5; cursor: not-allowed; transform: none !important; box-shadow: none !important; }

    .status-banner { border-radius: 10px; padding: 10px 14px; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 8px; margin-bottom: 12px; }
    .sb-lolos   { background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; }
    .sb-tolak   { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }
    .sb-pending { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }

    .terverifikasi-box { background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 12px; padding: 14px; text-align: center; font-size: 13px; font-weight: 700; color: #15803D; display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 8px; }
    .info-note { background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 10px; padding: 12px 14px; font-size: 12px; color: #92400E; display: flex; gap: 8px; align-items: flex-start; margin-top: 8px; line-height: 1.5; }

    @media (max-width: 900px) {
        .verif-grid { grid-template-columns: 1fr; }
        .mhs-grid   { grid-template-columns: 1fr 1fr; }
        .akad-grid  { grid-template-columns: 1fr 1fr; }
    }
</style>

<div class="verif-wrap">

    <div class="verif-hero">
        <div class="hero-content">
            <div class="hero-title">Verifikasi Administrasi Seminar TA-1</div>
            <div class="hero-sub">Tinjau dan verifikasi data administrasi seminar mahasiswa.</div>
        </div>
    </div>

    <a href="{{ route('admin.seminar.index') }}" class="btn-kembali-seminar">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
        Kembali
    </a>

    <form method="POST" action="{{ route('admin.seminar.verifikasi', $pengajuan->id) }}" id="formVerif">
        @csrf

        <div class="verif-grid">

            {{-- ══ KOLOM KIRI ══ --}}
            <div>

                {{-- 1. INFORMASI MAHASISWA --}}
                <div class="v-card">
                    <div class="v-card-head">
                        <div class="v-card-icon">👤</div>
                        <div class="v-card-title">Informasi Mahasiswa</div>
                    </div>
                    <div class="mhs-grid">
                        <div>
                            <div class="mhs-field-label">NIM / NID</div>
                            <div class="mhs-field-value mono">{{ $pengajuan->mahasiswa_id }}</div>
                        </div>
                        <div>
                            <div class="mhs-field-label">Nama Mahasiswa</div>
                            <div class="mhs-field-value">{{ $mahasiswa->nama ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="mhs-field-label">Email</div>
                            <div class="mhs-field-value">{{ $mahasiswa->email ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="mhs-field-label">Semester</div>
                            <div class="mhs-field-value">{{ $draftArr['semester'] ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="mhs-field-label">Dosen Wali</div>
                            <div class="mhs-field-value">{{ $draftArr['dosen_wali'] ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                {{-- 2. INFORMASI AKADEMIK --}}
                <div class="v-card">
                    <div class="v-card-head">
                        <div class="v-card-icon">📊</div>
                        <div class="v-card-title">Informasi Akademik</div>
                    </div>
                    <div class="akad-grid">
                        <div class="akad-item">
                            <div class="akad-label">IPK</div>
                            <div class="akad-value {{ ($draftArr['ipk'] ?? 0) >= 2.75 ? 'ok' : 'warn' }}">
                                {{ $draftArr['ipk'] ?? '-' }}
                                <span class="akad-badge {{ ($draftArr['ipk'] ?? 0) >= 2.75 ? 'akad-badge-ok' : 'akad-badge-warn' }}">
                                    {{ ($draftArr['ipk'] ?? 0) >= 2.75 ? 'OK' : 'Cek' }}
                                </span>
                            </div>
                        </div>
                        <div class="akad-item">
                            <div class="akad-label">Total SKS</div>
                            <div class="akad-value info">{{ $draftArr['total_sks'] ?? '-' }}</div>
                        </div>
                        <div class="akad-item">
                            <div class="akad-label">SKS Nilai D</div>
                            <div class="akad-value {{ ($draftArr['sks_nilai_d'] ?? 0) > 0 ? 'warn' : 'ok' }}">
                                {{ $draftArr['sks_nilai_d'] ?? '0' }}
                            </div>
                        </div>
                        <div class="akad-item">
                            <div class="akad-label">SKS Semester Ini</div>
                            <div class="akad-value info">{{ $draftArr['sks_semester'] ?? '-' }}</div>
                        </div>
                        <div class="akad-item">
                            <div class="akad-label">Total SKS (KHS+KRS)</div>
                            <div class="akad-value info">{{ $draftArr['total_sks_akumulasi'] ?? '-' }}</div>
                        </div>
                    </div>
                    @if(!empty($draftArr['mk_nilai_d']))
                    <div class="akad-note">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16" style="flex-shrink:0;margin-top:1px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                        </svg>
                        <div><strong>MK Nilai D:</strong> {{ $draftArr['mk_nilai_d'] }}</div>
                    </div>
                    @endif
                </div>

                {{-- 3. INFORMASI TUGAS AKHIR --}}
                <div class="v-card">
                    <div class="v-card-head">
                        <div class="v-card-icon">📝</div>
                        <div class="v-card-title">Informasi Tugas Akhir</div>
                    </div>
                    <div class="ta-judul">"{{ $pengajuanJudul->judul_disetujui ?? $pengajuanJudul->judul_1 ?? '-' }}"</div>
                    <div class="ta-dospem-grid">
                        <div class="ta-dospem">
                            <div class="ta-dospem-label">Dosen Pembimbing 1</div>
                            <div class="ta-dospem-name">{{ $namaDospem1 ?? '-' }}</div>
                        </div>
                        <div class="ta-dospem">
                            <div class="ta-dospem-label">Dosen Pembimbing 2</div>
                            <div class="ta-dospem-name">{{ $namaDospem2 ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                {{-- 4. VERIFIKASI DOKUMEN --}}
                <div class="v-card">
                    <div class="v-card-head">
                        <div class="v-card-icon">📁</div>
                        <div class="v-card-title">Verifikasi Dokumen</div>
                    </div>

                    @php
                    $svgDoc = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>';
                    @endphp

                    @foreach($grupDokumen as $grupLabel => $keys)
                    <div class="dok-section-title">{{ $grupLabel }}</div>

                    @foreach($keys as $key)
                    @php
                        $dok          = $dokByKey[$key];
                        $prevStatus   = $statusDokumenDB[$key] ?? 'menunggu';
                        $prevCatatan  = $catatanDokumenDB[$key] ?? '';
                        $sudahSetujui = $prevStatus === 'setujui' || $sudahLolos;
                        $isUploadUlang = !empty($dok['file'])
                            && $prevStatus === 'menunggu'
                            && array_key_exists($key, $statusDokumenDB);
                    @endphp

                    <div class="dok-item {{ $sudahSetujui ? 'state-setujui' : '' }}" id="dok_item_{{ $key }}">
                        <div class="dok-item-top">
                            <div class="dok-file-icon">{!! $svgDoc !!}</div>
                            <div class="dok-info">
                                <div class="dok-name">{{ $dok['label'] }}</div>
                                <div class="dok-meta">
                                    <span>{{ $dok['meta'] }}</span>
                                    @if(!empty($dok['file']))
                                        @if($sudahSetujui)
                                            <span class="dok-prev-badge dpb-setujui">✓ Disetujui</span>
                                        @elseif($isUploadUlang)
                                            <span class="dok-prev-badge dpb-ulang">↑ Upload Ulang</span>
                                        @elseif($prevStatus === 'tolak')
                                            <span class="dok-prev-badge dpb-tolak">✕ Ditolak</span>
                                        @else
                                            <span class="dok-prev-badge dpb-menunggu">⏳ Menunggu</span>
                                        @endif
                                    @else
                                        <span style="color:#DC2626;font-weight:600;">Belum diunggah</span>
                                    @endif
                                </div>
                                @if(!empty($prevCatatan) && $prevStatus === 'tolak' && !$sudahLolos)
                                <div style="font-size:11px;color:#92400E;margin-top:3px;">
                                    Catatan: {{ $prevCatatan }}
                                </div>
                                @endif
                            </div>
                            <div class="dok-actions">
                                @if(!empty($dok['file']))
                                    <a href="{{ asset('storage/'.$dok['file']) }}" target="_blank" class="btn-lihat">Lihat</a>

                                    @if($sudahSetujui)
                                        {{-- Terkunci: hanya tombol Lihat, tidak ada tombol lain --}}
                                        <input type="hidden" name="status_{{ $key }}" value="setujui">
                                    @else
                                        <button type="button" class="btn-setujui" id="setujui_{{ $key }}"
                                            onclick="toggleSetujui('{{ $key }}', this)">Setujui</button>
                                        <button type="button" class="btn-tolak-dok" id="tolak_btn_{{ $key }}"
                                            onclick="toggleTolak('{{ $key }}', this)">Tolak</button>
                                        <input type="hidden" name="status_{{ $key }}" id="status_{{ $key }}" value="menunggu">
                                    @endif
                                @else
                                    <span class="dok-status-badge dsb-none">Belum Ada</span>
                                @endif
                            </div>
                        </div>

                        @if(!$sudahSetujui && !empty($dok['file']))
                        <div class="catatan-per-dok" id="catatan_wrap_{{ $key }}">
                            <label>Alasan penolakan {{ $dok['label'] }}</label>
                            <textarea name="catatan_{{ $key }}" placeholder="Tulis alasan penolakan...">{{ old('catatan_'.$key) }}</textarea>
                        </div>
                        @endif
                    </div>
                    @endforeach
                    @endforeach

                </div>

            </div>{{-- end kolom kiri --}}

            {{-- ══ SIDEBAR KANAN ══ --}}
            <div class="sidebar-sticky">

                <div class="ringkasan-card">
                    <div class="ringkasan-title">Ringkasan Verifikasi</div>
                    <div class="rk-item">
                        <span class="rk-label">Total Dokumen</span>
                        <span class="rk-val">{{ $totalDok }}</span>
                    </div>
                    <div class="rk-item">
                        <span class="rk-label">Sudah Ada</span>
                        <span class="rk-val green">{{ $sudahAda }}</span>
                    </div>
                    <div class="rk-item">
                        <span class="rk-label">Belum Diunggah</span>
                        <span class="rk-val red">{{ $belumAda }}</span>
                    </div>
                    <div class="rk-item">
                        <span class="rk-label">Ditolak</span>
                        <span class="rk-val red" id="jumlahDitolak">{{ $jmlTolak }}</span>
                    </div>
                    <div class="rk-item">
                        <span class="rk-label">Disetujui</span>
                        <span class="rk-val green" id="jumlahDisetujui">{{ $jmlSetujui }}</span>
                    </div>
                </div>

                <div class="catatan-card">
                    <div class="catatan-title">Catatan (Opsional)</div>
                    <textarea name="catatan" class="catatan-textarea"
                        placeholder="Catatan"
                        {{ $sudahLolos ? 'readonly' : '' }}>{{ old('catatan', $pengajuan->catatan_admin ?? '') }}</textarea>
                </div>

                {{-- STATUS BANNER --}}
                @if($pengajuan->status_administrasi === 'Lolos Administrasi')
                <div class="status-banner sb-lolos">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                    </svg>
                    Sudah Lolos Administrasi
                </div>
                @elseif($pengajuan->status_administrasi === 'Tidak Administrasi')
                <div class="status-banner sb-tolak">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                    Tidak Lolos Administrasi
                </div>
                @else
                <div class="status-banner sb-pending">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                    </svg>
                    Menunggu Verifikasi
                </div>
                @endif

                {{-- TOMBOL AKSI --}}
                @if($pengajuan->status_administrasi === 'Lolos Administrasi')
                    <div class="terverifikasi-box">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                        </svg>
                        Administrasi Terverifikasi
                    </div>

                @elseif($pengajuan->status_administrasi === 'Tidak Administrasi')
                    <button type="submit" id="btnAkses" name="status_administrasi"
                        value="Lolos Administrasi" class="btn-aksi btn-aksi-lolos">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                        </svg>
                        Reverifikasi Pengajuan
                    </button>

                @else
                    <button type="submit" id="btnAkses" name="status_administrasi"
                        value="Lolos Administrasi" class="btn-aksi btn-aksi-lolos">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                        </svg>
                        Loloskan Administrasi
                    </button>
                @endif

                @if($pengajuan->status_administrasi !== 'Lolos Administrasi')
                <div class="info-note">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14" style="flex-shrink:0;margin-top:1px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                    </svg>
                    Jika ada dokumen yang ditolak, tombol akan otomatis berubah menjadi "Tolak Pengajuan".
                </div>
                @endif

            </div>

        </div>
    </form>

</div>

<script>
    const tolakCount      = {};
    const setujuiCount    = {};
    const statusDokumenDB = {!! json_encode($statusDokumenDB) !!};
    const terkunci        = {!! json_encode($terkunciArr) !!};

    function updateAksesBtn() {
        const btn = document.getElementById('btnAkses');
        if (!btn) return;

        const semuaKeys  = ['khs','krs','spp','bimbingan','persetujuan','laporan_doc','laporan_pdf'];
        const perluVerif = semuaKeys.filter(k => !terkunci.includes(k));
        const sudahAksi  = Object.keys(tolakCount).length + Object.keys(setujuiCount).length;
        const belumVerif = perluVerif.length - sudahAksi;
        const jumlahTolak = Object.keys(tolakCount).length;

        document.getElementById('jumlahDitolak').textContent  = jumlahTolak;
        document.getElementById('jumlahDisetujui').textContent = Object.keys(setujuiCount).length + terkunci.length;

        if (jumlahTolak > 0) {
            btn.disabled  = false;
            btn.value     = 'Tidak Administrasi';
            btn.className = 'btn-aksi btn-aksi-tolak';
            btn.style.opacity = '1';
            btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg> Tolak Pengajuan (${jumlahTolak} ditolak)`;
        } else if (belumVerif > 0) {
            btn.disabled  = true;
            btn.value     = 'Lolos Administrasi';
            btn.className = 'btn-aksi btn-aksi-lolos';
            btn.style.opacity = '0.5';
            btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg> Verifikasi ${belumVerif} dokumen lagi...`;
        } else {
            btn.disabled  = false;
            btn.value     = 'Lolos Administrasi';
            btn.className = 'btn-aksi btn-aksi-lolos';
            btn.style.opacity = '1';
            btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg> Loloskan Administrasi`;
        }
    }

    function toggleSetujui(key, btn) {
        const input       = document.getElementById('status_' + key);
        const tolakBtn    = document.getElementById('tolak_btn_' + key);
        const dokItem     = document.getElementById('dok_item_' + key);
        const catatanWrap = document.getElementById('catatan_wrap_' + key);

        if (tolakBtn && tolakBtn.classList.contains('active')) {
            tolakBtn.classList.remove('active');
            tolakBtn.textContent = 'Tolak';
            if (catatanWrap) catatanWrap.style.display = 'none';
            delete tolakCount[key];
        }

        if (btn.classList.contains('active')) {
            btn.classList.remove('active');
            dokItem.classList.remove('state-setujui');
            if (input) input.value = 'menunggu';
            delete setujuiCount[key];
        } else {
            btn.classList.add('active');
            dokItem.classList.add('state-setujui');
            dokItem.classList.remove('state-tolak');
            if (input) input.value = 'setujui';
            setujuiCount[key] = 1;
        }
        updateAksesBtn();
    }

    function toggleTolak(key, btn) {
        const input       = document.getElementById('status_' + key);
        const setujuiBtn  = document.getElementById('setujui_' + key);
        const dokItem     = document.getElementById('dok_item_' + key);
        const catatanWrap = document.getElementById('catatan_wrap_' + key);

        if (setujuiBtn && setujuiBtn.classList.contains('active')) {
            setujuiBtn.classList.remove('active');
            dokItem.classList.remove('state-setujui');
            delete setujuiCount[key];
        }

        if (btn.classList.contains('active')) {
            btn.classList.remove('active');
            btn.textContent = 'Tolak';
            dokItem.classList.remove('state-tolak');
            if (catatanWrap) catatanWrap.style.display = 'none';
            delete tolakCount[key];
            if (input) input.value = 'menunggu';
        } else {
            btn.classList.add('active');
            btn.textContent = 'Batalkan';
            dokItem.classList.add('state-tolak');
            dokItem.classList.remove('state-setujui');
            if (catatanWrap) catatanWrap.style.display = 'block';
            tolakCount[key] = 1;
            if (input) input.value = 'tolak';
        }
        updateAksesBtn();
    }

    document.addEventListener('DOMContentLoaded', function () {
        Object.entries(statusDokumenDB).forEach(([key, status]) => {
            if (terkunci.includes(key)) return;
            if (status === 'setujui') {
                const btn = document.getElementById('setujui_' + key);
                if (btn) toggleSetujui(key, btn);
            }
        });

        updateAksesBtn();

        setTimeout(() => {
            const toast = document.getElementById('toastNotif');
            if (toast) toast.style.display = 'none';
        }, 3500);
    });
</script>

@endsection