@extends('layouts.app')

@section('title', 'Riwayat Bimbingan Mahasiswa')

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
}

body { background: var(--bg); }

/* ── HERO ── */
.hero {
    position: relative;
    overflow: hidden;
    background: url('{{ asset("images/bg.jpeg") }}') center/cover no-repeat;
    border-radius: 20px;
    padding: 44px 48px 48px;
    margin-bottom: 24px;
    min-height: 200px;
    display: flex;
    align-items: center;
}
.hero-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to right, rgba(255,255,255,0.55) 40%, rgba(255,255,255,0.05));
    border-radius: 20px;
}
.hero-dots { position: absolute; top: 20px; left: 32px; z-index: 2; display: flex; flex-direction: column; gap: 5px; }
.hero-dots-row { display: flex; gap: 5px; }
.hero-dots span { width: 6px; height: 6px; border-radius: 50%; background: #FACC15; opacity: 0.55; display: block; }
.hero-content { position: relative; z-index: 2; }
.hero-title { font-size: 32px; font-weight: 800; color: #735C00; margin-bottom: 6px; line-height: 1.2; }
.hero-sub { font-size: 13px; color: #92400E; margin-bottom: 24px; }

/* ── TAB BUTTONS ── */
.tab-group { display: flex; gap: 10px; flex-wrap: wrap; }

.tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 12px;
    border: 1.5px solid #D1C6AB;
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    transition: .2s;
    font-family: inherit;
}
.tab-btn.active  { background: #FFE083; color: #6C5700; border-color: #D1C6AB; }
.tab-btn.inactive { background: #FFFFFF; color: #6C5700; }
.tab-btn.inactive:hover { background: #FFF8DC; border-color: #6C5700; }

.count-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 22px;
    height: 22px;
    padding: 0 6px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 800;
    background: rgba(0,0,0,0.12);
    color: inherit;
}
.tab-btn.active  .count-chip { background: rgba(108,87,0,0.18); color: #6C5700; }
.tab-btn.inactive .count-chip { background: #FFF3CD; color: #92400E; }

/* ── MAIN CARD ── */
.main-card {
    background: var(--white);
    border-radius: 18px;
    border: 1px solid var(--border);
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    overflow: hidden;
}

/* ── FILTER HEADER ── */
.filter-header {
    padding: 18px 22px;
    border-bottom: 1px solid var(--border);
    background: var(--white);
}

.filter-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 8px;
}

.filter-row { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }

.search-wrap { position: relative; flex: 1; min-width: 240px; }
.search-wrap i {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: #9CA3AF; font-size: 13px; pointer-events: none;
}
.search-input {
    width: 100%; height: 42px; padding: 0 14px 0 36px;
    border: 1.5px solid var(--border); border-radius: 12px;
    font-size: 13px; outline: none; background: #FAFAFA;
    font-family: inherit; transition: border .2s; box-sizing: border-box;
}
.search-input:focus { border-color: var(--gold); background: #fff; }
.search-input::placeholder { color: #9CA3AF; }

.filter-select {
    height: 42px; padding: 0 36px 0 14px;
    border: 1.5px solid var(--border); border-radius: 12px;
    font-size: 13px; outline: none;
    background: #FAFAFA url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 12px center;
    font-family: inherit; cursor: pointer; appearance: none; min-width: 160px; transition: border .2s;
}
.filter-select:focus { border-color: var(--gold); background-color: #fff; }

.btn-reset {
    height: 42px; padding: 0 18px; border: 1.5px solid var(--border); border-radius: 12px;
    font-size: 13px; font-weight: 600; background: #fff; color: var(--muted);
    cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
    white-space: nowrap; transition: .2s; font-family: inherit;
}
.btn-reset:hover { border-color: var(--gold); color: var(--gold); }

/* ── TABLE ── */
.tabel-scroll { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; min-width: 600px; }
thead tr { background: #F8FAFC; }
thead th {
    padding: 13px 18px; font-size: 11px; font-weight: 700; color: var(--muted);
    text-align: left; border-bottom: 1px solid var(--border);
    white-space: nowrap; text-transform: uppercase; letter-spacing: .5px;
}
tbody tr { border-bottom: 1px solid #F3F4F6; transition: background .15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #FFFDF5; }
tbody td { padding: 15px 18px; font-size: 13px; color: var(--neutral); vertical-align: middle; }

.mhs-info { display: flex; align-items: center; gap: 10px; }
.avatar {
    width: 36px; height: 36px; border-radius: 10px; background: var(--gold-lt);
    border: 1.5px solid var(--gold-border); display: inline-flex; align-items: center;
    justify-content: center; font-size: 14px; font-weight: 800; color: var(--gold); flex-shrink: 0;
}
.mhs-nama { font-weight: 700; color: var(--neutral); }

/* ── DOK CHIP ── */
.dok-list {
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 160px;
}

.dok-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 10px;
    border-radius: 8px;
    font-size: 11.5px;
    font-weight: 600;
    text-decoration: none;
    transition: .2s;
    width: fit-content;
    max-width: 200px;
    overflow: hidden;
}

.dok-chip span {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.dok-chip-file {
    background: #FEF2F2;
    border: 1px solid #FECACA;
    color: #DC2626;
}
.dok-chip-file:hover { background: #FEE2E2; color: #DC2626; }

.dok-chip-link {
    background: #EFF6FF;
    border: 1px solid #BFDBFE;
    color: #1D4ED8;
}
.dok-chip-link:hover { background: #DBEAFE; color: #1D4ED8; }

.dok-empty { color: #9CA3AF; font-size: 12px; }

/* BADGE */
.badge {
    display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px;
    border-radius: 10px; font-size: 11px; font-weight: 700; white-space: nowrap;
}
.badge-baru    { background: #FEF9EC; color: #B45309; border: 1px solid var(--gold-border); }
.badge-dilihat { background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; }
.badge-ditolak { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }

/* JUMLAH CHIP */
.jumlah-chip {
    display: inline-flex; align-items: center; justify-content: center;
    background: var(--gold-lt); border: 1.5px solid var(--gold-border);
    color: var(--gold); border-radius: 8px; padding: 4px 14px;
    font-size: 14px; font-weight: 800;
}

/* BTN AKSI */
.btn-lihat {
    display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px;
    border-radius: 10px; font-size: 12.5px; font-weight: 700; background: #fff;
    color: var(--neutral); border: 1.5px solid var(--border); text-decoration: none; transition: .2s;
}
.btn-lihat:hover { border-color: var(--gold); color: var(--gold); background: var(--gold-lt); }

/* EMPTY */
.empty-row td { text-align: center; padding: 60px; color: var(--muted); font-size: 14px; }

/* TAB PANEL */
.tab-panel { display: none; }
.tab-panel.active { display: block; }

@media (max-width: 768px) {
    .hero { padding: 32px 24px; }
    .hero-title { font-size: 24px; }
    .filter-row { flex-direction: column; align-items: stretch; }
}
</style>

{{-- ══ HERO ══ --}}
<div class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-dots">
        @for($r = 0; $r < 3; $r++)
        <div class="hero-dots-row">@for($c = 0; $c < 8; $c++)<span></span>@endfor</div>
        @endfor
    </div>
    <div class="hero-content">
        <div class="hero-title">Riwayat Bimbingan Mahasiswa</div>
        <div class="hero-sub">Kelola dan lihat riwayat bimbingan Mahasiswa</div>
        <div class="tab-group">

            <button class="tab-btn active" id="tab-proposal-btn" onclick="switchTab('proposal')">
                <i class="fa-solid fa-file-lines" style="font-size:13px;"></i>
                Proposal Bimbingan
                <span class="count-chip">{{ $countProposal }}</span>
            </button>

            <button class="tab-btn inactive" id="tab-dosen-btn" onclick="switchTab('dosen')">
                <i class="fa-solid fa-user-graduate" style="font-size:13px;"></i>
                Mahasiswa Bimbingan
                <span class="count-chip">{{ $countDosen }}</span>
            </button>

        </div>
    </div>
</div>

@if(session('success'))
<div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:12px;padding:13px 18px;
            margin-bottom:20px;font-size:13px;color:#15803D;display:flex;align-items:center;gap:8px;">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

{{-- ══ TAB 1 — PROPOSAL BIMBINGAN ══ --}}
<div class="tab-panel active" id="panel-proposal">
    <div class="main-card">

        <div class="filter-header">
            <div class="filter-label">Pencarian</div>
            <div class="filter-row">
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" class="search-input" id="searchProposal"
                        placeholder="Cari nama mahasiswa atau judul proposal..."
                        oninput="filterProposal()">
                </div>
                <select class="filter-select" id="filterStatusProposal" onchange="filterProposal()">
                    <option value="">Semua Status</option>
                    <option value="pending">Baru Dikirim</option>
                    <option value="sudah_dilihat">Sudah Dilihat</option>
                </select>
                <button class="btn-reset" onclick="resetProposal()">
                    <i class="fa-solid fa-rotate-right" style="font-size:11px;"></i>
                    Reset Filter
                </button>
            </div>
        </div>

        <div class="tabel-scroll">
            <table id="tabelProposal">
                <thead>
                    <tr>
                        <th style="width:52px;">No.</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Tanggal Upload</th>
                        <th>Judul Proposal</th>
                        <th>Dokumen</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposalList as $i => $p)
                    @php
                        $rawFiles = [];
                        $rawLinks = [];

                        if ($p->file_proposal) {
                            $decoded = json_decode($p->file_proposal, true);
                            if (is_array($decoded)) {
                                $rawFiles = $decoded['files'] ?? [];
                                $rawLinks = $decoded['links'] ?? [];
                            } else {
                                $rawFiles = [$p->file_proposal];
                            }
                        }

                        $extIcon = function(string $name): string {
                            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                            return match($ext) {
                                'pdf'                               => '📄',
                                'doc', 'docx'                       => '📝',
                                'xls', 'xlsx'                       => '📊',
                                'ppt', 'pptx'                       => '📑',
                                'jpg', 'jpeg', 'png', 'gif', 'webp' => '🖼️',
                                'zip', 'rar', '7z'                  => '🗜️',
                                default                             => '📎',
                            };
                        };

                        // ── pakai status_admin untuk badge & filter admin ──
                        $statusAdmin = $p->status_admin ?? 'pending';
                    @endphp
                    <tr data-nama="{{ strtolower($p->nama_mahasiswa) }}"
                        data-judul="{{ strtolower($p->judul) }}"
                        data-status="{{ $statusAdmin }}">

                        <td style="color:#94a3b8;font-weight:600;">{{ $i + 1 }}</td>

                        <td style="font-size:12.5px;font-weight:600;color:var(--muted);white-space:nowrap;">
                            {{ $p->nim_nid }}
                        </td>

                        <td>
                            <div class="mhs-info">
                                <div class="avatar">{{ strtoupper(substr($p->nama_mahasiswa,0,1)) }}</div>
                                <span class="mhs-nama">{{ $p->nama_mahasiswa }}</span>
                            </div>
                        </td>

                        <td style="white-space:nowrap;font-size:12.5px;color:var(--muted);">
                            {{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->translatedFormat('d M Y') }}
                        </td>

                        <td style="max-width:220px;font-size:12.5px;line-height:1.5;">
                            {{ Str::limit($p->judul, 60) }}
                        </td>

                        {{-- ── KOLOM DOKUMEN ── --}}
                        <td>
                            @if(empty($rawFiles) && empty($rawLinks))
                                <span class="dok-empty">—</span>
                            @else
                                <div class="dok-list">
                                    @foreach($rawFiles as $fIdx => $fileName)
                                        <a href="{{ asset('uploads/proposal/' . $fileName) }}"
                                           target="_blank"
                                           class="dok-chip dok-chip-file"
                                           title="{{ $fileName }}"
                                           onclick="trackBuka({{ $p->id }}, 'file_{{ $fIdx }}', this)">
                                            <span style="flex-shrink:0;">{{ $extIcon($fileName) }}</span>
                                            <span>{{ Str::limit(pathinfo($fileName, PATHINFO_BASENAME), 22) }}</span>
                                        </a>
                                    @endforeach

                                    @foreach($rawLinks as $lIdx => $link)
                                        @php
                                            $host  = parse_url($link, PHP_URL_HOST) ?? $link;
                                            $label = Str::limit($host, 20);
                                        @endphp
                                        <a href="{{ $link }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="dok-chip dok-chip-link"
                                           title="{{ $link }}"
                                           onclick="trackBuka({{ $p->id }}, 'link_{{ $lIdx }}', this)">
                                            <span style="flex-shrink:0;">🔗</span>
                                            <span>{{ $label }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </td>

                        {{-- ── BADGE pakai status_admin ── --}}
                        <td>
                            @if($statusAdmin === 'pending')
                                <span class="badge badge-baru">BARU DIKIRIM</span>
                            @elseif($statusAdmin === 'sudah_dilihat')
                                <span class="badge badge-dilihat">SUDAH DILIHAT</span>
                            @else
                                <span class="badge badge-baru">{{ strtoupper($statusAdmin) }}</span>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr class="empty-row">
                        <td colspan="7">
                            <i class="fa-regular fa-folder-open" style="font-size:36px;display:block;margin-bottom:10px;color:#d1d5db;"></i>
                            Belum ada proposal yang dikirim mahasiswa
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ══ TAB 2 — DAFTAR DOSEN ══ --}}
<div class="tab-panel" id="panel-dosen">
    <div class="main-card">

        <div class="filter-header">
            <div class="filter-label">Pencarian Mahasiswa</div>
            <div class="filter-row">
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" class="search-input" id="searchDosen"
                        placeholder="Cari nama dosen atau NIDN..."
                        oninput="filterDosen()">
                </div>
                <button class="btn-reset" onclick="resetDosen()">
                    <i class="fa-solid fa-rotate-right" style="font-size:11px;"></i>
                    Reset Filter
                </button>
            </div>
        </div>

        <div class="tabel-scroll">
            <table id="tabelDosen">
                <thead>
                    <tr>
                        <th style="width:52px;">No.</th>
                        <th>NIDN</th>
                        <th>Dosen</th>
                        <th>Jumlah Mahasiswa</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dosenList as $i => $d)
                    <tr data-nama="{{ strtolower($d->nama_dosen) }}" data-nidn="{{ $d->nim_nid }}">

                        <td style="color:#94a3b8;font-weight:600;">{{ $i + 1 }}.</td>

                        <td style="font-size:12.5px;font-weight:600;color:var(--muted);white-space:nowrap;">
                            {{ $d->nim_nid }}
                        </td>

                        <td>
                            <div class="mhs-info">
                                <div class="avatar">{{ strtoupper(substr($d->nama_dosen,0,1)) }}</div>
                                <span class="mhs-nama">{{ $d->nama_dosen }}</span>
                            </div>
                        </td>

                        <td>
                            <span class="jumlah-chip">{{ $d->jumlah_mahasiswa }}</span>
                        </td>

                        <td style="text-align:right;">
                            <a href="{{ route('admin.bimbingan.dosen', $d->nim_nid) }}" class="btn-lihat">
                                Lihat Mahasiswa
                                <i class="fa-solid fa-arrow-right" style="font-size:11px;"></i>
                            </a>
                        </td>

                    </tr>
                    @empty
                    <tr class="empty-row">
                        <td colspan="5">
                            <i class="fa-solid fa-chalkboard-user" style="font-size:36px;display:block;margin-bottom:10px;color:#d1d5db;"></i>
                            Belum ada data dosen pembimbing
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => { b.classList.remove('active'); b.classList.add('inactive'); });
    document.getElementById('panel-' + tab).classList.add('active');
    const btn = document.getElementById('tab-' + tab + '-btn');
    btn.classList.add('active');
    btn.classList.remove('inactive');
}

// ── AUTO SWITCH TAB dari URL ?tab=mahasiswa (dari sidebar admin) ──
document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    if (params.get('tab') === 'mahasiswa') {
        switchTab('dosen');
    }
});

function filterProposal() {
    const q      = document.getElementById('searchProposal').value.toLowerCase();
    const status = document.getElementById('filterStatusProposal').value;
    document.querySelectorAll('#tabelProposal tbody tr:not(.empty-row)').forEach(row => {
        const matchQ = !q || (row.dataset.nama + ' ' + row.dataset.judul).includes(q);
        const matchS = !status || row.dataset.status === status;
        row.style.display = (matchQ && matchS) ? '' : 'none';
    });
}

function resetProposal() {
    document.getElementById('searchProposal').value = '';
    document.getElementById('filterStatusProposal').value = '';
    filterProposal();
}

function filterDosen() {
    const q = document.getElementById('searchDosen').value.toLowerCase();
    document.querySelectorAll('#tabelDosen tbody tr:not(.empty-row)').forEach(row => {
        const match = !q || row.dataset.nama.includes(q) || row.dataset.nidn.includes(q);
        row.style.display = match ? '' : 'none';
    });
}

function resetDosen() {
    document.getElementById('searchDosen').value = '';
    filterDosen();
}

// ── TRACK BUKA: tiap admin klik chip → kalau semua sudah dibuka, badge otomatis "SUDAH DILIHAT" ──
// Pakai status_admin di DB, tidak mengganggu status dosen sama sekali
function trackBuka(proposalId, index, el) {
    fetch('/admin/bimbingan/proposal/' + proposalId + '/track', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ index: index })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.status === 'sudah_dilihat') {
            var row   = el.closest('tr');
            var badge = row.querySelector('.badge');
            if (badge) {
                badge.className    = 'badge badge-dilihat';
                badge.textContent  = 'SUDAH DILIHAT';
                row.dataset.status = 'sudah_dilihat';
            }
        }
    })
    .catch(function() {});
}
</script>

@endsection