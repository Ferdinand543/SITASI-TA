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
        $dosen = User::where('role', 'dosen')->get();

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
            'nidn' => 'required|unique:users,nim_nid',
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // simpan user dosen
        User::create([
            'nim_nid' => $request->nidn,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dosen',
            'foto' => '',
        ]);

        // simpan sub role dosen
        if ($request->has('is_pembimbing')) {
            DB::table('dosen_roles')->insert([
                'nim_nid' => $request->nidn,
                'role_dosen' => 'pembimbing'
            ]);
        }

        if ($request->has('is_penguji')) {
            DB::table('dosen_roles')->insert([
                'nim_nid' => $request->nidn,
                'role_dosen' => 'penguji'
            ]);
        }

        if ($request->has('is_reviewer')) {
            DB::table('dosen_roles')->insert([
                'nim_nid' => $request->nidn,
                'role_dosen' => 'reviewer'
            ]);
        }

        if ($request->has('is_koordinator')) {
            DB::table('dosen_roles')->insert([
                'nim_nid' => $request->nidn,
                'role_dosen' => 'koordinator'
            ]);
        }

        return redirect()
            ->route('dosen.index')
            ->with('success', 'Data dosen berhasil ditambahkan');
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS DOSEN
    |--------------------------------------------------------------------------
    */

    public function destroy($nim_nid)
    {
        DB::table('dosen_roles')
            ->where('nim_nid', $nim_nid)
            ->delete();

        User::where('nim_nid', $nim_nid)
            ->delete();

        return redirect()
            ->route('dosen.index')
            ->with('success', 'Data dosen berhasil dihapus');
    }

    //detail
    public function show($nim_nid)
    {
        $dosen = User::where('nim_nid', $nim_nid)
            ->where('role', 'dosen')
            ->firstOrFail();

        $roles = \DB::table('dosen_roles')
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
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,' . $nim_nid . ',nim_nid',
        ]);

        $dosen = User::where('nim_nid', $nim_nid)
            ->where('role', 'dosen')
            ->firstOrFail();

        // update user
        $dosen->update([
            'nama' => $request->nama,
            'email' => $request->email,
        ]);

        // update password kalau diisi
        if ($request->password) {
            $dosen->update([
                'password' => Hash::make($request->password)
            ]);
        }

        // hapus role lama
        DB::table('dosen_roles')
            ->where('nim_nid', $nim_nid)
            ->delete();

        // insert role baru
        if ($request->has('is_pembimbing')) {
            DB::table('dosen_roles')->insert([
                'nim_nid' => $nim_nid,
                'role_dosen' => 'pembimbing'
            ]);
        }

        if ($request->has('is_penguji')) {
            DB::table('dosen_roles')->insert([
                'nim_nid' => $nim_nid,
                'role_dosen' => 'penguji'
            ]);
        }

        if ($request->has('is_reviewer')) {
            DB::table('dosen_roles')->insert([
                'nim_nid' => $nim_nid,
                'role_dosen' => 'reviewer'
            ]);
        }

        if ($request->has('is_koordinator')) {
            DB::table('dosen_roles')->insert([
                'nim_nid' => $nim_nid,
                'role_dosen' => 'koordinator'
            ]);
        }

        return redirect()
            ->route('dosen.index')
            ->with('success', 'Data dosen berhasil diupdate');
    }
}