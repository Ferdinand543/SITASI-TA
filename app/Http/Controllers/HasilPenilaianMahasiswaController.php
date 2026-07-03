<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HasilPenilaianMahasiswaController extends Controller
{
    public function index()
    {
        if (!session('user')) return redirect('/login');

        $user = session('user');
        $nim  = $user->nim_nid;

        // ── Tandai notif nilai sudah dibaca (HARUS sama persis dengan logic di AppServiceProvider) ──
        $totalNilaiPenguji = DB::table('penilaian_seminar')
            ->where('nim_nid', $nim)
            ->whereNotNull('kelayakan')
            ->count();

        $totalNilaiPembimbing = DB::table('penilaian_seminar_pembimbing')
            ->where('nim_nid', $nim)
            ->where('status', 'submitted')
            ->count();

        session(['notif_nilai_terakhir_' . $nim => $totalNilaiPenguji + $totalNilaiPembimbing]);

        $proposal = DB::table('proposal')
            ->where('nim_nid', $nim)
            ->latest()
            ->first();

        $mahasiswa = DB::table('users')
            ->where('nim_nid', $nim)
            ->first();

        $pengajuanJudul = DB::table('pengajuan_judul')
            ->where('nim_nid', $nim)
            ->where('status', 'disetujui')
            ->latest('updated_at')
            ->first();
        $judulTA = $pengajuanJudul->judul_disetujui ?? '-';

        // ── Proposal belum ada ──
        if (!$proposal) {
            return view('mahasiswa.hasil_penilaian', [
                'user'                 => $user,
                'mahasiswa'            => $mahasiswa,
                'judulTA'              => $judulTA,
                'adaPenilaian'         => false,
                'sedangBerlangsung'    => false,
                'nilaiAkhir'           => null,
                'namaDospem1'          => '-',
                'namaDospem2'          => '-',
                'namaPenguji1'         => '-',
                'namaPenguji2'         => '-',
                'penilaianPembimbing1' => null,
                'penilaianPembimbing2' => null,
                'penilaianPenguji1'    => null,
                'penilaianPenguji2'    => null,
            ]);
        }

        // ── Penilaian Pembimbing 1 & 2 ──
        $nilaiPembimbing1 = DB::table('penilaian_seminar_pembimbing')
            ->where('proposal_id', $proposal->id)
            ->where('urutan_pembimbing', 1)
            ->where('status', 'submitted')
            ->first();

        $nilaiPembimbing2 = DB::table('penilaian_seminar_pembimbing')
            ->where('proposal_id', $proposal->id)
            ->where('urutan_pembimbing', 2)
            ->where('status', 'submitted')
            ->first();

        // ── Penilaian Penguji 1 & 2 ──
        $nilaiPenguji1 = DB::table('penilaian_seminar')
            ->where('proposal_id', $proposal->id)
            ->where('urutan_penguji', 1)
            ->where('status', 'submitted')
            ->first();

        $nilaiPenguji2 = DB::table('penilaian_seminar')
            ->where('proposal_id', $proposal->id)
            ->where('urutan_penguji', 2)
            ->where('status', 'submitted')
            ->first();

        // ── Nama Dosen Pembimbing ──
        $dospem1 = DB::table('dosen_pembimbing')
            ->where('proposal_id', $proposal->id)
            ->where('urutan', 1)->first();
        $dospem2 = DB::table('dosen_pembimbing')
            ->where('proposal_id', $proposal->id)
            ->where('urutan', 2)->first();

        $namaDospem1 = $dospem1
            ? DB::table('users')->where('nim_nid', $dospem1->nim_nid_dosen)->value('nama') ?? '-'
            : '-';
        $namaDospem2 = $dospem2
            ? DB::table('users')->where('nim_nid', $dospem2->nim_nid_dosen)->value('nama') ?? '-'
            : '-';

        // ── Nama Dosen Penguji 1 & 2 ──
        $namaPenguji1 = $nilaiPenguji1
            ? DB::table('users')->where('nim_nid', $nilaiPenguji1->nim_nid_penguji)->value('nama') ?? '-'
            : '-';

        $namaPenguji2 = $nilaiPenguji2
            ? DB::table('users')->where('nim_nid', $nilaiPenguji2->nim_nid_penguji)->value('nama') ?? '-'
            : '-';

        // ── Normalisasi Nilai ──
        // Pembimbing: nilai_akhir max 200 → dinormalisasi ke 100
        // Penguji:    nilai_akhir max 100 → langsung dipakai
        $np1 = $nilaiPembimbing1
            ? round(($nilaiPembimbing1->nilai_akhir / 200) * 100, 2)
            : null;
        $np2 = $nilaiPembimbing2
            ? round(($nilaiPembimbing2->nilai_akhir / 200) * 100, 2)
            : null;
        $nq1 = $nilaiPenguji1
            ? (float) $nilaiPenguji1->nilai_akhir
            : null;
        $nq2 = $nilaiPenguji2
            ? (float) $nilaiPenguji2->nilai_akhir
            : null;

        // ── Hitung jumlah dosen yang sudah submit (total 4 dosen) ──
        $jumlahSudahNilai = collect([$nilaiPembimbing1, $nilaiPembimbing2, $nilaiPenguji1, $nilaiPenguji2])
            ->filter(fn($n) => $n !== null)
            ->count();

        // adaPenilaian = TRUE hanya kalau SEMUA 4 dosen sudah submit
        $adaPenilaian = $jumlahSudahNilai === 4;

        // sedangBerlangsung = TRUE kalau minimal 1 sudah submit, tapi belum semua
        $sedangBerlangsung = $jumlahSudahNilai > 0 && !$adaPenilaian;

        // ── Nilai akhir: rata-rata dari 4 dosen (masing-masing sudah dinormalisasi ke 100) ──
        $nilaiAkhir = $adaPenilaian
            ? round(collect([$np1, $np2, $nq1, $nq2])->avg(), 2)
            : null;

        return view('mahasiswa.hasil_penilaian', [
            'user'                 => $user,
            'mahasiswa'            => $mahasiswa,
            'judulTA'              => $judulTA,
            'proposal'             => $proposal,
            'adaPenilaian'         => $adaPenilaian,
            'sedangBerlangsung'    => $sedangBerlangsung,
            'nilaiAkhir'           => $nilaiAkhir,
            'namaDospem1'          => $namaDospem1,
            'namaDospem2'          => $namaDospem2,
            'namaPenguji1'         => $namaPenguji1,
            'namaPenguji2'         => $namaPenguji2,
            'penilaianPembimbing1' => $nilaiPembimbing1,
            'penilaianPembimbing2' => $nilaiPembimbing2,
            'penilaianPenguji1'    => $nilaiPenguji1,
            'penilaianPenguji2'    => $nilaiPenguji2,
        ]);
    }
}