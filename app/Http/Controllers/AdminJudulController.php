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

        // SEARCH
        if ($request->search) {

            $query->where(function ($q) use ($request) {

                $q->where('users.nama', 'like', '%' . $request->search . '%')
                    ->orWhere('users.nim_nid', 'like', '%' . $request->search . '%')
                    ->orWhere('pengajuan_judul.judul_1', 'like', '%' . $request->search . '%')
                    ->orWhere('pengajuan_judul.judul_2', 'like', '%' . $request->search . '%')
                    ->orWhere('pengajuan_judul.judul_3', 'like', '%' . $request->search . '%');
            });
        }

        // FILTER STATUS
        if ($request->status) {

            $query->where('pengajuan_judul.status', $request->status);
        }

        // FILTER TANGGAL
        if ($request->tanggal) {

            $query->whereDate(
                'pengajuan_judul.tanggal_pengajuan',
                $request->tanggal
            );
        }

        // DATA
        $pengajuanJudul = $query->paginate(10);

        // STATISTIK
        $totalPengajuan = DB::table('pengajuan_judul')->count();

        $menunggu = DB::table('pengajuan_judul')
            ->where('status', 'menunggu verifikasi')
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

        return view('admin.judul.detail', compact(
            'pengajuan'
        ));
    }
}