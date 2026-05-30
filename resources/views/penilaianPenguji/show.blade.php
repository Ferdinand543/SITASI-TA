@extends('layouts.app')

@section('content')

<div style="padding-bottom:40px;">

    {{-- HERO --}}
    <div style="
        background:linear-gradient(135deg,#FFFBEB,#FEF3C7,#FDE68A);
        border-radius:20px;padding:36px 32px;margin-bottom:24px;
        position:relative;overflow:hidden;min-height:130px;">

        <div style="position:absolute;left:24px;top:50%;transform:translateY(-50%);
                    display:grid;grid-template-columns:repeat(3,6px);gap:5px;opacity:0.2;">
            @for($i=0;$i<12;$i++)
            <div style="width:5px;height:5px;border-radius:50%;background:#92741A;"></div>
            @endfor
        </div>

        <svg style="position:absolute;right:0;top:0;width:220px;height:100%;"
             viewBox="0 0 220 150" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMaxYMid slice">
            <path d="M220 0 Q150 30 170 75 Q190 120 140 150 L220 150Z" fill="#FDE68A" opacity="0.6"/>
            <path d="M220 0 Q170 40 185 80 Q200 115 160 150 L220 150Z" fill="#FACC15" opacity="0.35"/>
            <path d="M220 10 Q180 50 195 95 Q205 125 175 150 L220 150Z" fill="#F59E0B" opacity="0.2"/>
            <line x1="140" y1="0" x2="220" y2="60" stroke="#FACC15" stroke-width="1.5" opacity="0.4"/>
            <line x1="160" y1="0" x2="220" y2="45" stroke="#FACC15" stroke-width="1.5" opacity="0.35"/>
            <line x1="180" y1="0" x2="220" y2="30" stroke="#FACC15" stroke-width="1.5" opacity="0.3"/>
        </svg>

        <div style="position:relative;z-index:2;padding-left:32px;max-width:68%;">
            <div style="font-size:1.6rem;font-weight:800;color:#735C00;margin-bottom:8px;line-height:1.2;">
                Detail Penilaian Seminar TA-1
            </div>
            <div style="font-size:0.88rem;color:#92741A;line-height:1.6;">
                Lihat hasil penilaian seminar tugas akhir mahasiswa yang telah disubmit.
            </div>
        </div>
    </div>

    {{-- BANNER BERHASIL DISUBMIT --}}
    <div style="
        background:#fff;border-radius:16px;padding:20px 24px;margin-bottom:20px;
        border:1px solid #f0f0f0;box-shadow:0 1px 6px rgba(0,0,0,0.05);">
        <div style="display:flex;align-items:flex-start;gap:16px;">
            <div style="
                width:40px;height:40px;border-radius:50%;background:#F0FDF4;
                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fa fa-circle-check" style="color:#16a34a;font-size:1.2rem;"></i>
            </div>
            <div>
                <div style="font-size:0.7rem;font-weight:700;color:#16a34a;
                            text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">
                    Penilaian Berhasil Disubmit
                </div>
                <div style="font-size:1rem;font-weight:700;color:#111827;margin-bottom:4px;">
                    Penilaian Seminar Berhasil Disimpan
                </div>
                <div style="font-size:0.82rem;color:#9ca3af;line-height:1.5;">
                    Semua kriteria penilaian telah tersimpan secara permanen dalam sistem akademik
                    dan dapat ditinjau kembali di masa mendatang.
                </div>
            </div>
        </div>
    </div>

    {{-- LAYOUT 2 KOLOM --}}
    <div class="row g-4">

        {{-- KOLOM KIRI --}}
        <div class="col-md-7">

            {{-- INFO MAHASISWA --}}
            <div style="
                background:#fff;border-radius:16px;padding:24px;margin-bottom:20px;
                border:1px solid #f0f0f0;box-shadow:0 1px 6px rgba(0,0,0,0.05);">

                <div style="display:flex;align-items:center;gap:8px;
                            font-size:0.85rem;font-weight:700;color:#374151;
                            margin-bottom:18px;">
                    <i class="fa fa-address-card" style="color:#574500;"></i>
                    Informasi Mahasiswa
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div style="font-size:0.68rem;font-weight:700;color:#9ca3af;
                                    text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">NIM</div>
                        <div style="font-size:0.9rem;font-weight:600;color:#374151;">
                            {{ $proposal->nim_nid }}
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:0.68rem;font-weight:700;color:#9ca3af;
                                    text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Nama Mahasiswa</div>
                        <div style="font-size:0.9rem;font-weight:600;color:#374151;">
                            {{ $proposal->nama_mahasiswa }}
                        </div>
                    </div>
                    @if($proposal->judul_ta)
                    <div class="col-12">
                        <div style="font-size:0.68rem;font-weight:700;color:#9ca3af;
                                    text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Judul Tugas Akhir</div>
                        <div style="font-size:0.88rem;font-weight:700;color:#374151;line-height:1.5;">
                            {{ $proposal->judul_ta }}
                        </div>
                    </div>
                    @endif
                    <div class="col-12" style="border-top:1px solid #F3F4F6;padding-top:16px;margin-top:4px;">
                        <div class="row g-3">
                            <div class="col-6">
                                <div style="font-size:0.68rem;font-weight:700;color:#9ca3af;
                                            text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Dosen Penguji</div>
                                <div style="font-size:0.85rem;font-weight:600;color:#374151;">
                                    {{ $dosenPenguji->nama ?? '-' }}
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="font-size:0.68rem;font-weight:700;color:#9ca3af;
                                            text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">NID / Peran</div>
                                <div style="font-size:0.85rem;font-weight:600;color:#374151;">
                                    {{ $dosenPenguji->nim_nid ?? '-' }}
                                    <span style="color:#9ca3af;">•</span>
                                    <span style="color:#1A1C1C;">
                                        {{ $penilaian->urutan_penguji ? 'Penguji ' . $penilaian->urutan_penguji : 'Penguji' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABEL NILAI --}}
            <div style="
                background:#fff;border-radius:16px;overflow:hidden;margin-bottom:20px;
                border:1px solid #f0f0f0;box-shadow:0 1px 6px rgba(0,0,0,0.05);">

                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#FAFAFA;border-bottom:1px solid #F3F4F6;">
                            <th style="padding:12px 20px;font-size:0.68rem;font-weight:700;
                                       color:#574500;text-transform:uppercase;letter-spacing:1px;
                                       text-align:left;width:40px;">NO</th>
                            <th style="padding:12px 20px;font-size:0.68rem;font-weight:700;
                                       color:#574500;text-transform:uppercase;letter-spacing:1px;
                                       text-align:left;">Kriteria Penilaian</th>
                            <th style="padding:12px 20px;font-size:0.68rem;font-weight:700;
                                       color:#574500;text-transform:uppercase;letter-spacing:1px;
                                       text-align:right;">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $kriteria = [
                            ['label'=>'Teknik dan sikap presentasi',        'val'=>$penilaian->nilai_teknik_presentasi],
                            ['label'=>'Dokumentasi dan tata cara penulisan','val'=>$penilaian->nilai_dokumentasi],
                            ['label'=>'Pemahaman teori/metode',             'val'=>$penilaian->nilai_pemahaman_teori],
                            ['label'=>'Pemahaman kebutuhan/permasalahan',   'val'=>$penilaian->nilai_pemahaman_kebutuhan],
                        ];
                        @endphp
                        @foreach($kriteria as $i => $kr)
                        <tr style="border-bottom:1px solid #F9FAFB;">
                            <td style="padding:14px 20px;font-size:0.8rem;color:#7E7665;font-weight:600;">
                                {{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td style="padding:14px 20px;font-size:0.85rem;color:#1A1C1C;">
                                {{ $kr['label'] }}
                            </td>
                            <td style="padding:14px 20px;font-size:0.9rem;font-weight:700;
                                       color:#725C08;text-align:right;">
                                {{ number_format($kr['val'], 0) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#FFFBEB;border-top:2px solid #FDE68A;">
                            <td colspan="2" style="padding:14px 20px;font-size:0.8rem;
                                                   font-weight:700;color:#574500;text-transform:uppercase;
                                                   letter-spacing:1px;">
                                Total Nilai Seminar TA-1
                            </td>
                            <td style="padding:14px 20px;font-size:0.9rem;font-weight:800;
                                       color:#725C08;text-align:right;">
                                {{ number_format($penilaian->nilai_akhir, 0) }} / 100
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- CATATAN --}}
            @if($penilaian->catatan)
            <div style="
                background:#fff;border-radius:16px;padding:24px;
                border:1px solid #f0f0f0;box-shadow:0 1px 6px rgba(0,0,0,0.05);">
                <div style="display:flex;align-items:center;gap:8px;
                            font-size:0.85rem;font-weight:700;color:#374151;
                            margin-bottom:16px;">
                    <i class="fa fa-rectangle-list" style="color:#574500;"></i>
                    <span style="color:#574500;">Catatan Dosen Penguji</span>
                </div>
                <div style="
                    border-left:3px solid #FACC15;padding-left:16px;
                    font-size:0.88rem;color:#374151;line-height:1.75;font-style:italic;">
                    "{{ $penilaian->catatan }}"
                </div>
            </div>
            @endif

        </div>

        {{-- KOLOM KANAN --}}
        <div class="col-md-5">

            {{-- AKUMULASI NILAI --}}
            @php
            $na = $penilaian->nilai_akhir;
            $naColor = $na >= 80 ? '#16a34a' : ($na >= 60 ? '#735C00' : '#dc2626');
            $grade = $na >= 85 ? 'A' : ($na >= 75 ? 'B' : ($na >= 65 ? 'C' : ($na >= 55 ? 'D' : 'E')));
            @endphp

            <div style="
                background:#fff;border-radius:16px;padding:24px;margin-bottom:16px;
                border:1px solid #f0f0f0;box-shadow:0 1px 6px rgba(0,0,0,0.05);
                text-align:center;">
                <div style="font-size:0.68rem;font-weight:700;color:#9ca3af;
                            text-transform:uppercase;letter-spacing:1.5px;margin-bottom:12px;">
                    Akumulasi Nilai
                </div>
                <div style="display:flex;align-items:baseline;justify-content:center;gap:6px;">
                    <span style="font-size:3.5rem;font-weight:800;color:{{ $naColor }};line-height:1;">
                        {{ number_format($na, 0) }}
                    </span>
                    <span style="font-size:1rem;color:#9ca3af;">/ 100.00</span>
                </div>
            </div>

            {{-- GRADE --}}
            <div style="
                background:#fff;border-radius:16px;padding:24px;margin-bottom:16px;
                border:1px solid #f0f0f0;box-shadow:0 1px 6px rgba(0,0,0,0.05);
                text-align:center;">
                <div style="font-size:0.68rem;font-weight:700;color:#9ca3af;
                            text-transform:uppercase;letter-spacing:1.5px;margin-bottom:8px;">
                    Indeks Prestasi
                </div>
                <div style="font-size:1rem;font-weight:600;color:#374151;">Grade</div>
                <div style="font-size:3rem;font-weight:800;color:#735C00;line-height:1.1;">
                    {{ $grade }}
                </div>
            </div>

            {{-- KELAYAKAN --}}
            <div style="
                background:#fff;border-radius:16px;padding:20px;margin-bottom:16px;
                border:1px solid #f0f0f0;box-shadow:0 1px 6px rgba(0,0,0,0.05);">
                <div style="font-size:0.68rem;font-weight:700;color:#9ca3af;
                            text-transform:uppercase;letter-spacing:1.5px;margin-bottom:12px;">
                    Rekomendasi Kelayakan
                </div>
                @if($penilaian->kelayakan === 'layak')
                <div style="
                    background:#F0FDF4;border-radius:12px;padding:14px 16px;
                    display:flex;align-items:center;gap:10px;">
                    <div style="
                        width:28px;height:28px;border-radius:50%;background:#16a34a;
                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa fa-check" style="color:#fff;font-size:0.75rem;"></i>
                    </div>
                    <div>
                        <div style="font-size:0.85rem;font-weight:700;color:#16a34a;">
                            Layak Melanjutkan TA-2
                        </div>
                        <div style="font-size:0.72rem;color:#16a34a;opacity:0.7;">
                            Status Dikunci oleh Sistem
                        </div>
                    </div>
                </div>
                @else
                <div style="
                    background:#FEF2F2;border-radius:12px;padding:14px 16px;
                    display:flex;align-items:center;gap:10px;">
                    <div style="
                        width:28px;height:28px;border-radius:50%;background:#dc2626;
                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa fa-x" style="color:#fff;font-size:0.75rem;"></i>
                    </div>
                    <div>
                        <div style="font-size:0.85rem;font-weight:700;color:#dc2626;">
                            Belum Layak Melanjutkan TA-2
                        </div>
                        <div style="font-size:0.72rem;color:#dc2626;opacity:0.7;">
                            Status Dikunci oleh Sistem
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- TIMESTAMP --}}
            <div style="
                background:#fff;border-radius:16px;padding:16px 20px;margin-bottom:16px;
                border:1px solid #f0f0f0;box-shadow:0 1px 6px rgba(0,0,0,0.05);">
                <div style="font-size:0.78rem;color:#9ca3af;display:flex;align-items:center;gap:6px;">
                    <i class="fa fa-clock"></i>
                    Disubmit pada {{ \Carbon\Carbon::parse($penilaian->updated_at)->translatedFormat('d M Y') }}
                    • {{ \Carbon\Carbon::parse($penilaian->updated_at)->format('H:i') }} WIB
                </div>
            </div>

            {{-- TOMBOL EDIT / KEMBALI --}}
            @if($bisaEdit)
            <a href="{{ route('penilaian.form', $proposal->id) }}" style="
                display:flex;align-items:center;justify-content:center;gap:8px;
                padding:12px;border-radius:12px;
                background:#FACC15;color:#735C00;
                font-size:0.9rem;font-weight:700;
                text-decoration:none;margin-bottom:10px;
                box-shadow:0 4px 12px rgba(250,204,21,0.3);">
                <i class="fa fa-pen"></i> Edit Penilaian
            </a>
            @else
            <div style="
                display:flex;align-items:center;justify-content:center;gap:8px;
                padding:12px;border-radius:12px;
                background:#F3F4F6;color:#9ca3af;
                font-size:0.9rem;font-weight:700;
                margin-bottom:10px;cursor:not-allowed;">
                <i class="fa fa-lock"></i> Edit Ditutup
            </div>
            @endif

            <a href="{{ route('penilaian.index') }}" style="
                display:flex;align-items:center;justify-content:center;gap:8px;
                padding:12px;border-radius:12px;
                border:1px solid #E5E7EB;background:#fff;
                color:#374151;font-size:0.9rem;font-weight:700;
                text-decoration:none;">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>

        </div>
    </div>

</div>

@endsection