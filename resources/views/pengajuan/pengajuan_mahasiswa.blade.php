@extends('layouts.app')

@section('content')

<style>
    /* ── ROOT ── */
    :root {
        --gold: #C9A227;
        --gold-light: #FEF9EC;
        --gold-border: #F5D97A;
        --gold-btn: #FFE083;
        --neutral: #1E293B;
        --muted: #6B7280;
        --border: #E5E7EB;
        --white: #ffffff;
        --bg: #F5F6FA;
        --radius: 16px;
    }

    body { background: var(--bg); }

    /* ── HERO ── */
    .pj-hero {
        background-image: url('{{ asset("images/bg_ajukan.jpeg") }}');
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        padding: 44px 48px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
        min-height: 200px;
        display: flex;
        align-items: center;
    }

    .pj-hero-body { position: relative; z-index: 2; }

    .pj-hero h1 {
        font-size: 2rem;
        font-weight: 800;
        color: #735C00;
        margin-bottom: 20px;
        line-height: 1.2;
    }

    .btn-ajukan {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--gold-btn);
        color: var(--neutral);
        border: none;
        border-radius: 10px;
        padding: 10px 22px;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: .2s;
    }

    .btn-ajukan:hover {
        background: #d2bf75;
        color: var(--neutral);
    }

    /* ── FILTER ROW ── */
    .filter-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .filter-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--neutral);
        white-space: nowrap;
    }

    .search-wrap {
        position: relative;
        flex: 1;
        max-width: 420px;
    }

    .search-wrap svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        pointer-events: none;
    }

    .pj-search {
        width: 100%;
        padding: 9px 12px 9px 36px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 13px;
        background: #fff;
        outline: none;
        transition: border .2s;
        font-family: inherit;
        color: var(--neutral);
    }

    .pj-search:focus { border-color: var(--gold); }

    .pj-select {
        padding: 9px 14px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 13px;
        background: #fff;
        outline: none;
        cursor: pointer;
        font-family: inherit;
        color: var(--neutral);
        min-width: 150px;
        transition: border .2s;
    }

    .pj-select:focus { border-color: var(--gold); }

    .btn-reset-filter {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 16px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        background: #fff;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--muted);
        cursor: pointer;
        font-family: inherit;
        transition: .2s;
        white-space: nowrap;
    }

    .btn-reset-filter:hover {
        border-color: var(--gold);
        color: var(--gold);
    }

    /* ── TABLE CARD ── */
    .tabel-wrap {
        background: #fff;
        border-radius: var(--radius);
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    table { width: 100%; border-collapse: collapse; }

    thead th {
        padding: 13px 18px;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--gold);
        background: #fff;
        border-bottom: 1.5px solid var(--border);
        text-align: left;
        white-space: nowrap;
    }

    thead th:first-child,
    thead th:nth-child(2) { color: var(--gold); }

    tbody tr {
        border-bottom: 1px solid #F3F4F6;
        transition: background .15s;
    }

    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #FAFBFF; }

    tbody td {
        padding: 14px 18px;
        font-size: 13px;
        color: var(--neutral);
        vertical-align: middle;
    }

    .usulan-title {
        font-weight: 600;
        color: var(--neutral);
        font-size: 13.5px;
    }

    .usulan-more {
        font-size: 11.5px;
        color: var(--muted);
        margin-top: 2px;
    }

    /* Status badges */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 14px;
        border-radius: 99px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-disetujui  { background: #F0FDF4; color: #16A34A; border: 1px solid #BBF7D0; }
    .badge-menunggu   { background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A; }
    .badge-ditolak    { background: #FFF1F2; color: #E11D48; border: 1px solid #FECDD3; }
    .badge-default    { background: #F3F4F6; color: #6B7280; border: 1px solid #E5E7EB; }

    /* Detail button */
    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 18px;
        border: 1.5px solid var(--border);
        border-radius: 99px;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--neutral);
        background: #fff;
        text-decoration: none;
        transition: .2s;
        white-space: nowrap;
    }

    .btn-detail:hover {
        border-color: var(--gold);
        color: var(--gold);
        background: var(--gold-light);
    }

    .empty-td {
        text-align: center;
        padding: 52px;
        color: var(--muted);
        font-size: 14px;
    }

    /* ── MODAL ── */
    .pj-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.45);
        z-index: 1050;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .pj-modal-overlay.show { display: flex; }

    .pj-modal {
        background: #fff;
        border-radius: 20px;
        width: 100%;
        max-width: 520px;
        max-height: 92vh;
        overflow-y: auto;
        box-shadow: 0 24px 64px rgba(0,0,0,.18);
        animation: pjIn .25s ease;
    }

    @keyframes pjIn {
        from { transform: scale(.95) translateY(12px); opacity: 0; }
        to   { transform: scale(1)   translateY(0);    opacity: 1; }
    }

    .pj-modal-head {
        padding: 28px 28px 0;
        position: relative;
    }

    .pj-modal-head h2 {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--neutral);
        margin-bottom: 4px;
    }

    .pj-modal-head p {
        font-size: 13px;
        color: var(--muted);
        margin: 0 0 22px;
    }

    .btn-modal-close {
        position: absolute;
        top: 20px;
        right: 22px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: none;
        background: #F3F4F6;
        font-size: 17px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--muted);
        transition: .2s;
        line-height: 1;
    }

    .btn-modal-close:hover { background: #E5E7EB; color: var(--neutral); }

    .pj-modal-body { padding: 0 28px 20px; }

    /* Info box inside modal */
    .info-box {
        background: #FFFBEB;
        border: 1.5px solid #FDE68A;
        border-radius: 12px;
        padding: 16px 18px;
        margin-bottom: 22px;
    }

    .info-box-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        color: #92400E;
        margin-bottom: 10px;
    }

    .info-box ul {
        margin: 0;
        padding-left: 18px;
        font-size: 12.5px;
        color: #92400E;
        line-height: 1.7;
    }

    /* Form fields */
    .pj-form-group { margin-bottom: 14px; }

    .pj-form-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
        margin-bottom: 5px;
    }

    .pj-form-control {
        width: 100%;
        padding: 9px 12px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        color: var(--neutral);
        background: #FAFAFA;
        outline: none;
        transition: border .2s;
        font-family: inherit;
        box-sizing: border-box;
    }

    .pj-form-control:focus  { border-color: var(--gold); background: #fff; }
    .pj-form-control[readonly] { color: var(--muted); cursor: not-allowed; }
    .pj-form-control.is-invalid { border-color: #E11D48; }

    .form-row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .form-row-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 12px;
    }

    .usulan-section-title {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--neutral);
        margin-bottom: 10px;
        margin-top: 18px;
    }

    .divider {
        border: none;
        border-top: 1px dashed var(--gold-border);
        margin: 18px 0;
    }

    /* Modal footer */
    .pj-modal-foot {
        padding: 16px 28px 24px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-kembali {
        padding: 10px 24px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        background: #fff;
        font-size: 13.5px;
        font-weight: 600;
        color: var(--muted);
        cursor: pointer;
        font-family: inherit;
        transition: .2s;
    }

    .btn-kembali:hover { border-color: #D1D5DB; background: #F9FAFB; }

    .btn-simpan-form {
        padding: 10px 24px;
        border: none;
        border-radius: 10px;
        background: var(--gold-btn);
        font-size: 13.5px;
        font-weight: 700;
        color: var(--neutral);
        cursor: pointer;
        font-family: inherit;
        transition: .2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-simpan-form:hover { background: #d2bf75; }
    .btn-simpan-form:disabled { opacity: .7; cursor: not-allowed; }

    #noResult {
        text-align: center;
        padding: 40px;
        color: var(--muted);
        font-size: 14px;
    }
</style>

<div style="padding: 4px 0;">

    {{-- HERO --}}
    <div class="pj-hero">
        <div class="pj-hero-body">
            <h1>Pengajuan Judul Tugas Akhir</h1>
            <button class="btn-ajukan" onclick="bukaModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M2 21l21-9L2 3v7l15 2-15 2v7z"/>
                </svg>
                Ajukan Judul
            </button>
        </div>
    </div>

    {{-- FILTER ROW --}}
    <div class="filter-row">
        <span class="filter-label">Cari</span>
        <div class="search-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" class="pj-search" id="searchInput"
                placeholder="Cari nama, NIM, atau judul...">
        </div>

        <span class="filter-label">Status</span>
        <select class="pj-select" id="filterStatus">
            <option value="">Semua Status</option>
            <option value="disetujui">Disetujui</option>
            <option value="menunggu verifikasi">Menunggu Verifikasi</option>
            <option value="ditolak">Ditolak</option>
        </select>

        <button class="btn-reset-filter" id="btnResetFilter">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
            </svg>
            Reset Filter
        </button>
    </div>

    {{-- TABLE --}}
    <div class="tabel-wrap">
        <table id="tabelPengajuan">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Usulan Judul</th>
                    <th>Status</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuanList as $index => $item)
                <tr data-status="{{ strtolower($item->status) }}"
                    data-search="{{ strtolower($item->judul_1 . ' ' . $item->judul_2 . ' ' . $item->judul_3 . ' ' . ($item->judul_disetujui ?? '') . ' ' . $item->nim_nid) }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d M Y') }}</td>
                    <td>
                        @if(strtolower($item->status) === 'disetujui')
                            <div class="usulan-title">{{ \Illuminate\Support\Str::limit($item->judul_disetujui, 50) }}</div>
                        @else
                            <div class="usulan-title">{{ \Illuminate\Support\Str::limit($item->judul_1, 50) }}</div>
                            <div class="usulan-more">+2 lainnya</div>
                        @endif
                    </td>
                    <td>
                        @php $st = strtolower($item->status); @endphp
                        @if($st === 'disetujui')
                            <span class="badge-status badge-disetujui">Disetujui</span>
                        @elseif(str_contains($st, 'menunggu'))
                            <span class="badge-status badge-menunggu">Menunggu Verifikasi</span>
                        @elseif($st === 'ditolak')
                            <span class="badge-status badge-ditolak">Ditolak</span>
                        @else
                            <span class="badge-status badge-default">{{ $item->status }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('pengajuan.detail', $item->id) }}" class="btn-detail">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                            Lihat Pengajuan
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-td">
                        <div style="font-size:32px;margin-bottom:8px;">📋</div>
                        Belum ada pengajuan judul
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div id="noResult" style="display:none;">
            <div style="font-size:28px;margin-bottom:8px;">🔍</div>
            Tidak ada data yang sesuai filter.
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════
     MODAL FORM PENGAJUAN
══════════════════════════════════════════ --}}
<div class="pj-modal-overlay" id="modalOverlay" onclick="tutupModalLuar(event)">
    <div class="pj-modal">
        <div class="pj-modal-head">
            <button class="btn-modal-close" onclick="tutupModal()">×</button>
            <h2>Form Pengajuan Judul TA</h2>
            <p>Lengkapi data pengajuan judul tugas akhir mahasiswa</p>
        </div>

        <div class="pj-modal-body">

            {{-- Info box --}}
            <div class="info-box">
                <div class="info-box-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                        viewBox="0 0 24 24" stroke="#D97706" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                    </svg>
                    Informasi Penting
                </div>
                <ul>
                    <li>Mahasiswa wajib mengajukan tiga usulan judul Tugas Akhir sebagai alternatif pertimbangan dalam proses verifikasi.</li>
                    <li>Usulan judul pertama merupakan pilihan utama atau topik yang paling diminati untuk diteliti.</li>
                    <li>Setiap usulan judul dapat memiliki topik penelitian yang sama maupun berbeda sesuai minat dan bidang keilmuan mahasiswa.</li>
                    <li>Pastikan seluruh data yang diajukan telah sesuai sebelum melanjutkan proses pengajuan judul Tugas Akhir.</li>
                </ul>
            </div>

            <div id="errorAlertTA" style="display:none; background:#FFF1F2; border:1px solid #FECDD3; border-radius:8px; padding:10px 14px; font-size:13px; color:#E11D48; margin-bottom:14px;">
                Semua field wajib diisi. Mitra Penelitian bersifat opsional.
            </div>

            <form id="formPengajuanTA" action="{{ route('pengajuan.store') }}" method="POST">
                @csrf

                {{-- NIM & Nama --}}
                <div class="form-row-2" style="margin-bottom:14px;">
                    <div class="pj-form-group" style="margin:0;">
                        <label class="pj-form-label">Nomor Induk Mahasiswa (NIM)</label>
                        <input type="text" name="nim_nid" id="ta_nim" class="pj-form-control"
                            value="{{ session('user')->nim_nid ?? '' }}" readonly>
                    </div>
                    <div class="pj-form-group" style="margin:0;">
                        <label class="pj-form-label">Nama Lengkap</label>
                        <input type="text" name="nama" id="ta_nama" class="pj-form-control"
                            value="{{ session('user')->nama ?? session('user')->name ?? '' }}" readonly>
                    </div>
                </div>

                {{-- Tanggal --}}
                <div class="pj-form-group">
                    <label class="pj-form-label">Tanggal</label>
                    <input type="date" name="tanggal_pengajuan" id="ta_tanggal" class="pj-form-control">
                </div>

                <hr class="divider">

                {{-- Usulan Judul 1 --}}
                <div class="usulan-section-title">Usulan Judul 1</div>
                <div class="pj-form-group">
                    <label class="pj-form-label">Judul Penelitian</label>
                    <input type="text" name="judul_1" id="ta_judul1" class="pj-form-control"
                        placeholder="Masukkan judul penelitian pertama...">
                </div>
                <div class="form-row-2" style="margin-bottom:14px;">
                    <div>
                        <label class="pj-form-label">Topik Penelitian</label>
                        <input type="text" name="topik_1" id="ta_topik1" class="pj-form-control"
                            placeholder="Contoh: Cyber Security">
                    </div>
                    <div>
                        <label class="pj-form-label">Mitra Penelitian <span style="color:#9CA3AF;font-weight:400;">(Opsional)</span></label>
                        <input type="text" name="mitra_1" class="pj-form-control"
                            placeholder="Opsional">
                    </div>
                </div>

                <hr class="divider">

                {{-- Usulan Judul 2 --}}
                <div class="usulan-section-title">Usulan Judul 2</div>
                <div class="pj-form-group">
                    <label class="pj-form-label">Judul Penelitian</label>
                    <input type="text" name="judul_2" id="ta_judul2" class="pj-form-control"
                        placeholder="Masukkan judul penelitian kedua...">
                </div>
                <div class="form-row-2" style="margin-bottom:14px;">
                    <div>
                        <label class="pj-form-label">Topik Penelitian</label>
                        <input type="text" name="topik_2" id="ta_topik2" class="pj-form-control"
                            placeholder="Contoh: Cyber Security">
                    </div>
                    <div>
                        <label class="pj-form-label">Mitra Penelitian <span style="color:#9CA3AF;font-weight:400;">(Opsional)</span></label>
                        <input type="text" name="mitra_2" class="pj-form-control"
                            placeholder="Opsional">
                    </div>
                </div>

                <hr class="divider">

                {{-- Usulan Judul 3 --}}
                <div class="usulan-section-title">Usulan Judul 3</div>
                <div class="pj-form-group">
                    <label class="pj-form-label">Judul Penelitian</label>
                    <input type="text" name="judul_3" id="ta_judul3" class="pj-form-control"
                        placeholder="Masukkan judul penelitian ketiga...">
                </div>
                <div class="form-row-2" style="margin-bottom:4px;">
                    <div>
                        <label class="pj-form-label">Topik Penelitian</label>
                        <input type="text" name="topik_3" id="ta_topik3" class="pj-form-control"
                            placeholder="Contoh: Mobile DevOps">
                    </div>
                    <div>
                        <label class="pj-form-label">Mitra Penelitian <span style="color:#9CA3AF;font-weight:400;">(Opsional)</span></label>
                        <input type="text" name="mitra_3" class="pj-form-control"
                            placeholder="Opsional">
                    </div>
                </div>

            </form>
        </div>

        <div class="pj-modal-foot">
            <button type="button" class="btn-kembali" onclick="tutupModal()">Kembali</button>
            <button type="submit" form="formPengajuanTA" class="btn-simpan-form" id="btnAjukan">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M2 21l21-9L2 3v7l15 2-15 2v7z"/>
                </svg>
                Ajukan Judul
            </button>
        </div>
    </div>
</div>

<script>
    // ── Modal ──
    function bukaModal() {
        document.getElementById('modalOverlay').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function tutupModal() {
        document.getElementById('modalOverlay').classList.remove('show');
        document.body.style.overflow = '';
    }

    function tutupModalLuar(e) {
        if (e.target === document.getElementById('modalOverlay')) tutupModal();
    }

    // ── Filter & Search ──
    document.addEventListener('DOMContentLoaded', function () {

        const searchInput  = document.getElementById('searchInput');
        const filterStatus = document.getElementById('filterStatus');
        const btnReset     = document.getElementById('btnResetFilter');
        const noResult     = document.getElementById('noResult');
        const rows         = document.querySelectorAll('#tabelPengajuan tbody tr');

        function applyFilter() {
            const q      = searchInput.value.toLowerCase().trim();
            const status = filterStatus.value.toLowerCase();
            let visible  = 0;

            rows.forEach(row => {
                if (!row.dataset.status) { row.style.display = (!q && !status) ? '' : 'none'; return; }
                const matchSearch  = !q      || (row.dataset.search  || '').includes(q);
                const matchStatus  = !status || (row.dataset.status  || '').includes(status);
                const ok = matchSearch && matchStatus;
                row.style.display = ok ? '' : 'none';
                if (ok) visible++;
            });

            noResult.style.display = (visible === 0 && (q || status)) ? 'block' : 'none';
        }

        searchInput.addEventListener('input',   applyFilter);
        filterStatus.addEventListener('change', applyFilter);
        btnReset.addEventListener('click', function () {
            searchInput.value  = '';
            filterStatus.value = '';
            applyFilter();
        });

        // ── Validasi form ──
        const form      = document.getElementById('formPengajuanTA');
        const alertBox  = document.getElementById('errorAlertTA');
        const wajib     = ['ta_tanggal','ta_judul1','ta_topik1','ta_judul2','ta_topik2','ta_judul3','ta_topik3'];

        form.addEventListener('submit', function (e) {
            let valid = true;
            wajib.forEach(id => document.getElementById(id).classList.remove('is-invalid'));

            wajib.forEach(id => {
                const el = document.getElementById(id);
                if (!el.value.trim()) { el.classList.add('is-invalid'); valid = false; }
            });

            if (!valid) {
                e.preventDefault();
                alertBox.style.display = 'block';
                document.getElementById('modalOverlay').scrollTop = 0;
                return;
            }

            alertBox.style.display = 'none';
            const btn = document.getElementById('btnAjukan');
            btn.disabled = true;
            btn.innerHTML = `<span style="width:14px;height:14px;border:2px solid #00000044;border-top-color:#000;border-radius:50%;display:inline-block;animation:spin .6s linear infinite;"></span> Memproses...`;
        });
    });
</script>

<style>
    @keyframes spin { to { transform: rotate(360deg); } }
</style>

@endsection
