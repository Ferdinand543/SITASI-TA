@extends('layouts.app')

@section('content')

{{-- LOADING OVERLAY --}}
<div id="loadingOverlay" style="
    position:fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(255,255,255,0.9); z-index:9999;
    display:flex; flex-direction:column;
    align-items:center; justify-content:center;">
    <div class="spinner-border mb-3" role="status"
        style="width:3rem;height:3rem;color:#FACC15;border-width:3px;">
        <span class="visually-hidden">Loading...</span>
    </div>
    <p style="color:#6b7280;font-weight:600;font-family:'Hanken Grotesk',sans-serif;">
        Sedang memuat data...
    </p>
</div>

{{-- HERO --}}
<div style="
    background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 55%, #FDE68A 100%);
    border-radius: 20px;
    padding: 40px 36px;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
    min-height: 145px;
    display: flex;
    align-items: center;
">
    <div style="position:absolute;right:-20px;top:-40px;width:230px;height:230px;
                background:rgba(250,204,21,0.15);border-radius:50%;pointer-events:none;"></div>
    <div style="position:absolute;right:90px;bottom:-50px;width:150px;height:150px;
                background:rgba(250,204,21,0.10);border-radius:50%;pointer-events:none;"></div>

    <div style="position:relative;z-index:2;">
        <h2 style="font-size:1.75rem;font-weight:800;color:#735C00;margin-bottom:6px;">
            Penilaian Seminar TA-1
        </h2>
        <p style="color:#92741A;font-size:0.88rem;margin:0;">
            Kelola dan lakukan penilaian seminar tugas akhir mahasiswa bimbingan Anda.
        </p>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    @php
    $stats = [
        ['label'=>'Total Mahasiswa', 'val'=>$total,        'icon'=>'fa-users',         'color'=>'#735C00','bg'=>'#FFFBEB'],
        ['label'=>'Belum Dinilai',   'val'=>$belumDinilai, 'icon'=>'fa-calendar-xmark','color'=>'#dc2626','bg'=>'#FEF2F2'],
        ['label'=>'Draft Penilaian', 'val'=>$draft,        'icon'=>'fa-file-pen',       'color'=>'#92741A','bg'=>'#FEFCE8'],
        ['label'=>'Sudah Dinilai',   'val'=>$sudahDinilai, 'icon'=>'fa-circle-check',   'color'=>'#16a34a','bg'=>'#F0FDF4'],
    ];
    @endphp

    @foreach($stats as $s)
    <div class="col-6 col-md-3">
        <div style="
            background:#fff; border-radius:14px;
            padding:20px 18px; border:1px solid #f0f0f0;
            box-shadow:0 1px 4px rgba(0,0,0,0.05);
            display:flex; align-items:center; gap:14px;">
            <div style="
                width:46px; height:46px;
                background:{{ $s['bg'] }}; border-radius:12px;
                display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="fa {{ $s['icon'] }}" style="font-size:1.1rem;color:{{ $s['color'] }};"></i>
            </div>
            <div>
                <div style="font-size:1.7rem;font-weight:800;color:{{ $s['color'] }};line-height:1;">
                    {{ $s['val'] }}
                </div>
                <div style="font-size:0.71rem;color:#6b7280;font-weight:500;margin-top:2px;">
                    {{ $s['label'] }}
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- FILTER & SEARCH --}}
<div style="
    background:#fff; border-radius:14px;
    padding:18px 20px; margin-bottom:20px;
    border:1px solid #f0f0f0;
    box-shadow:0 1px 4px rgba(0,0,0,0.04);">

    <div class="row g-2 align-items-end">
        <div class="col-md-7">
            <div style="position:relative;">
                <i class="fa fa-search" style="
                    position:absolute; left:14px; top:50%;
                    transform:translateY(-50%);
                    color:#9ca3af; font-size:0.85rem;"></i>
                <input type="text" id="searchInput"
                    placeholder="Cari mahasiswa atau judul tugas akhir..."
                    style="
                        width:100%; padding:10px 14px 10px 38px;
                        border:1px solid #E5E7EB; border-radius:10px;
                        font-size:0.85rem; font-family:'Hanken Grotesk',sans-serif;
                        outline:none; color:#374151;">
            </div>
        </div>

        <div class="col-md-3">
            <div style="display:flex;flex-direction:column;gap:3px;">
                <label style="font-size:0.63rem;font-weight:700;color:#9ca3af;
                              text-transform:uppercase;letter-spacing:1px;">STATUS</label>
                <select id="filterStatus" style="
                    padding:10px 12px; border:1px solid #E5E7EB;
                    border-radius:10px; font-size:0.85rem;
                    font-family:'Hanken Grotesk',sans-serif;
                    outline:none; background:#fff; color:#374151;">
                    <option value="">Semua Status</option>
                    <option value="belum">Belum Dinilai</option>
                    <option value="draft">Draft Penilaian</option>
                    <option value="submitted">Sudah Dinilai</option>
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <button onclick="resetFilter()" style="
                width:100%; padding:10px;
                border:1px solid #E5E7EB; border-radius:10px;
                background:#fff; font-size:0.83rem;
                font-weight:600; color:#6b7280; cursor:pointer;
                display:flex; align-items:center;
                justify-content:center; gap:6px;
                font-family:'Hanken Grotesk',sans-serif;">
                <i class="fa fa-rotate-right"></i> Reset Filter
            </button>
        </div>
    </div>
</div>

{{-- TABEL --}}
<div style="
    background:#fff; border-radius:14px;
    border:1px solid #f0f0f0;
    box-shadow:0 1px 4px rgba(0,0,0,0.04);
    overflow:hidden;">

    <table style="width:100%;border-collapse:collapse;" id="tabelPenilaian">
        <thead>
            <tr style="background:#FFFBEB;border-bottom:2px solid #FDE68A;">
                <th style="padding:14px 20px;font-size:0.7rem;font-weight:700;
                           color:#92741A;text-transform:uppercase;letter-spacing:1px;text-align:left;">NIM</th>
                <th style="padding:14px 20px;font-size:0.7rem;font-weight:700;
                           color:#92741A;text-transform:uppercase;letter-spacing:1px;text-align:left;">Nama Mahasiswa</th>
                <th style="padding:14px 20px;font-size:0.7rem;font-weight:700;
                           color:#92741A;text-transform:uppercase;letter-spacing:1px;text-align:left;">Status Penilaian</th>
                <th style="padding:14px 20px;font-size:0.7rem;font-weight:700;
                           color:#92741A;text-transform:uppercase;letter-spacing:1px;text-align:center;">Aksi</th>
            </tr>
        </thead>

        <tbody id="tabelBody">
            @forelse($proposals as $p)
            <tr class="tabel-row"
                data-nama="{{ strtolower($p->nama) }}"
                data-judul="{{ strtolower($p->judul_ta ?? '') }}"
                data-status="{{ $p->status_penilaian ?? 'belum' }}"
                style="border-bottom:1px solid #F9FAFB;transition:background 0.15s;">

                <td style="padding:16px 20px;font-size:0.85rem;font-weight:600;color:#374151;">
                    {{ $p->nim_nid }}
                </td>

                <td style="padding:16px 20px;">
                    <div style="font-size:0.9rem;font-weight:700;color:#111827;">
                        {{ $p->nama }}
                    </div>
                    @if($p->judul_ta)
                    <div style="font-size:0.75rem;color:#9ca3af;margin-top:2px;">
                        {{ \Illuminate\Support\Str::limit($p->judul_ta, 58) }}
                    </div>
                    @endif
                </td>

                <td style="padding:16px 20px;">
                    @if(!$p->status_penilaian)
                        <span style="background:#FEF2F2;color:#dc2626;padding:5px 14px;border-radius:20px;font-size:0.78rem;font-weight:600;">
                            Belum Dinilai
                        </span>
                    @elseif($p->status_penilaian === 'draft')
                        <span style="background:#FEFCE8;color:#92741A;padding:5px 14px;border-radius:20px;font-size:0.78rem;font-weight:600;">
                            Draft Penilaian
                        </span>
                    @else
                        <span style="background:#F0FDF4;color:#16a34a;padding:5px 14px;border-radius:20px;font-size:0.78rem;font-weight:600;">
                            Sudah Dinilai
                        </span>
                    @endif
                </td>

                <td style="padding:16px 20px;text-align:center;">
                    @if(!$p->status_penilaian)
                        <a href="{{ route('penilaian.pembimbing.form', $p->proposal_id) }}"
                            style="display:inline-flex;align-items:center;gap:6px;
                                padding:8px 18px;background:#dc2626;color:#fff;
                                border-radius:10px;font-size:0.82rem;font-weight:700;
                                text-decoration:none;">
                            <i class="fa fa-pen-to-square"></i> Mulai Penilaian
                        </a>
                    @elseif($p->status_penilaian === 'draft')
                        <a href="{{ route('penilaian.pembimbing.form', $p->proposal_id) }}"
                            style="display:inline-flex;align-items:center;gap:6px;
                                padding:8px 18px;background:#92741A;color:#fff;
                                border-radius:10px;font-size:0.82rem;font-weight:700;
                                text-decoration:none;">
                            <i class="fa fa-pencil"></i> Lanjutkan Penilaian
                        </a>
                    @else
                        <a href="{{ route('penilaian.pembimbing.show', $p->proposal_id) }}"
                            style="display:inline-flex;align-items:center;gap:6px;
                                padding:8px 18px;background:#fff;color:#374151;
                                border:1px solid #D1D5DB;border-radius:10px;
                                font-size:0.82rem;font-weight:700;text-decoration:none;">
                            <i class="fa fa-eye"></i> Lihat Penilaian
                        </a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding:56px;text-align:center;color:#9ca3af;">
                    <i class="fa fa-inbox" style="font-size:2.2rem;margin-bottom:12px;display:block;color:#D1D5DB;"></i>
                    <div style="font-weight:600;font-size:0.9rem;">Belum ada mahasiswa yang perlu dinilai.</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div id="emptyFilter" style="display:none;padding:56px;text-align:center;color:#9ca3af;">
        <i class="fa fa-filter" style="font-size:2.2rem;margin-bottom:12px;display:block;color:#D1D5DB;"></i>
        <div style="font-weight:600;font-size:0.9rem;">Tidak ada hasil yang cocok dengan filter.</div>
    </div>
</div>

<script>
    const searchInput  = document.getElementById('searchInput');
    const filterStatus = document.getElementById('filterStatus');

    function filterTable() {
        const q      = searchInput.value.toLowerCase().trim();
        const status = filterStatus.value;
        const rows   = document.querySelectorAll('.tabel-row');
        let visible  = 0;

        rows.forEach(row => {
            const nama  = row.dataset.nama  || '';
            const judul = row.dataset.judul || '';
            const st    = row.dataset.status || 'belum';
            const show  = (!q || nama.includes(q) || judul.includes(q)) && (!status || st === status);
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        document.getElementById('emptyFilter').style.display =
            visible === 0 && document.querySelectorAll('.tabel-row').length > 0 ? 'block' : 'none';
    }

    function resetFilter() {
        searchInput.value  = '';
        filterStatus.value = '';
        filterTable();
    }

    searchInput.addEventListener('input', filterTable);
    filterStatus.addEventListener('change', filterTable);

    document.querySelectorAll('.tabel-row').forEach(row => {
        row.addEventListener('mouseenter', () => row.style.background = '#FFFBEB');
        row.addEventListener('mouseleave', () => row.style.background = '');
    });

    window.addEventListener('load', () => {
        document.getElementById('loadingOverlay').style.display = 'none';
    });
</script>

@endsection