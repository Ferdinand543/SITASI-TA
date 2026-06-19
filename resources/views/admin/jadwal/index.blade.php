@extends('layouts.app')

@section('content')

<style>
.bg-purple { background: #e9d5ff; }
.table td { vertical-align: middle; }

/* ACTION CARDS */
.aksi-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }
.aksi-card {
    background: #fff; border-radius: 16px; border: 1px solid #E5E7EB;
    box-shadow: 0 2px 10px rgba(0,0,0,.05); padding: 22px 24px;
    display: flex; align-items: flex-start; justify-content: space-between; gap: 16px;
}
.aksi-icon {
    width: 46px; height: 46px; border-radius: 12px;
    background: #FFFDF0; border: 1px solid #F5D97A;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.aksi-title { font-size: 15px; font-weight: 800; color: #1E293B; margin-bottom: 4px; }
.aksi-desc { font-size: 12.5px; color: #6B7280; line-height: 1.6; }
.aksi-btn {
    display: inline-flex; align-items: center; gap: 6px; margin-top: 12px;
    padding: 8px 16px; background: #FDE047; color: #713F12;
    border: 1px solid #FACC15; border-radius: 10px; font-size: 12.5px; font-weight: 700;
    text-decoration: none; transition: background .2s;
}
.aksi-btn:hover { background: #FACC15; color: #713F12; }
.aksi-badge {
    text-align: center; background: #FFFDF0; border: 1.5px solid #F5D97A;
    border-radius: 12px; padding: 12px 18px; flex-shrink: 0; min-width: 70px;
}
.aksi-badge-num { font-size: 28px; font-weight: 900; color: #C9A227; line-height: 1; }
.aksi-badge-label { font-size: 10px; font-weight: 700; color: #92400E; text-transform: uppercase; margin-top: 3px; }

@media (max-width: 700px) { .aksi-grid { grid-template-columns: 1fr; } }
</style>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Kelola Jadwal & Timeline Tugas Akhir</h3>
            <p class="text-muted mb-0">Kelola jadwal dan tahapan tugas akhir mahasiswa secara terstruktur.</p>
        </div>
        <button class="btn btn-warning rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambah">
            + Tambah Jadwal
        </button>
    </div>

    {{-- ✅ DUA CARD AKSI: KELOLA JADWAL SEMINAR + JADWAL SEMINAR MAHASISWA --}}
    <div class="aksi-grid">
        {{-- CARD 1: Kelola Jadwal Seminar (shared data dengan koordinator) --}}
        <div class="aksi-card">
            <div style="display:flex;align-items:flex-start;gap:14px;">
                <div class="aksi-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#C9A227" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                    </svg>
                </div>
                <div>
                    <div class="aksi-title">Kelola Jadwal Seminar</div>
                    <div class="aksi-desc">
                        Atur dan pantau penjadwalan seminar mahasiswa. Data jadwal ini sama dengan yang dikelola koordinator — tidak ada double input.
                    </div>
                    <a href="{{ route('jadwalseminar.index') }}" class="aksi-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                        </svg>
                        Kelola Jadwal Seminar
                    </a>
                </div>
            </div>
            <div class="aksi-badge">
                <div class="aksi-badge-num">{{ $jumlahMahasiswaSiapSeminar }}</div>
                <div class="aksi-badge-label">Mahasiswa</div>
            </div>
        </div>

        {{-- CARD 2: Jadwal Seminar Mahasiswa (admin lihat semua) --}}
        <div class="aksi-card">
            <div style="display:flex;align-items:flex-start;gap:14px;">
                <div class="aksi-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#C9A227" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                    </svg>
                </div>
                <div>
                    <div class="aksi-title">Jadwal Seminar Mahasiswa</div>
                    <div class="aksi-desc">
                        Lihat jadwal seminar seluruh mahasiswa lengkap dengan dosen pembimbing, penguji, ruang, dan waktu. Admin dapat melihat semua mahasiswa.
                    </div>
                    <a href="{{ route('admin.jadwal.seminar.mahasiswa') }}" class="aksi-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                        </svg>
                        Lihat Jadwal Seminar Mahasiswa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Total Kegiatan</small>
                    <h3 class="fw-bold">{{ $totalKegiatan }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Kegiatan Aktif</small>
                    <h3 class="fw-bold">{{ $kegiatanAktif }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Seminar Mendatang</small>
                    <h3 class="fw-bold">{{ $seminarMendatang }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted">Deadline Berakhir</small>
                    <h3 class="fw-bold">{{ $deadlineBerakhir }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Table -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">

            <div class="row mb-4">
                <div class="col-md-5">
                    <input type="text" id="adminSearch" class="form-control rounded-pill"
                        placeholder="Cari nama kegiatan atau kategori..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select id="adminStatus" class="form-select rounded-pill">
                        <option value="">Semua Status</option>
                        <option value="Akan Datang" {{ request('status') == 'Akan Datang' ? 'selected' : '' }}>Akan Datang</option>
                        <option value="Berlangsung" {{ request('status') == 'Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Ditutup" {{ request('status') == 'Ditutup' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button onclick="resetAdminFilter()" class="btn btn-light border rounded-pill w-100">✕ Reset</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Kegiatan</th>
                            <th>Kategori</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Waktu</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwals as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-semibold">{{ $item->nama_kegiatan }}</div>
                                <small class="text-muted">{{ $item->sub_judul }}</small>
                            </td>
                            <td>
                                @if($item->kategori == 'Seminar')
                                    <span class="badge bg-primary">Seminar</span>
                                @elseif($item->kategori == 'Administrasi')
                                    <span class="badge bg-purple text-dark">Administrasi</span>
                                @else
                                    <span class="badge bg-warning text-dark">Bimbingan</span>
                                @endif
                            </td>
                            <td>{{ date('d M Y', strtotime($item->tanggal)) }}</td>
                            <td>{{ $item->tanggal_selesai ? date('d M Y', strtotime($item->tanggal_selesai)) : '—' }}</td>
                            <td>{{ $item->waktu }}</td>
                            <td>{{ $item->lokasi }}</td>
                            <td>
                                @if($item->status == 'Akan Datang')
                                    <span class="badge bg-warning text-dark">Akan Datang</span>
                                @elseif($item->status == 'Berlangsung')
                                    <span class="badge bg-success">Berlangsung</span>
                                @elseif($item->status == 'Selesai')
                                    <span class="badge bg-secondary">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Ditutup</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-light border"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit{{ $item->id }}">✏️</button>
                                    <form action="{{ route('jadwal-akademik.destroy', $item->id) }}"
                                        method="POST" class="form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $jadwals->links() }}

        </div>
    </div>

</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('jadwal-akademik.store') }}" method="POST" class="modal-content border-0 rounded-4">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Jadwal Akademik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label>Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label>Sub Judul</label>
                        <input type="text" name="sub_judul" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label>Kategori</label>
                        <select name="kategori" class="form-select">
                            <option>Seminar</option>
                            <option>Administrasi</option>
                            <option>Bimbingan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="tanggal" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Tanggal Selesai <small class="text-muted">(kosongkan jika 1 hari)</small></label>
                        <input type="date" name="tanggal_selesai" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Waktu</label>
                        <input type="time" name="waktu" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Lokasi</label>
                        <input type="text" name="lokasi" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning px-4 rounded-pill">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
@foreach($jadwals as $item)
<div class="modal fade" id="modalEdit{{ $item->id }}">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('jadwal-akademik.update', $item->id) }}" method="POST" class="modal-content border-0 rounded-4">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Jadwal Akademik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label>Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" class="form-control" value="{{ $item->nama_kegiatan }}">
                    </div>
                    <div class="col-md-12">
                        <label>Sub Judul</label>
                        <input type="text" name="sub_judul" class="form-control" value="{{ $item->sub_judul }}">
                    </div>
                    <div class="col-md-12">
                        <label>Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="Seminar" {{ $item->kategori == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                            <option value="Administrasi" {{ $item->kategori == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                            <option value="Bimbingan" {{ $item->kategori == 'Bimbingan' ? 'selected' : '' }}>Bimbingan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ $item->tanggal }}">
                    </div>
                    <div class="col-md-6">
                        <label>Tanggal Selesai <small class="text-muted">(kosongkan jika 1 hari)</small></label>
                        <input type="date" name="tanggal_selesai" class="form-control" value="{{ $item->tanggal_selesai }}">
                    </div>
                    <div class="col-md-6">
                        <label>Waktu</label>
                        <input type="time" name="waktu" class="form-control" value="{{ $item->waktu }}">
                    </div>
                    <div class="col-md-6">
                        <label>Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" value="{{ $item->lokasi }}">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning px-4 rounded-pill">Update Jadwal</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<script>
let adminSearchTimer;
document.getElementById('adminSearch').addEventListener('input', function () {
    clearTimeout(adminSearchTimer);
    adminSearchTimer = setTimeout(() => applyAdminFilter(), 400);
});
document.getElementById('adminStatus').addEventListener('change', function () {
    applyAdminFilter();
});
function applyAdminFilter() {
    const search = document.getElementById('adminSearch').value;
    const status = document.getElementById('adminStatus').value;
    const url    = new URL(window.location.href);
    url.searchParams.set('search', search);
    url.searchParams.set('status', status);
    window.location.href = url.toString();
}
function resetAdminFilter() {
    const url = new URL(window.location.href);
    url.searchParams.delete('search');
    url.searchParams.delete('status');
    window.location.href = url.toString();
}
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Jadwal?',
                text: "Data yang dihapus tidak bisa dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FACC15',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
});
</script>

@endsection