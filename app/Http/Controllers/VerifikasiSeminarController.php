<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VerifikasiSeminarController extends Controller
{
    public function submit(Request $request, $nim, $proposal_id)
    {
        // ── Cek session ───────────────────────────────────────────────
        if (!session('user')) {
            return response()->json(['success' => false, 'message' => 'Session habis, login ulang.'], 401);
        }

        // ── Ambil data dosen dari DB (fresh) ──────────────────────────
        $dosen = DB::table('users')->where('nim_nid', session('user')->nim_nid)->first();
        if (!$dosen) {
            return response()->json(['success' => false, 'message' => 'Data dosen tidak ditemukan.'], 404);
        }

        // ── Cek urutan dosen (pembimbing 1 atau 2) ────────────────────
        $proposalTA = DB::table('proposal')
            ->where('nim_nid', $nim)
            ->latest()
            ->first();

        $urutanDosen = DB::table('dosen_pembimbing')
            ->where('nim_nid_dosen', $dosen->nim_nid)
            ->where('proposal_id', $proposalTA->id ?? 0)
            ->value('urutan');

        if (!$urutanDosen) {
            return response()->json(['success' => false, 'message' => 'Anda bukan pembimbing mahasiswa ini.']);
        }

        $kolomStatus   = 'status_pembimbing'   . $urutanDosen;
        $kolomSignedAt = 'signed_at_pembimbing' . $urutanDosen;

        // ── Ambil data seminar ────────────────────────────────────────
        $seminar = DB::table('pengajuan_seminars')
            ->where('mahasiswa_id', $nim)
            ->latest()
            ->first();

        if (!$seminar) {
            return response()->json(['success' => false, 'message' => 'Data seminar mahasiswa tidak ditemukan.']);
        }

        // ── Guard: dosen ini sudah pernah TTD ─────────────────────────
        if (($seminar->$kolomStatus ?? '') === 'layak') {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah pernah memverifikasi seminar mahasiswa ini.'
            ]);
        }

        // ── Guard: kalau pembimbing 2, pastikan pembimbing 1 sudah TTD ─
        if ($urutanDosen == 2 && ($seminar->status_pembimbing1 ?? '') !== 'layak') {
            return response()->json([
                'success' => false,
                'message' => 'Pembimbing 1 belum melakukan verifikasi. Tunggu persetujuan Pembimbing 1 terlebih dahulu.'
            ]);
        }

        // ── Cek PIN ───────────────────────────────────────────────────
        if (!$dosen->pin) {
            return response()->json([
                'success' => false,
                'message' => 'PIN belum diset! Silakan set PIN dulu di halaman profil.'
            ]);
        }

        if (!Hash::check($request->pin, $dosen->pin)) {
            return response()->json([
                'success' => false,
                'message' => 'PIN salah! Coba lagi.'
            ]);
        }

        // ── Generate token & fingerprint per pembimbing ───────────────
        $token       = hash('sha256', $dosen->nim_nid . $nim . $proposal_id . $urutanDosen . now()->timestamp);
        $fingerprint = hash('sha256', $token . $dosen->nim_nid);
        $qrUrl       = url('/verify/ttd/' . $token);

        // ── Update kolom sesuai urutan dosen ──────────────────────────
        $updateData = [
            $kolomStatus   => 'layak',
            $kolomSignedAt => now(),
            'updated_at'   => now(),
        ];

        if ($urutanDosen == 1) {
            $updateData['token_qr']    = $token;
            $updateData['fingerprint'] = $fingerprint;
        } else {
            $updateData['token_qr_pembimbing2']    = $token;
            $updateData['fingerprint_pembimbing2'] = $fingerprint;
        }

        DB::table('pengajuan_seminars')
            ->where('id', $seminar->id)
            ->update($updateData);

        // ── Update status proposal hanya kalau kedua pembimbing sudah TTD ─
        $seminarFresh = DB::table('pengajuan_seminars')->where('id', $seminar->id)->first();
        if (
            ($seminarFresh->status_pembimbing1 ?? '') === 'layak' &&
            ($seminarFresh->status_pembimbing2 ?? '') === 'layak'
        ) {
            DB::table('pengajuan_proposal_bimbingan')
                ->where('id', $proposal_id)
                ->update(['status' => 'disetujui', 'updated_at' => now()]);
        }

        return response()->json([
            'success'     => true,
            'urutan'      => $urutanDosen,
            'nama_dosen'  => $dosen->nama,
            'nip'         => $dosen->nim_nid,
            'tanggal'     => now()->translatedFormat('d F Y'),
            'fingerprint' => $fingerprint,
            'qr_url'      => $qrUrl,
        ]);
    }
}