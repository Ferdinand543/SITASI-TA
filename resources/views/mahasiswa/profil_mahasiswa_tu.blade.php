@extends('layouts.app')

@section('content')

<div class="profil-mhs-wrap">

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
    <div class="mb-4">
        <a href="/mahasiswa" class="btn-kembali-mhs">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- CARD UTAMA --}}
    <div class="profil-mhs-card">

        {{-- Garis kuning kiri --}}
        <div class="profil-mhs-bar"></div>

        <div class="profil-mhs-inner">

            {{-- HEADER: Avatar + Nama + Badge --}}
            <div class="profil-mhs-header">

                {{-- Avatar + Upload --}}
                <div class="avatar-wrap">
                    @if(!empty($user->foto))
                        <img src="{{ asset('storage/' . $user->foto) }}"
                             alt="Foto Profil"
                             class="avatar-img" id="avatarImg">
                    @else
                        <div class="avatar-placeholder" id="avatarPlaceholder">
                            <i class="fa fa-user"></i>
                        </div>
                        <img src="" alt="Foto Profil"
                             class="avatar-img" id="avatarImg"
                             style="{{ empty($user->foto) ? 'display:none;' : '' }}">
                    @endif

                    {{-- Tombol kamera --}}
                    <form id="formFoto"
                          action="{{ route('mahasiswa.profil.foto') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <input type="file"
                               id="inputFoto"
                               name="foto"
                               accept="image/jpg,image/jpeg,image/png"
                               style="display:none;"
                               onchange="previewFoto(this)">
                        <button type="button"
                                class="btn-kamera"
                                onclick="document.getElementById('inputFoto').click()"
                                title="Ganti foto profil">
                            <i class="fa fa-camera"></i>
                        </button>
                    </form>
                </div>

                {{-- Nama + Badge --}}
                <div class="profil-mhs-identity">
                    <div class="profil-mhs-nama">{{ $user->nama ?? '-' }}</div>
                    <span class="badge-role-mhs">Mahasiswa</span>
                </div>

            </div>

            <div class="profil-mhs-divider"></div>

            {{-- GRID DATA --}}
            <div class="profil-mhs-grid">

                {{-- Nama Lengkap --}}
                <div class="profil-mhs-field">
                    <div class="field-label">
                        <i class="fa fa-user field-icon"></i>
                        NAMA LENGKAP
                    </div>
                    <div class="field-value">{{ $user->nama ?? '-' }}</div>
                </div>

                {{-- NIM --}}
                <div class="profil-mhs-field">
                    <div class="field-label">
                        <i class="fa fa-id-badge field-icon"></i>
                        NIM
                    </div>
                    <div class="field-value">{{ $user->nim_nid ?? '-' }}</div>
                </div>

                {{-- Email --}}
                <div class="profil-mhs-field">
                    <div class="field-label">
                        <i class="fa fa-envelope field-icon"></i>
                        EMAIL
                    </div>
                    <div class="field-value">{{ $user->email ?? '-' }}</div>
                </div>

                {{-- Angkatan --}}
                <div class="profil-mhs-field">
                    <div class="field-label">
                        <i class="fa fa-calendar field-icon"></i>
                        ANGKATAN
                    </div>
                    <div class="field-value">{{ $user->angkatan ?? '-' }}</div>
                </div>

                {{-- Role --}}
                <div class="profil-mhs-field">
                    <div class="field-label">
                        <i class="fa fa-shield-halved field-icon"></i>
                        ROLE
                    </div>
                    <div class="field-value">{{ ucfirst($user->role ?? '-') }}</div>
                </div>

                {{-- Status Akademik --}}
                <div class="profil-mhs-field">
                    <div class="field-label">
                        <i class="fa fa-circle-check field-icon"></i>
                        STATUS AKADEMIK
                    </div>
                    <div class="field-value">
                        <span class="badge-aktif-mhs">
                            <i class="fa fa-circle-check"></i> Aktif
                        </span>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>

{{-- POPUP KONFIRMASI GANTI FOTO --}}
<div id="popupFoto" class="popup-foto-overlay" style="display:none;">
    <div class="popup-foto-box">
        <div class="popup-foto-icon">📷</div>
        <div class="popup-foto-title">Ganti Foto Profil?</div>
        <div class="popup-foto-msg">Foto profil kamu akan diperbarui.</div>
        <div class="popup-foto-preview-wrap">
            <img id="popupPreviewImg" src="" alt="Preview" class="popup-preview-img">
        </div>
        <div class="popup-foto-btn-row">
            <button class="popup-btn-batal" onclick="batalFoto()">Batal</button>
            <button class="popup-btn-simpan" onclick="document.getElementById('formFoto').submit()">
                Simpan <i class="fa fa-check"></i>
            </button>
        </div>
    </div>
</div>

<style>
/* ============================================================
   WRAP
   ============================================================ */
.profil-mhs-wrap {
    max-width: 860px;
}

/* ============================================================
   TOMBOL KEMBALI
   ============================================================ */
.btn-kembali-mhs {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 18px;
    border-radius: 10px;
    border: 1.5px solid #e2e8f0;
    background: #fff;
    color: #374151;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    transition: 0.2s;
}
.btn-kembali-mhs:hover {
    background: #FEF3C7;
    border-color: #FACC15;
    color: #735C00;
}

/* ============================================================
   CARD UTAMA
   ============================================================ */
.profil-mhs-card {
    background: #fff;
    border-radius: 20px;
    border: 1.5px solid #f1f5f9;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    display: flex;
    overflow: hidden;
}
.profil-mhs-bar {
    width: 5px;
    background: #FACC15;
    flex-shrink: 0;
}
.profil-mhs-inner {
    flex: 1;
    padding: 36px 40px;
}

/* ============================================================
   HEADER AVATAR
   ============================================================ */
.profil-mhs-header {
    display: flex;
    align-items: center;
    gap: 28px;
    margin-bottom: 28px;
}

/* Avatar */
.avatar-wrap {
    position: relative;
    width: 96px;
    height: 96px;
    flex-shrink: 0;
}
.avatar-img {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #FACC15;
}
.avatar-placeholder {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    background: #f3f4f6;
    border: 3px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.4rem;
    color: #9ca3af;
}
.btn-kamera {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #FACC15;
    border: 2px solid #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    color: #735C00;
    cursor: pointer;
    transition: 0.2s;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}
.btn-kamera:hover {
    background: #d4a00e;
    color: #fff;
}

/* Nama + Badge */
.profil-mhs-identity {}
.profil-mhs-nama {
    font-size: 1.35rem;
    font-weight: 800;
    color: #111827;
    margin-bottom: 8px;
    line-height: 1.2;
}
.badge-role-mhs {
    display: inline-block;
    padding: 4px 14px;
    background: #EFF6FF;
    color: #1E40AF;
    border: 1px solid #BFDBFE;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
}

/* ============================================================
   DIVIDER
   ============================================================ */
.profil-mhs-divider {
    height: 1px;
    background: #f1f5f9;
    margin-bottom: 28px;
}

/* ============================================================
   GRID DATA
   ============================================================ */
.profil-mhs-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px 40px;
}
.profil-mhs-field {}
.field-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.68rem;
    font-weight: 700;
    color: #9ca3af;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 6px;
}
.field-icon {
    color: #FACC15;
    font-size: 0.72rem;
    width: 14px;
    text-align: center;
}
.field-value {
    font-size: 0.92rem;
    font-weight: 600;
    color: #111827;
}

/* ============================================================
   BADGE AKTIF
   ============================================================ */
.badge-aktif-mhs {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 14px;
    background: #D1FAE5;
    color: #065F46;
    border: 1px solid #10B981;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 700;
}

/* ============================================================
   ALERT
   ============================================================ */
.alert-custom { padding: 12px 16px; border-radius: 10px; font-size: 0.9rem; margin-bottom: 16px; }
.alert-success-custom { background: #D1FAE5; color: #065F46; border: 1px solid #10B981; }
.alert-error-custom   { background: #fdecea; color: #92400E; border: 1px solid #f1aeb5; }

/* ============================================================
   POPUP FOTO
   ============================================================ */
.popup-foto-overlay {
    position: fixed;
    inset: 0;
    background: rgba(17,24,39,0.5);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.popup-foto-box {
    background: #fff;
    border-radius: 20px;
    padding: 36px 32px 28px;
    width: 100%;
    max-width: 360px;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}
.popup-foto-icon { font-size: 2.2rem; margin-bottom: 12px; }
.popup-foto-title { font-size: 1.1rem; font-weight: 800; color: #111827; margin-bottom: 6px; }
.popup-foto-msg { font-size: 0.85rem; color: #6b7280; margin-bottom: 18px; }
.popup-foto-preview-wrap { margin-bottom: 20px; }
.popup-preview-img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #FACC15;
}
.popup-foto-btn-row { display: flex; gap: 10px; justify-content: center; }
.popup-btn-batal {
    padding: 9px 24px;
    border-radius: 10px;
    border: 1.5px solid #e5e7eb;
    background: #fff;
    color: #374151;
    font-size: 0.88rem;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}
.popup-btn-batal:hover { background: #f9fafb; }
.popup-btn-simpan {
    padding: 9px 24px;
    border-radius: 10px;
    border: none;
    background: #FACC15;
    color: #735C00;
    font-size: 0.88rem;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.popup-btn-simpan:hover { background: #d4a00e; color: #fff; }

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 640px) {
    .profil-mhs-inner { padding: 24px 20px; }
    .profil-mhs-grid  { grid-template-columns: 1fr; gap: 18px; }
    .profil-mhs-header { flex-direction: column; align-items: flex-start; gap: 16px; }
}
</style>

<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('popupPreviewImg').src = e.target.result;
            document.getElementById('popupFoto').style.display = 'flex';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function batalFoto() {
    document.getElementById('popupFoto').style.display = 'none';
    document.getElementById('inputFoto').value = '';
}
</script>

@endsection