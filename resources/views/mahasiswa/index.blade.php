@extends('layouts.app')

@section('content')

{{-- LOADING OVERLAY --}}
<div id="loadingOverlay" style="
    position:fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(255,255,255,0.9);
    z-index:9999;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
">
    <div class="spinner-border mb-3" role="status" style="width:3rem;height:3rem;color:#FACC15;border-width:3px;">
        <span class="visually-hidden">Loading...</span>
    </div>
    <p style="color:#6b7280;font-weight:600;font-family:'Hanken Grotesk',sans-serif;">Sedang memproses data...</p>
</div>

<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary: #FFE083;
        --primary-dark: #d4a00e;
        --neutral: #1E293B;
        --tertiary: #FFFDF5;
        --text-muted: #6b7280;
        --card-bg: #ffffff;
        --border-radius: 16px;
    }

    body,
    .sitasi-wrap *:not(i) {
        font-family: 'Hanken Grotesk', sans-serif !important;
    }

    .sitasi-wrap {
        background: #f5f6fa;
        min-height: 100vh;
    }

    /* ── HERO ── */
    .hero-card {
        background-image: url('{{ asset('images/bg.jpeg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 20px;
        padding: 44px 48px;
        position: relative;
        overflow: hidden;
        margin-bottom: 0;
        min-height: 220px;
        display: flex;
        align-items: center;
    }

    .hero-body {
        position: relative;
        z-index: 2;
    }

    .hero-card h1 {
        font-size: 1.75rem;
        font-weight: 800;
        color: #735C00;
        line-height: 1.25;
        margin-bottom: 20px;
    }

    .btn-hero-primary {
        background: var(--primary);
        color: var(--neutral);
        border: none;
        border-radius: 10px;
        padding: 9px 22px;
        font-weight: 700;
        font-size: 0.82rem;
        text-decoration: none;
        transition: 0.2s;
        display: inline-block;
    }

    .btn-hero-primary:hover {
        background: #d2bf75;
        color: var(--neutral);
        text-decoration: none;
    }

    .btn-hero-outline {
        background: transparent;
        color: var(--neutral);
        border: 1.5px solid #d1d5db;
        border-radius: 10px;
        padding: 9px 22px;
        font-weight: 600;
        font-size: 0.82rem;
        text-decoration: none;
        transition: 0.2s;
        display: inline-block;
    }

    .btn-hero-outline:hover {
        background: rgba(0, 0, 0, 0.05);
        color: var(--neutral);
        text-decoration: none;
    }

    /* ── STAT ROW ── */
    .stat-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-top: 18px;
        margin-bottom: 24px;
        background: transparent;
        padding: 0;
        box-shadow: none;
        border-radius: 0;
    }

    .stat-item {
        background: #ffffff;
        border-radius: 18px;
        padding: 18px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        min-height: 96px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: 0.25s ease;
    }

    .stat-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    }

    .stat-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #fffdf3;
        border: 1.5px solid #f4e28a;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-content {
        display: flex;
        flex-direction: column;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 0.82rem;
        color: #64748b;
        font-weight: 500;
        line-height: 1.3;
    }

    /* ── MIDDLE ROW ── */
    .middle-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        align-items: flex-start;
    }

    .proposal-card {
        background: #fff;
        border-radius: var(--border-radius);
        padding: 22px 24px;
        flex: 1;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        min-width: 0;
    }

    .proposal-label {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 1.2px;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .proposal-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--neutral);
        margin-bottom: 18px;
        line-height: 1.35;
    }

    .dosen-row {
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
    }

    .dosen-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .dosen-avatar {
        width: 30px;
        height: 30px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
    }

    .dosen-role {
        font-size: 0.65rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .dosen-name {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--neutral);
    }

    .proposal-time {
        margin-top: 14px;
        font-size: 0.7rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* AKTIVITAS */
    .aktivitas-card {
        background: #fff;
        border-radius: var(--border-radius);
        padding: 18px;
        width: 240px;
        flex-shrink: 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .aktivitas-card h6 {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--neutral);
        margin-bottom: 12px;
    }

    .aktivitas-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 8px 0;
        border-bottom: 1px solid #f5f5f5;
    }

    .aktivitas-item:last-of-type {
        border-bottom: none;
    }

    .aktivitas-bar {
        width: 4px;
        min-height: 36px;
        border-radius: 4px;
        background: var(--primary);
        flex-shrink: 0;
        margin-top: 2px;
    }

    .aktivitas-text {
        font-size: 0.74rem;
        color: var(--neutral);
        font-weight: 600;
        line-height: 1.4;
    }

    .aktivitas-time {
        font-size: 0.63rem;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .btn-lihat-semua {
        display: block;
        text-align: center;
        margin-top: 10px;
        padding: 8px;
        border: 1.5px solid var(--primary);
        border-radius: 10px;
        font-size: 0.74rem;
        font-weight: 600;
        color: var(--neutral);
        text-decoration: none;
        background: transparent;
        transition: 0.2s;
    }

    .btn-lihat-semua:hover {
        background: var(--primary);
        color: var(--neutral);
        text-decoration: none;
    }

    /* ── ALUR ── */
    .alur-section {
        background: #fff;
        border-radius: var(--border-radius);
        border: 1.5px solid #e5e7eb;
        padding: 20px 24px;
        margin-bottom: 24px;
    }

    .alur-section h6 {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--neutral);
        margin-bottom: 16px;
    }

    .alur-steps {
        display: flex;
        align-items: center;
    }

    .alur-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
    }

    .alur-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 14px;
        left: calc(50% + 14px);
        width: calc(100% - 28px);
        height: 2px;
        background: #e5e7eb;
    }

    .alur-step.done:not(:last-child)::after {
        background: #FACC15;
    }

    .step-circle {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid #e5e7eb;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        color: #aaa;
        z-index: 1;
        position: relative;
    }

    .alur-step.done .step-circle {
        background: #FACC15;
        border-color: #FACC15;
        color: var(--neutral);
    }

    .alur-step.active .step-circle {
        background: #fff;
        border-color: #FACC15;
        border-width: 2px;
        border-style: dashed;
        color: var(--neutral);
        box-shadow: 0 0 0 3px rgba(250, 204, 21, 0.25);
    }

    .alur-step.active .step-circle::after {
        content: '';
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #FACC15;
        position: absolute;
    }

    .step-label {
        font-size: 0.6rem;
        font-weight: 600;
        color: #aaa;
        text-align: center;
        margin-top: 7px;
        line-height: 1.3;
    }

    .alur-step.done .step-label {
        color: var(--neutral);
    }

    .alur-step.active .step-label {
        color: var(--neutral);
        font-weight: 700;
    }

    /* ── MENU GRID ── */
    .menu-section {
        margin-bottom: 24px;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
    }

    @media (max-width: 900px) {
        .menu-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .stat-row {
            grid-template-columns: 1fr;
        }

        .middle-row {
            flex-direction: column;
        }

        .aktivitas-card {
            width: 100%;
        }

        .hero-card {
            padding: 28px 24px;
        }

        .hero-card h1 {
            font-size: 1.4rem;
        }
    }

    .menu-card-item {
        background: #fff;
        border-radius: 14px;
        padding: 0;
        text-align: center;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: 0.25s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1.5px solid #f0f0f0;
        overflow: visible;
        position: relative;
    }

    .menu-card-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border-color: #f0f0f0;
        color: inherit;
        text-decoration: none;
    }

    .menu-card-item .card-icon-wrap {
        width: 100%;
        height: 180px;
        background: #f3f4f7;
        border-radius: 14px 14px 0 0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .menu-card-item img {
        height: 120px;
        width: 120px;
        object-fit: contain;
    }

    .menu-card-item .card-text {
        padding: 16px 16px 18px 16px;
        width: 100%;
    }

    .menu-card-item h6 {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--neutral);
        margin-bottom: 4px;
    }

    .menu-card-item p {
        font-size: 0.72rem;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.4;
    }

    /* ── Badge notif card ── */
    .card-badge-notif {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 12px;
        height: 12px;
        background: red;
        border-radius: 50%;
        display: inline-block;
        z-index: 10;
    }

    /* ── STATUS TA ── */
    .status-section {
        background: #fff;
        border-radius: var(--border-radius);
        padding: 22px 24px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
    }

    .status-section h5 {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--neutral);
        margin-bottom: 16px;
    }

    .status-table td {
        font-size: 0.82rem;
        color: var(--neutral);
        padding: 7px 0;
        vertical-align: middle;
        border: none;
    }

    .status-table td:first-child {
        color: var(--text-muted);
        width: 130px;
        font-weight: 500;
    }

    .status-table td:nth-child(2) {
        color: var(--text-muted);
        width: 18px;
    }
</style>

<div class="sitasi-wrap">

    {{-- HERO --}}
    <div class="hero-card">
        <div class="hero-body">
            <h1>Sistem Bimbingan Tugas Akhir Mahasiswa<br>Sistem Informasi</h1>
            <div class="d-flex gap-2 flex-wrap">
                <a href="#" class="btn-hero-primary">Pelajari Alur TA</a>
                <a href="#" class="btn-hero-outline">Unduh Pedoman</a>
            </div>
        </div>
    </div>

    {{-- STATS --}}
    <div class="stat-row">

        <div class="stat-item">
            <div class="stat-icon-wrap">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="#2563eb" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="16" y1="13" x2="8" y2="13" />
                    <line x1="16" y1="17" x2="8" y2="17" />
                </svg>
            </div>
            <div>
                <span class="stat-number">{{ $totalPengajuan }}</span>
                <span class="stat-label">Total Pengajuan Judul</span>
            </div>
        </div>

        <div class="stat-item">
            <div class="stat-icon-wrap">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="#16a34a" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="17 8 12 3 7 8" />
                    <line x1="12" y1="3" x2="12" y2="15" />
                </svg>
            </div>
            <div>
                <span class="stat-number">{{ $totalProposal }}</span>
                <span class="stat-label">Total Upload Proposal</span>
            </div>
        </div>

        <div class="stat-item">
            <div class="stat-icon-wrap">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="#f59e0b" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
            </div>
            <div>
                <span class="stat-number">{{ $totalBimbingan ?? 0 }}</span>
                <span class="stat-label">Total Bimbingan</span>
            </div>
        </div>

    </div>

    {{-- PROPOSAL + AKTIVITAS --}}
    <div class="middle-row">
        <div class="proposal-card">
            <div class="proposal-label">Proposal Aktif</div>
            <div class="proposal-title">Implementasi Deep Learning untuk Klasifikasi Citra Medis Berbasis Web</div>
            <div class="dosen-row">
                <div class="dosen-item">
                    <div class="dosen-avatar">🎓</div>
                    <div>
                        <div class="dosen-role">Dosen Pembimbing 1</div>
                        <div class="dosen-name">Prof. Dr. Suharyanto, M.Eng</div>
                    </div>
                </div>
                <div class="dosen-item">
                    <div class="dosen-avatar">🎓</div>
                    <div>
                        <div class="dosen-role">Dosen Pembimbing 2</div>
                        <div class="dosen-name">Prof. Dr. Suharyanto, M.Eng</div>
                    </div>
                </div>
            </div>
            <div class="proposal-time">
                🕐 Terakhir diperbarui: 2 jam yang lalu
            </div>
        </div>

        <div class="aktivitas-card">
            <h6>Aktivitas Terbaru</h6>
            <div class="aktivitas-item">
                <div class="aktivitas-bar" style="background:#FACC15;"></div>
                <div>
                    <div class="aktivitas-text">Proposal berhasil diunggah</div>
                    <div class="aktivitas-time">Hari ini, 10:30 AM</div>
                </div>
            </div>
            <div class="aktivitas-item">
                <div class="aktivitas-bar" style="background:#e2e8f0;"></div>
                <div>
                    <div class="aktivitas-text">Pembimbing telah ditentukan</div>
                    <div class="aktivitas-time">Kemarin</div>
                </div>
            </div>
            <div class="aktivitas-item">
                <div class="aktivitas-bar" style="background:#e2e8f0;"></div>
                <div>
                    <div class="aktivitas-text">Judul TA Disetujui</div>
                    <div class="aktivitas-time">2 hari yang lalu</div>
                </div>
            </div>
            <div class="aktivitas-item">
                <div class="aktivitas-bar" style="background:#e2e8f0;"></div>
                <div>
                    <div class="aktivitas-text">Pengajuan Judul TA Baru</div>
                    <div class="aktivitas-time">1 minggu yang lalu</div>
                </div>
            </div>
            <a href="#" class="btn-lihat-semua">Lihat Semua Riwayat</a>
        </div>
    </div>

    {{-- ALUR KEMAJUAN --}}
    <div class="alur-section">
        <h6>Alur Kemajuan Tugas Akhir</h6>
        <div class="alur-steps">
            <div class="alur-step done">
                <div class="step-circle">✓</div>
                <div class="step-label">Pengajuan<br>Judul</div>
            </div>
            <div class="alur-step done">
                <div class="step-circle">✓</div>
                <div class="step-label">Upload Proposal<br>& Usulan Pembimbing</div>
            </div>
            <div class="alur-step active">
                <div class="step-circle"></div>
                <div class="step-label">Verifikasi<br>Pembimbing</div>
            </div>
            <div class="alur-step">
                <div class="step-circle">4</div>
                <div class="step-label">Review<br>Proposal</div>
            </div>
            <div class="alur-step">
                <div class="step-circle">5</div>
                <div class="step-label">Seminar<br>Proposal</div>
            </div>
        </div>
    </div>

    {{-- MENU CARDS --}}
    <div class="menu-section">
        <div class="menu-grid">

            {{-- CARD PENGAJUAN JUDUL --}}
            <a href="{{ session('user') ? route('pengajuan.mahasiswa') : '/login' }}" class="menu-card-item">
                @if(($notifJudulMahasiswa ?? 0) > 0)
                    <span class="card-badge-notif"></span>
                @endif
                <div class="card-icon-wrap">
                    <img src="{{ asset('images/ta.jpeg') }}" alt="Pengajuan Judul">
                </div>
                <div class="card-text">
                    <h6>Pengajuan Judul</h6>
                    <p>Ajukan judul dan topik Tugas Akhir</p>
                </div>
            </a>

            {{-- CARD UPLOAD PROPOSAL --}}
            <a href="{{ route('proposal.mahasiswa') }}" class="menu-card-item">
                @if(($notifProposalMahasiswa ?? 0) > 0)
                    <span class="card-badge-notif"></span>
                @endif
                <div class="card-icon-wrap">
                    <img src="{{ asset('images/proposal.jpeg') }}" alt="Upload Proposal">
                </div>
                <div class="card-text">
                    <h6>Upload Proposal</h6>
                    <p>Kirim proposal untuk di review</p>
                </div>
            </a>

            {{-- CARD RIWAYAT BIMBINGAN --}}
            <a href="#" class="menu-card-item">
                <div class="card-icon-wrap">
                    <img src="{{ asset('images/bimbingan.jpeg') }}" alt="Bimbingan">
                </div>
                <div class="card-text">
                    <h6>Riwayat Bimbingan</h6>
                    <p>Riwayat Bimbingan TA1</p>
                </div>
            </a>

            {{-- CARD JADWAL --}}
            <a href="#" class="menu-card-item">
                <div class="card-icon-wrap">
                    <img src="{{ asset('images/jadwal.jpeg') }}" alt="Jadwal">
                </div>
                <div class="card-text">
                    <h6>Jadwal</h6>
                    <p>Pelaksanaan TA & Seminar</p>
                </div>
            </a>

            {{-- CARD DAFTAR SEMINAR --}}
            <a href="#" class="menu-card-item">
                <div class="card-icon-wrap">
                    <img src="{{ asset('images/mahasiswa.jpeg') }}" alt="Daftar Seminar">
                </div>
                <div class="card-text">
                    <h6>Daftar Seminar</h6>
                    <p>Ajukan Pendaftaran Seminar</p>
                </div>
            </a>

            {{-- CARD NILAI --}}
            <a href="#" class="menu-card-item">
                <div class="card-icon-wrap">
                    <img src="{{ asset('images/penilaian.jpeg') }}" alt="Nilai">
                </div>
                <div class="card-text">
                    <h6>Nilai</h6>
                    <p>Lihat Hasil Penilaian TA dan Seminar</p>
                </div>
            </a>

        </div>
    </div>

    {{-- STATUS TA --}}
    <div class="status-section">
        <h5>Status Tugas Akhir</h5>
        @if(session('user'))
        <table class="status-table table mb-0">
            <tr>
                <td>Status</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Topik</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Bimbingan</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Seminar</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Sidang</td>
                <td>:</td>
                <td>-</td>
            </tr>
        </table>
        @else
        <div class="text-center py-4">
            <i class="fa-solid fa-lock mb-3" style="font-size:28px;color:#d1d5db;"></i>
            <p style="color:#9ca3af;font-size:0.85rem;margin-bottom:16px;">
                Silahkan login untuk melihat progres tugas akhir anda
            </p>
            <a href="/login" style="background:var(--primary);color:var(--neutral);border:none;border-radius:10px;padding:10px 28px;font-weight:700;font-size:0.85rem;text-decoration:none;">
                Login Sekarang
            </a>
        </div>
        @endif
    </div>

</div>

<script>
    window.addEventListener("load", function() {
        document.getElementById("loadingOverlay").style.display = "none";
    });
</script>

@endsection
