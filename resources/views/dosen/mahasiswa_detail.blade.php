@extends('layouts.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --gold: #A16207;
        --gold-mid: #FACC15;
        --neutral: #1E293B;
        --muted: #6B7280;
        --border: #E5E7EB;
        --white: #ffffff;
        --bg: #F5F6FA;
    }
    .det-wrap * { font-family: 'Hanken Grotesk', sans-serif !important; box-sizing: border-box; }
    .det-wrap { background: var(--bg); min-height: 100vh; }

    .det-back {
        display: inline-flex; align-items: center; gap: 6px;
        color: var(--gold); font-size: 0.85rem; font-weight: 600;
        text-decoration: none; margin-bottom: 20px;
    }
    .det-back:hover { text-decoration: underline; color: var(--gold); }

    .det-profile-outer {
        display: flex; gap: 20px; margin-bottom: 20px; align-items: stretch; flex-wrap: wrap;
    }

    .det-profile-card {
        background: var(--white); border-radius: 16px;
        padding: 28px 32px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        display: flex; gap: 24px; align-items: flex-start;
        flex: 1; min-width: 300px;
        border-left: 4px solid var(--gold-mid);
    }

    .det-avatar {
        width: 110px; height: 130px; border-radius: 10px;
        object-fit: cover; flex-shrink: 0;
    }
    .det-avatar-placeholder {
        width: 110px; height: 130px; border-radius: 10px;
        background: #F1F5F9; display: flex; align-items: center;
        justify-content: center; flex-shrink: 0;
    }

    .det-profile-info { flex: 1; }
    .det-badge-aktif {
        display: inline-block; padding: 3px 12px;
        background: #DCFCE7; color: #16A34A;
        border-radius: 20px; font-size: 0.72rem; font-weight: 700;
        margin-bottom: 10px;
    }
    .det-nama { font-size: 1.4rem; font-weight: 800; color: var(--neutral); margin-bottom: 12px; }
    .det-meta { display: flex; gap: 40px; flex-wrap: wrap; margin-bottom: 14px; }
    .det-meta-item label {
        font-size: 0.68rem; font-weight: 700; color: var(--muted);
        text-transform: uppercase; letter-spacing: 0.8px;
        display: block; margin-bottom: 3px;
    }
    .det-meta-item span { font-size: 0.95rem; font-weight: 700; color: var(--neutral); }
    .det-judul-label {
        font-size: 0.68rem; font-weight: 700; color: var(--muted);
        text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 4px;
    }
    .det-judul { font-size: 0.9rem; font-weight: 700; color: var(--gold); font-style: italic; line-height: 1.4; }

    .det-dosbing-card {
        background: var(--white); border-radius: 16px;
        padding: 28px 32px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        min-width: 260px;
        border-left: 4px solid var(--gold-mid);
    }
    .det-dosbing-label {
        font-size: 0.68rem; font-weight: 700; color: var(--muted);
        text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px;
    }
    .det-dosbing-item { margin-bottom: 14px; }
    .det-dosbing-item:last-child { margin-bottom: 0; }
    .det-dosbing-item .sub { font-size: 0.7rem; color: var(--muted); font-weight: 500; margin-bottom: 2px; }
    .det-dosbing-item .nama { font-size: 0.95rem; font-weight: 700; color: var(--neutral); }

    /* ── PROGRESS STEPS ── */
    .det-steps-card {
        background: var(--white); border-radius: 16px;
        padding: 28px 32px; margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .det-steps-header {
        display: flex; justify-content: space-between; align-items: flex-start;
        margin-bottom: 28px; flex-wrap: wrap; gap: 12px;
    }
    .det-steps-title { font-size: 1rem; font-weight: 800; color: var(--neutral); margin-bottom: 4px; }
    .det-steps-sub { font-size: 0.78rem; color: var(--muted); }
    .det-steps-legend { display: flex; gap: 16px; align-items: center; flex-shrink: 0; padding-top: 2px; flex-wrap: wrap; }
    .det-legend-item { display: flex; align-items: center; gap: 6px; font-size: 0.72rem; font-weight: 600; }
    .det-legend-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; flex-shrink: 0; }

    .det-steps { display: flex; align-items: flex-start; overflow-x: auto; padding-bottom: 8px; }
    .det-step { display: flex; flex-direction: column; align-items: center; flex: 1; min-width: 80px; position: relative; }

    /* Garis antar step */
    .det-step:not(:last-child)::after {
        content: ''; position: absolute;
        top: 18px; left: 50%; width: 100%; height: 2px;
        background: #E5E7EB; z-index: 0;
    }
    .det-step.done:not(:last-child)::after { background: #22C55E; }
    .det-step.current:not(:last-child)::after { background: #E5E7EB; }

    /* Icon step */
    .det-step-icon {
        width: 36px; height: 36px; border-radius: 50%;
        background: #F1F5F9; border: 2px solid #E5E7EB;
        display: flex; align-items: center; justify-content: center;
        position: relative; z-index: 1; margin-bottom: 10px; flex-shrink: 0;
    }
    .det-step.done .det-step-icon    { background: #22C55E; border-color: #22C55E; }
    .det-step.current .det-step-icon { background: #FACC15; border-color: #F59E0B; box-shadow: 0 0 0 4px rgba(250,204,21,0.2); }

    /* Label step */
    .det-step-label { font-size: 0.7rem; font-weight: 600; color: var(--muted); text-align: center; line-height: 1.3; white-space: pre-line; }
    .det-step.done .det-step-label    { color: #16A34A; font-weight: 700; }
    .det-step.current .det-step-label { color: #A16207; font-weight: 700; }

    /* ── BOTTOM CARDS ── */
    .det-bottom { display: flex; gap: 20px; flex-wrap: wrap; }
    .det-card {
        background: var(--white); border-radius: 16px;
        padding: 24px 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        flex: 1; min-width: 240px;
    }
    .det-card-title { font-size: 0.68rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; }
    .det-dosen-item { margin-bottom: 12px; }
    .det-dosen-item:last-child { margin-bottom: 0; }
    .det-dosen-item .sub { font-size: 0.7rem; color: var(--muted); font-weight: 500; margin-bottom: 2px; }
    .det-dosen-item .nama { font-size: 0.9rem; font-weight: 700; color: var(--neutral); }

    .det-jadwal-box {
        background: #F8FAFC; border-radius: 12px; padding: 16px 20px;
        display: flex; gap: 16px; align-items: center; flex-wrap: wrap;
    }
    .det-jadwal-date { text-align: center; min-width: 44px; }
    .det-jadwal-month { font-size: 0.7rem; font-weight: 700; color: var(--gold); text-transform: uppercase; }
    .det-jadwal-day { font-size: 2rem; font-weight: 800; color: var(--neutral); line-height: 1; }
    .det-jadwal-info { flex: 1; }
    .det-jadwal-nama { font-size: 0.9rem; font-weight: 700; color: var(--neutral); }
    .det-jadwal-tahun { font-size: 0.75rem; color: var(--muted); margin-bottom: 8px; }
    .det-jadwal-meta { display: flex; gap: 16px; flex-wrap: wrap; }
    .det-jadwal-meta-item { display: flex; align-items: center; gap: 6px; font-size: 0.78rem; font-weight: 600; color: var(--neutral); }

    /* ── EMPTY STATE ── */
    .det-empty { color: var(--muted); font-size: 0.82rem; font-style: italic; }
    .det-empty-state {
        display: flex; flex-direction: column; align-items: center;
        justify-content: center; padding: 24px 16px; text-align: center;
    }
    .det-empty-icon {
        width: 64px; height: 64px; border-radius: 50%;
        background: #F1F5F9; display: flex; align-items: center;
        justify-content: center; margin-bottom: 16px;
    }
    .det-empty-title { font-size: 0.95rem; font-weight: 700; color: var(--neutral); margin-bottom: 8px; }
    .det-empty-desc { font-size: 0.78rem; color: var(--muted); line-height: 1.5; }
</style>

<div class="det-wrap">

    <a href="{{ route('dosen.mahasiswa') }}" class="det-back">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Kembali ke Daftar Mahasiswa
    </a>

    {{-- PROFILE OUTER --}}
    <div class="det-profile-outer">

        {{-- KIRI: foto + info --}}
        <div class="det-profile-card">
            @if($mhs->foto)
                <img src="{{ asset('storage/' . $mhs->foto) }}" class="det-avatar" alt="foto">
            @else
                <div class="det-avatar-placeholder">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                </div>
            @endif

            <div class="det-profile-info">
                <span class="det-badge-aktif">Mahasiswa Aktif</span>
                <div class="det-nama">{{ $mhs->nama }}</div>
                <div class="det-meta">
                    <div class="det-meta-item">
                        <label>NIM</label>
                        <span>{{ $mhs->nim_nid }}</span>
                    </div>
                    <div class="det-meta-item">
                        <label>Angkatan</label>
                        <span>{{ $mhs->angkatan ?? '-' }}</span>
                    </div>
                </div>
                <div class="det-judul-label">Judul Tugas Akhir</div>
                @if($judul)
                    <div class="det-judul">"{{ $judul->judul_disetujui }}"</div>
                @else
                    <div class="det-empty">Belum ada judul yang disetujui</div>
                @endif
            </div>
        </div>

        {{-- KANAN: dosen pembimbing --}}
        <div class="det-dosbing-card">
            <div class="det-dosbing-label">Dosen Pembimbing</div>
            @if($pembimbing1)
                <div class="det-dosbing-item">
                    <div class="sub">Pembimbing 1</div>
                    <div class="nama">{{ $pembimbing1->nama }}</div>
                </div>
            @endif
            @if($pembimbing2)
                <div class="det-dosbing-item">
                    <div class="sub">Pembimbing 2</div>
                    <div class="nama">{{ $pembimbing2->nama }}</div>
                </div>
            @endif
            @if(!$pembimbing1 && !$pembimbing2)
                <div class="det-empty">Belum ditetapkan</div>
            @endif
        </div>

    </div>

    {{-- PROGRESS STEPS — 7 tahap dari Doc 30, logic currentIndex dari Doc 30, icon dari Doc 30 --}}
    <div class="det-steps-card">
        <div class="det-steps-header">
            <div>
                <div class="det-steps-title">Progress Akademik</div>
                <div class="det-steps-sub">Pelacakan tahapan penyelesaian Tugas Akhir mahasiswa</div>
            </div>
            <div class="det-steps-legend">
                <div class="det-legend-item" style="color:#16A34A;">
                    <span class="det-legend-dot" style="background:#22C55E;"></span> Selesai
                </div>
                <div class="det-legend-item" style="color:#A16207;">
                    <span class="det-legend-dot" style="background:#FACC15;"></span> Sedang Berjalan
                </div>
                <div class="det-legend-item" style="color:#9CA3AF;">
                    <span class="det-legend-dot" style="background:#D1D5DB;"></span> Belum Tercapai
                </div>
            </div>
        </div>

        @php
            // 7 tahap dari Doc 30 (lebih lengkap)
            $stepList = [
                ['label' => "Pengajuan\nJudul",     'done' => $steps['adaPengajuan']],
                ['label' => "Verifikasi\nJudul",     'done' => $steps['judulDisetujui']],
                ['label' => "Upload\nProposal",      'done' => $steps['adaProposal']],
                ['label' => "Penetapan\nPembimbing", 'done' => $steps['adaPembimbing']],
                ['label' => "Review\nProposal",      'done' => $steps['proposalSelesai']],
                ['label' => "Bimbingan\nTA",         'done' => $steps['adaBimbingan']],
                ['label' => "Seminar\nProposal",     'done' => $steps['daftarSeminar']],
            ];

            // Logic currentIndex dari Doc 30 — step pertama yang belum done = sedang berjalan
            $currentIndex = -1;
            foreach ($stepList as $i => $step) {
                if (!$step['done']) {
                    $currentIndex = $i;
                    break;
                }
            }
        @endphp

        <div class="det-steps">
            @foreach($stepList as $i => $step)
            @php
                $isCurrent = ($i === $currentIndex);
                $cls = $step['done'] ? 'done' : ($isCurrent ? 'current' : '');
            @endphp
            <div class="det-step {{ $cls }}">
                <div class="det-step-icon">
                    @if($step['done'])
                        {{-- Centang putih --}}
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    @elseif($isCurrent)
                        {{-- Jam/sedang berjalan — kuning --}}
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#735C00" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    @else
                        {{-- Abu belum --}}
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/></svg>
                    @endif
                </div>
                <div class="det-step-label">{{ $step['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- BOTTOM: PENGUJI + JADWAL --}}
    <div class="det-bottom">

        <div class="det-card">
            <div class="det-card-title">Dosen Penguji</div>
            @if($penguji1 || $penguji2)
                @if($penguji1)
                    <div class="det-dosen-item">
                        <div class="sub">Penguji 1</div>
                        <div class="nama">{{ $penguji1->nama }}</div>
                    </div>
                @endif
                @if($penguji2)
                    <div class="det-dosen-item">
                        <div class="sub">Penguji 2</div>
                        <div class="nama">{{ $penguji2->nama }}</div>
                    </div>
                @endif
            @else
                <div class="det-empty-state">
                    <div class="det-empty-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/><line x1="17" y1="11" x2="23" y2="11"/></svg>
                    </div>
                    <div class="det-empty-title">Dosen Penguji Belum Ditetapkan</div>
                    <div class="det-empty-desc">Menunggu proses administrasi dari koordinator.</div>
                </div>
            @endif
        </div>

        <div class="det-card">
            <div class="det-card-title">Jadwal Seminar</div>
            @if($seminar && $seminar->tanggal_seminar)
                <div class="det-jadwal-box">
                    <div class="det-jadwal-date">
                        <div class="det-jadwal-month">{{ \Carbon\Carbon::parse($seminar->tanggal_seminar)->translatedFormat('M') }}</div>
                        <div class="det-jadwal-day">{{ \Carbon\Carbon::parse($seminar->tanggal_seminar)->format('d') }}</div>
                    </div>
                    <div class="det-jadwal-info">
                        <div class="det-jadwal-nama">Seminar Proposal</div>
                        <div class="det-jadwal-tahun">{{ \Carbon\Carbon::parse($seminar->tanggal_seminar)->format('Y') }}</div>
                        <div class="det-jadwal-meta">
                            @if($seminar->waktu_mulai && $seminar->waktu_selesai)
                            <div class="det-jadwal-meta-item">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#6B7280" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                {{ substr($seminar->waktu_mulai,0,5) }} — {{ substr($seminar->waktu_selesai,0,5) }} WIB
                            </div>
                            @endif
                            @if($seminar->ruang)
                            <div class="det-jadwal-meta-item">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#6B7280" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ $seminar->ruang }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="det-empty-state">
                    <div class="det-empty-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                    </div>
                    <div class="det-empty-title">Jadwal Seminar Belum Tersedia</div>
                    <div class="det-empty-desc">Jadwal akan muncul setelah dosen penguji dan waktu pelaksanaan disetujui.</div>
                </div>
            @endif
        </div>

    </div>

</div>

@endsection