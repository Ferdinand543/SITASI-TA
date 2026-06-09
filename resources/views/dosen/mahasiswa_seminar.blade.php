@extends('layouts.app')

@section('content')

<style>
    .page-title { font-size: 1.5rem; font-weight: 800; color: #735C00; margin-bottom: 4px; }
    .page-subtitle { font-size: 0.85rem; color: #92784A; margin-bottom: 24px; }

    .stat-card {
        background: #FFFDF0;
        border: 1px solid #F0E080;
        border-radius: 16px;
        padding: 20px 24px;
        display: inline-flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-icon {
        width: 48px; height: 48px;
        background: #735C00;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.2rem; flex-shrink: 0;
    }
    .stat-label { font-size: 0.75rem; font-weight: 600; color: #92784A; margin-bottom: 2px; }
    .stat-number { font-size: 2rem; font-weight: 800; color: #735C00; line-height: 1; }
    .stat-desc { font-size: 0.72rem; color: #92784A; margin-top: 2px; }

    .search-wrap {
        position: relative;
        margin-bottom: 24px;
    }
    .search-wrap input {
        width: 100%;
        padding: 12px 16px 12px 42px;
        border: 1.5px solid #EEEEEE;
        border-radius: 12px;
        font-size: 0.85rem;
        color: #1A1C1C;
        background: #FFFFFF;
        outline: none;
        font-family: 'Hanken Grotesk', sans-serif;
        transition: border-color 0.2s;
    }
    .search-wrap input:focus { border-color: #FACC15; }
    .search-wrap i {
        position: absolute; left: 14px; top: 50%;
        transform: translateY(-50%);
        color: #CFC6B2; font-size: 0.9rem;
    }

    .table-card {
        background: #FFFFFF;
        border-radius: 16px;
        border: 1px solid #EEEEEE;
        overflow: hidden;
    }
    .table-card-header {
        padding: 20px 24px 12px;
        border-bottom: 1px solid #EEEEEE;
    }
    .table-card-header h5 { font-size: 1rem; font-weight: 800; color: #4C4637; margin: 0 0 2px; }
    .table-card-header p { font-size: 0.78rem; color: #7E7665; margin: 0; }

    .table-mhs { width: 100%; border-collapse: collapse; }
    .table-mhs thead tr { background: #F3F3F3; }
    .table-mhs thead th {
        padding: 12px 16px;
        font-size: 0.7rem; font-weight: 700;
        color: #7E7665; text-transform: uppercase;
        letter-spacing: 0.8px; border-bottom: 1px solid #EEEEEE;
    }
    .table-mhs tbody tr { border-bottom: 1px solid #EEEEEE; transition: background 0.15s; }
    .table-mhs tbody tr:last-child { border-bottom: none; }
    .table-mhs tbody tr:hover { background: #F3F3F3; }
    .table-mhs tbody td { padding: 16px; font-size: 0.83rem; color: #1A1C1C; vertical-align: top; }

    .nim-text { font-size: 0.82rem; color: #4C4637; }
    .nama-text { font-weight: 700; color: #1A1C1C; }
    .judul-text { color: #4C4637; line-height: 1.5; }
    .pb-label { font-size: 0.68rem; color: #CFC6B2; font-weight: 600; margin-bottom: 1px; }
    .pb-name { font-size: 0.78rem; color: #4C4637; font-weight: 600; margin-bottom: 8px; }
    .pb-name:last-child { margin-bottom: 0; }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #7E7665;
    }
    .empty-state i { font-size: 2.5rem; margin-bottom: 12px; opacity: 0.4; }
    .empty-state p { font-size: 0.88rem; margin: 0; }
</style>

<div class="page-title">Mahasiswa Seminar</div>
<div class="page-subtitle">Daftar mahasiswa yang menjadi tanggung jawab Anda sebagai dosen penguji seminar tugas akhir.</div>

{{-- Stat Card --}}
<div class="stat-card">
    <div class="stat-icon"><i class="fa-solid fa-user-graduate"></i></div>
    <div>
        <div class="stat-label">Total Mahasiswa Ujian</div>
        <div class="stat-number">{{ $totalMahasiswa }}</div>
        <div class="stat-desc">Mahasiswa yang akan Anda uji.</div>
    </div>
</div>

{{-- Search --}}
<div class="search-wrap">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" id="searchInput" placeholder="Cari NIM, nama mahasiswa, atau judul tugas akhir...">
</div>

{{-- Table --}}
<div class="table-card">
    <div class="table-card-header">
        <h5>Daftar Mahasiswa Seminar</h5>
        <p>Menampilkan seluruh mahasiswa yang telah ditetapkan kepada Anda sebagai dosen penguji.</p>
    </div>

    <table class="table-mhs" id="tableMhs">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Judul Tugas Akhir</th>
                <th>Dosen Pembimbing</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse($mahasiswaList as $mhs)
            <tr>
                <td class="nim-text">{{ $mhs->nim_nid }}</td>
                <td class="nama-text">{{ $mhs->nama }}</td>
                <td class="judul-text">{{ $mhs->judul_ta ?? '-' }}</td>
                <td>
                    @if($mhs->pembimbing1)
                        <div class="pb-label">Pembimbing 1:</div>
                        <div class="pb-name">{{ $mhs->pembimbing1 }}</div>
                    @endif
                    @if($mhs->pembimbing2)
                        <div class="pb-label">Pembimbing 2:</div>
                        <div class="pb-name">{{ $mhs->pembimbing2 }}</div>
                    @endif
                    @if(!$mhs->pembimbing1 && !$mhs->pembimbing2)
                        <span style="color:#CFC6B2;font-size:0.78rem;">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">
                    <div class="empty-state">
                        <i class="fa-solid fa-user-graduate"></i>
                        <p>Belum ada mahasiswa seminar yang ditugaskan kepada Anda.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    document.getElementById('searchInput').addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tableBody tr');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(keyword) ? '' : 'none';
        });
    });
</script>

@endsection