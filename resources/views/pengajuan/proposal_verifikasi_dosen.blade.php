@extends('layouts.app')

@section('content')

<style>
    body { background: #f4f6f9; }

    .wrapper { max-width: 900px; margin: auto; padding: 32px 20px; }

    .page-title { font-size: 1.8rem; font-weight: 800; color: #111; margin-bottom: 4px; }

    .page-sub { font-size: 0.9rem; color: #888; margin-bottom: 24px; }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 24px;
    }

    .info-box {
        border: 1px solid #e5e5e5;
        border-radius: 14px;
        padding: 16px 18px;
        background: #fff;
    }

    .info-box .lbl {
        font-size: 0.82rem;
        color: #888;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .info-box .val {
        font-size: 1rem;
        font-weight: 700;
        color: #111;
    }

    .val.selesai  { color: #28a745; }
    .val.menunggu { color: #b8860b; }
    .val.ditolak  { color: #dc3545; }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #111;
        margin-bottom: 2px;
    }

    .section-sub {
        font-size: 0.83rem;
        color: #888;
        margin-bottom: 14px;
    }

    .dosen-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 24px;
    }

    .dosen-card {
        border: 1px solid #e5e5e5;
        border-radius: 14px;
        padding: 18px;
        background: #fff;
        position: relative;
    }

    .dosen-card .dosen-label {
        font-size: 0.8rem;
        color: #888;
        margin-bottom: 8px;
    }

    .dosen-card .dosen-nama {
        font-size: 1rem;
        font-weight: 700;
        color: #111;
        margin-bottom: 2px;
    }

    .dosen-card .dosen-nidn {
        font-size: 0.85rem;
        color: #555;
        margin-bottom: 8px;
    }

    .dosen-card .dosen-tanggal {
        font-size: 0.8rem;
        color: #888;
    }

    .badge-disetujui {
        background: #d4edda;
        color: #28a745;
        border: 1px solid #b7dfbb;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-block;
    }

    .badge-menunggu {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffe082;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-block;
        text-decoration: none;
        cursor: pointer;
    }

    .badge-menunggu:hover {
        background: #ffe69c;
        color: #856404;
    }

    .badge-ditolak {
        background: #f8d7da;
        color: #dc3545;
        border: 1px solid #f1aeb5;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-block;
    }

    .proposal-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .proposal-box .file-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: #111;
    }

    .proposal-box .file-meta {
        font-size: 0.78rem;
        color: #888;
    }

    .footer-btn {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        margin-top: 30px;
    }

    .btn-kembali {
        background: #e0e0e0;
        color: #333;
        border: none;
        padding: 10px 22px;
        border-radius: 20px;
        font-size: 0.9rem;
        text-decoration: none;
        transition: 0.2s;
    }

    .btn-kembali:hover {
        background: #c8c8c8;
        color: #111;
    }

    .btn-ubah {
        position: absolute;
        top: 14px;
        right: 14px;
        background: #e8e8e8;
        border: none;
        color: #555;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-ubah:hover {
        background: #d4d4d4;
        color: #333;
    }

    .icon-user {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 2px solid #ccc;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
        color: #888;
        font-size: 16px;
        vertical-align: middle;
        flex-shrink: 0;
    }

    /* OVERLAY MODAL */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.45);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .modal-box {
        background: #fff;
        border-radius: 16px;
        padding: 32px 28px 24px;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        margin: 0 16px;
    }

    .modal-title {
        text-align: center;
        font-size: 1.2rem;
        font-weight: 800;
        color: #111;
        margin-bottom: 6px;
        line-height: 1.3;
    }

    .modal-sub {
        text-align: center;
        font-size: 0.82rem;
        color: #888;
        margin-bottom: 24px;
    }

    .form-label {
        font-size: 0.82rem;
        color: #444;
        display: block;
        margin-bottom: 4px;
    }

    .form-input {
        width: 100%;
        padding: 9px 12px;
        border-radius: 8px;
        border: 1px solid #ddd;
        font-size: 0.9rem;
        background: #f9f9f9;
        color: #555;
        box-sizing: border-box;
    }

    .form-select {
        width: 100%;
        padding: 9px 12px;
        border-radius: 8px;
        border: 1px solid #ddd;
        font-size: 0.9rem;
        background: #fff;
        box-sizing: border-box;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-modal-batal {
        padding: 9px 22px;
        border-radius: 20px;
        border: 1px solid #ddd;
        background: #fff;
        color: #555;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .btn-modal-simpan {
        padding: 9px 22px;
        border-radius: 20px;
        border: none;
        background: #f4b400;
        color: #000;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-modal-simpan:hover { background: #e0a800; }
</style>

<div class="wrapper">

    {{-- JUDUL --}}
    <div class="page-title">Verifikasi dan Tetapkan Dosen Pembimbing Mahasiswa</div>
    <div class="page-sub">Kelola verifikasi dan penetapan dosen pembimbing tugas akhir mahasiswa.</div>

    {{-- ALERT --}}
    @if(session('success'))
        <div style="background:#d4edda; color:#28a745; border:1px solid #b7dfbb;
                    border-radius:10px; padding:12px 16px; margin-bottom:16px; font-size:0.9rem;">
            <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background:#f8d7da; color:#dc3545; border:1px solid #f1aeb5;
                    border-radius:10px; padding:12px 16px; margin-bottom:16px; font-size:0.9rem;">
            <i class="fa fa-times-circle me-1"></i> {{ session('error') }}
        </div>
    @endif

    {{-- INFO GRID --}}
    <div class="info-grid">

        <div class="info-box">
            <div class="lbl"><i class="fa fa-calendar"></i> Tanggal Pengajuan</div>
            <div class="val">
                {{ \Carbon\Carbon::parse($proposal->tanggal_pengajuan)->translatedFormat('d M Y') }}
            </div>
        </div>

        <div class="info-box">
            <div class="lbl"><i class="fa fa-user"></i> NIM</div>
            <div class="val">{{ $proposal->nim_nid }}</div>
        </div>

        <div class="info-box">
            <div class="lbl"><i class="fa fa-clock"></i> Status</div>
            @php $st = strtolower(trim($proposal->status)); @endphp
            <div class="val {{ str_contains($st, 'menunggu') ? 'menunggu' : ($st == 'selesai' ? 'selesai' : 'ditolak') }}">
                {{ ucwords(str_replace('_', ' ', $proposal->status)) }}
            </div>
        </div>

        <div class="info-box">
            <div class="lbl"><i class="fa fa-users"></i> Nama</div>
            <div class="val">{{ $proposal->nama }}</div>
        </div>

        <div class="info-box">
            <div class="lbl">Judul</div>
            <div class="val" style="font-size:0.95rem;">{{ $proposal->judul }}</div>
        </div>

        <div class="info-box">
            <div class="lbl">Proposal</div>
            @if($proposal->file_proposal)
                <div class="proposal-box mt-2">
                    <div>
                        <div class="file-name">
                            <i class="fa fa-file-pdf text-danger me-1"></i>
                            {{ basename($proposal->file_proposal) }}
                        </div>
                        <div class="file-meta">
                            Diunggah pada
                            {{ \Carbon\Carbon::parse($proposal->tanggal_pengajuan)->translatedFormat('d M') }}
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $proposal->file_proposal) }}" target="_blank" class="text-dark">
                        <i class="fa fa-download"></i>
                    </a>
                </div>
            @else
                <div class="val text-muted" style="font-size:0.9rem;">Belum ada file proposal</div>
            @endif
        </div>

    </div>

    {{-- USULAN PEMBIMBING --}}
    <div class="section-title">Usulan Pembimbing</div>
    <div class="section-sub">Daftar dosen yang diusulkan mahasiswa</div>

    <div class="dosen-grid">

        {{-- Usulan 1 --}}
        <div class="dosen-card">
            <div class="dosen-label">Usulan Pembimbing 1</div>

            @if($proposal->usulan_dosen1_nama)
                <div style="display:flex; align-items:center; margin-bottom:4px;">
                    <span class="icon-user"><i class="fa fa-user"></i></span>
                    <div>
                        <div class="dosen-nama">{{ $proposal->usulan_dosen1_nama }}</div>
                        <div class="dosen-nidn">NIDN. {{ $proposal->usulan_dosen1_nidn }}</div>
                    </div>
                </div>

                <div class="dosen-tanggal mb-2">
                    Diusulkan pada<br>
                    {{ $proposal->usulan_dosen1_tanggal
                        ? \Carbon\Carbon::parse($proposal->usulan_dosen1_tanggal)->translatedFormat('d M Y')
                        : '-' }}
                </div>

                @php $s1 = strtolower($proposal->usulan_dosen1_status ?? 'menunggu'); @endphp

                @if($s1 == 'disetujui')
                    <span class="badge-disetujui">Disetujui</span>
                @elseif($s1 == 'ditolak')
                    <span class="badge-ditolak">Ditolak</span>
                @else
                    <button type="button"
                            class="badge-menunggu"
                            onclick="bukaModal(
                                1,
                                '{{ addslashes($proposal->usulan_dosen1_nama) }}',
                                '{{ $proposal->usulan_dosen1_nidn }}',
                                '{{ $proposal->nim_nid }}',
                                '{{ addslashes($proposal->nama) }}'
                            )">
                        Menunggu Verifikasi
                    </button>
                @endif
            @else
                <span class="text-muted">-</span>
            @endif
        </div>

        {{-- Usulan 2 --}}
        <div class="dosen-card">
            <div class="dosen-label">Usulan Pembimbing 2</div>

            @if($proposal->usulan_dosen2_nama)
                <div style="display:flex; align-items:center; margin-bottom:4px;">
                    <span class="icon-user"><i class="fa fa-user"></i></span>
                    <div>
                        <div class="dosen-nama">{{ $proposal->usulan_dosen2_nama }}</div>
                        <div class="dosen-nidn">NIDN. {{ $proposal->usulan_dosen2_nidn }}</div>
                    </div>
                </div>

                <div class="dosen-tanggal mb-2">
                    Diusulkan pada<br>
                    {{ $proposal->usulan_dosen2_tanggal
                        ? \Carbon\Carbon::parse($proposal->usulan_dosen2_tanggal)->translatedFormat('d M Y')
                        : '-' }}
                </div>

                @php $s2 = strtolower($proposal->usulan_dosen2_status ?? 'menunggu'); @endphp

                @if($s2 == 'disetujui')
                    <span class="badge-disetujui">Disetujui</span>
                @elseif($s2 == 'ditolak')
                    <span class="badge-ditolak">Ditolak</span>
                @else
                    <button type="button"
                            class="badge-menunggu"
                            onclick="bukaModal(
                                2,
                                '{{ addslashes($proposal->usulan_dosen2_nama) }}',
                                '{{ $proposal->usulan_dosen2_nidn }}',
                                '{{ $proposal->nim_nid }}',
                                '{{ addslashes($proposal->nama) }}'
                            )">
                        Menunggu Verifikasi
                    </button>
                @endif
            @else
                <span class="text-muted">-</span>
            @endif
        </div>

    </div>

    {{-- DOSEN PEMBIMBING --}}
    <div class="section-title">Dosen Pembimbing</div>
    <div class="section-sub">Dosen Pembimbing yang telah ditetapkan untuk Tugas Akhir ini</div>

    <div class="dosen-grid">

        {{-- Pembimbing 1 --}}
        <div class="dosen-card">
            <div class="dosen-label">Pembimbing 1</div>

            @if($proposal->dosen1_nama)

                <button type="button"
                        class="btn-ubah"
                        onclick="bukaModalUbah(
                            1,
                            '{{ addslashes($proposal->dosen1_nama) }}',
                            '{{ $proposal->dosen1_nidn }}'
                        )">
                    ✏ Ubah Pembimbing
                </button>

                <div style="display:flex; align-items:center; margin-bottom:4px;">
                    <span class="icon-user"><i class="fa fa-user"></i></span>
                    <div>
                        <div class="dosen-nama">{{ $proposal->dosen1_nama }}</div>
                        <div class="dosen-nidn">NIDN. {{ $proposal->dosen1_nidn }}</div>
                    </div>
                </div>

                <div class="dosen-tanggal">
                    Ditetapkan pada<br>
                    {{ $proposal->dosen1_tanggal
                        ? \Carbon\Carbon::parse($proposal->dosen1_tanggal)->translatedFormat('d M Y')
                        : '-' }}
                </div>

            @else

                <div style="display:flex; align-items:center; margin-bottom:8px;">
                    <span class="icon-user"><i class="fa fa-user"></i></span>
                    <span class="text-muted" style="font-size:0.9rem;">[Menunggu verifikasi]</span>
                </div>
                <div class="dosen-tanggal">Ditetapkan pada<br>-</div>

            @endif
        </div>

        {{-- Pembimbing 2 --}}
        <div class="dosen-card">
            <div class="dosen-label">Pembimbing 2</div>

            @if($proposal->dosen2_nama)

                <button type="button"
                        class="btn-ubah"
                        onclick="bukaModalUbah(
                            2,
                            '{{ addslashes($proposal->dosen2_nama) }}',
                            '{{ $proposal->dosen2_nidn }}'
                        )">
                    ✏ Ubah Pembimbing
                </button>

                <div style="display:flex; align-items:center; margin-bottom:4px;">
                    <span class="icon-user"><i class="fa fa-user"></i></span>
                    <div>
                        <div class="dosen-nama">{{ $proposal->dosen2_nama }}</div>
                        <div class="dosen-nidn">NIDN. {{ $proposal->dosen2_nidn }}</div>
                    </div>
                </div>

                <div class="dosen-tanggal">
                    Ditetapkan pada<br>
                    {{ $proposal->dosen2_tanggal
                        ? \Carbon\Carbon::parse($proposal->dosen2_tanggal)->translatedFormat('d M Y')
                        : '-' }}
                </div>

            @else

                <div style="display:flex; align-items:center; margin-bottom:8px;">
                    <span class="icon-user"><i class="fa fa-user"></i></span>
                    <span class="text-muted" style="font-size:0.9rem;">[Menunggu verifikasi]</span>
                </div>
                <div class="dosen-tanggal">Ditetapkan pada<br>-</div>

            @endif
        </div>

    </div>

    {{-- FOOTER --}}
    <div class="footer-btn">
        <a href="/proposal" class="btn-kembali">Kembali</a>

        <form action="{{ url('/proposal/' . $proposal->id . '/lanjutkan') }}" method="POST">
            @csrf
            <button type="submit"
                    style="background:#4caf7d; color:#fff; border:none;
                           padding:10px 24px; border-radius:20px; font-size:0.9rem;
                           font-weight:600; cursor:pointer; transition:0.2s;
                           display:inline-flex; align-items:center; gap:6px;"
                    onmouseover="this.style.background='#3d9c6a'; this.style.transform='translateX(3px)'"
                    onmouseout="this.style.background='#4caf7d'; this.style.transform='translateX(0)'">
                Simpan dan lanjutkan ke reviewer →
            </button>
        </form>
    </div>

</div>

{{-- ============================================================ --}}
{{-- MODAL VERIFIKASI USULAN                                      --}}
{{-- ============================================================ --}}
<div id="modalVerifikasi" class="modal-overlay">
    <div class="modal-box">

        <div class="modal-title">
            Form Penetapan Dosen<br>
            Pembimbing <span id="modalUrutanLabel">1</span> TA
        </div>
        <div class="modal-sub">
            Lengkapi data berikut untuk menetapkan dosen pembimbing tugas akhir mahasiswa.
        </div>

        <form id="formAcc" method="POST" action="">
            @csrf
            <input type="hidden" name="aksi" value="acc">

            <div style="margin-bottom:14px;">
                <label class="form-label">NIM</label>
                <input type="text" id="modalNim" readonly class="form-input">
            </div>

            <div style="margin-bottom:14px;">
                <label class="form-label">Nama</label>
                <input type="text" id="modalNama" readonly class="form-input">
            </div>

            <div style="margin-bottom:20px;">
                <label class="form-label">
                    Usulan Pembimbing <span id="modalUrutanLabel2">1</span>
                </label>
                <div style="display:flex; align-items:center; gap:8px;">
                    <input type="text" id="modalUsulanDosen" readonly class="form-input" style="flex:1;">

                    <button type="submit"
                            style="width:32px; height:32px; border-radius:50%; border:none;
                                   background:#e8f5e9; color:#28a745; font-size:1rem;
                                   cursor:pointer; display:flex; align-items:center;
                                   justify-content:center; flex-shrink:0;">
                        ✓
                    </button>

                    <button type="button"
                            onclick="tampilkanDropdown()"
                            style="width:32px; height:32px; border-radius:50%; border:none;
                                   background:#fdecea; color:#dc3545; font-size:1rem;
                                   cursor:pointer; display:flex; align-items:center;
                                   justify-content:center; flex-shrink:0;">
                        ✗
                    </button>
                </div>
            </div>

            <div id="footerAcc" style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="tutupModal()" class="btn-modal-batal">Kembali</button>
            </div>
        </form>

        <div id="sectionTolak" style="display:none;">
            <form id="formTolak" method="POST" action="">
                @csrf
                <input type="hidden" name="aksi" value="tolak">

                <div style="margin-bottom:14px;">
                    <label class="form-label">
                        Pembimbing <span id="modalUrutanLabel3">1</span>
                    </label>
                    <select name="dosen_pengganti" class="form-select">
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($dosenList as $d)
                            @if($d->nim_nid != $proposal->dosen1_nidn && $d->nim_nid != $proposal->dosen2_nidn)
                                <option value="{{ $d->nim_nid }}">{{ $d->nim_nid }} - {{ $d->nama }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" onclick="sembunyikanDropdown()" class="btn-modal-batal">Kembali</button>
                    <button type="submit" class="btn-modal-simpan">Kirim</button>
                </div>
            </form>
        </div>

    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL UBAH PEMBIMBING                                        --}}
{{-- ============================================================ --}}
<div id="modalUbah" class="modal-overlay">
    <div class="modal-box">

        <div class="modal-title">
            Ubah Dosen Pembimbing <span id="ubahUrutanLabel">1</span>
        </div>
        <div class="modal-sub">
            Pilih dosen pengganti untuk pembimbing ini.
        </div>

        <form id="formUbah" method="POST" action="">
            @csrf

            <div style="margin-bottom:14px;">
                <label class="form-label">Pembimbing Saat Ini</label>
                <input type="text" id="ubahDosenSekarang" readonly class="form-input">
            </div>

            <div style="margin-bottom:20px;">
                <label class="form-label">Ganti Dengan</label>
                <select name="dosen_baru" class="form-select" id="ubahDosenBaru">
                    <option value="">-- Pilih Dosen --</option>
                    @foreach($dosenList as $d)
                        <option value="{{ $d->nim_nid }}">{{ $d->nim_nid }} - {{ $d->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="tutupModalUbah()" class="btn-modal-batal">Batal</button>
                <button type="submit" class="btn-modal-simpan">Simpan</button>
            </div>

        </form>

    </div>
</div>

<script>

// ============================================================
// MODAL VERIFIKASI USULAN
// ============================================================
function bukaModal(urutan, nama, nidn, nimMhs, namaMhs) {
    document.getElementById('modalUrutanLabel').innerText  = urutan;
    document.getElementById('modalUrutanLabel2').innerText = urutan;
    document.getElementById('modalUrutanLabel3').innerText = urutan;
    document.getElementById('modalNim').value              = nimMhs;
    document.getElementById('modalNama').value             = namaMhs;
    document.getElementById('modalUsulanDosen').value      = nidn + ' - ' + nama;

    var baseUrl = "{{ url('/proposal/' . $proposal->id . '/tetapkan') }}/" + urutan;
    document.getElementById('formAcc').action   = baseUrl;
    document.getElementById('formTolak').action = baseUrl;

    sembunyikanDropdown();
    document.getElementById('modalVerifikasi').style.display = 'flex';
}

function tampilkanDropdown() {
    document.getElementById('footerAcc').style.display    = 'none';
    document.getElementById('sectionTolak').style.display = 'block';
}

function sembunyikanDropdown() {
    document.getElementById('footerAcc').style.display    = 'flex';
    document.getElementById('sectionTolak').style.display = 'none';
}

function tutupModal() {
    document.getElementById('modalVerifikasi').style.display = 'none';
}

document.getElementById('modalVerifikasi').addEventListener('click', function(e) {
    if (e.target === this) tutupModal();
});

// ============================================================
// MODAL UBAH PEMBIMBING
// ============================================================
function bukaModalUbah(urutan, nama, nidn) {
    document.getElementById('ubahUrutanLabel').innerText = urutan;
    document.getElementById('ubahDosenSekarang').value   = nidn + ' - ' + nama;
    document.getElementById('ubahDosenBaru').value       = '';

    var url = "{{ url('/proposal/' . $proposal->id . '/ubah-pembimbing') }}/" + urutan;
    document.getElementById('formUbah').action = url;

    document.getElementById('modalUbah').style.display = 'flex';
}

function tutupModalUbah() {
    document.getElementById('modalUbah').style.display = 'none';
}

document.getElementById('modalUbah').addEventListener('click', function(e) {
    if (e.target === this) tutupModalUbah();
});

</script>

@endsection