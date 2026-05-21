<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

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

    public function admin()
    {
        $userRole = 'admin';

        $dokumen = DB::table('panduan_ta_dokumen')
            ->where('aktif', true)
            ->whereIn('role', ['all', $userRole])
            ->orderBy('urutan')
            ->get();

        $shared = $dokumen->where('role', 'all');
        $khusus = $dokumen->where('role', $userRole);

        return view('admin.panduan_ta', compact('shared', 'khusus'));
    }

    public function create()
    {
        return view('admin.panduan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'file' => 'required|mimes:pdf,doc,docx',
            'role' => 'required'
        ]);

        // upload file
        $filePath = $request->file('file')->store('panduan_ta', 'public');

        DB::table('panduan_ta_dokumen')->insert([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => $filePath,
            'role' => $request->role,
            'icon' => 'document-text',
            'urutan' => 0,
            'aktif' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil ditambahkan');
    }

    public function download(int $id)
    {
        $userRole = session('user')->role;

        $dok = DB::table('panduan_ta_dokumen')
            ->where('id', $id)
            ->where('aktif', true)
            ->whereIn('role', ['all', $userRole])
            ->firstOrFail();

        return Storage::disk('public')->download(
            $dok->file_path,
            $dok->judul . '.docx'
        );
    }
}
