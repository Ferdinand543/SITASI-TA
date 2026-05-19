@extends('layouts.app')

@section('content')

@php
    $roleDosen = null;

    if(($user->role ?? '') === 'dosen'){
        $roleDosen = DB::table('dosen_roles')
            ->where('nim_nid', $user->nim_nid)
            ->pluck('role_dosen')
            ->toArray();
    }

    $roleLabel = 'Pengguna';

    if(($user->role ?? '') === 'admin'){
        $roleLabel = 'Administrator';
    }

    elseif(($user->role ?? '') === 'mahasiswa'){
        $roleLabel = 'Mahasiswa';
    }

    elseif(($user->role ?? '') === 'dosen'){

        if(in_array('koordinator', $roleDosen)){
            $roleLabel = 'Dosen Koordinator';
        }

        elseif(in_array('reviewer', $roleDosen)){
            $roleLabel = 'Dosen Reviewer';
        }

        elseif(in_array('pembimbing', $roleDosen)){
            $roleLabel = 'Dosen Pembimbing';
        }

        elseif(in_array('penguji', $roleDosen)){
            $roleLabel = 'Dosen Penguji';
        }

        else{
            $roleLabel = 'Dosen';
        }
    }

@endphp

<div class="container-fluid px-4 py-3">

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert-custom alert-success-custom mb-4">
            <i class="fa fa-check-circle me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-custom alert-error-custom mb-4">
            <i class="fa fa-times-circle me-1"></i>
            {{ session('error') }}
        </div>
    @endif


    {{-- HEADER --}}
    <div class="profil-header mb-4">

        <div>
            <h3 class="profil-title">
                Profil Administrator
            </h3>

            <p class="profil-subtitle">
                Informasi akun dan data personal pengguna sistem
            </p>
        </div>

        <a href="{{ url('/admin') }}" class="btn-kembali">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>

    </div>


    {{-- CARD PROFIL --}}
    <div class="profil-card">

        {{-- SIDEBAR --}}
        <div class="profil-sidebar">

            <div class="profil-avatar">

                @if(!empty($user->foto))
                    <img src="{{ asset($user->foto) }}" alt="Foto Profil">
                @else
                    <i class="fa fa-user-shield"></i>
                @endif

            </div>

            <h4 class="profil-nama">
                {{ $user->nama ?? '-' }}
            </h4>

            <span class="profil-role-badge">
                {{ $roleLabel }}
            </span>

            <div class="profil-nim">
                {{ $user->nim_nid ?? '-' }}
            </div>

        </div>


        {{-- CONTENT --}}
        <div class="profil-content">

            {{-- SECTION --}}
            <div class="profil-section">

                <div class="profil-section-title">
                    <i class="fa fa-id-badge"></i>
                    Informasi Personal
                </div>

                <div class="profil-info-grid">

                    {{-- Nama --}}
                    <div class="profil-info-item">

                        <div class="profil-info-label">
                            Nama Lengkap
                        </div>

                        <div class="profil-info-value">
                            {{ $user->nama ?? '-' }}
                        </div>

                    </div>


                    {{-- Email --}}
                    <div class="profil-info-item">

                        <div class="profil-info-label">
                            Email
                        </div>

                        <div class="profil-info-value">
                            {{ $user->email ?? '-' }}
                        </div>

                    </div>


                    {{-- Role --}}
                    <div class="profil-info-item">

                        <div class="profil-info-label">
                            Role Akun
                        </div>

                        <div class="profil-info-value">
                            {{ $roleLabel }}
                        </div>

                    </div>


                    {{-- NIM/NID --}}
                    <div class="profil-info-item">

                        <div class="profil-info-label">
                            NIM / NID
                        </div>

                        <div class="profil-info-value">
                            {{ $user->nim_nid ?? '-' }}
                        </div>

                    </div>


                    {{-- Angkatan --}}
                    <div class="profil-info-item">

                        <div class="profil-info-label">
                            Angkatan
                        </div>

                        <div class="profil-info-value">

                            @if(($user->role ?? '') === 'mahasiswa')
                                {{ $user->angkatan ?? '-' }}
                            @else
                                -
                            @endif

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="profil-info-item">

                        <div class="profil-info-label">
                            Status Akun
                        </div>

                        <div class="profil-info-value">

                            <span class="badge-status">
                                <i class="fa fa-circle-check"></i>
                                Aktif
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<style>

/* ==========================================================
   GLOBAL
========================================================== */

body{
    background:#f8f9fc;
    font-family:'Hanken Grotesk', sans-serif;
}


/* ==========================================================
   HEADER
========================================================== */

.profil-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    flex-wrap:wrap;
}

.profil-title{
    font-size:1.8rem;
    font-weight:800;
    color:#111827;
    margin-bottom:4px;
}

.profil-subtitle{
    margin:0;
    color:#6b7280;
    font-size:0.95rem;
}


/* ==========================================================
   BUTTON
========================================================== */

.btn-kembali{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:10px 18px;
    border-radius:12px;
    background:#fff;
    border:1px solid #E5E7EB;
    color:#374151;
    text-decoration:none;
    font-size:0.9rem;
    font-weight:600;
    transition:.25s;
}

.btn-kembali:hover{
    background:#FEF3C7;
    border-color:#FACC15;
    color:#735C00;
}


/* ==========================================================
   CARD
========================================================== */

.profil-card{
    background:#fff;
    border-radius:24px;
    overflow:hidden;
    display:flex;
    min-height:480px;
    box-shadow:0 4px 20px rgba(0,0,0,0.05);
    border:1px solid #ECECEC;
}


/* ==========================================================
   SIDEBAR
========================================================== */

.profil-sidebar{
    width:320px;
    background:linear-gradient(180deg,#FACC15,#EAB308);
    padding:40px 30px;
    text-align:center;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
}

.profil-avatar{
    width:120px;
    height:120px;
    border-radius:50%;
    overflow:hidden;
    background:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    margin-bottom:20px;
    border:5px solid rgba(255,255,255,0.5);
}

.profil-avatar img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.profil-avatar i{
    font-size:50px;
    color:#CA8A04;
}

.profil-nama{
    font-size:1.4rem;
    font-weight:800;
    color:#fff;
    margin-bottom:10px;
}

.profil-role-badge{
    background:rgba(255,255,255,0.2);
    color:#fff;
    padding:8px 16px;
    border-radius:30px;
    font-size:0.82rem;
    font-weight:700;
    margin-bottom:12px;
}

.profil-nim{
    color:#fff;
    font-size:0.9rem;
    opacity:.9;
}


/* ==========================================================
   CONTENT
========================================================== */

.profil-content{
    flex:1;
    padding:40px;
}

.profil-section-title{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:1.05rem;
    font-weight:800;
    color:#111827;
    margin-bottom:28px;
}

.profil-section-title i{
    color:#CA8A04;
}

.profil-info-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:22px;
}

.profil-info-item{
    background:#FAFAFA;
    border:1px solid #ECECEC;
    border-radius:16px;
    padding:18px;
}

.profil-info-label{
    font-size:0.8rem;
    color:#6B7280;
    margin-bottom:8px;
    font-weight:600;
}

.profil-info-value{
    font-size:0.95rem;
    color:#111827;
    font-weight:700;
    word-break:break-word;
}


/* ==========================================================
   BADGE
========================================================== */

.badge-status{
    display:inline-flex;
    align-items:center;
    gap:6px;
    background:#DCFCE7;
    color:#166534;
    border:1px solid #22C55E;
    padding:6px 14px;
    border-radius:30px;
    font-size:0.8rem;
    font-weight:700;
}


/* ==========================================================
   ALERT
========================================================== */

.alert-custom{
    padding:14px 18px;
    border-radius:14px;
    font-size:0.92rem;
    font-weight:600;
}

.alert-success-custom{
    background:#DCFCE7;
    color:#166534;
    border:1px solid #22C55E;
}

.alert-error-custom{
    background:#FEE2E2;
    color:#991B1B;
    border:1px solid #EF4444;
}


/* ==========================================================
   RESPONSIVE
========================================================== */

@media(max-width:992px){

    .profil-card{
        flex-direction:column;
    }

    .profil-sidebar{
        width:100%;
        padding:30px 20px;
    }

    .profil-content{
        padding:30px 20px;
    }

}

</style>

@endsection