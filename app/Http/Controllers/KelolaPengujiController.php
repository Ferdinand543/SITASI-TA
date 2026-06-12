<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class KelolaPengujiController extends Controller
{
    public function index()
    {
        if (!session('user')) return redirect('/login');

        $dosenPenguji = DB::table('dosen_roles as dr')
            ->join('users as u', 'u.nim_nid', '=', 'dr.nim_nid')
            ->where('dr.role_dosen', 'penguji')
            ->select('u.nim_nid', 'u.nama')
            ->get()
            ->map(function ($dosen) {
                $dosen->jumlah_mahasiswa = DB::table('dosen_penguji_seminar')
                    ->where('nim_nid_dosen', $dosen->nim_nid)
                    ->distinct()
                    ->count('pengajuan_seminar_id');
                return $dosen;
            });

        return view('KoorNetapinPenguji.index', compact('dosenPenguji'));
    }

    public function mahasiswa()
    {
        if (!session('user')) return redirect('/login');

        $mahasiswaList = DB::table('pengajuan_seminars as ps')
            ->join('users as u', 'u.nim_nid', '=', 'ps.mahasiswa_id')
            ->where('ps.status_administrasi', 'Lolos Administrasi')
            
            ->select('u.nim_nid', 'u.nama', 'ps.judul_ta', 'ps.id as pengajuan_id', 'ps.mahasiswa_id')
            ->get()
            ->map(function ($mhs) {
                $mhs->angkatan = substr($mhs->nim_nid, 0, 4);

                $proposal = DB::table('proposal')
                    ->where('nim_nid', $mhs->mahasiswa_id)
                    ->latest()
                    ->first();

                $mhs->pembimbing1 = null;
                $mhs->pembimbing2 = null;

                if ($proposal) {
                    $pb1 = DB::table('dosen_pembimbing')->where('proposal_id', $proposal->id)->where('urutan', 1)->first();
                    $pb2 = DB::table('dosen_pembimbing')->where('proposal_id', $proposal->id)->where('urutan', 2)->first();
                    $mhs->pembimbing1 = $pb1 ? DB::table('users')->where('nim_nid', $pb1->nim_nid_dosen)->value('nama') : null;
                    $mhs->pembimbing2 = $pb2 ? DB::table('users')->where('nim_nid', $pb2->nim_nid_dosen)->value('nama') : null;
                }

                $mhs->penguji1 = DB::table('dosen_penguji_seminar as dps')
                    ->join('users as u', DB::raw('u.nim_nid COLLATE utf8mb4_general_ci'), '=', 'dps.nim_nid_dosen')
                    ->where('dps.pengajuan_seminar_id', $mhs->pengajuan_id)
                    ->where('dps.urutan', 1)
                    ->value('u.nama');

                $mhs->penguji2 = DB::table('dosen_penguji_seminar as dps')
                    ->join('users as u', DB::raw('u.nim_nid COLLATE utf8mb4_general_ci'), '=', 'dps.nim_nid_dosen')
                    ->where('dps.pengajuan_seminar_id', $mhs->pengajuan_id)
                    ->where('dps.urutan', 2)
                    ->value('u.nama');

                return $mhs;
            });

        $dosenList = DB::table('dosen_roles as dr')
            ->join('users as u', 'u.nim_nid', '=', 'dr.nim_nid')
            ->where('dr.role_dosen', 'penguji')
            ->select('u.nim_nid', 'u.nama')
            ->get()
            ->map(function ($dosen) {
                $dosen->jumlah_mahasiswa = DB::table('dosen_penguji_seminar')
                    ->where('nim_nid_dosen', $dosen->nim_nid)
                    ->distinct()
                    ->count('pengajuan_seminar_id');
                return $dosen;
            });

        $mahasiswaBelumDitetapkanPerDosen = [];

        foreach ($dosenList as $dosen) {
            $belum = DB::table('pengajuan_seminars as ps')
                ->join('users as u', 'u.nim_nid', '=', 'ps.mahasiswa_id')
                ->where('ps.status_administrasi', 'Lolos Administrasi')
                ->where('ps.status_seminar', 'Menunggu Jadwal')
                ->select('u.nim_nid', 'u.nama', 'ps.judul_ta', 'ps.id as pengajuan_id', 'ps.mahasiswa_id')
                ->get()
                ->map(function ($mhs) use ($dosen) {

                    $sudahJadiPenguji = DB::table('dosen_penguji_seminar')
                        ->where('pengajuan_seminar_id', $mhs->pengajuan_id)
                        ->where('nim_nid_dosen', $dosen->nim_nid)
                        ->exists();

                    if ($sudahJadiPenguji) return null;

                    $proposal = DB::table('proposal')
                        ->where('nim_nid', $mhs->mahasiswa_id)
                        ->latest()
                        ->value('id');

                    if ($proposal) {
                        $sudahJadiPembimbing = DB::table('dosen_pembimbing')
                            ->where('proposal_id', $proposal)
                            ->where('nim_nid_dosen', $dosen->nim_nid)
                            ->exists();

                        if ($sudahJadiPembimbing) return null;
                    }

                    $existing = DB::table('dosen_penguji_seminar')
                        ->where('pengajuan_seminar_id', $mhs->pengajuan_id)
                        ->get();

                    $mhs->jumlah_penguji = $existing->count();
                    $mhs->ada_penguji1   = $existing->where('urutan', 1)->count() > 0;
                    $mhs->ada_penguji2   = $existing->where('urutan', 2)->count() > 0;

                    return $mhs;
                })
                ->filter(fn($mhs) => $mhs !== null && $mhs->jumlah_penguji < 2)
                ->values();

            $mahasiswaBelumDitetapkanPerDosen[$dosen->nim_nid] = $belum;
        }

        return view('KoorNetapinPenguji.mahasiswa', compact(
            'mahasiswaList',
            'dosenList',
            'mahasiswaBelumDitetapkanPerDosen'
        ));
    }

    public function show($nim_nid)
    {
        if (!session('user')) return redirect('/login');

        $dosen = DB::table('users')->where('nim_nid', $nim_nid)->first();

        $mahasiswaSudahDitetapkan = DB::table('dosen_penguji_seminar as dps')
            ->join('pengajuan_seminars as ps', 'ps.id', '=', 'dps.pengajuan_seminar_id')
            ->join('users as u', 'u.nim_nid', '=', 'ps.mahasiswa_id')
            ->where('dps.nim_nid_dosen', $nim_nid)
            ->select('u.nim_nid', 'u.nama', 'ps.judul_ta', 'ps.id as pengajuan_id', 'ps.mahasiswa_id', 'dps.urutan')
            ->get()
            ->map(function ($mhs) {
                $proposal = DB::table('proposal')
                    ->where('nim_nid', $mhs->mahasiswa_id)
                    ->latest()
                    ->first();

                $mhs->pembimbing1 = null;
                $mhs->pembimbing2 = null;

                if ($proposal) {
                    $pb1 = DB::table('dosen_pembimbing')->where('proposal_id', $proposal->id)->where('urutan', 1)->first();
                    $pb2 = DB::table('dosen_pembimbing')->where('proposal_id', $proposal->id)->where('urutan', 2)->first();
                    $mhs->pembimbing1 = $pb1 ? DB::table('users')->where('nim_nid', $pb1->nim_nid_dosen)->value('nama') : null;
                    $mhs->pembimbing2 = $pb2 ? DB::table('users')->where('nim_nid', $pb2->nim_nid_dosen)->value('nama') : null;
                }

                $mhs->penguji1 = DB::table('dosen_penguji_seminar as dps')
                    ->join('users as u', DB::raw('u.nim_nid COLLATE utf8mb4_general_ci'), '=', 'dps.nim_nid_dosen')
                    ->where('dps.pengajuan_seminar_id', $mhs->pengajuan_id)
                    ->where('dps.urutan', 1)
                    ->value('u.nama');

                $mhs->penguji2 = DB::table('dosen_penguji_seminar as dps')
                    ->join('users as u', DB::raw('u.nim_nid COLLATE utf8mb4_general_ci'), '=', 'dps.nim_nid_dosen')
                    ->where('dps.pengajuan_seminar_id', $mhs->pengajuan_id)
                    ->where('dps.urutan', 2)
                    ->value('u.nama');

                return $mhs;
            });

        $sudahDitetapkanIds = $mahasiswaSudahDitetapkan->pluck('pengajuan_id')->toArray();

        $mahasiswaBelumDitetapkan = DB::table('pengajuan_seminars as ps')
            ->join('users as u', 'u.nim_nid', '=', 'ps.mahasiswa_id')
            ->where('ps.status_administrasi', 'Lolos Administrasi')
            ->where('ps.status_seminar', 'Menunggu Jadwal')
            ->whereNotIn('ps.id', $sudahDitetapkanIds)
            ->select('u.nim_nid', 'u.nama', 'ps.judul_ta', 'ps.id as pengajuan_id', 'ps.mahasiswa_id')
            ->get()
            ->map(function ($mhs) use ($nim_nid) {

                $proposal = DB::table('proposal')
                    ->where('nim_nid', $mhs->mahasiswa_id)
                    ->latest()
                    ->value('id');

                if ($proposal) {
                    $sudahJadiPembimbing = DB::table('dosen_pembimbing')
                        ->where('proposal_id', $proposal)
                        ->where('nim_nid_dosen', $nim_nid)
                        ->exists();

                    if ($sudahJadiPembimbing) return null;
                }

                $existing = DB::table('dosen_penguji_seminar')
                    ->where('pengajuan_seminar_id', $mhs->pengajuan_id)
                    ->get();

                $mhs->jumlah_penguji = $existing->count();
                $mhs->ada_penguji1   = $existing->where('urutan', 1)->count() > 0;
                $mhs->ada_penguji2   = $existing->where('urutan', 2)->count() > 0;
                return $mhs;
            })
            ->filter(fn($mhs) => $mhs !== null && $mhs->jumlah_penguji < 2)
            ->values();

        return view('KoorNetapinPenguji.show', compact('dosen', 'mahasiswaSudahDitetapkan', 'mahasiswaBelumDitetapkan'));
    }

    public function tetapkan($nim_nid)
    {
        if (!session('user')) return redirect('/login');

        $penugasan = request('penugasan', []);

        if (empty($penugasan)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 mahasiswa.');
        }

        foreach ($penugasan as $item) {
            $pengajuanId = $item['pengajuan_id'] ?? null;
            $urutan      = (int)($item['urutan'] ?? 0);

            if (!$pengajuanId || !in_array($urutan, [1, 2])) continue;

            $mahasiswaId = DB::table('pengajuan_seminars')
                ->where('id', $pengajuanId)
                ->value('mahasiswa_id');

            $proposalId = DB::table('proposal')
                ->where('nim_nid', $mahasiswaId)
                ->latest()
                ->value('id');

            if ($proposalId) {
                $sudahJadiPembimbing = DB::table('dosen_pembimbing')
                    ->where('proposal_id', $proposalId)
                    ->where('nim_nid_dosen', $nim_nid)
                    ->exists();

                if ($sudahJadiPembimbing) continue;
            }

            $sudahAda = DB::table('dosen_penguji_seminar')
                ->where('pengajuan_seminar_id', $pengajuanId)
                ->where('nim_nid_dosen', $nim_nid)
                ->exists();

            $slotTerisi = DB::table('dosen_penguji_seminar')
                ->where('pengajuan_seminar_id', $pengajuanId)
                ->where('urutan', $urutan)
                ->exists();

            $jumlahPenguji = DB::table('dosen_penguji_seminar')
                ->where('pengajuan_seminar_id', $pengajuanId)
                ->count();

            if (!$sudahAda && !$slotTerisi && $jumlahPenguji < 2) {
                DB::table('dosen_penguji_seminar')->insert([
                    'pengajuan_seminar_id' => $pengajuanId,
                    'nim_nid_dosen'        => $nim_nid,
                    'urutan'               => $urutan,
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ]);
            }
        }

        $from = request('_from', 'dosen');
        if ($from === 'mahasiswa') {
            return redirect()->route('penguji.mahasiswa.index')->with('success', 'Mahasiswa berhasil ditetapkan.');
        }

        return redirect()->route('penguji.show', $nim_nid)->with('success', 'Mahasiswa berhasil ditetapkan.');
    }
}