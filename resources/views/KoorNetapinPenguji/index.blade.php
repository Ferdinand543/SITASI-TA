@extends('layouts.app')

@section('content')

<style>
    .hero-section {
        background-image: url('{{ asset('images/bg.jpeg') }}');
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        padding: 48px 40px;
        position: relative;
        overflow: hidden;
        min-height: 180px;
        display: flex;
        align-items: center;
        margin-bottom: 24px;
    }
    .hero-content h2 {
        color: #735C00;
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 4px;
    }
    .hero-content p {
        color: #735C00;
        font-size: 0.9rem;
        margin: 0;
    }
    .tab-btn {
        padding: 8px 20px;
        border-radius: 10px;
        border: 1.5px solid #e5e7eb;
        background: #fff;
        font-size: 0.82rem;
        font-weight: 600;
        color: #735C00;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: 0.2s;
        text-decoration: none;
    }
    .tab-btn.active, .tab-btn:hover {
        background: #FACC15;
        border-color: #FACC15;
        color: #735C00;
    }
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
    }
    .search-box:focus { border-color: #FACC15; }
    .search-wrap { position: relative; }
    .search-wrap i {
        position: absolute;
        left: 14px; top: 50%;
        transform: translateY(-50%);
        color: #9ca3af; font-size: 0.85rem;
    }
    .table-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #f0f0f0;
        overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }
    .table-header-bar {
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f5f5f5;
    }
    .table-header-bar h6 {
        font-weight: 700;
        font-size: 0.95rem;
        color: #374151;
        margin: 0;
    }
    .badge-total {
        background: #f3f4f6;
        color: #6b7280;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .custom-table { width: 100%; border-collapse: collapse; }
    .custom-table thead tr {
        background: #f9fafb;
        border-bottom: 1px solid #f0f0f0;
    }
    .custom-table thead th {
        padding: 12px 20px;
        font-size: 0.75rem;
        font-weight: 700;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .custom-table tbody tr {
        border-bottom: 1px solid #f9fafb;
        transition: background 0.15s;
    }
    .custom-table tbody tr:hover { background: #fffdf5; }
    .custom-table tbody td {
        padding: 14px 20px;
        font-size: 0.85rem;
        color: #374151;
    }
    .btn-lihat {
        padding: 6px 16px;
        border-radius: 8px;
        border: 1.5px solid #e5e7eb;
        background: #fff;
        font-size: 0.78rem;
        font-weight: 600;
        color: #735C00;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: 0.2s;
    }
    .btn-lihat:hover {
        background: #FACC15;
        border-color: #FACC15;
        color: #735C00;
    }
    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: #9ca3af;
    }
    .empty-state i { font-size: 2.5rem; margin-bottom: 12px; display: block; }
</style>

{{-- HERO --}}
<div class="hero-section">
    <div class="hero-content">
        <h2>Penetapan Dosen Penguji</h2>
        <p>Kelola dan lihat daftar dosen penguji</p>
    </div>
</div>

{{-- TAB --}}
<div style="display:flex;gap:10px;margin-bottom:20px;">
    <a href="{{ route('dosen.penguji.index') }}" class="tab-btn active">
        <i class="fa-solid fa-user-tie"></i> Dosen Penguji
    </a>
    <a href="{{ route('penguji.mahasiswa.index') }}" class="tab-btn">
        <i class="fa-solid fa-user-graduate"></i> Mahasiswa
    </a>
</div>

{{-- SEARCH --}}
<div style="margin-bottom:20px;">
    <p style="font-size:0.82rem;font-weight:600;color:#6b7280;margin-bottom:8px;">Pencarian Dosen</p>
    <div class="search-wrap">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="searchInput" class="search-box" placeholder="Cari nama dosen atau NIDN...">
    </div>
</div>

{{-- TABEL --}}
<div class="table-card">
    <div class="table-header-bar">
        <h6>Daftar Dosen Penguji</h6>
        <span class="badge-total">Total Dosen Penguji {{ $dosenPenguji->count() }}</span>
    </div>
    <table class="custom-table">
        <thead>
            <tr>
                <th>NO.</th>
                <th>NIDN</th>
                <th>DOSEN PENGUJI</th>
                <th>JUMLAH MAHASISWA</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse($dosenPenguji as $index => $dosen)
            <tr>
                <td>{{ $index + 1 }}.</td>
                <td style="color:#9ca3af;">{{ $dosen->nim_nid }}</td>
                <td style="font-weight:600;">{{ $dosen->nama }}</td>
                <td>{{ $dosen->jumlah_mahasiswa }}</td>
                <td>
                    <a href="{{ route('penguji.show', $dosen->nim_nid) }}" class="btn-lihat">
                        Lihat Mahasiswa <i class="fa-solid fa-chevron-right" style="font-size:0.7rem;"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="empty-state">
                        <i class="fa-solid fa-user-tie"></i>
                        <p>Belum ada dosen penguji</p>
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