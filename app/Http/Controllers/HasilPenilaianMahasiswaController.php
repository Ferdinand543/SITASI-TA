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

        if (!$proposal) {
            return view('mahasiswa.hasil_penilaian', [
                'user'                 => $user,
                'mahasiswa'            => $mahasiswa,
                'judulTA'              => $judulTA,
                'adaPenilaian'         => false,
                'sedangBerlangsung'    => false,  // <-- tambah
                'nilaiAkhir'           => null,
                'namaDospem1'          => '-',
                'namaDospem2'          => '-',
                'namaPenguji1'         => '-',
                'penilaianPembimbing1' => null,
                'penilaianPembimbing2' => null,
                'penilaianPenguji1'    => null,
            ]);
        }

        // ── Penilaian pembimbing 1 & 2 ──
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

        // ── Penilaian penguji 1 ──
        $nilaiPenguji1 = DB::table('penilaian_seminar')
            ->where('proposal_id', $proposal->id)
            ->where('urutan_penguji', 1)
            ->where('status', 'submitted')
            ->first();

        // ── Nama dosen ──
        $dospem1 = DB::table('usulan_pembimbing')
            ->where('proposal_id', $proposal->id)
            ->where('urutan', 1)->first();
        $dospem2 = DB::table('usulan_pembimbing')
            ->where('proposal_id', $proposal->id)
            ->where('urutan', 2)->first();

        $namaDospem1 = $dospem1
            ? DB::table('users')->where('nim_nid', $dospem1->nim_nid_dosen)->value('nama') ?? '-'
            : '-';
        $namaDospem2 = $dospem2
            ? DB::table('users')->where('nim_nid', $dospem2->nim_nid_dosen)->value('nama') ?? '-'
            : '-';

        $namaPenguji1 = $nilaiPenguji1
            ? DB::table('users')->where('nim_nid', $nilaiPenguji1->nim_nid_penguji)->value('nama') ?? '-'
            : '-';

        // ── Normalisasi nilai ──
        // Pembimbing max = 200 → dinormalisasi ke 100
        // Penguji max = 100
        $np1 = $nilaiPembimbing1
            ? round(($nilaiPembimbing1->nilai_akhir / 200) * 100, 2)
            : null;
        $np2 = $nilaiPembimbing2
            ? round(($nilaiPembimbing2->nilai_akhir / 200) * 100, 2)
            : null;
        $nq1 = $nilaiPenguji1
            ? (float) $nilaiPenguji1->nilai_akhir
            : null;

        $jumlahSudahNilai = collect([$nilaiPembimbing1, $nilaiPembimbing2, $nilaiPenguji1])
            ->filter(fn($n) => $n !== null)
            ->count();

        // adaPenilaian = TRUE hanya kalau SEMUA 3 dosen sudah submit
        $adaPenilaian = $jumlahSudahNilai === 3;

        // sedangBerlangsung = TRUE kalau minimal 1 sudah submit, tapi belum semua
        $sedangBerlangsung = $jumlahSudahNilai > 0 && !$adaPenilaian;

        // Nilai akhir hanya dihitung kalau semua sudah submit
        $nilaiAkhir = $adaPenilaian
            ? round(collect([$np1, $np2, $nq1])->avg(), 2)
            : null;

        return view('mahasiswa.hasil_penilaian', [
            'user'                 => $user,
            'mahasiswa'            => $mahasiswa,
            'judulTA'              => $judulTA,
            'proposal'             => $proposal,
            'adaPenilaian'         => $adaPenilaian,
            'sedangBerlangsung'    => $sedangBerlangsung,  // <-- tambah
            'nilaiAkhir'           => $nilaiAkhir,
            'namaDospem1'          => $namaDospem1,
            'namaDospem2'          => $namaDospem2,
            'namaPenguji1'         => $namaPenguji1,
            'penilaianPembimbing1' => $nilaiPembimbing1,
            'penilaianPembimbing2' => $nilaiPembimbing2,
            'penilaianPenguji1'    => $nilaiPenguji1,
        ]);
    }
}