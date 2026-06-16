<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Models\BeritaAcaraTemplate;
use Illuminate\Support\Facades\DB;

class BeritaAcaraController extends Controller
{
    public function generate()
    {
        $user = session('user');
        if (!$user) return redirect('/login');

        $nim = $user->nim_nid;

        // Ambil template terbaru
        $template = BeritaAcaraTemplate::latest()->first();
        if (!$template) abort(404, 'Template belum tersedia.');

        $templatePath = storage_path('app/public/' . $template->file_path);
        if (!file_exists($templatePath)) abort(404, 'File template tidak ditemukan.');

        // Ambil data pengajuan seminar
        $pengajuan = DB::table('pengajuan_seminars')
            ->where('mahasiswa_id', $nim)
            ->whereIn('status_seminar', ['Menunggu Jadwal', 'Sudah Dijadwalkan', 'Selesai'])
            ->latest()
            ->first();

        if (!$pengajuan) abort(404, 'Data seminar tidak ditemukan.');

        // Ambil data mahasiswa
        $mahasiswa = DB::table('users')->where('nim_nid', $nim)->first();

        // Ambil proposal untuk dapat pembimbing & reviewer
        $proposal = DB::table('proposal')->where('nim_nid', $nim)->latest()->first();

        // Pembimbing
        $pb1 = $pb2 = null;
        if ($proposal) {
            $d1 = DB::table('dosen_pembimbing')->where('proposal_id', $proposal->id)->where('urutan', 1)->first();
            $d2 = DB::table('dosen_pembimbing')->where('proposal_id', $proposal->id)->where('urutan', 2)->first();
            if ($d1) $pb1 = DB::table('users')->where('nim_nid', $d1->nim_nid_dosen)->first();
            if ($d2) $pb2 = DB::table('users')->where('nim_nid', $d2->nim_nid_dosen)->first();
        }

        // Reviewer (ambil dari tabel reviewer proposal — sesuaikan nama tabel kamu)
        // Dosen Penguji Seminar
$rv1 = $rv2 = null;

$penguji1 = DB::table('dosen_penguji_seminar')
    ->where('pengajuan_seminar_id', $pengajuan->id)
    ->where('urutan', 1)
    ->first();

$penguji2 = DB::table('dosen_penguji_seminar')
    ->where('pengajuan_seminar_id', $pengajuan->id)
    ->where('urutan', 2)
    ->first();

if ($penguji1) {
    $rv1 = DB::table('users')
        ->where('nim_nid', $penguji1->nim_nid_dosen)
        ->first();
}

if ($penguji2) {
    $rv2 = DB::table('users')
        ->where('nim_nid', $penguji2->nim_nid_dosen)
        ->first();
}

        // Nomor BA — generate otomatis (urutan seminar selesai + 1)
        $nomorBA = DB::table('pengajuan_seminars')
            ->whereIn('status_seminar', ['Sudah Dijadwalkan', 'Selesai'])
            ->where('id', '<=', $pengajuan->id)
            ->count();

        // Jam seminar
        $jamMulai   = $pengajuan->waktu_mulai   ? \Carbon\Carbon::parse($pengajuan->waktu_mulai)->format('H.i')   : '-';
        $jamSelesai = $pengajuan->waktu_selesai ? \Carbon\Carbon::parse($pengajuan->waktu_selesai)->format('H.i') : '-';

        // Helper ambil nama & NID dosen
        $namaDosen = fn($d) => $d->nama ?? '-';
        $nidDosen  = fn($d) => $d->nim_nid ?? '-';
        // Kode singkatan (3 huruf kapital dari nama depan + belakang)
        $kodeDosen = function($d) {
            if (!$d) return '-';
            $words = explode(' ', $d->nama);
            $kode = '';
            foreach ($words as $w) $kode .= strtoupper(substr($w, 0, 1));
            return substr($kode, 0, 3);
        };

        // Salin template ke file temp (JANGAN edit file asli)
        $tmpDir = storage_path('app/temp');
        if (!file_exists($tmpDir)) mkdir($tmpDir, 0755, true);
        $tmpPath = $tmpDir . '/ba_' . $nim . '_' . time() . '.docx';
        copy($templatePath, $tmpPath);

        // Replace semua placeholder
        $processor = new TemplateProcessor($tmpPath);

        $processor->setValue('NIM',              $nim);
        $processor->setValue('NAMA_MAHASISWA',   $mahasiswa->nama ?? '-');
        $processor->setValue('JUDUL_TA',         $pengajuan->judul_ta ?? '-');
        $processor->setValue('TANGGAL_SEMINAR',  $pengajuan->tanggal_seminar
            ? \Carbon\Carbon::parse($pengajuan->tanggal_seminar)->translatedFormat('d F Y')
            : '-');
        $processor->setValue('JAM_SEMINAR',      $jamMulai . ' - ' . $jamSelesai . ' WIB');
        $processor->setValue('RUANG',            $pengajuan->ruang ?? '-');
        $processor->setValue('NOMOR_BA',         $nomorBA);

        $processor->setValue('KODE_PEMBIMBING_1', $kodeDosen($pb1));
        $processor->setValue('NAMA_PEMBIMBING_1', $namaDosen($pb1));
        $processor->setValue('NID_PEMBIMBING_1',  $nidDosen($pb1));
        $processor->setValue('KODE_PEMBIMBING_2', $kodeDosen($pb2));
        $processor->setValue('NAMA_PEMBIMBING_2', $namaDosen($pb2));
        $processor->setValue('NID_PEMBIMBING_2',  $nidDosen($pb2));

        $processor->setValue('KODE_REVIEWER_1',  $kodeDosen($rv1));
        $processor->setValue('NAMA_REVIEWER_1',  $namaDosen($rv1));
        $processor->setValue('NID_REVIEWER_1',   $nidDosen($rv1));
        $processor->setValue('KODE_REVIEWER_2',  $kodeDosen($rv2));
        $processor->setValue('NAMA_REVIEWER_2',  $namaDosen($rv2));
        $processor->setValue('NID_REVIEWER_2',   $nidDosen($rv2));

        // Simpan output
        $outputPath = $tmpDir . '/output_ba_' . $nim . '_' . time() . '.docx';
        $processor->saveAs($outputPath);

        // Hapus file tmp perantara
        @unlink($tmpPath);

        // Stream ke browser lalu hapus output
        return response()->download(
            $outputPath,
            'Berita_Acara_Seminar_TA1_' . $nim . '.docx'
        )->deleteFileAfterSend(true);
    }
}