<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBimbinganController extends Controller
{
    public function index()
    {
        if (!session('user')) return redirect('/login');

        $admin = session('user');

        /*
        |--------------------------------------------------------------------------
        | TAB 1 — Semua Proposal Bimbingan
        |--------------------------------------------------------------------------
        */

        $proposalList = DB::table('pengajuan_proposal_bimbingan as ppb')
            ->join('users as mhs', 'mhs.nim_nid', '=', 'ppb.nim_nid')
            ->leftJoin('users as dosen', 'dosen.nim_nid', '=', 'ppb.dosen_nid')
            ->select(
                'ppb.id',
                'ppb.nim_nid',
                'ppb.judul',
                'ppb.tanggal_pengajuan',
                'ppb.file_proposal',
                'ppb.status',
                'ppb.created_at',

                'mhs.nama as nama_mahasiswa',
                'dosen.nama as nama_dosen'
            )
            ->orderBy('ppb.created_at', 'desc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TAB 2 — Semua Mahasiswa Bimbingan
        |--------------------------------------------------------------------------
        */

        $mahasiswaList = DB::table('bimbingan as b')
            ->join('users as mhs', 'mhs.nim_nid', '=', 'b.nim_nid')
            ->leftJoin('users as dosen', 'dosen.nim_nid', '=', 'b.dosen_nid')
            ->select(
                'b.nim_nid',
                'mhs.nama as nama_mahasiswa',
                'mhs.angkatan',

                'dosen.nama as nama_dosen',

                DB::raw('COUNT(b.id) as total_bimbingan')
            )
            ->groupBy(
                'b.nim_nid',
                'mhs.nama',
                'mhs.angkatan',
                'dosen.nama'
            )
            ->orderBy('mhs.nama')
            ->get();

        return view('admin.bimbingan', compact(
            'proposalList',
            'mahasiswaList',
            'admin'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS PROPOSAL
    |--------------------------------------------------------------------------
    */

    public function updateStatusProposal(Request $request, $id)
    {
        if (!session('user')) return redirect('/login');

        DB::table('pengajuan_proposal_bimbingan')
            ->where('id', $id)
            ->update([
                'status' => $request->status
            ]);

        return redirect()->back()
            ->with('success', 'Status proposal berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | PREVIEW PROPOSAL PDF
    |--------------------------------------------------------------------------
    */

    public function lihatProposal($id)
    {
        if (!session('user')) return redirect('/login');

        $proposal = DB::table('pengajuan_proposal_bimbingan')
            ->where('id', $id)
            ->first();

        if (!$proposal) {
            abort(404);
        }

        return redirect(asset('uploads/proposal/' . $proposal->file_proposal));
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL MAHASISWA BIMBINGAN
    |--------------------------------------------------------------------------
    */

    public function detailMahasiswa($nim)
    {
        if (!session('user')) return redirect('/login');

        $admin = session('user');

        /*
        |--------------------------------------------------------------------------
        | DATA MAHASISWA
        |--------------------------------------------------------------------------
        */

        $mahasiswa = DB::table('users')
            ->where('nim_nid', $nim)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | DATA BIMBINGAN
        |--------------------------------------------------------------------------
        */

        $bimbingan = DB::table('bimbingan as b')
            ->leftJoin('users as dosen', 'dosen.nim_nid', '=', 'b.dosen_nid')
            ->where('b.nim_nid', $nim)
            ->select(
                'b.*',
                'dosen.nama as nama_dosen'
            )
            ->orderBy('b.pertemuan_ke', 'asc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | JUDUL TA
        |--------------------------------------------------------------------------
        */

        $pengajuan = DB::table('pengajuan_judul')
            ->where('nim_nid', $nim)
            ->where('status', 'disetujui')
            ->latest('updated_at')
            ->first();

        $judulTA = $pengajuan->judul_disetujui ?? '-';

        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalBimbingan = $bimbingan->count();

        $minBimbingan = 6;

        return view('admin.detail_bimbingan', compact(
            'mahasiswa',
            'bimbingan',
            'judulTA',
            'admin',
            'totalBimbingan',
            'minBimbingan'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS BIMBINGAN
    |--------------------------------------------------------------------------
    */

    public function updateStatusBimbingan(Request $request, $id)
    {
        if (!session('user')) return redirect('/login');

        DB::table('bimbingan')
            ->where('id', $id)
            ->update([
                'status' => 'Sudah Dilihat'
            ]);

        return redirect()->back()
            ->with('success', 'Status bimbingan berhasil diperbarui.');
    }
}