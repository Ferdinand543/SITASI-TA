<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Surat Pernyataan Dosen Pembimbing</title>
    <style>
        @page {
            margin: 15mm 18mm 15mm 18mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.4;
        }

        /* ── HEADER ── */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .header-table td {
            vertical-align: middle;
            padding: 0;
        }
        .logo-cell {
            width: 24mm;
            text-align: center;
        }
        .logo-cell img {
            width: 27mm;
            height: 27mm;
            object-fit: contain;
        }
        .inst-cell {
            text-align: center;
            padding: 0 4mm;
        }
        .inst-cell .l1 {
            font-size: 13pt;
            font-weight: bold;
            line-height: 1.3;
            letter-spacing: 0.2px;
        }
        .inst-cell .l2 {
            font-size: 15pt;
            font-weight: bold;
            margin-top: 1px;
            letter-spacing: 0.3px;
        }
        .inst-cell .l3 {
            font-size: 8pt;
            margin-top: 3px;
        }

        .header-line {
            border: none;
            border-top: 3px solid #000;
            margin: 5px 0 9mm 0;
        }

        /* ── TITLE ── */
        .title-box {
            text-align: center;
            margin-bottom: 9mm;
        }
        .title-box p {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            line-height: 1.5;
        }

        /* ── BODY TEXT ── */
        .isi {
            text-align: justify;
            font-size: 11pt;
            margin-bottom: 6mm;
        }

        /* ── DATA MAHASISWA ── */
        table.data-mhs {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6mm;
            font-size: 11pt;
        }
        table.data-mhs td {
            padding: 1.5px 0;
            vertical-align: top;
        }
        table.data-mhs td.label {
            width: 45mm;
            white-space: nowrap;
        }
        table.data-mhs td.sep {
            width: 6mm;
        }

        /* ── BOX STATEMENT ── */
        .box-statement {
            border: 1.5px solid #000;
            padding: 8px 10px;
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            text-transform: uppercase;
            margin: 6mm 0;
            line-height: 1.6;
        }

        /* ── TANDA TANGAN ── */
        .ttd-section {
            margin-top: 12mm;
        }
        .ttd-tanggal {
            text-align: right;
            margin-bottom: 4mm;
            font-size: 11pt;
        }

        table.ttd-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        table.ttd-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 2px 8mm;
        }

        .ttd-role {
            font-weight: normal;
            line-height: 1.5;
            margin-bottom: 0;
            min-height: 10mm;
        }

        .ttd-qr-wrap {
            height: 28mm;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 4mm 0 2mm 0;
        }
        .ttd-qr-wrap svg {
            width: 24mm;
            height: 24mm;
        }

        .ttd-nama {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 3px;
            display: inline-block;
            min-width: 55mm;
            text-align: center;
        }
        .ttd-nid {
            font-size: 10.5pt;
            margin-top: 2px;
        }
        .ttd-pending {
            font-size: 9pt;
            color: #888;
            font-style: italic;
            margin-top: 4px;
        }

        /* ── CATATAN ── */
        .catatan {
            margin-top: 10mm;
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 8.5pt;
            font-weight: bold;
            text-align: justify;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    {{-- ══ HEADER ══ --}}
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                @if(!empty($logoKartikaBase64))
                    <img src="{{ $logoKartikaBase64 }}" alt="Logo Kartika">
                @endif
            </td>
            <td class="inst-cell">
                <div class="l1">YAYASAN KARTIKA EKA PAKSI (YKEP)</div>
                <div class="l1">UNIVERSITAS JENDERAL ACHMAD YANI (UNJANI)</div>
                <div class="l1">FAKULTAS SAINS DAN INFORMATIKA (FSI)</div>
                <div class="l2">PROGRAM STUDI S1 SISTEM INFORMASI</div>
                <div class="l3">Kampus Cimahi : Jl. Terusan Jenderal Sudirman PO. BOX 148 Telp. (022) 6631556 Fax. (022) 6631556</div>
            </td>
            <td class="logo-cell">
                @if(!empty($logoUnjaniBase64))
                    <img src="{{ $logoUnjaniBase64 }}" alt="Logo Unjani">
                @endif
            </td>
        </tr>
    </table>

    <hr class="header-line">

    {{-- ══ TITLE ══ --}}
    <div class="title-box">
        <p>SURAT PERNYATAAN DOSEN PEMBIMBING ½</p>
        <p>MAHASISWA TUGAS AKHIR ( TA ) – 1</p>
    </div>

    {{-- ══ PEMBUKA ══ --}}
    <div class="isi">
        KAMI YANG BERTANDATANGAN DI BAWAH INI SEBAGAI DOSEN PEMBIMBING ½ MAHASISWA
        PESERTA PENELITIAN TUGAS AKHIR – 1, BERIKUT DI BAWAH INI :
    </div>

    {{-- ══ DATA MAHASISWA ══ --}}
    <table class="data-mhs">
        <tr>
            <td class="label">NAMA MAHASISWA</td>
            <td class="sep">:</td>
            <td>
                {{ $mahasiswa->nama ?? '………………………………………………………………' }}
                &nbsp;&nbsp;&nbsp; NIM :
                {{ $mahasiswa->nim_nid ?? '……………………' }}
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="sep"></td>
            <td>
                NILAI IPK : {{ $mahasiswa->ipk_terakhir ?? '………' }} &nbsp;/&nbsp;
                SKS : {{ $seminar->total_sks ?? '………' }} &nbsp;&nbsp;
                NILAI D/E : {{ $seminar->sks_nilai_d ?? '…' }} / {{ $seminar->sks_nilai_e ?? '…' }}
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td class="sep"></td>
            <td>NO. KONTAK : {{ $mahasiswa->no_kontak ?? '…………………………………' }}</td>
        </tr>
        <tr>
            <td class="label" style="padding-top:3px;">JUDUL PENELITIAN</td>
            <td class="sep" style="padding-top:3px;">:</td>
            <td style="padding-top:3px;">
                {{ $judulTA ?? '………………………………………………………………………………………………………………………………………' }}
            </td>
        </tr>
    </table>

    {{-- ══ BADAN SURAT ══ --}}
    <div class="isi">
        DENGAN INI MENERANGKAN, BAHWA BERDASARKAN PENILAIAN TERHADAP HASIL PROSES
        BIMBINGAN YANG TELAH DILAKUKAN DAN ISI LAPORAN PENELITIAN TUGAS AKHIR, MAKA
        MATERI/LAPORAN TA1 MAHASISWA PESERTA TERSEBUT DI ATAS, KAMI NYATAKAN
    </div>

    {{-- ══ BOX LAYAK SEMINAR ══ --}}
    <div class="box-statement">
        TELAH MEMENUHI PERSYARATAN DAN LAYAK UNTUK DI SEMINARKAN<br>
        PADA SEMINAR TUGAS AKHIR – 1 TAHUN AKADEMIK : {{ $tahunAkademik ?? '…………………………………' }}
    </div>

    {{-- ══ PENUTUP ══ --}}
    <div class="isi">
        DEMIKIAN SURAT PERSYARATAN INI KAMI BUAT UNTUK DIPERGUNAKAN SEBAGAIMANA
        MESTINYA. ATAS SEGALA PERHATIAN DAN KERJASAMANYA DIUCAPKAN TERIMA KASIH.
    </div>

    {{-- ══ TANDA TANGAN ══ --}}
    <div class="ttd-section">
        <div class="ttd-tanggal">Cimahi, {{ $tanggalSurat ?? '………………………………' }}</div>

        <table class="ttd-table">

            {{-- Baris 1: Label role --}}
            <tr>
                <td>
                    <div class="ttd-role">Mengetahui,<br>Dosen Pembimbing 2</div>
                </td>
                <td>
                    <div class="ttd-role">Dosen Pembimbing 1</div>
                </td>
            </tr>

            {{-- Baris 2: Area QR --}}
            <tr>
                <td>
                    <div class="ttd-qr-wrap">
                        @if(!empty($ttd2Selesai) && !empty($qr2Svg))
                            {!! $qr2Svg !!}
                        @endif
                    </div>
                </td>
                <td>
                    <div class="ttd-qr-wrap">
                        @if(!empty($ttd1Selesai) && !empty($qr1Svg))
                            {!! $qr1Svg !!}
                        @endif
                    </div>
                </td>
            </tr>

            {{-- Baris 3: Nama, NID, status pending --}}
            <tr>
                <td>
                    <span class="ttd-nama">{{ $dosen2->nama ?? '' }}</span>
                    <div class="ttd-nid">NID. {{ $dosen2->nim_nid ?? '4121-…………' }}</div>
                    @if(empty($ttd2Selesai))
                        <div class="ttd-pending">Menunggu tanda tangan</div>
                    @endif
                </td>
                <td>
                    <span class="ttd-nama">{{ $dosen1->nama ?? '' }}</span>
                    <div class="ttd-nid">NID. {{ $dosen1->nim_nid ?? '4121-…………' }}</div>
                    @if(empty($ttd1Selesai))
                        <div class="ttd-pending">Menunggu tanda tangan</div>
                    @endif
                </td>
            </tr>

        </table>
    </div>

    {{-- ══ CATATAN ── --}}
    <div class="catatan">
        CATT.: KEPADA DOSEN PEMBIMBING HARAP UNTUK TIDAK MENANDATANGANI SURAT INI APABILA MAHASISWA
        TIDAK/BELUM MENYRAHKAN DRAFT LAPORAN PENELITIAN YANG AKAN DISEMINARKAN. TERIMAKASIH
    </div>

</body>
</html>