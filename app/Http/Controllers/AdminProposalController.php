<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminProposalController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('proposal')
            ->join('users', 'proposal.nim_nid', '=', 'users.nim_nid')

            ->leftJoin('tinjauan_proposal', 'proposal.id', '=', 'tinjauan_proposal.proposal_id')
            ->leftJoin('users as reviewer', 'tinjauan_proposal.nim_nid_reviewer', '=', 'reviewer.nim_nid')

            ->select(
                'proposal.*',
                'users.nama as nama_mahasiswa',
                'users.nim_nid',
                'reviewer.nama as nama_reviewer',
                'tinjauan_proposal.catatan'
            )

            ->orderBy('proposal.created_at', 'desc');

        // SEARCH
        if ($request->search) {

            $query->where(function ($q) use ($request) {

                $q->where('users.nama', 'like', '%' . $request->search . '%')
                    ->orWhere('users.nim_nid', 'like', '%' . $request->search . '%')
                    ->orWhere('proposal.judul', 'like', '%' . $request->search . '%');
            });
        }

        // FILTER STATUS
        if ($request->status) {

            $query->where('proposal.status', $request->status);
        }

        // FILTER TANGGAL
        if ($request->tanggal) {

            $query->whereDate(
                'proposal.tanggal_pengajuan',
                $request->tanggal
            );
        }

        // DATA PROPOSAL
        $proposal = $query->paginate(10);

        // STATISTIK
        $totalProposal = DB::table('proposal')->count();

        $belumDireview = DB::table('proposal')

            ->whereIn('status', [
                'menunggu_verifikasi',
                'menunggu_review'
            ])

            ->count();

        $selesaiDireview = DB::table('proposal')

            ->where('status', 'selesai')

            ->count();

        $ditolak = DB::table('proposal')

            ->where('status', 'ditolak')

            ->count();

        return view('admin.proposal.index', compact(
            'proposal',
            'totalProposal',
            'belumDireview',
            'selesaiDireview',
            'ditolak'
        ));
    }

    public function show($id)
    {
        $proposal = DB::table('proposal')

            ->join('users', 'proposal.nim_nid', '=', 'users.nim_nid')

            ->leftJoin('tinjauan_proposal', 'proposal.id', '=', 'tinjauan_proposal.proposal_id')

            ->leftJoin('users as reviewer', 'tinjauan_proposal.nim_nid_reviewer', '=', 'reviewer.nim_nid')

            ->select(
                'proposal.*',
                'users.nama as nama_mahasiswa',
                'users.email',
                'users.angkatan',
                'reviewer.nama as nama_reviewer',
                'tinjauan_proposal.catatan',
                'tinjauan_proposal.file_tinjauan',
                'tinjauan_proposal.tanggal_tinjauan'
            )

            ->where('proposal.id', $id)

            ->first();

        if (!$proposal) {

            abort(404);
        }

        $dosenPembimbing = DB::table('dosen_pembimbing')

            ->join('users', 'dosen_pembimbing.nim_nid_dosen', '=', 'users.nim_nid')

            ->select(
                'users.nama',
                'users.nim_nid',
                'dosen_pembimbing.urutan',
                'dosen_pembimbing.tanggal_penetapan'
            )

            ->where('dosen_pembimbing.proposal_id', $id)

            ->orderBy('dosen_pembimbing.urutan')

            ->get();

        $usulanPembimbing = DB::table('usulan_pembimbing')

            ->join('users', 'usulan_pembimbing.nim_nid_dosen', '=', 'users.nim_nid')

            ->select(
                'users.nama',
                'users.nim_nid',
                'usulan_pembimbing.urutan',
                'usulan_pembimbing.status',
                'usulan_pembimbing.tanggal_usulan'
            )

            ->where('usulan_pembimbing.proposal_id', $id)

            ->orderBy('usulan_pembimbing.urutan')

            ->get();

        $progressTA = DB::table('progress_ta')

            ->where('nim_nid', $proposal->nim_nid)

            ->orderBy('id')

            ->get();

        return view('admin.proposal.detail', compact(
            'proposal',
            'dosenPembimbing',
            'usulanPembimbing',
            'progressTA'
        ));
    }
}