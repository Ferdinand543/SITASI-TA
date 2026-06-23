<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VerifikasiSeminarController extends Controller
{
    public function submit(Request $request, $nim)
    {
        // ── Cek session ───────────────────────────────────────────────
        if (!session('user')) {
            return response()->json([
                'success' => false,
                'message' => 'Session habis, login ulang.'
            ], 401);
        }

        // ── Ambil data dosen dari DB (fresh) ──────────────────────────
        $dosen = DB::table('users')
            ->where('nim_nid', session('user')->nim_nid)
            ->first();

        if (!$dosen) {
            return response()->json([
                'success' => false,
                'message' => 'Data dosen tidak ditemukan.'
            ], 404);
        }

        // ── Ambil proposal terbaru milik mahasiswa (bukan dari URL) ───
        $proposalTerbaru = DB::table('proposal')
            ->where('nim_nid', $nim)
            ->latest()
            ->first();

        if (!$proposalTerbaru) {
            return response()->json([
                'success' => false,
                'message' => 'Data proposal mahasiswa tidak ditemukan.'
            ]);
        }

        // ── Cek urutan dosen (pembimbing 1 atau 2) ────────────────────
        $urutanDosen = DB::table('dosen_pembimbing')
            ->where('nim_nid_dosen', $dosen->nim_nid)
            ->where('proposal_id', $proposalTerbaru->id)
            ->value('urutan');

        if (!$urutanDosen) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan pembimbing mahasiswa ini.'
            ]);
        }

        // ── Cek bimbingan minimal 6x yang Valid dengan dosen ini ──────
        $jumlahBimbinganValid = DB::table('bimbingan')
            ->where('nim_nid', $nim)
            ->where('dosen_nid', $dosen->nim_nid)
            ->where('status_validasi', 'Valid')
            ->count();

        if ($jumlahBimbinganValid < 6) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa belum memenuhi syarat bimbingan minimal 6 kali. '
                           . 'Saat ini: ' . $jumlahBimbinganValid . ' kali bimbingan valid.'
            ]);
        }

        // ── Ambil atau buat record pengajuan_seminars ─────────────────
        $seminar = DB::table('pengajuan_seminars')
            ->where('mahasiswa_id', $nim)
            ->latest()
            ->first();

        if (!$seminar) {
            $seminarId = DB::table('pengajuan_seminars')->insertGetId([
                'mahasiswa_id' => $nim,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
            $seminar = DB::table('pengajuan_seminars')->find($seminarId);
        }

        $kolomStatus   = 'status_pembimbing'    . $urutanDosen;
        $kolomSignedAt = 'signed_at_pembimbing' . $urutanDosen;

        // ── Guard: dosen ini sudah pernah TTD ─────────────────────────
        if (($seminar->$kolomStatus ?? '') === 'layak') {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah pernah memverifikasi kelayakan seminar mahasiswa ini.'
            ]);
        }

        // ── Guard: pembimbing 2 harus tunggu pembimbing 1 dulu ────────
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
        $token       = hash('sha256', $dosen->nim_nid . $nim . $proposalTerbaru->id . $urutanDosen . now()->timestamp);
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

        // ── Kalau kedua pembimbing sudah TTD → mahasiswa boleh daftar seminar ──
        $seminarFresh = DB::table('pengajuan_seminars')
            ->where('id', $seminar->id)
            ->first();

        if (
            ($seminarFresh->status_pembimbing1 ?? '') === 'layak' &&
            ($seminarFresh->status_pembimbing2 ?? '') === 'layak'
        ) {
            DB::table('pengajuan_seminars')
                ->where('id', $seminar->id)
                ->update([
                    'status_administrasi' => 'Lolos Administrasi',
                    'updated_at'          => now(),
                ]);
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