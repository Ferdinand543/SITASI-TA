<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class SuratPembimbingController extends Controller
{
    public function download(Request $request, $nim)
    {
        if (!session('user')) {
            return redirect('/login')->with('error', 'Silakan login dulu!');
        }

        $seminar = DB::table('pengajuan_seminars')
            ->where('mahasiswa_id', $nim)
            ->latest()
            ->first();

        if (!$seminar) {
            abort(404, 'Data seminar tidak ditemukan.');
        }

        if (($seminar->status_pembimbing1 ?? 'menunggu') !== 'layak') {
            abort(403, 'Surat belum dapat diunduh. Pembimbing 1 belum menandatangani.');
        }

        $mahasiswa = DB::table('users')->where('nim_nid', $nim)->first();

        $proposal = DB::table('proposal')->where('nim_nid', $nim)->latest()->first();
        $judulTA  = $seminar->judul_ta ?? $proposal->judul ?? '-';

        $dp1 = DB::table('dosen_pembimbing')
            ->where('proposal_id', $proposal->id ?? 0)
            ->where('urutan', 1)
            ->first();
        $dp2 = DB::table('dosen_pembimbing')
            ->where('proposal_id', $proposal->id ?? 0)
            ->where('urutan', 2)
            ->first();

        $dosen1 = $dp1 ? DB::table('users')->where('nim_nid', $dp1->nim_nid_dosen)->first() : null;
        $dosen2 = $dp2 ? DB::table('users')->where('nim_nid', $dp2->nim_nid_dosen)->first() : null;

        $ttd1Selesai = ($seminar->status_pembimbing1 ?? 'menunggu') === 'layak';
        $ttd2Selesai = ($seminar->status_pembimbing2 ?? 'menunggu') === 'layak';

        $qr1Svg = null;
        $qr2Svg = null;

        if ($ttd1Selesai && !empty($seminar->token_qr)) {
            $qrUrl1 = url('/verify/ttd/' . $seminar->token_qr);
            $qr1Svg = QrCode::format('svg')->size(120)->generate($qrUrl1);
        }

        if ($ttd2Selesai && !empty($seminar->token_qr_pembimbing2)) {
            $qrUrl2 = url('/verify/ttd/' . $seminar->token_qr_pembimbing2);
            $qr2Svg = QrCode::format('svg')->size(120)->generate($qrUrl2);
        }

        $tahunAkademik = date('Y') . '/' . (date('Y') + 1);

        Carbon::setLocale('id');

        $tanggal1 = $seminar->signed_at_pembimbing1
            ? Carbon::parse($seminar->signed_at_pembimbing1)->locale('id')->translatedFormat('d F Y')
            : null;

        $tanggal2 = $seminar->signed_at_pembimbing2
            ? Carbon::parse($seminar->signed_at_pembimbing2)->locale('id')->translatedFormat('d F Y')
            : null;

        $tanggalSurat = $tanggal2 ?? $tanggal1 ?? Carbon::now()->locale('id')->translatedFormat('d F Y');

        $logoKartikaBase64 = null;
        $logoUnjaniBase64  = null;

        $logoKartikaPath = public_path('images/KARTIKA.png');
        $logoUnjaniPath  = public_path('images/UNJANI.png');

        if (file_exists($logoKartikaPath)) {
            $logoKartikaBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoKartikaPath));
        }

        if (file_exists($logoUnjaniPath)) {
            $logoUnjaniBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoUnjaniPath));
        }

        $data = compact(
            'seminar', 'mahasiswa', 'judulTA',
            'dosen1', 'dosen2', 'dp1', 'dp2',
            'ttd1Selesai', 'ttd2Selesai',
            'qr1Svg', 'qr2Svg',
            'tahunAkademik', 'tanggalSurat',
            'tanggal1', 'tanggal2',
            'logoKartikaBase64', 'logoUnjaniBase64'
        );

        $pdf = Pdf::loadView('pdf.surat_pembimbing', $data)
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'defaultFont'          => 'Arial',
                'dpi'                  => 96,
                'enable_php'           => false,
                'chroot'               => public_path(),
            ]);

        if ($request->query('preview') == '1') {
            return $pdf->stream('Surat_Pernyataan_Pembimbing_' . $nim . '.pdf');
        }

        return $pdf->download('Surat_Pernyataan_Pembimbing_' . $nim . '.pdf');
    }
}