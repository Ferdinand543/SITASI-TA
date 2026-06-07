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

    .tab-btn {
        padding: 8px 20px; border-radius: 10px; border: 1.5px solid #e5e7eb;
        background: #fff; font-size: 0.82rem; font-weight: 600; color: #735C00;
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
        transition: 0.2s; text-decoration: none;
    }
    .tab-btn.active, .tab-btn:hover { background: #FACC15; border-color: #FACC15; color: #735C00; }

    .search-wrap { position: relative; }
    .search-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.85rem; }
    .search-box { border-radius: 12px; border: 1.5px solid #e5e7eb; padding: 10px 16px 10px 40px; font-size: 0.88rem; width: 100%; outline: none; font-family: 'Hanken Grotesk', sans-serif; color: #374151; transition: border 0.2s; background: #f9fafb; box-sizing: border-box; }
    .search-box:focus { border-color: #FACC15; background: #fff; }

    .filter-select { border-radius: 12px; border: 1.5px solid #e5e7eb; padding: 10px 16px; font-size: 0.88rem; outline: none; font-family: 'Hanken Grotesk', sans-serif; color: #374151; background: #f9fafb; cursor: pointer; transition: border 0.2s; }
    .filter-select:focus { border-color: #FACC15; background: #fff; }

    .btn-reset { padding: 10px 18px; border-radius: 12px; border: 1.5px solid #e5e7eb; background: #fff; font-size: 0.82rem; font-weight: 600; color: #374151; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: 0.2s; }
    .btn-reset:hover { background: #f9fafb; }

    .table-card { background: #fff; border-radius: 16px; border: 1px solid #f0f0f0; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,0.04); }
    .table-header-bar { padding: 20px 24px; border-bottom: 1px solid #f5f5f5; }
    .table-header-bar h6 { font-weight: 700; font-size: 1rem; color: #1f2937; margin: 0 0 4px; }
    .table-header-bar p { font-size: 0.78rem; color: #9ca3af; margin: 0; }

    .badge-total-mhs { background: #FEF9C3; color: #735C00; font-size: 0.82rem; font-weight: 700; padding: 8px 18px; border-radius: 10px; display: inline-flex; align-items: center; gap: 6px; }

    .custom-table { width: 100%; border-collapse: collapse; }
    .custom-table thead tr { background: #f9fafb; }
    .custom-table thead th { padding: 12px 20px; font-size: 0.72rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #f0f0f0; }
    .custom-table tbody tr { border-bottom: 1px solid #f5f5f5; transition: background 0.15s; }
    .custom-table tbody tr:hover { background: #fffdf5; }
    .custom-table tbody td { padding: 16px 20px; font-size: 0.85rem; color: #374151; vertical-align: top; }

    .dosen-info-cell .role-label { font-size: 0.68rem; font-weight: 700; color: #9ca3af; font-style: italic; margin-bottom: 2px; }
    .dosen-info-cell .nama { font-weight: 600; color: #374151; font-size: 0.8rem; }

    .badge-sudah { background: #d1fae5; color: #065f46; font-size: 0.72rem; font-weight: 700; padding: 4px 12px; border-radius: 20px; white-space: nowrap; }
    .badge-belum { background: #fef3c7; color: #92400e; font-size: 0.72rem; font-weight: 700; padding: 4px 12px; border-radius: 20px; white-space: nowrap; }

    .table-footer { padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f5f5f5; }
    .btn-kelola { padding: 11px 28px; border-radius: 50px; background: #FACC15; border: none; font-size: 0.85rem; font-weight: 700; color: #735C00; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
    .btn-kelola:hover { background: #eab308; }
    .btn-back { padding: 8px 18px; border-radius: 10px; border: 1.5px solid #e5e7eb; background: #fff; font-size: 0.82rem; font-weight: 600; color: #735C00; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: 0.2s; }
    .btn-back:hover { background: #FACC15; border-color: #FACC15; }

    /* ===== MODAL ===== */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 9999; align-items: center; justify-content: center; }
    .modal-overlay.active { display: flex; }
    .modal-box { background: #fff; border-radius: 20px; width: 100%; max-width: 780px; max-height: 90vh; overflow-y: auto; box-shadow: 0 8px 40px rgba(0,0,0,0.18); display: flex; flex-direction: column; }
    .modal-head { padding: 20px 24px 16px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: flex-start; justify-content: space-between; position: sticky; top: 0; background: #fff; z-index: 1; }
    .modal-head h5 { font-weight: 800; font-size: 1rem; color: #1f2937; margin: 0 0 4px; }
    .modal-head p  { font-size: 0.78rem; color: #9ca3af; margin: 0; }
    .modal-close { background: none; border: none; font-size: 1.2rem; color: #9ca3af; cursor: pointer; padding: 0; }
    .modal-close:hover { color: #374151; }
    .modal-body { padding: 20px 24px; flex: 1; }
    .modal-foot { padding: 16px 24px; border-top: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; position: sticky; bottom: 0; background: #fff; }

    .modal-label { font-size: 0.7rem; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }

    /* Dropdown dosen */
    .dropdown-dosen { width: 100%; border-radius: 12px; border: 1.5px solid #e5e7eb; padding: 11px 16px; font-size: 0.88rem; outline: none; font-family: 'Hanken Grotesk', sans-serif; color: #374151; background: #f9fafb; cursor: pointer; transition: border 0.2s; margin-bottom: 16px; }
    .dropdown-dosen:focus { border-color: #FACC15; background: #fff; }

    /* Card dosen selected */
    .modal-dosen-card { background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 12px 16px; margin-bottom: 16px; display: none; }
    .modal-dosen-card.visible { display: block; }
    .modal-dosen-card .name { font-weight: 700; font-size: 0.88rem; color: #735C00; margin-bottom: 4px; }
    .modal-dosen-card .sub  { font-size: 0.73rem; color: #92400e; line-height: 1.6; }

    /* Placeholder state */
    .placeholder-state { text-align: center; padding: 40px 20px; color: #9ca3af; }
    .placeholder-state i { font-size: 2rem; margin-bottom: 10px; display: block; color: #d1d5db; }
    .placeholder-state p { font-size: 0.82rem; }

    .badge-belum-modal { background: #fef3c7; color: #92400e; font-size: 0.7rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; white-space: nowrap; }
    .badge-p1-modal    { background: #d1fae5; color: #065f46; font-size: 0.7rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; white-space: nowrap; }

    .cb-mhs { width: 16px; height: 16px; accent-color: #FACC15; cursor: pointer; }

    .btn-peran { padding: 5px 14px; border-radius: 8px; border: 1.5px solid #e5e7eb; background: #f9fafb; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: 0.15s; margin-right: 4px; color: #374151; }
    .btn-peran.active-p1, .btn-peran.active-p2 { background: #735C00; border-color: #735C00; color: #fff; }
    .btn-peran:disabled { opacity: 0.35; cursor: not-allowed; }

    .peran-section { margin-top: 20px; border-top: 2px dashed #f0f0f0; padding-top: 16px; }
    .peran-title { font-size: 0.78rem; font-weight: 700; color: #6b7280; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }

    .summary-bar { background: #FACC15; border-radius: 10px; padding: 10px 18px; display: flex; align-items: center; justify-content: space-between; font-size: 0.8rem; font-weight: 700; color: #735C00; margin-top: 16px; }
    .summary-num { background: #735C00; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800; margin-right: 8px; }

    .btn-pilih-mhs { padding: 10px 22px; border-radius: 50px; background: #FACC15; border: none; font-size: 0.85rem; font-weight: 700; color: #735C00; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
    .btn-pilih-mhs:hover { background: #eab308; }

    .btn-tambah { padding: 11px 28px; border-radius: 50px; background: #FACC15; border: none; font-size: 0.85rem; font-weight: 700; color: #735C00; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
    .btn-tambah:hover { background: #eab308; }
    .btn-batal { padding: 11px 28px; border-radius: 50px; border: 1.5px solid #374151; background: #fff; font-size: 0.85rem; font-weight: 600; color: #374151; cursor: pointer; display: inline-flex; align-items: center; transition: 0.2s; }
    .btn-batal:hover { background: #f9fafb; }

    .loading-state { text-align: center; padding: 30px 20px; color: #9ca3af; font-size: 0.82rem; }
</style>

{{-- HERO --}}
<div class="hero-section">
    <div class="hero-content">
        <h2>Mahasiswa</h2>
        <p>Kelola dan lihat riwayat bimbingan Mahasiswa</p>
    </div>
</div>

{{-- TAB --}}
<div style="display:flex;gap:10px;margin-bottom:24px;">
    <a href="{{ route('dosen.penguji.index') }}" class="tab-btn">
        <i class="fa-solid fa-user-tie"></i> Dosen Penguji
    </a>
    <a href="{{ route('penguji.mahasiswa.index') }}" class="tab-btn active">
        <i class="fa-solid fa-user-graduate"></i> Mahasiswa
    </a>
</div>

{{-- FILTER BAR --}}
<div style="display:flex;gap:12px;margin-bottom:20px;align-items:center;flex-wrap:wrap;">
    <div style="flex:1;min-width:200px;">
        <p style="font-size:0.78rem;font-weight:700;color:#9ca3af;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px;">Cari Mahasiswa</p>
        <div class="search-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="searchMahasiswa" class="search-box" placeholder="Cari NIM, nama mahasiswa, atau judul tugas akhir...">
        </div>
    </div>
    <div>
        <p style="font-size:0.78rem;font-weight:700;color:#9ca3af;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px;">Status</p>
        <select id="filterStatus" class="filter-select">
            <option value="">Semua Status</option>
            <option value="sudah">Sudah Memiliki Penguji</option>
            <option value="belum">Belum Memiliki Penguji</option>
        </select>
    </div>
    <div style="align-self:flex-end;">
        <button class="btn-reset" onclick="resetFilter()">
            <i class="fa-solid fa-rotate-left"></i> Reset Filter
        </button>
    </div>
</div>

{{-- TABEL --}}
<div class="table-card">
    <div class="table-header-bar" style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h6>Daftar Mahasiswa Seminar</h6>
            <p>Menampilkan seluruh mahasiswa yang telah memiliki dosen penguji seminar tugas akhir.</p>
        </div>
        <span class="badge-total-mhs">
            <i class="fa-solid fa-user-graduate"></i> Total Mahasiswa: {{ $mahasiswaList->count() }} Mahasiswa
        </span>
    </div>

    <table class="custom-table">
        <thead>
            <tr>
                <th>NIM</th>
                <th>NAMA MAHASISWA</th>
                <th>ANGKATAN</th>
                <th>JUDUL TUGAS AKHIR</th>
                <th>DOSEN PEMBIMBING</th>
                <th>DOSEN PENGUJI</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody id="tableMahasiswa">
            @forelse($mahasiswaList as $mhs)
            <tr data-status="{{ ($mhs->penguji1 || $mhs->penguji2) ? 'sudah' : 'belum' }}">
                <td style="color:#9ca3af;font-size:0.82rem;">{{ $mhs->nim_nid }}</td>
                <td style="font-weight:700;">{{ $mhs->nama }}</td>
                <td style="color:#6b7280;">{{ $mhs->angkatan ?? '-' }}</td>
                <td style="font-size:0.8rem;max-width:180px;">{{ $mhs->judul_ta }}</td>
                <td>
                    @if($mhs->pembimbing1)
                    <div class="dosen-info-cell">
                        <div class="role-label">Pembimbing 1</div>
                        <div class="nama">{{ $mhs->pembimbing1 }}</div>
                    </div>
                    @endif
                    @if($mhs->pembimbing2)
                    <div class="dosen-info-cell" style="margin-top:6px;">
                        <div class="role-label">Pembimbing 2</div>
                        <div class="nama">{{ $mhs->pembimbing2 }}</div>
                    </div>
                    @endif
                    @if(!$mhs->pembimbing1 && !$mhs->pembimbing2)
                    <span style="color:#d1d5db;font-size:0.78rem;">-</span>
                    @endif
                </td>
                <td>
                    @if($mhs->penguji1)
                    <div class="dosen-info-cell">
                        <div class="role-label">Penguji 1</div>
                        <div class="nama">{{ $mhs->penguji1 }}</div>
                    </div>
                    @endif
                    @if($mhs->penguji2)
                    <div class="dosen-info-cell" style="margin-top:6px;">
                        <div class="role-label">Penguji 2</div>
                        <div class="nama">{{ $mhs->penguji2 }}</div>
                    </div>
                    @endif
                    @if(!$mhs->penguji1 && !$mhs->penguji2)
                    <span style="color:#d1d5db;font-size:0.78rem;">-</span>
                    @endif
                </td>
                <td>
                    @if($mhs->penguji1 && $mhs->penguji2)
                        <span class="badge-sudah">Sudah Memiliki Penguji</span>
                    @else
                        <span class="badge-belum">Belum Memiliki Penguji</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:48px;color:#9ca3af;">
                    <i class="fa-solid fa-folder-open" style="font-size:2rem;margin-bottom:10px;display:block;"></i>
                    Belum ada data mahasiswa
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="table-footer">
        <a href="{{ route('dosen.penguji.index') }}" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <button type="button" class="btn-kelola" onclick="bukaModal()">
            <i class="fa-solid fa-user-shield"></i> Kelola Penetapan Penguji
        </button>
    </div>
</div>

{{-- ===================== MODAL ===================== --}}
<div class="modal-overlay" id="modalKelola">
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

            {{-- Row: Dropdown kiri + Card info dosen kanan --}}
            <div style="display:flex;gap:16px;align-items:flex-start;margin-bottom:16px;">
                <div style="flex:1;">
                    <div class="modal-label">Dosen Penguji</div>
                    <select id="dropdownDosen" class="dropdown-dosen" onchange="dosenDipilih()">
                        <option value="">Pilih dosen penguji</option>
                        @foreach($dosenList as $dosen)
                        <option value="{{ $dosen->nim_nid }}"
                                data-nama="{{ $dosen->nama }}"
                                data-jumlah="{{ $dosen->jumlah_mahasiswa }}">
                            {{ $dosen->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Card info dosen — muncul setelah dropdown dipilih --}}
                <div id="cardDosenInfo" class="modal-dosen-card" style="min-width:240px;margin-bottom:0;align-self:flex-start;display:none;">
                    <div class="name" id="cardDosenNama"></div>
                    <div class="sub" id="cardDosenNidn"></div>
                    <div class="sub" id="cardDosenJumlah"></div>
                </div>
            </div>

            {{-- Placeholder sebelum pilih dosen --}}
            <div id="placeholderState" style="text-align:center;padding:40px 20px;color:#9ca3af;">
                <i class="fa-solid fa-hand-pointer" style="font-size:2rem;margin-bottom:10px;display:block;color:#d1d5db;"></i>
                <p style="font-size:0.82rem;">Pilih dosen penguji terlebih dahulu untuk menampilkan daftar mahasiswa yang bisa ditugaskan.</p>
            </div>

            {{-- Konten mahasiswa — muncul setelah dropdown dipilih --}}
            <div id="kontenMahasiswa" style="display:none;">

                {{-- Search --}}
                <div class="search-wrap" style="margin-bottom:14px;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchModal" class="search-box" placeholder="Cari NIM atau nama mahasiswa...">
                </div>

                {{-- Tabel checklist --}}
                <table class="custom-table" style="border:1px solid #f0f0f0;border-radius:10px;overflow:hidden;">
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
                    <tbody id="modalTableBody"></tbody>
                </table>

                <div style="display:flex;justify-content:flex-end;margin-top:14px;">
                    <button type="button" class="btn-pilih-mhs" onclick="pilihMahasiswa()">
                        <i class="fa-solid fa-user-check"></i> Pilih Mahasiswa
                    </button>
                </div>

                {{-- Peran section --}}
                <div class="peran-section">
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
                            <span> Mahasiswa Dipilih</span>
                        </div>
                        <span>
                            <span id="summaryP1">👤 Penguji 1: 0 Mahasiswa</span>
                            &nbsp;|&nbsp;
                            <span id="summaryP2">👤 Penguji 2: 0 Mahasiswa</span>
                        </span>
                    </div>
                </div>

            </div>
        </div>

        {{-- Footer --}}
        <div class="modal-foot">
            <button type="button" class="btn-batal" onclick="tutupModal()">Batal</button>
            <button type="button" class="btn-tambah" onclick="simpanPenugasan()">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Penugasan
            </button>
        </div>

    </div>
</div>

{{-- Hidden form --}}
<form id="formSimpan" method="POST" style="display:none;">
    @csrf
    <div id="hiddenInputs"></div>
</form>

<script>
    // (data per dosen tidak dipakai lagi — tabel langsung render dari blade)

    function bukaModal() {
        document.getElementById('modalKelola').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function tutupModal() {
        document.getElementById('modalKelola').classList.remove('active');
        document.body.style.overflow = '';
        resetModal();
    }

    // Data per dosen dari controller
    const mahasiswaPerDosen = @json($mahasiswaBelumDitetapkanPerDosen);

    function resetModal() {
        document.getElementById('dropdownDosen').value = '';
        document.getElementById('cardDosenInfo').style.display = 'none';
        document.getElementById('placeholderState').style.display = '';
        document.getElementById('kontenMahasiswa').style.display = 'none';
        document.getElementById('modalTableBody').innerHTML = '';
        document.getElementById('peranTableBody').innerHTML = '';
        document.getElementById('searchModal').value = '';
        document.getElementById('cbAll').checked = false;
        updateSummary();
    }

    function dosenDipilih() {
        const select = document.getElementById('dropdownDosen');
        const opt    = select.options[select.selectedIndex];
        const nimNid = select.value;

        // Reset checklist & peran tiap ganti dosen
        document.getElementById('modalTableBody').innerHTML = '';
        document.getElementById('peranTableBody').innerHTML = '';
        document.getElementById('cbAll').checked = false;
        document.getElementById('searchModal').value = '';
        updateSummary();

        if (!nimNid) {
            document.getElementById('cardDosenInfo').style.display = 'none';
            document.getElementById('placeholderState').style.display = '';
            document.getElementById('kontenMahasiswa').style.display = 'none';
            return;
        }

        // Tampilkan card info dosen
        document.getElementById('cardDosenNama').textContent   = opt.dataset.nama;
        document.getElementById('cardDosenNidn').textContent   = 'NIDN: ' + nimNid;
        document.getElementById('cardDosenJumlah').textContent = 'Jumlah Mahasiswa Saat Ini: ' + opt.dataset.jumlah + ' Mahasiswa';
        document.getElementById('cardDosenInfo').style.display = 'block';

        // Sembunyikan placeholder, tampilkan konten
        document.getElementById('placeholderState').style.display = 'none';
        document.getElementById('kontenMahasiswa').style.display = '';

        // Render daftar mahasiswa untuk dosen ini
        const list  = mahasiswaPerDosen[nimNid] || [];
        const tbody = document.getElementById('modalTableBody');

        if (list.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;padding:30px;color:#9ca3af;font-size:0.82rem;">
                <i class="fa-solid fa-circle-check" style="color:#10b981;font-size:1.5rem;margin-bottom:8px;display:block;"></i>
                Semua mahasiswa sudah memiliki penguji dari dosen ini.
            </td></tr>`;
            return;
        }

        tbody.innerHTML = list.map(mhs => {
            let badge = '';
            if (!mhs.ada_penguji1 && !mhs.ada_penguji2)
                badge = `<span class="badge-belum-modal">Belum Memiliki Penguji</span>`;
            else if (mhs.ada_penguji1 && !mhs.ada_penguji2)
                badge = `<span class="badge-p1-modal">Sudah Penguji 1</span>`;
            else
                badge = `<span class="badge-belum-modal">Sudah Penguji 2</span>`;

            return `<tr data-pengajuan="${mhs.pengajuan_id}"
                        data-nama="${mhs.nama}"
                        data-nim="${mhs.nim_nid}"
                        data-ada-p1="${mhs.ada_penguji1 ? '1' : '0'}"
                        data-ada-p2="${mhs.ada_penguji2 ? '1' : '0'}">
                <td><input type="checkbox" class="cb-mhs cb-row" value="${mhs.pengajuan_id}"></td>
                <td style="color:#9ca3af;font-size:0.82rem;">${mhs.nim_nid}</td>
                <td style="font-weight:600;">${mhs.nama}</td>
                <td style="font-size:0.78rem;">${mhs.judul_ta ? mhs.judul_ta.substring(0, 35) + (mhs.judul_ta.length > 35 ? '...' : '') : '-'}</td>
                <td>${badge}</td>
            </tr>`;
        }).join('');
    }

    // cbAll
    document.getElementById('cbAll').addEventListener('change', function () {
        document.querySelectorAll('.cb-row').forEach(cb => {
            if (cb.closest('tr').style.display !== 'none') cb.checked = this.checked;
        });
    });

    // Search dalam modal
    document.getElementById('searchModal').addEventListener('input', function () {
        const kw = this.value.toLowerCase();
        document.querySelectorAll('#modalTableBody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(kw) ? '' : 'none';
        });
    });

    function pilihMahasiswa() {
        const checked = document.querySelectorAll('.cb-row:checked');
        if (checked.length === 0) { alert('Pilih minimal 1 mahasiswa terlebih dahulu.'); return; }

        const tbody      = document.getElementById('peranTableBody');
        const existingIds = Array.from(tbody.querySelectorAll('tr')).map(r => r.dataset.pengajuan);
        const checkedIds  = Array.from(checked).map(cb => cb.value);

        // Hapus yang sudah tidak diceklis
        tbody.querySelectorAll('tr').forEach(tr => {
            if (!checkedIds.includes(tr.dataset.pengajuan)) tr.remove();
        });

        // Tambah yang baru
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
        const rows    = document.querySelectorAll('#peranTableBody tr');
        const nimNid  = document.getElementById('dropdownDosen').value;

        if (!nimNid)        { alert('Pilih dosen penguji terlebih dahulu.'); return; }
        if (rows.length === 0) { alert('Belum ada mahasiswa dipilih.'); return; }

        const form         = document.getElementById('formSimpan');
        const hiddenInputs = document.getElementById('hiddenInputs');

        // Set action URL pakai nim_nid dosen yang dipilih
        form.action = `/dosen/penguji/${nimNid}/tetapkan`;

        hiddenInputs.innerHTML = '';
        rows.forEach((row, i) => {
            hiddenInputs.innerHTML += `
                <input type="hidden" name="penugasan[${i}][pengajuan_id]" value="${row.dataset.pengajuan}">
                <input type="hidden" name="penugasan[${i}][urutan]" value="${row.dataset.peran}">
            `;
        });
        form.submit();
    }

    // Filter tabel utama
    document.getElementById('searchMahasiswa').addEventListener('input', applyFilter);
    document.getElementById('filterStatus').addEventListener('change', applyFilter);

    function applyFilter() {
        const kw     = document.getElementById('searchMahasiswa').value.toLowerCase();
        const status = document.getElementById('filterStatus').value;
        document.querySelectorAll('#tableMahasiswa tr').forEach(row => {
            const text       = row.innerText.toLowerCase();
            const rowStatus  = row.dataset.status || '';
            const matchText  = text.includes(kw);
            const matchStatus = !status || rowStatus === status;
            row.style.display = matchText && matchStatus ? '' : 'none';
        });
    }

    function resetFilter() {
        document.getElementById('searchMahasiswa').value = '';
        document.getElementById('filterStatus').value   = '';
        document.querySelectorAll('#tableMahasiswa tr').forEach(row => row.style.display = '');
    }

    // Tutup modal klik luar
    document.getElementById('modalKelola').addEventListener('click', function (e) {
        if (e.target === this) tutupModal();
    });
</script>

@endsection