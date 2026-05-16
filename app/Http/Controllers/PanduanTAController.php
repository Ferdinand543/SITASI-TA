<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PanduanTAController extends Controller
{
    public function mahasiswa()
    {
        $userRole = 'mahasiswa';

        $dokumen = DB::table('panduan_ta_dokumen')
            ->where('aktif', true)
            ->whereIn('role', ['all', $userRole])
            ->orderBy('urutan')
            ->get();

        $shared = $dokumen->where('role', 'all');
        $khusus = $dokumen->where('role', $userRole);

        return view('mahasiswa.panduan_ta', compact('shared', 'khusus'));
    }

    public function dosen()
    {
        $userRole = 'dosen';

        $dokumen = DB::table('panduan_ta_dokumen')
            ->where('aktif', true)
            ->whereIn('role', ['all', $userRole])
            ->orderBy('urutan')
            ->get();

        $shared = $dokumen->where('role', 'all');
        $khusus = $dokumen->where('role', $userRole);

        return view('dosen.panduan_ta', compact('shared', 'khusus'));
    }

    public function download(int $id)
    {
        $userRole = Auth::user()->role;

        $dok = DB::table('panduan_ta_dokumen')
            ->where('id', $id)
            ->where('aktif', true)
            ->whereIn('role', ['all', $userRole])
            ->firstOrFail();

        return Storage::download($dok->file_path, $dok->judul . '.docx');
    }
}
