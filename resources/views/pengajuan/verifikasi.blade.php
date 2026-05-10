@extends('layouts.app')

@section('content')

<form action="{{ route('pengajuan.proses', $pengajuan->id) }}" method="POST" id="formVerifikasi">
@csrf

<div class="wrapper-page">

    <!-- TITLE -->
    <h1 class="page-title">
        Tinjau dan Verifikasi Judul TA-1 Mahasiswa
    </h1>

    <p class="page-subtitle">
        Informasi detail pengajuan judul tugas akhir yang telah diajukan.
    </p>

    <!-- IDENTITAS -->
    <div class="top-grid">

        <div class="mini-box">
            <div class="mini-label">NIM</div>
            <div class="mini-value">{{ $pengajuan->nim_nid }}</div>
        </div>

        <div class="mini-box">
            <div class="mini-label">Nama</div>
            <div class="mini-value">{{ $pengajuan->nama }}</div>
        </div>

        <div class="mini-box">
            <div class="mini-label">
                <i class="fa fa-calendar me-1"></i>
                Tanggal Pengajuan
            </div>
            <div class="mini-value">
                {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d M Y') }}
            </div>
        </div>

    </div>

    <!-- USULAN -->
    <div class="usulan-box">
        <div class="usulan-title">Usulan Judul</div>
        <div class="usulan-sub">
            Berikut adalah 3 usulan judul yang diajukan
        </div>
    </div>

    {{-- Tentukan status sekali aja biar ga berulang --}}
    @php
        $sudahDiverifikasi = strtolower($pengajuan->status) !== 'menunggu verifikasi';
        $judulDisetujui    = $pengajuan->judul_disetujui;
    @endphp

    <!-- JUDUL 1 -->
    <div class="judul-card">
        <div class="judul-left">
            <div class="judul-no">Judul 1</div>
            <div class="judul-main">{{ $pengajuan->judul_1 }}</div>
            <div class="row-info">
                <span class="left">Topik Penelitian</span>
                <span>{{ $pengajuan->topik_1 ?? '-' }}</span>
            </div>
            <div class="row-info">
                <span class="left">Mitra Penelitian</span>
                <span>{{ $pengajuan->mitra_penelitian ?? '-' }}</span>
            </div>
        </div>
        <div class="judul-right" id="aksi1">
            @if($sudahDiverifikasi)
                @if($judulDisetujui === $pengajuan->judul_1)
                    <span class="badge-hasil badge-disetujui-hasil">✓ Disetujui</span>
                @else
                    <span class="badge-hasil badge-ditolak-hasil">✕ Ditolak</span>
                @endif
            @else
                <button type="button" class="btn-setuju" onclick="setuju(1)">Setujui</button>
                <button type="button" class="btn-tolak" onclick="tolak(1)">Tolak</button>
                <i class="fa fa-rotate-right text-secondary reset-icon" onclick="resetJudul(1)"></i>
            @endif
        </div>
    </div>

    <!-- JUDUL 2 -->
    <div class="judul-card">
        <div class="judul-left">
            <div class="judul-no">Judul 2</div>
            <div class="judul-main">{{ $pengajuan->judul_2 }}</div>
            <div class="row-info">
                <span class="left">Topik Penelitian</span>
                <span>{{ $pengajuan->topik_2 ?? '-' }}</span>
            </div>
            <div class="row-info">
                <span class="left">Mitra Penelitian</span>
                <span>{{ $pengajuan->mitra_penelitian ?? '-' }}</span>
            </div>
        </div>
        <div class="judul-right" id="aksi2">
            @if($sudahDiverifikasi)
                @if($judulDisetujui === $pengajuan->judul_2)
                    <span class="badge-hasil badge-disetujui-hasil">✓ Disetujui</span>
                @else
                    <span class="badge-hasil badge-ditolak-hasil">✕ Ditolak</span>
                @endif
            @else
                <button type="button" class="btn-setuju" onclick="setuju(2)">Setujui</button>
                <button type="button" class="btn-tolak" onclick="tolak(2)">Tolak</button>
                <i class="fa fa-rotate-right text-secondary reset-icon" onclick="resetJudul(2)"></i>
            @endif
        </div>
    </div>

    <!-- JUDUL 3 -->
    <div class="judul-card">
        <div class="judul-left">
            <div class="judul-no">Judul 3</div>
            <div class="judul-main">{{ $pengajuan->judul_3 }}</div>
            <div class="row-info">
                <span class="left">Topik Penelitian</span>
                <span>{{ $pengajuan->topik_3 ?? '-' }}</span>
            </div>
            <div class="row-info">
                <span class="left">Mitra Penelitian</span>
                <span>{{ $pengajuan->mitra_penelitian ?? '-' }}</span>
            </div>
        </div>
        <div class="judul-right" id="aksi3">
            @if($sudahDiverifikasi)
                @if($judulDisetujui === $pengajuan->judul_3)
                    <span class="badge-hasil badge-disetujui-hasil">✓ Disetujui</span>
                @else
                    <span class="badge-hasil badge-ditolak-hasil">✕ Ditolak</span>
                @endif
            @else
                <button type="button" class="btn-setuju" onclick="setuju(3)">Setujui</button>
                <button type="button" class="btn-tolak" onclick="tolak(3)">Tolak</button>
                <i class="fa fa-rotate-right text-secondary reset-icon" onclick="resetJudul(3)"></i>
            @endif
        </div>
    </div>

    <input type="hidden" name="judul_disetujui" id="judul_disetujui">

    <!-- BUTTON -->
    <div class="footer-btn">
        <a href="/pengajuan" class="btn-kembali">Kembali</a>
        {{-- Tombol Kirim hanya muncul kalau belum diverifikasi --}}
        @if(!$sudahDiverifikasi)
            <button type="button" class="btn-kirim" onclick="simpanData()">Kirim</button>
        @endif
    </div>

</div>

</form>

<!-- POPUP -->
<div class="popup-bg" id="popupBg">
    <div class="popup-box">

        <div class="popup-icon" id="popupIcon">✓</div>
        <div class="popup-title" id="popupTitle">Berhasil!</div>
        <div class="popup-text" id="popupText">Judul berhasil di setujui</div>

        <button class="popup-btn" id="popupOkBtn" onclick="closePopup()">OK</button>

        <div id="confirmArea" style="display:none; margin-top:20px;">
            <button class="popup-btn" onclick="lanjutkanAksi()">Ya</button>
            <button class="popup-btn" style="background:#999; margin-left:10px;" onclick="closePopup()">
                Batal
            </button>
        </div>

    </div>
</div>

<script>

let jumlahSetuju = 0;
let aksiDipilih = null;
let nomorDipilih = null;

let statusJudul = {
    1:'',
    2:'',
    3:''
};

function showPopup(type,text){
    let icon = document.getElementById('popupIcon');
    let title = document.getElementById('popupTitle');

    document.getElementById('popupOkBtn').style.display = 'inline-block';
    document.getElementById('confirmArea').style.display = 'none';

    if(type == 'success'){
        icon.innerHTML = '✓';
        icon.className = 'popup-icon success';
        title.innerHTML = 'Berhasil!';
    }else{
        icon.innerHTML = '✕';
        icon.className = 'popup-icon error';
        title.innerHTML = 'Gagal!';
    }

    document.getElementById('popupText').innerHTML = text;
    document.getElementById('popupBg').style.display = 'flex';
}

function showConfirm(jenis,no){
    aksiDipilih = jenis;
    nomorDipilih = no;

    let icon = document.getElementById('popupIcon');
    let title = document.getElementById('popupTitle');

    icon.innerHTML = '?';
    icon.className = 'popup-icon success';
    title.innerHTML = 'Konfirmasi';

    if(jenis == 'setuju'){
        document.getElementById('popupText').innerHTML = 'Apakah anda yakin menyetujui judul ini?';
    }else{
        document.getElementById('popupText').innerHTML = 'Apakah anda yakin menolak judul ini?';
    }

    document.getElementById('popupOkBtn').style.display = 'none';
    document.getElementById('confirmArea').style.display = 'block';
    document.getElementById('popupBg').style.display = 'flex';
}

function closePopup(){
    document.getElementById('popupBg').style.display = 'none';
}

function setuju(no){
    showConfirm('setuju', no);
}

function tolak(no){
    showConfirm('tolak', no);
}

function lanjutkanAksi(){
    let no = nomorDipilih;

    if(aksiDipilih == 'setuju'){
        if(statusJudul[no] != 'setuju'){
            jumlahSetuju++;
        }
        statusJudul[no] = 'setuju';

        document.getElementById("aksi"+no).innerHTML =
            '<button class="btn-setuju">Setujui</button>' +
            '<i class="fa fa-rotate-right text-secondary reset-icon" onclick="resetJudul('+no+')"></i>';

        showPopup('success','Judul berhasil di setujui');
    }

    if(aksiDipilih == 'tolak'){
        if(statusJudul[no] == 'setuju'){
            jumlahSetuju--;
        }
        statusJudul[no] = 'tolak';

        document.getElementById("aksi"+no).innerHTML =
            '<button class="btn-tolak">Tolak</button>' +
            '<i class="fa fa-rotate-right text-secondary reset-icon" onclick="resetJudul('+no+')"></i>';

        showPopup('success','Judul berhasil di tolak');
    }
}

function resetJudul(no){
    if(statusJudul[no] == 'setuju'){
        jumlahSetuju--;
    }
    statusJudul[no] = '';

    document.getElementById("aksi"+no).innerHTML =
        '<button type="button" class="btn-setuju" onclick="setuju('+no+')">Setujui</button>' +
        '<button type="button" class="btn-tolak" onclick="tolak('+no+')">Tolak</button>' +
        '<i class="fa fa-rotate-right text-secondary reset-icon" onclick="resetJudul('+no+')"></i>';
}

function simpanData(){
    if(jumlahSetuju > 1){
        showPopup('error','Judul disetujui hanya boleh 1');
        return;
    }

    for(let i=1; i<=3; i++){
        if(statusJudul[i] == 'setuju'){
            document.getElementById('judul_disetujui').value = i;
        }
    }

    document.getElementById('formVerifikasi').submit();
}

</script>

<style>
body{
    background:#efefef;
}

.wrapper-page{
    width:100%;
    max-width:1400px;
    margin:auto;
    padding:35px 45px;
}

.page-title{
    font-size:42px;
    font-weight:700;
    margin-bottom:5px;
}

.page-subtitle{
    font-size:18px;
    color:#777;
    margin-bottom:28px;
}

.top-grid{
    display:grid;
    grid-template-columns:repeat(3, 1fr);
    gap:10px;
    margin-bottom:18px;
}

.mini-box{
    background:white;
    border:1px solid #d8d8d8;
    padding:18px;
    min-height:95px;
}

.mini-label{
    font-size:16px;
    font-weight:600;
    margin-bottom:8px;
}

.mini-value{
    font-size:18px;
}

.usulan-box{
    background:white;
    border:1px solid #d8d8d8;
    padding:16px;
    margin-bottom:14px;
}

.usulan-title{
    font-size:18px;
    font-weight:700;
}

.usulan-sub{
    font-size:15px;
    color:#777;
}

.judul-card{
    background:white;
    border:1px solid #d8d8d8;
    padding:18px;
    margin-bottom:14px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.judul-left{
    width:78%;
}

.judul-no{
    font-size:15px;
    color:#666;
    margin-bottom:5px;
}

.judul-main{
    font-size:22px;
    font-weight:700;
    margin-bottom:10px;
}

.row-info{
    display:flex;
    font-size:16px;
    color:#8a8a8a;
    margin-bottom:3px;
}

.left{
    width:180px;
}

.judul-right{
    width:22%;
    display:flex;
    justify-content:flex-end;
    align-items:center;
    gap:10px;
}

.btn-setuju{
    border:none;
    background:#8fdb9f;
    padding:8px 18px;
    border-radius:5px;
    font-size:15px;
    font-weight:600;
}

.btn-tolak{
    border:none;
    background:#f3a1a1;
    padding:8px 18px;
    border-radius:5px;
    font-size:15px;
    font-weight:600;
}

.reset-icon{
    cursor:pointer;
    font-size:18px;
}

/* Badge hasil verifikasi */
.badge-hasil{
    padding:8px 18px;
    border-radius:5px;
    font-size:15px;
    font-weight:600;
}

.badge-disetujui-hasil{
    background:#8fdb9f;
    color:#1a7a2e;
}

.badge-ditolak-hasil{
    background:#f3a1a1;
    color:#a81c1c;
}

.footer-btn{
    text-align:right;
    margin-top:25px;
}

.btn-kembali{
    background:#bcbcbc;
    color:black;
    text-decoration:none;
    padding:9px 18px;
    border-radius:20px;
    font-size:15px;
    margin-right:10px;
}

.btn-kirim{
    border:none;
    background:#f0c93d;
    padding:9px 22px;
    border-radius:20px;
    font-size:15px;
    font-weight:700;
}

.popup-bg{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.35);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:9999;
}

.popup-box{
    width:520px;
    background:#fff;
    border-radius:8px;
    padding:45px;
    text-align:center;
}

.popup-icon{
    width:95px;
    height:95px;
    border-radius:50%;
    line-height:95px;
    font-size:50px;
    margin:auto;
    margin-bottom:25px;
}

.success{
    border:5px solid #7edc88;
    color:#7edc88;
}

.error{
    border:5px solid #ef7d7d;
    color:#ef7d7d;
}

.popup-title{
    font-size:52px;
    font-weight:700;
    margin-bottom:10px;
}

.popup-text{
    font-size:26px;
    color:#555;
    margin-bottom:28px;
}

.popup-btn{
    background:#f0b400;
    border:none;
    color:white;
    font-size:24px;
    padding:12px 35px;
    border-radius:6px;
}
</style>

@endsection