<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminJudulController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('pengajuan_judul')
            ->join('users', 'pengajuan_judul.nim_nid', '=', 'users.nim_nid')
            ->select(
                'pengajuan_judul.*',
                'users.nama as nama_mahasiswa',
                'users.nim_nid'
            )
            ->orderBy('pengajuan_judul.created_at', 'desc');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('users.nama', 'like', '%' . $request->search . '%')
                    ->orWhere('users.nim_nid', 'like', '%' . $request->search . '%')
                    ->orWhere('pengajuan_judul.judul_1', 'like', '%' . $request->search . '%')
                    ->orWhere('pengajuan_judul.judul_2', 'like', '%' . $request->search . '%')
                    ->orWhere('pengajuan_judul.judul_3', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status) {
            $query->where('pengajuan_judul.status', $request->status);
        }

        if ($request->tanggal) {
            $query->whereDate('pengajuan_judul.tanggal_pengajuan', $request->tanggal);
        }

        $pengajuanJudul = $query->paginate(10);

        $totalPengajuan = DB::table('pengajuan_judul')->count();

        $menunggu = DB::table('pengajuan_judul')
            ->whereRaw("LOWER(status) LIKE '%menunggu%'")
            ->count();

        $disetujui = DB::table('pengajuan_judul')
            ->where('status', 'disetujui')
            ->count();

        $ditolak = DB::table('pengajuan_judul')
            ->where('status', 'ditolak')
            ->count();

        return view('admin.judul.index', compact(
            'pengajuanJudul',
            'totalPengajuan',
            'menunggu',
            'disetujui',
            'ditolak'
        ));
    }

    public function show($id)
    {
        $pengajuan = DB::table('pengajuan_judul')
            ->join('users', 'pengajuan_judul.nim_nid', '=', 'users.nim_nid')
            ->select(
                'pengajuan_judul.*',
                'users.nama as nama_mahasiswa',
                'users.email',
                'users.angkatan'
            )
            ->where('pengajuan_judul.id', $id)
            ->first();

        if (!$pengajuan) {
            abort(404);
        }

        return view('admin.judul.show', compact('pengajuan'));
    }

    public function proses(Request $request, $id)
    {
        // =========================
        // CEK SUDAH DIVERIFIKASI
        // =========================
        $existing = DB::table('pengajuan_judul')->where('id', $id)->first();

        if (!$existing) {
            return redirect()->route('admin.judul.show', $id)
                ->with('error', 'Data tidak ditemukan.');
        }

        $statusSekarang = strtolower($existing->status ?? '');
        $sudahDiverifikasi = !in_array($statusSekarang, ['menunggu', 'menunggu verifikasi']);

        if ($sudahDiverifikasi) {
            return redirect()->route('admin.judul.show', $id)
                ->with('error', 'Pengajuan ini sudah diverifikasi dan tidak dapat diubah.');
        }

        // =========================
        // PROSES VERIFIKASI
        // =========================
        $aksi = $request->aksi; // 'setujui' atau 'tolak'

        if ($aksi === 'setujui') {
            $request->validate([
                'judul_disetujui' => 'required|string',
                'catatan_1'       => 'nullable|string|max:500',
                'catatan_2'       => 'nullable|string|max:500',
                'catatan_3'       => 'nullable|string|max:500',
            ]);

            DB::table('pengajuan_judul')
                ->where('id', $id)
                ->update([
                    'status'          => 'disetujui',
                    'judul_disetujui' => $request->judul_disetujui,
                    'catatan_1'       => $request->catatan_1,
                    'catatan_2'       => $request->catatan_2,
                    'catatan_3'       => $request->catatan_3,
                    'updated_at'      => now(),
                ]);

            return redirect()->route('admin.judul.show', $id)
                ->with('success', 'Pengajuan judul berhasil disetujui.');

        } elseif ($aksi === 'tolak') {
            $request->validate([
                'catatan_1' => 'nullable|string|max:500',
                'catatan_2' => 'nullable|string|max:500',
                'catatan_3' => 'nullable|string|max:500',
            ]);

            DB::table('pengajuan_judul')
                ->where('id', $id)
                ->update([
                    'status'     => 'ditolak',
                    'catatan_1'  => $request->catatan_1,
                    'catatan_2'  => $request->catatan_2,
                    'catatan_3'  => $request->catatan_3,
                    'updated_at' => now(),
                ]);

            return redirect()->route('admin.judul.show', $id)
                ->with('success', 'Pengajuan judul telah ditolak.');
        }

        return redirect()->route('admin.judul.show', $id)
            ->with('error', 'Aksi tidak valid.');
    }
}