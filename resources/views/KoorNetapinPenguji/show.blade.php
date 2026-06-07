@extends('layouts.app')

@section('content')
<style>
    .hero-section {
        background-image: url('{{ asset('images/bg.jpeg') }}');
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        padding: 48px 40px;
        min-height: 180px;
        display: flex;
        align-items: center;
        margin-bottom: 24px;
    }
    .hero-content h2 { color: #735C00; font-size: 1.8rem; font-weight: 800; margin-bottom: 4px; }
    .hero-content p  { color: #735C00; font-size: 0.9rem; margin: 0; }

    .info-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #f0f0f0;
        padding: 20px 28px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 0;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }
    .info-section {
        flex: 1;
        padding: 0 24px;
        border-right: 1px solid #f0f0f0;
    }
    .info-section:first-child { padding-left: 0; }
    .info-section:last-child { border-right: none; }
    .info-section .label { font-size: 0.7rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .info-section .value { font-size: 0.95rem; font-weight: 700; color: #1f2937; }
    .badge-total {
        background: #FEF9C3;
        color: #735C00;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 6px 18px;
        border-radius: 20px;
        display: inline-block;
    }

    .section-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #f0f0f0;
        overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
        margin-bottom: 24px;
    }
    .section-header {
        padding: 18px 24px;
        border-bottom: 1px solid #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .section-header h6 {
        font-weight: 700;
        font-size: 0.95rem;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-header span {
        font-size: 0.78rem;
        color: #9ca3af;
    }

    .custom-table { width: 100%; border-collapse: collapse; }
    .custom-table thead tr { background: #f9fafb; }
    .custom-table thead th {
        padding: 12px 20px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #f0f0f0;
    }
    .custom-table tbody tr { border-bottom: 1px solid #f5f5f5; transition: background 0.15s; }
    .custom-table tbody tr:hover { background: #fffdf5; }
    .custom-table tbody td { padding: 16px 20px; font-size: 0.85rem; color: #374151; vertical-align: top; }

    .dosen-info-cell { font-size: 0.82rem; }
    .dosen-info-cell .role-label { font-size: 0.7rem; font-weight: 700; color: #9ca3af; font-style: italic; margin-bottom: 2px; }
    .dosen-info-cell .nama { font-weight: 600; color: #374151; }
    .dosen-info-cell + .dosen-info-cell { margin-top: 10px; }

    .search-wrap { position: relative; }
    .search-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.85rem; }
    .search-box {
        border-radius: 12px;
        border: 1.5px solid #e5e7eb;
        padding: 10px 16px 10px 40px;
        font-size: 0.88rem;
        width: 100%;
        outline: none;
        font-family: 'Hanken Grotesk', sans-serif;
        color: #374151;
        transition: border 0.2s;
        background: #f9fafb;
    }
    .search-box:focus { border-color: #FACC15; background: #fff; }

    .btn-back {
        padding: 8px 18px;
        border-radius: 10px;
        border: 1.5px solid #e5e7eb;
        background: #fff;
        font-size: 0.82rem;
        font-weight: 600;
        color: #735C00;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 20px;
        transition: 0.2s;
    }
    .btn-back:hover { background: #FACC15; border-color: #FACC15; color: #735C00; }

    .btn-tambah {
        padding: 11px 28px;
        border-radius: 50px;
        background: #FACC15;
        border: none;
        font-size: 0.85rem;
        font-weight: 700;
        color: #735C00;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.2s;
    }
    .btn-tambah:hover { background: #eab308; }

    .btn-batal {
        padding: 11px 28px;
        border-radius: 50px;
        border: 1.5px solid #374151;
        background: #fff;
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: 0.2s;
    }
    .btn-batal:hover { background: #f9fafb; }

    .checkbox-mhs { width: 16px; height: 16px; accent-color: #FACC15; cursor: pointer; border-radius: 4px; }

    .empty-state { text-align: center; padding: 40px 20px; color: #9ca3af; }
    .empty-state i { font-size: 2rem; margin-bottom: 10px; display: block; }

    .alert-success {
        background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;
        padding: 12px 18px; border-radius: 10px; margin-bottom: 16px; font-size: 0.85rem;
    }
    .alert-error {
        background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;
        padding: 12px 18px; border-radius: 10px; margin-bottom: 16px; font-size: 0.85rem;
    }
</style>

{{-- HERO --}}
<div class="hero-section">
    <div class="hero-content">
        <h2>Kelola Penetapan Dosen Penguji</h2>
        <p>Mengelola mahasiswa peserta ujian tugas akhir yang ditugaskan kepada dosen penguji.</p>
    </div>
</div>

<a href="{{ route('dosen.penguji.index') }}" class="btn-back">
    <i class="fa-solid fa-arrow-left"></i> Kembali
</a>

@if(session('success'))
<div class="alert-success"><i class="fa-solid fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert-error"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</div>
@endif

{{-- INFO DOSEN --}}
<div class="info-card">
    <div class="info-section">
        <div class="label">Dosen Penguji</div>
        <div class="value">{{ $dosen->nama }}</div>
    </div>
    <div class="info-section">
        <div class="label">NIDN</div>
        <div class="value">{{ $dosen->nim_nid }}</div>
    </div>
    <div class="info-section">
        <div class="label">Total Mahasiswa Direview</div>
        <span class="badge-total">{{ $mahasiswaSudahDitetapkan->count() }} Mahasiswa</span>
    </div>
</div>

{{-- TABEL MAHASISWA SUDAH DITETAPKAN --}}
<div class="section-card">
    <div class="section-header">
        <h6><i class="fa-solid fa-calendar-days" style="color:#FACC15;"></i> Daftar Mahasiswa Seminar</h6>
        <span>{{ $mahasiswaSudahDitetapkan->count() }} mahasiswa</span>
    </div>

    <div style="padding:16px 24px;border-bottom:1px solid #f5f5f5;">
        <div class="search-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="searchSudah" class="search-box" placeholder="Cari NIM atau nama...">
        </div>
    </div>

    <table class="custom-table">
        <thead>
            <tr>
                <th>NIM</th>
                <th>MAHASISWA</th>
                <th>JUDUL</th>
                <th>DOSEN PEMBIMBING</th>
                <th>DOSEN PENGUJI</th>
            </tr>
        </thead>
        <tbody id="tableSudah">
            @forelse($mahasiswaSudahDitetapkan as $mhs)
            <tr>
                <td style="color:#9ca3af;font-size:0.82rem;">{{ $mhs->nim_nid }}</td>
                <td style="font-weight:600;">{{ $mhs->nama }}</td>
                <td style="max-width:200px;font-size:0.82rem;">{{ $mhs->judul_ta }}</td>
                <td>
                    @if($mhs->pembimbing1)
                    <div class="dosen-info-cell">
                        <div class="role-label">Pembimbing 1</div>
                        <div class="nama">{{ $mhs->pembimbing1 }}</div>
                    </div>
                    @endif
                    @if($mhs->pembimbing2)
                    <div class="dosen-info-cell" style="margin-top:8px;">
                        <div class="role-label">Pembimbing 2</div>
                        <div class="nama">{{ $mhs->pembimbing2 }}</div>
                    </div>
                    @endif
                    @if(!$mhs->pembimbing1 && !$mhs->pembimbing2)
                        <span style="color:#9ca3af;font-size:0.78rem;">-</span>
                    @endif
                </td>
                <td>
                    @if($mhs->penguji1)
                    <div class="dosen-info-cell">
                        <div class="role-label">Penguji 1</div>
                        <div class="nama">{{ $mhs->penguji1 }}</div>
                    </div>
                    @endif
                    @if($mhs->penguji2)
                    <div class="dosen-info-cell" style="margin-top:8px;">
                        <div class="role-label">Penguji 2</div>
                        <div class="nama">{{ $mhs->penguji2 }}</div>
                    </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="empty-state">
                        <i class="fa-solid fa-inbox"></i>
                        <p>Belum ada mahasiswa yang ditetapkan</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- TABEL MAHASISWA BELUM DITETAPKAN --}}
<form method="POST" action="{{ route('penguji.tetapkan', $dosen->nim_nid) }}">
    @csrf
    <div class="section-card">
        <div class="section-header" style="padding:20px 24px;">
            <h6><i class="fa-solid fa-user-graduate" style="color:#735C00;"></i> Daftar Mahasiswa</h6>
            <div class="search-wrap" style="width:320px;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchBelum" class="search-box" placeholder="Cari mahasiswa...">
            </div>
        </div>

        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width:48px;"></th>
                    <th>NIM</th>
                    <th>NAMA MAHASISWA</th>
                    <th>JUDUL TUGAS AKHIR</th>
                </tr>
            </thead>
            <tbody id="tableBelum">
                @forelse($mahasiswaBelumDitetapkan as $mhs)
                <tr>
                    <td style="text-align:center;">
                        <input type="checkbox" name="pengajuan_ids[]" value="{{ $mhs->pengajuan_id }}" class="checkbox-mhs cb-mhs">
                    </td>
                    <td style="color:#9ca3af;font-size:0.82rem;">{{ $mhs->nim_nid }}</td>
                    <td style="font-weight:600;">{{ $mhs->nama }}</td>
                    <td style="font-size:0.82rem;">{{ Str::limit($mhs->judul_ta, 50) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <i class="fa-solid fa-circle-check" style="color:#22c55e;"></i>
                            <p>Semua mahasiswa sudah ditetapkan pengujinya</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($mahasiswaBelumDitetapkan->count() > 0)
        <div style="padding:20px 24px;display:flex;justify-content:space-between;align-items:center;border-top:1px solid #f5f5f5;">
            <a href="{{ route('dosen.penguji.index') }}" class="btn-batal">Batal</a>
            <button type="submit" class="btn-tambah">
                <i class="fa-solid fa-user-plus"></i> Tambahkan Mahasiswa
            </button>
        </div>
        @endif
    </div>
</form>

<script>
    document.getElementById('checkAll').addEventListener('change', function () {
        document.querySelectorAll('.cb-mhs').forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('searchSudah').addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        document.querySelectorAll('#tableSudah tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(keyword) ? '' : 'none';
        });
    });

    document.getElementById('searchBelum').addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        document.querySelectorAll('#tableBelum tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(keyword) ? '' : 'none';
        });
    });
</script>

@endsection