@extends('layouts.app')

@section('content')

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

    {{-- TOMBOL KEMBALI --}}
    <div class="mb-3">
        <a href="/dashboard/dosen" class="btn-kembali">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- CARD PROFIL --}}
    <div class="profil-card">

        {{-- Garis kuning kiri --}}
        <div class="profil-accent-bar"></div>

        <div class="profil-card-inner">

            {{-- Judul section --}}
            <div class="profil-section-title">
                <i class="fa fa-id-badge profil-section-icon"></i>
                Informasi Personal
            </div>

            {{-- Baris: Role --}}
            <div class="profil-row">
                <div class="profil-row-label">
                    <i class="fa fa-shield-halved profil-row-icon"></i>
                    Role
                </div>
                <div class="profil-row-value">
                    @php
                        $roleLabel = match(strtolower(trim($user->role ?? ''))) {
                            'admin'         => 'Administrator Utama',
                            'koordinator'   => 'Koordinator',
                            'dosen penguji' => 'Dosen Penguji',
                            'dosen pembimbing' => 'Dosen Pembimbing',
                            'dosen reviewer'   => 'Dosen Reviewer',
                            'mahasiswa'     => 'Mahasiswa',
                            default         => ucwords($user->role ?? '-'),
                        };
                    @endphp
                    {{ $roleLabel }}
                </div>
            </div>

            <div class="profil-divider"></div>

            {{-- Baris: Email --}}
            <div class="profil-row">
                <div class="profil-row-label">
                    <i class="fa fa-envelope profil-row-icon"></i>
                    Alamat Email
                </div>
                <div class="profil-row-value">{{ $user->email ?? '-' }}</div>
            </div>

            <div class="profil-divider"></div>

            {{-- Baris: Status Akun --}}
            <div class="profil-row">
                <div class="profil-row-label">
                    <i class="fa fa-shield-halved profil-row-icon"></i>
                    Status Akun
                </div>
                <div class="profil-row-value">
                    <span class="badge-aktif">
                        <i class="fa fa-circle-check"></i> Aktif
                    </span>
                </div>
            </div>

        </div>
    </div>

</div>

<style>
/* ============================================================
   TOMBOL KEMBALI
   ============================================================ */
.btn-kembali {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 18px;
    border-radius: 10px;
    border: 1.5px solid #D1C6AB;
    background: #fff;
    color: #4D4632;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    transition: 0.2s;
}
.btn-kembali:hover {
    background: #FEF3C7;
    border-color: #735C00;
    color: #735C00;
}

/* ============================================================
   CARD PROFIL
   ============================================================ */
.profil-card {
    background: #fff;
    border-radius: 16px;
    border: 1.5px solid #E5DFD0;
    box-shadow: 0 2px 12px rgba(17,28,45,0.07);
    display: flex;
    overflow: hidden;
    max-width: 700px;       /* sesuai mockup — tidak full width */
}

/* Garis kuning kiri sesuai mockup */
.profil-accent-bar {
    width: 5px;
    background: #735C00;
    flex-shrink: 0;
    border-radius: 0;
}

.profil-card-inner {
    flex: 1;
    padding: 32px 36px;
}

/* ============================================================
   SECTION TITLE
   ============================================================ */
.profil-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1rem;
    font-weight: 700;
    color: #111C2D;
    margin-bottom: 24px;
}
.profil-section-icon {
    color: #735C00;
    font-size: 1.05rem;
}

/* ============================================================
   BARIS DATA
   ============================================================ */
.profil-row {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 14px 0;
    min-height: 48px;
}
.profil-row-label {
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 170px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #6C5700;
}
.profil-row-icon {
    color: #735C00;
    font-size: 0.88rem;
    width: 16px;
    text-align: center;
}
.profil-row-value {
    font-size: 0.9rem;
    color: #111C2D;
    font-weight: 500;
}
.profil-divider {
    height: 1px;
    background: #F0EBE0;
    margin: 0;
}

/* ============================================================
   BADGE STATUS AKTIF
   ============================================================ */
.badge-aktif {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 14px;
    background: #D1FAE5;
    color: #065F46;
    border: 1px solid #10B981;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 700;
}

/* ============================================================
   ALERT
   ============================================================ */
.alert-custom { padding: 12px 16px; border-radius: 10px; font-size: 0.9rem; margin-bottom: 16px; }
.alert-success-custom { background: #D1FAE5; color: #065F46; border: 1px solid #10B981; }
.alert-error-custom   { background: #fdecea; color: #92400E; border: 1px solid #f1aeb5; }

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 640px) {
    .profil-card-inner { padding: 24px 20px; }
    .profil-row { flex-direction: column; align-items: flex-start; gap: 4px; }
    .profil-row-label { min-width: unset; }
}
</style>

@endsection