<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class jadwalseminarcontroller extends Controller
{
    public function index(Request $request)
    {
        if (!session('user')) return redirect('/login');

        $query = DB::table('pengajuan_seminars as ps')
            ->join('users as u', DB::raw('u.nim_nid COLLATE utf8mb4_unicode_ci'), '=', DB::raw('ps.mahasiswa_id COLLATE utf8mb4_unicode_ci'))
            ->select(
                'ps.id',
                'ps.mahasiswa_id',
                'ps.judul_ta',
                'ps.status_seminar',
                'ps.status_administrasi',
                'ps.tanggal_seminar',
                'ps.waktu_mulai',
                'ps.waktu_selesai',
                'ps.ruang',
                'u.nama',
                'u.angkatan'
            )
            ->where('ps.is_draft', 0)
            ->where('ps.status_seminar', '!=', 'Belum Daftar Seminar');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('u.nama', 'like', "%$search%")
                  ->orWhere('ps.mahasiswa_id', 'like', "%$search%")
                  ->orWhere('ps.judul_ta', 'like', "%$search%");
            });
        }

        if ($request->filled('status') && $request->status !== 'Semua Status') {
            $query->where('ps.status_seminar', $request->status);
        }

        $seminars = $query->orderBy('ps.tanggal_seminar', 'asc')->get();

        foreach ($seminars as $s) {
            $s->pembimbing = [];
            $s->penguji    = [];

            $proposal = DB::table('proposal')
                ->where('nim_nid', $s->mahasiswa_id)
                ->latest()
                ->first();

            if ($proposal) {
                $dosbings = DB::table('dosen_pembimbing')
                    ->where('proposal_id', $proposal->id)
                    ->orderBy('urutan')
                    ->get();

                foreach ($dosbings as $d) {
                    $nama = DB::table('users')->where('nim_nid', $d->nim_nid_dosen)->value('nama');
                    if ($nama) $s->pembimbing[] = $nama;
                }
            }

            $dospengujis = DB::table('dosen_penguji_seminar')
                ->where('pengajuan_seminar_id', $s->id)
                ->orderBy('urutan')
                ->get();

            foreach ($dospengujis as $dp) {
                $nama = DB::table('users')->where('nim_nid', $dp->nim_nid_dosen)->value('nama');
                if ($nama) $s->penguji[] = $nama;
            }
        }

        $today = now()->toDateString();

        $totalMenunggu = DB::table('pengajuan_seminars as ps')
            ->join('users as u', DB::raw('u.nim_nid COLLATE utf8mb4_unicode_ci'), '=', DB::raw('ps.mahasiswa_id COLLATE utf8mb4_unicode_ci'))
            ->where('ps.is_draft', 0)
            ->where('ps.status_seminar', 'Menunggu Jadwal')
            ->count();

        $totalDijadwalkan = DB::table('pengajuan_seminars as ps')
            ->join('users as u', DB::raw('u.nim_nid COLLATE utf8mb4_unicode_ci'), '=', DB::raw('ps.mahasiswa_id COLLATE utf8mb4_unicode_ci'))
            ->where('ps.is_draft', 0)
            ->where('ps.status_seminar', 'Sudah Dijadwalkan')
            ->count();

        $totalHariIni = DB::table('pengajuan_seminars as ps')
            ->join('users as u', DB::raw('u.nim_nid COLLATE utf8mb4_unicode_ci'), '=', DB::raw('ps.mahasiswa_id COLLATE utf8mb4_unicode_ci'))
            ->where('ps.is_draft', 0)
            ->where('ps.tanggal_seminar', $today)
            ->count();

        $seminarHariIni = DB::table('pengajuan_seminars as ps')
            ->join('users as u', DB::raw('u.nim_nid COLLATE utf8mb4_unicode_ci'), '=', DB::raw('ps.mahasiswa_id COLLATE utf8mb4_unicode_ci'))
            ->select('ps.*', 'u.nama')
            ->where('ps.tanggal_seminar', $today)
            ->where('ps.is_draft', 0)
            ->orderBy('ps.waktu_mulai', 'asc')
            ->get();

        return view('jadwalseminar.jadwalseminarindex', compact(
            'seminars',
            'totalMenunggu',
            'totalDijadwalkan',
            'totalHariIni',
            'seminarHariIni'
        ));
    }

    // =====================================================
    // SIMPAN JADWAL (1 MAHASISWA) — dengan popup berhasil/gagal
    // =====================================================
    public function jadwalkan(Request $request, $id)
    {
        $request->validate([
            'tanggal_seminar' => 'required|date',
            'waktu_mulai'     => 'required',
            'waktu_selesai'   => 'required',
            'ruang'           => 'required',
        ]);

        try {
            DB::table('pengajuan_seminars')->where('id', $id)->update([
                'tanggal_seminar' => $request->tanggal_seminar,
                'waktu_mulai'     => $request->waktu_mulai,
                'waktu_selesai'   => $request->waktu_selesai,
                'ruang'           => $request->ruang,
                'status_seminar'  => 'Sudah Dijadwalkan',
                'updated_at'      => now(),
            ]);

            return redirect()->route('jadwalseminar.detail', $id)
                ->with('simpan_berhasil', true);

        } catch (\Exception $e) {
            return redirect()->route('jadwalseminar.detail', $id)
                ->with('simpan_gagal', true);
        }
    }

    // =====================================================
    // HAPUS JADWAL — dengan popup berhasil/gagal
    // =====================================================
    public function hapusJadwal($id)
    {
        try {
            DB::table('pengajuan_seminars')->where('id', $id)->update([
                'tanggal_seminar' => null,
                'waktu_mulai'     => null,
                'waktu_selesai'   => null,
                'ruang'           => null,
                'status_seminar'  => 'Menunggu Jadwal',
                'updated_at'      => now(),
            ]);
            return redirect()->back()->with('hapus_berhasil', true);
        } catch (\Exception $e) {
            return redirect()->back()->with('hapus_gagal', true);
        }
    }

    // =====================================================
    // FORM JADWAL MASSAL
    // =====================================================
    public function formMassal()
    {
        if (!session('user')) return redirect('/login');
        return view('jadwalseminar.massal');
    }

    // =====================================================
    // GET MAHASISWA BELUM DIJADWAL (untuk popup tambah mahasiswa)
    // =====================================================
    public function getMahasiswaBelumJadwal(Request $request)
    {
        $query = DB::table('pengajuan_seminars as ps')
            ->join('users as u', DB::raw('u.nim_nid COLLATE utf8mb4_unicode_ci'), '=', DB::raw('ps.mahasiswa_id COLLATE utf8mb4_unicode_ci'))
            ->select('ps.id', 'ps.mahasiswa_id', 'ps.judul_ta', 'u.nama')
            ->where('ps.is_draft', 0)
            ->where('ps.status_seminar', 'Menunggu Jadwal');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('u.nama', 'like', "%$search%")
                  ->orWhere('ps.mahasiswa_id', 'like', "%$search%");
            });
        }

        return response()->json($query->get());
    }

    // =====================================================
    // SIMPAN JADWAL MASSAL
    // =====================================================
    public function simpanMassal(Request $request)
    {
        $request->validate([
            'tanggal_seminar'         => 'required|date',
            'ruang'                   => 'required',
            'peserta'                 => 'required|array|min:1',
            'peserta.*.id'            => 'required',
            'peserta.*.waktu_mulai'   => 'required',
            'peserta.*.waktu_selesai' => 'required',
        ]);

        foreach ($request->peserta as $peserta) {
            DB::table('pengajuan_seminars')->where('id', $peserta['id'])->update([
                'tanggal_seminar' => $request->tanggal_seminar,
                'waktu_mulai'     => $peserta['waktu_mulai'],
                'waktu_selesai'   => $peserta['waktu_selesai'],
                'ruang'           => $request->ruang,
                'status_seminar'  => 'Sudah Dijadwalkan',
                'updated_at'      => now(),
            ]);
        }

        return redirect()->route('admin.seminar.index')
            ->with('success', 'Jadwal seminar massal berhasil ditetapkan!');
    }

    // =====================================================
    // DETAIL + FORM JADWAL 1 MAHASISWA
    // =====================================================
    public function detail($id)
    {
        if (!session('user')) return redirect('/login');

        $seminar = DB::table('pengajuan_seminars as ps')
            ->join('users as u', DB::raw('u.nim_nid COLLATE utf8mb4_unicode_ci'), '=', DB::raw('ps.mahasiswa_id COLLATE utf8mb4_unicode_ci'))
            ->select('ps.*', 'u.nama', 'u.angkatan')
            ->where('ps.id', $id)
            ->first();

        if (!$seminar) abort(404);

        $proposal = DB::table('proposal')
            ->where('nim_nid', $seminar->mahasiswa_id)
            ->latest()
            ->first();

        $pembimbing = [];
        $penguji    = [];

        if ($proposal) {
            $dosbings = DB::table('dosen_pembimbing')
                ->where('proposal_id', $proposal->id)
                ->orderBy('urutan')
                ->get();

            foreach ($dosbings as $d) {
                $nama = DB::table('users')->where('nim_nid', $d->nim_nid_dosen)->value('nama');
                $pembimbing[] = ['urutan' => $d->urutan, 'nama' => $nama];
            }
        }

        $dospengujis = DB::table('dosen_penguji_seminar')
            ->where('pengajuan_seminar_id', $seminar->id)
            ->orderBy('urutan')
            ->get();

        foreach ($dospengujis as $dp) {
            $nama = DB::table('users')->where('nim_nid', $dp->nim_nid_dosen)->value('nama');
            $penguji[] = ['urutan' => $dp->urutan, 'nama' => $nama];
        }

        return view('jadwalseminar.perorang', compact('seminar', 'pembimbing', 'penguji'));
    }
}