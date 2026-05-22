<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrasi Admin - SITASI TA</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{ margin:0; padding:0; box-sizing:border-box; }
html, body{
    height:100%;
    font-family:'Inter',sans-serif;
    overflow:hidden;
}
body{
    display:flex;
    justify-content:center;
    align-items:center;
    background:
        linear-gradient(rgba(255,255,255,.92), rgba(255,255,255,.92)),
        url('{{ asset("images/bg.jpeg") }}');
    background-size:cover;
    background-position:center;
    position:relative;
}
body::before{
    content:'';
    position:absolute;
    top:-120px; left:-120px;
    width:420px; height:420px;
    border-radius:50%;
    background:radial-gradient(circle,#F7D04625 0%, transparent 70%);
    pointer-events:none;
}
body::after{
    content:'';
    position:absolute;
    bottom:-150px; right:-150px;
    width:500px; height:500px;
    border-radius:50%;
    background:radial-gradient(circle,#F7D04620 0%, transparent 70%);
    pointer-events:none;
}
.register-card{
    width:100%;
    max-width:380px;
    background:#ffffffee;
    backdrop-filter:blur(8px);
    border-radius:20px;
    padding:20px 22px 16px;
    box-shadow: 0 20px 40px rgba(0,0,0,.08), 0 4px 12px rgba(0,0,0,.04);
    border:1px solid rgba(255,255,255,.7);
    position:relative;
    z-index:2;
}
.logo-area{
    text-align:center;
    margin-bottom:10px;
}
.logo-area img{
    width:40px;
    margin-bottom:4px;
}
.logo-title{
    font-size:17px;
    font-weight:800;
    letter-spacing:.5px;
    color:#735C00;
}
.logo-subtitle{
    font-size:9px;
    letter-spacing:1.5px;
    color:#4D4632;
    opacity:0.8;
    margin-top:1px;
    text-transform:uppercase;
}
.heading{
    text-align:center;
    margin-bottom:14px;
}
.heading h2{
    font-size:17px;
    font-weight:700;
    color:#222;
    margin-bottom:3px;
}
.heading p{
    font-size:11px;
    color:#777;
}
.form-group{
    margin-bottom:9px;
}
.form-group label{
    display:block;
    margin-bottom:4px;
    font-size:11px;
    font-weight:600;
    color:#444;
}
.input-wrap{
    position:relative;
}
.input-wrap input{
    width:100%;
    height:38px;
    border:1px solid #E6E6E6;
    border-radius:10px;
    background:#FAFAFA;
    padding:0 14px 0 38px;
    font-size:12px;
    transition:.2s;
    outline:none;
    color:#333;
}
.input-wrap input:focus{
    border-color:#F5C518;
    background:#fff;
    box-shadow:0 0 0 3px rgba(245,197,24,.10);
}
.input-wrap input[readonly]{
    color:#888;
    cursor:default;
}
.input-icon{
    position:absolute;
    left:12px;
    top:50%;
    transform:translateY(-50%);
    color:#bbb;
    display:flex;
    align-items:center;
}
.toggle-password{
    position:absolute;
    right:12px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    color:#bbb;
    display:flex;
    align-items:center;
    background:none;
    border:none;
    padding:0;
    transition:.15s;
}
.toggle-password:hover{ color:#888; }
.input-error input{
    border-color:#E53935;
    background:#FFF5F5;
}
.error-text{
    margin-top:3px;
    font-size:10px;
    color:#E53935;
}
.btn-register{
    width:100%;
    height:42px;
    border:none;
    border-radius:12px;
    background:linear-gradient(to right,#F5C518,#F2D76B);
    color:#5A4600;
    font-weight:700;
    font-size:13px;
    cursor:pointer;
    margin-top:6px;
    transition:.25s;
    box-shadow:0 6px 14px rgba(245,197,24,.25);
}
.btn-register:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(245,197,24,.35);
}
.login-text{
    text-align:center;
    margin-top:12px;
    font-size:11px;
    color:#777;
}
.login-text a{
    color:#D4A900;
    text-decoration:none;
    font-weight:700;
}
.popup{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.45);
    display:flex;
    align-items:center;
    justify-content:center;
    z-index:99;
}
.popup-box{
    width:300px;
    background:#fff;
    border-radius:16px;
    padding:24px;
    text-align:center;
    box-shadow:0 10px 25px rgba(0,0,0,.15);
}
.popup-box p{ font-size:13px; margin-bottom:16px; }
.popup.success p{ color:#15803D; }
.popup.error p{ color:#DC2626; }
.popup-box button{
    border:none;
    background:#F5C518;
    padding:8px 16px;
    border-radius:8px;
    font-weight:600;
    cursor:pointer;
    font-size:13px;
}
</style>
</head>
<body>

<div class="register-card">

    <!-- LOGO -->
    <div class="logo-area">
        <img src="{{ asset('logo.png') }}" alt="Logo">
        <div class="logo-title">SITASI - TA</div>
        <div class="logo-subtitle">Sistem Informasi Bimbingan Tugas Akhir</div>
    </div>

    <!-- HEADING -->
    <div class="heading">
        <h2>Registrasi Admin</h2>
        <p>Buat akun admin untuk mengakses sistem SITASI-TA.</p>
    </div>

    <!-- FORM -->
    <form method="POST" action="/register-admin">
        @csrf

        <!-- NID ADMIN -->
        <div class="form-group @error('nim_nid') input-error @enderror">
            <label>NID / Username Admin</label>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="5" width="20" height="14" rx="2"/><path d="M16 10h2M16 14h2M6 10h6M6 14h4"/>
                    </svg>
                </span>
                <input type="text" name="nim_nid" placeholder="Masukkan NID / Username" value="{{ old('nim_nid') }}">
            </div>
            @error('nim_nid')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <!-- NAMA -->
        <div class="form-group @error('nama') input-error @enderror">
            <label>Nama Lengkap</label>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </span>
                <input type="text" name="nama" placeholder="Masukkan nama lengkap" value="{{ old('nama') }}">
            </div>
            @error('nama')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <!-- EMAIL -->
        <div class="form-group @error('email') input-error @enderror">
            <label>Email</label>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                    </svg>
                </span>
                <input type="email" name="email" placeholder="Masukkan email" value="{{ old('email') }}">
            </div>
            @error('email')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <!-- ROLE -->
        <div class="form-group">
            <label>Role</label>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/>
                    </svg>
                </span>
                <input type="text" value="Admin" readonly>
                <input type="hidden" name="role" value="admin">
            </div>
        </div>

        <!-- PASSWORD -->
        <div class="form-group @error('password') input-error @enderror">
            <label>Password</label>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </span>
                <input type="password" name="password" id="password" placeholder="Masukkan password">
                <button type="button" class="toggle-password" onclick="togglePassword('password', this)">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            @error('password')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <!-- KONFIRMASI -->
        <div class="form-group">
            <label>Konfirmasi Password</label>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </span>
                <input type="password" name="password_confirmation" id="confirm" placeholder="Masukkan konfirmasi password">
                <button type="button" class="toggle-password" onclick="togglePassword('confirm', this)">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-register">Daftar Admin →</button>
    </form>

    <div class="login-text">
        Sudah memiliki akun? <a href="/login">Login</a>
    </div>

</div>

{{-- SUCCESS --}}
@if(session('success'))
<div class="popup success" id="popup">
    <div class="popup-box">
        <p>{{ session('success') }}</p>
        <button onclick="closePopup()">OK</button>
    </div>
</div>
@endif

{{-- ERROR --}}
@if($errors->any())
<div class="popup error" id="popup">
    <div class="popup-box">
        <p>{{ $errors->first() }}</p>
        <button onclick="closePopup()">OK</button>
    </div>
</div>
@endif

<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.innerHTML = isText
        ? `<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`
        : `<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`;
}
function closePopup(){
    document.getElementById('popup').style.display='none';
}
</script>

</body>
</html>