<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaDosenController extends Controller
{
    public function index(Request $request)
    {
        if (!session('user')) return redirect('/login');

        $user = session('user');
        $role = strtolower(trim($user->role));

        if ($role !== 'dosen') {
            return redirect('/login')->with('error', 'Akses ditolak');
        }

        $search   = $request->get('search', '');
        $angkatan = $request->get('angkatan', '');

        $query = DB::table('users')->where('role', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nim_nid', 'like', "%$search%");
            });
        }

        if ($angkatan) {
            $query->where('angkatan', $angkatan);
        }

        $mahasiswaList = $query->orderBy('nama')->get();

        $mahasiswaList = $mahasiswaList->map(function ($mhs) {
            $nim = $mhs->nim_nid;

            $adaPengajuan      = DB::table('pengajuan_judul')->where('nim_nid', $nim)->exists();
            $judulDisetujui    = DB::table('pengajuan_judul')->where('nim_nid', $nim)->where('status', 'disetujui')->exists();
            $adaProposal       = DB::table('proposal')->where('nim_nid', $nim)->exists();
            $adaPembimbing     = false;
            $proposal          = DB::table('proposal')->where('nim_nid', $nim)->latest()->first();
            if ($proposal) {
                $adaPembimbing = DB::table('dosen_pembimbing')->where('proposal_id', $proposal->id)->exists();
            }
            $proposalSelesai   = DB::table('proposal')->where('nim_nid', $nim)->where('status', 'selesai')->exists();
            $adaBimbingan      = DB::table('bimbingan')->where('nim_nid', $nim)->exists();

            $seminarRow = DB::table('pengajuan_seminars')->where('mahasiswa_id', $nim)->latest()->first();

            $sudahDaftarSeminar = $seminarRow
                && $seminarRow->status_administrasi === 'Lolos Administrasi'
                && $seminarRow->status_seminar !== null
                && $seminarRow->status_seminar !== 'Belum Daftar Seminar';

            $seminarSelesai = false;
            if ($sudahDaftarSeminar && $seminarRow->tanggal_seminar && $seminarRow->waktu_selesai) {
                $waktuSelesai = \Carbon\Carbon::parse($seminarRow->tanggal_seminar . ' ' . $seminarRow->waktu_selesai);
                $seminarSelesai = $waktuSelesai->isPast();
            }

            $daftarSeminar = $sudahDaftarSeminar && $seminarSelesai;

            $hasil = $this->hitungProgress(compact(
                'adaPengajuan', 'judulDisetujui', 'adaProposal',
                'adaPembimbing', 'proposalSelesai', 'adaBimbingan', 'daftarSeminar'
            ));

            $mhs->progress      = $hasil['progress'];
            $mhs->progressLabel = $hasil['progressLabel'];
            return $mhs;
        });

        $angkatanList = DB::table('users')
            ->where('role', 'mahasiswa')
            ->whereNotNull('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan');

        $totalMahasiswa = DB::table('users')->where('role', 'mahasiswa')->count();
        $totalAngkatan  = $angkatanList->count();

        return view('dosen.mahasiswa_list', compact(
            'mahasiswaList',
            'angkatanList',
            'totalMahasiswa',
            'totalAngkatan',
            'search',
            'angkatan'
        ));
    }

    public function show($nim)
    {
        if (!session('user')) return redirect('/login');

        $user = session('user');
        if (strtolower(trim($user->role)) !== 'dosen') {
            return redirect('/login')->with('error', 'Akses ditolak');
        }

        $mhs = DB::table('users')->where('nim_nid', $nim)->where('role', 'mahasiswa')->first();
        if (!$mhs) abort(404);

        $adaPengajuan    = DB::table('pengajuan_judul')->where('nim_nid', $nim)->exists();
        $judulDisetujui  = DB::table('pengajuan_judul')->where('nim_nid', $nim)->where('status', 'disetujui')->exists();
        $adaProposal     = DB::table('proposal')->where('nim_nid', $nim)->exists();
        $proposalSelesai = DB::table('proposal')->where('nim_nid', $nim)->where('status', 'selesai')->exists();
        $adaBimbingan    = DB::table('bimbingan')->where('nim_nid', $nim)->exists();

        $proposal      = DB::table('proposal')->where('nim_nid', $nim)->latest()->first();
        $adaPembimbing = false;
        $pembimbing1   = null;
        $pembimbing2   = null;

        if ($proposal) {
            $adaPembimbing = DB::table('dosen_pembimbing')->where('proposal_id', $proposal->id)->exists();
            $dosbing1      = DB::table('dosen_pembimbing')->where('proposal_id', $proposal->id)->where('urutan', 1)->first();
            $dosbing2      = DB::table('dosen_pembimbing')->where('proposal_id', $proposal->id)->where('urutan', 2)->first();
            $pembimbing1   = $dosbing1 ? DB::table('users')->where('nim_nid', $dosbing1->nim_nid_dosen)->first() : null;
            $pembimbing2   = $dosbing2 ? DB::table('users')->where('nim_nid', $dosbing2->nim_nid_dosen)->first() : null;
        }

        $seminar = DB::table('pengajuan_seminars')->where('mahasiswa_id', $nim)->latest()->first();

        $sudahDaftarSeminar = $seminar
            && $seminar->status_administrasi === 'Lolos Administrasi'
            && $seminar->status_seminar !== null
            && $seminar->status_seminar !== 'Belum Daftar Seminar';

        $seminarSelesai = false;
        if ($sudahDaftarSeminar && $seminar->tanggal_seminar && $seminar->waktu_selesai) {
            $waktuSelesai = \Carbon\Carbon::parse($seminar->tanggal_seminar . ' ' . $seminar->waktu_selesai);
            $seminarSelesai = $waktuSelesai->isPast();
        }

        $daftarSeminar = $sudahDaftarSeminar && $seminarSelesai;

        $hasil = $this->hitungProgress(compact(
            'adaPengajuan', 'judulDisetujui', 'adaProposal',
            'adaPembimbing', 'proposalSelesai', 'adaBimbingan', 'daftarSeminar'
        ));

        $progress      = $hasil['progress'];
        $progressLabel = $hasil['progressLabel'];
        $steps         = $hasil['steps'];

        $judul = DB::table('pengajuan_judul')
            ->where('nim_nid', $nim)
            ->where('status', 'disetujui')
            ->latest('id')
            ->first();

        $penguji1 = null;
        $penguji2 = null;

        if ($seminar) {
            $p1       = DB::table('dosen_penguji_seminar')->where('pengajuan_seminar_id', $seminar->id)->where('urutan', 1)->first();
            $p2       = DB::table('dosen_penguji_seminar')->where('pengajuan_seminar_id', $seminar->id)->where('urutan', 2)->first();
            $penguji1 = $p1 ? DB::table('users')->where('nim_nid', $p1->nim_nid_dosen)->first() : null;
            $penguji2 = $p2 ? DB::table('users')->where('nim_nid', $p2->nim_nid_dosen)->first() : null;
        }

        return view('dosen.mahasiswa_detail', compact(
            'mhs', 'progress', 'progressLabel', 'steps',
            'judul', 'pembimbing1', 'pembimbing2',
            'seminar', 'penguji1', 'penguji2'
        ));
    }

    private function hitungProgress($flags)
    {
        $order  = ['adaPengajuan','judulDisetujui','adaProposal','adaPembimbing','proposalSelesai','adaBimbingan','daftarSeminar'];
        $points = [14, 14, 14, 14, 14, 15, 15];
        $labels = [
            'adaPengajuan'    => 'Pengajuan Judul',
            'judulDisetujui'  => 'Judul Disetujui',
            'adaProposal'     => 'Upload Proposal',
            'adaPembimbing'   => 'Penetapan Pembimbing',
            'proposalSelesai' => 'Review Proposal',
            'adaBimbingan'    => 'Bimbingan TA',
            'daftarSeminar'   => 'Seminar Proposal',
        ];

        $lastIndex = -1;
        foreach ($order as $i => $key) {
            if (!empty($flags[$key])) {
                $lastIndex = $i;
            }
        }

        $progress = 0;
        $cascaded = [];
        foreach ($order as $i => $key) {
            $done = $i <= $lastIndex;
            $cascaded[$key] = $done;
            if ($done) {
                $progress += $points[$i];
            }
        }

        return [
            'progress'      => $progress,
            'progressLabel' => $lastIndex >= 0 ? $labels[$order[$lastIndex]] : 'Belum Mulai',
            'steps'         => $cascaded,
        ];
    }
}