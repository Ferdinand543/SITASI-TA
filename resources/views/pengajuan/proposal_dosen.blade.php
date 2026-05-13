@extends('layouts.app')

@section('content')

<!-- SUB-MENU -->
<div class="container">
    <div style="display:flex; gap:8px; padding:10px 0 14px 0;">
        <a href="/dashboard/dosen" class="sub-menu">Beranda</a>
        <a href="#" class="sub-menu">Aktivitas</a>
        <a href="#" class="sub-menu">Panduan TA</a>
    </div>
</div>

<!-- HERO -->
<div class="hero-section mb-4">
    <div class="hero-content" style="width:100%; text-align:center;">
        <h2 class="fw-bold" style="text-align:center;">Pengajuan Proposal TA-1</h2>
        <p class="text-muted" style="text-align:center;">
            Verifikasi dan tetapkan dosen pembimbing tugas akhir mahasiswa.
        </p>
    </div>
</div>

<div class="container-fluid px-4">

    {{-- FILTER --}}
    <div class="d-flex justify-content-end align-items-center gap-2 mb-3 flex-wrap">
        <form method="GET"
              action="{{ url('/proposal') }}"
              class="d-flex align-items-center gap-2 flex-wrap">

            <select name="status"
                    onchange="this.form.submit()"
                    class="form-select"
                    style="width:185px; height:40px; font-size:14px;">
                <option value="">Semua Status</option>
                <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>
                    Menunggu Verifikasi
                </option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>
                    Selesai
                </option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>
                    Ditolak
                </option>
            </select>

            <div class="input-group" style="width:260px;">
                <input type="text"
                       name="search"
                       placeholder="Cari nama, NIM, Judul"
                       value="{{ request('search') }}"
                       class="form-control"
                       style="font-size:14px;">
                <button type="submit" class="input-group-text">
                    <i class="fa fa-search"></i>
                </button>
            </div>

            <button type="button"
                    onclick="window.location.href='{{ url('/proposal') }}'"
                    class="btn btn-outline-secondary d-flex align-items-center gap-1"
                    style="height:40px; font-size:14px;">
                <i class="fa fa-rotate-right"></i>
                Reset filter
            </button>

        </form>
    </div>

    {{-- TABEL --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center"
               style="min-width:1400px; font-size:14px;">

            <thead>
                <tr style="background:#f4b400; color:white;">
                    <th class="align-middle" style="width:50px;">No.</th>
                    <th class="align-middle">Tanggal</th>
                    <th class="align-middle">NIM</th>
                    <th class="align-middle">Nama</th>
                    <th class="align-middle">Judul</th>
                    <th class="align-middle">Proposal</th>
                    <th class="align-middle">Pembimbing 1</th>
                    <th class="align-middle">Pembimbing 2</th>
                    <th class="align-middle">Status</th>
                    <th class="align-middle">Detail</th>
                </tr>
            </thead>

            <tbody>
                @forelse($proposals as $i => $p)

                @php $status = strtolower(trim($p->status)); @endphp

                <tr style="height:95px;">

                    <td>{{ $i + 1 }}</td>

                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d/m/Y') }}</td>

                    <td>{{ $p->nim_nid }}</td>

                    <td class="text-start">{{ $p->nama }}</td>

                    <td class="text-start">{{ $p->judul }}</td>

                    <td>
                        @if($p->file_proposal)
                            <a href="{{ asset('storage/' . $p->file_proposal) }}"
                               target="_blank"
                               class="btn btn-sm"
                               style="border:1px solid #cfcfcf; background:white; color:#444; border-radius:8px; width:70px;">
                                Lihat
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    <td class="text-start" style="width:220px;">
                        @if($p->dosen1_nama)
                            <div class="fw-semibold">{{ $p->dosen1_nama }}</div>
                            <div style="font-size:12px; color:#666;">NIDN. {{ $p->dosen1_nidn }}</div>
                            <div style="font-size:11px; color:#888;">[Pembimbing TA]</div>
                        @elseif($p->usulan_dosen1_nama)
                            <div class="fw-semibold">{{ $p->usulan_dosen1_nama }}</div>
                            <div style="font-size:12px; color:#666;">NIDN. {{ $p->usulan_dosen1_nidn }}</div>
                            <div style="font-size:11px; color:#888;">[Usulan Pembimbing]</div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    <td class="text-start" style="width:220px;">
                        @if($p->dosen2_nama)
                            <div class="fw-semibold">{{ $p->dosen2_nama }}</div>
                            <div style="font-size:12px; color:#666;">NIDN. {{ $p->dosen2_nidn }}</div>
                            <div style="font-size:11px; color:#888;">[Pembimbing TA]</div>
                        @elseif($p->usulan_dosen2_nama)
                            <div class="fw-semibold">{{ $p->usulan_dosen2_nama }}</div>
                            <div style="font-size:12px; color:#666;">NIDN. {{ $p->usulan_dosen2_nidn }}</div>
                            <div style="font-size:11px; color:#888;">[Usulan Pembimbing]</div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    <td>
                        @if(str_contains($status, 'menunggu'))
                            <span class="badge-status badge-menunggu">Menunggu Verifikasi</span>
                        @elseif($status == 'selesai')
                            <span class="badge-status badge-disetujui">Selesai</span>
                        @elseif($status == 'ditolak')
                            <span class="badge-status badge-ditolak">Ditolak</span>
                        @else
                            <span class="text-muted">{{ $p->status }}</span>
                        @endif
                    </td>

                    {{-- AKSI: belum verifikasi = Verifikasi aja, udah = Detail aja --}}
                    <td>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            @if(str_contains($status, 'menunggu'))
                                {{-- BELUM DIVERIFIKASI: tombol Verifikasi aja --}}
                                <a href="{{ url('/proposal/' . $p->id . '/verifikasi') }}"
                                   class="btn btn-sm btn-warning fw-bold"
                                   style="color:#000; border-radius:8px; min-width:80px;">
                                    Verifikasi
                                </a>
                            @else
                                {{-- SUDAH DIVERIFIKASI: tombol Detail aja --}}
                                <a href="{{ url('/proposal/' . $p->id) }}"
                                   class="btn btn-sm btn-outline-secondary"
                                   style="border-radius:8px; min-width:70px;">
                                    Detail
                                </a>
                            @endif
                        </div>
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-5">
                        <i class="fa fa-inbox me-2"></i>
                        Belum ada pengajuan proposal
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<style>
.hero-section {
    min-height: 220px !important;
    padding: 40px 60px !important;
    justify-content: center !important;
    background-image: url('/images/bg_ajukan.jpeg') !important;
    background-size: cover !important;
    background-position: center center !important;
    background-repeat: no-repeat !important;
}
.hero-section .hero-content {
    width: 100% !important;
    text-align: center !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
}
.badge-status {
    display: inline-block;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 600;
}
.badge-disetujui { background: #d4edda; color: #28a745; }
.badge-menunggu  { background: #fff3cd; color: #856404; }
.badge-ditolak   { background: #f8d7da; color: #dc3545; }
.sub-menu {
    text-decoration: none;
    color: #555;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 14px;
    transition: 0.2s;
}
.sub-menu:hover { background: #f7d27c; color: #000; }
.sub-menu.active { background: #f7d27c; color: #000; font-weight: 500; }
</style>

@endsection