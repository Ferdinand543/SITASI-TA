@extends('layouts.app')

@section('title', 'Tetapkan Jadwal Seminar Mahasiswa')

@section('content')

<style>
    :root {
        --gold: #C9A227;
        --gold-lt: #FEF9EC;
        --gold-border: #F5D97A;
        --neutral: #1E293B;
        --muted: #6B7280;
        --border: #E5E7EB;
        --white: #ffffff;
        --bg: #F5F6FA;
        --radius: 16px;
        --danger: #EF4444;
        --danger-lt: #FEF2F2;
        --green: #15803D;
        --green-lt: #F0FDF4;
        --green-border: #BBF7D0;
    }

    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    /* Pastikan wrapper konten utama (dari layouts.app) juga full height */
    body > div,
    .app-wrapper,
    .main-content,
    [class*="content"] {
        min-height: 100%;
    }

    .wrap {
        background: var(--bg);
        min-height: 100vh;
        padding: 28px 32px 40px;
        box-sizing: border-box;
    }

    .page-title { font-size: 22px; font-weight: 800; color: var(--neutral); margin-bottom: 4px; }
    .page-sub { font-size: 13px; color: var(--muted); margin-bottom: 24px; line-height: 1.6; }

    .btn-back {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 16px; background: #fff; border: 1.5px solid var(--border);
        border-radius: 10px; font-size: 13px; font-weight: 600; color: var(--neutral);
        text-decoration: none; transition: all .2s; margin-bottom: 20px;
    }
    .btn-back:hover { border-color: var(--gold); color: var(--gold); }

    .card {
        background: var(--white); border-radius: var(--radius);
        border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(0,0,0,.05);
        padding: 28px; margin-bottom: 20px;
    }

    .section-label {
        display: flex; align-items: center; gap: 8px;
        font-size: 13px; font-weight: 800; color: var(--neutral); margin-bottom: 20px;
    }
    .section-label svg { color: var(--gold); }

    /* 3 KOLOM UTAMA */
    .main-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr 1.2fr;
        gap: 32px;
        align-items: start;
    }

    /* KOLOM KIRI - INFO MAHASISWA */
    .mhs-field { margin-bottom: 14px; }
    .mhs-field:last-child { margin-bottom: 0; }
    .mhs-field label { display: block; font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; margin-bottom: 6px; }
    .mhs-value {
        padding: 10px 12px; background: #F9FAFB; border: 1.5px solid var(--border);
        border-radius: 10px; font-size: 13px; color: var(--neutral); font-weight: 600;
        min-height: 40px; display: flex; align-items: center;
    }
    .mhs-value.judul {
        align-items: flex-start; font-weight: 400; line-height: 1.6; min-height: 80px;
        font-size: 12.5px;
    }
    .mhs-nama-row { display: flex; align-items: center; gap: 8px; }
    .mhs-nama-row .mhs-value { flex: 1; }
    .mhs-icon-btn {
        width: 40px; height: 40px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        border: 1.5px solid var(--border); border-radius: 8px; color: var(--muted);
    }
    .nim-angkatan { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

    /* KOLOM TENGAH - DOSEN */
    .dosen-group-title { font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; margin-bottom: 10px; }
    .dosen-group { margin-bottom: 20px; }
    .dosen-group:last-child { margin-bottom: 0; }
    .dosen-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 14px; background: #F9FAFB; border: 1.5px solid var(--border);
        border-radius: 10px; margin-bottom: 8px;
    }
    .dosen-item:last-child { margin-bottom: 0; }
    .dosen-badge {
        width: 28px; height: 28px; border-radius: 8px; background: var(--gold-lt);
        border: 1px solid var(--gold-border); display: flex; align-items: center;
        justify-content: center; font-size: 10px; font-weight: 800; color: var(--gold);
        flex-shrink: 0;
    }
    .dosen-nama { font-size: 13px; font-weight: 600; color: var(--neutral); }
    .dosen-empty { font-size: 12px; color: var(--muted); font-style: italic; padding: 10px 0; }

    /* KOLOM KANAN - DETAIL PELAKSANAAN */
    .pelaksanaan-title { font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; margin-bottom: 14px; }
    .form-group { margin-bottom: 14px; }
    .form-group:last-child { margin-bottom: 0; }
    .form-label { display: block; font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; margin-bottom: 6px; }
    .form-input {
        width: 100%; padding: 10px 12px; border: 1.5px solid var(--border);
        border-radius: 10px; font-size: 13px; outline: none; font-family: inherit;
        transition: border .2s; box-sizing: border-box; background: #FAFAFA;
    }
    .form-input:focus { border-color: var(--gold); background: #fff; }
    .jam-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

    /* ACTIONS */
    .form-actions {
        display: flex; gap: 12px; justify-content: flex-end; margin-top: 4px;
    }
    .btn-submit {
        padding: 11px 32px; background: var(--gold); color: #fff;
        border: none; border-radius: 10px; font-size: 14px; font-weight: 700;
        cursor: pointer; font-family: inherit; transition: background .2s;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-submit:hover { background: #b8911f; }
    .btn-cancel-link {
        padding: 11px 20px; background: #fff; color: var(--muted);
        border: 1.5px solid var(--border); border-radius: 10px; font-size: 14px;
        font-weight: 600; text-decoration: none; display: inline-flex; align-items: center;
        transition: all .2s;
    }
    .btn-cancel-link:hover { border-color: var(--danger); color: var(--danger); }

    .alert-success {
        background: var(--green-lt); border: 1px solid var(--green-border); color: var(--green);
        border-radius: 10px; padding: 12px 16px; margin-bottom: 16px;
        font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;
    }

    @media (max-width: 900px) {
        .main-grid { grid-template-columns: 1fr; }
        .nim-angkatan { grid-template-columns: 1fr 1fr; }
        .jam-row { grid-template-columns: 1fr 1fr; }
        .wrap { padding: 20px 16px 32px; }
    }
</style>

{{-- Inject style ke parent container biar full height --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Cari semua parent dari .wrap dan paksa min-height: 100%
        let el = document.querySelector('.wrap');
        if (el) {
            let parent = el.parentElement;
            while (parent && parent !== document.body) {
                parent.style.minHeight = '100%';
                parent.style.background = '#F5F6FA';
                parent = parent.parentElement;
            }
        }
    });
</script>

<div class="wrap">

    <a href="{{ route('admin.seminar.index') }}" class="btn-back">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
        </svg>
        Kembali
    </a>

    @if(session('success'))
    <div class="alert-success">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="page-title">Tetapkan Jadwal Seminar Mahasiswa</div>
    <div class="page-sub">Kelola penjadwalan seminar mahasiswa yang telah menyelesaikan administrasi seminar dan telah memiliki dosen penguji.</div>

    <form method="POST" action="{{ route('admin.seminar.jadwalkan', $seminar->id) }}">
        @csrf

        <div class="card">
            <div class="section-label">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                </svg>
                Peserta Seminar
            </div>

            <div class="main-grid">

                {{-- KOLOM KIRI: INFO MAHASISWA --}}
                <div>
                    <div class="mhs-field">
                        <label>Nama Mahasiswa</label>
                        <div class="mhs-nama-row">
                            <div class="mhs-value">{{ $seminar->nama }}</div>
                            <div class="mhs-icon-btn">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 15.803a7.5 7.5 0 0 0 10.607 0Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="nim-angkatan">
                        <div class="mhs-field">
                            <label>NIM</label>
                            <div class="mhs-value">{{ $seminar->mahasiswa_id }}</div>
                        </div>
                        <div class="mhs-field">
                            <label>Angkatan</label>
                            <div class="mhs-value">{{ $seminar->angkatan ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="mhs-field">
                        <label>Judul Tugas Akhir</label>
                        <div class="mhs-value judul">{{ $seminar->judul_ta ?? '-' }}</div>
                    </div>
                </div>

                {{-- KOLOM TENGAH: DOSEN --}}
                <div>
                    <div class="dosen-group">
                        <div class="dosen-group-title">Dosen Pembimbing</div>
                        @forelse($pembimbing as $d)
                        <div class="dosen-item">
                            <div class="dosen-badge">P{{ $d['urutan'] }}</div>
                            <div class="dosen-nama">{{ $d['nama'] }}</div>
                        </div>
                        @empty
                        <div class="dosen-empty">Belum ada dosen pembimbing</div>
                        @endforelse
                    </div>
                    <div class="dosen-group">
                        <div class="dosen-group-title">Dosen Penguji</div>
                        @forelse($penguji as $p)
                        <div class="dosen-item">
                            <div class="dosen-badge">P{{ $p['urutan'] }}</div>
                            <div class="dosen-nama">{{ $p['nama'] }}</div>
                        </div>
                        @empty
                        <div class="dosen-empty">Belum ada dosen penguji</div>
                        @endforelse
                    </div>
                </div>

                {{-- KOLOM KANAN: DETAIL PELAKSANAAN --}}
                <div>
                    <div class="pelaksanaan-title">Detail Pelaksanaan</div>
                    <div class="form-group">
                        <label class="form-label">Ruangan Seminar <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="ruang" class="form-input"
                            placeholder="contoh: R.2.3 FSI"
                            value="{{ old('ruang', $seminar->ruang) }}" required>
                    </div>
                    <div class="jam-row">
                        <div class="form-group">
                            <label class="form-label">Jam Mulai <span style="color:var(--danger)">*</span></label>
                            <input type="time" name="waktu_mulai" class="form-input"
                                value="{{ old('waktu_mulai', $seminar->waktu_mulai ? \Carbon\Carbon::parse($seminar->waktu_mulai)->format('H:i') : '') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jam Selesai <span style="color:var(--danger)">*</span></label>
                            <input type="time" name="waktu_selesai" class="form-input"
                                value="{{ old('waktu_selesai', $seminar->waktu_selesai ? \Carbon\Carbon::parse($seminar->waktu_selesai)->format('H:i') : '') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Seminar <span style="color:var(--danger)">*</span></label>
                        <input type="date" name="tanggal_seminar" class="form-input"
                            value="{{ old('tanggal_seminar', $seminar->tanggal_seminar) }}" required>
                    </div>
                </div>

            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="form-actions">
            <a href="{{ route('admin.seminar.index') }}" class="btn-cancel-link">Kembali</a>
            <button type="submit" class="btn-submit">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                Simpan Jadwal Seminar
            </button>
        </div>

    </form>

</div>

@endsection