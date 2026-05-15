<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
            'nidn' => 'required|unique:users,nidn',
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'status' => 'required'
        ]);

        User::create([
            'nidn' => $request->nidn,
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'dosen',

            // sub role
            'is_pembimbing' => $request->has('is_pembimbing'),
            'is_penguji' => $request->has('is_penguji'),
            'is_reviewer' => $request->has('is_reviewer'),
            'is_koordinator' => $request->has('is_koordinator'),

            'status' => $request->status,

            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('dosen.index')
            ->with('success', 'Data dosen berhasil ditambahkan');
    }
}