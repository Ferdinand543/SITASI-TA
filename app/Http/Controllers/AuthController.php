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

        // Cek apakah password di DB sudah bcrypt atau masih plain text
        $isHashed = str_starts_with($user->password, '$2y$');

        if ($isHashed) {
            if (!Hash::check($password, $user->password)) {
                return back()->with('error', 'Password salah');
            }
        } else {
            if ($password !== $user->password) {
                return back()->with('error', 'Password salah');
            }
        }

        session(['user' => $user]);

        $role = strtolower(trim($user->role));

        if ($role == 'dosen pembimbing' || $role == 'dosen penguji' || $role == 'dosen reviewer' || $role == 'koordinator') {
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
            'nim_nid'          => 'required',
            'old_password'     => 'required',
            'password'         => 'required|min:6',
            'confirm_password' => 'required'
        ]);

        $user = DB::table('users')
            ->where('nim_nid', $request->nim_nid)
            ->first();

        if (!$user) {
            return back()->with('error', 'Username tidak ditemukan');
        }

        $isHashed = str_starts_with($user->password, '$2y$');

        if ($isHashed) {
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->with('error', 'Password lama tidak sesuai');
            }
        } else {
            if ($request->old_password !== $user->password) {
                return back()->with('error', 'Password lama tidak sesuai');
            }
        }

        if ($request->password !== $request->confirm_password) {
            return back()->with('error', 'Password baru dan konfirmasi tidak sama');
        }

        DB::table('users')
            ->where('nim_nid', $request->nim_nid)
            ->update([
                'password' => Hash::make($request->password)
            ]);

        return back()->with('success', 'Password berhasil diubah');
    }

    // =====================================================
    // PROFIL ADMIN
    // =====================================================
    public function profilAdmin()
    {
        // Belum login → tendang ke login
        if (!session('user')) return redirect('/login');

        $user = session('user');

        // Bukan admin → tendang ke login, ga boleh akses
        if (strtolower(trim($user->role)) !== 'admin') {
            return redirect('/login')->with('error', 'Akses ditolak');
        }

        // Ambil data fresh dari DB biar selalu up to date
        $user = DB::table('users')->where('nim_nid', $user->nim_nid)->first();

        return view('dosen.profil_admin_tu', compact('user'));
    }
}