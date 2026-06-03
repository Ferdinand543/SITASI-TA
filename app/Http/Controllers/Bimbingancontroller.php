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

        // ✅ FIX: Join ke tabel proposal dulu untuk dapat proposal_id milik mahasiswa ini,
        // lalu join ke dosen_pembimbing dan users untuk dapat nama dosen
        $dosenList = DB::table('dosen_pembimbing as pb')
            ->join('proposal as p', 'p.id', '=', 'pb.proposal_id')
            ->join('users as u', 'u.nim_nid', '=', 'pb.nim_nid_dosen')
            ->where('p.nim_nid', $nim)
            ->select('pb.nim_nid_dosen', 'pb.urutan', 'u.nama')
            ->orderBy('pb.urutan')
            ->get();

        // $semuaDosen dipakai di modal tambah bimbingan (select dosen)
        $semuaDosen = $dosenList;

        return view('mahasiswa.bimbingan', compact(
            'bimbingan', 'judulTA', 'dosenList', 'semuaDosen', 'user', 'proposal'
        ));
    }

    // SIMPAN BIMBINGAN (modal tambah bimbingan)
    public function store(Request $request)
    {
        if (!session('user')) return redirect('/login');

        $user = session('user');
        $nim  = $user->nim_nid;

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

    // UPLOAD DOKUMEN (sidebar) — mendukung multi-file + multi-link, semua opsional
    public function storeProposal(Request $request)
    {
        if (!session('user')) return redirect('/login');

        $user = session('user');
        $nim  = $user->nim_nid;

        // Kumpulkan semua file path yang diunggah
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

        // Kumpulkan semua link yang diisi (filter yang kosong)
        $links = [];
        if ($request->has('links')) {
            $links = array_filter($request->input('links', []), fn($l) => !empty(trim($l)));
            $links = array_values($links);
        }

        // Gabungkan files dan links menjadi JSON untuk disimpan di kolom file_proposal
        $fileProposalData = json_encode([
            'files' => $uploadedFiles,
            'links' => $links,
        ]);

        // Hanya simpan jika ada file atau link yang diisi
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