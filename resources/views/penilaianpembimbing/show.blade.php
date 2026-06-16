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
$komponenA = [
    ['key'=>'nilai_kualitas_bimbingan',    'label'=>'Keaktifan Bimbingan',            'maks'=>10],
    ['key'=>'nilai_kemampuan_penelusuran', 'label'=>'Kemampuan Penyelesaian Tugas',    'maks'=>15],
    ['key'=>'nilai_penggunaan_teori',      'label'=>'Penguasaan Teori & Referensi',    'maks'=>15],
    ['key'=>'nilai_dokumentasi_produk',    'label'=>'Dokumentasi Produk/Penelitian',   'maks'=>25],
    ['key'=>'nilai_kesesuaian_target',     'label'=>'Kesesuaian Hasil dengan Target',  'maks'=>35],
];
$komponenB = [
    ['key'=>'nilai_teknik_presentasi',    'label'=>'Teknik Presentasi',                'maks'=>15],
    ['key'=>'nilai_dokumentasi_proposal', 'label'=>'Dokumentasi Penulisan (Proposal)', 'maks'=>20],
    ['key'=>'nilai_kemanfaatan_teori',    'label'=>'Pemahaman Teori/Metode',           'maks'=>30],
    ['key'=>'nilai_pemahaman_kebutuhan',  'label'=>'Pemahaman Kebutuhan/Keluaran',     'maks'=>35],
];

$nilaiAkhir  = (int) ($penilaian->nilai_akhir ?? 0);
$nilaiPersen = ($nilaiAkhir / 200) * 100;

if ($nilaiPersen >= 85)      { $grade = 'A';  $gradeBg = '#F0FDF4'; $gradeColor = '#15803d'; }
elseif ($nilaiPersen >= 75)  { $grade = 'B';  $gradeBg = '#EFF6FF'; $gradeColor = '#1d4ed8'; }
elseif ($nilaiPersen >= 60)  { $grade = 'C';  $gradeBg = '#FEFCE8'; $gradeColor = '#92741A'; }
elseif ($nilaiPersen >= 50)  { $grade = 'D';  $gradeBg = '#FFF7ED'; $gradeColor = '#c2410c'; }
else                          { $grade = 'E';  $gradeBg = '#FEF2F2'; $gradeColor = '#dc2626'; }
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
            Detail Penilaian Seminar TA-1
        </h2>
        <p style="color:#92741A;font-size:0.85rem;margin:0;">
            Lihat hasil penilaian seminar tugas akhir mahasiswa yang telah disubmit.
        </p>
    </div>
</div>

{{-- STATUS SUBMITTED --}}
<div style="background:#F0FDF4;border:1px solid #bbf7d0;border-radius:12px;padding:14px 18px;
            margin-bottom:20px;color:#15803d;font-weight:600;font-size:0.88rem;
            display:flex;align-items:center;gap:8px;">
    <i class="fa fa-circle-check"></i>
    Penilaian Seminar Berhasil Disimpan.
    <span style="font-weight:400;color:#166534;">
        Semua kriteria penilaian telah tersimpan secara permanen dalam sistem akademik dan dapat ditinjau kembali di masa mendatang.
    </span>
</div>

@if(session('success'))
<div style="background:#F0FDF4;border:1px solid #bbf7d0;border-radius:12px;padding:14px 18px;
            margin-bottom:20px;color:#15803d;font-weight:600;font-size:0.88rem;">
    <i class="fa fa-circle-check" style="margin-right:6px;"></i> {{ session('success') }}
</div>
@endif

<div class="row g-4">
    {{-- KOLOM KIRI --}}
    <div class="col-lg-8">

        {{-- INFORMASI MAHASISWA --}}
        <div style="background:#fff;border-radius:14px;padding:24px;margin-bottom:20px;
                    border:1px solid #f0f0f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
            <div style="font-size:0.72rem;font-weight:700;color:#9ca3af;text-transform:uppercase;
                        letter-spacing:1px;margin-bottom:16px;border-left:3px solid #FACC15;padding-left:10px;
                        display:flex;align-items:center;gap:8px;">
                <i class="fa fa-user-graduate" style="color:#FACC15;"></i> Informasi Mahasiswa
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <div style="font-size:0.72rem;font-weight:600;color:#9ca3af;margin-bottom:4px;">NIM</div>
                    <div style="font-size:0.88rem;font-weight:700;color:#374151;">{{ $proposal->nim_nid }}</div>
                </div>
                <div class="col-md-8">
                    <div style="font-size:0.72rem;font-weight:600;color:#9ca3af;margin-bottom:4px;">NAMA MAHASISWA</div>
                    <div style="font-size:0.88rem;font-weight:700;color:#374151;">{{ $proposal->nama_mahasiswa }}</div>
                </div>
                <div class="col-12">
                    <div style="font-size:0.72rem;font-weight:600;color:#9ca3af;margin-bottom:4px;">JUDUL TUGAS AKHIR</div>
                    <div style="font-size:0.88rem;color:#374151;line-height:1.6;">{{ $proposal->judul_ta }}</div>
                </div>
                <div class="col-md-6">
                    <div style="font-size:0.72rem;font-weight:600;color:#9ca3af;margin-bottom:4px;">DOSEN PEMBIMBING</div>
                    <div style="font-size:0.88rem;font-weight:700;color:#374151;">{{ $dosenPembimbing->nama ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <div style="font-size:0.72rem;font-weight:600;color:#9ca3af;margin-bottom:4px;">NID / PERAN</div>
                    <div style="font-size:0.88rem;color:#374151;">
                        {{ $dosenPembimbing->nim_nid ?? '-' }} •
                        <span style="color:#92741A;font-weight:700;">
                            Pembimbing {{ $penilaian->urutan_pembimbing ?? '' }} Utama
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL BAGIAN A --}}
        <div style="background:#fff;border-radius:14px;padding:24px;margin-bottom:20px;
                    border:1px solid #f0f0f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
            <div style="font-size:0.72rem;font-weight:700;color:#9ca3af;text-transform:uppercase;
                        letter-spacing:1px;margin-bottom:16px;border-left:3px solid #FACC15;padding-left:10px;
                        display:flex;align-items:center;gap:8px;">
                <i class="fa fa-list-check" style="color:#FACC15;"></i> Penilaian Proses Bimbingan
            </div>
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#F9FAFB;">
                        <th style="padding:10px 14px;font-size:0.72rem;font-weight:700;color:#9ca3af;text-align:left;text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid #F3F4F6;">NO</th>
                        <th style="padding:10px 14px;font-size:0.72rem;font-weight:700;color:#9ca3af;text-align:left;text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid #F3F4F6;">KRITERIA PENILAIAN</th>
                        <th style="padding:10px 14px;font-size:0.72rem;font-weight:700;color:#9ca3af;text-align:center;text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid #F3F4F6;">NILAI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($komponenA as $i => $k)
                    <tr style="border-bottom:1px solid #F9FAFB;">
                        <td style="padding:12px 14px;font-size:0.82rem;color:#9ca3af;font-weight:600;">{{ $i + 1 }}</td>
                        <td style="padding:12px 14px;font-size:0.88rem;color:#374151;">{{ $k['label'] }}</td>
                        <td style="padding:12px 14px;text-align:center;">
                            <span style="display:inline-block;min-width:44px;padding:4px 10px;
                                         background:#FFFBEB;border:1px solid #FDE68A;border-radius:8px;
                                         font-size:0.88rem;font-weight:800;color:#735C00;">
                                {{ (int)($penilaian->{$k['key']} ?? 0) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:#FFFBEB;">
                        <td colspan="2" style="padding:12px 14px;font-size:0.82rem;font-weight:800;color:#92741A;text-align:right;">TOTAL NILAI</td>
                        <td style="padding:12px 14px;text-align:center;font-size:1rem;font-weight:800;color:#735C00;">
                            {{ (int)($penilaian->total_a ?? 0) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- TABEL BAGIAN B --}}
        <div style="background:#fff;border-radius:14px;padding:24px;margin-bottom:20px;
                    border:1px solid #f0f0f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
            <div style="font-size:0.72rem;font-weight:700;color:#9ca3af;text-transform:uppercase;
                        letter-spacing:1px;margin-bottom:16px;border-left:3px solid #FACC15;padding-left:10px;
                        display:flex;align-items:center;gap:8px;">
                <i class="fa fa-chalkboard-user" style="color:#FACC15;"></i> Penilaian Proses Seminar TA-1
            </div>
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#F9FAFB;">
                        <th style="padding:10px 14px;font-size:0.72rem;font-weight:700;color:#9ca3af;text-align:left;text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid #F3F4F6;">NO</th>
                        <th style="padding:10px 14px;font-size:0.72rem;font-weight:700;color:#9ca3af;text-align:left;text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid #F3F4F6;">KRITERIA PENILAIAN</th>
                        <th style="padding:10px 14px;font-size:0.72rem;font-weight:700;color:#9ca3af;text-align:center;text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid #F3F4F6;">NILAI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($komponenB as $i => $k)
                    <tr style="border-bottom:1px solid #F9FAFB;">
                        <td style="padding:12px 14px;font-size:0.82rem;color:#9ca3af;font-weight:600;">{{ $i + 1 }}</td>
                        <td style="padding:12px 14px;font-size:0.88rem;color:#374151;">{{ $k['label'] }}</td>
                        <td style="padding:12px 14px;text-align:center;">
                            <span style="display:inline-block;min-width:44px;padding:4px 10px;
                                         background:#FFFBEB;border:1px solid #FDE68A;border-radius:8px;
                                         font-size:0.88rem;font-weight:800;color:#735C00;">
                                {{ (int)($penilaian->{$k['key']} ?? 0) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:#FFFBEB;">
                        <td colspan="2" style="padding:12px 14px;font-size:0.82rem;font-weight:800;color:#92741A;text-align:right;">TOTAL NILAI</td>
                        <td style="padding:12px 14px;text-align:center;font-size:1rem;font-weight:800;color:#735C00;">
                            {{ (int)($penilaian->total_b ?? 0) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- CATATAN --}}
        <div style="background:#fff;border-radius:14px;padding:24px;margin-bottom:20px;
                    border:1px solid #f0f0f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
            <div style="font-size:0.72rem;font-weight:700;color:#9ca3af;text-transform:uppercase;
                        letter-spacing:1px;margin-bottom:12px;border-left:3px solid #FACC15;padding-left:10px;
                        display:flex;align-items:center;gap:8px;">
                <i class="fa fa-comment-dots" style="color:#FACC15;"></i> Catatan Dosen Pembimbing
            </div>
            @if($penilaian->catatan)
                <p style="font-size:0.88rem;color:#374151;line-height:1.7;margin:0;
                           background:#F9FAFB;border-radius:10px;padding:14px 16px;
                           border-left:3px solid #FDE68A;font-style:italic;">
                    "{{ $penilaian->catatan }}"
                </p>
            @else
                <p style="font-size:0.88rem;color:#9ca3af;margin:0;font-style:italic;">Tidak ada catatan.</p>
            @endif
        </div>

    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-lg-4">

        {{-- AKUMULASI NILAI --}}
        <div style="background:#fff;border-radius:14px;padding:24px;margin-bottom:16px;
                    border:1px solid #f0f0f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);text-align:center;">
            <div style="font-size:0.72rem;font-weight:700;color:#9ca3af;text-transform:uppercase;
                        letter-spacing:1px;margin-bottom:16px;">AKUMULASI NILAI</div>
            <div style="font-size:3.5rem;font-weight:800;color:#735C00;line-height:1;">
                {{ $nilaiAkhir }}
            </div>
            <div style="font-size:0.88rem;color:#9ca3af;margin-bottom:20px;">/ 200.00</div>
            <hr style="border-color:#F3F4F6;margin:16px 0;">
            <div style="font-size:0.72rem;font-weight:700;color:#9ca3af;text-transform:uppercase;
                        letter-spacing:1px;margin-bottom:10px;">INDEKS PRESTASI</div>
            <div style="font-size:0.75rem;color:#9ca3af;margin-bottom:6px;">Grade</div>
            <div style="display:inline-flex;width:52px;height:52px;border-radius:12px;
                        background:{{ $gradeBg }};
                        align-items:center;justify-content:center;
                        font-size:1.6rem;font-weight:800;color:{{ $gradeColor }};
                        margin:0 auto;">
                {{ $grade }}
            </div>
        </div>

        {{-- REKOMENDASI KELAYAKAN --}}
        <div style="background:#fff;border-radius:14px;padding:24px;margin-bottom:16px;
                    border:1px solid #f0f0f0;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
            <div style="font-size:0.72rem;font-weight:700;color:#9ca3af;text-transform:uppercase;
                        letter-spacing:1px;margin-bottom:14px;">REKOMENDASI KELAYAKAN</div>
            @if($penilaian->kelayakan === 'layak')
            <div style="display:flex;align-items:center;gap:10px;background:#F0FDF4;
                        border:1px solid #bbf7d0;border-radius:10px;padding:12px 14px;">
                <i class="fa fa-circle-check" style="color:#16a34a;font-size:1rem;"></i>
                <span style="font-size:0.88rem;font-weight:700;color:#15803d;">Layak Melanjutkan TA-2</span>
            </div>
            @else
            <div style="display:flex;align-items:center;gap:10px;background:#FEF2F2;
                        border:1px solid #fca5a5;border-radius:10px;padding:12px 14px;">
                <i class="fa fa-circle-xmark" style="color:#dc2626;font-size:1rem;"></i>
                <span style="font-size:0.88rem;font-weight:700;color:#dc2626;">Belum Layak Melanjutkan TA-2</span>
            </div>
            @endif

            <div style="font-size:0.72rem;color:#9ca3af;margin-top:12px;text-align:center;">
                <i class="fa fa-clock" style="margin-right:4px;"></i>
                Disubmit pada {{ \Carbon\Carbon::parse($penilaian->updated_at)->format('d M Y • H:i') }} WIB
            </div>
        </div>

        {{-- TOMBOL AKSI --}}
        <div style="display:flex;flex-direction:column;gap:10px;">
            @if($bisaEdit)
            <a href="{{ route('penilaian.pembimbing.form', $proposal->id) }}"
                style="display:flex;align-items:center;justify-content:center;gap:8px;
                       padding:12px 20px;background:linear-gradient(135deg,#92741A,#735C00);
                       color:#fff;border-radius:12px;font-size:0.88rem;font-weight:700;
                       text-decoration:none;">
                <i class="fa fa-pen"></i> Edit Penilaian
            </a>
            @else
            <div style="display:flex;align-items:center;justify-content:center;gap:8px;
                        padding:12px 20px;background:#F3F4F6;color:#9ca3af;
                        border-radius:12px;font-size:0.88rem;font-weight:700;cursor:not-allowed;">
                <i class="fa fa-lock"></i> Edit Ditutup
            </div>
            @endif

            <a href="{{ route('penilaian.index') }}"
                style="display:flex;align-items:center;justify-content:center;gap:8px;
                       padding:12px 20px;background:#fff;color:#374151;
                       border:1.5px solid #D1D5DB;border-radius:12px;
                       font-size:0.88rem;font-weight:700;text-decoration:none;">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>

    </div>
</div>

<script>
    window.addEventListener('load', () => {
        document.getElementById('loadingOverlay').style.display = 'none';
    });
</script>

@endsection