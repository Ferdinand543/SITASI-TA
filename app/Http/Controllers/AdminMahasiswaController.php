<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminMahasiswaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN DATA MAHASISWA
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $mahasiswa = User::where('role', 'mahasiswa')->get();

        return view('admin.mahasiswa.index', compact('mahasiswa'));
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN MAHASISWA
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'nim_nid' => 'required|unique:users,nim_nid',
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'angkatan' => 'required',
            'password' => 'required|min:6',
        ]);

        User::create([
            'nim_nid' => $request->nim_nid,
            'nama' => $request->nama,
            'email' => $request->email,
            'angkatan' => $request->angkatan,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
            'foto' => '',
        ]);

        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan');
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL MAHASISWA
    |--------------------------------------------------------------------------
    */

    public function show($nim_nid)
    {
        $mahasiswa = User::where('nim_nid', $nim_nid)
            ->where('role', 'mahasiswa')
            ->firstOrFail();

        return view('admin.mahasiswa.detail', compact('mahasiswa'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE MAHASISWA
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $nim_nid)
    {
        $mahasiswa = User::where('nim_nid', $nim_nid)
            ->where('role', 'mahasiswa')
            ->firstOrFail();

        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,' . $mahasiswa->nim_nid . ',nim_nid',
            'angkatan' => 'required',
        ]);

        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
            'angkatan' => $request->angkatan,
        ];

        // kalau password diisi
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $mahasiswa->update($data);

        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diupdate');
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS MAHASISWA
    |--------------------------------------------------------------------------
    */

    public function destroy($nim_nid)
    {
        $mahasiswa = User::where('nim_nid', $nim_nid)
            ->where('role', 'mahasiswa')
            ->firstOrFail();

        $mahasiswa->delete();

        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus');
    }
}