@extends('layouts.app')

@section('content')

<style>
    :root {
        --gold: #C9A227;
        --gold-light: #FEF9EC;
        --gold-border: #F5D97A;
        --neutral: #1E293B;
        --muted: #6B7280;
        --border: #E5E7EB;
        --white: #ffffff;
        --bg: #F5F6FA;
        --radius: 16px;
    }

    body { background: var(--bg); }

    .detail-wrap {
        max-width: 900px;
        margin: 0 auto;
        padding: 28px 20px 48px;
    }

    /* ── BACK ── */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none !important;
        color: #B86A00 !important;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 20px;
    }

    .back-link i { font-size: 1rem; line-height: 1; }
    .back-link:hover { color: #C9A227 !important; }

    /* ── PAGE TITLE ── */
    .page-title {
        font-size: 1.7rem;
        font-weight: 800;
        color: var(--neutral);
        margin-bottom: 4px;
    }

    .page-sub {
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 28px;
    }

    /* ── INFO GRID ── */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 28px;
    }

    .info-box {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        position: relative;
        overflow: hidden;
    }

    .info-box::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--gold-border);
        border-radius: 4px 0 0 4px;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        background: var(--gold-light);
        border: 1.5px solid var(--gold-border);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .info-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 15px;
        font-weight: 700;
        color: var(--neutral);
    }

    /* Status badge inline */
    .status-inline {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 99px;
        font-size: 12px;
        font-weight: 600;
    }

    .s-menunggu { background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A; }
    .s-disetujui { background: #F0FDF4; color: #16A34A; border: 1px solid #BBF7D0; }
    .s-ditolak { background: #FFF1F2; color: #E11D48; border: 1px solid #FECDD3; }

    /* ── JUDUL CARDS ── */
    .judul-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px 24px 20px;
        margin-bottom: 16px;
        position: relative;
        overflow: hidden;
        transition: box-shadow .2s;
    }

    .judul-card:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,.07);
    }

    .judul-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--gold-border);
        border-radius: 4px 0 0 4px;
    }

    .judul-card.card-disetujui::before { background: #86EFAC; }
    .judul-card.card-ditolak::before   { background: #FCA5A5; }
    .judul-card.card-menunggu::before  { background: var(--gold-border); }

    .judul-card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .judul-nomor {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--neutral);
    }

    .judul-nama {
        font-size: 15px;
        font-weight: 700;
        color: var(--gold);
        margin-bottom: 18px;
        line-height: 1.5;
    }

    .judul-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px 24px;
    }

    .meta-item-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--muted);
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .meta-item-value {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--neutral);
    }

    @media (max-width: 640px) {
        .info-grid { grid-template-columns: 1fr; }
        .judul-meta { grid-template-columns: 1fr; }
        .page-title { font-size: 1.35rem; }
    }
</style>

<div class="detail-wrap">

    {{-- BACK --}}
    <a href="{{ url()->previous() }}" class="back-link">
        <i class="fa-solid fa-arrow-left"></i>
        Kembali
    </a>

    {{-- TITLE --}}
    <div class="page-title">Detail Pengajuan Judul</div>
    <div class="page-sub">Informasi detail pengajuan judul tugas akhir yang telah diajukan.</div>

    {{-- INFO GRID --}}
    <div class="info-grid">

        {{-- Tanggal --}}
        <div class="info-box">
            <div class="info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none"
                    viewBox="0 0 24 24" stroke="#C9A227" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <div>
                <div class="info-label">Tanggal Pengajuan</div>
                <div class="info-value">
                    {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d M Y') }}
                </div>
            </div>
        </div>

        {{-- NIM --}}
        <div class="info-box">
            <div class="info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none"
                    viewBox="0 0 24 24" stroke="#C9A227" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <div class="info-label">NIM</div>
                <div class="info-value">{{ $pengajuan->nim_nid }}</div>
            </div>
        </div>

        {{-- Status --}}
        <div class="info-box">
            <div class="info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none"
                    viewBox="0 0 24 24" stroke="#C9A227" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="info-label">Status Keseluruhan</div>
                @php $st = strtolower($pengajuan->status); @endphp
                @if($st === 'disetujui')
                    <span class="status-inline s-disetujui">Disetujui</span>
                @elseif($st === 'ditolak')
                    <span class="status-inline s-ditolak">Ditolak</span>
                @else
                    <span class="status-inline s-menunggu">Menunggu verifikasi</span>
                @endif
            </div>
        </div>

        {{-- Mahasiswa --}}
        <div class="info-box">
            <div class="info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none"
                    viewBox="0 0 24 24" stroke="#C9A227" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <div class="info-label">Mahasiswa</div>
                <div class="info-value">{{ session('user')->nama ?? session('user')->name ?? '-' }}</div>
            </div>
        </div>

    </div>

    {{-- JUDUL CARDS --}}
    @php
        $judulList = [
            ['nomor' => 1, 'judul' => $pengajuan->judul_1, 'topik' => $pengajuan->topik_1, 'mitra' => $pengajuan->mitra_1 ?? null],
            ['nomor' => 2, 'judul' => $pengajuan->judul_2, 'topik' => $pengajuan->topik_2, 'mitra' => $pengajuan->mitra_2 ?? null],
            ['nomor' => 3, 'judul' => $pengajuan->judul_3, 'topik' => $pengajuan->topik_3, 'mitra' => $pengajuan->mitra_3 ?? null],
        ];

        $statusUtama    = strtolower($pengajuan->status);
        $judulDisetujui = $pengajuan->judul_disetujui ?? null;
    @endphp

    @foreach($judulList as $item)
        @php
            if ($statusUtama === 'ditolak') {
                $statusCard = 'ditolak';
            } elseif ($statusUtama === 'disetujui') {
                $statusCard = ($item['judul'] === $judulDisetujui) ? 'disetujui' : 'ditolak';
            } else {
                $statusCard = 'menunggu';
            }
        @endphp

        <div class="judul-card card-{{ $statusCard }}">
            <div class="judul-card-head">
                <div class="judul-nomor">Usulan Judul {{ $item['nomor'] }}</div>
                @if($statusCard === 'disetujui')
                    <span class="status-inline s-disetujui">Disetujui</span>
                @elseif($statusCard === 'ditolak')
                    <span class="status-inline s-ditolak">Ditolak</span>
                @else
                    <span class="status-inline s-menunggu">Menunggu Verifikasi</span>
                @endif
            </div>

            <div class="judul-nama">{{ $item['judul'] }}</div>

            <div class="judul-meta">
                <div>
                    <div class="meta-item-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        Bidang Minat (Topic)
                    </div>
                    <div class="meta-item-value">{{ $item['topik'] ?? '-' }}</div>
                </div>
                <div>
                    <div class="meta-item-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Instansi/Mitra
                    </div>
                    <div class="meta-item-value">{{ $item['mitra'] ?? '-' }}</div>
                </div>
            </div>
        </div>
    @endforeach

</div>

@endsection
