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
            ->whereRaw('TRIM(LOWER(name)) = ?', [strtolower($username)])
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
            'name' => 'required',
            'password' => 'required|min:2|same:confirm_password'
        ]);

        $user = DB::table('users')
            ->where('name', $request->name)
            ->first();

        if (!$user) {
            return back()->with('error', 'Password Tidak Tersimpan'); // ← DIUBAH
        }

        DB::table('users')
            ->where('name', $request->name)
            ->update([
                'password' => Hash::make($request->password)
            ]);

        return back()->with('success', 'Password berhasil diubah');
    }

    public function showRegister()
    {
        return view('auth.register-admin');
    }

    public function register(Request $request)
{
    // VALIDASI
    $request->validate([
        'username' => 'required|unique:users,name',
        'email' => 'required|email',
        'password' => 'required|confirmed'
    ]);

    // SIMPAN DATA
    DB::table('users')->insert([
        'name' => $request->username, // sesuaikan dengan kolom di DB kamu
        'email' => $request->email,
        'role' => 'admin',
        'password' => Hash::make($request->password)
    ]);

    return redirect('/login')->with('success', 'Registrasi berhasil, silakan login');
}
}