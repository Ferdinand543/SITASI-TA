@extends('layouts.app')

@section('content')

@php
    $subRoles = \DB::table('dosen_roles')
        ->where('nim_nid', session('user')->nim_nid)
        ->pluck('role_dosen')
        ->toArray();

    $isKoor     = in_array('koordinator', $subRoles);
    $isReviewer = in_array('reviewer',    $subRoles);
@endphp

<!-- HERO -->
<div class="hero-section mb-4">
    <div class="hero-content">
        <h2 class="fw-bold">Pengajuan Proposal TA-1</h2>
        <p>Tinjau Proposal Tugas Akhir Mahasiswa dan Berikan Hasil Review</p>
        <div class="hero-btn-row">
            @if($isKoor)
            <a href="{{ url('/proposal') }}" class="btn-hero-white">
                <i class="fa fa-user-check"></i>
                Penetapan Dosen Pembimbing
            </a>
            @endif
            @if($isReviewer)
            <a href="{{ route('reviewer.proposal') }}" class="btn-hero-yellow">
                <i class="fa fa-file-circle-check"></i>
                Review Proposal
            </a>
            @endif
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="stats-row">
    <div class="stats-card">
        <div class="stats-icon-wrap stats-blue">
            <i class="fa fa-file-lines"></i>
        </div>
        <div class="stats-info">
            <div class="stats-number">{{ $totalProposal }}</div>
            <div class="stats-label">Total Proposal Mahasiswa</div>
        </div>
    </div>
    <div class="stats-card">
        <div class="stats-icon-wrap stats-yellow">
            <i class="fa fa-clock"></i>
        </div>
        <div class="stats-info">
            <div class="stats-number">{{ $totalMenunggu }}</div>
            <div class="stats-label">Proposal Belum Direview</div>
        </div>
    </div>
    <div class="stats-card">
        <div class="stats-icon-wrap stats-green">
            <i class="fa fa-circle-check"></i>
        </div>
        <div class="stats-info">
            <div class="stats-number">{{ $totalSelesai }}</div>
            <div class="stats-label">Proposal Selesai Direview</div>
        </div>
    </div>
</div>

<div class="container-fluid px-4">

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert-custom alert-success-custom mb-3">
            <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert-custom alert-error-custom mb-3">
            <i class="fa fa-times-circle me-1"></i> {{ session('error') }}
        </div>
    @endif

    {{-- FILTER --}}
    <div class="filter-wrapper mb-3">
        <div class="filter-search">
            <i class="fa fa-search filter-search-icon"></i>
            <input type="text" id="inputSearch"
                   placeholder="Cari nama, NIM, atau judul proposal..."
                   value="{{ request('search') }}">
        </div>
        <div class="filter-select-wrap">
            <select id="inputStatus">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status_review') === 'menunggu' ? 'selected' : '' }}>Menunggu Review</option>
                <option value="selesai"  {{ request('status_review') === 'selesai'  ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>
        <div class="filter-date-wrap">
            <input type="date" id="inputTanggal" value="{{ request('tanggal') }}">
        </div>
        <button class="btn-reset" onclick="resetFilter()">
            <i class="fa fa-rotate-right"></i>
            Reset Filter
        </button>
    </div>

    {{-- TABEL --}}
    <div class="table-wrapper">
        <table class="tbl-reviewer" id="tblReviewer">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal Review</th>
                    <th>NIM</th>
                    <th>Mahasiswa</th>
                    <th>Judul Proposal</th>
                    <th>Proposal</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tblBody">
                @forelse($proposals as $i => $p)
                @php
                    $sudahReview    = !empty($p->tinjauan_id);
                    $statusLabel    = $sudahReview ? 'selesai' : 'menunggu';
                    $catatanSingkat = $p->catatan ? \Illuminate\Support\Str::limit($p->catatan, 30) : '-';
                    $tanggalReview  = $p->tanggal_tinjauan
                        ? \Carbon\Carbon::parse($p->tanggal_tinjauan)->format('d M Y')
                        : '-';
                    $dsn1 = $p->nidn_dsn1 ? $p->nidn_dsn1 . ($p->nama_dsn1 ? ' - ' . $p->nama_dsn1 : '') : '-';
                    $dsn2 = $p->nidn_dsn2 ? $p->nidn_dsn2 . ($p->nama_dsn2 ? ' - ' . $p->nama_dsn2 : '') : '-';
                @endphp
                <tr data-status="{{ $statusLabel }}"
                    data-search="{{ strtolower($p->nim_nid . ' ' . $p->nama . ' ' . $p->judul) }}"
                    data-tanggal="{{ $p->tanggal_tinjauan ?? '' }}">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $tanggalReview }}</td>
                    <td>{{ $p->nim_nid }}</td>
                    <td class="td-nama">{{ $p->nama }}</td>
                    <td class="td-judul">{{ \Illuminate\Support\Str::limit($p->judul, 40) }}</td>
                    <td>
                        @if($p->file_proposal)
                            <a href="{{ asset('storage/' . $p->file_proposal) }}" target="_blank" class="btn-file">
                                <i class="fa fa-file-pdf"></i>
                                {{ \Illuminate\Support\Str::limit(basename($p->file_proposal), 20) }}
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($sudahReview)
                            <span class="badge-reviewer badge-selesai">SUDAH<br>DIREVIEW</span>
                        @else
                            <span class="badge-reviewer badge-menunggu">MENUNGGU<br>REVIEW</span>
                        @endif
                    </td>
                    <td class="td-catatan">{{ $catatanSingkat }}</td>
                    <td>
                        @if($sudahReview)
                            <a href="{{ route('reviewer.proposal.detail', $p->id) }}" class="btn-aksi btn-detail">Detail</a>
                        @else
                            <button class="btn-aksi btn-review"
                                onclick="bukaModalReview(
                                    '{{ addslashes($p->nama) }}',
                                    '{{ $p->nim_nid }}',
                                    '{{ addslashes($p->judul) }}',
                                    '{{ $p->id }}',
                                    '{{ $p->file_proposal ?? '' }}',
                                    '{{ addslashes($dsn1) }}',
                                    '{{ addslashes($dsn2) }}'
                                )">
                                Review
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                {{-- Belum ada data dari DB sama sekali → inbox icon --}}
                <tr id="rowEmpty">
                    <td colspan="9" style="padding: 60px 20px; text-align: center; border: none;">
                        <div style="display:inline-flex; flex-direction:column; align-items:center; gap:12px;">
                            <div style="width:64px; height:64px; background:#f1f5f9; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                <i class="fa fa-inbox" style="font-size:1.8rem; color:#94a3b8;"></i>
                            </div>
                            <div style="font-size:0.95rem; font-weight:700; color:#475569;">Belum ada data</div>
                            <div style="font-size:0.82rem; color:#94a3b8;">Data akan muncul setelah proses dilakukan.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Filter/search tidak nemu hasil → magnifier icon --}}
        <div id="msgKosong" style="display:none;">
            <table class="tbl-reviewer">
                <tbody>
                    <tr>
                        <td colspan="9" style="padding: 60px 20px; text-align: center; border: none;">
                            <div style="display:inline-flex; flex-direction:column; align-items:center; gap:12px;">
                                <div style="width:64px; height:64px; background:#f1f5f9; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                    <i class="fa fa-magnifying-glass" style="font-size:1.8rem; color:#94a3b8;"></i>
                                </div>
                                <div style="font-size:0.95rem; font-weight:700; color:#475569;">Data tidak ditemukan</div>
                                <div style="font-size:0.82rem; color:#94a3b8;">Coba gunakan kata kunci atau filter yang berbeda.</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

</div>

{{-- MODAL REVIEW --}}
<div id="modalReview" class="modal-overlay">
    <div class="modal-box-new">
        <div class="mnew-header">
            <div>
                <div class="mnew-title">Form Tinjauan Proposal Mahasiswa</div>
                <div class="mnew-sub">Lengkapi data berikut untuk memberikan tinjauan dan evaluasi proposal mahasiswa.</div>
            </div>
            <button class="mnew-close" onclick="tutupModalReview()">&#x2715;</button>
        </div>
        <div class="mnew-row2">
            <div class="mnew-field">
                <label class="mnew-lbl">NIM</label>
                <input type="text" id="reviewNim" readonly class="mnew-inp">
            </div>
            <div class="mnew-field">
                <label class="mnew-lbl">Nama Mahasiswa</label>
                <input type="text" id="reviewNama" readonly class="mnew-inp">
            </div>
        </div>
        <div class="mnew-field mb14">
            <label class="mnew-lbl">Tanggal Review</label>
            <div class="mnew-date-wrap">
                <input type="text" id="reviewTanggal" readonly class="mnew-inp mnew-date-readonly">
                <i class="fa fa-calendar mnew-date-icon"></i>
            </div>
        </div>
        <div class="mnew-field mb14">
            <label class="mnew-lbl">Judul Proposal</label>
            <textarea id="reviewJudul" readonly class="mnew-inp mnew-textarea-judul" rows="2"></textarea>
        </div>
        <div class="mnew-field mb14">
            <label class="mnew-lbl">Dokumen Proposal</label>
            <div class="mnew-doc-box" id="reviewDocBox">
                <div class="mnew-doc-inner">
                    <div class="mnew-doc-icon-wrap">
                        <i class="fa fa-file-pdf" style="color:#e53e3e; font-size:1.2rem;"></i>
                    </div>
                    <div class="mnew-doc-info">
                        <div class="mnew-doc-name" id="reviewDocName">-</div>
                        <div class="mnew-doc-size" id="reviewDocSize"></div>
                    </div>
                    <a id="reviewDocLink" href="#" target="_blank" class="mnew-preview-btn">
                        <i class="fa fa-eye"></i> Preview
                    </a>
                </div>
            </div>
        </div>
        <div class="mnew-row2 mb14">
            <div class="mnew-field">
                <label class="mnew-lbl">NIDN – Dosen Pembimbing 1</label>
                <input type="text" id="reviewDsn1" readonly class="mnew-inp">
            </div>
            <div class="mnew-field">
                <label class="mnew-lbl">NIDN – Dosen Pembimbing 2</label>
                <input type="text" id="reviewDsn2" readonly class="mnew-inp">
            </div>
        </div>
        <form id="formReview" method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="mnew-field mb14">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                    <label class="mnew-lbl" style="margin-bottom:0;">Catatan Review</label>
                    <span id="charCount" style="font-size:0.75rem; color:#888;">Maksimal 200 karakter</span>
                </div>
                <textarea name="catatan" id="reviewCatatan" class="mnew-inp mnew-textarea-catatan"
                          rows="4" maxlength="200"
                          placeholder="Tuliskan hasil review proposal mahasiswa..."></textarea>
            </div>
            <div class="mnew-field mb14">
                <label class="mnew-lbl">File Tinjauan Proposal (Upload)</label>
                <div class="mnew-upload-area" id="uploadArea" onclick="document.getElementById('reviewFile').click()">
                    <input type="file" name="file_tinjauan" id="reviewFile"
                           accept=".pdf,.doc,.docx" style="display:none;"
                           onchange="onFileSelected(this)">
                    <div id="uploadPlaceholder">
                        <div class="mnew-upload-icon-wrap">
                            <i class="fa fa-cloud-arrow-up" style="font-size:1.6rem; color:#735C00;"></i>
                        </div>
                        <div class="mnew-upload-text">Klik untuk unggah atau seret file ke sini</div>
                        <div class="mnew-upload-sub">Mendukung format PDF, DOC, atau DOCX (Max 10MB)</div>
                    </div>
                    <div id="uploadPreview" style="display:none;">
                        <i class="fa fa-file-pdf" style="color:#e53e3e; font-size:1.4rem;"></i>
                        <span id="uploadFileName" style="margin-left:8px; font-size:0.88rem; color:#333;"></span>
                        <button type="button" onclick="hapusFile(event)"
                                style="margin-left:10px; background:none; border:none; color:#c0392b; cursor:pointer; font-size:0.85rem;">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mnew-footer">
                <button type="button" onclick="tutupModalReview()" class="mnew-btn-kembali">Kembali</button>
                <button type="button" onclick="konfirmasiReview()" class="mnew-btn-kirim">
                    Kirim <i class="fa fa-paper-plane" style="margin-left:4px;"></i>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- POPUP KONFIRMASI --}}
<div id="popupKonfirmasiReview" class="popup-overlay">
    <div class="popup-box">
        <div class="popup-icon-wrap confirm">❓</div>
        <div class="popup-title">Konfirmasi</div>
        <div class="popup-msg">Apakah Anda yakin ingin mengirimkan hasil review ini?</div>
        <div class="popup-btn-row">
            <button class="popup-btn batal" onclick="tutupPopup('popupKonfirmasiReview')">Batal</button>
            <button class="popup-btn kirim" onclick="submitReview()">Kirim</button>
        </div>
    </div>
</div>

<div id="popupBerhasil" class="popup-overlay">
    <div class="popup-box">
        <div class="popup-icon-wrap success">✓</div>
        <div class="popup-title">Berhasil!</div>
        <div class="popup-msg">Review berhasil disimpan.</div>
        <div class="popup-btn-row">
            <button class="popup-btn ok" onclick="window.location.reload()">OK</button>
        </div>
    </div>
</div>

<div id="popupGagal" class="popup-overlay">
    <div class="popup-box">
        <div class="popup-icon-wrap error">✕</div>
        <div class="popup-title">Gagal!</div>
        <div class="popup-msg">Gagal mengirimkan review. Silakan coba lagi.</div>
        <div class="popup-btn-row">
            <button class="popup-btn ok" onclick="tutupPopup('popupGagal')">OK</button>
        </div>
    </div>
</div>

<div id="popupValidasi" class="popup-overlay">
    <div class="popup-box">
        <div class="popup-icon-wrap warning">✕</div>
        <div class="popup-title">Perhatian!</div>
        <div class="popup-msg">Catatan review wajib diisi sebelum mengirim.</div>
        <div class="popup-btn-row">
            <button class="popup-btn ok" onclick="tutupPopup('popupValidasi')">OK</button>
        </div>
    </div>
</div>

<style>
:root {
    --navy:        #111C2D;
    --brown-dark:  #4D4632;
    --gold:        #735C00;
    --blue-dark:   #1E40AF;
    --blue-bg:     #DBEAFE;
    --blue-badge:  #DBEAFE;
    --blue-text:   #1E40AF;
    --green-dark:  #065F46;
    --green-mid:   #10B981;
    --green-bg:    #D1FAE5;
    --border:      #E5DFD0;
    --border-soft: #F0EBE0;
    --bg-input:    #F7F5F0;
    --bg-table:    #F9F9FF;
}

/* ── HERO ── */
.hero-section {
    min-height: 220px;
    padding: 44px 48px;
    display: flex;
    align-items: center;
    background-image: url('{{ asset('images/bg.jpeg') }}');
    background-size: cover;
    background-position: center;
    border-radius: 20px;
    overflow: hidden;
}
.hero-content h2 { font-size: 2rem; color: #735C00; margin-bottom: 6px; font-weight: 800; }
.hero-content p  { color: #735C00; font-size: 0.95rem; margin-bottom: 20px; }
.hero-btn-row    { display: flex; gap: 12px; flex-wrap: wrap; }

.btn-hero-white {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: 10px;
    background: #fff; color: #735C00;
    font-size: 0.85rem; font-weight: 600;
    text-decoration: none; border: 1.5px solid #D1C6AB; transition: 0.2s;
}
.btn-hero-white:hover { background: #f5f0e8; color: #735C00; text-decoration: none; }

.btn-hero-yellow {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: 10px;
    background: #FACC15; color: #735C00;
    font-size: 0.85rem; font-weight: 700;
    text-decoration: none; border: none; transition: 0.2s;
}
.btn-hero-yellow:hover { background: #e6b800; color: #735C00; text-decoration: none; }

/* ── STATS ── */
.stats-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin: 0 0 20px 0;
}
.stats-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: 16px;
    padding: 22px 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(17,28,45,0.06);
    transition: 0.2s;
}
.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(17,28,45,0.1);
}
.stats-icon-wrap {
    width: 48px; height: 48px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; flex-shrink: 0;
}
.stats-blue   { background: var(--blue-bg);  color: var(--blue-dark); }
.stats-yellow { background: #FEF3C7; color: #D97706; }
.stats-green  { background: var(--green-bg); color: var(--green-mid); }
.stats-number { font-size: 1.6rem; font-weight: 800; color: var(--navy); line-height: 1; margin-bottom: 4px; }
.stats-label  { font-size: 0.8rem; color: var(--brown-dark); font-weight: 500; }

/* ── FILTER ── */
.filter-wrapper { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.filter-search  { position: relative; flex: 1; min-width: 220px; }
.filter-search input {
    width: 100%; padding: 10px 12px 10px 36px;
    border: 1px solid #D1C6AB; border-radius: 10px;
    font-size: 0.88rem; background: #fff;
    box-sizing: border-box; color: var(--navy);
}
.filter-search input:focus { outline: none; border-color: var(--gold); }
.filter-search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 0.85rem; }
.filter-select-wrap select,
.filter-date-wrap input {
    padding: 10px 12px; border: 1px solid #D1C6AB;
    border-radius: 10px; font-size: 0.88rem;
    background: #fff; height: 40px; color: var(--navy);
}
.filter-select-wrap select:focus,
.filter-date-wrap input:focus { outline: none; border-color: var(--gold); }
.filter-select-wrap select { min-width: 160px; }
.filter-date-wrap input    { min-width: 150px; }
.btn-reset {
    display: flex; align-items: center; gap: 6px;
    padding: 9px 16px; border: 1px solid #D1C6AB;
    border-radius: 10px; background: #fff;
    font-size: 0.88rem; color: var(--brown-dark);
    cursor: pointer; white-space: nowrap; transition: 0.2s;
}
.btn-reset:hover { background: #FEF3C7; border-color: var(--gold); }

/* ── TABLE ── */
.table-wrapper { overflow-x: auto; }
.tbl-reviewer {
    width: 100%; min-width: 1000px;
    border-collapse: collapse; font-size: 0.88rem;
    background: #fff; border-radius: 12px;
    overflow: hidden; box-shadow: 0 2px 8px rgba(17,28,45,0.07);
}
.tbl-reviewer thead tr { background: #FEF3C7; }
.tbl-reviewer th {
    padding: 13px 14px; text-align: center;
    font-weight: 700; color: var(--navy);
    font-size: 0.83rem; border-bottom: 2px solid #D1C6AB; white-space: nowrap;
}
.tbl-reviewer td {
    padding: 14px; text-align: center;
    vertical-align: middle; border-bottom: 1px solid var(--border-soft); color: #333;
}
.tbl-reviewer tbody tr:hover { background: var(--bg-table); }
.td-nama    { font-weight: 600; white-space: nowrap; color: var(--navy); }
.td-judul   { text-align: left; }
.td-catatan { text-align: left; color: var(--brown-dark); font-size: 0.83rem; }

/* ── BADGE ── */
.badge-reviewer {
    display: inline-block; padding: 5px 10px;
    border-radius: 8px; font-size: 0.72rem;
    font-weight: 800; letter-spacing: 0.5px;
    line-height: 1.4; text-align: center;
}
.badge-selesai  { background: var(--green-bg); color: var(--green-dark); border: 1px solid #10B981; }
.badge-menunggu { background: var(--blue-badge); color: var(--blue-text); border: 1px solid #93C5FD; }

/* ── FILE ── */
.btn-file {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 0.8rem; color: #92400E; text-decoration: none;
    background: #FEF3C7; padding: 4px 10px;
    border-radius: 6px; border: 1px solid #fde68a;
    white-space: nowrap; transition: 0.2s;
}
.btn-file:hover { background: #FFE083; }

/* ── AKSI ── */
.btn-aksi {
    display: inline-block; padding: 7px 18px;
    border-radius: 8px; border: none;
    font-size: 0.83rem; font-weight: 600;
    cursor: pointer; transition: 0.2s;
    white-space: nowrap; text-decoration: none; text-align: center;
}
.btn-review { background: var(--gold); color: #fff; }
.btn-review:hover { background: var(--brown-dark); }
.btn-detail { background: #fff; color: var(--brown-dark); border: 1px solid #D1C6AB; }
.btn-detail:hover { background: #FEF3C7; border-color: var(--gold); color: var(--gold); }

/* ── ALERT ── */
.alert-custom { padding: 12px 16px; border-radius: 10px; font-size: 0.9rem; }
.alert-success-custom { background: var(--green-bg); color: var(--green-dark); border: 1px solid #10B981; }
.alert-error-custom   { background: #fdecea; color: #92400E; border: 1px solid #f1aeb5; }

/* ── MODAL ── */
.modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(17,28,45,0.45); z-index: 9999;
    align-items: center; justify-content: center; padding: 20px;
}
.modal-box-new {
    background: #fff; border-radius: 20px;
    padding: 28px 28px 24px; width: 100%; max-width: 560px;
    box-shadow: 0 12px 40px rgba(17,28,45,0.18);
    max-height: 92vh; overflow-y: auto;
}
.mnew-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 22px; }
.mnew-title  { font-size: 1.15rem; font-weight: 800; color: var(--navy); margin-bottom: 4px; }
.mnew-sub    { font-size: 0.8rem; color: var(--gold); line-height: 1.4; max-width: 380px; }
.mnew-close  { background: none; border: none; font-size: 1.1rem; color: #888; cursor: pointer; padding: 4px 8px; border-radius: 6px; transition: 0.2s; flex-shrink: 0; }
.mnew-close:hover { background: var(--border-soft); color: #333; }
.mnew-row2   { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }
.mnew-field  { display: flex; flex-direction: column; }
.mb14        { margin-bottom: 14px; }
.mnew-lbl    { font-size: 0.82rem; font-weight: 600; color: var(--brown-dark); margin-bottom: 5px; }
.mnew-inp {
    padding: 10px 13px; border: 1.5px solid var(--border);
    border-radius: 10px; font-size: 0.88rem; color: var(--navy);
    background: var(--bg-input); font-family: inherit;
    box-sizing: border-box; width: 100%; transition: 0.18s;
}
.mnew-inp:not([readonly]):focus { outline: none; border-color: var(--gold); background: #fff; }
.mnew-inp[readonly] { cursor: default; color: #555; }
.mnew-textarea-judul   { resize: none; line-height: 1.5; min-height: 60px; }
.mnew-textarea-catatan { resize: vertical; min-height: 100px; line-height: 1.5; }
.mnew-date-wrap   { position: relative; }
.mnew-date-icon   { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #aaa; pointer-events: none; font-size: 0.88rem; }
.mnew-date-readonly { pointer-events: none; cursor: default; background: var(--bg-input) !important; color: #555 !important; padding-right: 36px; }
.mnew-doc-box     { border: 1.5px solid var(--border); border-radius: 10px; background: var(--bg-input); padding: 12px 14px; }
.mnew-doc-inner   { display: flex; align-items: center; gap: 12px; }
.mnew-doc-icon-wrap { width: 38px; height: 38px; background: #fff; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border); flex-shrink: 0; }
.mnew-doc-info    { flex: 1; }
.mnew-doc-name    { font-size: 0.88rem; font-weight: 600; color: var(--navy); }
.mnew-doc-size    { font-size: 0.76rem; color: #888; }
.mnew-preview-btn { display: inline-flex; align-items: center; gap: 5px; padding: 6px 14px; border-radius: 8px; border: 1.5px solid #D1C6AB; background: #fff; color: var(--brown-dark); font-size: 0.82rem; font-weight: 600; text-decoration: none; transition: 0.2s; white-space: nowrap; flex-shrink: 0; }
.mnew-preview-btn:hover { background: #FEF3C7; border-color: var(--gold); color: var(--gold); }
.mnew-upload-area { border: 2px dashed #D1C6AB; border-radius: 12px; background: #FAFAF8; padding: 28px 20px; text-align: center; cursor: pointer; transition: 0.2s; min-height: 110px; display: flex; align-items: center; justify-content: center; }
.mnew-upload-area:hover    { border-color: var(--gold); background: #FEF3C7; }
.mnew-upload-area.dragover { border-color: var(--gold); background: #FEF3C7; }
.mnew-upload-icon-wrap { width: 48px; height: 48px; background: #FEF3C7; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px; }
.mnew-upload-text { font-size: 0.88rem; font-weight: 600; color: var(--navy); margin-bottom: 4px; }
.mnew-upload-sub  { font-size: 0.78rem; color: #888; }
.mnew-footer      { display: flex; justify-content: flex-end; gap: 12px; margin-top: 8px; }
.mnew-btn-kembali { padding: 10px 24px; border-radius: 10px; border: 1.5px solid #D1C6AB; background: #fff; color: var(--brown-dark); font-size: 0.9rem; font-weight: 600; cursor: pointer; transition: 0.2s; }
.mnew-btn-kembali:hover { background: #FEF3C7; border-color: var(--gold); }
.mnew-btn-kirim   { padding: 10px 28px; border-radius: 10px; border: none; background: var(--gold); color: #fff; font-size: 0.9rem; font-weight: 700; cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center; gap: 6px; }
.mnew-btn-kirim:hover { background: var(--brown-dark); }

/* ── POPUP ── */
.popup-overlay { display: none; position: fixed; inset: 0; background: rgba(17,28,45,0.45); z-index: 99999; align-items: center; justify-content: center; }
.popup-overlay.active { display: flex; }
.popup-box { background: #fff; border-radius: 20px; padding: 40px 32px 32px; width: 100%; max-width: 380px; margin: 0 16px; box-shadow: 0 12px 40px rgba(17,28,45,0.18); text-align: center; }
.popup-icon-wrap { width: 72px; height: 72px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 18px; font-size: 2rem; }
.popup-icon-wrap.success { background: var(--green-bg);  border: 3px solid #10B981; color: var(--green-dark); }
.popup-icon-wrap.error   { background: #fdecea;           border: 3px solid #fca5a5; color: #92400E; }
.popup-icon-wrap.confirm { background: #FEF3C7;           border: 3px solid #FFE083; color: var(--gold); }
.popup-icon-wrap.warning { background: #FEF3C7;           border: 3px solid #fde68a; color: #92400E; }
.popup-title { font-size: 1.4rem; font-weight: 800; color: var(--navy); margin-bottom: 8px; }
.popup-msg   { font-size: 0.9rem; color: var(--brown-dark); margin-bottom: 24px; line-height: 1.5; }
.popup-btn-row { display: flex; gap: 12px; justify-content: center; }
.popup-btn { padding: 10px 28px; border-radius: 10px; font-size: 0.92rem; font-weight: 700; cursor: pointer; border: none; transition: 0.2s; min-width: 90px; }
.popup-btn.ok, .popup-btn.kirim { background: var(--gold); color: #fff; }
.popup-btn.ok:hover, .popup-btn.kirim:hover { background: var(--brown-dark); }
.popup-btn.batal { background: #D1C6AB; color: var(--navy); }
.popup-btn.batal:hover { background: #c4b89a; }

@media (max-width: 640px) {
    .stats-row { grid-template-columns: 1fr; }
    .mnew-row2 { grid-template-columns: 1fr; }
    .modal-box-new { padding: 20px 16px; }
    .hero-section { padding: 28px 24px; }
    .hero-content h2 { font-size: 1.4rem; }
    .hero-btn-row { flex-direction: column; }
}
</style>

<script>
const inputSearch  = document.getElementById('inputSearch');
const inputStatus  = document.getElementById('inputStatus');
const inputTanggal = document.getElementById('inputTanggal');
const tblBody      = document.getElementById('tblBody');
const msgKosong    = document.getElementById('msgKosong');
const tblReviewer  = document.getElementById('tblReviewer');

function applyFilter() {
    const search  = inputSearch.value.toLowerCase().trim();
    const status  = inputStatus.value.toLowerCase();
    const tanggal = inputTanggal.value;
    const rows    = tblBody.querySelectorAll('tr[data-status]');
    let visible   = 0;

    rows.forEach(function(row) {
        const rowSearch  = (row.getAttribute('data-search')  || '').toLowerCase();
        const rowStatus  = (row.getAttribute('data-status')  || '').toLowerCase();
        const rowTanggal = (row.getAttribute('data-tanggal') || '');
        const cocok = (!search || rowSearch.includes(search))
                   && (!status || rowStatus === status)
                   && (!tanggal || rowTanggal === tanggal);
        row.style.display = cocok ? '' : 'none';
        if (cocok) visible++;
    });

    const rowEmpty = document.getElementById('rowEmpty');
    if (rowEmpty) rowEmpty.style.display = 'none';

    if (visible === 0 && rows.length > 0) {
        tblReviewer.style.display = 'none';
        msgKosong.style.display   = 'block';
    } else {
        tblReviewer.style.display = '';
        msgKosong.style.display   = 'none';
    }
}

inputSearch.addEventListener('input', applyFilter);
inputStatus.addEventListener('change', applyFilter);
inputTanggal.addEventListener('change', applyFilter);

function resetFilter() {
    inputSearch.value  = '';
    inputStatus.value  = '';
    inputTanggal.value = '';
    applyFilter();
}

function bukaPopup(id)  { document.getElementById(id).classList.add('active'); }
function tutupPopup(id) { document.getElementById(id).classList.remove('active'); }

var _reviewProposalId = null;

function bukaModalReview(nama, nim, judul, proposalId, fileProposal, dsn1, dsn2) {
    _reviewProposalId = proposalId;
    document.getElementById('reviewNim').value     = nim;
    document.getElementById('reviewNama').value    = nama;
    document.getElementById('reviewJudul').value   = judul;
    document.getElementById('reviewDsn1').value    = dsn1 || '-';
    document.getElementById('reviewDsn2').value    = dsn2 || '-';
    document.getElementById('reviewCatatan').value = '';
    document.getElementById('reviewFile').value    = '';
    document.getElementById('charCount').textContent = 'Maksimal 200 karakter';

    var today = new Date();
    var dd    = String(today.getDate()).padStart(2, '0');
    var mm    = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy  = today.getFullYear();
    document.getElementById('reviewTanggal').value = dd + '/' + mm + '/' + yyyy;

    if (fileProposal) {
        var namaFile = fileProposal.split('/').pop();
        document.getElementById('reviewDocName').textContent      = namaFile;
        document.getElementById('reviewDocSize').textContent      = '';
        document.getElementById('reviewDocLink').href             = '/storage/' + fileProposal;
        document.getElementById('reviewDocLink').style.display    = '';
        document.getElementById('reviewDocBox').style.display     = 'block';
    } else {
        document.getElementById('reviewDocName').textContent      = 'Tidak ada file';
        document.getElementById('reviewDocLink').href             = '#';
        document.getElementById('reviewDocLink').style.display    = 'none';
    }

    document.getElementById('uploadPlaceholder').style.display = 'block';
    document.getElementById('uploadPreview').style.display     = 'none';
    document.getElementById('formReview').action = '/reviewer/proposal/' + proposalId + '/review';
    document.getElementById('modalReview').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function tutupModalReview() {
    document.getElementById('modalReview').style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('modalReview').addEventListener('click', function(e) {
    if (e.target === this) tutupModalReview();
});

document.getElementById('reviewCatatan').addEventListener('input', function() {
    var sisa = 200 - this.value.length;
    document.getElementById('charCount').textContent = sisa + ' karakter tersisa';
    document.getElementById('charCount').style.color = sisa < 20 ? '#c0392b' : '#888';
});

var uploadArea = document.getElementById('uploadArea');
uploadArea.addEventListener('dragover',  function(e) { e.preventDefault(); this.classList.add('dragover'); });
uploadArea.addEventListener('dragleave', function()  { this.classList.remove('dragover'); });
uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
    var files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('reviewFile').files = files;
        onFileSelected(document.getElementById('reviewFile'));
    }
});

function onFileSelected(input) {
    if (input.files && input.files[0]) {
        document.getElementById('uploadFileName').textContent         = input.files[0].name;
        document.getElementById('uploadPlaceholder').style.display    = 'none';
        document.getElementById('uploadPreview').style.display        = 'flex';
        document.getElementById('uploadPreview').style.alignItems     = 'center';
        document.getElementById('uploadPreview').style.justifyContent = 'center';
    }
}

function hapusFile(e) {
    e.stopPropagation();
    document.getElementById('reviewFile').value = '';
    document.getElementById('uploadPlaceholder').style.display = 'block';
    document.getElementById('uploadPreview').style.display     = 'none';
}

function konfirmasiReview() {
    if (!document.getElementById('reviewCatatan').value.trim()) {
        bukaPopup('popupValidasi');
        return;
    }
    bukaPopup('popupKonfirmasiReview');
}

function submitReview() {
    tutupPopup('popupKonfirmasiReview');
    var form     = document.getElementById('formReview');
    var formData = new FormData(form);
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(res) {
        tutupModalReview();
        if (res.ok) bukaPopup('popupBerhasil');
        else        bukaPopup('popupGagal');
    })
    .catch(function() {
        tutupModalReview();
        bukaPopup('popupGagal');
    });
}
</script>

@endsection