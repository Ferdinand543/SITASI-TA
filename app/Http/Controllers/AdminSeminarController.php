<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PengajuanSeminar;

class AdminSeminarController extends Controller
{
    public function index(Request $request)
    {
        if (!session('user')) return redirect('/login');

        $query = PengajuanSeminar::where('is_draft', 0);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('mahasiswa_id', 'like', '%' . $request->search . '%')
                    ->orWhereHas('mahasiswa', fn($q2) => $q2->where('nama', 'like', '%' . $request->search . '%'));
            });
        }

        if ($request->filled('status_administrasi') && $request->status_administrasi !== 'Semua Status administrasi') {
            $query->where('status_administrasi', $request->status_administrasi);
        }

        if ($request->filled('status_seminar') && $request->status_seminar !== 'Semua Status Seminar') {
            $query->where('status_seminar', $request->status_seminar);
        }

        if ($request->filled('angkatan')) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('angkatan', $request->angkatan));
        }

        $pengajuans = $query->orderBy('created_at', 'desc')->get()->map(function ($p) {
            $p->mahasiswa = DB::table('users')->where('nim_nid', $p->mahasiswa_id)->first();
            return $p;
        });

        $total       = PengajuanSeminar::where('is_draft', 0)->count();
        $pending     = PengajuanSeminar::where('is_draft', 0)->where('status_administrasi', 'Menunggu Verifikasi')->count();
        $lolos       = PengajuanSeminar::where('is_draft', 0)->where('status_administrasi', 'Lolos Administrasi')->count();
        $dijadwalkan = PengajuanSeminar::where('is_draft', 0)->whereNotNull('tanggal_seminar')->count();

        $angkatanList = DB::table('users')
            ->where('role', 'mahasiswa')
            ->distinct()->pluck('angkatan')
            ->filter()->sort()->values();

        return view('admin.seminar.index', compact(
            'pengajuans', 'total', 'pending', 'lolos', 'dijadwalkan', 'angkatanList'
        ));
    }

    public function show($id)
    {
        if (!session('user')) return redirect('/login');

        $pengajuan = PengajuanSeminar::findOrFail($id);
        $mahasiswa = DB::table('users')->where('nim_nid', $pengajuan->mahasiswa_id)->first();
        $draftArr  = $pengajuan->draft_data ? json_decode($pengajuan->draft_data, true) : [];

        $proposal = DB::table('proposal')
            ->where('nim_nid', $pengajuan->mahasiswa_id)
            ->latest()->first();

        $pengajuanJudul = DB::table('pengajuan_judul')
            ->where('nim_nid', $pengajuan->mahasiswa_id)
            ->where('status', 'disetujui')
            ->latest()->first();

        $dospem1 = DB::table('usulan_pembimbing')
            ->where('proposal_id', $proposal->id ?? null)
            ->where('urutan', 1)->first();
        $dospem2 = DB::table('usulan_pembimbing')
            ->where('proposal_id', $proposal->id ?? null)
            ->where('urutan', 2)->first();

        $namaDospem1 = $dospem1
            ? DB::table('users')->where('nim_nid', $dospem1->nim_nid_dosen)->value('nama') ?? '-'
            : '-';
        $namaDospem2 = $dospem2
            ? DB::table('users')->where('nim_nid', $dospem2->nim_nid_dosen)->value('nama') ?? '-'
            : '-';

        return view('admin.seminar.show', compact(
            'pengajuan', 'mahasiswa', 'draftArr',
            'pengajuanJudul', 'namaDospem1', 'namaDospem2'
        ));
    }

    public function verifikasi(Request $request, $id)
    {
        if (!session('user')) return redirect('/login');

        $request->validate([
            'status_administrasi' => 'required|in:Lolos Administrasi,Tidak Administrasi',
            'catatan'             => 'nullable|string',
        ]);

        $pengajuan = PengajuanSeminar::findOrFail($id);

        // ══ Ambil status dokumen yang SUDAH ADA di DB ══
        // Ini penting untuk mode reverifikasi: dokumen yang terkunci (setujui)
        // tidak dikirim via tombol, tapi sudah dikirim via hidden input di blade.
        // Fallback ke status lama kalau request tidak mengirim field tersebut.
        $statusLama = $pengajuan->status_dokumen
            ? (json_decode($pengajuan->status_dokumen, true) ?? [])
            : [];

        $catatanLama = $pengajuan->catatan_dokumen
            ? (json_decode($pengajuan->catatan_dokumen, true) ?? [])
            : [];

        $dokKeys = ['khs', 'krs', 'spp', 'bimbingan', 'persetujuan', 'laporan_doc', 'laporan_pdf'];

        $statusDokumen  = [];
        $catatanDokumen = [];

        foreach ($dokKeys as $key) {
            $fieldStatus  = 'status_'  . $key;
            $fieldCatatan = 'catatan_' . $key;

            // Kalau field dikirim dari form → pakai nilai baru
            // Kalau tidak dikirim (dokumen terkunci tidak punya tombol) → pakai nilai lama dari DB
            if ($request->has($fieldStatus)) {
                $statusDokumen[$key] = $request->input($fieldStatus, 'menunggu');
            } else {
                // Pertahankan status lama (biasanya 'setujui' untuk yang terkunci)
                $statusDokumen[$key] = $statusLama[$key] ?? 'menunggu';
            }

            // Catatan: kalau dokumen di-tolak → simpan catatan baru
            // Kalau tidak di-tolak → kosongkan catatan (sudah tidak relevan)
            if ($statusDokumen[$key] === 'tolak') {
                $catatanDokumen[$key] = $request->input($fieldCatatan) ?? $catatanLama[$key] ?? null;
            } else {
                $catatanDokumen[$key] = null;
            }
        }

        // Hitung ulang progress_dokumen berdasarkan jumlah yang disetujui
        $progressDokumen = collect($statusDokumen)
            ->filter(fn($v) => $v === 'setujui')
            ->count();

        $pengajuan->update([
            'status_administrasi' => $request->status_administrasi,
            'catatan_admin'       => $request->catatan,
            'status_dokumen'      => json_encode($statusDokumen),
            'catatan_dokumen'     => json_encode($catatanDokumen),
            'progress_dokumen'    => $progressDokumen,
        ]);

        return redirect()->back()->with('success', 'Status administrasi berhasil diperbarui.');
    }

    public function jadwalkan(Request $request, $id)
    {
        if (!session('user')) return redirect('/login');

        $request->validate([
            'tanggal_seminar' => 'required|date',
            'waktu_mulai'     => 'required',
            'waktu_selesai'   => 'required',
            'ruang'           => 'required|string',
        ]);

        PengajuanSeminar::findOrFail($id)->update([
            'tanggal_seminar' => $request->tanggal_seminar,
            'waktu_mulai'     => $request->waktu_mulai,
            'waktu_selesai'   => $request->waktu_selesai,
            'ruang'           => $request->ruang,
            'status_seminar'  => 'Sudah Dijadwalkan',
        ]);

        return redirect()->back()->with('success', 'Jadwal seminar berhasil ditetapkan.');
    }
}