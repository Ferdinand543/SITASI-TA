<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class jadwalseminarcontroller extends Controller
{
    // =====================================================
    // HELPER: CEK BENTROK DOSEN
    // Cek apakah ada dosen (dari list nim_nid) yang sudah
    // terjadwal di tanggal + waktu yang overlap,
    // kecuali seminar dengan $excludeId (untuk edit).
    // Return: array ['ada' => bool, 'konflik' => [...]]
    // =====================================================
    private function cekBentrokDosen(array $nimNidDosen, string $tanggal, string $waktuMulai, string $waktuSelesai, $excludeId = null)
    {
        if (empty($nimNidDosen)) return ['ada' => false, 'konflik' => []];

        $konflik = [];

        foreach ($nimNidDosen as $nimNid) {
            // Cari seminar lain yang melibatkan dosen ini sebagai PENGUJI
            $bentrokPenguji = DB::table('pengajuan_seminars as ps')
                ->join('dosen_penguji_seminar as dps', 'dps.pengajuan_seminar_id', '=', 'ps.id')
                ->join('users as u', DB::raw('u.nim_nid COLLATE utf8mb4_unicode_ci'), '=', DB::raw('ps.mahasiswa_id COLLATE utf8mb4_unicode_ci'))
                ->select('u.nama as nama_mahasiswa', 'ps.waktu_mulai', 'ps.waktu_selesai', 'ps.tanggal_seminar')
                ->where(DB::raw('dps.nim_nid_dosen COLLATE utf8mb4_unicode_ci'), $nimNid)
                ->where('ps.tanggal_seminar', $tanggal)
                ->where('ps.is_draft', 0)
                ->whereNotNull('ps.tanggal_seminar')
                ->when($excludeId, fn($q) => $q->where('ps.id', '!=', $excludeId))
                ->get();

            foreach ($bentrokPenguji as $b) {
                if ($this->isOverlap($waktuMulai, $waktuSelesai, $b->waktu_mulai, $b->waktu_selesai)) {
                    $namaDosen = DB::table('users')->where('nim_nid', $nimNid)->value('nama');
                    $konflik[] = [
                        'dosen'      => $namaDosen ?? $nimNid,
                        'mahasiswa'  => $b->nama_mahasiswa,
                        'jam'        => substr($b->waktu_mulai, 0, 5) . ' - ' . substr($b->waktu_selesai, 0, 5),
                    ];
                }
            }

            // Cari seminar lain yang melibatkan dosen ini sebagai PEMBIMBING
            $bentrokPembimbing = DB::table('pengajuan_seminars as ps')
                ->join('proposal as pr', DB::raw('pr.nim_nid COLLATE utf8mb4_unicode_ci'), '=', DB::raw('ps.mahasiswa_id COLLATE utf8mb4_unicode_ci'))
                ->join('dosen_pembimbing as dp', 'dp.proposal_id', '=', 'pr.id')
                ->join('users as u', DB::raw('u.nim_nid COLLATE utf8mb4_unicode_ci'), '=', DB::raw('ps.mahasiswa_id COLLATE utf8mb4_unicode_ci'))
                ->select('u.nama as nama_mahasiswa', 'ps.waktu_mulai', 'ps.waktu_selesai', 'ps.tanggal_seminar')
                ->where(DB::raw('dp.nim_nid_dosen COLLATE utf8mb4_unicode_ci'), $nimNid)
                ->where('ps.tanggal_seminar', $tanggal)
                ->where('ps.is_draft', 0)
                ->whereNotNull('ps.tanggal_seminar')
                ->when($excludeId, fn($q) => $q->where('ps.id', '!=', $excludeId))
                ->get();

            foreach ($bentrokPembimbing as $b) {
                if ($this->isOverlap($waktuMulai, $waktuSelesai, $b->waktu_mulai, $b->waktu_selesai)) {
                    $namaDosen = DB::table('users')->where('nim_nid', $nimNid)->value('nama');
                    // Hindari duplikat kalau dosen sama sudah masuk dari penguji check
                    $sudahAda = collect($konflik)->contains(fn($k) => $k['dosen'] === ($namaDosen ?? $nimNid) && $k['mahasiswa'] === $b->nama_mahasiswa);
                    if (!$sudahAda) {
                        $konflik[] = [
                            'dosen'     => $namaDosen ?? $nimNid,
                            'mahasiswa' => $b->nama_mahasiswa,
                            'jam'       => substr($b->waktu_mulai, 0, 5) . ' - ' . substr($b->waktu_selesai, 0, 5),
                        ];
                    }
                }
            }
        }

        return ['ada' => count($konflik) > 0, 'konflik' => $konflik];
    }

    // Cek apakah dua interval waktu overlap
    private function isOverlap($mulai1, $selesai1, $mulai2, $selesai2): bool
    {
        if (!$mulai1 || !$selesai1 || !$mulai2 || !$selesai2) return false;
        // Overlap jika: mulai1 < selesai2 AND selesai1 > mulai2
        return $mulai1 < $selesai2 && $selesai1 > $mulai2;
    }

    // Ambil semua nim_nid dosen yang terlibat di seminar tertentu (penguji + pembimbing)
    private function getDosenTerlibat($pengajuanSeminarId, $mahasiswaId): array
    {
        $nimNids = [];

        // Penguji
        $penguji = DB::table('dosen_penguji_seminar')
            ->where('pengajuan_seminar_id', $pengajuanSeminarId)
            ->pluck('nim_nid_dosen')
            ->toArray();
        $nimNids = array_merge($nimNids, $penguji);

        // Pembimbing (ambil dari proposal terakhir mahasiswa)
        $proposal = DB::table('proposal')
            ->where('nim_nid', $mahasiswaId)
            ->latest()
            ->first();
        if ($proposal) {
            $pembimbing = DB::table('dosen_pembimbing')
                ->where('proposal_id', $proposal->id)
                ->pluck('nim_nid_dosen')
                ->toArray();
            $nimNids = array_merge($nimNids, $pembimbing);
        }

        return array_unique($nimNids);
    }

    // =====================================================
    // INDEX
    // =====================================================
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
            ->where('ps.status_seminar', '!=', 'Belum Daftar Seminar')
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('dosen_penguji_seminar as dps')
                  ->whereColumn('dps.pengajuan_seminar_id', 'ps.id');
            });

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
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('dosen_penguji_seminar as dps')
                  ->whereColumn('dps.pengajuan_seminar_id', 'ps.id');
            })
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
    // SIMPAN JADWAL (1 MAHASISWA) — dengan validasi bentrok
    // =====================================================
    public function jadwalkan(Request $request, $id)
    {
        $request->validate([
            'tanggal_seminar' => 'required|date',
            'waktu_mulai'     => 'required',
            'waktu_selesai'   => 'required',
            'ruang'           => 'required',
        ]);

        // Ambil data seminar yang mau dijadwalkan
        $seminar = DB::table('pengajuan_seminars')->where('id', $id)->first();
        if (!$seminar) abort(404);

        // Kumpulkan semua dosen yang terlibat
        $nimNidDosen = $this->getDosenTerlibat($id, $seminar->mahasiswa_id);

        // Cek bentrok — exclude seminar ini sendiri (untuk edit)
        $cek = $this->cekBentrokDosen(
            $nimNidDosen,
            $request->tanggal_seminar,
            $request->waktu_mulai,
            $request->waktu_selesai,
            $id
        );

        if ($cek['ada']) {
            // Buat pesan error yang informatif
            $pesanList = collect($cek['konflik'])->map(function ($k) {
                return "Dosen {$k['dosen']} sudah dijadwalkan bersama mahasiswa {$k['mahasiswa']} pukul {$k['jam']}";
            })->join(' | ');

            return redirect()->route('jadwalseminar.detail', $id)
                ->with('bentrok_dosen', $cek['konflik'])
                ->with('bentrok_pesan', $pesanList);
        }

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
    // HAPUS JADWAL
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
    // GET MAHASISWA BELUM DIJADWAL
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
    // SIMPAN JADWAL MASSAL — dengan validasi bentrok
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

        $tanggal      = $request->tanggal_seminar;
        $semua_konflik = [];

        // ✅ Validasi bentrok untuk semua peserta sebelum simpan apapun
        foreach ($request->peserta as $peserta) {
            $seminar = DB::table('pengajuan_seminars')->where('id', $peserta['id'])->first();
            if (!$seminar) continue;

            $nimNidDosen = $this->getDosenTerlibat($peserta['id'], $seminar->mahasiswa_id);
            $namaMhs     = DB::table('users')->where('nim_nid', $seminar->mahasiswa_id)->value('nama');

            $cek = $this->cekBentrokDosen(
                $nimNidDosen,
                $tanggal,
                $peserta['waktu_mulai'],
                $peserta['waktu_selesai'],
                $peserta['id']
            );

            if ($cek['ada']) {
                foreach ($cek['konflik'] as $k) {
                    $semua_konflik[] = "Mahasiswa {$namaMhs}: Dosen {$k['dosen']} sudah dijadwalkan bersama {$k['mahasiswa']} pukul {$k['jam']}";
                }
            }
        }

        if (!empty($semua_konflik)) {
            $pesan = implode("\n", $semua_konflik);
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $pesan], 422);
            }
            return redirect()->back()
                ->with('error', $pesan)
                ->withInput();
        }

        // Tidak ada konflik, simpan semua
        foreach ($request->peserta as $peserta) {
            DB::table('pengajuan_seminars')->where('id', $peserta['id'])->update([
                'tanggal_seminar' => $tanggal,
                'waktu_mulai'     => $peserta['waktu_mulai'],
                'waktu_selesai'   => $peserta['waktu_selesai'],
                'ruang'           => $request->ruang,
                'status_seminar'  => 'Sudah Dijadwalkan',
                'updated_at'      => now(),
            ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
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