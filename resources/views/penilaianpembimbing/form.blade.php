@extends('layouts.app')

@section('content')

{{-- LOADING OVERLAY --}}
<div id="loadingOverlay" style="
    position:fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(255,255,255,0.9); z-index:9999;
    display:flex; flex-direction:column;
    align-items:center; justify-content:center;">
    <div class="spinner-border mb-3" role="status"
        style="width:3rem;height:3rem;color:#FACC15;border-width:3px;">
        <span class="visually-hidden">Loading...</span>
    </div>
    <p style="color:#6b7280;font-weight:600;font-family:'Hanken Grotesk',sans-serif;">
        Sedang memuat data...
    </p>
</div>

@php
    $isSubmitted = $penilaian && $penilaian->status === 'submitted';
    $isDraft     = $penilaian && $penilaian->status === 'draft';
    $readOnly    = $isSubmitted && !$bisaEdit;
@endphp

{{-- HERO --}}
<div style="
    background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 55%, #FDE68A 100%);
    border-radius: 20px; padding: 36px; margin-bottom: 28px;
    position:relative; overflow:hidden; min-height:130px;
    display:flex; align-items:center; justify-content:space-between; gap:16px;">
    <div style="position:absolute;right:-20px;top:-40px;width:220px;height:220px;
                background:rgba(250,204,21,0.15);border-radius:50%;pointer-events:none;"></div>
    <div style="position:absolute;right:90px;bottom:-50px;width:150px;height:150px;
                background:rgba(250,204,21,0.10);border-radius:50%;pointer-events:none;"></div>

    <div style="position:relative;z-index:2;">
        <h2 style="font-size:1.6rem;font-weight:800;color:#735C00;margin-bottom:4px;">
            @if($readOnly)
                Detail Penilaian Seminar TA-1
            @elseif($isDraft)
                Lanjutkan Penilaian Seminar TA-1
            @else
                Form Penilaian Seminar TA-1
            @endif
        </h2>
        <p style="color:#92741A;font-size:0.85rem;margin:0;">
            @if($readOnly)
                Rekap penilaian yang telah disubmit untuk mahasiswa ini.
            @else
                Silakan berikan penilaian objektif berdasarkan performa mahasiswa dalam seminar Tugas Akhir.
            @endif
        </p>
    </div>
</div>

{{-- TOMBOL KEMBALI --}}
<a href="{{ route('penilaian.index') }}" style="
    display:inline-flex;align-items:center;gap:6px;
    color:#92741A;font-size:0.85rem;font-weight:600;
    text-decoration:none;margin-bottom:22px;">
    <i class="fa fa-arrow-left"></i> Kembali
</a>

{{-- STATUS BADGE --}}
@if($isSubmitted)
<div style="background:#F0FDF4;border:1px solid #bbf7d0;border-radius:12px;padding:14px 18px;
            margin-bottom:20px;color:#15803d;font-weight:600;font-size:0.88rem;
            display:flex;align-items:center;gap:8px;">
    <i class="fa fa-circle-check"></i>
    Penilaian telah disubmit.
    @if($bisaEdit) Masih bisa diedit hingga deadline berakhir. @else Batas waktu edit telah berakhir. @endif
</div>
@elseif($isDraft)
<div style="background:#FEFCE8;border:1px solid #FDE68A;border-radius:12px;padding:14px 18px;
            margin-bottom:20px;color:#92741A;font-weight:600;font-size:0.88rem;
            display:flex;align-items:center;gap:8px;">
    <i class="fa fa-file-pen"></i> Draft tersimpan. Lengkapi dan submit penilaian Anda.
</div>
@endif

@if(session('success'))
<div style="background:#F0FDF4;border:1px solid #bbf7d0;border-radius:12px;padding:14px 18px;
            margin-bottom:20px;color:#15803d;font-weight:600;font-size:0.88rem;">
    <i class="fa fa-circle-check" style="margin-right:6px;"></i> {{ session('success') }}
</div>
@endif
@if(session('info'))
<div style="background:#EFF6FF;border:1px solid #bfdbfe;border-radius:12px;padding:14px 18px;
            margin-bottom:20px;color:#1d4ed8;font-weight:600;font-size:0.88rem;">
    <i class="fa fa-circle-info" style="margin-right:6px;"></i> {{ session('info') }}
</div>
@endif

@if(!$readOnly)
<form method="POST" action="{{ route('penilaian.pembimbing.store', $proposal->id) }}" id="formPenilaian">
@csrf
@endif

{{-- INFORMASI MAHASISWA --}}
<div style="background:#fff;border-radius:14px;padding:24px;margin-bottom:20px;
            border:1px solid #f0f0f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
    <div style="font-size:0.72rem;font-weight:700;color:#9ca3af;text-transform:uppercase;
                letter-spacing:1px;margin-bottom:16px;border-left:3px solid #FACC15;padding-left:10px;">
        Informasi Mahasiswa
    </div>
    <div class="row g-3">
        <div class="col-md-4">
            <label style="font-size:0.75rem;font-weight:600;color:#6b7280;margin-bottom:5px;display:block;">NIM Mahasiswa</label>
            <div style="background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:10px 14px;font-size:0.88rem;color:#374151;font-weight:600;">
                <i class="fa fa-id-card" style="color:#92741A;margin-right:8px;"></i>{{ $proposal->nim_nid }}
            </div>
        </div>
        <div class="col-md-8">
            <label style="font-size:0.75rem;font-weight:600;color:#6b7280;margin-bottom:5px;display:block;">Nama Mahasiswa</label>
            <div style="background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:10px 14px;font-size:0.88rem;color:#374151;font-weight:600;">
                <i class="fa fa-user" style="color:#92741A;margin-right:8px;"></i>{{ $proposal->nama_mahasiswa }}
            </div>
        </div>
        <div class="col-12">
            <label style="font-size:0.75rem;font-weight:600;color:#6b7280;margin-bottom:5px;display:block;">Judul Tugas Akhir</label>
            <div style="background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:10px 14px;font-size:0.88rem;color:#374151;line-height:1.6;">
                <i class="fa fa-book" style="color:#92741A;margin-right:8px;"></i>{{ $proposal->judul_ta }}
            </div>
        </div>
        <div class="col-md-6">
            <label style="font-size:0.75rem;font-weight:600;color:#6b7280;margin-bottom:5px;display:block;">Nama Dosen Pembimbing</label>
            <div style="background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:10px 14px;font-size:0.88rem;color:#374151;font-weight:600;">
                <i class="fa fa-chalkboard-user" style="color:#92741A;margin-right:8px;"></i>{{ $dosenPembimbing->nama ?? '-' }}
            </div>
        </div>
        <div class="col-md-3">
            <label style="font-size:0.75rem;font-weight:600;color:#6b7280;margin-bottom:5px;display:block;">NID</label>
            <div style="background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:10px 14px;font-size:0.88rem;color:#374151;">
                {{ $dosenPembimbing->nim_nid ?? '-' }}
            </div>
        </div>
        <div class="col-md-3">
            <label style="font-size:0.75rem;font-weight:600;color:#6b7280;margin-bottom:5px;display:block;">Sebagai</label>
            <div style="background:#FEF9C3;border:1px solid #FDE68A;border-radius:10px;padding:10px 14px;font-size:0.85rem;color:#92741A;font-weight:700;">
                {{ $urutanPembimbing }}
            </div>
        </div>
    </div>
</div>

{{-- BAGIAN A --}}
@php
$komponenA = [
    ['key'=>'nilai_kualitas_bimbingan',    'label'=>'Keaktifan Bimbingan',            'desc'=>'Frekuensi dan inisiatif mahasiswa dalam berkonsultasi',        'maks'=>10],
    ['key'=>'nilai_kemampuan_penelusuran', 'label'=>'Kemampuan Penyelesaian Tugas',    'desc'=>'Ketepatan waktu dan kualitas pengerjaan arahan dosen',         'maks'=>15],
    ['key'=>'nilai_penggunaan_teori',      'label'=>'Penguasaan Teori & Referensi',    'desc'=>'Pemahaman dasar teori yang digunakan dalam penelitian',        'maks'=>15],
    ['key'=>'nilai_dokumentasi_produk',    'label'=>'Dokumentasi Produk/Penelitian',   'desc'=>'Kelengkapan data, kode, atau artefak penelitian',              'maks'=>25],
    ['key'=>'nilai_kesesuaian_target',     'label'=>'Kesesuaian Hasil dengan Target',  'desc'=>'Pencapaian milestone yang ditetapkan di awal bimbingan',      'maks'=>35],
];
$totalMaksA = array_sum(array_column($komponenA, 'maks'));
@endphp

<div style="background:#fff;border-radius:14px;padding:24px;margin-bottom:20px;
            border:1px solid #f0f0f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:6px;">
        <div style="width:32px;height:32px;background:#FFFBEB;border:2px solid #FACC15;border-radius:8px;
                    display:flex;align-items:center;justify-content:center;font-weight:800;color:#735C00;font-size:0.85rem;">A</div>
        <div>
            <div style="font-size:1rem;font-weight:800;color:#111827;">Penilaian Proses Bimbingan</div>
            <div style="font-size:0.78rem;color:#9ca3af;">Evaluasi berdasarkan interaksi selama masa bimbingan</div>
        </div>
    </div>
    <hr style="border-color:#F3F4F6;margin:16px 0;">

    @foreach($komponenA as $k)
    <div style="background:#F9FAFB;border-radius:12px;padding:16px 20px;margin-bottom:10px;">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
            <div style="flex:1;">
                <div style="font-size:0.9rem;font-weight:700;color:#111827;margin-bottom:2px;">{{ $k['label'] }}</div>
                <div style="font-size:0.78rem;color:#9ca3af;">{{ $k['desc'] }}</div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                <span style="font-size:0.72rem;color:#9ca3af;white-space:nowrap;">Maks {{ $k['maks'] }} poin</span>
                @if($readOnly)
                    <div style="width:70px;padding:8px 10px;border:1.5px solid #E5E7EB;border-radius:8px;
                                font-size:0.88rem;font-weight:800;text-align:center;color:#735C00;background:#FFFBEB;">
                        {{ $penilaian->{$k['key']} ?? 0 }}
                    </div>
                @else
                    <input type="number" name="{{ $k['key'] }}"
                        min="1" max="{{ $k['maks'] }}" step="1"
                        value="{{ old($k['key'], $penilaian->{$k['key']} ?? '') }}"
                        class="nilai-input-a"
                        style="width:70px;padding:8px 10px;border:1.5px solid #E5E7EB;border-radius:8px;
                               font-size:0.88rem;font-weight:700;text-align:center;color:#374151;outline:none;"
                        placeholder="0"
                        oninput="clampNilai(this); hitungTotal()">
                @endif
            </div>
        </div>
    </div>
    @endforeach

    <div style="display:flex;justify-content:flex-end;align-items:center;gap:12px;
                margin-top:4px;padding:12px 16px;background:#FFFBEB;border-radius:10px;">
        <span style="font-size:0.82rem;font-weight:700;color:#92741A;">TOTAL SKOR A</span>
        <span id="totalA" style="font-size:1.4rem;font-weight:800;color:#735C00;min-width:60px;text-align:right;">
            {{ $penilaian ? ($penilaian->total_a ?? 0) : 0 }}
        </span>
        <span style="font-size:0.82rem;color:#9ca3af;">/ {{ $totalMaksA }}</span>
    </div>
</div>

{{-- BAGIAN B --}}
@php
$komponenB = [
    ['key'=>'nilai_teknik_presentasi',    'label'=>'Teknik Presentasi',               'desc'=>'Gaya penyampaian, intonasi, dan manajemen waktu',           'maks'=>15],
    ['key'=>'nilai_dokumentasi_proposal', 'label'=>'Dokumentasi Penulisan (Proposal)', 'desc'=>'Kualitas tata tulis dan sistematika laporan',                'maks'=>20],
    ['key'=>'nilai_kemanfaatan_teori',    'label'=>'Pemahaman Teori/Metode',           'desc'=>'Ketajaman analisis dan pembenaran pemilihan metode',        'maks'=>30],
    ['key'=>'nilai_pemahaman_kebutuhan',  'label'=>'Pemahaman Kebutuhan/Keluaran',     'desc'=>'Kejelasan output yang akan dihasilkan pada TA-2',           'maks'=>35],
];
$totalMaksB = array_sum(array_column($komponenB, 'maks'));
@endphp

<div style="background:#fff;border-radius:14px;padding:24px;margin-bottom:20px;
            border:1px solid #f0f0f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:6px;">
        <div style="width:32px;height:32px;background:#FFFBEB;border:2px solid #FACC15;border-radius:8px;
                    display:flex;align-items:center;justify-content:center;font-weight:800;color:#735C00;font-size:0.85rem;">B</div>
        <div>
            <div style="font-size:1rem;font-weight:800;color:#111827;">Penilaian Seminar TA-1</div>
            <div style="font-size:0.78rem;color:#9ca3af;">Evaluasi berdasarkan performa mahasiswa saat presentasi</div>
        </div>
    </div>
    <hr style="border-color:#F3F4F6;margin:16px 0;">

    @foreach($komponenB as $k)
    <div style="background:#F9FAFB;border-radius:12px;padding:16px 20px;margin-bottom:10px;">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
            <div style="flex:1;">
                <div style="font-size:0.9rem;font-weight:700;color:#111827;margin-bottom:2px;">{{ $k['label'] }}</div>
                <div style="font-size:0.78rem;color:#9ca3af;">{{ $k['desc'] }}</div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                <span style="font-size:0.72rem;color:#9ca3af;white-space:nowrap;">Maks {{ $k['maks'] }} poin</span>
                @if($readOnly)
                    <div style="width:70px;padding:8px 10px;border:1.5px solid #E5E7EB;border-radius:8px;
                                font-size:0.88rem;font-weight:800;text-align:center;color:#735C00;background:#FFFBEB;">
                        {{ $penilaian->{$k['key']} ?? 0 }}
                    </div>
                @else
                    <input type="number" name="{{ $k['key'] }}"
                        min="1" max="{{ $k['maks'] }}" step="1"
                        value="{{ old($k['key'], $penilaian->{$k['key']} ?? '') }}"
                        class="nilai-input-b"
                        style="width:70px;padding:8px 10px;border:1.5px solid #E5E7EB;border-radius:8px;
                               font-size:0.88rem;font-weight:700;text-align:center;color:#374151;outline:none;"
                        placeholder="0"
                        oninput="clampNilai(this); hitungTotal()">
                @endif
            </div>
        </div>
    </div>
    @endforeach

    <div style="display:flex;justify-content:flex-end;align-items:center;gap:12px;
                margin-top:4px;padding:12px 16px;background:#FFFBEB;border-radius:10px;">
        <span style="font-size:0.82rem;font-weight:700;color:#92741A;">TOTAL SKOR B</span>
        <span id="totalB" style="font-size:1.4rem;font-weight:800;color:#735C00;min-width:60px;text-align:right;">
            {{ $penilaian ? ($penilaian->total_b ?? 0) : 0 }}
        </span>
        <span style="font-size:0.82rem;color:#9ca3af;">/ {{ $totalMaksB }}</span>
    </div>
</div>

{{-- AKUMULASI --}}
<div style="background:#fff;border-radius:14px;padding:20px 24px;margin-bottom:20px;
            border:1px solid #f0f0f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <div style="font-size:0.9rem;font-weight:700;color:#374151;">Akumulasi Nilai</div>
            <div style="font-size:0.75rem;color:#9ca3af;">
                @if($readOnly) Nilai final yang telah disubmit @else Nilai otomatis dihitung berdasarkan input di atas @endif
            </div>
        </div>
        <div style="display:flex;align-items:baseline;gap:6px;">
            <span id="nilaiAkhir" style="font-size:2.8rem;font-weight:800;color:#735C00;line-height:1;">
                {{ $penilaian ? ($penilaian->nilai_akhir ?? 0) : 0 }}
            </span>
            <span style="font-size:1rem;color:#9ca3af;font-weight:600;">/ 200</span>
        </div>
    </div>
</div>

{{-- KELAYAKAN --}}
<div style="background:#fff;border-radius:14px;padding:24px;margin-bottom:20px;
            border:1px solid #f0f0f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
    <div style="font-size:0.72rem;font-weight:700;color:#9ca3af;text-transform:uppercase;
                letter-spacing:1px;margin-bottom:16px;border-left:3px solid #FACC15;padding-left:10px;">
        Kelayakan Melanjutkan TA-2
    </div>
    @if($readOnly)
        @if($penilaian->kelayakan === 'layak')
        <div style="display:flex;align-items:center;gap:12px;background:#F0FDF4;border:1.5px solid #86efac;border-radius:12px;padding:16px;">
            <div style="width:40px;height:40px;background:#16a34a;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fa fa-check" style="color:#fff;"></i>
            </div>
            <div>
                <div style="font-size:1rem;font-weight:800;color:#15803d;">Layak Melanjutkan TA-2</div>
                <div style="font-size:0.78rem;color:#9ca3af;">Mahasiswa dinyatakan layak melanjutkan ke tahap selanjutnya</div>
            </div>
        </div>
        @else
        <div style="display:flex;align-items:center;gap:12px;background:#FEF2F2;border:1.5px solid #fca5a5;border-radius:12px;padding:16px;">
            <div style="width:40px;height:40px;background:#dc2626;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fa fa-xmark" style="color:#fff;"></i>
            </div>
            <div>
                <div style="font-size:1rem;font-weight:800;color:#dc2626;">Belum Layak Melanjutkan TA-2</div>
                <div style="font-size:0.78rem;color:#9ca3af;">Mahasiswa belum memenuhi kriteria untuk melanjutkan ke tahap berikutnya</div>
            </div>
        </div>
        @endif
    @else
        <div class="row g-3">
            <div class="col-md-6">
                <label style="cursor:pointer;display:block;" onclick="setKelayakan('layak')">
                    <div id="card-layak" style="border:2px solid {{ old('kelayakan', $penilaian->kelayakan ?? '') === 'layak' ? '#16a34a' : '#E5E7EB' }};border-radius:14px;padding:18px 20px;background:{{ old('kelayakan', $penilaian->kelayakan ?? '') === 'layak' ? '#F0FDF4' : '#fff' }};transition:all 0.2s;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div id="ico-layak" style="width:36px;height:36px;border-radius:50%;background:{{ old('kelayakan', $penilaian->kelayakan ?? '') === 'layak' ? '#16a34a' : '#E5E7EB' }};display:flex;align-items:center;justify-content:center;">
                                <i class="fa fa-check" style="color:#fff;font-size:0.85rem;"></i>
                            </div>
                            <div>
                                <div style="font-size:0.92rem;font-weight:700;color:#15803d;">Layak Melanjutkan TA-2</div>
                                <div style="font-size:0.75rem;color:#9ca3af;">Mahasiswa dinyatakan layak melanjutkan ke tahap selanjutnya</div>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
            <div class="col-md-6">
                <label style="cursor:pointer;display:block;" onclick="setKelayakan('tidak_layak')">
                    <div id="card-tidak" style="border:2px solid {{ old('kelayakan', $penilaian->kelayakan ?? '') === 'tidak_layak' ? '#dc2626' : '#E5E7EB' }};border-radius:14px;padding:18px 20px;background:{{ old('kelayakan', $penilaian->kelayakan ?? '') === 'tidak_layak' ? '#FEF2F2' : '#fff' }};transition:all 0.2s;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div id="ico-tidak" style="width:36px;height:36px;border-radius:50%;background:{{ old('kelayakan', $penilaian->kelayakan ?? '') === 'tidak_layak' ? '#dc2626' : '#E5E7EB' }};display:flex;align-items:center;justify-content:center;">
                                <i class="fa fa-xmark" style="color:#fff;font-size:0.85rem;"></i>
                            </div>
                            <div>
                                <div style="font-size:0.92rem;font-weight:700;color:#dc2626;">Belum Layak Melanjutkan TA-2</div>
                                <div style="font-size:0.75rem;color:#9ca3af;">Mahasiswa belum memenuhi kriteria untuk melanjutkan</div>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </div>
        <input type="hidden" name="kelayakan" id="inputKelayakan" value="{{ old('kelayakan', $penilaian->kelayakan ?? '') }}">
        @error('kelayakan')
        <div style="color:#dc2626;font-size:0.78rem;margin-top:8px;">{{ $message }}</div>
        @enderror
    @endif
</div>

{{-- CATATAN --}}
<div style="background:#fff;border-radius:14px;padding:24px;margin-bottom:24px;
            border:1px solid #f0f0f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
    <div style="font-size:0.72rem;font-weight:700;color:#9ca3af;text-transform:uppercase;
                letter-spacing:1px;margin-bottom:12px;border-left:3px solid #FACC15;padding-left:10px;">
        Catatan Tambahan
    </div>
    @if($readOnly)
        @if($penilaian->catatan)
            <p style="font-size:0.88rem;color:#374151;line-height:1.7;margin:0;">{{ $penilaian->catatan }}</p>
        @else
            <p style="font-size:0.88rem;color:#9ca3af;margin:0;font-style:italic;">Tidak ada catatan.</p>
        @endif
    @else
        <textarea name="catatan" rows="4"
            placeholder="Tuliskan masukan atau catatan perbaikan untuk mahasiswa..."
            style="width:100%;border:1px solid #E5E7EB;border-radius:10px;padding:12px 14px;
                   font-size:0.88rem;font-family:'Hanken Grotesk',sans-serif;
                   color:#374151;outline:none;resize:vertical;line-height:1.6;">{{ old('catatan', $penilaian->catatan ?? '') }}</textarea>
    @endif
</div>

{{-- TOMBOL --}}
@if(!$readOnly)
<div style="display:flex;justify-content:flex-end;gap:12px;margin-bottom:32px;">
    <button type="button" onclick="submitForm('draft')"
        style="padding:12px 28px;background:#fff;border:1.5px solid #D1D5DB;border-radius:12px;
               font-size:0.88rem;font-weight:700;color:#6b7280;cursor:pointer;">
        <i class="fa fa-floppy-disk" style="margin-right:6px;"></i> Simpan Draft
    </button>
    <button type="button" onclick="konfirmasiSubmit()"
        style="padding:12px 28px;background:linear-gradient(135deg,#92741A,#735C00);
               border:none;border-radius:12px;font-size:0.88rem;font-weight:700;
               color:#fff;cursor:pointer;display:flex;align-items:center;gap:8px;">
        <i class="fa fa-paper-plane"></i> Submit Penilaian
    </button>
</div>
<input type="hidden" name="aksi" id="inputAksi" value="draft">
</form>
@endif

{{-- MODAL KONFIRMASI SUBMIT --}}
<div id="modalKonfirmasi" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:20px;padding:32px;max-width:440px;width:90%;text-align:center;box-shadow:0 12px 40px rgba(0,0,0,0.15);">
        <div style="width:56px;height:56px;background:#FFFBEB;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="fa fa-paper-plane" style="font-size:1.4rem;color:#92741A;"></i>
        </div>
        <h5 style="font-weight:800;color:#111827;margin-bottom:4px;">Submit Penilaian?</h5>
        <p style="font-size:0.82rem;color:#9ca3af;margin-bottom:20px;">Pastikan nilai berikut sudah benar sebelum disubmit.</p>

        <div style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:14px;padding:18px 20px;margin-bottom:16px;text-align:left;">
            <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px dashed #FDE68A;margin-bottom:10px;">
                <div>
                    <div style="font-size:0.8rem;font-weight:700;color:#92741A;">Skor A</div>
                    <div style="font-size:0.72rem;color:#b49a30;">Proses Bimbingan</div>
                </div>
                <div style="display:flex;align-items:baseline;gap:4px;">
                    <span id="modalSkorA" style="font-size:1.25rem;font-weight:800;color:#735C00;">0</span>
                    <span id="modalMaksA" style="font-size:0.78rem;color:#9ca3af;font-weight:600;">/ {{ $totalMaksA }}</span>
                </div>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:12px;border-bottom:1px dashed #FDE68A;margin-bottom:12px;">
                <div>
                    <div style="font-size:0.8rem;font-weight:700;color:#92741A;">Skor B</div>
                    <div style="font-size:0.72rem;color:#b49a30;">Seminar TA-1</div>
                </div>
                <div style="display:flex;align-items:baseline;gap:4px;">
                    <span id="modalSkorB" style="font-size:1.25rem;font-weight:800;color:#735C00;">0</span>
                    <span id="modalMaksB" style="font-size:0.78rem;color:#9ca3af;font-weight:600;">/ {{ $totalMaksB }}</span>
                </div>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div style="font-size:0.88rem;font-weight:800;color:#735C00;">Total Nilai Akhir</div>
                <div style="display:flex;align-items:baseline;gap:4px;">
                    <span id="modalNilaiAkhir" style="font-size:2rem;font-weight:800;color:#735C00;line-height:1;">0</span>
                    <span style="font-size:0.85rem;color:#9ca3af;font-weight:600;">/ 200</span>
                </div>
            </div>
        </div>

        <p style="font-size:0.78rem;color:#9ca3af;margin-bottom:20px;">
            Penilaian yang sudah disubmit masih bisa diedit selama deadline belum berakhir.
        </p>
        <div style="display:flex;gap:10px;justify-content:center;">
            <button onclick="tutupModal()"
                style="padding:10px 24px;border:1.5px solid #D1D5DB;border-radius:10px;background:#fff;font-weight:600;color:#6b7280;cursor:pointer;">
                Batal
            </button>
            <button onclick="submitForm('submitted')"
                style="padding:10px 24px;background:#735C00;border:none;border-radius:10px;font-weight:700;color:#fff;cursor:pointer;">
                Ya, Submit
            </button>
        </div>
    </div>
</div>

<script>
    function clampNilai(input) {
        const min = parseInt(input.min) || 1;
        const max = parseInt(input.max) || 100;
        let val = parseInt(input.value);

        if (isNaN(val) || input.value === '') return;

        if (val > max) {
            input.value = max;
            input.style.borderColor = '#dc2626';
            input.style.color = '#dc2626';
            setTimeout(() => {
                input.style.borderColor = '#E5E7EB';
                input.style.color = '#374151';
            }, 600);
        } else if (val < min) {
            input.value = min;
        }
    }

    function hitungTotal() {
        let sumA = 0, sumB = 0;
        document.querySelectorAll('.nilai-input-a').forEach(i => sumA += parseInt(i.value || 0));
        document.querySelectorAll('.nilai-input-b').forEach(i => sumB += parseInt(i.value || 0));
        document.getElementById('totalA').textContent     = sumA;
        document.getElementById('totalB').textContent     = sumB;
        document.getElementById('nilaiAkhir').textContent = sumA + sumB;
    }

    function setKelayakan(val) {
        document.getElementById('inputKelayakan').value = val;
        const isLayak = val === 'layak';
        document.getElementById('card-layak').style.border     = isLayak ? '2px solid #16a34a' : '2px solid #E5E7EB';
        document.getElementById('card-layak').style.background = isLayak ? '#F0FDF4' : '#fff';
        document.getElementById('ico-layak').style.background  = isLayak ? '#16a34a' : '#E5E7EB';
        document.getElementById('card-tidak').style.border     = !isLayak ? '2px solid #dc2626' : '2px solid #E5E7EB';
        document.getElementById('card-tidak').style.background = !isLayak ? '#FEF2F2' : '#fff';
        document.getElementById('ico-tidak').style.background  = !isLayak ? '#dc2626' : '#E5E7EB';
    }

    function konfirmasiSubmit() {
        if (!document.getElementById('inputKelayakan').value) {
            alert('Harap pilih kelayakan mahasiswa terlebih dahulu.');
            return;
        }
        const skorA      = document.getElementById('totalA').textContent.trim();
        const skorB      = document.getElementById('totalB').textContent.trim();
        const nilaiAkhir = document.getElementById('nilaiAkhir').textContent.trim();
        document.getElementById('modalSkorA').textContent      = skorA;
        document.getElementById('modalSkorB').textContent      = skorB;
        document.getElementById('modalNilaiAkhir').textContent = nilaiAkhir;
        document.getElementById('modalKonfirmasi').style.display = 'flex';
    }

    function tutupModal() {
        document.getElementById('modalKonfirmasi').style.display = 'none';
    }

    function submitForm(aksi) {
        document.getElementById('inputAksi').value = aksi;
        document.getElementById('formPenilaian').submit();
    }

    window.addEventListener('load', () => {
        document.getElementById('loadingOverlay').style.display = 'none';
    });
</script>

@endsection