<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProposalController extends Controller
{
    // =====================================================
    // INDEX - List semua proposal (koordinator)
    // =====================================================
    public function index(Request $request)
    {
        if (!session('user')) {
            return redirect('/login')->with('error', 'Silakan login dulu!');
        }

        $role = strtolower(trim(session('user')->role));

        if ($role !== 'koordinator') {
            if ($role === 'mahasiswa') {
                return redirect('/mahasiswa')->with('error', 'Akses ditolak!');
            }
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $query = DB::table('proposal')
            ->join('users as mhs', 'proposal.nim_nid', '=', 'mhs.nim_nid')

            ->leftJoin('usulan_pembimbing as up1', function ($join) {
                $join->on('up1.proposal_id', '=', 'proposal.id')
                     ->where('up1.urutan', '=', 1);
            })
            ->leftJoin('users as du1', 'up1.nim_nid_dosen', '=', 'du1.nim_nid')

            ->leftJoin('usulan_pembimbing as up2', function ($join) {
                $join->on('up2.proposal_id', '=', 'proposal.id')
                     ->where('up2.urutan', '=', 2);
            })
            ->leftJoin('users as du2', 'up2.nim_nid_dosen', '=', 'du2.nim_nid')

            ->leftJoin('dosen_pembimbing as dp1', function ($join) {
                $join->on('dp1.proposal_id', '=', 'proposal.id')
                     ->where('dp1.urutan', '=', 1);
            })
            ->leftJoin('users as dd1', 'dp1.nim_nid_dosen', '=', 'dd1.nim_nid')

            ->leftJoin('dosen_pembimbing as dp2', function ($join) {
                $join->on('dp2.proposal_id', '=', 'proposal.id')
                     ->where('dp2.urutan', '=', 2);
            })
            ->leftJoin('users as dd2', 'dp2.nim_nid_dosen', '=', 'dd2.nim_nid')

            ->select([
                'proposal.id',
                'proposal.nim_nid',
                'mhs.nama',
                'proposal.judul',
                'proposal.file_proposal',
                'proposal.tanggal_pengajuan',
                'proposal.status',

                'du1.nama as usulan_dosen1_nama',
                'du1.nim_nid as usulan_dosen1_nidn',
                'up1.status as usulan_dosen1_status',

                'du2.nama as usulan_dosen2_nama',
                'du2.nim_nid as usulan_dosen2_nidn',
                'up2.status as usulan_dosen2_status',

                'dd1.nama as dosen1_nama',
                'dd1.nim_nid as dosen1_nidn',

                'dd2.nama as dosen2_nama',
                'dd2.nim_nid as dosen2_nidn',
            ]);

        if ($request->filled('status')) {
            $query->where('proposal.status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('mhs.nama', 'like', "%{$search}%")
                  ->orWhere('proposal.nim_nid', 'like', "%{$search}%")
                  ->orWhere('proposal.judul', 'like', "%{$search}%");
            });
        }

        $proposals = $query->orderBy('proposal.tanggal_pengajuan', 'asc')->get();

        return view('pengajuan.proposal_dosen', compact('proposals'));
    }

    // =====================================================
    // DETAIL - Detail proposal
    // =====================================================
    public function detail($id)
    {
        if (!session('user')) {
            return redirect('/login')->with('error', 'Silakan login dulu!');
        }

        $proposal = DB::table('proposal')
            ->join('users as mhs', 'proposal.nim_nid', '=', 'mhs.nim_nid')

            ->leftJoin('usulan_pembimbing as up1', function ($join) {
                $join->on('up1.proposal_id', '=', 'proposal.id')
                     ->where('up1.urutan', '=', 1);
            })
            ->leftJoin('users as du1', 'up1.nim_nid_dosen', '=', 'du1.nim_nid')

            ->leftJoin('usulan_pembimbing as up2', function ($join) {
                $join->on('up2.proposal_id', '=', 'proposal.id')
                     ->where('up2.urutan', '=', 2);
            })
            ->leftJoin('users as du2', 'up2.nim_nid_dosen', '=', 'du2.nim_nid')

            ->leftJoin('dosen_pembimbing as dp1', function ($join) {
                $join->on('dp1.proposal_id', '=', 'proposal.id')
                     ->where('dp1.urutan', '=', 1);
            })
            ->leftJoin('users as dd1', 'dp1.nim_nid_dosen', '=', 'dd1.nim_nid')

            ->leftJoin('dosen_pembimbing as dp2', function ($join) {
                $join->on('dp2.proposal_id', '=', 'proposal.id')
                     ->where('dp2.urutan', '=', 2);
            })
            ->leftJoin('users as dd2', 'dp2.nim_nid_dosen', '=', 'dd2.nim_nid')

            ->leftJoin('tinjauan_proposal as tp', 'tp.proposal_id', '=', 'proposal.id')
            ->leftJoin('users as reviewer', 'tp.nim_nid_reviewer', '=', 'reviewer.nim_nid')

            ->select([
                'proposal.id',
                'proposal.nim_nid',
                'mhs.nama',
                'proposal.judul',
                'proposal.file_proposal',
                'proposal.tanggal_pengajuan',
                'proposal.status',

                'du1.nama as usulan_dosen1_nama',
                'du1.nim_nid as usulan_dosen1_nidn',
                'up1.status as usulan_dosen1_status',
                'up1.tanggal_usulan as usulan_dosen1_tanggal',

                'du2.nama as usulan_dosen2_nama',
                'du2.nim_nid as usulan_dosen2_nidn',
                'up2.status as usulan_dosen2_status',
                'up2.tanggal_usulan as usulan_dosen2_tanggal',

                'dd1.nama as dosen1_nama',
                'dd1.nim_nid as dosen1_nidn',
                'dp1.tanggal_penetapan as dosen1_tanggal',

                'dd2.nama as dosen2_nama',
                'dd2.nim_nid as dosen2_nidn',
                'dp2.tanggal_penetapan as dosen2_tanggal',

                'tp.catatan as tinjauan_catatan',
                'tp.file_tinjauan',
                'tp.tanggal_tinjauan',
                'reviewer.nama as reviewer_nama',
            ])
            ->where('proposal.id', $id)
            ->first();

        if (!$proposal) {
            return redirect('/proposal')->with('error', 'Data tidak ditemukan!');
        }

        return view('pengajuan.proposal_detail_dosen', compact('proposal'));
    }

    // =====================================================
    // VERIFIKASI - Form verifikasi proposal (tetapkan dosen)
    // =====================================================
    public function verifikasi($id)
    {
        if (!session('user')) {
            return redirect('/login')->with('error', 'Silakan login dulu!');
        }

        $role = strtolower(trim(session('user')->role));
        if ($role !== 'koordinator') {
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $proposal = DB::table('proposal')
            ->join('users as mhs', 'proposal.nim_nid', '=', 'mhs.nim_nid')

            ->leftJoin('usulan_pembimbing as up1', function ($join) {
                $join->on('up1.proposal_id', '=', 'proposal.id')
                     ->where('up1.urutan', '=', 1);
            })
            ->leftJoin('users as du1', 'up1.nim_nid_dosen', '=', 'du1.nim_nid')

            ->leftJoin('usulan_pembimbing as up2', function ($join) {
                $join->on('up2.proposal_id', '=', 'proposal.id')
                     ->where('up2.urutan', '=', 2);
            })
            ->leftJoin('users as du2', 'up2.nim_nid_dosen', '=', 'du2.nim_nid')

            // Dosen pembimbing tetap (kalau udah ditetapkan sebelumnya)
            ->leftJoin('dosen_pembimbing as dp1', function ($join) {
                $join->on('dp1.proposal_id', '=', 'proposal.id')
                     ->where('dp1.urutan', '=', 1);
            })
            ->leftJoin('users as dd1', 'dp1.nim_nid_dosen', '=', 'dd1.nim_nid')

            ->leftJoin('dosen_pembimbing as dp2', function ($join) {
                $join->on('dp2.proposal_id', '=', 'proposal.id')
                     ->where('dp2.urutan', '=', 2);
            })
            ->leftJoin('users as dd2', 'dp2.nim_nid_dosen', '=', 'dd2.nim_nid')

            ->select([
                'proposal.id',
                'proposal.nim_nid',
                'mhs.nama',
                'proposal.judul',
                'proposal.file_proposal',
                'proposal.tanggal_pengajuan',
                'proposal.status',

                // Usulan pembimbing 1
                'du1.nama as usulan_dosen1_nama',
                'du1.nim_nid as usulan_dosen1_nidn',
                'up1.status as usulan_dosen1_status',
                'up1.tanggal_usulan as usulan_dosen1_tanggal',

                // Usulan pembimbing 2
                'du2.nama as usulan_dosen2_nama',
                'du2.nim_nid as usulan_dosen2_nidn',
                'up2.status as usulan_dosen2_status',
                'up2.tanggal_usulan as usulan_dosen2_tanggal',

                // Dosen pembimbing tetap 1
                'dd1.nama as dosen1_nama',
                'dd1.nim_nid as dosen1_nidn',
                'dp1.tanggal_penetapan as dosen1_tanggal',

                // Dosen pembimbing tetap 2
                'dd2.nama as dosen2_nama',
                'dd2.nim_nid as dosen2_nidn',
                'dp2.tanggal_penetapan as dosen2_tanggal',
            ])
            ->where('proposal.id', $id)
            ->first();

        if (!$proposal) {
            return redirect('/proposal')->with('error', 'Data tidak ditemukan!');
        }

        // Ambil semua dosen untuk dropdown
        // Sesuai role lo sekarang masih pakai role lama
        $dosenList = DB::table('users')
            ->whereIn('role', ['dosen pembimbing', 'dosen penguji', 'dosen reviewer', 'koordinator'])
            ->select('nim_nid', 'nama')
            ->get();

        return view('pengajuan.proposal_verifikasi_dosen', compact('proposal', 'dosenList'));
    }

    // =====================================================
    // PROSES VERIFIKASI - Simpan penetapan dosen pembimbing
    // =====================================================
    public function prosesVerifikasi(Request $request, $id)
    {
        if (!session('user')) {
            return redirect('/login')->with('error', 'Silakan login dulu!');
        }

        $request->validate([
            'dosen_pembimbing_1' => 'required',
            'dosen_pembimbing_2' => 'required',
        ]);

        // Hapus dosen pembimbing lama kalau ada
        DB::table('dosen_pembimbing')->where('proposal_id', $id)->delete();

        // Insert dosen pembimbing 1
        DB::table('dosen_pembimbing')->insert([
            'proposal_id'       => $id,
            'nim_nid_dosen'     => $request->dosen_pembimbing_1,
            'urutan'            => 1,
            'tanggal_penetapan' => now()->toDateString(),
        ]);

        // Insert dosen pembimbing 2
        DB::table('dosen_pembimbing')->insert([
            'proposal_id'       => $id,
            'nim_nid_dosen'     => $request->dosen_pembimbing_2,
            'urutan'            => 2,
            'tanggal_penetapan' => now()->toDateString(),
        ]);

        // Update status usulan — yang dipilih jadi disetujui
        DB::table('usulan_pembimbing')
            ->where('proposal_id', $id)
            ->where('nim_nid_dosen', $request->dosen_pembimbing_1)
            ->update(['status' => 'disetujui']);

        DB::table('usulan_pembimbing')
            ->where('proposal_id', $id)
            ->where('nim_nid_dosen', $request->dosen_pembimbing_2)
            ->update(['status' => 'disetujui']);

        // Yang tidak dipilih jadi ditolak
        DB::table('usulan_pembimbing')
            ->where('proposal_id', $id)
            ->whereNotIn('nim_nid_dosen', [
                $request->dosen_pembimbing_1,
                $request->dosen_pembimbing_2
            ])
            ->update(['status' => 'ditolak']);

        // Update status proposal
        DB::table('proposal')
            ->where('id', $id)
            ->update([
                'status'     => 'menunggu_review',
                'updated_at' => now(),
            ]);

        return redirect('/proposal')->with('success', 'Dosen pembimbing berhasil ditetapkan!');
    }
}