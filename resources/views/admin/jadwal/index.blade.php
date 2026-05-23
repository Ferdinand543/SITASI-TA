@extends('layouts.app')

@section('content')

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

            <form method="GET" class="row mb-4">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control rounded-pill"
                        placeholder="Cari nama kegiatan atau kategori..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select rounded-pill">
                        <option>Semua Status</option>
                        <option {{ request('status') == 'Akan Datang' ? 'selected' : '' }}>Akan Datang</option>
                        <option {{ request('status') == 'Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                        <option {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option {{ request('status') == 'Ditutup' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-light border rounded-pill w-100">Filter</button>
                </div>
            </form>

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
                            <td>
                                {{ $item->tanggal_selesai ? date('d M Y', strtotime($item->tanggal_selesai)) : '—' }}
                            </td>
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
                    <div class="col-md-6">
                        <label>Kategori</label>
                        <select name="kategori" class="form-select">
                            <option>Seminar</option>
                            <option>Administrasi</option>
                            <option>Bimbingan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option>Akan Datang</option>
                            <option>Berlangsung</option>
                            <option>Selesai</option>
                            <option>Ditutup</option>
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
                    <div class="col-md-6">
                        <label>Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="Seminar" {{ $item->kategori == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                            <option value="Administrasi" {{ $item->kategori == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                            <option value="Bimbingan" {{ $item->kategori == 'Bimbingan' ? 'selected' : '' }}>Bimbingan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="Akan Datang" {{ $item->status == 'Akan Datang' ? 'selected' : '' }}>Akan Datang</option>
                            <option value="Berlangsung" {{ $item->status == 'Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="Selesai" {{ $item->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Ditutup" {{ $item->status == 'Ditutup' ? 'selected' : '' }}>Ditutup</option>
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

<style>
.bg-purple { background: #e9d5ff; }
.table td { vertical-align: middle; }
.card { transition: .3s; }
.card:hover { transform: translateY(-3px); }
</style>

<script>
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