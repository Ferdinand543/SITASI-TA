<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class VerifyTtdController extends Controller
{
    public function show($token)
    {
        $seminar = DB::table('pengajuan_seminars')
            ->where('token_qr', $token)
            ->orWhere('token_qr_pembimbing2', $token)
            ->first();

        if (!$seminar) {
            return view('verify.invalid', ['token' => $token]);
        }

        $mahasiswa = DB::table('users')->where('nim_nid', $seminar->mahasiswa_id)->first();

        $proposalTA = DB::table('proposal')
            ->where('nim_nid', $seminar->mahasiswa_id)
            ->latest()
            ->first();

        $pengajuanJudul = DB::table('pengajuan_judul')
            ->where('nim_nid', $seminar->mahasiswa_id)
            ->where('status', 'disetujui')
            ->latest('updated_at')
            ->first();

        $judulTA = $pengajuanJudul->judul_disetujui ?? '-';

        // ── Ambil data Pembimbing 1 ──
        $dosen1Rel = DB::table('dosen_pembimbing')
            ->where('proposal_id', $proposalTA->id ?? 0)
            ->where('urutan', 1)
            ->first();
        $dosen1 = $dosen1Rel
            ? DB::table('users')->where('nim_nid', $dosen1Rel->nim_nid_dosen)->first()
            : null;

        // ── Ambil data Pembimbing 2 ──
        $dosen2Rel = DB::table('dosen_pembimbing')
            ->where('proposal_id', $proposalTA->id ?? 0)
            ->where('urutan', 2)
            ->first();
        $dosen2 = $dosen2Rel
            ? DB::table('users')->where('nim_nid', $dosen2Rel->nim_nid_dosen)->first()
            : null;

        // ── Susun status & data masing-masing slot ──
        $pembimbing1 = [
            'dosen'       => $dosen1,
            'sudah_ttd'   => ($seminar->status_pembimbing1 ?? '') === 'layak',
            'signed_at'   => $seminar->signed_at_pembimbing1 ?? null,
            'fingerprint' => $seminar->fingerprint ?? null,
        ];

        $pembimbing2 = [
            'dosen'       => $dosen2,
            'sudah_ttd'   => ($seminar->status_pembimbing2 ?? '') === 'layak',
            'signed_at'   => $seminar->signed_at_pembimbing2 ?? null,
            'fingerprint' => $seminar->fingerprint_pembimbing2 ?? null,
        ];

        $statusLengkap = $pembimbing1['sudah_ttd'] && $pembimbing2['sudah_ttd'];

        return view('verify.ttd', [
            'mahasiswa'     => $mahasiswa,
            'judulTA'       => $judulTA,
            'pembimbing1'   => $pembimbing1,
            'pembimbing2'   => $pembimbing2,
            'statusLengkap' => $statusLengkap,
            'token'         => $token,
        ]);
    }
}