<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register-admin');
    }

    public function register(Request $request)
    {
        // VALIDASI
        $request->validate([
            'username' => 'required|unique:users,username',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);

        // SIMPAN DATA
        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'role' => 'Admin',
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Registrasi berhasil!');
    }
}