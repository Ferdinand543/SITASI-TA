<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewerController extends Controller
{
    // =====================================================
    // INDEX — Daftar proposal yang sudah selesai (siap direview)
    // =====================================================
    public function index(Request $request)
    {
        if (!session('user')) {
            return redirect('/login')->with('error', 'Silakan login dulu!');
        }

        $role = strtolower(trim(session('user')->role));

        if ($role !== 'dosen reviewer') {
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $nimReviewer = session('user')->nim_nid;

        // Ambil semua proposal dengan status 'selesai'
        $query = DB::table('proposal')
            ->join('users as mhs', 'proposal.nim_nid', '=', 'mhs.nim_nid')
            ->leftJoin('tinjauan_proposal as tp', function ($join) use ($nimReviewer) {
                $join->on('tp.proposal_id', '=', 'proposal.id')
                     ->where('tp.nim_nid_reviewer', '=', $nimReviewer);
            })
            // Dosen Pembimbing 1 (urutan = 1)
            ->leftJoin('dosen_pembimbing as dp1', function ($join) {
                $join->on('dp1.proposal_id', '=', 'proposal.id')
                     ->where('dp1.urutan', '=', 1);
            })
            ->leftJoin('users as dsn1', 'dp1.nim_nid_dosen', '=', 'dsn1.nim_nid')
            // Dosen Pembimbing 2 (urutan = 2)
            ->leftJoin('dosen_pembimbing as dp2', function ($join) {
                $join->on('dp2.proposal_id', '=', 'proposal.id')
                     ->where('dp2.urutan', '=', 2);
            })
            ->leftJoin('users as dsn2', 'dp2.nim_nid_dosen', '=', 'dsn2.nim_nid')
            ->where('proposal.status', 'selesai')
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

                // Dosen Pembimbing 1
                'dp1.nim_nid_dosen as nidn_dsn1',
                'dsn1.nama as nama_dsn1',
                'dp1.tanggal_penetapan as tgl_dsn1',

                // Dosen Pembimbing 2
                'dp2.nim_nid_dosen as nidn_dsn2',
                'dsn2.nama as nama_dsn2',
                'dp2.tanggal_penetapan as tgl_dsn2',
            ]);

        // Filter status review
        if ($request->filled('status_review')) {
            if ($request->status_review === 'menunggu') {
                $query->whereNull('tp.id');
            } elseif ($request->status_review === 'selesai') {
                $query->whereNotNull('tp.id');
            }
        }

        // Filter tanggal review
        if ($request->filled('tanggal')) {
            $query->whereDate('tp.tanggal_tinjauan', $request->tanggal);
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('mhs.nama', 'like', "%{$search}%")
                  ->orWhere('proposal.nim_nid', 'like', "%{$search}%")
                  ->orWhere('proposal.judul', 'like', "%{$search}%");
            });
        }

        $proposals = $query->orderBy('proposal.tanggal_pengajuan', 'asc')->get();

        // =====================================================
        // STATISTIK — dihitung dari data yang sudah difilter
        // =====================================================
        $totalProposal = $proposals->count();
        $totalMenunggu = $proposals->filter(fn($p) => is_null($p->tinjauan_id))->count();
        $totalSelesai  = $proposals->filter(fn($p) => !is_null($p->tinjauan_id))->count();

        return view('pengajuan.proposal_reviewer', compact(
            'proposals',
            'totalProposal',
            'totalMenunggu',
            'totalSelesai'
        ));
    }

    // =====================================================
    // DETAIL — Halaman detail hasil review proposal
    // =====================================================
    public function detail($id)
    {
        if (!session('user')) {
            return redirect('/login')->with('error', 'Silakan login dulu!');
        }

        $role = strtolower(trim(session('user')->role));
        if ($role !== 'dosen reviewer') {
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $nimReviewer = session('user')->nim_nid;

        $proposal = DB::table('proposal')
            ->join('users as mhs', 'proposal.nim_nid', '=', 'mhs.nim_nid')
            ->leftJoin('tinjauan_proposal as tp', function ($join) use ($nimReviewer) {
                $join->on('tp.proposal_id', '=', 'proposal.id')
                     ->where('tp.nim_nid_reviewer', '=', $nimReviewer);
            })
            // Dosen Pembimbing 1
            ->leftJoin('dosen_pembimbing as dp1', function ($join) {
                $join->on('dp1.proposal_id', '=', 'proposal.id')
                     ->where('dp1.urutan', '=', 1);
            })
            ->leftJoin('users as dsn1', 'dp1.nim_nid_dosen', '=', 'dsn1.nim_nid')
            // Dosen Pembimbing 2
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

                // Dosen Pembimbing 1
                'dp1.nim_nid_dosen as nidn_dsn1',
                'dsn1.nama as nama_dsn1',
                'dp1.tanggal_penetapan as tgl_dsn1',

                // Dosen Pembimbing 2
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
    // SIMPAN REVIEW — Simpan catatan & file tinjauan
    // =====================================================
    public function simpanReview(Request $request, $id)
    {
        if (!session('user')) {
            return redirect('/login')->with('error', 'Silakan login dulu!');
        }

        $role = strtolower(trim(session('user')->role));
        if ($role !== 'dosen reviewer') {
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'catatan'       => 'required|string|max:200',
            'file_tinjauan' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $nimReviewer = session('user')->nim_nid;

        // Upload file jika ada — pakai nama asli file
        $filePath = null;
        if ($request->hasFile('file_tinjauan')) {
            $file         = $request->file('file_tinjauan');
            $originalName = $file->getClientOriginalName();
            $filePath     = $file->storeAs('tinjauan', $originalName, 'public');
        }

        // Cek apakah sudah ada tinjauan dari reviewer ini
        $existing = DB::table('tinjauan_proposal')
            ->where('proposal_id', $id)
            ->where('nim_nid_reviewer', $nimReviewer)
            ->first();

        if ($existing) {
            $updateData = [
                'catatan'          => $request->catatan,
                'tanggal_tinjauan' => now()->toDateString(),
            ];
            if ($filePath) {
                $updateData['file_tinjauan'] = $filePath;
            }
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

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('reviewer.proposal')
            ->with('success', 'Review berhasil disimpan!');
    }
}