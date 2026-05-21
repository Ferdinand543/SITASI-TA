<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminDosenController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN HALAMAN DOSEN
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $dosen = User::where('role', 'dosen')
            ->orderBy('nama', 'asc')
            ->get();

        return view('admin.dosen.index', compact('dosen'));
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN DOSEN
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'nim_nid' => 'required|unique:users,nim_nid',
            'nama'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'roles'    => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | SIMPAN USER DOSEN
            |--------------------------------------------------------------------------
            */

            User::create([
                'nim_nid' => $request->nim_nid,
                'nama'    => $request->nama,
                'email'   => $request->email,
                'password'=> Hash::make($request->password),
                'role'    => 'dosen',
                'foto'    => '',
            ]);

            /*
            |--------------------------------------------------------------------------
            | SIMPAN ROLE DOSEN
            |--------------------------------------------------------------------------
            */

            $roles = [];

            foreach ($request->roles as $role) {

                $roles[] = [
                    'nim_nid'    => $request->nim_nid,
                    'role_dosen' => $role,
                ];
            }

            DB::table('dosen_roles')->insert($roles);

            DB::commit();

            return redirect()
                ->route('dosen.index')
                ->with('success', 'Data dosen berhasil ditambahkan');

        } catch (\Exception $e) {

            DB::rollback();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL DOSEN
    |--------------------------------------------------------------------------
    */

    public function show($nim_nid)
    {
        $dosen = User::where('nim_nid', $nim_nid)
            ->where('role', 'dosen')
            ->firstOrFail();

        $roles = DB::table('dosen_roles')
            ->where('nim_nid', $nim_nid)
            ->pluck('role_dosen');

        return view('admin.dosen.detail', compact('dosen', 'roles'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM EDIT DOSEN
    |--------------------------------------------------------------------------
    */

    public function edit($nim_nid)
    {
        $dosen = User::where('nim_nid', $nim_nid)
            ->where('role', 'dosen')
            ->firstOrFail();

        $roles = DB::table('dosen_roles')
            ->where('nim_nid', $nim_nid)
            ->pluck('role_dosen')
            ->toArray();

        return view('admin.dosen.edit', compact('dosen', 'roles'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE DOSEN
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $nim_nid)
    {
        $request->validate([
            'nama'  => 'required',
            'email' => 'required|email|unique:users,email,' . $nim_nid . ',nim_nid',
            'roles' => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {

            $dosen = User::where('nim_nid', $nim_nid)
                ->where('role', 'dosen')
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | UPDATE USER
            |--------------------------------------------------------------------------
            */

            $dosen->update([
                'nama'  => $request->nama,
                'email' => $request->email,
            ]);

            /*
            |--------------------------------------------------------------------------
            | UPDATE PASSWORD JIKA DIISI
            |--------------------------------------------------------------------------
            */

            if ($request->filled('password')) {

                $dosen->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | HAPUS ROLE LAMA
            |--------------------------------------------------------------------------
            */

            DB::table('dosen_roles')
                ->where('nim_nid', $nim_nid)
                ->delete();

            /*
            |--------------------------------------------------------------------------
            | INSERT ROLE BARU
            |--------------------------------------------------------------------------
            */

            $roles = [];

            foreach ($request->roles as $role) {

                $roles[] = [
                    'nim_nid'    => $nim_nid,
                    'role_dosen' => $role,
                ];
            }

            DB::table('dosen_roles')->insert($roles);

            DB::commit();

            return redirect()
                ->route('dosen.index')
                ->with('success', 'Data dosen berhasil diupdate');

        } catch (\Exception $e) {

            DB::rollback();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS DOSEN
    |--------------------------------------------------------------------------
    */

    public function destroy($nim_nid)
    {
        DB::beginTransaction();

        try {

            DB::table('dosen_roles')
                ->where('nim_nid', $nim_nid)
                ->delete();

            User::where('nim_nid', $nim_nid)
                ->where('role', 'dosen')
                ->delete();

            DB::commit();

            return redirect()
                ->route('dosen.index')
                ->with('success', 'Data dosen berhasil dihapus');

        } catch (\Exception $e) {

            DB::rollback();

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data');
        }
    }
}