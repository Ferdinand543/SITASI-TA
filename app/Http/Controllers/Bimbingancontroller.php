<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BimbinganController extends Controller
{
    public function index()
    {
        if (!session('user')) return redirect('/login');

        $user = session('user');
        $nim  = $user->nim_nid;

        $bimbingan = DB::table('bimbingan')
            ->where('nim_nid', $nim)
            ->orderBy('pertemuan_ke', 'desc')
            ->get();

        $pengajuan = DB::table('pengajuan_judul')
            ->where('nim_nid', $nim)
            ->where('status', 'disetujui')
            ->latest('updated_at')
            ->first();

        $judulTA = $pengajuan->judul_disetujui ?? '-';

        $proposal = DB::table('proposal')
            ->where('nim_nid', $nim)
            ->latest('created_at')
            ->first();

        // ✅ Riwayat upload dokumen pra bimbingan (buat dropdown di sidebar)
        $riwayatProposal = DB::table('pengajuan_proposal_bimbingan')
            ->where('nim_nid', $nim)
            ->orderBy('created_at', 'desc')
            ->get();

        $dosenList = DB::table('dosen_pembimbing as pb')
            ->join('proposal as p', 'p.id', '=', 'pb.proposal_id')
            ->join('users as u', 'u.nim_nid', '=', 'pb.nim_nid_dosen')
            ->where('p.nim_nid', $nim)
            ->select('pb.nim_nid_dosen', 'pb.urutan', 'u.nama')
            ->orderBy('pb.urutan')
            ->get();

        $semuaDosen = $dosenList;

        // ✅ Tambahan: ambil data seminar aktif mahasiswa ini
        // untuk cek status TTD pembimbing (dipakai banner kelayakan)
        $seminar = DB::table('pengajuan_seminars')
            ->where('mahasiswa_id', $nim)
            ->latest()
            ->first();

        // 🔵 FIX #1 — tandai notif "Riwayat Bimbingan" sebagai sudah dilihat
        // (sama persis logic-nya dengan yang dihitung di AppServiceProvider)
        $totalBimbinganDirespon = DB::table('bimbingan')
            ->where('nim_nid', $nim)
            ->whereIn('status_validasi', ['Valid', 'Tidak Valid'])
            ->count();

        session(['notif_bimbingan_terakhir_' . $nim => $totalBimbinganDirespon]);

        // 🔵 FIX #2 — tandai notif "Kelayakan Seminar" (banner ijo) sebagai sudah dilihat
        // (sama persis logic-nya dengan yang dihitung di AppServiceProvider)
        $totalLayakDirespon = DB::table('pengajuan_seminars')
            ->where('mahasiswa_id', $nim)
            ->where('status_pembimbing1', 'layak')
            ->count();

        session(['notif_layak_terakhir_' . $nim => $totalLayakDirespon]);

        return view('mahasiswa.bimbingan', compact(
            'bimbingan', 'judulTA', 'dosenList', 'semuaDosen', 'user', 'proposal',
            'seminar', 'riwayatProposal'
        ));
    }

    // SIMPAN BIMBINGAN (modal tambah bimbingan)
    public function store(Request $request)
    {
        if (!session('user')) return redirect('/login');

        $user = session('user');
        $nim  = $user->nim_nid;

        // ✅ VALIDASI — dokumentasi foto wajib diisi
        $request->validate([
            'tanggal_bimbingan' => 'required|date',
            'pertemuan_ke'      => 'required|integer|min:1',
            'dosen_nid'         => 'required',
            'topik_bimbingan'   => 'required|string',
            'dokumentasi'       => 'required|image|mimes:jpg,jpeg,png|max:5120', // wajib, maks 5MB
        ], [
            'dokumentasi.required' => 'Foto dokumentasi bimbingan wajib diunggah.',
            'dokumentasi.image'    => 'File yang diunggah harus berupa gambar.',
            'dokumentasi.mimes'    => 'Format foto harus JPG atau PNG.',
            'dokumentasi.max'      => 'Ukuran foto maksimal 5MB.',
            'tanggal_bimbingan.required' => 'Tanggal bimbingan wajib diisi.',
            'pertemuan_ke.required'      => 'Bimbingan ke- wajib diisi.',
            'dosen_nid.required'         => 'Dosen pembimbing wajib dipilih.',
            'topik_bimbingan.required'   => 'Topik bimbingan wajib diisi.',
        ]);

        $filePath = null;
        if ($request->hasFile('dokumentasi')) {
            $file     = $request->file('dokumentasi');
            $fileName = 'bimbingan_' . $nim . '_' . time() . '.' . $file->getClientOriginalExtension();

            if (!file_exists(public_path('uploads/bimbingan'))) {
                mkdir(public_path('uploads/bimbingan'), 0755, true);
            }

            $file->move(public_path('uploads/bimbingan'), $fileName);
            $filePath = $fileName;
        }

        DB::table('bimbingan')->insert([
            'nim_nid'           => $nim,
            'dosen_nid'         => $request->dosen_nid ?? '',
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'pertemuan_ke'      => $request->pertemuan_ke,
            'topik_bimbingan'   => $request->topik_bimbingan,
            'dokumentasi'       => $filePath,
            'status'            => 'Baru Dikirim',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return redirect()->back()->with('success', 'Riwayat bimbingan berhasil ditambahkan!');
    }

    // UPLOAD DOKUMEN (sidebar)
    public function storeProposal(Request $request)
    {
        if (!session('user')) return redirect('/login');

        $user = session('user');
        $nim  = $user->nim_nid;

        $uploadedFiles = [];
        if ($request->hasFile('file_dokumen')) {
            foreach ($request->file('file_dokumen') as $file) {
                $fileName = 'dokumen_' . $nim . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                if (!file_exists(public_path('uploads/proposal'))) {
                    mkdir(public_path('uploads/proposal'), 0755, true);
                }

                $file->move(public_path('uploads/proposal'), $fileName);
                $uploadedFiles[] = $fileName;
            }
        }

        $links = [];
        if ($request->has('links')) {
            $links = array_filter($request->input('links', []), fn($l) => !empty(trim($l)));
            $links = array_values($links);
        }

        $fileProposalData = json_encode([
            'files' => $uploadedFiles,
            'links' => $links,
        ]);

        if (empty($uploadedFiles) && empty($links)) {
            return redirect()->back()->with('proposal_error', 'Silakan unggah minimal satu file atau masukkan satu link.');
        }

        DB::table('pengajuan_proposal_bimbingan')->insert([
            'nim_nid'           => $nim,
            'dosen_nid'         => $request->dosen_nid,
            'judul'             => $request->judul ?? '-',
            'tanggal_pengajuan' => $request->tanggal_pengajuan ?? now()->toDateString(),
            'file_proposal'     => $fileProposalData,
            'status'            => 'pending',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return redirect()->back()->with('proposal_success', 'Dokumen berhasil dikirim!');
    }
}