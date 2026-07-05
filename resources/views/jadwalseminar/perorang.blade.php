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

    html, body { height: 100%; margin: 0; padding: 0; }

    body > div, .app-wrapper, .main-content, [class*="content"] { min-height: 100%; }

    .wrap {
        background: var(--bg); min-height: 100vh;
        padding: 28px 32px 40px; box-sizing: border-box;
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

    .main-grid {
        display: grid; grid-template-columns: 1.2fr 1fr 1.2fr;
        gap: 32px; align-items: start;
    }

    .mhs-field { margin-bottom: 14px; }
    .mhs-field:last-child { margin-bottom: 0; }
    .mhs-field label { display: block; font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; margin-bottom: 6px; }
    .mhs-value {
        padding: 10px 12px; background: #F9FAFB; border: 1.5px solid var(--border);
        border-radius: 10px; font-size: 13px; color: var(--neutral); font-weight: 600;
        min-height: 40px; display: flex; align-items: center;
    }
    .mhs-value.judul {
        align-items: flex-start; font-weight: 400; line-height: 1.6;
        min-height: 80px; font-size: 12.5px;
    }
    .mhs-nama-row { display: flex; align-items: center; gap: 8px; }
    .mhs-nama-row .mhs-value { flex: 1; }
    .mhs-icon-btn {
        width: 40px; height: 40px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        border: 1.5px solid var(--border); border-radius: 8px; color: var(--muted);
    }
    .nim-angkatan { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

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

    .form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 4px; }
    .btn-submit {
        padding: 11px 32px; background: #FDE047; color: #713F12;
        border: 1px solid #FACC15; border-radius: 10px; font-size: 14px; font-weight: 700;
        cursor: pointer; font-family: inherit; transition: background .2s;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-submit:hover { background: #FACC15; color: #713F12; }
    .btn-cancel-link {
        padding: 11px 20px; background: #fff; color: var(--muted);
        border: 1.5px solid var(--border); border-radius: 10px; font-size: 14px;
        font-weight: 600; text-decoration: none; display: inline-flex; align-items: center;
        transition: all .2s;
    }
    .btn-cancel-link:hover { border-color: var(--danger); color: var(--danger); }

    /* ===================== POPUP ===================== */
    .popup-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.45); z-index: 9999;
        align-items: center; justify-content: center;
    }
    .popup-overlay.active { display: flex; }
    .popup-box {
        background: #fff; border-radius: 20px; padding: 40px 32px;
        width: 100%; max-width: 420px; text-align: center;
        box-shadow: 0 20px 60px rgba(0,0,0,.18); animation: popIn .2s ease;
    }
    @keyframes popIn {
        from { transform: scale(.85); opacity: 0; }
        to   { transform: scale(1);   opacity: 1; }
    }
    .popup-icon {
        width: 80px; height: 80px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 24px;
    }
    .popup-icon.berhasil { background: #fff; border: 2.5px solid #22C55E; }
    .popup-icon.gagal    { background: #fff; border: 2.5px solid #EF4444; }
    .popup-icon.bentrok  { background: #FEF3C7; border: 3px solid #F5D97A; }
    .popup-title { font-size: 22px; font-weight: 800; color: var(--neutral); margin-bottom: 10px; }
    .popup-msg   { font-size: 13.5px; color: var(--muted); margin-bottom: 20px; line-height: 1.6; }
    .popup-actions { display: flex; gap: 10px; justify-content: center; }
    .popup-btn {
        padding: 11px 32px; border-radius: 10px; font-size: 14px; font-weight: 700;
        cursor: pointer; font-family: inherit; border: none; transition: all .2s;
    }
    .popup-btn.ok { background: #FACC15; color: #735C00; min-width: 120px; }
    .popup-btn.ok:hover { background: #d4a00e; color: #fff; }

    /* List bentrok dosen */
    .bentrok-list {
        text-align: left; background: #FEF2F2; border: 1px solid #FECACA;
        border-radius: 10px; padding: 12px 14px; margin-bottom: 20px;
    }
    .bentrok-list-item {
        font-size: 12px; color: #991B1B; padding: 4px 0;
        border-bottom: 1px solid #FECACA; display: flex; flex-direction: column; gap: 2px;
    }
    .bentrok-list-item:last-child { border-bottom: none; padding-bottom: 0; }
    .bentrok-dosen-name { font-weight: 700; color: #B91C1C; }
    .bentrok-detail { font-size: 11px; color: #DC2626; }

    @media (max-width: 900px) {
        .main-grid { grid-template-columns: 1fr; }
        .nim-angkatan { grid-template-columns: 1fr 1fr; }
        .jam-row { grid-template-columns: 1fr 1fr; }
        .wrap { padding: 20px 16px 32px; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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

    <div class="page-title">Tetapkan Jadwal Seminar Mahasiswa</div>
    <div class="page-sub">Kelola penjadwalan seminar mahasiswa yang telah menyelesaikan administrasi seminar dan telah memiliki dosen penguji.</div>

    <form method="POST" action="{{ route('jadwalseminar.jadwalkan', $seminar->id) }}">
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
            <a href="{{ route('jadwalseminar.index') }}" class="btn-cancel-link">Kembali</a>
            <button type="submit" class="btn-submit">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                Simpan Jadwal Seminar
            </button>
        </div>

    </form>

</div>

{{-- ===================== POPUP BERHASIL ===================== --}}
<div class="popup-overlay" id="popupBerhasil">
    <div class="popup-box">
        <div class="popup-icon berhasil">
            <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#22C55E" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div class="popup-title">Berhasil!</div>
        <div class="popup-msg">Jadwal seminar mahasiswa berhasil diperbarui.</div>
        <div class="popup-actions">
            <button class="popup-btn ok"
                onclick="window.location.href='{{ route('jadwalseminar.index') }}'">
                OK
            </button>
        </div>
    </div>
</div>

{{-- ===================== POPUP GAGAL ===================== --}}
<div class="popup-overlay" id="popupGagal">
    <div class="popup-box">
        <div class="popup-icon gagal">
            <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#EF4444" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <div class="popup-title">Gagal!</div>
        <div class="popup-msg">Jadwal seminar gagal diperbarui. Silakan coba lagi.</div>
        <div class="popup-actions">
            <button class="popup-btn ok"
                onclick="document.getElementById('popupGagal').classList.remove('active')">
                OK
            </button>
        </div>
    </div>
</div>

{{-- ===================== POPUP BENTROK DOSEN ===================== --}}
<div class="popup-overlay" id="popupBentrok">
    <div class="popup-box" style="max-width:480px;">
        <div class="popup-icon bentrok">
            <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#C9A227" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.008v.008H12v-.008Z"/>
            </svg>
        </div>
        <div class="popup-title">Jadwal Bentrok!</div>
        <div class="popup-msg">Dosen berikut sudah memiliki jadwal seminar di waktu yang sama:</div>
        <div class="bentrok-list" id="bentrokList">
            {{-- Diisi oleh PHP --}}
            @if(session('bentrok_dosen'))
                @foreach(session('bentrok_dosen') as $k)
                <div class="bentrok-list-item">
                    <span class="bentrok-dosen-name">{{ $k['dosen'] }}</span>
                    <span class="bentrok-detail">Sudah dijadwalkan bersama {{ $k['mahasiswa'] }} pukul {{ $k['jam'] }}</span>
                </div>
                @endforeach
            @endif
        </div>
        <div class="popup-actions">
            <button class="popup-btn ok"
                onclick="document.getElementById('popupBentrok').classList.remove('active')">
                Ubah Jadwal
            </button>
        </div>
    </div>
</div>

{{-- ===================== SCRIPT TRIGGER POPUP ===================== --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('simpan_berhasil'))
            document.getElementById('popupBerhasil').classList.add('active');
        @endif
        @if(session('simpan_gagal'))
            document.getElementById('popupGagal').classList.add('active');
        @endif
        @if(session('bentrok_dosen'))
            document.getElementById('popupBentrok').classList.add('active');
        @endif
    });
</script>

@endsection