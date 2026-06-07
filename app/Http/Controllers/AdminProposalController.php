<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Proposal;

class AdminProposalController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('proposal')
            ->join('users', 'proposal.nim_nid', '=', 'users.nim_nid')
            ->leftJoin('tinjauan_proposal', 'proposal.id', '=', 'tinjauan_proposal.proposal_id')
            ->leftJoin('users as reviewer', 'tinjauan_proposal.nim_nid_reviewer', '=', 'reviewer.nim_nid')
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
            ->select(
                'proposal.id',
                'proposal.nim_nid',
                'proposal.judul',
                'proposal.file_proposal',
                'proposal.status',
                'proposal.created_at',
                'users.nama as nama_mahasiswa',
                'reviewer.nama as nama_reviewer',
                'tinjauan_proposal.catatan',
                'dd1.nama as dosen1_nama',
                'dd1.nim_nid as dosen1_nidn',
                'dd2.nama as dosen2_nama',
                'dd2.nim_nid as dosen2_nidn',
            )
            ->orderBy('proposal.created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('users.nama',       'like', "%{$search}%")
                  ->orWhere('users.nim_nid',  'like', "%{$search}%")
                  ->orWhere('proposal.judul', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('proposal.status', $request->status);
        }

        $proposals = $query->paginate(10)->withQueryString();

        $totalProposal          = DB::table('proposal')->count();
        $menungguVerifikasi     = DB::table('proposal')->where('status', 'menunggu_verifikasi')->count();
        $menungguReview         = DB::table('proposal')->where('status', 'menunggu_review')->count();
        $belumDireview          = $menungguVerifikasi + $menungguReview;
        $selesaiDireview        = DB::table('proposal')->where('status', 'selesai')->count();
        $ditolak                = DB::table('proposal')->where('status', 'ditolak')->count();
        $countPenetapanReviewer = $menungguVerifikasi;

        return view('admin.proposal.index', compact(
            'proposals',
            'totalProposal',
            'belumDireview',
            'menungguVerifikasi',
            'menungguReview',
            'selesaiDireview',
            'ditolak',
            'countPenetapanReviewer'
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

        if (!$proposal) abort(404);

        $dosenPembimbing = DB::table('dosen_pembimbing')
            ->join('users', 'dosen_pembimbing.nim_nid_dosen', '=', 'users.nim_nid')
            ->select('users.nama', 'users.nim_nid', 'dosen_pembimbing.urutan', 'dosen_pembimbing.tanggal_penetapan')
            ->where('dosen_pembimbing.proposal_id', $id)
            ->orderBy('dosen_pembimbing.urutan')
            ->get();

        $usulanPembimbing = DB::table('usulan_pembimbing')
            ->join('users', 'usulan_pembimbing.nim_nid_dosen', '=', 'users.nim_nid')
            ->select('users.nama', 'users.nim_nid', 'usulan_pembimbing.urutan', 'usulan_pembimbing.status', 'usulan_pembimbing.tanggal_usulan')
            ->where('usulan_pembimbing.proposal_id', $id)
            ->orderBy('usulan_pembimbing.urutan')
            ->get();

        return view('admin.proposal.detail', compact('proposal', 'dosenPembimbing', 'usulanPembimbing'));
    }

    public function serveFile($id)
    {
        $proposal = DB::table('proposal')->where('id', $id)->first();
        if (!$proposal || !$proposal->file_proposal) abort(404);

        $path = storage_path('app/public/' . $proposal->file_proposal);
        if (!file_exists($path)) $path = storage_path('app/' . $proposal->file_proposal);
        if (!file_exists($path)) $path = public_path($proposal->file_proposal);
        if (!file_exists($path)) abort(404, 'File tidak ditemukan');

        return response()->file($path, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="proposal_' . $id . '.pdf"',
        ]);
    }

    public function approve($id)
    {
        $proposal = Proposal::findOrFail($id);
        $proposal->update(['status' => 'selesai']);
        return back()->with('success', 'Proposal berhasil disetujui');
    }

    public function reject($id)
    {
        $proposal = Proposal::findOrFail($id);
        $proposal->update(['status' => 'ditolak']);
        return back()->with('success', 'Proposal berhasil ditolak');
    }
}