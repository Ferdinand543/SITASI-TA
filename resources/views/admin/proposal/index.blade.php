@extends('layouts.app')

@section('title', 'Riwayat Pengajuan Proposal')

@section('content')

<style>
    :root {
        --gold: #C9A227;
        --gold-light: #FEF9EC;
        --gold-border: #F5D97A;
        --bg: #F5F6FA;
        --white: #fff;
        --border: #E5E7EB;
        --text: #1E293B;
        --muted: #6B7280;
    }

    body {
        background: var(--bg);
    }

    .hero {
        background-image: url('{{ asset("images/bg.jpeg") }}');
        background-size: cover;
        background-position: center;
        border-radius: 24px;
        padding: 34px 38px;
        margin-bottom: 24px;
    }

    .hero h1 {
        font-size: 34px;
        font-weight: 800;
        color: #7C5C00;
        margin-bottom: 8px;
    }

    .hero p {
        font-size: 14px;
        color: #8B6B00;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit,minmax(240px,1fr));
        gap: 18px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 22px;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        background: #FEF3C7;
        color: #B45309;
    }

    .stat-number {
        font-size: 28px;
        font-weight: 800;
    }

    .stat-label {
        font-size: 12px;
        color: var(--muted);
    }

    .card {
        background: white;
        border-radius: 22px;
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .table-wrap {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1100px;
    }

    thead th {
        background: #F9FAFB;
        padding: 14px 16px;
        font-size: 11px;
        font-weight: 800;
        color: var(--muted);
        border-bottom: 1px solid var(--border);
    }

    tbody td {
        padding: 16px;
        border-bottom: 1px solid #F3F4F6;
        font-size: 13px;
        vertical-align: middle;
    }

    .student {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: var(--gold-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: var(--gold);
    }

    .student-name {
        font-weight: 700;
    }

    .proposal-title {
        max-width: 220px;
    }

    .file-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 10px;
        background: #FEF2F2;
        color: #DC2626;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        color: white;
    }

    .badge-warning {
        background: #F59E0B;
    }

    .badge-info {
        background: #3B82F6;
    }

    .badge-success {
        background: #10B981;
    }

    .badge-danger {
        background: #EF4444;
    }

    .btn-detail {
        padding: 8px 14px;
        border-radius: 10px;
        background: var(--gold-light);
        color: var(--gold);
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
    }

    .empty-state {
        text-align: center;
        padding: 70px 20px;
        color: var(--muted);
    }

</style>

<div class="hero">

    <h1>Pengajuan Proposal TA-1</h1>

    <p>
        Tinjau proposal tugas akhir mahasiswa dan lihat seluruh riwayat pengajuan proposal.
    </p>

</div>

<div class="stats-grid">

    <div class="stat-card">

        <div class="stat-icon">
            <i class="fa-regular fa-file-lines"></i>
        </div>

        <div>
            <div class="stat-number">
                {{ $proposal->count() }}
            </div>

            <div class="stat-label">
                Total Proposal
            </div>
        </div>

    </div>

    <div class="stat-card">

        <div class="stat-icon">
            <i class="fa-regular fa-clock"></i>
        </div>

        <div>
            <div class="stat-number">
                {{ $proposal->where('status','menunggu_review')->count() }}
            </div>

            <div class="stat-label">
                Menunggu Review
            </div>
        </div>

    </div>

    <div class="stat-card">

        <div class="stat-icon">
            <i class="fa-regular fa-circle-check"></i>
        </div>

        <div>
            <div class="stat-number">
                {{ $proposal->where('status','selesai')->count() }}
            </div>

            <div class="stat-label">
                Proposal Selesai
            </div>
        </div>

    </div>

</div>

<div class="card">

    <div class="table-wrap">

        <table>

            <thead>

                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>NIM</th>
                    <th>Mahasiswa</th>
                    <th>Judul Proposal</th>
                    <th>Proposal</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>

            </thead>

            <tbody>

                @forelse($proposal as $i => $p)

                <tr>

                    <td>{{ $i + 1 }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d M Y') }}
                    </td>

                    <td>
                        {{ $p->nim_nid }}
                    </td>

                    <td>

                        <div class="student">

                            <div class="avatar">
                                {{ strtoupper(substr($p->nama_mahasiswa,0,1)) }}
                            </div>

                            <div class="student-name">
                                {{ $p->nama_mahasiswa }}
                            </div>

                        </div>

                    </td>

                    <td>

                        <div class="proposal-title">
                            {{ Str::limit($p->judul, 60) }}
                        </div>

                    </td>

                    <td>

                        @if($p->file_proposal)

                        <a href="{{ asset('storage/' . $p->file_proposal) }}"
                           target="_blank"
                           class="file-link">

                            <i class="fa-regular fa-file-pdf"></i>

                            Lihat File

                        </a>

                        @endif

                    </td>

                    <td>

                        @if($p->status == 'menunggu_verifikasi')

                            <span class="badge badge-warning">
                                Menunggu Verifikasi
                            </span>

                        @elseif($p->status == 'menunggu_review')

                            <span class="badge badge-info">
                                Direview
                            </span>

                        @elseif($p->status == 'selesai')

                            <span class="badge badge-success">
                                Selesai
                            </span>

                        @elseif($p->status == 'ditolak')

                            <span class="badge badge-danger">
                                Ditolak
                            </span>

                        @endif

                    </td>

                    <td>
                        {{ $p->catatan ?? '-' }}
                    </td>

                    <td>

                        <a href="{{ route('admin.proposal.detail', $p->id) }}"
                           class="btn-detail">

                            Detail

                        </a>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="9">

                        <div class="empty-state">
                            Belum ada proposal mahasiswa.
                        </div>

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div style="margin-top:20px;">
    {{ $proposal->links() }}
</div>

@endsection