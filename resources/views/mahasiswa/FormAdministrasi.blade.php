@extends('layouts.app')

@section('title', 'Administrasi Seminar')

@section('content')

@php
$isPerbaikan = isset($pengajuan) && $pengajuan->status_administrasi === 'Tidak Administrasi';
$statusDokumen = [];
if ($isPerbaikan && $pengajuan->status_dokumen) {
    $statusDokumen = json_decode($pengajuan->status_dokumen, true) ?? [];
}
$isDitolak = fn($key) => $isPerbaikan ? (($statusDokumen[$key] ?? '') === 'tolak') : true;
$disabledField = $isPerbaikan ? 'disabled' : '';
@endphp

<style>
    :root {
        --gold: #C9A227;
        --gold-light: #FEF9EC;
        --gold-border: #F5D97A;
        --neutral: #1E293B;
        --muted: #6B7280;
        --border: #E5E7EB;
        --bg: #F5F6FA;
    }

    body { background: var(--bg); }

    .hero-adm {
        position: relative;
        overflow: hidden;
        background: url('{{ asset("images/1.jpeg") }}') right center / auto 100% no-repeat;
        background-color: #fffbe6;
        border-radius: 20px;
        padding: 36px 40px 40px;
        margin-bottom: 24px;
        min-height: 160px;
        display: flex;
        align-items: center;
    }

    .hero-adm::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, #fffbe6 55%, transparent 100%);
        pointer-events: none;
    }

    .hero-adm-content { position: relative; z-index: 2; }
    .hero-adm-content h1 { font-size: 32px; font-weight: 800; color: #7C5C00; margin-bottom: 0; }

    .alert-penting {
        background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 14px;
        padding: 16px 20px; margin-bottom: 28px;
        display: flex; gap: 12px; align-items: flex-start;
        color: #92400E; font-size: 13.5px;
    }
    .alert-penting strong { display: block; font-weight: 700; margin-bottom: 4px; font-size: 14px; }

    .alert-tolak {
        background: #FEF2F2; border: 1px solid #FECACA; border-radius: 14px;
        padding: 16px 20px; margin-bottom: 28px;
        display: flex; gap: 12px; align-items: flex-start;
        color: #991B1B; font-size: 13.5px;
    }
    .alert-tolak strong { display: block; font-weight: 700; margin-bottom: 4px; font-size: 14px; }

    .form-layout { display: grid; grid-template-columns: 1fr 280px; gap: 24px; align-items: start; }

    .sec-card { background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 28px; margin-bottom: 20px; }
    .sec-head { display: flex; align-items: center; gap: 14px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid #F3F4F6; }
    .sec-num { width: 34px; height: 34px; background: #1E293B; color: #fff; border-radius: 10px; display: grid; place-items: center; font-size: 15px; font-weight: 800; flex-shrink: 0; }
    .sec-title { font-size: 17px; font-weight: 700; color: var(--neutral); }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
    .form-row.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
    .form-row.cols-1 { grid-template-columns: 1fr; }

    .form-group { display: flex; flex-direction: column; }
    .form-group label { font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.4px; }
    .form-group input, .form-group textarea {
        height: 44px; border: 1px solid var(--border); border-radius: 10px;
        padding: 0 14px; font-size: 14px; color: var(--neutral);
        background: #FAFAFA; font-family: inherit; outline: none;
        transition: border-color .2s, background .2s;
    }
    .form-group textarea { height: auto; padding: 12px 14px; resize: vertical; min-height: 90px; }
    .form-group input:focus, .form-group textarea:focus { border-color: var(--gold); background: #fff; }
    .form-group input:disabled, .form-group textarea:disabled {
        background: #F3F4F6 !important; color: #9CA3AF !important;
        cursor: not-allowed; border-color: #E5E7EB !important;
    }
    .form-group input.field-error, .form-group textarea.field-error { border-color: #dc2626 !important; background: #fff5f5 !important; }
    .err-msg { font-size: 11.5px; color: #dc2626; margin-top: 5px; display: block; font-weight: 600; }
    .form-hint { font-size: 11.5px; color: #94a3b8; margin-top: 5px; }
    .optional-badge { display: inline-block; font-size: 10px; font-weight: 600; background: #F3F4F6; color: #9CA3AF; border-radius: 6px; padding: 2px 7px; margin-left: 6px; vertical-align: middle; text-transform: none; letter-spacing: 0; }

    .dok-row { display: flex; align-items: center; gap: 16px; padding: 16px; border: 1.5px dashed #E5E7EB; border-radius: 12px; margin-bottom: 12px; transition: border-color .2s, background .2s; }
    .dok-row:hover { border-color: var(--gold-border); }
    .dok-row.dok-disabled { background: #F9FAFB; border-color: #E5E7EB; opacity: 0.7; pointer-events: none; }
    .dok-row.dok-ditolak { border-color: #FECACA; background: #FEF2F2; border-style: solid; }

    .dok-box { border: 1px solid var(--border); border-radius: 12px; padding: 18px; position: relative; transition: border-color .2s, background .2s; }
    .dok-box.dok-disabled { background: #F9FAFB; border-color: #E5E7EB; opacity: 0.7; pointer-events: none; }
    .dok-box.dok-ditolak { border-color: #FECACA; background: #FEF2F2; }

    .dok-icon { width: 44px; height: 44px; background: var(--gold-light); border-radius: 10px; display: grid; place-items: center; flex-shrink: 0; color: var(--gold); }
    .dok-info { flex: 1; }
    .dok-info-title { font-size: 14px; font-weight: 600; color: var(--neutral); margin-bottom: 2px; }
    .dok-info-sub { font-size: 12px; color: var(--muted); }

    .badge-tolak { display: inline-flex; align-items: center; gap: 4px; background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; font-size: 10.5px; font-weight: 700; padding: 3px 8px; border-radius: 99px; margin-bottom: 6px; }
    .badge-ok { display: inline-flex; align-items: center; gap: 4px; background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; font-size: 10.5px; font-weight: 700; padding: 3px 8px; border-radius: 99px; margin-bottom: 6px; }

    .btn-pilih-file { padding: 8px 18px; border-radius: 10px; border: 1.5px solid var(--gold-border); background: var(--gold-light); color: #7C5C00; font-size: 13px; font-weight: 700; cursor: pointer; white-space: nowrap; transition: background .15s; }
    .btn-pilih-file:hover { background: #fef3c7; }

    .file-name { font-size: 12px; color: #16a34a; margin-top: 4px; display: none; align-items: center; gap: 6px; }
    .file-name .btn-hapus-file { display: inline-flex; align-items: center; justify-content: center; width: 16px; height: 16px; border-radius: 50%; background: #fee2e2; color: #dc2626; font-size: 10px; font-weight: 900; cursor: pointer; border: none; padding: 0; line-height: 1; flex-shrink: 0; transition: background .15s; }
    .file-name .btn-hapus-file:hover { background: #fca5a5; }

    .status-card { background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 22px; position: sticky; top: 24px; }
    .status-title { font-size: 15px; font-weight: 700; color: var(--neutral); margin-bottom: 18px; }
    .status-item { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid #F9FAFB; font-size: 13px; color: var(--muted); transition: color .2s; }
    .status-item:last-child { border-bottom: none; }
    .status-item.active { color: var(--neutral); font-weight: 600; }
    .status-dot { width: 20px; height: 20px; border-radius: 50%; border: 2px solid #D1D5DB; display: grid; place-items: center; flex-shrink: 0; transition: background .2s, border-color .2s; }
    .progress-wrap { margin-top: 20px; }
    .progress-label { display: flex; justify-content: space-between; font-size: 12px; color: var(--muted); margin-bottom: 6px; }
    .progress-bar-bg { background: #F3F4F6; border-radius: 999px; height: 8px; }
    .progress-bar-fill { height: 8px; border-radius: 999px; background: var(--gold); transition: width .4s; }

    .form-footer { position: sticky; bottom: 0; background: #fff; border-top: 1px solid var(--border); padding: 14px 0; display: flex; justify-content: space-between; align-items: center; margin-top: 8px; z-index: 10; }
    .footer-info { font-size: 12px; color: var(--muted); }
    .footer-btns { display: flex; gap: 10px; }
    .btn-draft { padding: 10px 22px; border-radius: 12px; border: 1.5px solid var(--border); background: #fff; color: var(--neutral); font-size: 13px; font-weight: 700; cursor: pointer; }
    .btn-ajukan { padding: 10px 24px; border-radius: 12px; border: none; background: #FFE083; color: #7C5C00; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px; }
    .btn-ajukan:hover { background: #ffd84d; }

    @media (max-width: 900px) { .form-layout { grid-template-columns: 1fr; } .form-row.cols-3 { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 600px) { .form-row, .form-row.cols-3 { grid-template-columns: 1fr; } .hero-adm-content h1 { font-size: 22px; } }
</style>

{{-- HERO --}}
<div class="hero-adm">
    <div class="hero-adm-content">
        <h1>{{ $isPerbaikan ? 'Perbaikan Dokumen Seminar' : 'Administrasi Seminar' }}</h1>
    </div>
</div>

{{-- ALERT --}}
@if($isPerbaikan)
<div class="alert-tolak">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20" style="flex-shrink:0;margin-top:1px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
    </svg>
    <div>
        <strong>Perbaikan Diperlukan</strong>
        Hanya dokumen yang ditolak (ditandai merah) yang bisa diupload ulang. Dokumen lain sudah terkunci.
    </div>
</div>
@else
<div class="alert-penting">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20" style="flex-shrink:0;margin-top:1px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
    </svg>
    <div>
        <strong>Peringatan Penting</strong>
        Lengkapi seluruh dokumen administrasi seminar dengan benar sebelum melanjutkan proses pengajuan seminar tugas akhir.
    </div>
</div>
@endif

<form action="{{ route('seminar.store') }}" method="POST" enctype="multipart/form-data" id="formAdm">
    @csrf

    {{-- Hidden inputs untuk identifikasi mode --}}
    @if(isset($pengajuan))
        <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">
    @endif
    @if($isPerbaikan)
        <input type="hidden" name="mode_perbaikan" value="1">
    @else
        <input type="hidden" name="is_draft_mode" value="1">
    @endif

    <div class="form-layout">
        <div>

            {{-- 1. INFORMASI MAHASISWA --}}
            <div class="sec-card">
                <div class="sec-head"><div class="sec-num">1</div><div class="sec-title">Informasi Mahasiswa</div></div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nomor Induk Mahasiswa (NIM)</label>
                        <input type="text" value="{{ $mahasiswa->nim_nid ?? '-' }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" value="{{ $mahasiswa->nama ?? '-' }}" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email Institusi</label>
                        <input type="text" value="{{ $mahasiswa->email ?? '-' }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <input type="text" name="semester"
                            value="{{ $draftData['semester'] ?? ($pengajuan->semester ?? old('semester')) }}"
                            placeholder="Contoh: 7" {{ $disabledField }}>
                    </div>
                </div>
                <div class="form-row cols-1">
                    <div class="form-group">
                        <label>Dosen Wali</label>
                        <input type="text" name="dosen_wali"
                            value="{{ $draftData['dosen_wali'] ?? ($pengajuan->dosen_wali ?? old('dosen_wali')) }}"
                            placeholder="Nama dosen wali" {{ $disabledField }}>
                    </div>
                </div>
            </div>

            {{-- 2. INFORMASI AKADEMIK --}}
            <div class="sec-card">
                <div class="sec-head"><div class="sec-num">2</div><div class="sec-title">Informasi Akademik</div></div>
                <div class="form-row cols-3">
                    <div class="form-group">
                        <label>IPK</label>
                        <input type="text" name="ipk" placeholder="3.XX"
                            value="{{ $draftData['ipk'] ?? ($pengajuan->ipk ?? old('ipk')) }}" {{ $disabledField }}>
                    </div>
                    <div class="form-group">
                        <label>Total SKS Diambil</label>
                        <input type="number" name="total_sks" placeholder="1XX"
                            value="{{ $draftData['total_sks'] ?? ($pengajuan->total_sks ?? old('total_sks')) }}" {{ $disabledField }}>
                    </div>
                    <div class="form-group">
                        <label>SKS Nilai D <span class="optional-badge">Opsional</span></label>
                        <input type="number" name="sks_nilai_d" placeholder="0"
                            value="{{ $draftData['sks_nilai_d'] ?? ($pengajuan->sks_nilai_d ?? old('sks_nilai_d', 0)) }}" {{ $disabledField }}>
                    </div>
                </div>
                <div class="form-row cols-1">
                    <div class="form-group">
                        <label>Nama Mata Kuliah Nilai D <span class="optional-badge">Opsional</span></label>
                        <textarea name="mk_nilai_d" placeholder="Kosongkan jika tidak ada" {{ $disabledField }}>{{ $draftData['mk_nilai_d'] ?? ($pengajuan->mk_nilai_d ?? old('mk_nilai_d')) }}</textarea>
                        <span class="form-hint">Sertakan mata kuliah jika ada, dipisahkan dengan koma</span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>SKS Semester Berjalan</label>
                        <input type="number" name="sks_semester" placeholder="SKS di KRS"
                            value="{{ $draftData['sks_semester'] ?? ($pengajuan->sks_semester ?? old('sks_semester')) }}" {{ $disabledField }}>
                    </div>
                    <div class="form-group">
                        <label>Total SKS (KHS + KRS)</label>
                        <input type="number" name="total_sks_akumulasi" placeholder="Total akumulasi"
                            value="{{ ($draftData['total_sks_akumulasi'] ?? ($pengajuan->total_sks_akumulasi ?? old('total_sks_akumulasi'))) ?: '' }}" {{ $disabledField }}>
                    </div>
                </div>
            </div>

            {{-- 3. DOKUMEN AKADEMIK --}}
            <div class="sec-card">
                <div class="sec-head"><div class="sec-num">3</div><div class="sec-title">Dokumen Akademik</div></div>
                @php
                $dokAkademik = [
                    ['key'=>'khs', 'field'=>'file_khs', 'label'=>'Transkrip Nilai (KHS)',     'sub'=>'PDF. Maksimal 5MB.',                    'accept'=>'.pdf'],
                    ['key'=>'krs', 'field'=>'file_krs', 'label'=>'Kartu Rencana Studi (KRS)', 'sub'=>'PDF. Maksimal 5MB.',                    'accept'=>'.pdf'],
                    ['key'=>'spp', 'field'=>'file_spp', 'label'=>'Bukti Lunas SPP',           'sub'=>'JPG/PDF. Bukti transfer atau kwitansi.','accept'=>'.pdf,.jpg,.jpeg,.png'],
                ];
                @endphp
                @foreach($dokAkademik as $dok)
                @php
                    $ditolak  = $isDitolak($dok['key']);
                    $namaId   = 'nama-' . str_replace('_','-', str_replace('file_','',$dok['field']));
                    $rowClass = $isPerbaikan ? ($ditolak ? 'dok-row dok-ditolak' : 'dok-row dok-disabled') : 'dok-row';
                    $namaFile = $draftData[$dok['field']] ?? ($pengajuan->{$dok['field']} ?? null);
                @endphp
                <div class="{{ $rowClass }}" id="row_{{ $dok['field'] }}">
                    <div class="dok-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="22" height="22">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                    </div>
                    <div class="dok-info">
                        @if($isPerbaikan)
                            @if($ditolak)<span class="badge-tolak">✕ Ditolak — Upload Ulang</span>
                            @else<span class="badge-ok">✓ Terverifikasi</span>@endif
                        @endif
                        <div class="dok-info-title">{{ $dok['label'] }}</div>
                        <div class="dok-info-sub">{{ $dok['sub'] }}</div>
                        <div class="file-name" id="{{ $namaId }}" style="{{ $namaFile ? 'display:flex' : '' }}">
                            <span class="file-label">{{ $namaFile ? '✓ '.basename($namaFile) : '' }}</span>
                            @if($ditolak)
                            <button type="button" class="btn-hapus-file"
                                onclick="hapusFile('{{ $dok['field'] }}','{{ $namaId }}','row_{{ $dok['field'] }}')">✕</button>
                            @endif
                        </div>
                    </div>
                    @if($ditolak)
                    <label class="btn-pilih-file" for="{{ $dok['field'] }}">Pilih File</label>
                    <input type="file" id="{{ $dok['field'] }}" name="{{ $dok['field'] }}"
                        accept="{{ $dok['accept'] }}" style="display:none"
                        onchange="tampilNamaFile(this,'{{ $namaId }}','row_{{ $dok['field'] }}')">
                    @endif
                </div>
                @endforeach
            </div>

            {{-- 4. INFORMASI TUGAS AKHIR --}}
            <div class="sec-card">
                <div class="sec-head"><div class="sec-num">4</div><div class="sec-title">Informasi Tugas Akhir</div></div>
                <div class="form-row cols-1">
                    <div class="form-group">
                        <label>Judul Tugas Akhir</label>
                        <textarea name="judul_ta" rows="2" readonly style="background:#FAFAFA;">{{ $pengajuanJudul->judul_disetujui ?? $pengajuanJudul->judul_1 ?? '-' }}</textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Dosen Pembimbing 1</label>
                        <input type="text" value="{{ $namaDospem1 ?? '-' }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Dosen Pembimbing 2</label>
                        <input type="text" value="{{ $namaDospem2 ?? '-' }}" readonly>
                    </div>
                </div>
            </div>

            {{-- 5. DOKUMEN BIMBINGAN --}}
            <div class="sec-card">
                <div class="sec-head"><div class="sec-num">5</div><div class="sec-title">Dokumen Bimbingan</div></div>
                <div class="form-row">
                @php
                $dokBimbingan = [
                    ['key'=>'bimbingan',   'field'=>'file_bimbingan',   'label'=>'Kartu Bimbingan',        'sub'=>'Scan asli/tanda tangan pembimbing.'],
                    ['key'=>'persetujuan', 'field'=>'file_persetujuan', 'label'=>'Persetujuan Pembimbing',  'sub'=>'Lembar persetujuan seminar TA 1.'],
                ];
                @endphp
                @foreach($dokBimbingan as $dok)
                @php
                    $ditolak  = $isDitolak($dok['key']);
                    $namaId   = 'nama-' . str_replace('_','-', str_replace('file_','',$dok['field']));
                    $boxClass = $isPerbaikan ? ($ditolak ? 'dok-box dok-ditolak' : 'dok-box dok-disabled') : 'dok-box';
                    $namaFile = $draftData[$dok['field']] ?? ($pengajuan->{$dok['field']} ?? null);
                @endphp
                <div id="row_{{ $dok['field'] }}" class="{{ $boxClass }}">
                    @if($isPerbaikan)
                        @if($ditolak)<span class="badge-tolak">✕ Ditolak — Upload Ulang</span>
                        @else<span class="badge-ok">✓ Terverifikasi</span>@endif
                    @endif
                    <div style="font-size:14px;font-weight:700;color:var(--neutral);margin-bottom:6px;">{{ $dok['label'] }}</div>
                    <div style="font-size:12px;color:var(--muted);margin-bottom:12px;">{{ $dok['sub'] }}</div>
                    <div class="file-name" id="{{ $namaId }}" style="margin-bottom:8px;{{ $namaFile ? 'display:flex' : '' }}">
                        <span class="file-label">{{ $namaFile ? '✓ '.basename($namaFile) : '' }}</span>
                        @if($ditolak)
                        <button type="button" class="btn-hapus-file"
                            onclick="hapusFile('{{ $dok['field'] }}','{{ $namaId }}','row_{{ $dok['field'] }}')">✕</button>
                        @endif
                    </div>
                    @if($ditolak)
                    <label class="btn-pilih-file" for="{{ $dok['field'] }}" style="display:inline-block;">Upload File (PDF)</label>
                    <input type="file" id="{{ $dok['field'] }}" name="{{ $dok['field'] }}"
                        accept=".pdf" style="display:none"
                        onchange="tampilNamaFile(this,'{{ $namaId }}','row_{{ $dok['field'] }}')">
                    @endif
                </div>
                @endforeach
                </div>
            </div>

            {{-- 6. LAPORAN TUGAS AKHIR --}}
            <div class="sec-card">
                <div class="sec-head"><div class="sec-num">6</div><div class="sec-title">Laporan Tugas Akhir 1</div></div>
                <div class="form-row">
                @php
                $dokLaporan = [
                    ['key'=>'laporan_doc','field'=>'file_laporan_doc','label'=>'Laporan TA 1 (Docx)','sub'=>'Format .doc / .docx','accept'=>'.doc,.docx'],
                    ['key'=>'laporan_pdf','field'=>'file_laporan_pdf','label'=>'Laporan TA 1 (PDF)', 'sub'=>'Format .pdf',        'accept'=>'.pdf'],
                ];
                @endphp
                @foreach($dokLaporan as $dok)
                @php
                    $ditolak  = $isDitolak($dok['key']);
                    $namaId   = 'nama-' . str_replace('_','-', str_replace('file_','',$dok['field']));
                    $boxClass = $isPerbaikan ? ($ditolak ? 'dok-box dok-ditolak' : 'dok-box dok-disabled') : 'dok-box';
                    $namaFile = $draftData[$dok['field']] ?? ($pengajuan->{$dok['field']} ?? null);
                @endphp
                <div id="row_{{ $dok['field'] }}" class="{{ $boxClass }}">
                    @if($isPerbaikan)
                        @if($ditolak)<span class="badge-tolak">✕ Ditolak — Upload Ulang</span>
                        @else<span class="badge-ok">✓ Terverifikasi</span>@endif
                    @endif
                    <div style="font-size:14px;font-weight:700;color:var(--neutral);margin-bottom:6px;">{{ $dok['label'] }}</div>
                    <div style="font-size:12px;color:var(--muted);margin-bottom:12px;">{{ $dok['sub'] }}</div>
                    <div class="file-name" id="{{ $namaId }}" style="margin-bottom:8px;{{ $namaFile ? 'display:flex' : '' }}">
                        <span class="file-label">{{ $namaFile ? '✓ '.basename($namaFile) : '' }}</span>
                        @if($ditolak)
                        <button type="button" class="btn-hapus-file"
                            onclick="hapusFile('{{ $dok['field'] }}','{{ $namaId }}','row_{{ $dok['field'] }}')">✕</button>
                        @endif
                    </div>
                    @if($ditolak)
                    <label class="btn-pilih-file" for="{{ $dok['field'] }}" style="display:inline-block;">Upload File</label>
                    <input type="file" id="{{ $dok['field'] }}" name="{{ $dok['field'] }}"
                        accept="{{ $dok['accept'] }}" style="display:none"
                        onchange="tampilNamaFile(this,'{{ $namaId }}','row_{{ $dok['field'] }}')">
                    @endif
                </div>
                @endforeach
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="form-footer">
                <div class="footer-info">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="14" height="14" style="vertical-align:middle;margin-right:4px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Terakhir disimpan: Hari ini, {{ now()->format('H:i') }} WIB
                </div>
                <div class="footer-btns">
                    @if(!$isPerbaikan)
                    <button type="button" class="btn-draft" onclick="simpanDraft()">Simpan Draft</button>
                    @endif
                    <button type="button" class="btn-ajukan" onclick="showKonfirmasi()">
                        {{ $isPerbaikan ? 'Ajukan Ulang' : 'Ajukan Seminar' }}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>
            </div>

        </div>

        {{-- SIDEBAR --}}
        <div>
            <div class="status-card">
                <div class="status-title">Status Pendaftaran</div>
                @php $steps = ['Informasi Mahasiswa','Informasi Akademik','Dokumen Akademik','Informasi Tugas Akhir','Dokumen Bimbingan','Laporan Tugas Akhir 1']; @endphp
                @foreach($steps as $i => $step)
                <div class="status-item" id="step-item-{{ $i }}">
                    <div class="status-dot" id="step-dot-{{ $i }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#fff" width="11" height="11" style="display:none" id="step-check-{{ $i }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                    {{ $step }}
                </div>
                @endforeach
                <div class="progress-wrap">
                    <div class="progress-label"><span>Progress Pengisian</span><span id="progressPersen">0%</span></div>
                    <div class="progress-bar-bg"><div class="progress-bar-fill" id="progressBar" style="width:0%"></div></div>
                </div>
            </div>
        </div>

    </div>

    <div id="draftNotif" style="display:none;position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:#fff;border:1.5px solid #16a34a;color:#16a34a;font-size:13px;font-weight:600;padding:12px 24px;border-radius:99px;box-shadow:0 4px 20px rgba(0,0,0,0.12);z-index:9999;white-space:nowrap;"></div>

</form>

<script>
const isPerbaikan = {{ $isPerbaikan ? 'true' : 'false' }};
const tolakKeys   = {!! $isPerbaikan ? json_encode(array_keys(array_filter($statusDokumen, fn($v) => $v === 'tolak'))) : '[]' !!};

const fieldWajib = isPerbaikan ? [] : ['semester','dosen_wali','ipk','total_sks','sks_semester','total_sks_akumulasi'];
const fileWajib  = isPerbaikan
    ? tolakKeys.map(k => ({khs:'file_khs',krs:'file_krs',spp:'file_spp',bimbingan:'file_bimbingan',persetujuan:'file_persetujuan',laporan_doc:'file_laporan_doc',laporan_pdf:'file_laporan_pdf'})[k]).filter(Boolean)
    : ['file_khs','file_krs','file_spp','file_bimbingan','file_persetujuan','file_laporan_doc','file_laporan_pdf'];

const labelFile = {
    file_khs: 'Transkrip Nilai (KHS)',
    file_krs: 'Kartu Rencana Studi (KRS)',
    file_spp: 'Bukti Lunas SPP',
    file_bimbingan: 'Kartu Bimbingan',
    file_persetujuan: 'Lembar Persetujuan Pembimbing',
    file_laporan_doc: 'Laporan TA 1 (Docx)',
    file_laporan_pdf: 'Laporan TA 1 (PDF)',
};
const labelField = {
    semester: 'Semester',
    dosen_wali: 'Dosen Wali',
    ipk: 'IPK',
    total_sks: 'Total SKS Diambil',
    sks_semester: 'SKS Semester Berjalan',
    total_sks_akumulasi: 'Total SKS (KHS + KRS)',
};

function fileAdaIsi(id) {
    const el     = document.getElementById(id);
    const namaEl = document.getElementById('nama-' + id.replace('file_','').replace(/_/g,'-'));
    return (namaEl && namaEl.style.display === 'flex') || (el && el.files && el.files.length > 0);
}

const stepChecks = [
    () => isPerbaikan ? true : !!(document.querySelector('[name="semester"]')?.value.trim() && document.querySelector('[name="dosen_wali"]')?.value.trim()),
    () => {
        if (isPerbaikan) return true;
        const ipk = document.querySelector('[name="ipk"]')?.value.trim();
        const sks = Number(document.querySelector('[name="total_sks"]')?.value);
        const sem = Number(document.querySelector('[name="sks_semester"]')?.value);
        const aku = Number(document.querySelector('[name="total_sks_akumulasi"]')?.value);
        return !!(ipk && sks > 0 && sem > 0 && aku > 0);
    },
    () => ['file_khs','file_krs','file_spp'].every(id => (isPerbaikan && !fileWajib.includes(id)) ? true : fileAdaIsi(id)),
    () => { const j = document.querySelector('[name="judul_ta"]')?.value.trim(); return !!(j && j !== '-'); },
    () => ['file_bimbingan','file_persetujuan'].every(id => (isPerbaikan && !fileWajib.includes(id)) ? true : fileAdaIsi(id)),
    () => ['file_laporan_doc','file_laporan_pdf'].every(id => (isPerbaikan && !fileWajib.includes(id)) ? true : fileAdaIsi(id)),
];

function hitungProgress() {
    let done = 0;
    stepChecks.forEach((check, i) => {
        const ok   = check();
        const dot  = document.getElementById('step-dot-' + i);
        const chk  = document.getElementById('step-check-' + i);
        const item = document.getElementById('step-item-' + i);
        if (ok) { done++; dot.style.background='#C9A227'; dot.style.borderColor='#C9A227'; chk.style.display='block'; item.classList.add('active'); }
        else    { dot.style.background=''; dot.style.borderColor=''; chk.style.display='none'; item.classList.remove('active'); }
    });
    const p = Math.round((done / stepChecks.length) * 100);
    document.getElementById('progressPersen').textContent = p + '%';
    document.getElementById('progressBar').style.width    = p + '%';
}

function tampilNamaFile(input, targetId, rowId) {
    const el = document.getElementById(targetId);
    if (input.files && input.files[0]) {
        const label = el.querySelector('.file-label');
        if (label) label.textContent = '✓ ' + input.files[0].name;
        el.style.display = 'flex';
        if (rowId) { const row = document.getElementById(rowId); if (row) { row.style.borderColor=''; row.style.background=''; const e=row.querySelector('.err-msg'); if(e)e.remove(); } }
        hitungProgress();
    }
}

function hapusFile(inputId, targetId, rowId) {
    const input = document.getElementById(inputId);
    if (input) input.value = '';
    const el = document.getElementById(targetId);
    if (el) { const label = el.querySelector('.file-label'); if (label) label.textContent=''; el.style.display='none'; }
    if (rowId) { const row = document.getElementById(rowId); if (row) { row.style.borderColor=''; row.style.background=''; const e=row.querySelector('.err-msg'); if(e)e.remove(); } }
    hitungProgress();
}

function validasiForm() {
    document.querySelectorAll('.err-msg').forEach(el => el.remove());
    fieldWajib.forEach(name => { const el=document.querySelector(`[name="${name}"]`); if(el){el.classList.remove('field-error');el.style.borderColor='';el.style.background='';} });
    fileWajib.forEach(id => { const row=document.getElementById('row_'+id); if(row){row.style.borderColor='';row.style.background='';} });

    let valid = true;
    const missing = [];

    for (const name of fieldWajib) {
        const el      = document.querySelector(`[name="${name}"]`);
        const val     = el ? el.value.trim() : '';
        const isAngka = el && el.type === 'number';
        const invalid = isAngka ? (val===''||Number(val)<=0) : (val==='');
        if (!el || invalid) {
            if (el) {
                el.classList.add('field-error');
                let msg = el.parentElement.querySelector('.err-msg');
                if (!msg) { msg=document.createElement('span'); msg.className='err-msg'; el.parentElement.appendChild(msg); }
                msg.textContent = isAngka ? 'Harus diisi dan lebih dari 0' : 'Field ini wajib diisi';
            }
            missing.push(labelField[name] || name);
            valid = false;
        }
    }

    for (const id of fileWajib) {
        if (!fileAdaIsi(id)) {
            const row = document.getElementById('row_' + id);
            if (row) {
                row.style.borderColor = '#dc2626'; row.style.background = '#fff5f5';
                const target = row.querySelector('.dok-info') || row;
                let msg = target.querySelector('.err-msg');
                if (!msg) { msg=document.createElement('span'); msg.className='err-msg'; target.appendChild(msg); }
                msg.textContent = 'Dokumen wajib diunggah';
            }
            missing.push(labelFile[id] || id);
            valid = false;
        }
    }

    if (!valid) {
        const f = document.querySelector('.field-error') || document.querySelector('[style*="dc2626"]');
        if (f) f.scrollIntoView({ behavior:'smooth', block:'center' });

        const listHtml = missing.map(m => `<li style="text-align:left;margin-bottom:4px;">${m}</li>`).join('');
        Swal.fire({
            title: 'Form Belum Lengkap',
            html: `<div style="font-size:13px;color:#374151;margin-bottom:8px;">Mohon lengkapi data/dokumen berikut sebelum mengajukan:</div>
                   <ul style="padding-left:20px;margin:0;font-size:13px;font-weight:600;color:#dc2626;">${listHtml}</ul>`,
            icon: 'warning',
            confirmButtonColor: '#FACC15',
            confirmButtonText: 'Oke, Saya Lengkapi',
        });
    }

    return valid;
}

function showKonfirmasi() {
    if (!validasiForm()) return;

    Swal.fire({
        title: isPerbaikan ? 'Ajukan Ulang Dokumen?' : 'Kirim Pengajuan Seminar?',
        text: isPerbaikan
            ? 'Pastikan semua dokumen yang ditolak sudah diupload ulang dengan benar.'
            : 'Pastikan semua data dan dokumen sudah lengkap dan benar sebelum dikirim ke admin.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#FACC15',
        cancelButtonColor:  '#d1d5db',
        confirmButtonText:  isPerbaikan ? 'Ya, Ajukan Ulang' : 'Ya, Kirim Sekarang',
        cancelButtonText:   'Cek Lagi',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formAdm').submit();
        }
    });
}

function simpanDraft() {
    const btn = document.querySelector('.btn-draft');
    btn.textContent = 'Menyimpan...'; btn.disabled = true;
    const formData = new FormData(document.getElementById('formAdm'));
    formData.append('_token', '{{ csrf_token() }}');
    fetch('{{ route("seminar.draft") }}', { method:'POST', body:formData })
        .then(r => r.json())
        .then(data => {
            btn.textContent = 'Simpan Draft'; btn.disabled = false;
            if (data.success) {
                const notif = document.getElementById('draftNotif');
                notif.textContent   = '✓ Draft disimpan: ' + data.saved_at;
                notif.style.display = 'block';
                setTimeout(() => { window.location.href = '{{ route("seminar.daftar") }}'; }, 1500);
            }
        })
        .catch(() => { btn.textContent='Simpan Draft'; btn.disabled=false; alert('Gagal menyimpan draft.'); });
}

document.querySelectorAll('input[type=text], input[type=number], textarea').forEach(el => {
    el.addEventListener('input', function() {
        this.classList.remove('field-error'); this.style.borderColor=''; this.style.background='';
        const msg = this.parentElement.querySelector('.err-msg'); if(msg) msg.remove();
        hitungProgress();
    });
});

document.addEventListener('DOMContentLoaded', hitungProgress);
</script>

@endsection