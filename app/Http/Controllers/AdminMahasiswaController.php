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
    try {

        $user = new User();

        $user->nim_nid = $request->nim_nid;
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->angkatan = $request->angkatan;
        $user->password = Hash::make($request->password);
        $user->role = 'mahasiswa';
        $user->foto = '';

        $user->save();

        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Berhasil tambah mahasiswa');

    } catch (\Exception $e) {

        dd($e->getMessage());

    }
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