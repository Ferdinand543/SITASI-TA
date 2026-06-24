<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProposalMahasiswa;
use App\Models\UsulanPembimbing;

class ProposalMahasiswaController extends Controller
{
    public function index()
    {
        if (!session('user')) {
            return redirect('/login')->with('error', 'Silakan login dulu!');
        }

        $nim = session('user')->nim_nid;

        $totalDirespon = ProposalMahasiswa::where('nim_nid', $nim)
            ->whereIn('status', ['selesai', 'ditolak', 'disetujui'])
            ->count();
        session()->put('notif_proposal_terakhir_' . $nim, $totalDirespon);

        $proposalList = ProposalMahasiswa::where('nim_nid', $nim)
            ->with(['usulanPembimbing', 'dosenPembimbing'])
            ->latest()
            ->get();

        $dosenList = DB::table('users')
            ->join('dosen_roles', 'users.nim_nid', '=', 'dosen_roles.nim_nid')
            ->where('dosen_roles.role_dosen', 'pembimbing')
            ->orderBy('users.nama')
            ->select('users.nim_nid', 'users.nama')
            ->distinct()
            ->get();

        $judulDisetujui = DB::table('pengajuan_judul')
            ->where('nim_nid', $nim)
            ->where('status', 'disetujui')
            ->orderByDesc('created_at')
            ->value('judul_disetujui');

        return view('pengajuan.proposal_mahasiswa', compact('proposalList', 'dosenList', 'judulDisetujui'));
    }

    public function store(Request $request)
    {
        if (!session('user')) {
            return redirect('/login')->with('error', 'Silakan login dulu!');
        }

        $request->validate([
            'judul'             => 'required|string|max:255',
            'file_proposal'     => 'required|file|mimes:pdf|max:5120',
            'pembimbing_1'      => 'required|string|max:20',
            'pembimbing_2'      => 'required|string|max:20',
            'tanggal_pengajuan' => 'required|date',
        ]);

        try {
            $originalName = $request->file('file_proposal')->getClientOriginalName();
            $filePath = $request->file('file_proposal')->storeAs('proposals', $originalName, 'public');

            $proposal = ProposalMahasiswa::create([
                'nim_nid'           => session('user')->nim_nid,
                'judul'             => $request->judul,
                'file_proposal'     => $filePath,
                'tanggal_pengajuan' => $request->tanggal_pengajuan,
                'status'            => 'menunggu_review',
            ]);

            UsulanPembimbing::create([
                'proposal_id'    => $proposal->id,
                'nim_nid_dosen'  => $request->pembimbing_1,
                'urutan'         => 1,
                'status'         => 'menunggu',
                'tanggal_usulan' => $request->tanggal_pengajuan,
            ]);

            UsulanPembimbing::create([
                'proposal_id'    => $proposal->id,
                'nim_nid_dosen'  => $request->pembimbing_2,
                'urutan'         => 2,
                'status'         => 'menunggu',
                'tanggal_usulan' => $request->tanggal_pengajuan,
            ]);

            return redirect()->route('proposal.mahasiswa')->with('success', 'Proposal berhasil diupload!');
        } catch (\Exception $e) {
            return redirect()->route('proposal.mahasiswa')->with('error', 'Upload proposal gagal. Silakan coba lagi.');
        }
    }

    public function detail(int $id)
    {
        if (!session('user')) {
            return redirect('/login')->with('error', 'Silakan login dulu!');
        }

        $nim = session('user')->nim_nid;

        $totalDirespon = ProposalMahasiswa::where('nim_nid', $nim)
            ->whereIn('status', ['selesai', 'ditolak', 'disetujui'])
            ->count();
        session()->put('notif_proposal_terakhir_' . $nim, $totalDirespon);

        $proposal = ProposalMahasiswa::with(['usulanPembimbing', 'dosenPembimbing', 'tinjauanProposal'])
            ->findOrFail($id);

        return view('pengajuan.proposal_mahasiswa_detail', compact('proposal'));
    }
}