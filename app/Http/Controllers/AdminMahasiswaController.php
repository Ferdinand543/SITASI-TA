<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminMahasiswaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN HALAMAN DAFTAR MAHASISWA
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = User::where('role', 'mahasiswa');

        /*
        |--------------------------------------------------------------------------
        | FILTER SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->search) {

            $query->where(function ($q) use ($request) {

                $q->where('name', 'like', '%' . $request->search . '%')

                  ->orWhere('nim', 'like', '%' . $request->search . '%');

            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER ANGKATAN
        |--------------------------------------------------------------------------
        */

        if ($request->angkatan) {

            $query->where(
                'angkatan',
                $request->angkatan
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->status) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA
        |--------------------------------------------------------------------------
        */

        $mahasiswa = $query->latest()->get();

        return view(
            'admin.mahasiswa.index',
            compact('mahasiswa')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL MAHASISWA
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $mahasiswa = User::findOrFail($id);

        return view(
            'admin.mahasiswa.detail',
            compact('mahasiswa')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $mahasiswa = User::findOrFail($id);

        return view(
            'admin.mahasiswa.edit',
            compact('mahasiswa')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE DATA MAHASISWA
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $request->validate([

            'name'      => 'required',

            'email'     => 'required|email',

            'angkatan'  => 'required',

            'status'    => 'required|in:Aktif,Nonaktif'

        ]);

        $mahasiswa = User::findOrFail($id);

        $mahasiswa->update([

            'name'      => $request->name,

            'email'     => $request->email,

            'angkatan'  => $request->angkatan,

            'status'    => $request->status

        ]);

        return redirect('/admin/mahasiswa')

            ->with(
                'success',
                'Data mahasiswa berhasil diperbarui'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS DATA MAHASISWA
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $mahasiswa = User::findOrFail($id);

        $mahasiswa->delete();

        return redirect('/admin/mahasiswa')

            ->with(
                'success',
                'Data mahasiswa berhasil dihapus'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN TAMBAH MAHASISWA
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('admin.mahasiswa.create');
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN DATA MAHASISWA BARU
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'name'      => 'required',

            'nim'       => 'required|unique:users',

            'email'     => 'required|email|unique:users',

            'angkatan'  => 'required',

            'password'  => 'required|min:6'

        ]);

        User::create([

            'name'      => $request->name,

            'nim'       => $request->nim,

            'email'     => $request->email,

            'angkatan'  => $request->angkatan,

            'role'      => 'mahasiswa',

            'status'    => 'Aktif',

            'password'  => Hash::make($request->password)

        ]);

        return redirect('/admin/mahasiswa')

            ->with(
                'success',
                'Mahasiswa berhasil ditambahkan'
            );
    }
}