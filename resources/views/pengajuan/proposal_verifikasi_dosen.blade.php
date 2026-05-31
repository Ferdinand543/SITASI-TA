@extends('layouts.app')

@section('content')

<style>
    body { background: #f4f6f9; }

    .wrapper {
        max-width: 900px;
        margin: auto;
        padding: 32px 20px;
    }

    .page-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: #111;
        margin-bottom: 4px;
    }

    .page-sub {
        font-size: 0.9rem;
        color: #888;
        margin-bottom: 24px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 24px;
    }

    .info-box {
        border: 1px solid #e5e5e5;
        border-radius: 14px;
        padding: 16px 18px;
        background: #fff;
    }

    .info-box .lbl {
        font-size: 0.82rem;
        color: #888;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .info-box .val {
        font-size: 1rem;
        font-weight: 700;
        color: #111;
    }

    .val.selesai   { color: #28a745; }
    .val.menunggu  { color: #b8860b; }
    .val.ditolak   { color: #dc3545; }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #111;
        margin-bottom: 2px;
    }

    .section-sub {
        font-size: 0.83rem;
        color: #888;
        margin-bottom: 14px;
    }

    .dosen-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 24px;
    }

    .dosen-card {
        border: 1px solid #e5e5e5;
        border-radius: 14px;
        padding: 18px;
        background: #fff;
        position: relative;
    }

    .dosen-card .dosen-label { font-size: 0.8rem; color: #888; margin-bottom: 8px; }
    .dosen-card .dosen-nama  { font-size: 1rem; font-weight: 700; color: #111; margin-bottom: 2px; }
    .dosen-card .dosen-nidn  { font-size: 0.85rem; color: #555; margin-bottom: 8px; }
    .dosen-card .dosen-tanggal { font-size: 0.8rem; color: #888; }

    .badge-disetujui {
        background: #d4edda; color: #28a745; border: 1px solid #b7dfbb;
        padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; display: inline-block;
    }

    .badge-menunggu {
        background: #fff3cd; color: #856404; border: 1px solid #ffe082;
        padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 700;
        display: inline-block; text-decoration: none; cursor: pointer;
    }

    .badge-menunggu:hover { background: #ffe69c; color: #856404; }

    .badge-ditolak {
        background: #f8d7da; color: #dc3545; border: 1px solid #f1aeb5;
        padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; display: inline-block;
    }

    .proposal-box { display: flex; align-items: center; justify-content: space-between; }
    .proposal-box .file-name { font-size: 0.9rem; font-weight: 600; color: #111; }
    .proposal-box .file-meta { font-size: 0.78rem; color: #888; }

    .footer-btn {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        margin-top: 30px;
    }

    .btn-kembali {
        background: #e0e0e0; color: #333; border: none;
        padding: 10px 22px; border-radius: 20px; font-size: 0.9rem;
        text-decoration: none; transition: 0.2s;
    }

    .btn-kembali:hover { background: #c8c8c8; color: #111; }

    .btn-ubah {
        position: absolute; top: 14px; right: 14px;
        background: #e8e8e8; border: none; color: #555;
        font-size: 0.75rem; font-weight: 600; padding: 4px 12px;
        border-radius: 20px; cursor: pointer; transition: 0.2s;
    }

    .btn-ubah:hover { background: #d4d4d4; color: #333; }

    .icon-user {
        width: 32px; height: 32px; border-radius: 50%; border: 2px solid #ccc;
        display: inline-flex; align-items: center; justify-content: center;
        margin-right: 8px; color: #888; font-size: 16px; vertical-align: middle; flex-shrink: 0;
    }

    /* ── MODAL OVERLAY ── */
    .modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,0.45); z-index: 9999;
        align-items: center; justify-content: center;
    }

    .modal-box-new {
        background: #fff; border-radius: 20px; padding: 32px 32px 28px;
        width: 100%; max-width: 500px;
        box-shadow: 0 12px 40px rgba(0,0,0,0.18); margin: 0 16px; position: relative;
    }

    .modal-close-btn {
        position: absolute; top: 18px; right: 18px;
        background: none; border: none; font-size: 1.1rem;
        color: #aaa; cursor: pointer; line-height: 1; transition: 0.15s;
    }

    .modal-close-btn:hover { color: #333; }

    .modal-title-new { font-size: 1.25rem; font-weight: 800; color: #111; margin-bottom: 6px; }
    .modal-sub-new   { font-size: 0.83rem; color: #888; margin-bottom: 24px; line-height: 1.5; }

    .modal-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px; }
    .modal-field { display: flex; flex-direction: column; }
    .modal-lbl { font-size: 0.82rem; font-weight: 600; color: #444; margin-bottom: 5px; }

    .modal-inp {
        padding: 10px 13px; border: 1.5px solid #e5e7eb; border-radius: 10px;
        font-size: 0.88rem; color: #111; background: #f9fafb;
        font-family: inherit; box-sizing: border-box; width: 100%;
    }

    .modal-inp[readonly] { cursor: default; color: #555; }
    .modal-inp:focus { outline: none; border-color: #FACC15; background: #fff; }

    .usulan-row { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; }
    .usulan-inp-wrap { flex: 1; position: relative; }

    .usulan-inp-icon {
        position: absolute; left: 12px; top: 50%;
        transform: translateY(-50%); color: #aaa; font-size: 0.9rem;
    }

    .usulan-inp {
        width: 100%; padding: 10px 13px 10px 34px;
        border: 1.5px solid #e5e7eb; border-radius: 10px;
        font-size: 0.88rem; color: #111; background: #f9fafb; box-sizing: border-box;
    }

    .usulan-inp.error { border-color: #dc3545; }

    .btn-acc {
        width: 40px; height: 40px; border-radius: 50%; border: none;
        background: #d4edda; color: #28a745; font-size: 1.1rem; font-weight: 700;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: 0.2s;
    }

    .btn-acc:hover { background: #b7dfbb; }

    .btn-tolak-x {
        width: 40px; height: 40px; border-radius: 50%; border: none;
        background: #f8d7da; color: #dc3545; font-size: 1.1rem; font-weight: 700;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: 0.2s;
    }

    .btn-tolak-x:hover { background: #f1aeb5; }

    .error-msg { font-size: 0.78rem; color: #dc3545; margin-top: 4px; display: none; }
    .error-msg.show { display: block; }

    .catatan-box {
        background: #fffbe6; border: 1px solid #ffe082; border-radius: 12px;
        padding: 14px 16px; margin-bottom: 24px; display: flex; gap: 10px; align-items: flex-start;
    }

    .catatan-box-icon  { color: #f59e0b; font-size: 1rem; flex-shrink: 0; margin-top: 1px; }
    .catatan-box-title { font-size: 0.82rem; font-weight: 700; color: #856404; margin-bottom: 3px; }
    .catatan-box-text  { font-size: 0.8rem; color: #856404; line-height: 1.5; }

    .modal-footer-new { display: flex; justify-content: flex-end; gap: 10px; margin-top: 4px; }

    .btn-modal-kembali {
        padding: 10px 26px; border-radius: 10px; border: 1.5px solid #e5e7eb;
        background: #fff; color: #555; font-size: 0.9rem; font-weight: 600;
        cursor: pointer; transition: 0.2s;
    }

    .btn-modal-kembali:hover { background: #f5f5f5; }

    .btn-modal-kirim {
        padding: 10px 26px; border-radius: 10px; border: none;
        background: #FACC15; color: #333; font-size: 0.9rem; font-weight: 700;
        cursor: pointer; transition: 0.2s;
    }

    .btn-modal-kirim:hover { background: #e6b800; }

    .section-tolak-new { display: none; }

    .modal-select {
        width: 100%; padding: 10px 13px; border: 1.5px solid #e5e7eb;
        border-radius: 10px; font-size: 0.88rem; background: #fff;
        box-sizing: border-box; margin-top: 0;
    }

    .modal-select:focus { outline: none; border-color: #FACC15; }

    .modal-box-ubah {
        background: #fff; border-radius: 20px; padding: 32px 32px 28px;
        width: 100%; max-width: 460px;
        box-shadow: 0 12px 40px rgba(0,0,0,0.18); margin: 0 16px; position: relative;
    }

    /* ── POPUP ── */
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
    .popup-icon-wrap.confirm { background: #fff8e1; border: 3px solid #fdd835; color: #f9a825; }
    .popup-icon-wrap.warning { background: #fdecea; border: 3px solid #ef9a9a; color: #dc3545; }

    .popup-title { font-size: 1.5rem; font-weight: 800; color: #222; margin-bottom: 10px; }
    .popup-msg   { font-size: 0.92rem; color: #555; margin-bottom: 28px; line-height: 1.5; }
    .popup-btn-row { display: flex; gap: 12px; justify-content: center; }

    .popup-btn {
        padding: 11px 32px; border-radius: 10px; font-size: 0.95rem;
        font-weight: 700; cursor: pointer; border: none; transition: 0.2s;
    }

    .popup-btn.ok,
    .popup-btn.kirim { background: #FACC15; color: #333; min-width: 120px; }
    .popup-btn.ok:hover,
    .popup-btn.kirim:hover { background: #e6b800; }
    .popup-btn.batal { background: #e0e0e0; color: #333; min-width: 100px; }
    .popup-btn.batal:hover { background: #c8c8c8; }

    /* ── BOX STATUS REVIEWER ── */
    .status-reviewer-box {
        display: flex; align-items: center; gap: 14px;
        background: #fff; border: 1px solid #e5e5e5; border-radius: 14px;
        padding: 18px 20px; margin-bottom: 24px;
    }

    .status-reviewer-icon {
        width: 44px; height: 44px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; flex-shrink: 0;
    }

    .status-reviewer-icon.menunggu { background: #fff3cd; color: #856404; }
    .status-reviewer-icon.selesai  { background: #d4edda; color: #28a745; }

    .reviewer-assign-box {
        background: #fff; border: 1px solid #e5e5e5;
        border-radius: 14px; padding: 20px; margin-bottom: 24px;
    }
</style>

<div class="wrapper">

    <div class="page-title">Verifikasi Proposal Mahasiswa</div>
    <div class="page-sub">Kelola verifikasi dan penetapan dosen pembimbing tugas akhir mahasiswa.</div>

    @if(session('success'))
    <div style="background:#d4edda;color:#28a745;border:1px solid #b7dfbb;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:0.9rem;">
        <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background:#f8d7da;color:#dc3545;border:1px solid #f1aeb5;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:0.9rem;">
        <i class="fa fa-times-circle me-1"></i> {{ session('error') }}
    </div>
    @endif

    {{-- ═══════════════════════════════════════
         INFO GRID (selalu tampil)
    ════════════════════════════════════════ --}}
    <div class="info-grid">
        <div class="info-box">
            <div class="lbl"><i class="fa fa-calendar"></i> Tanggal Pengajuan</div>
            <div class="val">{{ \Carbon\Carbon::parse($proposal->tanggal_pengajuan)->translatedFormat('d M Y') }}</div>
        </div>
        <div class="info-box">
            <div class="lbl"><i class="fa fa-user"></i> NIM</div>
            <div class="val">{{ $proposal->nim_nid }}</div>
        </div>
        <div class="info-box">
            <div class="lbl"><i class="fa fa-clock"></i> Status</div>
            @php $st = strtolower(trim($proposal->status)); @endphp
            <div class="val {{ str_contains($st,'menunggu') ? 'menunggu' : ($st=='selesai' ? 'selesai' : 'ditolak') }}">
                {{ ucwords(str_replace('_',' ',$proposal->status)) }}
            </div>
        </div>
        <div class="info-box">
            <div class="lbl"><i class="fa fa-users"></i> Nama</div>
            <div class="val">{{ $proposal->nama }}</div>
        </div>
        <div class="info-box">
            <div class="lbl">Judul</div>
            <div class="val" style="font-size:0.95rem;">{{ $proposal->judul }}</div>
        </div>
        <div class="info-box">
            <div class="lbl">Proposal</div>
            @if($proposal->file_proposal)
            <div class="proposal-box mt-2">
                <div>
                    <div class="file-name"><i class="fa fa-file-pdf text-danger me-1"></i>{{ basename($proposal->file_proposal) }}</div>
                    <div class="file-meta">Diunggah pada {{ \Carbon\Carbon::parse($proposal->tanggal_pengajuan)->translatedFormat('d M') }}</div>
                </div>
                <a href="{{ asset('storage/'.$proposal->file_proposal) }}" target="_blank" class="text-dark"><i class="fa fa-download"></i></a>
            </div>
            @else
            <div class="val text-muted" style="font-size:0.9rem;">Belum ada file proposal</div>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         KONDISI BERDASARKAN STATUS
    ════════════════════════════════════════ --}}
    @php
        $stProposal = strtolower(trim($proposal->status));
        $sudahPunyaReviewer = !empty($proposal->nim_nid_reviewer);
    @endphp

    {{-- ─────────────────────────────────────
         STATUS: belum ada reviewer
         → tampilkan form ASSIGN REVIEWER
    ────────────────────────────────────── --}}
    @if(!$sudahPunyaReviewer && $stProposal !== 'selesai')

        <div class="section-title">Penugasan Reviewer</div>
        <div class="section-sub">Pilih dosen reviewer untuk mengevaluasi proposal ini sebelum ditetapkan pembimbingnya.</div>

        @php
            $reviewerList = \DB::table('users')
                ->join('dosen_roles', 'users.nim_nid', '=', 'dosen_roles.nim_nid')
                ->where('dosen_roles.role_dosen', 'reviewer')
                ->select('users.nim_nid', 'users.nama')
                ->distinct()->get();
        @endphp

        <div class="reviewer-assign-box">
            <form action="{{ url('/proposal/'.$proposal->id.'/assign-reviewer') }}" method="POST">
                @csrf
                <div style="display:flex;gap:10px;align-items:flex-end;">
                    <div style="flex:1;">
                        <label style="font-size:0.82rem;font-weight:600;color:#444;margin-bottom:6px;display:block;">
                            <i class="fa fa-user-check me-1" style="color:#FACC15;"></i> Pilih Dosen Reviewer
                        </label>
                        <select name="nim_nid_reviewer"
                            style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.88rem;background:#fff;">
                            <option value="">-- Pilih Dosen Reviewer --</option>
                            @foreach($reviewerList as $r)
                                <option value="{{ $r->nim_nid }}">{{ $r->nim_nid }} - {{ $r->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        style="padding:11px 22px;background:#FACC15;color:#333;border:none;border-radius:10px;font-weight:700;cursor:pointer;white-space:nowrap;font-size:0.9rem;">
                        <i class="fa fa-paper-plane me-1"></i> Tetapkan & Teruskan
                    </button>
                </div>
                <div style="margin-top:12px;font-size:0.8rem;color:#888;">
                    <i class="fa fa-circle-info me-1" style="color:#FACC15;"></i>
                    Setelah reviewer ditetapkan, proposal akan masuk ke antrian review dosen yang dipilih.
                </div>
            </form>
        </div>

    {{-- ─────────────────────────────────────
         STATUS: menunggu_review
         → reviewer sudah dipilih, belum selesai direview
    ────────────────────────────────────── --}}
    @elseif($stProposal === 'menunggu_review')

        @php
            $reviewerData = \DB::table('users')->where('nim_nid', $proposal->nim_nid_reviewer)->first();
        @endphp

        <div class="section-title">Status Review</div>
        <div class="section-sub">Proposal sudah diteruskan ke reviewer. Menunggu hasil review.</div>

        <div class="status-reviewer-box">
            <div class="status-reviewer-icon menunggu">
                <i class="fa fa-hourglass-half"></i>
            </div>
            <div style="flex:1;">
                <div style="font-weight:700;color:#111;margin-bottom:2px;">Menunggu Review</div>
                <div style="font-size:0.85rem;color:#666;">
                    Reviewer: <strong>{{ $reviewerData->nama ?? $proposal->nim_nid_reviewer }}</strong>
                    @if($reviewerData)
                        <span style="color:#aaa;margin-left:4px;">({{ $proposal->nim_nid_reviewer }})</span>
                    @endif
                </div>
                <div style="font-size:0.78rem;color:#aaa;margin-top:4px;">
                    Proposal sedang dalam antrian review dosen yang ditunjuk.
                </div>
            </div>
            {{-- Tombol ganti reviewer jika mau diganti --}}
            <div>
                @php
                    $reviewerList2 = \DB::table('users')
                        ->join('dosen_roles', 'users.nim_nid', '=', 'dosen_roles.nim_nid')
                        ->where('dosen_roles.role_dosen', 'reviewer')
                        ->select('users.nim_nid', 'users.nama')
                        ->distinct()->get();
                @endphp
                <button type="button"
                    onclick="document.getElementById('formGantiReviewer').style.display = document.getElementById('formGantiReviewer').style.display === 'none' ? 'block' : 'none'"
                    style="background:#e8e8e8;border:none;color:#555;font-size:0.78rem;font-weight:600;padding:6px 14px;border-radius:20px;cursor:pointer;">
                    ✏ Ganti Reviewer
                </button>
            </div>
        </div>

        <div id="formGantiReviewer" style="display:none;background:#fff;border:1px solid #e5e5e5;border-radius:14px;padding:18px 20px;margin-bottom:24px;">
            <form action="{{ url('/proposal/'.$proposal->id.'/assign-reviewer') }}" method="POST">
                @csrf
                <label style="font-size:0.82rem;font-weight:600;color:#444;margin-bottom:6px;display:block;">Ganti Reviewer</label>
                <div style="display:flex;gap:10px;align-items:center;">
                    <select name="nim_nid_reviewer"
                        style="flex:1;padding:10px 13px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.88rem;background:#fff;">
                        <option value="">-- Pilih Dosen Reviewer --</option>
                        @foreach($reviewerList2 as $r)
                            <option value="{{ $r->nim_nid }}" {{ $proposal->nim_nid_reviewer == $r->nim_nid ? 'selected' : '' }}>
                                {{ $r->nim_nid }} - {{ $r->nama }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit"
                        style="padding:10px 18px;background:#FACC15;color:#333;border:none;border-radius:10px;font-weight:700;cursor:pointer;font-size:0.88rem;">
                        Simpan
                    </button>
                </div>
            </form>
        </div>

    {{-- ─────────────────────────────────────
         STATUS: selesai
         → reviewer udah review, koor tetapkan dosbing
    ────────────────────────────────────── --}}
    @elseif($stProposal === 'selesai')

        {{-- Info reviewer yang sudah mereview --}}
        @php
            $reviewerData = \DB::table('users')->where('nim_nid', $proposal->nim_nid_reviewer)->first();
            $hasilReview  = \DB::table('tinjauan_proposal')->where('proposal_id', $proposal->id)->first();
        @endphp

        <div class="section-title">Hasil Review</div>
        <div class="section-sub">Proposal sudah selesai direview. Silakan tetapkan dosen pembimbing.</div>

        <div class="status-reviewer-box" style="margin-bottom:24px;">
            <div class="status-reviewer-icon selesai">
                <i class="fa fa-circle-check"></i>
            </div>
            <div style="flex:1;">
                <div style="font-weight:700;color:#111;margin-bottom:2px;">Review Selesai</div>
                <div style="font-size:0.85rem;color:#555;">
                    Reviewer: <strong>{{ $reviewerData->nama ?? $proposal->nim_nid_reviewer }}</strong>
                </div>
                @if($hasilReview && $hasilReview->catatan)
                <div style="margin-top:8px;background:#f9f9f9;border:1px solid #e5e5e5;border-radius:8px;padding:10px 14px;font-size:0.85rem;color:#444;font-style:italic;">
                    "{{ $hasilReview->catatan }}"
                </div>
                @endif
                @if($hasilReview && $hasilReview->file_tinjauan)
                <div style="margin-top:8px;">
                    <a href="{{ asset('storage/'.$hasilReview->file_tinjauan) }}" target="_blank"
                        style="font-size:0.8rem;color:#735C00;font-weight:600;text-decoration:none;">
                        <i class="fa fa-file-pdf text-danger me-1"></i> Lihat File Tinjauan
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- USULAN PEMBIMBING --}}
        <div class="section-title">Usulan Pembimbing</div>
        <div class="section-sub">Daftar dosen yang diusulkan mahasiswa — verifikasi satu per satu.</div>

        <div class="dosen-grid">
            {{-- Usulan 1 --}}
            <div class="dosen-card">
                <div class="dosen-label">Usulan Pembimbing 1</div>
                @if($proposal->usulan_dosen1_nama)
                <div style="display:flex;align-items:center;margin-bottom:4px;">
                    <span class="icon-user"><i class="fa fa-user"></i></span>
                    <div>
                        <div class="dosen-nama">{{ $proposal->usulan_dosen1_nama }}</div>
                        <div class="dosen-nidn">NIDN. {{ $proposal->usulan_dosen1_nidn }}</div>
                    </div>
                </div>
                <div class="dosen-tanggal mb-2">
                    Diusulkan pada<br>
                    {{ $proposal->usulan_dosen1_tanggal ? \Carbon\Carbon::parse($proposal->usulan_dosen1_tanggal)->translatedFormat('d M Y') : '-' }}
                </div>
                @php $s1 = strtolower($proposal->usulan_dosen1_status ?? 'menunggu'); @endphp
                @if($s1 == 'disetujui')
                    <span class="badge-disetujui">Disetujui</span>
                @elseif($s1 == 'ditolak')
                    <span class="badge-ditolak">Ditolak</span>
                @else
                    <button type="button" class="badge-menunggu"
                        onclick="bukaModal(1,'{{ addslashes($proposal->usulan_dosen1_nama) }}','{{ $proposal->usulan_dosen1_nidn }}','{{ $proposal->nim_nid }}','{{ addslashes($proposal->nama) }}')">
                        Menunggu Verifikasi
                    </button>
                @endif
                @else
                    <span class="text-muted">-</span>
                @endif
            </div>

            {{-- Usulan 2 --}}
            <div class="dosen-card">
                <div class="dosen-label">Usulan Pembimbing 2</div>
                @if($proposal->usulan_dosen2_nama)
                <div style="display:flex;align-items:center;margin-bottom:4px;">
                    <span class="icon-user"><i class="fa fa-user"></i></span>
                    <div>
                        <div class="dosen-nama">{{ $proposal->usulan_dosen2_nama }}</div>
                        <div class="dosen-nidn">NIDN. {{ $proposal->usulan_dosen2_nidn }}</div>
                    </div>
                </div>
                <div class="dosen-tanggal mb-2">
                    Diusulkan pada<br>
                    {{ $proposal->usulan_dosen2_tanggal ? \Carbon\Carbon::parse($proposal->usulan_dosen2_tanggal)->translatedFormat('d M Y') : '-' }}
                </div>
                @php $s2 = strtolower($proposal->usulan_dosen2_status ?? 'menunggu'); @endphp
                @if($s2 == 'disetujui')
                    <span class="badge-disetujui">Disetujui</span>
                @elseif($s2 == 'ditolak')
                    <span class="badge-ditolak">Ditolak</span>
                @else
                    <button type="button" class="badge-menunggu"
                        onclick="bukaModal(2,'{{ addslashes($proposal->usulan_dosen2_nama) }}','{{ $proposal->usulan_dosen2_nidn }}','{{ $proposal->nim_nid }}','{{ addslashes($proposal->nama) }}')">
                        Menunggu Verifikasi
                    </button>
                @endif
                @else
                    <span class="text-muted">-</span>
                @endif
            </div>
        </div>

        {{-- DOSEN PEMBIMBING --}}
        <div class="section-title">Dosen Pembimbing</div>
        <div class="section-sub">Dosen Pembimbing yang telah ditetapkan untuk Tugas Akhir ini.</div>

        <div class="dosen-grid">
            {{-- Pembimbing 1 --}}
            <div class="dosen-card">
                <div class="dosen-label">Pembimbing 1</div>
                @if($proposal->dosen1_nama)
                <button type="button" class="btn-ubah"
                    data-urutan="1"
                    data-nama="{{ addslashes($proposal->dosen1_nama) }}"
                    data-nidn="{{ $proposal->dosen1_nidn }}"
                    data-nidn-lain="{{ $proposal->dosen2_nidn }}"
                    onclick="bukaModalUbah(this)">✏ Ubah Pembimbing</button>
                <div style="display:flex;align-items:center;margin-bottom:4px;">
                    <span class="icon-user"><i class="fa fa-user"></i></span>
                    <div>
                        <div class="dosen-nama">{{ $proposal->dosen1_nama }}</div>
                        <div class="dosen-nidn">NIDN. {{ $proposal->dosen1_nidn }}</div>
                    </div>
                </div>
                <div class="dosen-tanggal">Ditetapkan pada<br>{{ $proposal->dosen1_tanggal ? \Carbon\Carbon::parse($proposal->dosen1_tanggal)->translatedFormat('d M Y') : '-' }}</div>
                @else
                <div style="display:flex;align-items:center;margin-bottom:8px;">
                    <span class="icon-user"><i class="fa fa-user"></i></span>
                    <span class="text-muted" style="font-size:0.9rem;">[Menunggu verifikasi]</span>
                </div>
                <div class="dosen-tanggal">Ditetapkan pada<br>-</div>
                @endif
            </div>

            {{-- Pembimbing 2 --}}
            <div class="dosen-card">
                <div class="dosen-label">Pembimbing 2</div>
                @if($proposal->dosen2_nama)
                <button type="button" class="btn-ubah"
                    data-urutan="2"
                    data-nama="{{ addslashes($proposal->dosen2_nama) }}"
                    data-nidn="{{ $proposal->dosen2_nidn }}"
                    data-nidn-lain="{{ $proposal->dosen1_nidn }}"
                    onclick="bukaModalUbah(this)">✏ Ubah Pembimbing</button>
                <div style="display:flex;align-items:center;margin-bottom:4px;">
                    <span class="icon-user"><i class="fa fa-user"></i></span>
                    <div>
                        <div class="dosen-nama">{{ $proposal->dosen2_nama }}</div>
                        <div class="dosen-nidn">NIDN. {{ $proposal->dosen2_nidn }}</div>
                    </div>
                </div>
                <div class="dosen-tanggal">Ditetapkan pada<br>{{ $proposal->dosen2_tanggal ? \Carbon\Carbon::parse($proposal->dosen2_tanggal)->translatedFormat('d M Y') : '-' }}</div>
                @else
                <div style="display:flex;align-items:center;margin-bottom:8px;">
                    <span class="icon-user"><i class="fa fa-user"></i></span>
                    <span class="text-muted" style="font-size:0.9rem;">[Menunggu verifikasi]</span>
                </div>
                <div class="dosen-tanggal">Ditetapkan pada<br>-</div>
                @endif
            </div>
        </div>

    @endif
    {{-- end kondisi status --}}

    {{-- FOOTER --}}
    <div class="footer-btn">
        <a href="/proposal" class="btn-kembali">Kembali</a>
    </div>

</div>

{{-- ══════════════════════════════════════════════
     MODAL VERIFIKASI USULAN PEMBIMBING
     (hanya muncul saat status selesai)
════════════════════════════════════════════════ --}}
<div id="modalVerifikasi" class="modal-overlay">
    <div class="modal-box-new">

        <button class="modal-close-btn" onclick="tutupModal()">✕</button>

        <div class="modal-title-new">Form Penetapan Dosen Pembimbing <span id="modalUrutanLabel">1</span> TA</div>
        <div class="modal-sub-new">Lengkapi data berikut untuk melakukan verifikasi dosen pembimbing tugas akhir mahasiswa.</div>

        <form id="formAcc" method="POST" action="">
            @csrf
            <input type="hidden" name="aksi" value="acc">

            <div class="modal-row2">
                <div class="modal-field">
                    <label class="modal-lbl">NIM</label>
                    <input type="text" id="modalNim" readonly class="modal-inp">
                </div>
                <div class="modal-field">
                    <label class="modal-lbl">Nama Mahasiswa</label>
                    <input type="text" id="modalNama" readonly class="modal-inp">
                </div>
            </div>

            <div class="modal-field" style="margin-bottom:16px;">
                <label class="modal-lbl">Usulan Pembimbing <span id="modalUrutanLabel2">1</span></label>
                <div class="usulan-row">
                    <div class="usulan-inp-wrap">
                        <i class="fa fa-user usulan-inp-icon"></i>
                        <input type="text" id="modalUsulanDosen" readonly class="usulan-inp">
                    </div>
                    <button type="button" class="btn-acc" onclick="konfirmasiAcc()">
                        <i class="fa fa-check"></i>
                    </button>
                    <button type="button" class="btn-tolak-x" onclick="tampilkanDropdown()">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div id="errorMsgUsulan" class="error-msg"></div>
            </div>

            <div class="catatan-box">
                <i class="fa fa-circle-info catatan-box-icon"></i>
                <div>
                    <div class="catatan-box-title">Catatan Verifikasi</div>
                    <div class="catatan-box-text">Pastikan kesesuaian topik penelitian mahasiswa dengan kepakaran dosen.</div>
                </div>
            </div>

            <div class="modal-footer-new">
                <button type="button" onclick="tutupModal()" class="btn-modal-kembali">Kembali</button>
                <button type="button" onclick="konfirmasiAcc()" class="btn-modal-kirim">Kirim</button>
            </div>
        </form>

        <div id="sectionTolak" class="section-tolak-new">
            <form id="formTolak" method="POST" action="">
                @csrf
                <input type="hidden" name="aksi" value="tolak">

                <div class="modal-row2" style="margin-bottom:16px;">
                    <div class="modal-field">
                        <label class="modal-lbl">NIM</label>
                        <input type="text" id="modalNimTolak" readonly class="modal-inp">
                    </div>
                    <div class="modal-field">
                        <label class="modal-lbl">Nama Mahasiswa</label>
                        <input type="text" id="modalNamaTolak" readonly class="modal-inp">
                    </div>
                </div>

                <div class="modal-field" style="margin-bottom:6px;">
                    <label class="modal-lbl">Usulan Pembimbing <span id="modalUrutanLabel3">1</span></label>
                    <div class="usulan-row">
                        <div class="usulan-inp-wrap">
                            <i class="fa fa-user usulan-inp-icon"></i>
                            <input type="text" id="modalUsulanDosenTolak" readonly class="usulan-inp error">
                        </div>
                        <button type="button" class="btn-tolak-x" style="background:#dc3545;color:#fff;" disabled>
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    <div class="error-msg show" id="errorKuota">Kuota dosen penuh atau tidak bersedia.</div>
                </div>

                <div class="modal-field" style="margin-bottom:16px;">
                    <label class="modal-lbl">Pembimbing Pengganti <span id="modalUrutanLabel4">1</span></label>
                    <select name="dosen_pengganti" class="modal-select" id="selectPengganti">
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($dosenList as $d)
                            @if($d->nim_nid != $proposal->dosen1_nidn && $d->nim_nid != $proposal->dosen2_nidn)
                            <option value="{{ $d->nim_nid }}">{{ $d->nim_nid }} - {{ $d->nama }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="catatan-box">
                    <i class="fa fa-circle-info catatan-box-icon"></i>
                    <div>
                        <div class="catatan-box-title">Catatan Verifikasi</div>
                        <div class="catatan-box-text">Pastikan kesesuaian topik penelitian mahasiswa dengan kepakaran dosen.</div>
                    </div>
                </div>

                <div class="modal-footer-new">
                    <button type="button" onclick="sembunyikanDropdown()" class="btn-modal-kembali">Kembali</button>
                    <button type="button" onclick="konfirmasiTolak()" class="btn-modal-kirim">Kirim</button>
                </div>
            </form>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════
     MODAL UBAH PEMBIMBING
════════════════════════════════════════════════ --}}
<div id="modalUbah" class="modal-overlay">
    <div class="modal-box-ubah">
        <button class="modal-close-btn" onclick="tutupModalUbah()">✕</button>
        <div class="modal-title-new">Ubah Dosen Pembimbing <span id="ubahUrutanLabel">1</span></div>
        <div class="modal-sub-new">Pilih dosen pengganti untuk pembimbing ini.</div>

        <form id="formUbah" method="POST" action="">
            @csrf
            <div class="modal-field" style="margin-bottom:14px;">
                <label class="modal-lbl">Pembimbing Saat Ini</label>
                <input type="text" id="ubahDosenSekarang" readonly class="modal-inp">
            </div>
            <div class="modal-field" style="margin-bottom:20px;">
                <label class="modal-lbl">Ganti Dengan</label>
                <select name="dosen_baru" class="modal-select" id="ubahDosenBaru">
                    <option value="">-- Pilih Dosen --</option>
                </select>
            </div>
            <div class="modal-footer-new">
                <button type="button" onclick="tutupModalUbah()" class="btn-modal-kembali">Kembali</button>
                <button type="button" onclick="konfirmasiUbah()" class="btn-modal-kirim">Kirim</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     SEMUA POPUP
════════════════════════════════════════════════ --}}

<div id="popupKonfirmasiAcc" class="popup-overlay">
    <div class="popup-box">
        <div class="popup-icon-wrap confirm">❓</div>
        <div class="popup-title">Konfirmasi</div>
        <div class="popup-msg">Apakah Anda yakin ingin menyetujui usulan dosen pembimbing ini?</div>
        <div class="popup-btn-row">
            <button class="popup-btn batal" onclick="tutupPopup('popupKonfirmasiAcc')">Batal</button>
            <button class="popup-btn kirim" onclick="submitAcc()">Konfirmasi</button>
        </div>
    </div>
</div>

<div id="popupKonfirmasiTolak" class="popup-overlay">
    <div class="popup-box">
        <div class="popup-icon-wrap confirm">❓</div>
        <div class="popup-title">Konfirmasi</div>
        <div class="popup-msg">Apakah Anda yakin ingin menolak usulan ini dan memilih pengganti?</div>
        <div class="popup-btn-row">
            <button class="popup-btn batal" onclick="tutupPopup('popupKonfirmasiTolak')">Batal</button>
            <button class="popup-btn kirim" onclick="submitTolak()">Konfirmasi</button>
        </div>
    </div>
</div>

<div id="popupPembimbingBerhasil" class="popup-overlay">
    <div class="popup-box">
        <div class="popup-icon-wrap success">✓</div>
        <div class="popup-title">Berhasil!</div>
        <div class="popup-msg">Penentuan Dosen Pembimbing Berhasil Disimpan.</div>
        <div class="popup-btn-row">
            <button class="popup-btn ok" onclick="window.location.reload()">OK</button>
        </div>
    </div>
</div>

<div id="popupPembimbingGagal" class="popup-overlay">
    <div class="popup-box">
        <div class="popup-icon-wrap error">✕</div>
        <div class="popup-title">Gagal!</div>
        <div class="popup-msg">Gagal menyimpan data dosen pembimbing. Silakan coba lagi.</div>
        <div class="popup-btn-row">
            <button class="popup-btn ok" onclick="tutupPopup('popupPembimbingGagal')">OK</button>
        </div>
    </div>
</div>

<div id="popupKonfirmasiUbah" class="popup-overlay">
    <div class="popup-box">
        <div class="popup-icon-wrap confirm">❓</div>
        <div class="popup-title">Konfirmasi</div>
        <div class="popup-msg">Apakah Anda yakin ingin mengubah dosen pembimbing ini?</div>
        <div class="popup-btn-row">
            <button class="popup-btn batal" onclick="tutupPopup('popupKonfirmasiUbah')">Batal</button>
            <button class="popup-btn kirim" onclick="submitUbah()">Konfirmasi</button>
        </div>
    </div>
</div>

@php
$dosen1Nama = $proposal->dosen1_nama ?? null;
$dosen2Nama = $proposal->dosen2_nama ?? null;
@endphp

<script>
    const semuaDosen = @json($dosenList);

    function bukaPopup(id) {
        document.getElementById(id).classList.add('active');
    }

    function tutupPopup(id) {
        document.getElementById(id).classList.remove('active');
    }

    // ── Modal Verifikasi Usulan ──
    function bukaModal(urutan, nama, nidn, nimMhs, namaMhs) {
        document.getElementById('modalUrutanLabel').innerText  = urutan;
        document.getElementById('modalUrutanLabel2').innerText = urutan;
        document.getElementById('modalUrutanLabel3').innerText = urutan;
        document.getElementById('modalUrutanLabel4').innerText = urutan;

        document.getElementById('modalNim').value            = nimMhs;
        document.getElementById('modalNama').value           = namaMhs;
        document.getElementById('modalNimTolak').value       = nimMhs;
        document.getElementById('modalNamaTolak').value      = namaMhs;
        document.getElementById('modalUsulanDosen').value    = nidn + ' - ' + nama;
        document.getElementById('modalUsulanDosenTolak').value = nidn + ' - ' + nama;

        var baseUrl = "{{ url('/proposal/'.$proposal->id.'/tetapkan') }}/" + urutan;
        document.getElementById('formAcc').action  = baseUrl;
        document.getElementById('formTolak').action = baseUrl;

        sembunyikanDropdown();
        document.getElementById('modalVerifikasi').style.display = 'flex';
    }

    function tampilkanDropdown() {
        document.getElementById('formAcc').style.display      = 'none';
        document.getElementById('sectionTolak').style.display = 'block';
    }

    function sembunyikanDropdown() {
        document.getElementById('formAcc').style.display      = 'block';
        document.getElementById('sectionTolak').style.display = 'none';
    }

    function tutupModal() {
        document.getElementById('modalVerifikasi').style.display = 'none';
    }

    document.getElementById('modalVerifikasi').addEventListener('click', function(e) {
        if (e.target === this) tutupModal();
    });

    function konfirmasiAcc() {
        bukaPopup('popupKonfirmasiAcc');
    }

    function submitAcc() {
        tutupPopup('popupKonfirmasiAcc');
        tutupModal();
        var form = document.getElementById('formAcc');
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(res) {
            res.ok ? bukaPopup('popupPembimbingBerhasil') : bukaPopup('popupPembimbingGagal');
        })
        .catch(function() { bukaPopup('popupPembimbingGagal'); });
    }

    function konfirmasiTolak() {
        var sel = document.querySelector('#formTolak select[name="dosen_pengganti"]');
        if (!sel || !sel.value) return;
        bukaPopup('popupKonfirmasiTolak');
    }

    function submitTolak() {
        tutupPopup('popupKonfirmasiTolak');
        tutupModal();
        var form = document.getElementById('formTolak');
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(res) {
            res.ok ? bukaPopup('popupPembimbingBerhasil') : bukaPopup('popupPembimbingGagal');
        })
        .catch(function() { bukaPopup('popupPembimbingGagal'); });
    }

    // ── Modal Ubah Pembimbing ──
    function bukaModalUbah(btn) {
        var urutan   = btn.getAttribute('data-urutan');
        var nama     = btn.getAttribute('data-nama');
        var nidn     = btn.getAttribute('data-nidn');
        var nidnLain = btn.getAttribute('data-nidn-lain');

        document.getElementById('ubahUrutanLabel').innerText    = urutan;
        document.getElementById('ubahDosenSekarang').value      = nidn + ' - ' + nama;

        var url = "{{ url('/proposal/'.$proposal->id.'/ubah-pembimbing') }}/" + urutan;
        document.getElementById('formUbah').action = url;

        var select = document.getElementById('ubahDosenBaru');
        select.innerHTML = '<option value="">-- Pilih Dosen --</option>';
        semuaDosen.forEach(function(d) {
            if (d.nim_nid === nidnLain) return;
            var opt = document.createElement('option');
            opt.value       = d.nim_nid;
            opt.textContent = d.nim_nid + ' - ' + d.nama;
            select.appendChild(opt);
        });

        document.getElementById('modalUbah').style.display = 'flex';
    }

    function tutupModalUbah() {
        document.getElementById('modalUbah').style.display = 'none';
    }

    document.getElementById('modalUbah').addEventListener('click', function(e) {
        if (e.target === this) tutupModalUbah();
    });

    function konfirmasiUbah() {
        var sel = document.getElementById('ubahDosenBaru');
        if (!sel || !sel.value) return;
        bukaPopup('popupKonfirmasiUbah');
    }

    function submitUbah() {
        tutupPopup('popupKonfirmasiUbah');
        tutupModalUbah();
        var form = document.getElementById('formUbah');
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(res) {
            res.ok ? bukaPopup('popupPembimbingBerhasil') : bukaPopup('popupPembimbingGagal');
        })
        .catch(function() { bukaPopup('popupPembimbingGagal'); });
    }
</script>

@endsection