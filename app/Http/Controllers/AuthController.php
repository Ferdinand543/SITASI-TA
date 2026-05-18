<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

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

        if ($role == 'dosen') {
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

    // =====================================================
    // GANTI PASSWORD (dari halaman forgotpass — pakai password lama)
    // =====================================================
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
    // FORGOT PASSWORD — KIRIM EMAIL
    // =====================================================
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email'], [
            'email.required' => 'Email wajib diisi',
            'email.email'    => 'Format email tidak valid',
        ]);

        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan');
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        Mail::send(
            'emails.reset_password',
            ['token' => $token, 'user' => $user],
            function ($mail) use ($request) {
                $mail->to($request->email)
                     ->subject('Reset Password - SITASI-TA');
            }
        );

        return back()->with('success', 'Link reset password sudah dikirim ke email lo!');
    }

    // =====================================================
    // FORGOT PASSWORD — TAMPILKAN FORM RESET BARU
    // =====================================================
    public function showResetForm($token)
    {
        $record = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();

        if (!$record) {
            return redirect('/forgot-password')->with('error', 'Token tidak valid atau sudah kadaluarsa');
        }

        return view('auth.reset_password', ['token' => $token, 'email' => $record->email]);
    }

    // =====================================================
    // FORGOT PASSWORD — SIMPAN PASSWORD BARU (via email token)
    // =====================================================
    public function doResetPassword(Request $request)
    {
        $request->validate([
            'token'            => 'required',
            'email'            => 'required|email',
            'password'         => 'required|min:6',
            'confirm_password' => 'required',
        ]);

        if ($request->password !== $request->confirm_password) {
            return back()->with('error', 'Password baru dan konfirmasi tidak sama');
        }

        $record = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return back()->with('error', 'Token tidak valid atau sudah kadaluarsa');
        }

        DB::table('users')
            ->where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/reset-success');
    }

    // =====================================================
    // PROFIL ADMIN
    // =====================================================
    public function profilAdmin()
    {
        if (!session('user')) return redirect('/login');

        $user = session('user');

        if (strtolower(trim($user->role)) !== 'admin') {
            return redirect('/login')->with('error', 'Akses ditolak');
        }

        $user = DB::table('users')->where('nim_nid', $user->nim_nid)->first();

        return view('dosen.profil_admin_tu', compact('user'));
    }

    // =====================================================
    // PROFIL MAHASISWA
    // =====================================================
    public function profilMahasiswa()
    {
        if (!session('user')) return redirect('/login');

        $user = session('user');

        if (strtolower(trim($user->role)) !== 'mahasiswa') {
            return redirect('/login')->with('error', 'Akses ditolak');
        }

        $user = DB::table('users')->where('nim_nid', $user->nim_nid)->first();

        return view('mahasiswa.profil_mahasiswa_tu', compact('user'));
    }

    // =====================================================
    // UPLOAD FOTO MAHASISWA
    // =====================================================
    public function uploadFotoMahasiswa(Request $request)
    {
        if (!session('user')) return redirect('/login');

        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'foto.required' => 'Pilih foto terlebih dahulu',
            'foto.image'    => 'File harus berupa gambar',
            'foto.mimes'    => 'Format foto harus JPG atau PNG',
            'foto.max'      => 'Ukuran foto maksimal 2MB',
        ]);

        $nim      = session('user')->nim_nid;
        $file     = $request->file('foto');
        $namaFile = 'foto_' . $nim . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs('foto_profil', $namaFile, 'public');

        DB::table('users')
            ->where('nim_nid', $nim)
            ->update(['foto' => $path]);

        $userBaru = DB::table('users')->where('nim_nid', $nim)->first();
        session(['user' => $userBaru]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    // =====================================================
    // PROFIL DOSEN
    // =====================================================
    public function profilDosen()
    {
        if (!session('user')) return redirect('/login');

        $user = session('user');

        if (strtolower(trim($user->role)) !== 'dosen') {
            return redirect('/login')->with('error', 'Akses ditolak');
        }

        $user = DB::table('users')->where('nim_nid', $user->nim_nid)->first();

        $rolesRaw = DB::table('dosen_roles')
            ->where('nim_nid', $user->nim_nid)
            ->pluck('role_dosen')
            ->toArray();

        $roleLabels = [
            'reviewer'    => 'Reviewer',
            'pembimbing'  => 'Dosen Pembimbing',
            'penguji'     => 'Dosen Penguji',
            'koordinator' => 'Koordinator',
        ];

        $roles = array_map(fn($r) => $roleLabels[$r] ?? ucfirst($r), $rolesRaw);

        return view('dosen.profil_dosen_tu', compact('user', 'roles'));
    }

    // =====================================================
    // UPLOAD FOTO DOSEN
    // =====================================================
    public function uploadFotoDosen(Request $request)
    {
        if (!session('user')) return redirect('/login');

        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'foto.required' => 'Pilih foto terlebih dahulu',
            'foto.image'    => 'File harus berupa gambar',
            'foto.mimes'    => 'Format foto harus JPG atau PNG',
            'foto.max'      => 'Ukuran foto maksimal 2MB',
        ]);

        $nid      = session('user')->nim_nid;
        $file     = $request->file('foto');
        $namaFile = 'foto_' . $nid . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs('foto_profil', $namaFile, 'public');

        DB::table('users')
            ->where('nim_nid', $nid)
            ->update(['foto' => $path]);

        $userBaru = DB::table('users')->where('nim_nid', $nid)->first();
        session(['user' => $userBaru]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}