@extends('layouts.app')

@section('content')


<div class="container-fluid px-4 pb-5">

    {{-- KEMBALI --}}
    <a href="{{ route('reviewer.proposal') }}" class="btn-kembali">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>

    {{-- PAGE TITLE --}}
    <div class="page-title-wrap">
        <div class="page-title">Detail Review Proposal Mahasiswa</div>
        <div class="page-sub">Review dan evaluasi proposal mahasiswa sebelum dosen pembimbing ditetapkan.</div>
    </div>

    @php
        $tanggalPengajuan = $proposal->tanggal_pengajuan
            ? \Carbon\Carbon::parse($proposal->tanggal_pengajuan)->translatedFormat('d F Y')
            : '-';
        $tanggalTinjauan  = $proposal->tanggal_tinjauan
            ? \Carbon\Carbon::parse($proposal->tanggal_tinjauan)->translatedFormat('d F Y')
            : '-';
        $namaFile         = $proposal->file_proposal  ? basename($proposal->file_proposal)  : null;
        $namaFileTinjauan = $proposal->file_tinjauan  ? basename($proposal->file_tinjauan)  : null;
        $sudahDireview    = !is_null($proposal->tinjauan_id);
    @endphp

    {{-- ROW 1: Tanggal Pengajuan --}}
    <div class="detail-card mb-14">
        <div class="detail-label">TANGGAL PENGAJUAN</div>
        <div class="detail-value">{{ $tanggalPengajuan }}</div>
    </div>

    {{-- ROW 2: NIM & Nama --}}
    <div class="detail-row2 mb-14">
        <div class="detail-card">
            <div class="detail-label">NIM</div>
            <div class="detail-value">{{ $proposal->nim_nid }}</div>
        </div>
        <div class="detail-card" style="flex:2;">
            <div class="detail-label">NAMA MAHASISWA</div>
            <div class="detail-value">{{ $proposal->nama }}</div>
        </div>
    </div>

    {{-- ROW 3: Status + Judul | File Proposal --}}
    <div class="detail-row2 mb-14" style="align-items:stretch;">

        {{-- Kiri: Status + Judul --}}
        <div style="display:flex; flex-direction:column; gap:14px; flex:1;">
            <div class="detail-card">
                <div class="detail-label">STATUS</div>
                <div style="margin-top:6px;">
                    @if($sudahDireview)
                        <span class="badge-selesai-detail">
                            <i class="fa fa-circle-check" style="margin-right:5px;"></i>Sudah Direview
                        </span>
                    @else
                        <span class="badge-menunggu-detail">
                            <i class="fa fa-hourglass-half" style="margin-right:5px;"></i>Menunggu Review
                        </span>
                    @endif
                </div>
            </div>
            <div class="detail-card" style="flex:1;">
                <div class="detail-label">JUDUL</div>
                <div class="detail-value" style="line-height:1.6; margin-top:6px;">{{ $proposal->judul }}</div>
            </div>
        </div>

        {{-- Kanan: File Proposal --}}
        <div class="detail-card" style="flex:2;">
            <div class="detail-label">PROPOSAL MAHASISWA</div>
            @if($namaFile)
                <a href="{{ asset('storage/' . $proposal->file_proposal) }}"
                   target="_blank"
                   class="file-box-link">
                    <div class="file-box-inner">
                        <div class="file-icon-wrap">
                            <i class="fa fa-file-pdf" style="color:#e53e3e; font-size:1.3rem;"></i>
                        </div>
                        <div class="file-info">
                            <div class="file-name">{{ $namaFile }}</div>
                            <div class="file-meta">Diunggah pada {{ $tanggalPengajuan }}</div>
                        </div>
                        <div class="file-download-btn">
                            <i class="fa fa-download"></i>
                        </div>
                    </div>
                </a>
            @else
                <div style="color:#aaa; font-size:0.88rem; margin-top:10px;">
                    <i class="fa fa-inbox me-1"></i> Tidak ada file proposal
                </div>
            @endif
        </div>

    </div>

    {{-- ══════════════════════════════════════════
         CATATAN: Info bahwa dosbing belum ditetapkan
    ══════════════════════════════════════════ --}}
    <div class="info-belum-dosbing mb-14">
        <i class="fa fa-circle-info" style="color:#FACC15; margin-right:8px; flex-shrink:0;"></i>
        <div>
            <strong style="color:#856404;">Dosen pembimbing belum ditetapkan.</strong>
            <span style="color:#856404; font-size:0.85rem;"> Penetapan dosen pembimbing akan dilakukan oleh koordinator setelah review ini selesai.</span>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         TINJAUAN PROPOSAL
    ══════════════════════════════════════════ --}}
    <div class="section-title-wrap mb-10">
        <div class="section-title">Tinjauan Proposal</div>
        <div class="section-sub">Isi catatan review dan upload file tinjauan jika ada.</div>
    </div>

    @if($sudahDireview)

        {{-- Sudah ada review — tampilkan hasilnya --}}
        <div class="tinjauan-catatan-box mb-14">
            <div class="tinjauan-catatan-icon">
                <i class="fa fa-comment-dots"></i>
            </div>
            <div class="tinjauan-catatan-text">
                "{{ $proposal->catatan }}"
            </div>
        </div>

        <div class="tinjauan-bottom-row mb-14">
            <div style="flex:1;">
                @if($namaFileTinjauan)
                    <a href="{{ asset('storage/' . $proposal->file_tinjauan) }}"
                       target="_blank"
                       class="file-tinjauan-link">
                        <div class="file-tinjauan-inner">
                            <i class="fa fa-file-pdf" style="color:#e53e3e; font-size:1.2rem; margin-right:10px; flex-shrink:0;"></i>
                            <div>
                                <div class="file-name" style="margin-bottom:2px;">{{ $namaFileTinjauan }}</div>
                                <div class="file-meta">Klik untuk membuka file</div>
                            </div>
                        </div>
                    </a>
                @else
                    <div class="file-tinjauan-empty">
                        <i class="fa fa-file-circle-xmark me-2" style="color:#aaa;"></i>
                        Tidak ada file tinjauan
                    </div>
                @endif
            </div>

            <div class="tinjauan-tgl-wrap">
                <div class="tinjauan-tgl-label">Review Selesai Pada</div>
                <div class="tinjauan-tgl-val">{{ $tanggalTinjauan }}</div>
            </div>
        </div>

        {{-- Tombol edit review --}}
        <div style="margin-bottom:24px;">
            <button type="button" onclick="tampilkanFormEdit()"
                style="background:#e8e8e8;border:none;color:#555;font-size:0.85rem;font-weight:600;padding:9px 18px;border-radius:10px;cursor:pointer;">
                ✏ Edit Catatan Review
            </button>
        </div>

        {{-- Form edit (tersembunyi dulu) --}}
        <div id="formEditReview" style="display:none;">
            @include('pengajuan._form_review', ['proposal' => $proposal])
        </div>

    @else

        {{-- Belum ada review — langsung tampilkan form --}}
        @include('pengajuan._form_review', ['proposal' => $proposal])

    @endif

</div>


{{-- ══════════════════════════════════════════════════
     POPUP BERHASIL & GAGAL
══════════════════════════════════════════════════ --}}

<div id="popupReviewBerhasil" class="popup-overlay">
    <div class="popup-box">
        <div class="popup-icon-wrap success">✓</div>
        <div class="popup-title">Berhasil!</div>
        <div class="popup-msg">Catatan review berhasil disimpan.</div>
        <div class="popup-btn-row">
            <button class="popup-btn ok" onclick="window.location.href='{{ route('reviewer.proposal') }}'">OK</button>
        </div>
    </div>
</div>

<div id="popupReviewGagal" class="popup-overlay">
    <div class="popup-box">
        <div class="popup-icon-wrap error">✕</div>
        <div class="popup-title">Gagal!</div>
        <div class="popup-msg">Gagal menyimpan review. Silakan coba lagi.</div>
        <div class="popup-btn-row">
            <button class="popup-btn ok" onclick="tutupPopup('popupReviewGagal')">OK</button>
        </div>
    </div>
</div>

<style>
/* ============================================================
   GLOBAL
   ============================================================ */
.mb-14 { margin-bottom: 14px; }
.mb-10 { margin-bottom: 10px; }

/* ============================================================
   KEMBALI
   ============================================================ */
.btn-kembali {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #735C00;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    margin-bottom: 14px;
    transition: 0.2s;
}
.btn-kembali:hover { color: #4D4632; }

/* ============================================================
   PAGE TITLE
   ============================================================ */
.page-title-wrap { margin-bottom: 22px; }
.page-title {
    font-size: 1.25rem;
    font-weight: 800;
    color: #111C2D;
    margin-bottom: 3px;
}
.page-sub { font-size: 0.85rem; color: #6C5700; }

/* ============================================================
   SECTION TITLE
   ============================================================ */
.section-title {
    font-size: 1rem;
    font-weight: 700;
    color: #111C2D;
    margin-bottom: 2px;
}
.section-sub { font-size: 0.82rem; color: #6C5700; }

/* ============================================================
   DETAIL CARD
   ============================================================ */
.detail-card {
    background: #fff;
    border: 1.5px solid #E5DFD0;
    border-radius: 14px;
    padding: 18px 20px;
}
.detail-label {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.8px;
    color: #888;
    text-transform: uppercase;
    margin-bottom: 8px;
}
.detail-value {
    font-size: 0.97rem;
    font-weight: 600;
    color: #111C2D;
}

/* ============================================================
   ROW 2 KOLOM
   ============================================================ */
.detail-row2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}

/* ============================================================
   BADGE
   ============================================================ */
.badge-selesai-detail {
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
    background: #DCFCE7;
    color: #166534;
    border: 1px solid #bbf7d0;
    border-radius: 20px;
    font-size: 0.83rem;
    font-weight: 700;
}

.badge-menunggu-detail {
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffe082;
    border-radius: 20px;
    font-size: 0.83rem;
    font-weight: 700;
}

/* ============================================================
   INFO BELUM DOSBING
   ============================================================ */
.info-belum-dosbing {
    display: flex;
    align-items: flex-start;
    gap: 4px;
    background: #fffbe6;
    border: 1px solid #ffe082;
    border-radius: 12px;
    padding: 14px 16px;
}

/* ============================================================
   FILE BOX (Proposal)
   ============================================================ */
.file-box-link {
    display: block;
    text-decoration: none;
    margin-top: 12px;
    border-radius: 10px;
    overflow: hidden;
    transition: 0.2s;
}
.file-box-link:hover .file-box-inner { background: #F0EBE0; }
.file-box-inner {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #F7F5F0;
    border: 1.5px solid #E5DFD0;
    border-radius: 10px;
    padding: 12px 14px;
    transition: 0.2s;
}
.file-icon-wrap {
    width: 40px; height: 40px;
    background: #fff;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    border: 1px solid #E5DFD0;
    flex-shrink: 0;
}
.file-info { flex: 1; }
.file-name  { font-size: 0.88rem; font-weight: 600; color: #111C2D; word-break: break-all; }
.file-meta  { font-size: 0.75rem; color: #888; margin-top: 2px; }
.file-download-btn {
    width: 34px; height: 34px;
    border-radius: 8px;
    background: #fff;
    border: 1.5px solid #D1C6AB;
    display: flex; align-items: center; justify-content: center;
    color: #735C00;
    flex-shrink: 0;
    font-size: 0.9rem;
    transition: 0.2s;
}
.file-box-link:hover .file-download-btn { background: #FEF3C7; border-color: #735C00; }

/* ============================================================
   TINJAUAN
   ============================================================ */
.tinjauan-catatan-box {
    background: #fff;
    border: 1.5px solid #E5DFD0;
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    align-items: flex-start;
    gap: 14px;
}
.tinjauan-catatan-icon {
    width: 38px; height: 38px;
    background: #F7F5F0;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: #735C00;
    font-size: 1.1rem;
    flex-shrink: 0;
    border: 1px solid #E5DFD0;
}
.tinjauan-catatan-text {
    font-size: 0.92rem;
    color: #333;
    line-height: 1.7;
    font-style: italic;
}
.tinjauan-bottom-row {
    display: flex;
    align-items: center;
    gap: 20px;
}
.file-tinjauan-link {
    display: block;
    text-decoration: none;
    border-radius: 10px;
    overflow: hidden;
    transition: 0.2s;
}
.file-tinjauan-inner {
    display: flex;
    align-items: center;
    background: #F7F5F0;
    border: 1.5px solid #E5DFD0;
    border-radius: 10px;
    padding: 12px 16px;
    transition: 0.2s;
}
.file-tinjauan-link:hover .file-tinjauan-inner {
    background: #F0EBE0;
    border-color: #D1C6AB;
}
.file-tinjauan-empty {
    background: #F7F5F0;
    border: 1.5px solid #E5DFD0;
    border-radius: 10px;
    padding: 14px 16px;
    font-size: 0.85rem;
    color: #aaa;
}
.tinjauan-tgl-wrap { text-align: right; flex-shrink: 0; white-space: nowrap; }
.tinjauan-tgl-label { font-size: 0.75rem; color: #888; margin-bottom: 3px; }
.tinjauan-tgl-val   { font-size: 0.95rem; font-weight: 700; color: #111C2D; }

/* ============================================================
   FORM REVIEW
   ============================================================ */
.form-review-wrap {
    background: #fff;
    border: 1.5px solid #E5DFD0;
    border-radius: 14px;
    padding: 22px 24px;
    margin-bottom: 14px;
}
.form-review-lbl {
    font-size: 0.82rem;
    font-weight: 600;
    color: #444;
    margin-bottom: 6px;
    display: block;
}
.form-review-textarea {
    width: 100%;
    padding: 12px 14px;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-size: 0.88rem;
    font-family: inherit;
    resize: vertical;
    min-height: 100px;
    box-sizing: border-box;
    color: #111;
    background: #fafafa;
    transition: 0.2s;
}
.form-review-textarea:focus {
    outline: none;
    border-color: #FACC15;
    background: #fff;
}
.form-review-file-input {
    display: block;
    font-size: 0.85rem;
    color: #555;
    margin-top: 4px;
}
.btn-kirim-review {
    padding: 11px 28px;
    background: #FACC15;
    color: #333;
    border: none;
    border-radius: 10px;
    font-size: 0.95rem;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
}
.btn-kirim-review:hover { background: #e6b800; }

/* ============================================================
   POPUP
   ============================================================ */
.popup-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.45); z-index: 99999;
    align-items: center; justify-content: center;
}
.popup-overlay.active { display: flex; }
.popup-box {
    background: #fff; border-radius: 20px; padding: 40px 32px 32px;
    width: 100%; max-width: 400px; margin: 0 16px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.2); text-align: center;
}
.popup-icon-wrap {
    width: 80px; height: 80px; border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    margin-bottom: 20px; font-size: 2.2rem;
}
.popup-icon-wrap.success { background: #e8f5e9; border: 3px solid #66bb6a; color: #28a745; }
.popup-icon-wrap.error   { background: #fdecea; border: 3px solid #ef9a9a; color: #dc3545; }
.popup-title { font-size: 1.5rem; font-weight: 800; color: #222; margin-bottom: 10px; }
.popup-msg   { font-size: 0.92rem; color: #555; margin-bottom: 28px; line-height: 1.5; }
.popup-btn-row { display: flex; gap: 12px; justify-content: center; }
.popup-btn {
    padding: 11px 32px; border-radius: 10px;
    font-size: 0.95rem; font-weight: 700; cursor: pointer; border: none; transition: 0.2s;
}
.popup-btn.ok  { background: #FACC15; color: #333; min-width: 120px; }
.popup-btn.ok:hover { background: #e6b800; }

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 640px) {
    .detail-row2         { grid-template-columns: 1fr; }
    .tinjauan-bottom-row { flex-direction: column; align-items: flex-start; }
    .tinjauan-tgl-wrap   { text-align: left; }
}
</style>

<script>
    function tampilkanFormEdit() {
        var el = document.getElementById('formEditReview');
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }

    function bukaPopup(id) {
        document.getElementById(id).classList.add('active');
    }

    function tutupPopup(id) {
        document.getElementById(id).classList.remove('active');
    }

    function submitReview(formId) {
        var form = document.getElementById(formId);
        var catatan = form.querySelector('textarea[name="catatan"]');
        if (!catatan || !catatan.value.trim()) {
            catatan.style.borderColor = '#dc3545';
            catatan.focus();
            return;
        }

        var formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(res) {
            res.ok ? bukaPopup('popupReviewBerhasil') : bukaPopup('popupReviewGagal');
        })
        .catch(function() { bukaPopup('popupReviewGagal'); });
    }
</script>

@endsection


{{-- ══════════════════════════════════════════════════════════
     PARTIAL: _form_review.blade.php
     Buat file baru: resources/views/pengajuan/_form_review.blade.php
     Isi file tersebut dengan kode di bawah ini:

@csrf
<div class="form-review-wrap">
    <form id="formReview-{{ $proposal->id }}"
          action="{{ route('reviewer.simpanReview', $proposal->id) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:16px;">
            <label class="form-review-lbl">Catatan Review <span style="color:#dc3545;">*</span></label>
            <textarea name="catatan" class="form-review-textarea"
                placeholder="Tuliskan catatan hasil review proposal..."
                maxlength="200">{{ $proposal->catatan ?? '' }}</textarea>
            <div style="font-size:0.75rem;color:#aaa;margin-top:4px;">Maksimal 200 karakter</div>
        </div>

        <div style="margin-bottom:20px;">
            <label class="form-review-lbl">File Tinjauan <span style="color:#aaa;font-weight:400;">(opsional, PDF/DOC maks 10MB)</span></label>
            <input type="file" name="file_tinjauan" class="form-review-file-input"
                accept=".pdf,.doc,.docx">
        </div>

        <div style="display:flex;justify-content:flex-end;">
            <button type="button"
                onclick="submitReview('formReview-{{ $proposal->id }}')"
                class="btn-kirim-review">
                <i class="fa fa-paper-plane me-1"></i> Kirim Review
            </button>
        </div>
    </form>
</div>

     SAMPAI SINI untuk _form_review.blade.php
════════════════════════════════════════════════════════════ --}}