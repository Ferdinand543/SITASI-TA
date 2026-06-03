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

    .wrap {
        background: var(--bg);
        min-height: 100vh;
    }

    .hero {
        background-image: url('{{ asset("images/bg.jpeg") }}');
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        padding: 36px 40px 32px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }

    .hero::before {
        content: '';
        position: absolute;
        right: -40px;
        top: -40px;
        width: 200px;
        height: 200px;
        background: rgba(201, 162, 39, .12);
        border-radius: 50%;
    }

    .hero-title {
        font-size: 28px;
        font-weight: 800;
        color: #735C00;
        margin-bottom: 6px;
    }

    .hero-sub {
        font-size: 13px;
        color: #92400E;
        margin-bottom: 24px;
    }

    .tabs {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        border-radius: 10px;
        border: 2px solid transparent;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s;
        font-family: inherit;
    }

    .tab-btn.active {
        background: #FFE083;
        color: #6C5700;
        border-color: #D1C6AB;
    }

    .tab-btn.inactive {
        background: #FFFFFF;
        color: #6C5700;
        border-color: #D1C6AB;
    }

    .tab-btn.inactive:hover {
        background: #FFF8DC;
        border-color: #6C5700;
    }

    .count-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 22px;
        height: 22px;
        padding: 0 6px;
        background: var(--gold);
        color: #fff;
        border-radius: 99px;
        font-size: 11px;
        font-weight: 800;
    }

    .tab-btn.inactive .count-chip {
        background: #6C5700;
    }

    .card {
        background: var(--white);
        border-radius: var(--radius);
        box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .filter-bar {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
        flex: 1;
        min-width: 180px;
    }

    .filter-group label {
        font-size: 11px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .search-wrap {
        position: relative;
    }

    .search-wrap svg {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        padding: 10px 12px 10px 34px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 13px;
        outline: none;
        background: #FAFAFA;
        font-family: inherit;
        transition: border .2s;
        box-sizing: border-box;
    }

    .search-input:focus {
        border-color: var(--gold);
        background: #fff;
    }

    .filter-select {
        padding: 10px 12px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 13px;
        outline: none;
        background: #FAFAFA;
        font-family: inherit;
        cursor: pointer;
        width: 100%;
        box-sizing: border-box;
    }

    .filter-select:focus {
        border-color: var(--gold);
    }

    .btn-reset {
        padding: 10px 18px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
        background: #fff;
        color: var(--muted);
        cursor: pointer;
        transition: .2s;
        font-family: inherit;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        align-self: flex-end;
    }

    .btn-reset:hover {
        border-color: var(--gold);
        color: var(--gold);
    }

    .tabel-scroll {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }

    thead th {
        padding: 13px 18px;
        font-size: 11px;
        font-weight: 700;
        color: var(--muted);
        text-align: left;
        background: #FAFAFA;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    tbody tr {
        border-bottom: 1px solid #F3F4F6;
        transition: background .15s;
    }

    tbody tr:last-child {
        border-bottom: none;
    }

    tbody tr:hover {
        background: #FAFBFF;
    }

    tbody td {
        padding: 15px 18px;
        font-size: 13px;
        color: var(--neutral);
        vertical-align: middle;
    }

    .avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: var(--gold-lt);
        border: 1.5px solid var(--gold-border);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 800;
        color: var(--gold);
        flex-shrink: 0;
    }

    .mhs-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .mhs-nama {
        font-weight: 700;
        color: var(--neutral);
    }

    /* ── Dokumen chips ── */
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

    /* file chip — merah muda */
    .dok-chip-file {
        background: #FEF2F2;
        border: 1px solid #FECACA;
        color: #DC2626;
    }

    .dok-chip-file:hover {
        background: #FEE2E2;
        color: #DC2626;
    }

    /* link chip — biru muda */
    .dok-chip-link {
        background: #EFF6FF;
        border: 1px solid #BFDBFE;
        color: #1D4ED8;
    }

    .dok-chip-link:hover {
        background: #DBEAFE;
        color: #1D4ED8;
    }

    .dok-empty {
        color: #9CA3AF;
        font-size: 12px;
    }

    /* ── badge status ── */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 99px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-pending {
        background: #FEF9EC;
        color: #B45309;
        border: 1px solid var(--gold-border);
    }

    .badge-dilihat {
        background: #F0FDF4;
        color: #15803D;
        border: 1px solid #BBF7D0;
    }

    .badge-ditolak {
        background: #FEF2F2;
        color: #DC2626;
        border: 1px solid #FECACA;
    }

    .btn-lihat {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 16px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        background: var(--gold-lt);
        color: var(--gold);
        border: 1.5px solid var(--gold-border);
        text-decoration: none;
        transition: .2s;
        cursor: pointer;
        font-family: inherit;
    }

    .btn-lihat:hover {
        background: #FEF3C7;
        border-color: var(--gold);
        color: var(--gold);
    }

    .empty-row td {
        text-align: center;
        padding: 60px;
        color: var(--muted);
        font-size: 14px;
    }

    .tab-panel {
        display: none;
    }

    .tab-panel.active {
        display: block;
    }

    @media (max-width: 600px) {
        .hero { padding: 24px 20px; }
        .hero-title { font-size: 22px; }
        .filter-bar { flex-direction: column; }
    }
</style>

<div class="wrap">

    <div class="hero">
        <div class="hero-title">Riwayat Bimbingan Mahasiswa</div>
        <div class="hero-sub">Kelola dan lihat riwayat bimbingan Mahasiswa</div>
        <div class="tabs">
            <button class="tab-btn active" id="tab-proposal-btn" onclick="switchTab('proposal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                Proposal Bimbingan
                <span class="count-chip">{{ $proposalList->count() }}</span>
            </button>
            <button class="tab-btn inactive" id="tab-mahasiswa-btn" onclick="switchTab('mahasiswa')">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                Mahasiswa Bimbingan
                <span class="count-chip">{{ $mahasiswaList->count() }}</span>
            </button>
        </div>
    </div>

    @if(session('success'))
    <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:12px;padding:13px 18px;margin-bottom:20px;font-size:13px;color:#15803D;display:flex;align-items:center;gap:8px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- ══ TAB 1: PROPOSAL BIMBINGAN ══ --}}
    <div class="tab-panel active" id="panel-proposal">
        <div class="card">
            <div class="filter-bar">
                <div class="filter-group" style="max-width:420px;">
                    <label>Pencarian</label>
                    <div class="search-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#9CA3AF" stroke-width="2">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.35-4.35" />
                        </svg>
                        <input type="text" class="search-input" id="searchProposal"
                            placeholder="Cari nama mahasiswa atau judul proposal..."
                            oninput="filterProposal()">
                    </div>
                </div>
                <div class="filter-group" style="max-width:180px;">
                    <label>Status</label>
                    <select class="filter-select" id="filterStatusProposal" onchange="filterProposal()">
                        <option value="">Semua Status</option>
                        <option value="pending">Baru Dikirim</option>
                        <option value="sudah_dilihat">Sudah Dilihat</option>
                    </select>
                </div>
                <button class="btn-reset" onclick="resetProposal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Reset Filter
                </button>
            </div>
            <div class="tabel-scroll">
                <table id="tabelProposal">
                    <thead>
                        <tr>
                            <th>No.</th>
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
                                    // format lama: plain filename string
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
                        @endphp
                        <tr data-nama="{{ strtolower($p->nama_mahasiswa) }}"
                            data-judul="{{ strtolower($p->judul) }}"
                            data-status="{{ $p->status }}">
                            <td>{{ $i + 1 }}</td>
                            <td style="font-size:12.5px;font-weight:600;color:var(--muted);">{{ $p->nim_nid }}</td>
                            <td>
                                <div class="mhs-info">
                                    <div class="avatar">{{ strtoupper(substr($p->nama_mahasiswa, 0, 1)) }}</div>
                                    <span class="mhs-nama">{{ $p->nama_mahasiswa }}</span>
                                </div>
                            </td>
                            <td style="white-space:nowrap;font-size:12.5px;">
                                {{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->translatedFormat('d M Y') }}
                            </td>
                            <td style="max-width:200px;font-size:12.5px;">{{ Str::limit($p->judul, 50) }}</td>

                            {{-- ── KOLOM DOKUMEN ── --}}
                            <td>
                                @if(empty($rawFiles) && empty($rawLinks))
                                    <span class="dok-empty">—</span>
                                @else
                                    <div class="dok-list">
                                        {{-- FILE CHIPS: tiap klik kirim AJAX trackBuka --}}
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

                                        {{-- LINK CHIPS: tiap klik kirim AJAX trackBuka --}}
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

                            <td>
                                @if($p->status === 'pending')
                                <span class="badge badge-pending">⏳ Baru Dikirim</span>
                                @elseif($p->status === 'sudah_dilihat')
                                <span class="badge badge-dilihat">✓ Sudah Dilihat</span>
                                @elseif($p->status === 'ditolak')
                                <span class="badge badge-ditolak">✕ Ditolak</span>
                                @else
                                <span class="badge badge-pending">{{ $p->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr class="empty-row">
                            <td colspan="7">
                                <div style="font-size:36px;margin-bottom:10px;">📭</div>
                                Belum ada proposal yang dikirim mahasiswa
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ══ TAB 2: MAHASISWA BIMBINGAN ══ --}}
    <div class="tab-panel" id="panel-mahasiswa">
        <div class="card">
            <div class="filter-bar">
                <div class="filter-group" style="max-width:360px;">
                    <label>Pencarian Mahasiswa</label>
                    <div class="search-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#9CA3AF" stroke-width="2">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.35-4.35" />
                        </svg>
                        <input type="text" class="search-input" id="searchMahasiswa"
                            placeholder="Cari nama mahasiswa atau NIM..."
                            oninput="filterMahasiswa()">
                    </div>
                </div>
                <button class="btn-reset" onclick="resetMahasiswa()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Reset Filter
                </button>
            </div>
            <div class="tabel-scroll">
                <table id="tabelMahasiswa">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Angkatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mahasiswaList as $i => $m)
                        <tr data-nama="{{ strtolower($m->nama_mahasiswa) }}" data-nim="{{ $m->nim_nid }}">
                            <td>{{ $i + 1 }}.</td>
                            <td style="font-size:12.5px;font-weight:600;color:var(--muted);">{{ $m->nim_nid }}</td>
                            <td>
                                <div class="mhs-info">
                                    <div class="avatar">{{ strtoupper(substr($m->nama_mahasiswa, 0, 1)) }}</div>
                                    <span class="mhs-nama">{{ $m->nama_mahasiswa }}</span>
                                </div>
                            </td>
                            <td style="font-size:13px;font-weight:600;">{{ $m->angkatan }}</td>
                            <td>
                                <a href="{{ route('dosen.bimbingan.detail', $m->nim_nid) }}" class="btn-lihat">
                                    Lihat Riwayat
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr class="empty-row">
                            <td colspan="5">
                                <div style="font-size:36px;margin-bottom:10px;">👨‍🎓</div>
                                Belum ada mahasiswa bimbingan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    function switchTab(tab) {
        document.querySelectorAll('.tab-panel').forEach(function(p) { p.classList.remove('active'); });
        document.querySelectorAll('.tab-btn').forEach(function(b) {
            b.classList.remove('active');
            b.classList.add('inactive');
        });
        document.getElementById('panel-' + tab).classList.add('active');
        var btn = document.getElementById('tab-' + tab + '-btn');
        btn.classList.add('active');
        btn.classList.remove('inactive');
    }

    function filterProposal() {
        var q      = document.getElementById('searchProposal').value.toLowerCase();
        var status = document.getElementById('filterStatusProposal').value;
        document.querySelectorAll('#tabelProposal tbody tr:not(.empty-row)').forEach(function(row) {
            var matchQ = !q || (row.dataset.nama + ' ' + row.dataset.judul).includes(q);
            var matchS = !status || row.dataset.status === status;
            row.style.display = (matchQ && matchS) ? '' : 'none';
        });
    }

    function resetProposal() {
        document.getElementById('searchProposal').value = '';
        document.getElementById('filterStatusProposal').value = '';
        filterProposal();
    }

    function filterMahasiswa() {
        var q = document.getElementById('searchMahasiswa').value.toLowerCase();
        document.querySelectorAll('#tabelMahasiswa tbody tr:not(.empty-row)').forEach(function(row) {
            var match = !q || row.dataset.nama.includes(q) || row.dataset.nim.includes(q);
            row.style.display = match ? '' : 'none';
        });
    }

    function resetMahasiswa() {
        document.getElementById('searchMahasiswa').value = '';
        filterMahasiswa();
    }

    // ── TRACK BUKA: dipanggil tiap dosen klik chip file/link ──
    // Kalau semua file+link di proposal itu sudah dibuka → badge otomatis berubah jadi "Sudah Dilihat"
    function trackBuka(proposalId, index, el) {
        fetch('/dosen/bimbingan/proposal/' + proposalId + '/track', {
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
                // Cari baris tabel yang sama dengan chip yang diklik
                var row   = el.closest('tr');
                var badge = row.querySelector('.badge');
                if (badge) {
                    badge.className       = 'badge badge-dilihat';
                    badge.innerHTML       = '✓ Sudah Dilihat';
                    row.dataset.status    = 'sudah_dilihat';
                }
            }
        })
        .catch(function() {
            // Fetch gagal pun tidak masalah, file tetap terbuka di tab baru
        });
    }
</script>

@endsection