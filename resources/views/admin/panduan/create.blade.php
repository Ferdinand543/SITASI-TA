@extends('layouts.app')

@section('content')

<style>
        .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #C9A227;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        margin-bottom: 20px;
    }
</style>
   
    
<div class="container mt-4">

<a href="javascript:history.back()" class="back-link">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
        stroke="currentColor" width="16" height="16">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
    </svg>
    Kembali
</a>

    <div class="card shadow-sm border-0 p-4">

        <h3 class="fw-bold mb-4">
            Tambah Panduan TA
        </h3>

        <form action="{{ route('panduan.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="mb-3">
                <label class="form-label">Judul</label>

                <input type="text"
                       name="judul"
                       class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>

                <textarea name="deskripsi"
                          class="form-control"
                          rows="4"
                          required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>

                <select name="role" class="form-select">

                    <option value="all">
                        Semua
                    </option>

                    <option value="mahasiswa">
                        Mahasiswa
                    </option>

                    <option value="dosen">
                        Dosen
                    </option>

                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Upload File</label>

                <input type="file"
                       name="file"
                       class="form-control"
                       required>
            </div>

            <button type="submit"
                    class="btn btn-warning text-white">

                Simpan
            </button>

        </form>

    </div>

</div>

@endsection