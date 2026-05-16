@extends('layouts.app')

@section('content')

<style>
    .detail-wrapper {
        max-width: 900px;
        margin: auto;
        padding: 24px 0 48px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #555;
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 600;
        margin-bottom: 16px;
    }

    .back-link:hover {
        color: #B86A00;
    }

    .page-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #111;
        margin-bottom: 4px;
    }

    .page-subtitle {
        font-size: 0.84rem;
        color: #94a3b8;
        margin-bottom: 28px;
    }

    /* SECTION */
    .section-block {
        margin-bottom: 28px;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.95rem;
        font-weight: 800;
        color: #111;
        margin-bottom: 14px;
    }

    .section-heading::before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 18px;
        background: #FACC15;
        border-radius: 4px;
    }

    /* INFO CARDS */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 14px;
        margin-bottom: 14px;
    }

    .info-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .info-card {
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 14px;
        padding: 16px 18px;
    }

    .info-card .ic-label {
        font-size: 0.72rem;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 6px;
    }

    .info-card .ic-value {
        font-size: 0.92rem;
        font-weight: 700;
        color: #111;
        line-height: 1.3;
    }

    /* STATUS BADGE */
    .status-pill {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .s-selesai {
        background: #d4edda;
        color: #28a745;
    }

    .s-menunggu {
        background: #fff3cd;
        color: #856404;
    }

    .s-menunggu-review {
        background: #cce5ff;
        color: #004085;
    }

    .s-ditolak {
        background: #f8d7da;
        color: #dc3545;
    }

    /* MAHASISWA CHIP */
    .mhs-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .mhs-avatar {
        width: 32px;
        height: 32px;
        background: #e2e8f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.85rem;
        color: #475569;
        flex-shrink: 0;
    }

    .mhs-name {
        font-size: 0.88rem;
        font-weight: 700;
        color: #111;
    }

    .mhs-nim {
        font-size: 0.74rem;
        color: #94a3b8;
    }

    /* FILE CARD */
    .file-card {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .file-icon {
        width: 36px;
        height: 36px;
        background: #fee2e2;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .file-name {
        font-size: 0.84rem;
        font-weight: 700;
        color: #111;
    }

    .file-meta {
        font-size: 0.72rem;
        color: #94a3b8;
    }

    .btn-download {
        margin-left: auto;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: #475569;
        flex-shrink: 0;
        transition: background 0.15s;
    }

    .btn-download:hover {
        background: #e2e8f0;
        color: #111;
    }

    /* PEMBIMBING GRID */
    .pb-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .pb-card {
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 14px;
        padding: 16px 18px;
    }

    .pb-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .pb-type-badge {
        font-size: 0.68rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 6px;
        background: #e0f2fe;
        color: #0369a1;
    }

    .pb-date-small {
        font-size: 0.72rem;
        color: #94a3b8;
    }

    .pb-avatar {
        width: 40px;
        height: 40px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        position: relative;
    }

    .pb-avatar .check-dot {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 14px;
        height: 14px;
        background: #22c55e;
        border-radius: 50%;
        border: 2px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pb-name {
        font-size: 0.9rem;
        font-weight: 700;
        color: #111;
    }

    .pb-nidn {
        font-size: 0.76rem;
        color: #94a3b8;
    }

    /* USULAN BADGE */
    .badge-disetujui {
        background: #d4edda;
        color: #28a745;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 0.68rem;
        font-weight: 700;
    }

    .badge-ditolak {
        background: #f8d7da;
        color: #dc3545;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 0.68rem;
        font-weight: 700;
    }

    .badge-menunggu {
        background: #fff3cd;
        color: #856404;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 0.68rem;
        font-weight: 700;
    }

    /* TINJAUAN */
    .tinjauan-card {
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 12px;
    }

    .tinjauan-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .tinjauan-icon {
        width: 36px;
        height: 36px;
        background: #fef9c3;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .tinjauan-catatan {
        font-size: 0.88rem;
        color: #333;
        line-height: 1.7;
        margin-bottom: 14px;
    }

    .tinjauan-file-row {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f8fafc;
        border-radius: 10px;
        padding: 10px 14px;
    }

    .tinjauan-file-icon {
        width: 30px;
        height: 30px;
        background: #fee2e2;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .tinjauan-file-name {
        font-size: 0.8rem;
        font-weight: 700;
        color: #111;
    }

    .tinjauan-file-meta {
        font-size: 0.7rem;
        color: #94a3b8;
    }

    .btn-unduh {
        margin-left: auto;
        background: #1e293b;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 7px 16px;
        font-size: 0.78rem;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition: background 0.15s;
    }

    .btn-unduh:hover {
        background: #334155;
        color: #fff;
    }

    @media(max-width:768px) {

        .info-grid,
        .pb-grid {
            grid-template-columns: 1fr;
        }

        .info-grid-2 {
            grid-template-columns: 1fr;
        }
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: #B86A00;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .back-link i {
        font-size: 1rem;
        line-height: 1;
    }
</style>

<div class="container">
    <div class="detail-wrapper">

        {{-- BACK --}}
        <a href="{{ route('proposal.mahasiswa') }}" class="back-link">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali
        </a>

        <h2 class="page-title">Detail Dosen Pembimbing dan Proposal</h2>
        <p class="page-subtitle">Informasi detail dosen pembimbing, proposal, dan review proposal Anda.</p>

        {{-- INFORMASI PROPOSAL --}}
        <div class="section-block">
            <div class="section-heading">Informasi Proposal</div>

            {{-- ROW 1: Tanggal | Status | Mahasiswa --}}
            <div class="info-grid mb-3">
                <div class="info-card">
                    <div class="ic-label">Tanggal Pengajuan</div>
                    <div class="ic-value">{{ \Carbon\Carbon::parse($proposal->tanggal_pengajuan)->translatedFormat('d M Y') }}</div>
                </div>
                <div class="info-card">
                    <div class="ic-label">Status</div>
                    @php $st = strtolower($proposal->status); @endphp
                    <div>
                        @if($st === 'selesai')
                        <span class="status-pill s-selesai">Selesai</span>
                        @elseif($st === 'menunggu_review')
                        <span class="status-pill s-menunggu-review">Menunggu Review</span>
                        @elseif($st === 'ditolak')
                        <span class="status-pill s-ditolak">Ditolak</span>
                        @else
                        <span class="status-pill s-menunggu">Menunggu Verifikasi</span>
                        @endif
                    </div>
                </div>
                <div class="info-card">
                    <div class="ic-label">Mahasiswa</div>
                    <div class="mhs-chip">
                        <div class="mhs-avatar">{{ strtoupper(substr(session('user')->nama ?? 'M', 0, 1)) }}</div>
                        <div>
                            <div class="mhs-name">{{ session('user')->nama ?? '-' }}</div>
                            <div class="mhs-nim">{{ $proposal->nim_nid }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ROW 2: Judul | File --}}
            <div class="info-grid-2">
                <div class="info-card">
                    <div class="ic-label">Judul Proposal</div>
                    <div class="ic-value" style="font-size:0.88rem; line-height:1.5;">{{ $proposal->judul }}</div>
                </div>
                <div class="info-card">
                    <div class="ic-label">Proposal</div>
                    @if($proposal->file_proposal)
                    <div class="file-card">
                        <div class="file-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#dc3545" viewBox="0 0 16 16">
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="file-name">{{ basename($proposal->file_proposal) }}</div>
                            <div class="file-meta">
                                · Diunggah {{ \Carbon\Carbon::parse($proposal->created_at)->translatedFormat('d M Y') }}
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $proposal->file_proposal) }}" target="_blank" class="btn-download">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                            </svg>
                        </a>
                    </div>
                    @else
                    <div class="ic-value">-</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- USULAN PEMBIMBING --}}
        @if($proposal->usulanPembimbing->count() > 0)
        <div class="section-block">
            <div class="section-heading">Usulan Pembimbing</div>
            <div class="pb-grid">
                @foreach($proposal->usulanPembimbing->sortBy('urutan') as $usulan)
                @php $dosen = \DB::table('users')->where('nim_nid', $usulan->nim_nid_dosen)->first(); @endphp
                <div class="pb-card">
                    <div class="pb-top">
                        <span style="font-size:0.72rem; color:#94a3b8; font-weight:600;">USULAN PEMBIMBING {{ $usulan->urutan }}</span>
                        @if($usulan->status === 'disetujui')
                        <span class="badge-disetujui">Disetujui</span>
                        @elseif($usulan->status === 'ditolak')
                        <span class="badge-ditolak">Ditolak</span>
                        @else
                        <span class="badge-menunggu">Menunggu</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="pb-avatar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#94a3b8" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4z" />
                            </svg>
                        </div>
                        <div>
                            <div class="pb-name">{{ $dosen->nama ?? $usulan->nim_nid_dosen }}</div>
                            <div class="pb-nidn">NIDN. {{ $dosen->nim_nid ?? '-' }}</div>
                            <div style="font-size:0.72rem; color:#94a3b8; margin-top:2px;">
                                Diusulkan {{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->translatedFormat('d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- DOSEN PEMBIMBING FINAL --}}
        @if($proposal->dosenPembimbing->count() > 0)
        <div class="section-block">
            <div class="section-heading">Dosen Pembimbing Utama</div>
            <p style="font-size:0.82rem; color:#94a3b8; margin-top:-10px; margin-bottom:14px;">Dosen Pembimbing yang telah ditetapkan untuk Tugas Akhir Anda</p>
            <div class="pb-grid">
                @foreach($proposal->dosenPembimbing->sortBy('urutan') as $dosbing)
                @php $dosen = \DB::table('users')->where('nim_nid', $dosbing->nim_nid_dosen)->first(); @endphp
                <div class="pb-card">
                    <div class="pb-top">
                        <span class="pb-type-badge">Pembimbing TA {{ $dosbing->urutan }}</span>
                        <span class="pb-date-small">· Ditetapkan {{ \Carbon\Carbon::parse($dosbing->tanggal_penetapan)->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="pb-avatar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#94a3b8" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4z" />
                            </svg>
                            <div class="check-dot">
                                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" fill="#fff" viewBox="0 0 16 16">
                                    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <div class="pb-name">{{ $dosen->nama ?? $dosbing->nim_nid_dosen }}</div>
                            <div class="pb-nidn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="#94a3b8" viewBox="0 0 16 16" style="margin-right:3px;">
                                    <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5z" />
                                </svg>
                                NIDN. {{ $dosen->nim_nid ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- TINJAUAN PROPOSAL --}}
        <div class="section-block">
            <div class="section-heading">Tinjauan Proposal</div>
            <p style="font-size:0.82rem; color:#94a3b8; margin-top:-10px; margin-bottom:14px;">Informasi hasil review dan evaluasi proposal Anda.</p>

            @forelse($proposal->tinjauanProposal as $tinjauan)
            <div class="tinjauan-card">
                <div class="tinjauan-top">
                    <div class="d-flex align-items-center gap-3">
                        <div class="tinjauan-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#d4a01e" viewBox="0 0 16 16">
                                <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z" />
                            </svg>
                        </div>
                        <span style="font-size:0.88rem; font-weight:700; color:#111;">Catatan Reviewer</span>
                    </div>
                    <span style="font-size:0.78rem; color:#94a3b8;">{{ \Carbon\Carbon::parse($tinjauan->tanggal_tinjauan)->translatedFormat('d M Y') }}</span>
                </div>

                <div class="tinjauan-catatan">{{ $tinjauan->catatan }}</div>

                @if($tinjauan->file_tinjauan)
                <div class="tinjauan-file-row">
                    <div class="tinjauan-file-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#dc3545" viewBox="0 0 16 16">
                            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="tinjauan-file-name">{{ basename($tinjauan->file_tinjauan) }}</div>
                        <div class="tinjauan-file-meta">· Signed by Reviewer</div>
                    </div>
                    <a href="{{ asset('storage/' . $tinjauan->file_tinjauan) }}" target="_blank" class="btn-unduh">
                        Unduh Hasil Review
                    </a>
                </div>
                @endif
            </div>
            @empty
            <div class="tinjauan-card text-center" style="color:#94a3b8; font-size:0.88rem; padding:32px;">
                Belum ada tinjauan dari dosen pembimbing.
            </div>
            @endforelse
        </div>

    </div>
</div>

@endsection