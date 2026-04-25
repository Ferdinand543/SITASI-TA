<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi'
        ]);

        $username = trim($request->username);
        $password = trim($request->password);

        $user = DB::table('users')
            ->whereRaw('TRIM(LOWER(nim_nid)) = ?', [strtolower($username)])
            ->first();

        if (!$user) {
            return back()->with('error', 'Username tidak ditemukan');
        }

        if (!Hash::check($password, $user->password)) {
            return back()->with('error', 'Password salah');
        }

        session(['user' => $user]);

        $role = strtolower(trim($user->role));

        if ($role == 'dosen pembimbing' || $role == 'dosen penguji') {
            return redirect('/dashboard/dosen')->with('success', 'Login berhasil');
        }

        if ($role == 'mahasiswa') {
            return redirect('/mahasiswa')->with('success', 'Login berhasil');
        }

        if ($role == 'admin') {
            return redirect('/admin')->with('success', 'Login berhasil');
        }

        return back()->with('error', 'Role tidak dikenali');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'nim_nid' => 'required',
            'password' => 'required|min:2|same:confirm_password'
        ]);

        $user = DB::table('users')
            ->where('nim_nid', $request->nim_nid)
            ->first();

        if (!$user) {
            return back()->with('error', 'Password Tidak Tersimpan'); // ← DIUBAH
        }

        DB::table('users')
            ->where('nim_nid', $request->nim_nid)
            ->update([
                'password' => Hash::make($request->password)
            ]);

        return back()->with('success', 'Password berhasil diubah');
    }
}