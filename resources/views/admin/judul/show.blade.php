@extends('layouts.app')

@section('content')
<style>
    :root {
        --gold: #C9A227;
        --gold-light: #FEF9EC;
        --gold-border: #F5D97A;
        --neutral: #1E293B;
        --muted: #6B7280;
        --border: #E5E7EB;
        --white: #ffffff;
    }

    .detail-wrap {
        max-width: 960px;
        margin: 0 auto;
        padding: 28px 20px 60px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: #B86A00;
        font-weight: 600;
        font-size: .9rem;
        margin-bottom: 20px;
    }

    .back-link:hover {
        color: var(--gold);
    }

    .page-title {
        font-size: 1.7rem;
        font-weight: 800;
        color: var(--neutral);
        margin-bottom: 4px;
    }

    .page-sub {
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 28px;
    }

    .alert-sukses {
        background: #F0FDF4;
        border: 1px solid #BBF7D0;
        color: #15803D;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 28px;
    }

    .info-box {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        position: relative;
        overflow: hidden;
    }

    .info-box::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--gold-border);
        border-radius: 4px 0 0 4px;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        background: var(--gold-light);
        border: 1.5px solid var(--gold-border);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .info-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 15px;
        font-weight: 700;
        color: var(--neutral);
    }

    .status-inline {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 99px;
        font-size: 12px;
        font-weight: 600;
    }

    .s-menunggu {
        background: #FFFBEB;
        color: #B45309;
        border: 1px solid #FDE68A;
    }

    .s-disetujui {
        background: #F0FDF4;
        color: #16A34A;
        border: 1px solid #BBF7D0;
    }

    .s-ditolak {
        background: #FFF1F2;
        color: #E11D48;
        border: 1px solid #FECDD3;
    }

    .judul-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 22px 24px;
        margin-bottom: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        transition: box-shadow .2s;
        position: relative;
        overflow: hidden;
    }

    .judul-card:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, .07);
    }

    .judul-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        border-radius: 4px 0 0 4px;
        background: var(--gold-border);
    }

    .judul-card.c-disetujui::before {
        background: #86EFAC;
    }

    .judul-card.c-ditolak::before {
        background: #FCA5A5;
    }

    .judul-left {
        flex: 1;
    }

    .judul-no {
        font-size: 12px;
        font-weight: 700;
        color: var(--muted);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .judul-main {
        font-size: 17px;
        font-weight: 700;
        color: var(--gold);
        margin-bottom: 14px;
        line-height: 1.5;
    }

    .judul-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 20px;
    }

    .meta-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--muted);
        margin-bottom: 3px;
    }

    .meta-value {
        font-size: 13px;
        font-weight: 600;
        color: var(--neutral);
    }

    .judul-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
        flex-shrink: 0;
        min-width: 140px;
    }

    .btn-setujui {
        padding: 9px 20px;
        border-radius: 10px;
        border: none;
        background: #DCFCE7;
        color: #15803D;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        width: 100%;
        text-align: center;
        transition: background .15s;
    }

    .btn-setujui:hover {
        background: #bbf7d0;
    }

    .btn-tolak-aksi {
        padding: 9px 20px;
        border-radius: 10px;
        border: none;
        background: #FEE2E2;
        color: #DC2626;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        width: 100%;
        text-align: center;
        transition: background .15s;
    }

    .btn-tolak-aksi:hover {
        background: #fecaca;
    }

    .btn-reset-judul {
        background: none;
        border: none;
        color: var(--muted);
        font-size: 12px;
        cursor: pointer;
        text-decoration: underline;
        padding: 0;
    }

    .btn-reset-judul:hover {
        color: var(--neutral);
    }

    .badge-hasil {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 8px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        width: 100%;
        justify-content: center;
    }

    .badge-disetujui-hasil {
        background: #DCFCE7;
        color: #15803D;
    }

    .badge-ditolak-hasil {
        background: #FEE2E2;
        color: #DC2626;
    }

    .footer-row {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 28px;
    }

    .btn-footer-kirim {
        padding: 11px 32px;
        border-radius: 12px;
        border: none;
        background: var(--gold);
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-footer-kirim:hover {
        background: #b08a1e;
    }

    .btn-footer-batal {
        padding: 11px 20px;
        border-radius: 12px;
        border: 1.5px solid var(--border);
        background: #fff;
        color: #374151;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-footer-batal:hover {
        background: #F9FAFB;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .45);
        z-index: 99999;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.open {
        display: flex;
    }

    .modal-box {
        background: #fff;
        border-radius: 18px;
        padding: 32px 28px 24px;
        max-width: 420px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .18);
        text-align: center;
        animation: modal-in .18s ease;
    }

    @keyframes modal-in {
        from {
            transform: scale(.94);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .modal-icon {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        margin: 0 auto 16px;
    }

    .modal-icon.warn {
        background: #FEF9EC;
        color: #C9A227;
    }

    .modal-icon.danger {
        background: #FEF2F2;
        color: #DC2626;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 8px;
    }

    .modal-desc {
        font-size: 13px;
        color: #6B7280;
        line-height: 1.6;
        margin-bottom: 24px;
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .modal-btn {
        padding: 10px 24px;
        border-radius: 10px;
        border: none;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .modal-btn-cancel {
        background: #F3F4F6;
        color: #374151;
    }

    .modal-btn-ok {
        background: var(--gold);
        color: #fff;
    }

    .modal-btn-danger {
        background: #DC2626;
        color: #fff;
    }

    @media (max-width: 640px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .judul-card {
            flex-direction: column;
        }

        .judul-right {
            width: 100%;
        }

        .judul-meta {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="detail-wrap">
    <a href="{{ route('admin.judul.index') }}" class="back-link">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        Kembali
    </a>

    @if(session('success'))
    <div class="alert-sukses">✓ {{ session('success') }}</div>
    @endif

    <div class="page-title">Verifikasi Pengajuan Judul TA</div>
    <div class="page-sub">Tinjau dan berikan keputusan untuk setiap usulan judul mahasiswa.</div>

    <div class="info-grid">
        <div class="info-box">
            <div class="info-icon"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="#C9A227" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg></div>
            <div>
                <div class="info-label">Nama Mahasiswa</div>
                <div class="info-value">{{ $pengajuan->nama_mahasiswa }}</div>
            </div>
        </div>
        <div class="info-box">
            <div class="info-icon"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="#C9A227" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                </svg></div>
            <div>
                <div class="info-label">NIM</div>
                <div class="info-value">{{ $pengajuan->nim_nid }}</div>
            </div>
        </div>
        <div class="info-box">
            <div class="info-icon"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="#C9A227" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg></div>
            <div>
                <div class="info-label">Status</div>
                @php $st = strtolower($pengajuan->status); @endphp
                @if($st === 'disetujui') <span class="status-inline s-disetujui">Disetujui</span>
                @elseif($st === 'ditolak') <span class="status-inline s-ditolak">Ditolak</span>
                @else <span class="status-inline s-menunggu">Menunggu Verifikasi</span>
                @endif
            </div>
        </div>
    </div>

    @php
    $sudahDiverifikasi = !in_array($st, ['menunggu', 'menunggu verifikasi']);
    $judulDisetujui = $pengajuan->judul_disetujui ?? null;
    $judulList = [
    ['nomor' => 1, 'judul' => $pengajuan->judul_1, 'topik' => $pengajuan->topik_1 ?? null, 'mitra' => $pengajuan->mitra_1 ?? null],
    ['nomor' => 2, 'judul' => $pengajuan->judul_2, 'topik' => $pengajuan->topik_2 ?? null, 'mitra' => $pengajuan->mitra_2 ?? null],
    ['nomor' => 3, 'judul' => $pengajuan->judul_3, 'topik' => $pengajuan->topik_3 ?? null, 'mitra' => $pengajuan->mitra_3 ?? null],
    ];
    @endphp

    <form action="{{ route('admin.judul.proses', $pengajuan->id) }}" method="POST" id="formVerifikasi">
        @csrf
        <input type="hidden" name="aksi" id="inputAksi">
        <input type="hidden" name="judul_disetujui" id="inputJudulDisetujui">

        @foreach($judulList as $item)
        @if($item['judul'])
        @php
        if ($sudahDiverifikasi) {
        $statusCard = ($st === 'ditolak') ? 'ditolak' : (($item['judul'] === $judulDisetujui) ? 'disetujui' : 'ditolak');
        } else { $statusCard = 'menunggu'; }
        @endphp
        <div class="judul-card c-{{ $statusCard }}" id="card{{ $item['nomor'] }}">
            <div class="judul-left">
                <div class="judul-no">Usulan Judul {{ $item['nomor'] }}</div>
                <div class="judul-main">{{ $item['judul'] }}</div>
                <div class="judul-meta">
                    <div>
                        <div class="meta-label">Topik Penelitian</div>
                        <div class="meta-value">{{ $item['topik'] ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="meta-label">Mitra Penelitian</div>
                        <div class="meta-value">{{ $item['mitra'] ?? '-' }}</div>
                    </div>
                </div>
            </div>
            <div class="judul-right" id="aksi{{ $item['nomor'] }}">
                @if($sudahDiverifikasi)
                @if($statusCard === 'disetujui') <span class="badge-hasil badge-disetujui-hasil">✓ Disetujui</span>
                @else <span class="badge-hasil badge-ditolak-hasil">✕ Ditolak</span>
                @endif
                @else
                <button type="button" class="btn-setujui" onclick="konfirmasiSetujui({{ $item['nomor'] }}, '{{ addslashes($item['judul']) }}')">✓ Setujui</button>
                <button type="button" class="btn-tolak-aksi" onclick="konfirmasiTolak({{ $item['nomor'] }})">✕ Tolak</button>
                @endif
            </div>
        </div>
        @endif
        @endforeach

        <div class="footer-row">
            @if(!$sudahDiverifikasi)
            <button type="button" class="btn-footer-kirim" onclick="simpanData()">Kirim Keputusan</button>
            @endif
        </div>
    </form>
</div>

<div class="modal-overlay" id="modalSetujui">
    <div class="modal-box">
        <div class="modal-icon warn"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="28" height="28">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg></div>
        <div class="modal-title">Setujui Judul Ini?</div>
        <div class="modal-desc" id="modalSetujuiDesc">Judul ini akan disetujui.</div>
        <div class="modal-actions">
            <button class="modal-btn modal-btn-cancel" onclick="tutupModal('modalSetujui')">Batal</button>
            <button class="modal-btn modal-btn-ok" onclick="konfirmasiSetujuiOk()">Ya, Setujui</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="modalTolak">
    <div class="modal-box">
        <div class="modal-icon danger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="28" height="28">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg></div>
        <div class="modal-title">Tolak Judul Ini?</div>
        <div class="modal-desc">Judul ini akan ditolak.</div>
        <div class="modal-actions">
            <button class="modal-btn modal-btn-cancel" onclick="tutupModal('modalTolak')">Batal</button>
            <button class="modal-btn modal-btn-danger" onclick="konfirmasiTolakOk()">Ya, Tolak</button>
        </div>
    </div>
</div>

<script>
    let statusJudul = {
        1: '',
        2: '',
        3: ''
    };
    let nomorAktif = null,
        judulAktif = '';
    const judulMap = {
        @foreach($judulList as $item)
        @if(!empty($item['judul'])) {
            {
                $item['nomor']
            }
        }: '{{ addslashes($item['judul']) }}',
        @endif
        @endforeach
    };

    function konfirmasiSetujui(no, judul) {
        nomorAktif = no;
        judulAktif = judul;
        document.getElementById('modalSetujuiDesc').innerHTML = 'Judul <strong>"' + judul.substring(0, 60) + (judul.length > 60 ? '..."' : '"') + '</strong> akan disetujui.';
        document.getElementById('modalSetujui').classList.add('open');
    }

    function konfirmasiSetujuiOk() {
        tutupModal('modalSetujui');
        statusJudul[nomorAktif] = 'setuju';
        updateTampilan(nomorAktif);
    }

    function konfirmasiTolak(no) {
        nomorAktif = no;
        document.getElementById('modalTolak').classList.add('open');
    }

    function konfirmasiTolakOk() {
        tutupModal('modalTolak');
        statusJudul[nomorAktif] = 'tolak';
        updateTampilan(nomorAktif);
    }

    function updateTampilan(no) {
        const aksi = document.getElementById('aksi' + no);
        const card = document.getElementById('card' + no);
        if (statusJudul[no] === 'setuju') {
            card.className = 'judul-card c-disetujui';
            aksi.innerHTML = '<span class="badge-hasil badge-disetujui-hasil">✓ Disetujui</span><button type="button" class="btn-reset-judul" onclick="resetJudul(' + no + ')">Ubah</button>';
        } else {
            card.className = 'judul-card c-ditolak';
            aksi.innerHTML = '<span class="badge-hasil badge-ditolak-hasil">✕ Ditolak</span><button type="button" class="btn-reset-judul" onclick="resetJudul(' + no + ')">Ubah</button>';
        }
    }

    function resetJudul(no) {
        statusJudul[no] = '';
        document.getElementById('card' + no).className = 'judul-card';
        const j = (judulMap[no] || '').replace(/'/g, "\\'");
        document.getElementById('aksi' + no).innerHTML =
            '<button type="button" class="btn-setujui" onclick="konfirmasiSetujui(' + no + ',\'' + j + '\')">✓ Setujui</button>' +
            '<button type="button" class="btn-tolak-aksi" onclick="konfirmasiTolak(' + no + ')">✕ Tolak</button>';
    }

    function tutupModal(id) {
        document.getElementById(id).classList.remove('open');
    }

    function simpanData() {
        const keys = Object.keys(judulMap).map(Number);
        let jumlahSetuju = 0,
            judulDipilih = '',
            masihKosong = false;
        keys.forEach(i => {
            if (!statusJudul[i]) masihKosong = true;
            if (statusJudul[i] === 'setuju') {
                jumlahSetuju++;
                judulDipilih = judulMap[i];
            }
        });
        if (masihKosong) {
            alert('Semua judul harus diberi keputusan.');
            return;
        }
        if (jumlahSetuju > 1) {
            alert('Hanya boleh 1 judul yang disetujui.');
            return;
        }
        document.getElementById('inputAksi').value = jumlahSetuju === 1 ? 'setujui' : 'tolak';
        document.getElementById('inputJudulDisetujui').value = judulDipilih;
        document.getElementById('formVerifikasi').submit();
    }
    document.querySelectorAll('.modal-overlay').forEach(o => o.addEventListener('click', e => {
        if (e.target === o) o.classList.remove('open');
    }));
</script>
@endsection