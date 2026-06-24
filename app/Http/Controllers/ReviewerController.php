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

        if (strtolower(trim($user->role)) === 'admin') return true;

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

        $user    = session('user');
        $isAdmin = strtolower(trim($user->role)) === 'admin';
        $nimReviewer = $user->nim_nid;

        // =====================================================
        // ADMIN: lihat SEMUA proposal yang sudah punya reviewer
        // DOSEN REVIEWER: lihat proposal miliknya sendiri (TIDAK DIUBAH)
        // =====================================================
        if ($isAdmin) {
            $query = DB::table('proposal')
                ->join('users as mhs', 'proposal.nim_nid', '=', 'mhs.nim_nid')
                ->leftJoin('tinjauan_proposal as tp', 'tp.proposal_id', '=', 'proposal.id')
                ->whereNotNull('proposal.nim_nid_reviewer')
                ->whereIn('proposal.status', ['menunggu_review', 'menunggu_verifikasi', 'selesai'])
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
                ]);
        } else {
            // DOSEN REVIEWER — TIDAK DIUBAH SAMA SEKALI
            $query = DB::table('proposal')
                ->join('users as mhs', 'proposal.nim_nid', '=', 'mhs.nim_nid')
                ->leftJoin('tinjauan_proposal as tp', function ($join) use ($nimReviewer) {
                    $join->on('tp.proposal_id', '=', 'proposal.id')
                        ->where('tp.nim_nid_reviewer', '=', $nimReviewer);
                })
                ->whereIn('proposal.status', ['menunggu_review', 'menunggu_verifikasi', 'selesai'])
                ->where('proposal.nim_nid_reviewer', $nimReviewer)
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
                ]);
        }

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

        $mahasiswaBelumReviewerCount = DB::table('proposal')
            ->whereNull('nim_nid_reviewer')
            ->whereIn('status', ['menunggu_verifikasi', 'menunggu_review'])
            ->count();

        return view('pengajuan.proposal_reviewer', compact(
            'proposals',
            'totalProposal',
            'totalMenunggu',
            'totalSelesai',
            'mahasiswaBelumReviewerCount'
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
            ->where('proposal.id', $id)
            ->where('proposal.nim_nid_reviewer', $nimReviewer)
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

        DB::table('proposal')
            ->where('id', $id)
            ->update(['status' => 'menunggu_verifikasi', 'updated_at' => now()]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('reviewer.proposal')->with('success', 'Review berhasil disimpan!');
    }
}