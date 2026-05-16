<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewerController extends Controller
{
    // =====================================================
    // HELPER CEK REVIEWER
    // =====================================================
    private function isReviewer()
    {
        $user = session('user');
        if (!$user) return false;

        return DB::table('dosen_roles')
            ->where('nim_nid', $user->nim_nid)
            ->where('role_dosen', 'reviewer')
            ->exists();
    }

    // =====================================================
    // INDEX
    // =====================================================
    public function index(Request $request)
    {
        if (!session('user')) return redirect('/login')->with('error', 'Silakan login dulu!');

        if (!$this->isReviewer()) {
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $nimReviewer = session('user')->nim_nid;

        $query = DB::table('proposal')
            ->join('users as mhs', 'proposal.nim_nid', '=', 'mhs.nim_nid')
            ->leftJoin('tinjauan_proposal as tp', function ($join) use ($nimReviewer) {
                $join->on('tp.proposal_id', '=', 'proposal.id')
                     ->where('tp.nim_nid_reviewer', '=', $nimReviewer);
            })
            ->leftJoin('dosen_pembimbing as dp1', function ($join) {
                $join->on('dp1.proposal_id', '=', 'proposal.id')
                     ->where('dp1.urutan', '=', 1);
            })
            ->leftJoin('users as dsn1', 'dp1.nim_nid_dosen', '=', 'dsn1.nim_nid')
            ->leftJoin('dosen_pembimbing as dp2', function ($join) {
                $join->on('dp2.proposal_id', '=', 'proposal.id')
                     ->where('dp2.urutan', '=', 2);
            })
            ->leftJoin('users as dsn2', 'dp2.nim_nid_dosen', '=', 'dsn2.nim_nid')
            ->whereIn('proposal.status', ['menunggu_review', 'selesai'])
            ->select([
                'proposal.id',
                'proposal.nim_nid',
                'mhs.nama',
                'proposal.judul',
                'proposal.file_proposal',
                'proposal.tanggal_pengajuan',
                'proposal.status as proposal_status',
                'tp.id as tinjauan_id',
                'tp.catatan',
                'tp.file_tinjauan',
                'tp.tanggal_tinjauan',
                'dp1.nim_nid_dosen as nidn_dsn1',
                'dsn1.nama as nama_dsn1',
                'dp1.tanggal_penetapan as tgl_dsn1',
                'dp2.nim_nid_dosen as nidn_dsn2',
                'dsn2.nama as nama_dsn2',
                'dp2.tanggal_penetapan as tgl_dsn2',
            ]);

        if ($request->filled('status_review')) {
            if ($request->status_review === 'menunggu') {
                $query->whereNull('tp.id');
            } elseif ($request->status_review === 'selesai') {
                $query->whereNotNull('tp.id');
            }
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tp.tanggal_tinjauan', $request->tanggal);
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

        $totalProposal = $proposals->count();
        $totalMenunggu = $proposals->filter(fn($p) => is_null($p->tinjauan_id))->count();
        $totalSelesai  = $proposals->filter(fn($p) => !is_null($p->tinjauan_id))->count();

        return view('pengajuan.proposal_reviewer', compact(
            'proposals', 'totalProposal', 'totalMenunggu', 'totalSelesai'
        ));
    }

    // =====================================================
    // DETAIL
    // =====================================================
    public function detail($id)
    {
        if (!session('user')) return redirect('/login')->with('error', 'Silakan login dulu!');

        if (!$this->isReviewer()) {
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $nimReviewer = session('user')->nim_nid;

        $proposal = DB::table('proposal')
            ->join('users as mhs', 'proposal.nim_nid', '=', 'mhs.nim_nid')
            ->leftJoin('tinjauan_proposal as tp', function ($join) use ($nimReviewer) {
                $join->on('tp.proposal_id', '=', 'proposal.id')
                     ->where('tp.nim_nid_reviewer', '=', $nimReviewer);
            })
            ->leftJoin('dosen_pembimbing as dp1', function ($join) {
                $join->on('dp1.proposal_id', '=', 'proposal.id')
                     ->where('dp1.urutan', '=', 1);
            })
            ->leftJoin('users as dsn1', 'dp1.nim_nid_dosen', '=', 'dsn1.nim_nid')
            ->leftJoin('dosen_pembimbing as dp2', function ($join) {
                $join->on('dp2.proposal_id', '=', 'proposal.id')
                     ->where('dp2.urutan', '=', 2);
            })
            ->leftJoin('users as dsn2', 'dp2.nim_nid_dosen', '=', 'dsn2.nim_nid')
            ->where('proposal.id', $id)
            ->select([
                'proposal.id',
                'proposal.nim_nid',
                'mhs.nama',
                'proposal.judul',
                'proposal.file_proposal',
                'proposal.tanggal_pengajuan',
                'proposal.status as proposal_status',
                'tp.id as tinjauan_id',
                'tp.catatan',
                'tp.file_tinjauan',
                'tp.tanggal_tinjauan',
                'dp1.nim_nid_dosen as nidn_dsn1',
                'dsn1.nama as nama_dsn1',
                'dp1.tanggal_penetapan as tgl_dsn1',
                'dp2.nim_nid_dosen as nidn_dsn2',
                'dsn2.nama as nama_dsn2',
                'dp2.tanggal_penetapan as tgl_dsn2',
            ])
            ->first();

        if (!$proposal) {
            return redirect()->route('reviewer.proposal')->with('error', 'Proposal tidak ditemukan!');
        }

        return view('pengajuan.proposal_detail_reviewer', compact('proposal'));
    }

    // =====================================================
    // SIMPAN REVIEW
    // =====================================================
    public function simpanReview(Request $request, $id)
    {
        if (!session('user')) return redirect('/login')->with('error', 'Silakan login dulu!');

        if (!$this->isReviewer()) {
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'catatan'       => 'required|string|max:200',
            'file_tinjauan' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $nimReviewer = session('user')->nim_nid;

        $filePath = null;
        if ($request->hasFile('file_tinjauan')) {
            $file         = $request->file('file_tinjauan');
            $originalName = $file->getClientOriginalName();
            $filePath     = $file->storeAs('tinjauan', $originalName, 'public');
        }

        $existing = DB::table('tinjauan_proposal')
            ->where('proposal_id', $id)
            ->where('nim_nid_reviewer', $nimReviewer)
            ->first();

        if ($existing) {
            $updateData = [
                'catatan'          => $request->catatan,
                'tanggal_tinjauan' => now()->toDateString(),
            ];
            if ($filePath) $updateData['file_tinjauan'] = $filePath;

            DB::table('tinjauan_proposal')
                ->where('id', $existing->id)
                ->update($updateData);
        } else {
            DB::table('tinjauan_proposal')->insert([
                'proposal_id'      => $id,
                'nim_nid_reviewer' => $nimReviewer,
                'catatan'          => $request->catatan,
                'file_tinjauan'    => $filePath,
                'tanggal_tinjauan' => now()->toDateString(),
            ]);
        }

        // Update status proposal jadi selesai setelah direview
        DB::table('proposal')
            ->where('id', $id)
            ->update(['status' => 'selesai']);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('reviewer.proposal')->with('success', 'Review berhasil disimpan!');
    }
}
