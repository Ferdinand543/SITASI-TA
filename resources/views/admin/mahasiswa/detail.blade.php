@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card border-0 shadow-sm p-4">

        <h3 class="fw-bold mb-4">
            Detail Mahasiswa
        </h3>

        <table class="table">

            <tr>
                <th width="200">NIM</th>
                <td>{{ $mahasiswa->nim_nid }}</td>
            </tr>

            <tr>
                <th>Nama Mahasiswa</th>
                <td>{{ $mahasiswa->nama }}</td>
            </tr>

            <tr>
                <th>Email</th>
                <td>{{ $mahasiswa->email }}</td>
            </tr>

            <tr>
                <th>Angkatan</th>
                <td>{{ $mahasiswa->angkatan }}</td>
            </tr>

        </table>

        <a href="{{ route('mahasiswa.index') }}"
           class="btn btn-secondary">
            Kembali
        </a>

    </div>

</div>

@endsection