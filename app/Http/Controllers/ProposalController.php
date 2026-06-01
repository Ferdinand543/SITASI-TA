<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProposalController extends Controller
{
    private function isKoordinator()
    {
        $user = session('user');
        if (!$user) return false;
        if (strtolower(trim($user->role)) === 'admin') return true;
        return DB::table('dosen_roles')
            ->where('nim_nid', $user->nim_nid)
            ->where('role_dosen', 'koordinator')
            ->exists();
    }

    private function getDosenPembimbingList()
    {
        return DB::table('users')
            ->join('dosen_roles', 'users.nim_nid', '=', 'dosen_roles.nim_nid')
            ->where('users.role', 'dosen')
            ->where('dosen_roles.role_dosen', 'pembimbing')
            ->select('users.nim_nid', 'users.nama')
            ->distinct()
            ->get();
    }

    private function baseProposalQuery()
    {
        return DB::table('proposal')
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
            ->leftJoin('users as dd2', 'dp2.nim_nid_dosen', '=', 'dd2.nim_nid');
    }

    public function index(Request $request)
    {
        $user = session('user');
        if (!$user) return redirect('/login')->with('error', 'Silakan login dulu!');

        $role = strtolower(trim($user->role));

        if (!in_array($role, ['dosen', 'admin']) || !$this->isKoordinator()) {
            if ($role === 'mahasiswa') {
                return redirect('/mahasiswa')->with('error', 'Akses ditolak!');
            }
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $query = $this->baseProposalQuery()
            ->select([
                'proposal.id',
                'proposal.nim_nid',
                'mhs.nama',
                'proposal.judul',
                'proposal.file_proposal',
                'proposal.tanggal_pengajuan',
                'proposal.status',
                'proposal.nim_nid_reviewer',
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
                    ->orWhere('proposal.judul', 'like', "%{$search}%")
                    ->orWhere('du1.nama', 'like', "%{$search}%")
                    ->orWhere('du2.nama', 'like', "%{$search}%")
                    ->orWhere('dd1.nama', 'like', "%{$search}%")
                    ->orWhere('dd2.nama', 'like', "%{$search}%");
            });
        }

        $proposals = $query->orderBy('proposal.tanggal_pengajuan', 'asc')->get();

        $dosenReviewerList = DB::table('users')
            ->join('dosen_roles', 'users.nim_nid', '=', 'dosen_roles.nim_nid')
            ->where('dosen_roles.role_dosen', 'reviewer')
            ->select('users.nim_nid', 'users.nama')
            ->distinct()
            ->get()
            ->map(function ($dosen) {
                $jumlahProposal = DB::table('proposal')
                    ->where('nim_nid_reviewer', $dosen->nim_nid)
                    ->whereIn('status', ['menunggu_review', 'selesai'])
                    ->count();
                $dosen->jumlah_proposal  = $jumlahProposal;
                $dosen->sudah_ditugaskan = $jumlahProposal > 0;
                return $dosen;
            });

        $mahasiswaBelumReviewer = DB::table('proposal')
            ->join('users as mhs', 'proposal.nim_nid', '=', 'mhs.nim_nid')
            ->whereNull('proposal.nim_nid_reviewer')
            ->whereIn('proposal.status', ['menunggu_verifikasi', 'menunggu_review'])
            ->select([
                'proposal.id',
                'proposal.nim_nid',
                'mhs.nama',
                'proposal.judul',
                'proposal.tanggal_pengajuan',
                'proposal.status',
            ])
            ->orderBy('proposal.tanggal_pengajuan', 'asc')
            ->get();

        $reviewerListDropdown = DB::table('users')
            ->join('dosen_roles', 'users.nim_nid', '=', 'dosen_roles.nim_nid')
            ->where('dosen_roles.role_dosen', 'reviewer')
            ->select('users.nim_nid', 'users.nama')
            ->distinct()
            ->get();

        return view('pengajuan.proposal_dosen', compact(
            'proposals',
            'dosenReviewerList',
            'mahasiswaBelumReviewer',
            'reviewerListDropdown'
        ));
    }

    public function kelolaReviewer($nimReviewer)
    {
        $user = session('user');
        if (!$user) return redirect('/login')->with('error', 'Silakan login dulu!');

        if (!$this->isKoordinator()) {
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $dosen = DB::table('users')
            ->join('dosen_roles', 'users.nim_nid', '=', 'dosen_roles.nim_nid')
            ->where('users.nim_nid', $nimReviewer)
            ->where('dosen_roles.role_dosen', 'reviewer')
            ->select('users.nim_nid', 'users.nama')
            ->first();

        if (!$dosen) {
            return redirect('/proposal?tab=reviewer')->with('error', 'Dosen reviewer tidak ditemukan!');
        }

        $mahasiswaReviewer = DB::table('proposal')
            ->join('users as mhs', 'proposal.nim_nid', '=', 'mhs.nim_nid')
            ->where('proposal.nim_nid_reviewer', $nimReviewer)
            ->whereIn('proposal.status', ['menunggu_review', 'selesai'])
            ->select([
                'proposal.id',
                'proposal.nim_nid',
                'mhs.nama',
                'proposal.judul',
                'proposal.status',
                'proposal.tanggal_pengajuan',
            ])
            ->orderBy('proposal.tanggal_pengajuan', 'asc')
            ->get();

        $mahasiswaBelumReviewer = DB::table('proposal')
            ->join('users as mhs', 'proposal.nim_nid', '=', 'mhs.nim_nid')
            ->whereNull('proposal.nim_nid_reviewer')
            ->whereIn('proposal.status', ['menunggu_verifikasi', 'menunggu_review'])
            ->select([
                'proposal.id',
                'proposal.nim_nid',
                'mhs.nama',
                'proposal.judul',
            ])
            ->orderBy('proposal.tanggal_pengajuan', 'asc')
            ->get();

        return view('pengajuan.kelola_reviewer', compact(
            'dosen',
            'mahasiswaReviewer',
            'mahasiswaBelumReviewer'
        ));
    }

    public function tambahMahasiswaReviewer(Request $request, $nimReviewer)
    {
        $user = session('user');
        if (!$user) return redirect('/login')->with('error', 'Silakan login dulu!');

        if (!$this->isKoordinator()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['message' => 'Akses ditolak'], 403);
            }
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $request->validate(['proposal_ids' => 'required|array']);

        foreach ($request->proposal_ids as $proposalId) {
            DB::table('proposal')->where('id', $proposalId)->update([
                'nim_nid_reviewer' => $nimReviewer,
                'status'           => 'menunggu_review',
                'updated_at'       => now(),
            ]);
        }

        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['message' => 'Berhasil'], 200);
        }

        return redirect('/proposal/reviewer/' . $nimReviewer . '/kelola')
            ->with('success', count($request->proposal_ids) . ' mahasiswa berhasil ditambahkan!');
    }

    public function removeReviewer(Request $request, $id)
    {
        $user = session('user');
        if (!$user) return redirect('/login')->with('error', 'Silakan login dulu!');

        if (!$this->isKoordinator()) {
            if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['message' => 'Akses ditolak'], 403);
            }
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        DB::table('proposal')->where('id', $id)->update([
            'nim_nid_reviewer' => null,
            'status'           => 'menunggu_review',
            'updated_at'       => now(),
        ]);

        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['message' => 'Berhasil'], 200);
        }

        return back()->with('success', 'Penugasan reviewer berhasil dihapus!');
    }

    public function indexPenguji()
    {
        $user = session('user');
        if (!$user) return redirect('/login')->with('error', 'Silakan login dulu!');

        $isPenguji = DB::table('dosen_roles')
            ->where('nim_nid', $user->nim_nid)
            ->where('role_dosen', 'penguji')
            ->exists();

        if (!$isPenguji) {
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $proposals = $this->baseProposalQuery()
            ->select([
                'proposal.id',
                'proposal.nim_nid',
                'mhs.nama',
                'proposal.judul',
                'proposal.file_proposal',
                'proposal.tanggal_pengajuan',
                'proposal.status',
                'dd1.nama as dosen1_nama',
                'dd1.nim_nid as dosen1_nidn',
                'dd2.nama as dosen2_nama',
                'dd2.nim_nid as dosen2_nidn',
            ])
            ->orderBy('proposal.tanggal_pengajuan', 'asc')
            ->get();

        return view('pengajuan.proposal_penguji', compact('proposals'));
    }

    public function detail($id)
    {
        $user = session('user');
        if (!$user) return redirect('/login')->with('error', 'Silakan login dulu!');

        if (!$this->isKoordinator()) {
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $proposal = $this->baseProposalQuery()
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
                'proposal.nim_nid_reviewer',
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

        $dosenList = $this->getDosenPembimbingList();

        return view('pengajuan.proposal_verifikasi_dosen', compact('proposal', 'dosenList'));
    }

    public function verifikasi($id)
    {
        $user = session('user');
        if (!$user) return redirect('/login')->with('error', 'Silakan login dulu!');

        if (!$this->isKoordinator()) {
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $proposal = $this->baseProposalQuery()
            ->leftJoin('tinjauan_proposal as tp', 'tp.proposal_id', '=', 'proposal.id')
            ->select([
                'proposal.id',
                'proposal.nim_nid',
                'mhs.nama',
                'proposal.judul',
                'proposal.file_proposal',
                'proposal.tanggal_pengajuan',
                'proposal.status',
                'proposal.nim_nid_reviewer',
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
            ])
            ->where('proposal.id', $id)
            ->first();

        if (!$proposal) {
            return redirect('/proposal')->with('error', 'Data tidak ditemukan!');
        }

        $dosenList = $this->getDosenPembimbingList();

        return view('pengajuan.proposal_verifikasi_dosen', compact('proposal', 'dosenList'));
    }

    public function assignReviewer(Request $request, $id)
    {
        $user = session('user');
        if (!$user) return redirect('/login')->with('error', 'Silakan login dulu!');

        if (!$this->isKoordinator()) {
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $request->validate(['nim_nid_reviewer' => 'required']);

        DB::table('proposal')->where('id', $id)->update([
            'nim_nid_reviewer' => $request->nim_nid_reviewer,
            'status'           => 'menunggu_review',
            'updated_at'       => now(),
        ]);

        if ($request->filled('redirect_to_list')) {
            return redirect('/proposal?tab=reviewer')->with('success', 'Reviewer berhasil ditetapkan!');
        }

        return redirect('/proposal/' . $id)->with('success', 'Reviewer berhasil ditetapkan!');
    }

    public function prosesVerifikasi(Request $request, $id)
    {
        $user = session('user');
        if (!$user) return redirect('/login')->with('error', 'Silakan login dulu!');

        if (!$this->isKoordinator()) {
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'dosen_pembimbing_1' => 'required',
            'dosen_pembimbing_2' => 'required',
        ]);

        DB::table('dosen_pembimbing')->where('proposal_id', $id)->delete();

        DB::table('dosen_pembimbing')->insert([
            'proposal_id'       => $id,
            'nim_nid_dosen'     => $request->dosen_pembimbing_1,
            'urutan'            => 1,
            'tanggal_penetapan' => now()->toDateString(),
        ]);

        DB::table('dosen_pembimbing')->insert([
            'proposal_id'       => $id,
            'nim_nid_dosen'     => $request->dosen_pembimbing_2,
            'urutan'            => 2,
            'tanggal_penetapan' => now()->toDateString(),
        ]);

        DB::table('usulan_pembimbing')
            ->where('proposal_id', $id)
            ->where('nim_nid_dosen', $request->dosen_pembimbing_1)
            ->update(['status' => 'disetujui']);

        DB::table('usulan_pembimbing')
            ->where('proposal_id', $id)
            ->where('nim_nid_dosen', $request->dosen_pembimbing_2)
            ->update(['status' => 'disetujui']);

        DB::table('usulan_pembimbing')
            ->where('proposal_id', $id)
            ->whereNotIn('nim_nid_dosen', [
                $request->dosen_pembimbing_1,
                $request->dosen_pembimbing_2,
            ])
            ->update(['status' => 'ditolak']);

        DB::table('proposal')
            ->where('id', $id)
            ->update([
                'status'     => 'selesai',
                'updated_at' => now(),
            ]);

        return redirect('/proposal')->with('success', 'Dosen pembimbing berhasil ditetapkan!');
    }

    public function tetapkanUsulan(Request $request, $id, $urutan)
    {
        $user = session('user');
        if (!$user) return redirect('/login')->with('error', 'Silakan login dulu!');

        if (!$this->isKoordinator()) {
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $aksi = $request->aksi;

        if ($aksi === 'acc') {

            $usulan = DB::table('usulan_pembimbing')
                ->where('proposal_id', $id)
                ->where('urutan', $urutan)
                ->first();

            if (!$usulan) {
                return redirect()->back()->with('error', 'Usulan tidak ditemukan!');
            }

            DB::table('usulan_pembimbing')
                ->where('proposal_id', $id)
                ->where('urutan', $urutan)
                ->update(['status' => 'disetujui']);

            DB::table('dosen_pembimbing')
                ->where('proposal_id', $id)
                ->where('urutan', $urutan)
                ->delete();

            DB::table('dosen_pembimbing')->insert([
                'proposal_id'       => $id,
                'nim_nid_dosen'     => $usulan->nim_nid_dosen,
                'urutan'            => $urutan,
                'tanggal_penetapan' => now()->toDateString(),
            ]);

        } elseif ($aksi === 'tolak') {

            $request->validate([
                'dosen_pengganti' => 'required',
            ], [
                'dosen_pengganti.required' => 'Pilih dosen pengganti terlebih dahulu!',
            ]);

            DB::table('usulan_pembimbing')
                ->where('proposal_id', $id)
                ->where('urutan', $urutan)
                ->update(['status' => 'ditolak']);

            DB::table('dosen_pembimbing')
                ->where('proposal_id', $id)
                ->where('urutan', $urutan)
                ->delete();

            DB::table('dosen_pembimbing')->insert([
                'proposal_id'       => $id,
                'nim_nid_dosen'     => $request->dosen_pengganti,
                'urutan'            => $urutan,
                'tanggal_penetapan' => now()->toDateString(),
            ]);

        } else {
            return redirect()->back()->with('error', 'Aksi tidak valid!');
        }

        // ✅ DIUBAH: status tetap menunggu_verifikasi, bukan selesai
        DB::table('proposal')
            ->where('id', $id)
            ->update([
                'status'     => 'menunggu_verifikasi',
                'updated_at' => now(),
            ]);

        return redirect('/proposal/' . $id . '/verifikasi')
            ->with('success', 'Pembimbing ' . $urutan . ' berhasil ditetapkan!');
    }

    public function ubahPembimbing(Request $request, $id, $urutan)
    {
        $user = session('user');
        if (!$user) return redirect('/login')->with('error', 'Silakan login dulu!');

        if (!$this->isKoordinator()) {
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'dosen_baru' => 'required',
        ], [
            'dosen_baru.required' => 'Pilih dosen pengganti terlebih dahulu!',
        ]);

        DB::table('dosen_pembimbing')
            ->where('proposal_id', $id)
            ->where('urutan', $urutan)
            ->delete();

        DB::table('dosen_pembimbing')->insert([
            'proposal_id'       => $id,
            'nim_nid_dosen'     => $request->dosen_baru,
            'urutan'            => $urutan,
            'tanggal_penetapan' => now()->toDateString(),
        ]);

        // ✅ DIUBAH: status tetap menunggu_verifikasi, bukan selesai
        DB::table('proposal')
            ->where('id', $id)
            ->update([
                'status'     => 'menunggu_verifikasi',
                'updated_at' => now(),
            ]);

        return redirect('/proposal/' . $id . '/verifikasi')
            ->with('success', 'Pembimbing ' . $urutan . ' berhasil diubah!');
    }

    // ✅ METHOD BARU: simpan penetapan final → status jadi selesai
    public function simpanPenetapan($id)
    {
        $user = session('user');
        if (!$user) return redirect('/login')->with('error', 'Silakan login dulu!');

        if (!$this->isKoordinator()) {
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $jumlah = DB::table('dosen_pembimbing')
            ->where('proposal_id', $id)
            ->count();

        if ($jumlah < 2) {
            return redirect()->back()->with('error', 'Kedua dosen pembimbing harus ditetapkan terlebih dahulu!');
        }

        DB::table('proposal')
            ->where('id', $id)
            ->update([
                'status'     => 'selesai',
                'updated_at' => now(),
            ]);

        return redirect('/proposal')->with('success', 'Penetapan dosen pembimbing berhasil disimpan!');
    }

    public function lanjutkanKeReviewer(Request $request, $id)
    {
        $user = session('user');
        if (!$user) return redirect('/login')->with('error', 'Silakan login dulu!');

        if (!$this->isKoordinator()) {
            return redirect('/dashboard/dosen')->with('error', 'Akses ditolak!');
        }

        $jumlah = DB::table('dosen_pembimbing')
            ->where('proposal_id', $id)
            ->count();

        if ($jumlah < 2) {
            return redirect()->back()->with('error', 'Kedua dosen pembimbing harus ditetapkan terlebih dahulu!');
        }

        DB::table('proposal')
            ->where('id', $id)
            ->update([
                'status'     => 'menunggu_review',
                'updated_at' => now(),
            ]);

        return redirect('/proposal')
            ->with('success', 'Proposal berhasil diteruskan ke reviewer!');
    }
}