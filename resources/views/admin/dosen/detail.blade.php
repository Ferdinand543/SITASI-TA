@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card border-0 shadow-sm p-4">

        <h3 class="fw-bold mb-4">
            Detail Dosen
        </h3>

        <table class="table">

            <tr>
                <th width="200">NIDN</th>
                <td>{{ $dosen->nim_nid }}</td>
            </tr>

            <tr>
                <th>Nama Dosen</th>
                <td>{{ $dosen->nama }}</td>
            </tr>

            <tr>
                <th>Email</th>
                <td>{{ $dosen->email }}</td>
            </tr>

            <tr>
                <th>Role Dosen</th>
                <td>

                    @foreach($roles as $role)

                        <span class="badge bg-primary">
                            {{ ucfirst($role) }}
                        </span>

                    @endforeach

                </td>
            </tr>

        </table>

        <a href="{{ route('dosen.index') }}"
           class="btn btn-secondary">
            Kembali
        </a>

    </div>

</div>

@endsection