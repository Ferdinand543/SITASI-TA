<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PanduanTAController extends Controller
{
    // ── MAHASISWA: tampilkan role 'all' dan 'mahasiswa' — flat, tanpa section
    public function mahasiswa()
    {
        $dokumen = DB::table('panduan_ta_dokumen')
            ->where('aktif', true)
            ->whereIn('role', ['all', 'mahasiswa'])
            ->orderBy('urutan')
            ->get();

        return view('mahasiswa.panduan_ta', compact('dokumen'));
    }

    // ── DOSEN: tampilkan role 'all' dan 'dosen' — flat, tanpa section
    public function dosen()
    {
        $dokumen = DB::table('panduan_ta_dokumen')
            ->where('aktif', true)
            ->whereIn('role', ['all', 'dosen'])
            ->orderBy('urutan')
            ->get();

        return view('dosen.panduan_ta', compact('dokumen'));
    }

    // ── ADMIN: tampilkan SEMUA dokumen — flat, tanpa section
    public function admin()
    {
        $dokumen = DB::table('panduan_ta_dokumen')
            ->where('aktif', true)
            ->orderBy('urutan')
            ->get();

        return view('admin.panduan_ta', compact('dokumen'));
    }

    public function create()
    {
        return view('admin.panduan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required',
            'deskripsi' => 'required',
            'file'      => 'required|mimes:pdf,doc,docx',
            'role'      => 'required|in:all,mahasiswa,dosen',
        ]);

        $filePath = $request->file('file')->store('panduan_ta', 'public');

        DB::table('panduan_ta_dokumen')->insert([
            'judul'      => $request->judul,
            'deskripsi'  => $request->deskripsi,
            'file_path'  => $filePath,
            'role'       => $request->role,
            'icon'       => 'document-text',
            'urutan'     => 0,
            'aktif'      => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('panduan-ta.admin')->with('success', 'Dokumen berhasil ditambahkan');
    }

    public function download(int $id)
    {
        $userRole = auth()->user()->role ?? session('user')->role;

        if ($userRole === 'admin') {
            $dok = DB::table('panduan_ta_dokumen')
                ->where('id', $id)
                ->where('aktif', true)
                ->firstOrFail();
        } else {
            $dok = DB::table('panduan_ta_dokumen')
                ->where('id', $id)
                ->where('aktif', true)
                ->whereIn('role', ['all', $userRole])
                ->firstOrFail();
        }

        $ext      = pathinfo($dok->file_path, PATHINFO_EXTENSION);
        $filename = $dok->judul . '.' . $ext;

        return Storage::disk('public')->download($dok->file_path, $filename);
    }

    public function destroy($id)
    {
        $panduan = DB::table('panduan_ta_dokumen')->where('id', $id)->first();

        if (!$panduan) {
            abort(404);
        }

        if ($panduan->file_path) {
            Storage::disk('public')->delete($panduan->file_path);
        }

        DB::table('panduan_ta_dokumen')->where('id', $id)->delete();

        return redirect()->route('panduan-ta.admin')->with('success', 'Dokumen berhasil dihapus.');
    }
}