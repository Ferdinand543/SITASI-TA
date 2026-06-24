@extends('layouts.app')

@section('title', 'Hasil Penilaian Seminar TA-1')

@section('content')

@php
$tabAktif = request('tab', 'akumulasi');

$nilaiPembimbing1 = $penilaianPembimbing1 ?? null;
$nilaiPembimbing2 = $penilaianPembimbing2 ?? null;
$nilaiPenguji1    = $penilaianPenguji1    ?? null;
$nilaiPenguji2    = $penilaianPenguji2    ?? null;

// ── Section A: khusus pembimbing (kolom di penilaian_seminar_pembimbing) ──
$komponenA = [
    ['key' => 'nilai_kualitas_bimbingan',    'label' => 'Keaktifan dalam melakukan proses bimbingan/konsultasi',            'maks' => 10],
    ['key' => 'nilai_kemampuan_penelusuran', 'label' => 'Kemampuan untuk melakukan pekerjaan/tugas berdasarkan instruksi',   'maks' => 15],
    ['key' => 'nilai_penggunaan_teori',      'label' => 'Penguasaan teori dasar yang digunakan dalam setiap proses',         'maks' => 15],
    ['key' => 'nilai_dokumentasi_produk',    'label' => 'Penyediaan dokumentasi dan produk yang dibuat',                     'maks' => 25],
    ['key' => 'nilai_kesesuaian_target',     'label' => 'Kesesuaian hasil/produk dengan ketentuan/persyaratan yang berlaku', 'maks' => 35],
];

// ── Section B pembimbing (kolom di penilaian_seminar_pembimbing) ──
$komponenBPembimbing = [
    ['key' => 'nilai_teknik_presentasi',    'label' => 'Teknik dan sikap presentasi',                                            'maks' => 15],
    ['key' => 'nilai_dokumentasi_proposal', 'label' => 'Dokumentasi dan tata cara penulisan',                                     'maks' => 20],
    ['key' => 'nilai_kemanfaatan_teori',    'label' => 'Pemahaman teori, metode penelitian, konsep hingga pengujian',             'maks' => 30],
    ['key' => 'nilai_pemahaman_kebutuhan',  'label' => 'Pemahaman kebutuhan dan permasalahan penelitian yang dipresentasikan',    'maks' => 35],
];

// ── Section B penguji (kolom di penilaian_seminar — nama kolom BEDA) ──
$komponenBPenguji = [
    ['key' => 'nilai_teknik_presentasi',   'label' => 'Teknik dan sikap presentasi',                                            'maks' => 15],
    ['key' => 'nilai_dokumentasi',         'label' => 'Dokumentasi dan tata cara penulisan',                                     'maks' => 20],
    ['key' => 'nilai_pemahaman_teori',     'label' => 'Pemahaman teori, metode penelitian, konsep hingga pengujian',             'maks' => 30],
    ['key' => 'nilai_pemahaman_kebutuhan', 'label' => 'Pemahaman kebutuhan dan permasalahan penelitian yang dipresentasikan',    'maks' => 35],
];

// Default komponenB untuk render (akan di-override sesuai tab)
$komponenB = $komponenBPembimbing;

$nilaiAkhirAkumulasi = $nilaiAkhir ?? null;
$adaPenilaian        = $nilaiAkhirAkumulasi !== null;

$jumlahSudahNilai = count(array_filter([$nilaiPembimbing1, $nilaiPembimbing2, $nilaiPenguji1, $nilaiPenguji2]));
$sedangBerlangsung = $jumlahSudahNilai > 0 && !$adaPenilaian;

$tabSudahNilai = match($tabAktif) {
    'pembimbing1' => $nilaiPembimbing1 !== null,
    'pembimbing2' => $nilaiPembimbing2 !== null,
    'penguji1'    => $nilaiPenguji1    !== null,
    'penguji2'    => $nilaiPenguji2    !== null,
    default       => $adaPenilaian,
};

$grade = $status = $statusClass = null;
if ($adaPenilaian) {
    $persen = $nilaiAkhirAkumulasi;
    if ($persen >= 85)     { $grade = 'A';  $status = 'LULUS'; }
    elseif ($persen >= 75) { $grade = 'B+'; $status = 'LULUS'; }
    elseif ($persen >= 70) { $grade = 'B';  $status = 'LULUS'; }
    elseif ($persen >= 60) { $grade = 'C';  $status = 'TIDAK LULUS'; }
    else                   { $grade = 'D';  $status = 'TIDAK LULUS'; }
    $statusClass = $status === 'LULUS' ? 'lulus' : 'tidak-lulus';
}
@endphp

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
    }
    .penilaian-wrap { background: var(--bg); min-height: 100vh; padding-bottom: 60px; }
    .penilaian-hero {
        background-image: url('{{ asset("images/1.jpeg") }}');
        background-size: cover; background-position: center right;
        border-radius: 20px; padding: 32px 40px; margin-bottom: 24px;
        position: relative; overflow: hidden; min-height: 140px; display: flex; align-items: center;
    }
    .hero-content { position: relative; z-index: 2; }
    .hero-title { font-size: 26px; font-weight: 800; color: #7C5C00; margin-bottom: 4px; }
    .hero-sub { font-size: 13px; color: #92400E; }
    .penilaian-grid { display: grid; grid-template-columns: 260px 1fr; gap: 20px; align-items: start; }
    .sidebar-kiri { position: sticky; top: 20px; }
    .nilai-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,.04); text-align: center; }
    .nilai-label { font-size: 10.5px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 8px; }
    .final-grade-badge { display: inline-block; font-size: 10px; font-weight: 800; padding: 2px 10px; border-radius: 99px; margin-bottom: 10px; background: var(--gold-lt); color: var(--gold); border: 1px solid var(--gold-border); }
    .nilai-circle-wrap { position: relative; width: 120px; height: 120px; margin: 0 auto 16px; }
    .nilai-circle-svg { width: 120px; height: 120px; transform: rotate(-90deg); }
    .nilai-circle-bg { fill: none; stroke: #F3F4F6; stroke-width: 8; }
    .nilai-circle-fill { fill: none; stroke-width: 8; stroke-linecap: round; }
    .nilai-circle-fill.lulus       { stroke: #C9A227; }
    .nilai-circle-fill.tidak-lulus { stroke: #DC2626; }
    .nilai-angka { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; }
    .nilai-angka-besar { font-size: 24px; font-weight: 900; color: var(--neutral); line-height: 1; }
    .nilai-angka-max { font-size: 11px; color: var(--muted); margin-top: 2px; }
    .grade-status-row { display: flex; gap: 10px; justify-content: center; margin-bottom: 16px; }
    .grade-box { background: #F8FAFC; border-radius: 10px; padding: 10px 14px; text-align: center; flex: 1; }
    .grade-box-label { font-size: 10px; font-weight: 700; color: var(--muted); text-transform: uppercase; margin-bottom: 4px; }
    .grade-box-value { font-size: 20px; font-weight: 900; color: var(--neutral); }
    .status-badge-lulus { display: inline-block; background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 99px; }
    .status-badge-tidak-lulus { display: inline-block; background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 99px; }
    .sidebar-belum-nilai { text-align: center; padding: 10px 0 4px; }
    .sidebar-lock-icon { width: 52px; height: 52px; background: #F8FAFC; border: 1.5px solid var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; }
    .sidebar-belum-label { font-size: 12.5px; font-weight: 700; color: var(--neutral); margin-bottom: 6px; }
    .sidebar-belum-desc { font-size: 11px; color: var(--muted); line-height: 1.6; margin-bottom: 14px; }
    .sidebar-nilai-placeholder { background: #F8FAFC; border: 1.5px dashed var(--border); border-radius: 12px; padding: 14px; margin-bottom: 10px; text-align: center; }
    .sidebar-placeholder-num { font-size: 32px; font-weight: 900; color: #D1D5DB; line-height: 1; }
    .sidebar-placeholder-sub { font-size: 11px; color: #9CA3AF; margin-top: 4px; }
    .sidebar-sync-note { font-size: 11px; color: #9CA3AF; display: flex; align-items: center; gap: 5px; justify-content: center; }
    .badge-berlangsung { display: inline-flex; align-items: center; gap: 5px; background: #FFF7ED; border: 1px solid #FED7AA; color: #C2410C; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 99px; margin-bottom: 12px; }
    .badge-berlangsung-dot { width: 7px; height: 7px; background: #F97316; border-radius: 50%; animation: pulse-dot 1.4s infinite; }
    @keyframes pulse-dot { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: .5; transform: scale(.7); } }
    .progress-dosen-wrap { background: #F8FAFC; border: 1px solid var(--border); border-radius: 10px; padding: 12px 14px; margin-bottom: 10px; }
    .progress-dosen-label { font-size: 10.5px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; margin-bottom: 10px; }
    .progress-dosen-item { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-size: 11.5px; color: var(--neutral); font-weight: 600; }
    .progress-dosen-item:last-child { margin-bottom: 0; }
    .progress-dot-done    { width: 9px; height: 9px; background: #16A34A; border-radius: 50%; flex-shrink: 0; }
    .progress-dot-pending { width: 9px; height: 9px; background: #D1D5DB; border-radius: 50%; flex-shrink: 0; }
    .progress-dot-active  { width: 9px; height: 9px; background: #F97316; border-radius: 50%; flex-shrink: 0; animation: pulse-dot 1.4s infinite; }
    .progress-status-done    { margin-left: auto; font-size: 10px; color: #16A34A; font-weight: 700; }
    .progress-status-pending { margin-left: auto; font-size: 10px; color: #9CA3AF; font-weight: 600; }
    .progress-status-active  { margin-left: auto; font-size: 10px; color: #F97316; font-weight: 700; }
    .empty-berlangsung, .empty-menunggu, .empty-penilaian { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 50px 24px; box-shadow: 0 2px 8px rgba(0,0,0,.04); text-align: center; }
    .empty-penilaian { padding: 60px 24px; }
    .empty-icon-wrap { width: 72px; height: 72px; border-radius: 18px; background: var(--gold-lt); border: 2px solid var(--gold-border); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; position: relative; }
    .empty-icon-dot { position: absolute; bottom: -4px; right: -4px; width: 20px; height: 20px; background: var(--gold); border-radius: 50%; border: 2px solid #fff; display: flex; align-items: center; justify-content: center; }
    .empty-title { font-size: 18px; font-weight: 800; color: var(--neutral); margin-bottom: 8px; }
    .empty-desc { font-size: 13px; color: var(--muted); max-width: 360px; margin: 0 auto 6px; line-height: 1.7; }
    .empty-desc2 { font-size: 12px; color: #9CA3AF; max-width: 320px; margin: 0 auto 24px; line-height: 1.6; }
    .empty-badge { display: inline-flex; align-items: center; gap: 6px; background: var(--gold-lt); border: 1px solid var(--gold-border); color: #92400E; font-size: 12px; font-weight: 700; padding: 5px 14px; border-radius: 99px; margin-bottom: 20px; }
    .empty-btns { display: flex; gap: 12px; justify-content: center; }
    .btn-berlangsung { display: inline-flex; align-items: center; gap: 6px; padding: 9px 20px; border-radius: 10px; font-size: 12.5px; font-weight: 700; background: #FFF7ED; color: #C2410C; border: 1.5px solid #FED7AA; text-decoration: none; }
    .btn-menunggu    { display: inline-flex; align-items: center; gap: 6px; padding: 9px 20px; border-radius: 10px; font-size: 12.5px; font-weight: 700; background: var(--gold-lt); color: #7C5C00; border: 1.5px solid var(--gold-border); text-decoration: none; }
    .btn-kembali     { padding: 10px 24px; border-radius: 10px; font-size: 13px; font-weight: 700; background: var(--white); color: var(--neutral); border: 1.5px solid var(--border); text-decoration: none; }
    .btn-jadwal      { padding: 10px 24px; border-radius: 10px; font-size: 13px; font-weight: 700; background: #FFE083; color: #7C5C00; border: none; text-decoration: none; }
    .mhs-info-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,.04); }
    .mhs-info-head { display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 700; color: var(--muted); margin-bottom: 14px; }
    .mhs-info-label { font-size: 10px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; margin-bottom: 3px; margin-top: 10px; }
    .mhs-info-label:first-of-type { margin-top: 0; }
    .mhs-info-value { font-size: 13px; font-weight: 600; color: var(--neutral); }
    .mhs-info-judul { font-size: 12.5px; font-style: italic; color: var(--neutral); background: #F8FAFC; border-radius: 8px; padding: 10px 12px; margin-top: 4px; line-height: 1.5; }
    .tab-nav { display: flex; gap: 4px; background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 6px; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,.04); flex-wrap: wrap; }
    .tab-btn { flex: 1; min-width: 80px; padding: 8px 10px; border-radius: 10px; font-size: 12px; font-weight: 700; color: var(--muted); border: none; background: transparent; cursor: pointer; transition: all .2s; font-family: inherit; text-decoration: none; text-align: center; display: block; position: relative; }
    .tab-btn:hover { background: var(--gold-lt); color: var(--gold); }
    .tab-btn.active { background: #FFE083; color: #7C5C00; }
    .tab-btn .tab-dot-done    { position: absolute; top: 4px; right: 6px; width: 6px; height: 6px; background: #16A34A; border-radius: 50%; }
    .tab-btn .tab-dot-pending { position: absolute; top: 4px; right: 6px; width: 6px; height: 6px; background: #F97316; border-radius: 50%; animation: pulse-dot 1.4s infinite; }
    .penilaian-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 22px; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,.04); }
    .penilaian-section-title { font-size: 13px; font-weight: 800; color: var(--neutral); margin-bottom: 14px; }
    .nilai-table { width: 100%; border-collapse: collapse; }
    .nilai-table thead th { font-size: 10.5px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; padding: 8px 12px; text-align: left; border-bottom: 1px solid var(--border); background: #FAFAFA; }
    .nilai-table thead th:last-child { text-align: right; }
    .nilai-table tbody tr { border-bottom: 1px solid #F3F4F6; }
    .nilai-table tbody tr:last-child { border-bottom: none; }
    .nilai-table tbody td { padding: 12px; font-size: 13px; color: var(--neutral); }
    .nilai-table tbody td:first-child { color: var(--muted); font-weight: 600; width: 32px; }
    .nilai-table tbody td:last-child { text-align: right; font-weight: 800; font-size: 14px; }
    .nilai-total-row td { background: var(--gold-lt); font-weight: 800 !important; color: #7C5C00 !important; padding: 12px !important; }
    .nilai-total-row td:last-child { font-size: 16px !important; color: var(--gold) !important; }
    .catatan-section { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 22px; box-shadow: 0 2px 8px rgba(0,0,0,.04); }
    .catatan-head { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 800; color: var(--neutral); margin-bottom: 16px; flex-wrap: wrap; }
    .catatan-item { display: flex; gap: 14px; padding: 16px; background: #FAFAFA; border-radius: 12px; border: 1px solid var(--border); margin-bottom: 10px; }
    .catatan-item:last-child { margin-bottom: 0; }
    .catatan-avatar { width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0; background: var(--gold-lt); border: 2px solid var(--gold-border); display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800; color: var(--gold); }
    .catatan-dosen-nama  { font-size: 13px; font-weight: 700; color: var(--neutral); }
    .catatan-dosen-peran { font-size: 11px; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: .3px; margin-bottom: 6px; }
    .catatan-teks { font-size: 13px; color: var(--neutral); font-style: italic; background: var(--white); border-left: 3px solid var(--gold-border); padding: 10px 14px; border-radius: 0 8px 8px 0; line-height: 1.6; }
    .alert-lulus { background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 12px; padding: 14px 18px; margin-bottom: 16px; display: flex; gap: 12px; align-items: flex-start; font-size: 13px; color: #15803D; }
    .alert-lulus strong { display: block; font-weight: 800; font-size: 14px; margin-bottom: 2px; }
    .alert-tidak-lulus { background: #FEF2F2; border: 1px solid #FECACA; border-radius: 12px; padding: 14px 18px; margin-bottom: 16px; display: flex; gap: 12px; align-items: flex-start; font-size: 13px; color: #DC2626; }
    .alert-tidak-lulus strong { display: block; font-weight: 800; font-size: 14px; margin-bottom: 2px; }
    .info-note-section { background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 10px; padding: 10px 14px; font-size: 12px; color: #92400E; margin-bottom: 16px; display: flex; gap: 8px; align-items: flex-start; }
    @media (max-width: 900px) { .penilaian-grid { grid-template-columns: 1fr; } .sidebar-kiri { position: static; } }
</style>

<div class="penilaian-wrap">

    <div class="penilaian-hero">
        <div class="hero-content">
            <div class="hero-title">Hasil Penilaian Seminar TA-1</div>
            <div class="hero-sub">Lihat hasil penilaian seminar tugas akhir Anda.</div>
        </div>
    </div>

    {{-- ════════ KONDISI 1: Belum ada satu pun dosen input nilai ════════ --}}
    @if(!$adaPenilaian && !$sedangBerlangsung)
    <div class="empty-penilaian">
        <div class="empty-badge">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="13" height="13"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            Menunggu Penilaian
        </div>
        <div class="empty-icon-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#C9A227" width="32" height="32">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
            </svg>
            <div class="empty-icon-dot">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#fff" width="11" height="11"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
        </div>
        <div class="empty-title">Penilaian Seminar Belum Tersedia</div>
        <div class="empty-desc">Hasil penilaian seminar tugas akhir Anda belum tersedia. Hasil akan segera ditampilkan setelah proses penilaian selesai.</div>
        <div class="empty-desc2">Nilai dan catatan akademik akan ditampilkan setelah seluruh dosen menyelesaikan penilaian.</div>
        <div class="empty-btns">
            <a href="/mahasiswa" class="btn-kembali">Kembali</a>
            <a href="{{ route('jadwal.index') }}" class="btn-jadwal">Lihat Jadwal Seminar</a>
        </div>
    </div>

    {{-- ════════ KONDISI 2 & 3: Ada penilaian (full atau partial) ════════ --}}
    @else

    <div class="penilaian-grid">

        {{-- ─── SIDEBAR KIRI ─── --}}
        <div class="sidebar-kiri">
            <div class="nilai-card">
                <div class="nilai-label">Nilai Akhir</div>

                @if($adaPenilaian)
                    <div class="final-grade-badge">Final Grade</div>
                    @php
                        $radius        = 52;
                        $circumference = 2 * M_PI * $radius;
                        $offset        = $circumference - (min($nilaiAkhirAkumulasi, 100) / 100) * $circumference;
                    @endphp
                    <div class="nilai-circle-wrap">
                        <svg class="nilai-circle-svg" viewBox="0 0 120 120">
                            <circle class="nilai-circle-bg" cx="60" cy="60" r="{{ $radius }}"/>
                            <circle class="nilai-circle-fill {{ $statusClass }}"
                                cx="60" cy="60" r="{{ $radius }}"
                                stroke-dasharray="{{ $circumference }}"
                                stroke-dashoffset="{{ $offset }}"/>
                        </svg>
                        <div class="nilai-angka">
                            <div class="nilai-angka-besar">{{ $nilaiAkhirAkumulasi }}</div>
                            <div class="nilai-angka-max">/ 100.00</div>
                        </div>
                    </div>
                    <div class="grade-status-row">
                        <div class="grade-box">
                            <div class="grade-box-label">Grade</div>
                            <div class="grade-box-value">{{ $grade }}</div>
                        </div>
                        <div class="grade-box">
                            <div class="grade-box-label">Status</div>
                            <div style="margin-top:6px;">
                                @if($status === 'LULUS')
                                <span class="status-badge-lulus">LULUS</span>
                                @else
                                <span class="status-badge-tidak-lulus">TIDAK LULUS</span>
                                @endif
                            </div>
                        </div>
                    </div>

                @else
                    <div class="sidebar-belum-nilai">
                        <div class="badge-berlangsung">
                            <span class="badge-berlangsung-dot"></span>
                            Sedang Berlangsung
                        </div>
                        <div class="sidebar-lock-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#9CA3AF" width="22" height="22">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                            </svg>
                        </div>
                        <div class="sidebar-belum-label">Nilai Akhir Belum Tersedia</div>
                        <div class="sidebar-belum-desc">Nilai akhir akan ditampilkan setelah seluruh dosen menyelesaikan penilaian seminar.</div>
                        <div class="sidebar-nilai-placeholder">
                            <div class="sidebar-placeholder-num">-</div>
                            <div class="sidebar-placeholder-sub">Menunggu akumulasi</div>
                        </div>
                        <div class="progress-dosen-wrap">
                            <div class="progress-dosen-label">Status Penilaian</div>
                            <div class="progress-dosen-item">
                                <span class="{{ $nilaiPembimbing1 ? 'progress-dot-done' : 'progress-dot-active' }}"></span>
                                Pembimbing 1
                                @if($nilaiPembimbing1)<span class="progress-status-done">✓ Selesai</span>
                                @else<span class="progress-status-active">Menunggu</span>@endif
                            </div>
                            <div class="progress-dosen-item">
                                <span class="{{ $nilaiPembimbing2 ? 'progress-dot-done' : 'progress-dot-pending' }}"></span>
                                Pembimbing 2
                                @if($nilaiPembimbing2)<span class="progress-status-done">✓ Selesai</span>
                                @else<span class="progress-status-pending">Menunggu</span>@endif
                            </div>
                            <div class="progress-dosen-item">
                                <span class="{{ $nilaiPenguji1 ? 'progress-dot-done' : 'progress-dot-pending' }}"></span>
                                Penguji 1
                                @if($nilaiPenguji1)<span class="progress-status-done">✓ Selesai</span>
                                @else<span class="progress-status-pending">Menunggu</span>@endif
                            </div>
                            <div class="progress-dosen-item">
                                <span class="{{ $nilaiPenguji2 ? 'progress-dot-done' : 'progress-dot-pending' }}"></span>
                                Penguji 2
                                @if($nilaiPenguji2)<span class="progress-status-done">✓ Selesai</span>
                                @else<span class="progress-status-pending">Menunggu</span>@endif
                            </div>
                        </div>
                        <div class="sidebar-sync-note">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#9CA3AF" width="11" height="11">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                            </svg>
                            Sinkronisasi data otomatis...
                        </div>
                    </div>
                @endif
            </div>

            <div class="mhs-info-card">
                <div class="mhs-info-head">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0"/>
                    </svg>
                    Informasi Mahasiswa
                </div>
                <div class="mhs-info-label">Nomor Induk Mahasiswa</div>
                <div class="mhs-info-value">{{ $user->nim_nid ?? '-' }}</div>
                <div class="mhs-info-label">Nama Lengkap</div>
                <div class="mhs-info-value">{{ $mahasiswa->nama ?? $user->nama ?? '-' }}</div>
                <div class="mhs-info-label">Judul Tugas Akhir</div>
                <div class="mhs-info-judul">"{{ $judulTA ?? '-' }}"</div>
            </div>
        </div>

        {{-- ─── KONTEN KANAN ─── --}}
        <div>

            @if($adaPenilaian)
                @if($status === 'LULUS')
                <div class="alert-lulus">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="20" height="20" style="flex-shrink:0;margin-top:1px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <div>
                        <strong>Selamat, Anda dinyatakan Lulus Seminar TA-1!</strong>
                        Silakan ikuti tahap selanjutnya sesuai arahan dosen pembimbing.
                    </div>
                </div>
                @else
                <div class="alert-tidak-lulus">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="20" height="20" style="flex-shrink:0;margin-top:1px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                    </svg>
                    <div>
                        <strong>Maaf, Anda Belum Lulus Seminar TA-1</strong>
                        Silakan perbaiki laporan sesuai masukan dosen dan daftar ulang seminar TA-1.
                    </div>
                </div>
                @endif
            @endif

            {{-- TABS --}}
            <div class="tab-nav">
                <a href="?tab=akumulasi"   class="tab-btn {{ $tabAktif==='akumulasi'   ? 'active':'' }}">Akumulasi</a>
                <a href="?tab=pembimbing1" class="tab-btn {{ $tabAktif==='pembimbing1' ? 'active':'' }}">
                    Pembimbing 1
                    @if($nilaiPembimbing1)<span class="tab-dot-done"></span>
                    @elseif($sedangBerlangsung)<span class="tab-dot-pending"></span>@endif
                </a>
                <a href="?tab=pembimbing2" class="tab-btn {{ $tabAktif==='pembimbing2' ? 'active':'' }}">
                    Pembimbing 2
                    @if($nilaiPembimbing2)<span class="tab-dot-done"></span>
                    @elseif($sedangBerlangsung)<span class="tab-dot-pending"></span>@endif
                </a>
                <a href="?tab=penguji1" class="tab-btn {{ $tabAktif==='penguji1' ? 'active':'' }}">
                    Penguji 1
                    @if($nilaiPenguji1)<span class="tab-dot-done"></span>
                    @elseif($sedangBerlangsung)<span class="tab-dot-pending"></span>@endif
                </a>
                <a href="?tab=penguji2" class="tab-btn {{ $tabAktif==='penguji2' ? 'active':'' }}">
                    Penguji 2
                    @if($nilaiPenguji2)<span class="tab-dot-done"></span>
                    @elseif($sedangBerlangsung)<span class="tab-dot-pending"></span>@endif
                </a>
            </div>

            {{-- KONDISI 2: Sedang Berlangsung, Tab Akumulasi --}}
            @if($sedangBerlangsung && $tabAktif === 'akumulasi')
            <div class="empty-berlangsung">
                <div class="empty-badge" style="background:#FFF7ED;border-color:#FED7AA;color:#C2410C;">
                    <span class="badge-berlangsung-dot" style="width:8px;height:8px;"></span>
                    Penilaian Sedang Berlangsung
                </div>
                <div class="empty-icon-wrap" style="background:#FFF7ED;border-color:#FED7AA;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#F97316" width="32" height="32">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                    </svg>
                    <div class="empty-icon-dot" style="background:#F97316;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#fff" width="10" height="10"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    </div>
                </div>
                <div class="empty-title">Penilaian Belum Tersedia</div>
                <div class="empty-desc">Klik tab dosen lainnya untuk melihat detail penilaian yang sudah masuk ke sistem.</div>
                <div class="empty-desc2">Nilai akumulasi akan tersedia setelah semua dosen menyelesaikan penilaian.</div>
                <a href="?tab=pembimbing1" class="btn-berlangsung">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                    </svg>
                    Penilaian Sedang Berlangsung
                </a>
            </div>

            {{-- KONDISI 3: Tab dosen belum input --}}
            @elseif(($sedangBerlangsung || $adaPenilaian) && $tabAktif !== 'akumulasi' && !$tabSudahNilai)
            <div class="empty-menunggu">
                <div class="empty-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="13" height="13"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    Menunggu Penilaian
                </div>
                <div class="empty-icon-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#C9A227" width="32" height="32">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                    </svg>
                    <div class="empty-icon-dot">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#fff" width="10" height="10"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    </div>
                </div>
                <div class="empty-title">Penilaian Belum Tersedia</div>
                <div class="empty-desc">Dosen belum menyelesaikan proses penilaian seminar tugas akhir.</div>
                <a href="?tab=akumulasi" class="btn-menunggu">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    Menunggu Penilaian
                </a>
            </div>

            {{-- KONDISI NORMAL: Tampilkan tabel nilai --}}
            @else

            @php
            $modelTampil      = match($tabAktif) {
                'pembimbing1' => $nilaiPembimbing1,
                'pembimbing2' => $nilaiPembimbing2,
                'penguji1'    => $nilaiPenguji1,
                'penguji2'    => $nilaiPenguji2,
                default       => null,
            };

            $modelsPembimbing = array_filter([$nilaiPembimbing1, $nilaiPembimbing2]);
            $modelsPenguji    = array_filter([$nilaiPenguji1, $nilaiPenguji2]);
            $isPenguji        = in_array($tabAktif, ['penguji1', 'penguji2']);

            // ── Hitung nilai per tab ──
            if ($tabAktif === 'akumulasi') {
                // Section A: rata-rata dari pembimbing saja
                foreach ($komponenA as &$k) {
                    $k['nilai'] = count($modelsPembimbing) > 0
                        ? round(collect($modelsPembimbing)->avg(fn($m) => $m->{$k['key']} ?? 0), 1)
                        : 0;
                }

                // Section B akumulasi: rata-rata per kriteria,
                // tapi nama kolom pembimbing dan penguji BEDA → hitung manual
                $avgTeknik = collect(array_merge(
                    array_map(fn($m) => $m->nilai_teknik_presentasi ?? 0, $modelsPembimbing),
                    array_map(fn($m) => $m->nilai_teknik_presentasi ?? 0, $modelsPenguji)
                ))->avg();

                $avgDokumentasi = collect(array_merge(
                    array_map(fn($m) => $m->nilai_dokumentasi_proposal ?? 0, $modelsPembimbing),
                    array_map(fn($m) => $m->nilai_dokumentasi          ?? 0, $modelsPenguji)
                ))->avg();

                $avgTeori = collect(array_merge(
                    array_map(fn($m) => $m->nilai_kemanfaatan_teori ?? 0, $modelsPembimbing),
                    array_map(fn($m) => $m->nilai_pemahaman_teori   ?? 0, $modelsPenguji)
                ))->avg();

                $avgPemahaman = collect(array_merge(
                    array_map(fn($m) => $m->nilai_pemahaman_kebutuhan ?? 0, $modelsPembimbing),
                    array_map(fn($m) => $m->nilai_pemahaman_kebutuhan ?? 0, $modelsPenguji)
                ))->avg();

                $komponenB[0]['nilai'] = round($avgTeknik, 1);
                $komponenB[1]['nilai'] = round($avgDokumentasi, 1);
                $komponenB[2]['nilai'] = round($avgTeori, 1);
                $komponenB[3]['nilai'] = round($avgPemahaman, 1);
                unset($k);

            } elseif (in_array($tabAktif, ['pembimbing1', 'pembimbing2'])) {
                // Pembimbing: pakai $komponenBPembimbing (nama kolom pembimbing)
                foreach ($komponenA as &$k) { $k['nilai'] = (int)($modelTampil->{$k['key']} ?? 0); }
                foreach ($komponenB as &$k) { $k['nilai'] = (int)($modelTampil->{$k['key']} ?? 0); }
                unset($k);

            } else {
                // Penguji: override komponenB pakai nama kolom penguji yang BEDA
                $komponenB = $komponenBPenguji;
                foreach ($komponenA as &$k) { $k['nilai'] = null; }
                foreach ($komponenB as &$k) { $k['nilai'] = (int)($modelTampil->{$k['key']} ?? 0); }
                unset($k);
            }

            $totalA = collect($komponenA)->filter(fn($k) => $k['nilai'] !== null)->sum('nilai');
            $totalB = collect($komponenB)->sum('nilai');
            @endphp

            {{-- Info note --}}
            @if($tabAktif === 'akumulasi')
            <div class="info-note-section">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14" style="flex-shrink:0;margin-top:1px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                </svg>
                Tab Akumulasi menampilkan rata-rata nilai dari semua penilai. Section A dari pembimbing, Section B dari semua penilai (pembimbing + penguji).
            </div>
            @elseif($isPenguji)
            <div class="info-note-section">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14" style="flex-shrink:0;margin-top:1px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                </svg>
                Penguji hanya menilai Section B. Section A tidak dinilai oleh penguji.
            </div>
            @endif

            {{-- Section A --}}
            <div class="penilaian-card">
                <div class="penilaian-section-title">Section A — Penilaian Proses Bimbingan</div>
                @if($isPenguji)
                <div style="text-align:center;padding:20px;color:var(--muted);font-size:13px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="20" height="20" style="display:block;margin:0 auto 8px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    Section A tidak dinilai oleh penguji
                </div>
                @else
                <table class="nilai-table">
                    <thead><tr><th>No</th><th>Kriteria Penilaian</th><th>Maks</th><th>Nilai</th></tr></thead>
                    <tbody>
                        @foreach($komponenA as $k)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $k['label'] }}</td>
                            <td style="text-align:right;color:var(--muted);font-size:12px;">{{ $k['maks'] }}</td>
                            <td>{{ $k['nilai'] ?? '-' }}</td>
                        </tr>
                        @endforeach
                        <tr class="nilai-total-row">
                            <td colspan="3">Total Section A</td>
                            <td>{{ $totalA }}</td>
                        </tr>
                    </tbody>
                </table>
                @endif
            </div>

            {{-- Section B --}}
            <div class="penilaian-card">
                <div class="penilaian-section-title">Section B — Penilaian Seminar TA-1</div>
                <table class="nilai-table">
                    <thead><tr><th>No</th><th>Kriteria Penilaian</th><th>Maks</th><th>Nilai</th></tr></thead>
                    <tbody>
                        @foreach($komponenB as $k)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $k['label'] }}</td>
                            <td style="text-align:right;color:var(--muted);font-size:12px;">{{ $k['maks'] }}</td>
                            <td>{{ $k['nilai'] ?? '-' }}</td>
                        </tr>
                        @endforeach
                        <tr class="nilai-total-row">
                            <td colspan="3">Total Section B</td>
                            <td>{{ $totalB }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-top:16px;background:var(--gold-lt);border:1px solid var(--gold-border);border-radius:10px;padding:12px 16px;display:flex;justify-content:space-between;align-items:center;">
                    @if(!$isPenguji)
                    <span style="font-size:13px;font-weight:700;color:#7C5C00;">Total Nilai (A + B)</span>
                    <span style="font-size:16px;font-weight:900;color:var(--gold);">{{ $totalA + $totalB }} / 200</span>
                    @else
                    <span style="font-size:13px;font-weight:700;color:#7C5C00;">Total Nilai Penguji</span>
                    <span style="font-size:16px;font-weight:900;color:var(--gold);">{{ $totalB }} / 100</span>
                    @endif
                </div>
            </div>

            {{-- Catatan Akademik --}}
            @php
            $semuaCatatan = collect();
            if ($nilaiPembimbing1 && $nilaiPembimbing1->catatan)
                $semuaCatatan->push(['nama' => $namaDospem1  ?? 'Pembimbing 1', 'peran' => 'Pembimbing 1', 'catatan' => $nilaiPembimbing1->catatan]);
            if ($nilaiPembimbing2 && $nilaiPembimbing2->catatan)
                $semuaCatatan->push(['nama' => $namaDospem2  ?? 'Pembimbing 2', 'peran' => 'Pembimbing 2', 'catatan' => $nilaiPembimbing2->catatan]);
            if ($nilaiPenguji1 && $nilaiPenguji1->catatan)
                $semuaCatatan->push(['nama' => $namaPenguji1 ?? 'Penguji 1',    'peran' => 'Penguji 1',    'catatan' => $nilaiPenguji1->catatan]);
            if ($nilaiPenguji2 && $nilaiPenguji2->catatan)
                $semuaCatatan->push(['nama' => $namaPenguji2 ?? 'Penguji 2',    'peran' => 'Penguji 2',    'catatan' => $nilaiPenguji2->catatan]);
            @endphp

            @if($semuaCatatan->count() > 0)
            <div class="catatan-section">
                <div class="catatan-head">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                    </svg>
                    Catatan Akademik
                    <span style="font-weight:400;font-size:11px;color:var(--muted);">Masukan dari dosen pembimbing dan penguji.</span>
                </div>
                @foreach($semuaCatatan as $cat)
                <div class="catatan-item">
                    <div class="catatan-avatar">{{ strtoupper(substr($cat['nama'], 0, 1)) }}</div>
                    <div style="flex:1;">
                        <div class="catatan-dosen-nama">{{ $cat['nama'] }}</div>
                        <div class="catatan-dosen-peran">{{ $cat['peran'] }}</div>
                        <div class="catatan-teks">"{{ $cat['catatan'] }}"</div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @endif {{-- end kondisi normal --}}
        </div>{{-- end konten kanan --}}
    </div>
    @endif

</div>
@endsection