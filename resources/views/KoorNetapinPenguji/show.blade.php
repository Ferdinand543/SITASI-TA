@extends('layouts.app')

@section('content')
<style>
    .hero-section {
        background-image: url('{{ asset('images/bg.jpeg') }}');
        background-size: cover; background-position: center;
        border-radius: 20px; padding: 48px 40px;
        min-height: 180px; display: flex; align-items: center; margin-bottom: 24px;
    }
    .hero-content h2 { color: #735C00; font-size: 1.8rem; font-weight: 800; margin-bottom: 4px; }
    .hero-content p  { color: #735C00; font-size: 0.9rem; margin: 0; }

    .info-card {
        background: #fff; border-radius: 16px; border: 1px solid #f0f0f0;
        padding: 20px 28px; margin-bottom: 24px; display: flex; align-items: center;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }
    .info-section { flex: 1; padding: 0 24px; border-right: 1px solid #f0f0f0; }
    .info-section:first-child { padding-left: 0; }
    .info-section:last-child  { border-right: none; }
    .info-section .label { font-size: 0.7rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .info-section .value { font-size: 0.95rem; font-weight: 700; color: #1f2937; }
    .badge-total { background: #FEF9C3; color: #735C00; font-size: 0.82rem; font-weight: 700; padding: 6px 18px; border-radius: 20px; display: inline-block; }

    .section-card { background: #fff; border-radius: 16px; border: 1px solid #f0f0f0; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,0.04); margin-bottom: 24px; }
    .section-header { padding: 18px 24px; border-bottom: 1px solid #f5f5f5; display: flex; align-items: center; justify-content: space-between; }
    .section-header h6 { font-weight: 700; font-size: 0.95rem; color: #1f2937; margin: 0; display: flex; align-items: center; gap: 8px; }

    .custom-table { width: 100%; border-collapse: collapse; }
    .custom-table thead tr { background: #f9fafb; }
    .custom-table thead th { padding: 12px 20px; font-size: 0.72rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #f0f0f0; }
    .custom-table tbody tr { border-bottom: 1px solid #f5f5f5; transition: background 0.15s; }
    .custom-table tbody tr:hover { background: #fffdf5; }
    .custom-table tbody td { padding: 16px 20px; font-size: 0.85rem; color: #374151; vertical-align: top; }

    .dosen-info-cell .role-label { font-size: 0.7rem; font-weight: 700; color: #9ca3af; font-style: italic; margin-bottom: 2px; }
    .dosen-info-cell .nama { font-weight: 600; color: #374151; font-size: 0.82rem; }

    .search-wrap { position: relative; }
    .search-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.85rem; }
    .search-box { border-radius: 12px; border: 1.5px solid #e5e7eb; padding: 10px 16px 10px 40px; font-size: 0.88rem; width: 100%; outline: none; font-family: 'Hanken Grotesk', sans-serif; color: #374151; transition: border 0.2s; background: #f9fafb; box-sizing: border-box; }
    .search-box:focus { border-color: #FACC15; background: #fff; }

    .btn-back { padding: 8px 18px; border-radius: 10px; border: 1.5px solid #e5e7eb; background: #fff; font-size: 0.82rem; font-weight: 600; color: #735C00; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px; transition: 0.2s; }
    .btn-back:hover { background: #FACC15; border-color: #FACC15; color: #735C00; }

    .btn-tambah { padding: 11px 28px; border-radius: 50px; background: #FACC15; border: none; font-size: 0.85rem; font-weight: 700; color: #735C00; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; text-decoration: none; }
    .btn-tambah:hover { background: #eab308; }

    .btn-batal { padding: 11px 28px; border-radius: 50px; border: 1.5px solid #374151; background: #fff; font-size: 0.85rem; font-weight: 600; color: #374151; text-decoration: none; display: inline-flex; align-items: center; transition: 0.2s; cursor: pointer; }
    .btn-batal:hover { background: #f9fafb; }

    .empty-big { text-align: center; padding: 60px 20px; }
    .empty-big h5 { font-size: 1rem; font-weight: 700; color: #374151; margin-bottom: 8px; }
    .empty-big p { font-size: 0.82rem; color: #9ca3af; max-width: 320px; margin: 0 auto 4px; }
    .badge-count { background: #f3f4f6; color: #6b7280; font-size: 0.75rem; font-weight: 600; padding: 3px 12px; border-radius: 20px; display: inline-block; margin-top: 8px; }

    .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 18px; border-radius: 10px; margin-bottom: 16px; font-size: 0.85rem; }
    .alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 18px; border-radius: 10px; margin-bottom: 16px; font-size: 0.85rem; }

    /* ===== MODAL ===== */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 9999; align-items: center; justify-content: center; }
    .modal-overlay.active { display: flex; }
    .modal-box { background: #fff; border-radius: 20px; width: 100%; max-width: 740px; max-height: 90vh; overflow-y: auto; box-shadow: 0 8px 40px rgba(0,0,0,0.18); display: flex; flex-direction: column; }
    .modal-head { padding: 20px 24px 16px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: flex-start; justify-content: space-between; position: sticky; top: 0; background: #fff; z-index: 1; }
    .modal-head h5 { font-weight: 800; font-size: 1rem; color: #1f2937; margin: 0 0 4px; }
    .modal-head p  { font-size: 0.78rem; color: #9ca3af; margin: 0; }
    .modal-close { background: none; border: none; font-size: 1.2rem; color: #9ca3af; cursor: pointer; padding: 0; line-height: 1; }
    .modal-close:hover { color: #374151; }
    .modal-body { padding: 20px 24px; flex: 1; }
    .modal-foot { padding: 16px 24px; border-top: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; position: sticky; bottom: 0; background: #fff; }

    /* Info dosen di modal */
    .modal-dosen-label { font-size: 0.7rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
    .modal-dosen-card {
        background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px;
        padding: 12px 16px; display: inline-block; margin-bottom: 16px;
    }
    .modal-dosen-card .name { font-weight: 700; font-size: 0.88rem; color: #735C00; margin-bottom: 4px; }
    .modal-dosen-card .sub  { font-size: 0.73rem; color: #92400e; line-height: 1.6; }

    .badge-belum { background: #fef3c7; color: #92400e; font-size: 0.7rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; white-space: nowrap; }
    .badge-p1    { background: #d1fae5; color: #065f46; font-size: 0.7rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; white-space: nowrap; }

    .btn-peran { padding: 5px 14px; border-radius: 8px; border: 1.5px solid #e5e7eb; background: #f9fafb; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: 0.15s; margin-right: 4px; color: #374151; }
    .btn-peran.active-p1 { background: #735C00; border-color: #735C00; color: #fff; }
    .btn-peran.active-p2 { background: #735C00; border-color: #735C00; color: #fff; }
    .btn-peran:not(.active-p1):not(.active-p2):hover { background: #f0f0f0; }
    .btn-peran:disabled { opacity: 0.35; cursor: not-allowed; }

    .summary-bar { background: #FACC15; border-radius: 10px; padding: 10px 18px; display: flex; align-items: center; justify-content: space-between; font-size: 0.8rem; font-weight: 700; color: #735C00; margin-top: 16px; }
    .summary-num { background: #735C00; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800; margin-right: 8px; }

    .cb-mhs { width: 16px; height: 16px; accent-color: #FACC15; cursor: pointer; }

    .peran-section { margin-top: 20px; border-top: 2px dashed #f0f0f0; padding-top: 16px; }
    .peran-title { font-size: 0.78rem; font-weight: 700; color: #6b7280; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }

    .btn-pilih-mhs { padding: 10px 22px; border-radius: 50px; background: #FACC15; border: none; font-size: 0.85rem; font-weight: 700; color: #735C00; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
    .btn-pilih-mhs:hover { background: #eab308; }
</style>

{{-- HERO --}}
<div class="hero-section">
    <div class="hero-content">
        <h2>Kelola Penetapan Dosen Penguji</h2>
        <p>Mengelola mahasiswa peserta ujian tugas akhir yang ditugaskan kepada dosen penguji.</p>
    </div>
</div>

<a href="{{ route('dosen.penguji.index') }}" class="btn-back">
    <i class="fa-solid fa-arrow-left"></i> Kembali
</a>

@if(session('success'))
<div class="alert-success"><i class="fa-solid fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert-error"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</div>
@endif

{{-- INFO DOSEN --}}
<div class="info-card">
    <div class="info-section">
        <div class="label">Dosen Penguji</div>
        <div class="value">{{ $dosen->nama }}</div>
    </div>
    <div class="info-section">
        <div class="label">NIDN</div>
        <div class="value">{{ $dosen->nim_nid }}</div>
    </div>
    <div class="info-section">
        <div class="label">Total Mahasiswa Diuji</div>
        <span class="badge-total">{{ $mahasiswaSudahDitetapkan->unique('pengajuan_id')->count() }} Mahasiswa</span>
    </div>
</div>

@if($mahasiswaSudahDitetapkan->count() > 0)

    {{-- TABEL SUDAH DITETAPKAN --}}
    <div class="section-card">
        <div class="section-header">
            <h6><i class="fa-solid fa-calendar-days" style="color:#FACC15;"></i> Daftar Mahasiswa Reviewer</h6>
            <div style="display:flex;align-items:center;gap:12px;">
                <span style="font-size:0.78rem;color:#9ca3af;">{{ $mahasiswaSudahDitetapkan->count() }} mahasiswa</span>
                <div class="search-wrap" style="width:220px;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchSudah" class="search-box" placeholder="Cari nama atau NIM...">
                </div>
            </div>
        </div>
        <table class="custom-table">
            <thead>
                <tr>
                    <th>NIM</th><th>MAHASISWA</th><th>JUDUL</th><th>DOSEN PEMBIMBING</th><th>DOSEN PENGUJI</th>
                </tr>
            </thead>
            <tbody id="tableSudah">
                @foreach($mahasiswaSudahDitetapkan as $mhs)
                <tr>
                    <td style="color:#9ca3af;font-size:0.82rem;">{{ $mhs->nim_nid }}</td>
                    <td style="font-weight:600;">{{ $mhs->nama }}</td>
                    <td style="max-width:200px;font-size:0.82rem;">{{ $mhs->judul_ta }}</td>
                    <td>
                        @if($mhs->pembimbing1)
                        <div class="dosen-info-cell"><div class="role-label">Pembimbing 1</div><div class="nama">{{ $mhs->pembimbing1 }}</div></div>
                        @endif
                        @if($mhs->pembimbing2)
                        <div class="dosen-info-cell" style="margin-top:8px;"><div class="role-label">Pembimbing 2</div><div class="nama">{{ $mhs->pembimbing2 }}</div></div>
                        @endif
                        @if(!$mhs->pembimbing1 && !$mhs->pembimbing2)
                        <span style="color:#9ca3af;font-size:0.78rem;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($mhs->penguji1)
                        <div class="dosen-info-cell"><div class="role-label">Penguji 1</div><div class="nama">{{ $mhs->penguji1 }}</div></div>
                        @endif
                        @if($mhs->penguji2)
                        <div class="dosen-info-cell" style="margin-top:8px;"><div class="role-label">Penguji 2</div><div class="nama">{{ $mhs->penguji2 }}</div></div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- TABEL BELUM DITETAPKAN --}}
    @if($mahasiswaBelumDitetapkan->count() > 0)
    <div class="section-card">
        <div class="section-header" style="padding:20px 24px;">
            <h6><i class="fa-solid fa-user-graduate" style="color:#735C00;"></i> Daftar Mahasiswa</h6>
            <div class="search-wrap" style="width:300px;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchBelum" class="search-box" placeholder="Cari mahasiswa...">
            </div>
        </div>
        <table class="custom-table">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>NAMA MAHASISWA</th>
                    <th>JUDUL TUGAS AKHIR</th>
                </tr>
            </thead>
            <tbody id="tableBelum">
                @foreach($mahasiswaBelumDitetapkan as $mhs)
                <tr>
                    <td style="color:#9ca3af;font-size:0.82rem;">{{ $mhs->nim_nid }}</td>
                    <td style="font-weight:600;">{{ $mhs->nama }}</td>
                    <td style="font-size:0.82rem;">{{ Str::limit($mhs->judul_ta, 50) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding:20px 24px;display:flex;justify-content:space-between;align-items:center;border-top:1px solid #f5f5f5;">
            <a href="{{ route('dosen.penguji.index') }}" class="btn-batal">Batal</a>
            <button type="button" class="btn-tambah" onclick="bukaModal()">
                <i class="fa-solid fa-user-plus"></i> Tambahkan Mahasiswa
            </button>
        </div>
    </div>
    @endif

@else

    {{-- EMPTY STATE --}}
    <div class="section-card">
        <div class="section-header" style="padding:20px 24px;">
            <div>
                <h6 style="margin-bottom:4px;"><i class="fa-solid fa-calendar-days" style="color:#FACC15;"></i> Daftar Mahasiswa Reviewer</h6>
                <p style="font-size:0.78rem;color:#9ca3af;margin:0;">Menampilkan semua mahasiswa aktif yang ditinjau oleh reviewer ini.</p>
            </div>
        </div>
        <div class="empty-big">
            <div style="width:100px;height:100px;background:#FEF9C3;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                <i class="fa-solid fa-folder-open" style="font-size:2.5rem;color:#FACC15;"></i>
            </div>
            <h5>Belum Ada Mahasiswa Ditugaskan</h5>
            <span class="badge-count">0 Mahasiswa Direview</span>
            <p style="margin-top:12px;">Klik "Tambah Mahasiswa" untuk menetapkan mahasiswa kepada dosen penguji ini.</p>
        </div>
    </div>

    {{-- TABEL BELUM DITETAPKAN (EMPTY STATE) --}}
    @if($mahasiswaBelumDitetapkan->count() > 0)
    <div class="section-card">
        <div class="section-header" style="padding:20px 24px;">
            <h6><i class="fa-solid fa-user-graduate" style="color:#735C00;"></i> Daftar Mahasiswa</h6>
            <div class="search-wrap" style="width:300px;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchBelum" class="search-box" placeholder="Cari mahasiswa...">
            </div>
        </div>
        <table class="custom-table">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>NAMA MAHASISWA</th>
                    <th>JUDUL TUGAS AKHIR</th>
                </tr>
            </thead>
            <tbody id="tableBelum">
                @foreach($mahasiswaBelumDitetapkan as $mhs)
                <tr>
                    <td style="color:#9ca3af;font-size:0.82rem;">{{ $mhs->nim_nid }}</td>
                    <td style="font-weight:600;">{{ $mhs->nama }}</td>
                    <td style="font-size:0.82rem;">{{ Str::limit($mhs->judul_ta, 50) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding:20px 24px;display:flex;justify-content:space-between;align-items:center;border-top:1px solid #f5f5f5;">
            <a href="{{ route('dosen.penguji.index') }}" class="btn-batal">Batal</a>
            <button type="button" class="btn-tambah" onclick="bukaModal()">
                <i class="fa-solid fa-user-plus"></i> Tambahkan Mahasiswa
            </button>
        </div>
    </div>
    @endif

@endif

{{-- ===================== MODAL ===================== --}}
<div class="modal-overlay" id="modalTambah">
    <div class="modal-box">

        {{-- Header --}}
        <div class="modal-head">
            <div>
                <h5>Tambah Mahasiswa Pengujian</h5>
                <p>Pilih mahasiswa yang akan ditugaskan kepada dosen penguji ini.</p>
            </div>
            <button class="modal-close" onclick="tutupModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>

        {{-- Body --}}
        <div class="modal-body">

            {{-- Info dosen --}}
            <div style="margin-bottom:16px;">
                <div class="modal-dosen-label">Dosen Penguji</div>
                <div class="modal-dosen-card">
                    <div class="name">{{ $dosen->nama }}</div>
                    <div class="sub">NIDN: {{ $dosen->nim_nid }}</div>
                    <div class="sub">Jumlah Mahasiswa Saat Ini: {{ $mahasiswaSudahDitetapkan->unique('pengajuan_id')->count() }} mahasiswa</div>
                </div>
            </div>

            {{-- Search --}}
            <div class="search-wrap" style="margin-bottom:14px;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchModal" class="search-box" placeholder="Cari NIM atau nama mahasiswa...">
            </div>

            {{-- Tabel checklist --}}
            <table class="custom-table" style="border:1px solid #f0f0f0;border-radius:10px;overflow:hidden;margin-bottom:0;">
                <thead>
                    <tr>
                        <th style="width:36px;">
                            <input type="checkbox" class="cb-mhs" id="cbAll" title="Pilih semua">
                        </th>
                        <th>NIM</th>
                        <th>NAMA MAHASISWA</th>
                        <th>JUDUL TA</th>
                        <th>STATUS PENGUJI</th>
                    </tr>
                </thead>
                <tbody id="modalTableBody">
                    @foreach($mahasiswaBelumDitetapkan as $mhs)
                    <tr data-pengajuan="{{ $mhs->pengajuan_id }}"
                        data-nama="{{ $mhs->nama }}"
                        data-nim="{{ $mhs->nim_nid }}"
                        data-judul="{{ $mhs->judul_ta }}"
                        data-ada-p1="{{ $mhs->ada_penguji1 ? '1' : '0' }}"
                        data-ada-p2="{{ $mhs->ada_penguji2 ? '1' : '0' }}">
                        <td><input type="checkbox" class="cb-mhs cb-row" value="{{ $mhs->pengajuan_id }}"></td>
                        <td style="color:#9ca3af;font-size:0.82rem;">{{ $mhs->nim_nid }}</td>
                        <td style="font-weight:600;">{{ $mhs->nama }}</td>
                        <td style="font-size:0.78rem;">{{ Str::limit($mhs->judul_ta, 35) }}</td>
                        <td>
                            @if(!$mhs->ada_penguji1 && !$mhs->ada_penguji2)
                                <span class="badge-belum">Belum Memiliki Penguji</span>
                            @elseif($mhs->ada_penguji1 && !$mhs->ada_penguji2)
                                <span class="badge-p1">Sudah Penguji 1</span>
                            @else
                                <span class="badge-belum">Sudah Penguji 2</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Tombol Pilih Mahasiswa — tepat di bawah tabel checklist --}}
            <div style="display:flex;justify-content:flex-end;align-items:center;margin-top:14px;">
                <button type="button" class="btn-pilih-mhs" onclick="pilihMahasiswa()">
                    <i class="fa-solid fa-user-check"></i> Pilih Mahasiswa
                </button>
            </div>

            {{-- Section peran — selalu tampil, kosong kalau belum ada yang diceklis --}}
            <div class="peran-section" id="peranSection">
                <div class="peran-title">
                    <i class="fa-solid fa-shield-halved" style="color:#735C00;"></i>
                    Peran Penguji
                </div>
                <table class="custom-table" style="border:1px solid #f0f0f0;border-radius:10px;overflow:hidden;">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>NAMA MAHASISWA</th>
                            <th>PERAN PENGUJI</th>
                        </tr>
                    </thead>
                    <tbody id="peranTableBody"></tbody>
                </table>

                <div class="summary-bar">
                    <div style="display:flex;align-items:center;">
                        <span class="summary-num" id="summaryAngka">0</span>
                        <span id="summaryJumlah"> Mahasiswa Dipilih</span>
                    </div>
                    <span>
                        <span id="summaryP1">👤 Penguji 1: 0 Mahasiswa</span>
                        &nbsp;|&nbsp;
                        <span id="summaryP2">👤 Penguji 2: 0 Mahasiswa</span>
                    </span>
                </div>
            </div>

        </div>

        {{-- Footer sticky bawah: Batal + Simpan Penugasan --}}
        <div class="modal-foot">
            <button type="button" class="btn-batal" onclick="tutupModal()">Batal</button>
            <button type="button" class="btn-tambah" onclick="simpanPenugasan()">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Penugasan
            </button>
        </div>

    </div>
</div>

{{-- Hidden form submit --}}
<form id="formSimpan" method="POST" action="{{ route('penguji.tetapkan', $dosen->nim_nid) }}" style="display:none;">
    @csrf
    <div id="hiddenInputs"></div>
</form>

<script>
    function bukaModal() {
        document.getElementById('modalTambah').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function tutupModal() {
        document.getElementById('modalTambah').classList.remove('active');
        document.body.style.overflow = '';
        resetModal();
    }

    function resetModal() {
        document.querySelectorAll('.cb-row').forEach(cb => cb.checked = false);
        document.getElementById('cbAll').checked = false;
        document.getElementById('peranTableBody').innerHTML = '';
        document.getElementById('searchModal').value = '';
        document.querySelectorAll('#modalTableBody tr').forEach(r => r.style.display = '');
        updateSummary();
    }

    document.getElementById('cbAll').addEventListener('change', function () {
        document.querySelectorAll('.cb-row').forEach(cb => {
            const row = cb.closest('tr');
            if (row.style.display !== 'none') cb.checked = this.checked;
        });
    });

    document.getElementById('searchModal').addEventListener('input', function () {
        const kw = this.value.toLowerCase();
        document.querySelectorAll('#modalTableBody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(kw) ? '' : 'none';
        });
    });

    function pilihMahasiswa() {
        const checked = document.querySelectorAll('.cb-row:checked');
        if (checked.length === 0) {
            alert('Pilih minimal 1 mahasiswa terlebih dahulu.');
            return;
        }

        const tbody = document.getElementById('peranTableBody');
        const existingIds = Array.from(tbody.querySelectorAll('tr')).map(r => r.dataset.pengajuan);
        const checkedIds  = Array.from(checked).map(cb => cb.value);

        // Hapus baris yang sudah tidak diceklis
        tbody.querySelectorAll('tr').forEach(tr => {
            if (!checkedIds.includes(tr.dataset.pengajuan)) tr.remove();
        });

        // Tambah baris yang baru diceklis
        checked.forEach(cb => {
            const pengajuanId = cb.value;
            if (existingIds.includes(pengajuanId)) return;

            const row          = cb.closest('tr');
            const nama         = row.dataset.nama;
            const nim          = row.dataset.nim;
            const adaP1        = row.dataset.adaP1 === '1';
            const adaP2        = row.dataset.adaP2 === '1';
            const defaultPeran = adaP1 ? 2 : 1;

            const tr = document.createElement('tr');
            tr.dataset.pengajuan = pengajuanId;
            tr.dataset.peran     = defaultPeran;

            tr.innerHTML = `
                <td style="color:#9ca3af;font-size:0.82rem;">${nim}</td>
                <td style="font-weight:600;font-size:0.85rem;">${nama}</td>
                <td>
                    <button type="button"
                        class="btn-peran ${defaultPeran === 1 ? 'active-p1' : ''}"
                        id="p1-${pengajuanId}"
                        onclick="setPeran('${pengajuanId}', 1)"
                        ${adaP1 ? 'disabled' : ''}>Penguji 1</button>
                    <button type="button"
                        class="btn-peran ${defaultPeran === 2 ? 'active-p2' : ''}"
                        id="p2-${pengajuanId}"
                        onclick="setPeran('${pengajuanId}', 2)"
                        ${adaP2 ? 'disabled' : ''}>Penguji 2</button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        updateSummary();
    }

    function setPeran(pengajuanId, peran) {
        const row = document.querySelector(`#peranTableBody tr[data-pengajuan="${pengajuanId}"]`);
        if (!row) return;
        row.dataset.peran = peran;

        const btn1 = document.getElementById(`p1-${pengajuanId}`);
        const btn2 = document.getElementById(`p2-${pengajuanId}`);
        if (btn1 && !btn1.disabled) btn1.classList.toggle('active-p1', peran === 1);
        if (btn2 && !btn2.disabled) btn2.classList.toggle('active-p2', peran === 2);

        updateSummary();
    }

    function updateSummary() {
        const rows = document.querySelectorAll('#peranTableBody tr');
        let p1 = 0, p2 = 0;
        rows.forEach(r => { parseInt(r.dataset.peran) === 1 ? p1++ : p2++; });
        document.getElementById('summaryAngka').textContent = rows.length;
        document.getElementById('summaryP1').textContent    = `👤 Penguji 1: ${p1} Mahasiswa`;
        document.getElementById('summaryP2').textContent    = `👤 Penguji 2: ${p2} Mahasiswa`;
    }

    function simpanPenugasan() {
        const rows = document.querySelectorAll('#peranTableBody tr');
        if (rows.length === 0) { alert('Belum ada mahasiswa dipilih.'); return; }

        const hiddenInputs = document.getElementById('hiddenInputs');
        hiddenInputs.innerHTML = '';
        rows.forEach((row, i) => {
            hiddenInputs.innerHTML += `
                <input type="hidden" name="penugasan[${i}][pengajuan_id]" value="${row.dataset.pengajuan}">
                <input type="hidden" name="penugasan[${i}][urutan]" value="${row.dataset.peran}">
            `;
        });
        document.getElementById('formSimpan').submit();
    }

    const searchSudah = document.getElementById('searchSudah');
    if (searchSudah) {
        searchSudah.addEventListener('input', function () {
            const kw = this.value.toLowerCase();
            document.querySelectorAll('#tableSudah tr').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(kw) ? '' : 'none';
            });
        });
    }

    const searchBelum = document.getElementById('searchBelum');
    if (searchBelum) {
        searchBelum.addEventListener('input', function () {
            const kw = this.value.toLowerCase();
            document.querySelectorAll('#tableBelum tr').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(kw) ? '' : 'none';
            });
        });
    }

    document.getElementById('modalTambah').addEventListener('click', function (e) {
        if (e.target === this) tutupModal();
    });

    updateSummary();
</script>

@endsection