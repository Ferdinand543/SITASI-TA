@extends('layouts.app')

@section('title', 'Pendaftaran Seminar TA-1')

@section('content')

<style>
    :root {
        --gold: #C9A227;
        --gold-lt: #FEF9EC;
        --gold-border: #F5D97A;
        --neutral: #1E293B;
        --muted: #6B7280;
        --border: #E5E7EB;
        --white: #ffffff;
        --bg: #F5F6FA;
        --radius: 16px;
    }

    .daftar-wrap { background: var(--bg); min-height: 100vh; padding-bottom: 60px; }

    .daftar-hero {
        background-image: url('{{ asset("images/1.jpeg") }}');
        background-size: cover;
        background-position: center right;
        border-radius: 20px;
        padding: 36px 40px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        min-height: 160px;
        display: flex;
        align-items: center;
    }
  
    
    .hero-content { position: relative; z-index: 2; }
    .hero-title { font-size: 28px; font-weight: 800; color: #7C5C00; margin-bottom: 6px; }
    .hero-sub { font-size: 13px; color: #92400E; max-width: 500px; line-height: 1.6; }

    .alert-penting {
        background: #FFFBEB;
        border: 1px solid #FDE68A;
        border-radius: 14px;
        padding: 14px 18px;
        margin-bottom: 24px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
        color: #92400E;
        font-size: 13px;
        line-height: 1.6;
    }
    .alert-penting strong { font-weight: 700; }

    .form-card {
        background: var(--white);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
        padding: 28px;
        margin-bottom: 20px;
    }

    .sec-head {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
        padding-bottom: 16px;
        border-bottom: 1px solid #F3F4F6;
    }
    .sec-icon {
        width: 36px; height: 36px;
        background: var(--gold-lt);
        border: 1.5px solid var(--gold-border);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: var(--gold); flex-shrink: 0;
    }
    .sec-title { font-size: 16px; font-weight: 800; color: var(--neutral); }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
    .form-row.cols-1 { grid-template-columns: 1fr; }
    .form-group { display: flex; flex-direction: column; }
    .form-group label {
        font-size: 11.5px; font-weight: 700; color: var(--muted);
        text-transform: uppercase; letter-spacing: .4px; margin-bottom: 6px;
    }
    .form-input {
        height: 44px; border: 1.5px solid var(--border); border-radius: 10px;
        padding: 0 14px; font-size: 14px; color: var(--neutral);
        background: #FAFAFA; font-family: inherit; outline: none;
        transition: border .2s, background .2s;
    }
    .form-input:focus { border-color: var(--gold); background: #fff; }
    .form-input.readonly { background: #F3F4F6; color: var(--muted); cursor: not-allowed; }
    .form-textarea {
        border: 1.5px solid var(--border); border-radius: 10px;
        padding: 12px 14px; font-size: 14px; color: var(--neutral);
        background: #FAFAFA; font-family: inherit; outline: none;
        resize: vertical; min-height: 90px;
        transition: border .2s, background .2s;
    }
    .form-textarea:focus { border-color: var(--gold); background: #fff; }
    .form-textarea.readonly { background: #F3F4F6; color: var(--muted); cursor: not-allowed; }
    .field-error { border-color: #DC2626 !important; background: #fff5f5 !important; }
    .err-msg { font-size: 11.5px; color: #DC2626; margin-top: 4px; font-weight: 600; }

    /* UPLOAD FILE */
    .upload-zone {
        border: 2px dashed var(--border);
        border-radius: 12px;
        padding: 28px;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s, background .2s;
        position: relative;
    }
    .upload-zone:hover { border-color: var(--gold); background: var(--gold-lt); }
    .upload-zone.has-file { border-color: #16A34A; background: #F0FDF4; border-style: solid; }
    .upload-zone.has-error { border-color: #DC2626; background: #fff5f5; }
    .upload-icon { font-size: 32px; margin-bottom: 8px; }
    .upload-text { font-size: 13px; font-weight: 700; color: var(--neutral); margin-bottom: 4px; }
    .upload-sub { font-size: 11.5px; color: var(--muted); }
    .upload-file-name {
        display: none;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        color: #15803D;
        margin-top: 6px;
    }
    .upload-file-name.show { display: flex; }
    .btn-hapus-file {
        width: 18px; height: 18px; border-radius: 50%;
        background: #FEE2E2; color: #DC2626;
        border: none; cursor: pointer; font-size: 11px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: background .15s;
    }
    .btn-hapus-file:hover { background: #FECACA; }

    /* DOSPEM CARD */
    .dospem-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .dospem-card {
        background: #F8FAFC;
        border-radius: 10px;
        padding: 14px 16px;
        display: flex; align-items: center; gap: 12px;
    }
    .dospem-avatar {
        width: 36px; height: 36px; border-radius: 50%;
        background: var(--gold-lt); border: 2px solid var(--gold-border);
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; font-weight: 800; color: var(--gold);
        flex-shrink: 0;
    }
    .dospem-label { font-size: 10.5px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; }
    .dospem-name { font-size: 13px; font-weight: 700; color: var(--neutral); }

    /* FOOTER */
    .form-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--white);
        border-radius: var(--radius);
        padding: 16px 24px;
        border: 1px solid var(--border);
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
        position: sticky;
        bottom: 0;
        z-index: 10;
    }
    .btn-kembali {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 10px 20px; border-radius: 10px;
        font-size: 13px; font-weight: 600;
        background: #F3F4F6; color: var(--neutral);
        border: none; cursor: pointer; text-decoration: none;
        transition: background .2s; font-family: inherit;
    }
    .btn-kembali:hover { background: #E5E7EB; color: var(--neutral); }
    .btn-ajukan {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 28px; border-radius: 10px;
        font-size: 13px; font-weight: 800;
        background: #FFE083; color: #7C5C00;
        border: none; cursor: pointer; transition: background .2s;
        font-family: inherit;
    }
    .btn-ajukan:hover { background: #fdd835; }

    @media (max-width: 600px) {
        .form-row { grid-template-columns: 1fr; }
        .dospem-grid { grid-template-columns: 1fr; }
        .daftar-hero { padding: 24px 20px; }
        .hero-title { font-size: 22px; }
    }
</style>

<div class="daftar-wrap">

    {{-- HERO --}}
    <div class="daftar-hero">
        <div class="hero-content">
            <div class="hero-title">Pendaftaran Seminar TA-1</div>
            <div class="hero-sub">Satu langkah lebih dekat menuju seminar tugas akhir. Lengkapi data dan berkas Anda dengan teliti sebelum melakukan pengajuan seminar.</div>
        </div>
    </div>

    {{-- ALERT --}}
    <div class="alert-penting">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18" style="flex-shrink:0;margin-top:1px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
        </svg>
        <div>
            <strong>Informasi Penting</strong><br>
            Periksa kembali seluruh data dan dokumen seminar sebelum diajukan. Kesalahan atau ketidaksesuaian berkas dapat memperlambat proses verifikasi atau ditolak.
        </div>
    </div>

    <form action="{{ route('seminar.submitDaftar', $pengajuan->id) }}" method="POST" enctype="multipart/form-data" id="formDaftar">
        @csrf

        {{-- INFORMASI SEMINAR --}}
        <div class="form-card">
            <div class="sec-head">
                <div class="sec-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-1.516"/>
                    </svg>
                </div>
                <div class="sec-title">Informasi Seminar</div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-input readonly" value="{{ $mahasiswa->nama ?? '-' }}" readonly>
                </div>
                <div class="form-group">
                    <label>NIM</label>
                    <input type="text" class="form-input readonly" value="{{ $mahasiswa->nim_nid ?? '-' }}" readonly>
                </div>
            </div>

            <div class="form-row cols-1">
                <div class="form-group">
                    <label>Rencana Tanggal Seminar</label>
                    <input type="date" name="rencana_tanggal_seminar" id="rencana_tanggal_seminar"
                        class="form-input"
                        value="{{ old('rencana_tanggal_seminar') }}"
                        min="{{ now()->addDays(3)->format('Y-m-d') }}">
                </div>
            </div>

            <div class="form-row cols-1">
                <div class="form-group">
                    <label>Judul Tugas Akhir</label>
                    <textarea name="judul_ta" id="judul_ta" class="form-textarea"
                        placeholder="Masukkan judul lengkap tugas akhir Anda...">{{ old('judul_ta', $pengajuan->judul_ta ?? '') }}</textarea>
                </div>
            </div>

            {{-- UPLOAD FILE PROPOSAL --}}
            <div class="form-group">
                <label>Upload File Proposal Seminar</label>
                <div class="upload-zone" id="uploadZone" onclick="document.getElementById('file_proposal').click()">
                    <div id="uploadDefault">
                        <div class="upload-icon">📄</div>
                        <div class="upload-text">Klik untuk upload atau drag & drop</div>
                        <div class="upload-sub">File format PDF, Doc, Docx (Max. 5MB)</div>
                    </div>
                    <div class="upload-file-name" id="uploadFileName">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#15803D" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                        </svg>
                        <span id="fileNameText"></span>
                        <button type="button" class="btn-hapus-file" onclick="hapusFile(event)">✕</button>
                    </div>
                </div>
                <input type="file" id="file_proposal" name="file_proposal"
                    accept=".pdf,.doc,.docx" style="display:none"
                    onchange="tampilFile(this)">
                <span class="err-msg" id="errFile" style="display:none;">File proposal wajib diunggah</span>
            </div>

        </div>

        {{-- DOSEN PEMBIMBING --}}
        <div class="form-card">
            <div class="sec-head">
                <div class="sec-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                    </svg>
                </div>
                <div class="sec-title">Dosen Pembimbing & Wali</div>
            </div>

            <div class="dospem-grid" style="margin-bottom:12px;">
                <div class="dospem-card">
                    <div class="dospem-avatar">{{ strtoupper(substr($namaDospem1 ?? 'D', 0, 1)) }}</div>
                    <div>
                        <div class="dospem-label">Dosen Pembimbing 1</div>
                        <div class="dospem-name">{{ $namaDospem1 ?? '-' }}</div>
                    </div>
                </div>
                <div class="dospem-card">
                    <div class="dospem-avatar">{{ strtoupper(substr($namaDospem2 ?? 'D', 0, 1)) }}</div>
                    <div>
                        <div class="dospem-label">Dosen Pembimbing 2</div>
                        <div class="dospem-name">{{ $namaDospem2 ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="form-row cols-1">
                <div class="form-group">
                    <label>Dosen Wali</label>
                    @php
                        $dosenWali = $pengajuan->dosen_wali
                            ?? (($pengajuan->draft_data ? json_decode($pengajuan->draft_data, true) : [])['dosen_wali'] ?? '-');
                    @endphp
                    <input type="text" class="form-input readonly" value="{{ $dosenWali }}" readonly>
                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="form-footer">
            <a href="{{ route('seminar.daftar') }}" class="btn-kembali">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Kembali
            </a>
            <button type="submit" class="btn-ajukan">
                Ajukan Seminar
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="14" height="14">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </button>
        </div>

    </form>

</div>

<script>
    function tampilFile(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const maxSize = 5 * 1024 * 1024; // 5MB
            const zone = document.getElementById('uploadZone');

            if (file.size > maxSize) {
                Swal.fire({
                    title: 'Ukuran File Terlalu Besar',
                    html: `File <strong>${file.name}</strong> berukuran ${(file.size / (1024*1024)).toFixed(2)} MB.<br>Maksimal ukuran file yang diperbolehkan adalah <strong>5 MB</strong>.`,
                    icon: 'error',
                    confirmButtonColor: '#FACC15',
                    confirmButtonText: 'Oke, Saya Ganti File',
                });
                input.value = '';
                zone.classList.remove('has-file');
                return;
            }

            const def  = document.getElementById('uploadDefault');
            const nama = document.getElementById('uploadFileName');
            const text = document.getElementById('fileNameText');
            const err  = document.getElementById('errFile');

            def.style.display  = 'none';
            nama.classList.add('show');
            text.textContent   = file.name;
            zone.classList.add('has-file');
            zone.classList.remove('has-error');
            err.style.display  = 'none';
        }
    }

    function hapusFile(e) {
        e.stopPropagation();
        const input = document.getElementById('file_proposal');
        const zone  = document.getElementById('uploadZone');
        const def   = document.getElementById('uploadDefault');
        const nama  = document.getElementById('uploadFileName');

        input.value = '';
        def.style.display = '';
        nama.classList.remove('show');
        zone.classList.remove('has-file');
    }

    // Drag & drop
    const zone = document.getElementById('uploadZone');
    zone.addEventListener('dragover', e => {
        e.preventDefault();
        zone.style.borderColor = '#C9A227';
        zone.style.background  = '#FEF9EC';
    });
    zone.addEventListener('dragleave', () => {
        zone.style.borderColor = '';
        zone.style.background  = '';
    });
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.style.borderColor = '';
        zone.style.background  = '';
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const input    = document.getElementById('file_proposal');
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(files[0]);
            input.files = dataTransfer.files;
            tampilFile(input);
        }
    });

    // Validasi submit
    document.getElementById('formDaftar').addEventListener('submit', function(e) {
        let ok = true;

        const tanggal = document.getElementById('rencana_tanggal_seminar');
        const judul   = document.getElementById('judul_ta');
        const file    = document.getElementById('file_proposal');
        const errFile = document.getElementById('errFile');
        const uploadZone = document.getElementById('uploadZone');

        // Reset error
        [tanggal, judul].forEach(el => {
            el.classList.remove('field-error');
            const prev = el.parentElement.querySelector('.err-msg');
            if (prev && prev !== errFile) prev.remove();
        });
        errFile.style.display = 'none';
        uploadZone.classList.remove('has-error');

        if (!tanggal.value) {
            tanggal.classList.add('field-error');
            let msg = document.createElement('span');
            msg.className = 'err-msg';
            msg.textContent = 'Rencana tanggal seminar wajib diisi';
            tanggal.parentElement.appendChild(msg);
            ok = false;
        }

        if (!judul.value.trim()) {
            judul.classList.add('field-error');
            let msg = document.createElement('span');
            msg.className = 'err-msg';
            msg.textContent = 'Judul tugas akhir wajib diisi';
            judul.parentElement.appendChild(msg);
            ok = false;
        }

        if (!file.files || file.files.length === 0) {
            errFile.style.display = 'block';
            uploadZone.classList.add('has-error');
            ok = false;
        }

        if (!ok) {
            e.preventDefault();
            const firstErr = document.querySelector('.field-error') || uploadZone;
            if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    // Hapus error saat input
    document.querySelectorAll('.form-input, .form-textarea').forEach(el => {
        el.addEventListener('input', function() {
            this.classList.remove('field-error');
            const msg = this.parentElement.querySelector('.err-msg');
            if (msg) msg.remove();
        });
    });
</script>

@endsection