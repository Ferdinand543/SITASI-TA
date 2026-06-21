<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\PengajuanSeminar;

class DaftarSeminarController extends Controller
{
    // ============================================================
    // HELPER: ambil dospem dari dosen_pembimbing
    // ============================================================
    private function getDospem($proposalId)
    {
        $dospem1 = DB::table('dosen_pembimbing')
            ->where('proposal_id', $proposalId)
            ->where('urutan', 1)
            ->first();

        $dospem2 = DB::table('dosen_pembimbing')
            ->where('proposal_id', $proposalId)
            ->where('urutan', 2)
            ->first();

        return [$dospem1, $dospem2];
    }

    // ============================================================
    // HELPER: resolve nama dosen
    // ============================================================
    private function getNamaDosen($dospem): string
    {
        if (!$dospem) return '-';
        $nimDosen = $dospem->nim_nid_dosen ?? null;
        if (!$nimDosen) return '-';
        return DB::table('users')->where('nim_nid', $nimDosen)->value('nama') ?? '-';
    }

    // ============================================================
    // HELPER: upload file fields, merge dengan data lama
    // ============================================================
    private function uploadFiles(Request $request, string $nimNid, array $oldData = [], array $tolakKeys = []): array
    {
        $fileFields = [
            'file_khs', 'file_krs', 'file_spp',
            'file_bimbingan', 'file_persetujuan',
            'file_laporan_doc', 'file_laporan_pdf',
        ];

        $result = [];
        foreach ($fileFields as $field) {
            $hapus = $request->input('hapus_' . $field) == '1';

            if ($request->hasFile($field)) {
                // Hapus file lama kalau ada
                if (!empty($oldData[$field])) {
                    Storage::disk('public')->delete($oldData[$field]);
                }
                $result[$field] = $request->file($field)->store('seminar/' . $nimNid, 'public');
            } elseif ($hapus) {
                if (!empty($oldData[$field])) {
                    Storage::disk('public')->delete($oldData[$field]);
                }
                $result[$field] = null;
            } else {
                $key = str_replace('file_', '', $field);
                if (in_array($key, $tolakKeys)) {
                    $result[$field] = null;
                } else {
                    $result[$field] = $oldData[$field] ?? null;
                }
            }
        }

        return $result;
    }

    // ============================================================
    // INDEX — halaman daftar seminar
    // ============================================================
    public function index(Request $request)
    {
        if (!session('user')) return redirect('/login');

        $user   = session('user');
        $nimNid = $user->nim_nid;

        // ── Reset notif seminar setelah halaman dibuka ──
        $totalSeminar = DB::table('pengajuan_seminars')
            ->where('mahasiswa_id', $nimNid)
            ->whereIn('status_seminar', [
                'Lolos Administrasi',
                'Menunggu Jadwal',
                'Selesai',
                'Ditolak'
            ])->count();

        session(['notif_seminar_terakhir_' . $nimNid => $totalSeminar]);
        // ───────────────────────────────────────────────

        $query = PengajuanSeminar::where('mahasiswa_id', $nimNid);

        if ($request->filled('search')) {
            $query->where('judul_ta', 'like', '%' . $request->search . '%');
        }

        $pengajuans = $query->orderBy('created_at', 'desc')->get();

        $latest             = $pengajuans->first();
        $progressAdm        = $latest->progress_dokumen ?? 0;
        $totalDokumen       = $latest->total_dokumen ?? 7;
        $progressPersen     = $totalDokumen > 0 ? round(($progressAdm / $totalDokumen) * 100) : 0;
        $statusAdministrasi = $latest->status_administrasi ?? 'Menunggu Verifikasi';
        $statusSeminar      = $latest->status_seminar ?? 'Belum Daftar Seminar';
        $sudahDaftar        = $pengajuans->contains(
            fn($p) => in_array($p->status_seminar, ['Menunggu Jadwal', 'Jadwal ditetapkan', 'Selesai'])
        );

        return view('mahasiswa.DaftarSeminar', compact(
            'pengajuans', 'progressPersen', 'progressAdm', 'totalDokumen',
            'statusAdministrasi', 'statusSeminar', 'sudahDaftar', 'user'
        ));
    }

    // ============================================================
    // CREATE — buka form administrasi baru
    // ============================================================
    public function create()
    {
        if (!session('user')) return redirect('/login');

        $user   = session('user');
        $nimNid = $user->nim_nid;

        // Kalau sudah submit (bukan draft) → redirect ke daftar
        $submitted = PengajuanSeminar::where('mahasiswa_id', $nimNid)
            ->whereIn('status_administrasi', ['Menunggu Verifikasi', 'Lolos Administrasi'])
            ->where('is_draft', 0)
            ->latest()->first();

        if ($submitted) {
            return redirect()->route('seminar.daftar')
                ->with('info', 'Pengajuan administrasi sudah ada dan sedang diproses.');
        }

        // Load draft kalau ada
        $draft     = PengajuanSeminar::where('mahasiswa_id', $nimNid)
            ->where('is_draft', 1)->latest()->first();
        $draftData = $draft ? (json_decode($draft->draft_data, true) ?? []) : [];

        $mahasiswa      = DB::table('users')->where('nim_nid', $nimNid)->first();
        $proposal       = DB::table('proposal')->where('nim_nid', $nimNid)->latest()->first();
        $pengajuanJudul = DB::table('pengajuan_judul')->where('nim_nid', $nimNid)
            ->where('status', 'disetujui')->latest()->first();

        [$dospem1, $dospem2] = $this->getDospem($proposal->id ?? null);
        $namaDospem1 = $this->getNamaDosen($dospem1);
        $namaDospem2 = $this->getNamaDosen($dospem2);

        return view('mahasiswa.FormAdministrasi', compact(
            'user', 'mahasiswa', 'proposal', 'pengajuanJudul',
            'dospem1', 'dospem2', 'namaDospem1', 'namaDospem2',
            'draftData', 'draft'
        ));
    }

    // ============================================================
    // SAVE DRAFT — simpan progress tanpa submit (AJAX)
    // ============================================================
    public function saveDraft(Request $request)
    {
        if (!session('user')) return response()->json(['error' => 'Unauthorized'], 401);

        $user   = session('user');
        $nimNid = $user->nim_nid;

        Log::info('saveDraft dipanggil', [
            'nim'   => $nimNid,
            'files' => array_keys($request->allFiles()),
        ]);

        // Ambil draft lama kalau ada
        $existing = PengajuanSeminar::where('mahasiswa_id', $nimNid)
            ->where('is_draft', 1)->latest()->first();
        $oldData  = $existing ? (json_decode($existing->draft_data, true) ?? []) : [];

        // Upload file
        $filePaths = $this->uploadFiles($request, $nimNid, $oldData);
        $savedAt   = now('Asia/Jakarta')->format('d M Y, H:i');

        $draftData = array_merge($filePaths, [
            'semester'            => $request->semester,
            'dosen_wali'          => $request->dosen_wali,
            'ipk'                 => $request->ipk,
            'total_sks'           => $request->total_sks,
            'sks_nilai_d'         => $request->sks_nilai_d,
            'mk_nilai_d'          => $request->mk_nilai_d,
            'sks_semester'        => $request->sks_semester,
            'total_sks_akumulasi' => $request->total_sks_akumulasi,
            'saved_at'            => $savedAt,
        ]);

        $dokumenAda = collect($filePaths)->filter()->count();

        $payload = [
            'is_draft'            => 1,
            'draft_data'          => json_encode($draftData),
            'progress_dokumen'    => $dokumenAda,
            'file_khs'            => $filePaths['file_khs'],
            'file_krs'            => $filePaths['file_krs'],
            'file_spp'            => $filePaths['file_spp'],
            'file_bimbingan'      => $filePaths['file_bimbingan'],
            'file_persetujuan'    => $filePaths['file_persetujuan'],
            'file_laporan_doc'    => $filePaths['file_laporan_doc'],
            'file_laporan_pdf'    => $filePaths['file_laporan_pdf'],
            'semester'            => $request->semester,
            'dosen_wali'          => $request->dosen_wali,
            'ipk'                 => $request->ipk,
            'total_sks'           => $request->total_sks,
            'sks_nilai_d'         => $request->sks_nilai_d,
            'mk_nilai_d'          => $request->mk_nilai_d,
            'sks_semester'        => $request->sks_semester,
            'total_sks_akumulasi' => $request->total_sks_akumulasi,
            'updated_at'          => now(),
        ];

        if ($existing) {
            $existing->update($payload);
            $id = $existing->id;
        } else {
            $baru = PengajuanSeminar::create(array_merge($payload, [
                'mahasiswa_id'        => $nimNid,
                'total_dokumen'       => 7,
                'status_administrasi' => 'Menunggu Verifikasi',
                'status_seminar'      => 'Belum Daftar Seminar',
            ]));
            $id = $baru->id;
        }

        Log::info('saveDraft selesai', ['id' => $id, 'filePaths' => $filePaths]);

        return response()->json([
            'success'  => true,
            'message'  => 'Draft berhasil disimpan',
            'id'       => $id,
            'saved_at' => $savedAt,
        ]);
    }

    // ============================================================
    // STORE — submit final
    // ============================================================
    public function store(Request $request)
    {
        if (!session('user')) return redirect('/login');

        $user   = session('user');
        $nimNid = $user->nim_nid;

        // ── MODE PERBAIKAN ──
        if ($request->filled('pengajuan_id') && $request->input('mode_perbaikan') == '1') {
            $pengajuan = PengajuanSeminar::where('id', $request->pengajuan_id)
                ->where('mahasiswa_id', $nimNid)
                ->where('status_administrasi', 'Tidak Administrasi')
                ->firstOrFail();

            $oldData = $pengajuan->draft_data
                ? json_decode($pengajuan->draft_data, true) ?? []
                : [];

            $statusDokumen = $pengajuan->status_dokumen
                ? json_decode($pengajuan->status_dokumen, true)
                : [];
            $tolakKeys = array_keys(array_filter($statusDokumen, fn($v) => $v === 'tolak'));

            $filePaths = $this->uploadFiles($request, $nimNid, $oldData, $tolakKeys);

            foreach ($filePaths as $field => $path) {
                $key = str_replace('file_', '', $field);
                if ($request->hasFile($field)) {
                    $statusDokumen[$key] = 'menunggu';
                }
            }

            $dokumenAda = collect($filePaths)->filter()->count();

            $pengajuan->update([
                'status_administrasi' => 'Menunggu Verifikasi',
                'status_dokumen'      => json_encode($statusDokumen),
                'catatan_admin'       => null,
                'progress_dokumen'    => $dokumenAda,
                'draft_data'          => json_encode(array_merge($oldData, $filePaths)),
                'file_khs'            => $filePaths['file_khs'],
                'file_krs'            => $filePaths['file_krs'],
                'file_spp'            => $filePaths['file_spp'],
                'file_bimbingan'      => $filePaths['file_bimbingan'],
                'file_persetujuan'    => $filePaths['file_persetujuan'],
                'file_laporan_doc'    => $filePaths['file_laporan_doc'],
                'file_laporan_pdf'    => $filePaths['file_laporan_pdf'],
                'updated_at'          => now(),
            ]);

            return redirect()->route('seminar.show', $pengajuan->id)
                ->with('success', 'Dokumen berhasil diajukan ulang! Menunggu verifikasi admin.');
        }

        // ── MODE NORMAL ──
        $submitted = PengajuanSeminar::where('mahasiswa_id', $nimNid)
            ->whereIn('status_administrasi', ['Menunggu Verifikasi', 'Lolos Administrasi'])
            ->where('is_draft', 0)
            ->latest()->first();

        if ($submitted) {
            return redirect()->route('seminar.daftar')
                ->with('info', 'Pengajuan administrasi sudah ada.');
        }

        // Ambil draft
        $draft = null;
        if ($request->filled('pengajuan_id')) {
            $draft = PengajuanSeminar::where('id', $request->pengajuan_id)
                ->where('mahasiswa_id', $nimNid)
                ->where('is_draft', 1)
                ->first();
        }
        if (!$draft) {
            $draft = PengajuanSeminar::where('mahasiswa_id', $nimNid)
                ->where('is_draft', 1)->latest()->first();
        }

        $oldData   = $draft ? (json_decode($draft->draft_data, true) ?? []) : [];
        $filePaths = $this->uploadFiles($request, $nimNid, $oldData);
        $dokumenAda = collect($filePaths)->filter()->count();

        $judulTA = DB::table('pengajuan_judul')
            ->where('nim_nid', $nimNid)
            ->where('status', 'disetujui')
            ->latest('updated_at')
            ->value('judul_disetujui');

        $dataLengkap = array_merge($filePaths, [
            'is_draft'            => 0,
            'draft_data'          => json_encode(array_merge($oldData, $filePaths, [
                'semester'            => $request->semester,
                'dosen_wali'          => $request->dosen_wali,
                'ipk'                 => $request->ipk,
                'total_sks'           => $request->total_sks,
                'sks_nilai_d'         => $request->sks_nilai_d,
                'mk_nilai_d'          => $request->mk_nilai_d,
                'sks_semester'        => $request->sks_semester,
                'total_sks_akumulasi' => $request->total_sks_akumulasi,
            ])),
            'status_administrasi' => 'Menunggu Verifikasi',
            'progress_dokumen'    => $dokumenAda,
            'total_dokumen'       => 7,
            'judul_ta'            => $judulTA,
            'semester'            => $request->semester,
            'dosen_wali'          => $request->dosen_wali,
            'ipk'                 => $request->ipk,
            'total_sks'           => $request->total_sks,
            'sks_nilai_d'         => $request->sks_nilai_d,
            'mk_nilai_d'          => $request->mk_nilai_d,
            'sks_semester'        => $request->sks_semester,
            'total_sks_akumulasi' => $request->total_sks_akumulasi,
        ]);

        if ($draft) {
            $draft->update($dataLengkap);
        } else {
            $dataLengkap['mahasiswa_id']   = $nimNid;
            $dataLengkap['status_seminar'] = 'Belum Daftar Seminar';
            PengajuanSeminar::create($dataLengkap);
        }

        return redirect()->route('seminar.daftar')
            ->with('success', 'Pengajuan administrasi berhasil disubmit! Menunggu verifikasi admin.');
    }

    // ============================================================
    // SHOW — detail pengajuan
    // ============================================================
    public function show($id)
    {
        if (!session('user')) return redirect('/login');

        $user      = session('user');
        $nimNid    = $user->nim_nid;
        $pengajuan = PengajuanSeminar::where('id', $id)
            ->where('mahasiswa_id', $nimNid)
            ->firstOrFail();

        // Draft → buka form edit
        if ($pengajuan->is_draft) {
            return redirect()->route('seminar.edit', $pengajuan->id);
        }

        $mahasiswa      = DB::table('users')->where('nim_nid', $nimNid)->first();
        $proposal       = DB::table('proposal')->where('nim_nid', $nimNid)->latest()->first();
        $pengajuanJudul = DB::table('pengajuan_judul')->where('nim_nid', $nimNid)
            ->where('status', 'disetujui')->latest()->first();

        [$dospem1, $dospem2] = $this->getDospem($proposal->id ?? null);
        $namaDospem1 = $this->getNamaDosen($dospem1);
        $namaDospem2 = $this->getNamaDosen($dospem2);

        $draftData = $pengajuan->draft_data
            ? (json_decode($pengajuan->draft_data, true) ?? [])
            : [];

        return view('mahasiswa.DetailAdministrasi', compact(
            'pengajuan', 'user', 'mahasiswa', 'proposal',
            'pengajuanJudul', 'dospem1', 'dospem2',
            'namaDospem1', 'namaDospem2', 'draftData'
        ));
    }

    // ============================================================
    // EDIT — form dari draft atau pengajuan ditolak
    // ============================================================
    public function edit($id)
    {
        if (!session('user')) return redirect('/login');

        $user      = session('user');
        $nimNid    = $user->nim_nid;
        $pengajuan = PengajuanSeminar::where('id', $id)
            ->where('mahasiswa_id', $nimNid)
            ->firstOrFail();

        if (!$pengajuan->is_draft && $pengajuan->status_administrasi !== 'Tidak Administrasi') {
            return redirect()->route('seminar.show', $id);
        }

        $draftData = $pengajuan->draft_data
            ? (json_decode($pengajuan->draft_data, true) ?? [])
            : [];

        $statusDokumen = $pengajuan->status_dokumen
            ? (json_decode($pengajuan->status_dokumen, true) ?? [])
            : [];
        $catatanDokumen = $pengajuan->catatan_dokumen
            ? (json_decode($pengajuan->catatan_dokumen, true) ?? [])
            : [];

        $mahasiswa      = DB::table('users')->where('nim_nid', $nimNid)->first();
        $proposal       = DB::table('proposal')->where('nim_nid', $nimNid)->latest()->first();
        $pengajuanJudul = DB::table('pengajuan_judul')->where('nim_nid', $nimNid)
            ->where('status', 'disetujui')->latest()->first();

        [$dospem1, $dospem2] = $this->getDospem($proposal->id ?? null);
        $namaDospem1 = $this->getNamaDosen($dospem1);
        $namaDospem2 = $this->getNamaDosen($dospem2);

        $draft = $pengajuan;

        return view('mahasiswa.FormAdministrasi', compact(
            'user', 'mahasiswa', 'proposal', 'pengajuanJudul',
            'dospem1', 'dospem2', 'namaDospem1', 'namaDospem2',
            'draftData', 'draft', 'pengajuan',
            'statusDokumen', 'catatanDokumen'
        ));
    }

    // ============================================================
    // FORM DAFTAR SEMINAR
    // ============================================================
    public function formDaftar($id)
    {
        if (!session('user')) return redirect('/login');

        $user      = session('user');
        $nimNid    = $user->nim_nid;
        $pengajuan = PengajuanSeminar::where('id', $id)
            ->where('mahasiswa_id', $nimNid)
            ->where('status_administrasi', 'Lolos Administrasi')
            ->firstOrFail();

        $mahasiswa = DB::table('users')->where('nim_nid', $nimNid)->first();
        $proposal  = DB::table('proposal')->where('nim_nid', $nimNid)->latest()->first();

        [$dospem1, $dospem2] = $this->getDospem($proposal->id ?? null);
        $namaDospem1 = $this->getNamaDosen($dospem1);
        $namaDospem2 = $this->getNamaDosen($dospem2);

        return view('mahasiswa.FormDaftarSeminar', compact(
            'pengajuan', 'user', 'mahasiswa',
            'dospem1', 'dospem2', 'namaDospem1', 'namaDospem2'
        ));
    }

    // ============================================================
    // SUBMIT DAFTAR SEMINAR
    // ============================================================
    public function submitDaftar(Request $request, $id)
    {
        if (!session('user')) return redirect('/login');

        $request->validate([
            'judul_ta'                => 'required|string|max:500',
            'rencana_tanggal_seminar' => 'required|date',
            'file_proposal'           => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:20480',
        ]);

        $user      = session('user');
        $nimNid    = $user->nim_nid;
        $pengajuan = PengajuanSeminar::where('id', $id)
            ->where('mahasiswa_id', $nimNid)
            ->firstOrFail();

        $filePath = null;
        if ($request->hasFile('file_proposal')) {
            $filePath = $request->file('file_proposal')
                ->store('proposal_seminar/' . $nimNid, 'public');
        }

        $pengajuan->update([
            'judul_ta'                => $request->judul_ta,
            'rencana_tanggal_seminar' => $request->rencana_tanggal_seminar,
            'file_proposal'           => $filePath,
            'status_seminar'          => 'Menunggu Jadwal',
        ]);

        return redirect()->route('seminar.daftar')
            ->with('success', 'Pendaftaran seminar berhasil! Menunggu penjadwalan dari admin.');
    }
}