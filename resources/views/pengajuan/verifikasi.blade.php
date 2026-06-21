@extends('layouts.app')

@section('content')

<form action="{{ route('pengajuan.proses', $pengajuan->id) }}" method="POST" id="formVerifikasi">
    @csrf

    <div class="wrapper-page">

        <!-- TOMBOL KEMBALI -->
        <a href="/pengajuan" class="btn-back-top">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>

        <!-- TITLE -->
        <h1 class="page-title">Tinjau dan Verifikasi Judul TA Mahasiswa</h1>
        <p class="page-subtitle">Verifikasi usulan judul tugas akhir mahasiswa untuk memastikan standar akademik.</p>

        <!-- INFO CARDS -->
        <div class="info-grid">
            <div class="info-card">
                <div class="info-icon"><i class="fa fa-calendar"></i></div>
                <div>
                    <div class="info-label">TANGGAL PENGAJUAN</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d M Y') }}</div>
                </div>
            </div>
            <div class="info-card">
                <div class="info-icon"><i class="fa fa-id-card"></i></div>
                <div>
                    <div class="info-label">NIM</div>
                    <div class="info-value">{{ $pengajuan->nim_nid }}</div>
                </div>
            </div>
            <div class="info-card">
                <div class="info-icon"><i class="fa fa-circle-dot"></i></div>
                <div>
                    <div class="info-label">STATUS KESELURUHAN</div>
                    @php $st = strtolower($pengajuan->status); @endphp
                    <div class="info-value status-{{ $st === 'menunggu verifikasi' ? 'menunggu' : ($st === 'disetujui' ? 'disetujui' : 'ditolak') }}">
                        {{ ucfirst($pengajuan->status) }}
                    </div>
                </div>
            </div>
            <div class="info-card">
                <div class="info-icon"><i class="fa fa-user"></i></div>
                <div>
                    <div class="info-label">MAHASISWA</div>
                    <div class="info-value">{{ $pengajuan->nama }}</div>
                </div>
            </div>
        </div>

        <!-- INFORMASI PENTING -->
        <div class="info-penting">
            <div class="info-penting-header">
                <span class="info-penting-icon"><i class="fa fa-circle-info"></i></span>
                <span class="info-penting-title">Informasi Penting</span>
            </div>
            <ul class="info-penting-list">
                <li>Koordinator hanya dapat menyetujui maksimal satu judul dari beberapa usulan yang diajukan oleh mahasiswa sebagai prioritas penelitian tugas akhir.</li>
                <li>Apabila seluruh usulan judul dinilai belum sesuai, koordinator berhak menolak seluruh pengajuan yang diberikan oleh mahasiswa.</li>
                <li>Keputusan verifikasi yang telah disimpan atau disubmit tidak dapat diubah kembali, sehingga proses peninjauan perlu dilakukan secara cermat sebelum menyimpan hasil verifikasi.</li>
                <li>Pastikan judul yang disetujui memiliki relevansi dengan topik penelitian, layak untuk diteliti, serta sesuai dengan ketentuan dan standar akademik program studi.</li>
            </ul>
        </div>

        <!-- DAFTAR USULAN JUDUL -->
        <div class="daftar-header">
            <i class="fa fa-file-lines" style="color:#f4b400;"></i>
            <span>Daftar Usulan Judul</span>
        </div>

        @php
        $sudahDiverifikasi = strtolower($pengajuan->status) !== 'menunggu verifikasi';
        $judulDisetujui    = $pengajuan->judul_disetujui;

        $juduls = [
            1 => [
                'judul'   => $pengajuan->judul_1,
                'topik'   => $pengajuan->topik_1   ?? '-',
                'mitra'   => $pengajuan->mitra_1   ?? '-',
                'catatan' => $pengajuan->catatan_1 ?? '',
            ],
            2 => [
                'judul'   => $pengajuan->judul_2,
                'topik'   => $pengajuan->topik_2   ?? '-',
                'mitra'   => $pengajuan->mitra_2   ?? '-',
                'catatan' => $pengajuan->catatan_2 ?? '',
            ],
            3 => [
                'judul'   => $pengajuan->judul_3,
                'topik'   => $pengajuan->topik_3   ?? '-',
                'mitra'   => $pengajuan->mitra_3   ?? '-',
                'catatan' => $pengajuan->catatan_3 ?? '',
            ],
        ];
        @endphp

        @foreach($juduls as $no => $j)
        @php
            $isDisetujui = $sudahDiverifikasi && $judulDisetujui === $j['judul'];
            $isDitolak   = $sudahDiverifikasi && $judulDisetujui !== $j['judul'];
            $badgeClass  = $isDisetujui ? 'badge-approved' : ($isDitolak ? 'badge-rejected' : 'badge-pending');
            $badgeLabel  = $isDisetujui ? 'Approved' : ($isDitolak ? 'Rejected' : 'Pending');
            $cardBorder  = $isDisetujui ? 'border-green' : ($isDitolak ? 'border-red' : 'border-yellow');
        @endphp
        <div class="judul-card {{ $cardBorder }}" id="card{{ $no }}">
            <div class="judul-card-top">
                <span class="usulan-badge {{ $badgeClass }}" id="badge{{ $no }}">Usulan {{ $no }} · {{ $badgeLabel }}</span>
                @if(!$sudahDiverifikasi)
                <i class="fa fa-rotate-right reset-icon" onclick="resetJudul({{ $no }})" title="Reset"></i>
                @endif
            </div>
            <div class="judul-main">{{ $j['judul'] }}</div>
            <div class="judul-meta">
                <span class="meta-item">
                    <i class="fa fa-circle" style="font-size:8px; color:#f4b400;"></i>
                    Topik: {{ $j['topik'] }}
                </span>
                <span class="meta-item">
                    <i class="fa fa-building" style="font-size:11px; color:#aaa;"></i>
                    Mitra: {{ $j['mitra'] }}
                </span>
            </div>

            @if(!$sudahDiverifikasi)
                <div class="catatan-wrap">
                    <label class="catatan-label" for="catatan_{{ $no }}">
                        <i class="fa fa-pen-to-square"></i>
                        Catatan <span class="catatan-opsional" id="catatanLabel{{ $no }}">(opsional)</span>
                    </label>
                    <textarea
                        class="catatan-input"
                        id="catatanInput{{ $no }}"
                        name="catatan_{{ $no }}"
                        placeholder="Tulis catatan atau alasan keputusan untuk judul ini..."
                        rows="2"
                        maxlength="500"></textarea>
                    <div class="catatan-counter"><span id="counter{{ $no }}">0</span>/500</div>
                </div>
            @else
                @if(!empty($j['catatan']))
                <div class="catatan-wrap catatan-readonly">
                    <div class="catatan-label">
                        <i class="fa fa-comment-dots"></i>
                        Catatan
                    </div>
                    <div class="catatan-isi">{{ $j['catatan'] }}</div>
                </div>
                @endif
            @endif

            <div class="judul-aksi" id="aksi{{ $no }}">
                @if($sudahDiverifikasi)
                    @if($isDisetujui)
                        <span class="btn-hasil btn-disetujui-hasil">✓ Disetujui</span>
                    @else
                        <span class="btn-hasil btn-ditolak-hasil">✕ Ditolak</span>
                    @endif
                @else
                    <button type="button" class="btn-setuju" onclick="setuju({{ $no }})">
                        <i class="fa fa-check"></i> Setujui
                    </button>
                    <button type="button" class="btn-tolak" onclick="tolak({{ $no }})">
                        <i class="fa fa-xmark"></i> Tolak
                    </button>
                @endif
            </div>
        </div>
        @endforeach

        <input type="hidden" name="judul_disetujui" id="judul_disetujui">

    </div>

    <!-- FOOTER STICKY -->
    <div class="footer-sticky">
        <a href="/pengajuan" class="btn-kembali">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
        @if(!$sudahDiverifikasi)
        <button type="button" class="btn-kirim" onclick="simpanData()">
            Kirim Verifikasi <i class="fa fa-arrow-right"></i>
        </button>
        @endif
    </div>

</form>

<!-- POPUP -->
<div class="popup-bg" id="popupBg">
    <div class="popup-box">
        <div class="popup-icon-wrap" id="popupIconWrap">
            <span id="popupIcon">✓</span>
        </div>
        <div class="popup-title" id="popupTitle">Berhasil!</div>
        <div class="popup-text" id="popupText">Judul berhasil dipilih</div>
        <button class="popup-btn" id="popupOkBtn" onclick="closePopup()">OK</button>
        <div id="confirmArea" style="display:none; margin-top:12px;">
            <button class="popup-btn" onclick="lanjutkanAksi()">Ya, Lanjutkan</button>
            <button class="popup-btn btn-batal" onclick="closePopup()">Batal</button>
        </div>
    </div>
</div>

<style>
    .main-content {
        padding: 0 !important;
    }

    body { background: #F5F5F5; }

    .wrapper-page {
        max-width: 100%;
        margin: 0;
        padding: 36px 24px 100px;
    }

    /* TOMBOL KEMBALI ATAS */
    .btn-back-top {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.88rem;
        font-weight: 600;
        color: #374151;
        text-decoration: none;
        margin-bottom: 20px;
        padding: 8px 16px;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        background: #fff;
        transition: 0.15s;
    }
    .btn-back-top:hover {
        border-color: #F4B400;
        color: #111;
        background: #FFFBEA;
    }

    /* TITLE */
    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111;
        margin-bottom: 4px;
    }
    .page-subtitle {
        font-size: 0.9rem;
        color: #888;
        margin-bottom: 28px;
    }

    /* INFO GRID */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }
    .info-card {
        background: #fff;
        border: 1px solid #E5E7EB;
        border-left: 4px solid #FFE083;
        border-radius: 10px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
        overflow: hidden;
    }
    .info-icon {
        width: 38px; height: 38px;
        background: #FFFBEA;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: #F4B400;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .info-label {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        color: #9CA3AF;
        margin-bottom: 4px;
    }
    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: #111;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .status-menunggu  { color: #D97706; }
    .status-disetujui { color: #16A34A; }
    .status-ditolak   { color: #DC2626; }

    /* INFORMASI PENTING */
    .info-penting {
        background: #FFFBEA;
        border: 1px solid #FDE68A;
        border-radius: 12px;
        padding: 18px 22px;
        margin-bottom: 28px;
    }
    .info-penting-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }
    .info-penting-icon  { color: #F4B400; font-size: 1.1rem; }
    .info-penting-title { font-size: 0.95rem; font-weight: 700; color: #92400E; }
    .info-penting-list {
        margin: 0; padding-left: 18px;
        display: flex; flex-direction: column; gap: 6px;
    }
    .info-penting-list li {
        font-size: 0.82rem;
        color: #78350F;
        line-height: 1.5;
    }

    /* DAFTAR HEADER */
    .daftar-header {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 1.05rem;
        font-weight: 700;
        color: #111;
        margin-bottom: 14px;
    }

    /* JUDUL CARD */
    .judul-card {
        background: #fff;
        border: 1px solid #E5E7EB;
        border-left: 4px solid #E5E7EB;
        border-radius: 10px;
        padding: 18px 20px;
        margin-bottom: 12px;
        transition: border-color 0.2s;
    }
    .judul-card.border-yellow { border-left-color: #F4B400; }
    .judul-card.border-green  { border-left-color: #22C55E; }
    .judul-card.border-red    { border-left-color: #EF4444; }

    .judul-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .usulan-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
    }
    .badge-pending  { background: #FEF9C3; color: #92400E; }
    .badge-approved { background: #DCFCE7; color: #15803D; }
    .badge-rejected { background: #FEE2E2; color: #B91C1C; }

    .reset-icon {
        cursor: pointer;
        color: #9CA3AF;
        font-size: 0.95rem;
        transition: color 0.15s;
    }
    .reset-icon:hover { color: #374151; }

    .judul-main {
        font-size: 1rem;
        font-weight: 700;
        color: #111;
        margin-bottom: 10px;
        line-height: 1.45;
    }

    .judul-meta {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 14px;
    }
    .meta-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.8rem;
        color: #6B7280;
    }

    /* CATATAN */
    .catatan-wrap {
        margin-bottom: 14px;
        margin-top: 2px;
    }
    .catatan-label {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.78rem;
        font-weight: 600;
        color: #6B7280;
        margin-bottom: 6px;
    }
    .catatan-opsional {
        font-weight: 400;
        color: #9CA3AF;
        font-size: 0.75rem;
    }
    /* EDIT: style wajib untuk label catatan */
    .catatan-wajib {
        font-weight: 600;
        color: #DC2626;
        font-size: 0.75rem;
    }
    .catatan-input {
        width: 100%;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 0.82rem;
        color: #374151;
        resize: none;
        background: #FAFAFA;
        transition: border-color 0.15s, background 0.15s;
        font-family: inherit;
        line-height: 1.5;
    }
    .catatan-input:focus {
        outline: none;
        border-color: #F4B400;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(244,180,0,0.08);
    }
    /* EDIT: border merah saat error */
    .catatan-input.input-error {
        border-color: #EF4444;
        background: #FFF5F5;
    }
    .catatan-input.input-error:focus {
        border-color: #EF4444;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.08);
    }
    .catatan-input::placeholder { color: #BFC7D2; }
    .catatan-counter {
        text-align: right;
        font-size: 0.72rem;
        color: #9CA3AF;
        margin-top: 4px;
    }

    /* Readonly catatan */
    .catatan-readonly .catatan-label { color: #6B7280; }
    .catatan-isi {
        background: #F9FAFB;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 0.82rem;
        color: #374151;
        line-height: 1.6;
        white-space: pre-wrap;
    }

    .judul-aksi {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    /* ACTION BUTTONS */
    .btn-setuju {
        display: inline-flex; align-items: center; gap: 5px;
        background: #DCFCE7; color: #15803D;
        border: 1px solid #86EFAC;
        border-radius: 8px; padding: 7px 16px;
        font-size: 0.82rem; font-weight: 600;
        cursor: pointer; transition: all 0.15s;
    }
    .btn-setuju:hover    { background: #BBF7D0; }
    .btn-setuju:disabled { opacity: 0.7; cursor: default; }

    .btn-tolak {
        display: inline-flex; align-items: center; gap: 5px;
        background: #FEE2E2; color: #B91C1C;
        border: 1px solid #FCA5A5;
        border-radius: 8px; padding: 7px 16px;
        font-size: 0.82rem; font-weight: 600;
        cursor: pointer; transition: all 0.15s;
    }
    .btn-tolak:hover    { background: #FECACA; }
    .btn-tolak:disabled { opacity: 0.7; cursor: default; }

    .btn-hasil { display: inline-flex; align-items: center; gap: 5px; border-radius: 8px; padding: 7px 16px; font-size: 0.82rem; font-weight: 600; }
    .btn-disetujui-hasil { background: #DCFCE7; color: #15803D; }
    .btn-ditolak-hasil   { background: #FEE2E2; color: #B91C1C; }

    /* FOOTER STICKY */
    .footer-sticky {
        position: fixed;
        bottom: 0; left: 0; right: 0;
        background: #fff;
        border-top: 1px solid #E5E7EB;
        padding: 14px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 100;
    }
    .btn-kembali {
        display: inline-flex; align-items: center; gap: 6px;
        background: none; color: #374151;
        border: none; font-size: 0.88rem;
        font-weight: 500; text-decoration: none;
        cursor: pointer;
    }
    .btn-kembali:hover { color: #111; }
    .btn-kirim {
        display: inline-flex; align-items: center; gap: 8px;
        background: #F4B400; color: #fff;
        border: none;
        border-radius: 10px; padding: 10px 24px;
        font-size: 0.9rem; font-weight: 700;
        cursor: pointer; transition: background 0.15s;
    }
    .btn-kirim:hover { background: #D97706; }

    /* POPUP */
    .popup-bg {
        position: fixed; top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.35);
        display: none;
        justify-content: center; align-items: center;
        z-index: 9999;
    }
    .popup-box {
        width: 340px; background: #fff;
        border-radius: 16px; padding: 32px 28px;
        text-align: center;
    }
    .popup-icon-wrap {
        width: 64px; height: 64px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem;
        margin: 0 auto 16px;
    }
    .popup-icon-wrap.success { background: #DCFCE7; color: #15803D; }
    .popup-icon-wrap.error   { background: #FEE2E2; color: #B91C1C; }
    .popup-icon-wrap.confirm { background: #FEF9C3; color: #92400E; }
    .popup-title { font-size: 1.15rem; font-weight: 700; margin-bottom: 6px; }
    .popup-text  { font-size: 0.85rem; color: #6B7280; margin-bottom: 20px; }
    .popup-btn {
        background: #F4B400; border: none; color: #fff;
        font-size: 0.88rem; font-weight: 600;
        padding: 10px 24px; border-radius: 8px;
        cursor: pointer; transition: background 0.15s;
    }
    .popup-btn:hover { background: #D97706; }
    .btn-batal {
        background: #F3F4F6; color: #374151;
        margin-left: 8px;
    }
    .btn-batal:hover { background: #E5E7EB; }

    @media (max-width: 600px) {
        .info-grid      { grid-template-columns: 1fr; }
        .wrapper-page   { padding: 20px 14px 100px; }
        .footer-sticky  { padding: 12px 16px; }
    }
</style>

<script>
    let aksiDipilih  = null;
    let nomorDipilih = null;
    let statusJudul  = { 1: '', 2: '', 3: '' };

    // ── Counter karakter catatan ──
    [1, 2, 3].forEach(function (no) {
        const el = document.getElementById('catatanInput' + no);
        if (!el) return;
        el.addEventListener('input', function () {
            document.getElementById('counter' + no).textContent = this.value.length;
            // EDIT: hapus error highlight saat user mulai ngetik
            this.classList.remove('input-error');
        });
    });

    function showPopup(type, text) {
        const wrap  = document.getElementById('popupIconWrap');
        const icon  = document.getElementById('popupIcon');
        const title = document.getElementById('popupTitle');
        document.getElementById('popupOkBtn').style.display    = 'inline-block';
        document.getElementById('confirmArea').style.display   = 'none';
        if (type === 'success') {
            wrap.className  = 'popup-icon-wrap success';
            icon.innerHTML  = '✓';
            title.innerHTML = 'Berhasil!';
        } else {
            wrap.className  = 'popup-icon-wrap error';
            icon.innerHTML  = '✕';
            title.innerHTML = 'Gagal!';
        }
        document.getElementById('popupText').innerHTML = text;
        document.getElementById('popupBg').style.display = 'flex';
    }

    function showConfirm(jenis, no) {
        aksiDipilih  = jenis;
        nomorDipilih = no;
        const wrap  = document.getElementById('popupIconWrap');
        const icon  = document.getElementById('popupIcon');
        wrap.className  = 'popup-icon-wrap confirm';
        icon.innerHTML  = '?';
        document.getElementById('popupTitle').innerHTML = 'Konfirmasi';
        document.getElementById('popupText').innerHTML  = jenis === 'setuju'
            ? 'Apakah anda yakin menyetujui judul ini?'
            : 'Apakah anda yakin menolak judul ini?';
        document.getElementById('popupOkBtn').style.display  = 'none';
        document.getElementById('confirmArea').style.display = 'block';
        document.getElementById('popupBg').style.display     = 'flex';
    }

    function closePopup() {
        document.getElementById('popupBg').style.display = 'none';
    }

    function setuju(no) { showConfirm('setuju', no); }
    function tolak(no)  { showConfirm('tolak',  no); }

    function lanjutkanAksi() {
        const no    = nomorDipilih;
        const card  = document.getElementById('card'  + no);
        const badge = document.getElementById('badge' + no);

        if (aksiDipilih === 'setuju') {
            statusJudul[no] = 'setuju';
            card.className  = card.className.replace(/border-\w+/, 'border-green');
            badge.className = 'usulan-badge badge-approved';
            badge.innerHTML = 'Usulan ' + no + ' · Approved';
            document.getElementById('aksi' + no).innerHTML =
                '<button type="button" class="btn-setuju" disabled><i class="fa fa-check"></i> Disetujui</button>';

            // EDIT: kembaliin label catatan jadi opsional kalau sebelumnya pernah ditolak
            const labelEl = document.getElementById('catatanLabel' + no);
            if (labelEl) {
                labelEl.textContent = '(opsional)';
                labelEl.className   = 'catatan-opsional';
            }
            // EDIT: hapus error highlight juga
            const catatanEl = document.getElementById('catatanInput' + no);
            if (catatanEl) catatanEl.classList.remove('input-error');

            showPopup('success', 'Judul berhasil dipilih.');
        } else {
            statusJudul[no] = 'tolak';
            card.className  = card.className.replace(/border-\w+/, 'border-red');
            badge.className = 'usulan-badge badge-rejected';
            badge.innerHTML = 'Usulan ' + no + ' · Rejected';
            document.getElementById('aksi' + no).innerHTML =
                '<button type="button" class="btn-tolak" disabled><i class="fa fa-xmark"></i> Ditolak</button>';

            // EDIT: ubah label catatan jadi wajib
            const labelEl = document.getElementById('catatanLabel' + no);
            if (labelEl) {
                labelEl.textContent = '(wajib)';
                labelEl.className   = 'catatan-wajib';
            }

            showPopup('success', 'Judul berhasil ditolak.');
        }
    }

    function resetJudul(no) {
        statusJudul[no] = '';
        const card  = document.getElementById('card'  + no);
        const badge = document.getElementById('badge' + no);
        card.className  = card.className.replace(/border-\w+/, 'border-yellow');
        badge.className = 'usulan-badge badge-pending';
        badge.innerHTML = 'Usulan ' + no + ' · Pending';
        document.getElementById('aksi' + no).innerHTML =
            '<button type="button" class="btn-setuju" onclick="setuju(' + no + ')"><i class="fa fa-check"></i> Setujui</button>' +
            '<button type="button" class="btn-tolak"  onclick="tolak('  + no + ')"><i class="fa fa-xmark"></i> Tolak</button>';

        // EDIT: kembaliin label catatan jadi opsional saat reset
        const labelEl = document.getElementById('catatanLabel' + no);
        if (labelEl) {
            labelEl.textContent = '(opsional)';
            labelEl.className   = 'catatan-opsional';
        }
        // EDIT: hapus error highlight juga
        const catatanEl = document.getElementById('catatanInput' + no);
        if (catatanEl) catatanEl.classList.remove('input-error');

        const counterEl = document.getElementById('counter' + no);
        if (counterEl && catatanEl) {
            counterEl.textContent = catatanEl.value.length;
        }
    }

    function simpanData() {
        let jumlahSetuju  = 0;
        let masihKosong   = false;
        let judulDipilih  = '';
        let catatanKosong = false;  // EDIT

        for (let i = 1; i <= 3; i++) {
            if (statusJudul[i] === '')       { masihKosong = true; }
            if (statusJudul[i] === 'setuju') { jumlahSetuju++; judulDipilih = i; }

            // EDIT: kalau ditolak, cek catatan wajib diisi
            if (statusJudul[i] === 'tolak') {
                const catatanEl = document.getElementById('catatanInput' + i);
                if (catatanEl && catatanEl.value.trim() === '') {
                    catatanKosong = true;
                    catatanEl.classList.add('input-error');  // highlight merah
                }
            }
        }

        if (masihKosong)      { showPopup('error', 'Semua judul harus diberi keputusan.'); return; }
        if (jumlahSetuju > 1) { showPopup('error', 'Hanya boleh 1 judul yang disetujui.'); return; }
        if (catatanKosong)    { showPopup('error', 'Catatan wajib diisi untuk semua judul yang ditolak.'); return; }  // EDIT

        document.getElementById('judul_disetujui').value = judulDipilih;
        document.getElementById('formVerifikasi').submit();
    }
</script>

@endsection