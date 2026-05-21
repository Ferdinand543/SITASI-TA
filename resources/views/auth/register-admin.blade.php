<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Registrasi Admin - SITASI TA</title>

<link rel="preconnect" href="https://fonts.googleapis.com">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

html{
    overflow-y:auto;
}

body{
    font-family:'Inter',sans-serif;
    min-height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;

    background:
        linear-gradient(rgba(255,255,255,.92), rgba(255,255,255,.92)),
        url('{{ asset("images/bg.jpeg") }}');

    background-size:cover;
    background-position:center;

    position:relative;

    overflow-y:auto;
    overflow-x:hidden;

    padding:40px 0;
}

/* DECORATION */
body::before{
    content:'';
    position:absolute;
    top:-120px;
    left:-120px;
    width:420px;
    height:420px;
    border-radius:50%;
    background:radial-gradient(circle,#F7D04625 0%, transparent 70%);
}

body::after{
    content:'';
    position:absolute;
    bottom:-150px;
    right:-150px;
    width:500px;
    height:500px;
    border-radius:50%;
    background:radial-gradient(circle,#F7D04620 0%, transparent 70%);
}

/* CARD */
.register-card{
    width:100%;
    max-width:420px;
    background:#ffffffee;
    backdrop-filter:blur(8px);
    border-radius:24px;

    padding:32px 28px;

    box-shadow:
        0 20px 40px rgba(0,0,0,.08),
        0 4px 12px rgba(0,0,0,.04);

    border:1px solid rgba(255,255,255,.7);

    position:relative;
    z-index:2;

    margin:30px 20px;
}

/* LOGO */
.logo-area{
    text-align:center;
    margin-bottom:28px;
}

.logo-area img{
    width:60px;
    margin-bottom:10px;
}

.logo-title{
    font-size:24px;
    font-weight:800;
    letter-spacing:.5px;
    color:#E0B300;
}

.logo-subtitle{
    font-size:11px;
    letter-spacing:1.5px;
    color:#7A7A7A;
    margin-top:2px;
    text-transform:uppercase;
}

/* HEADING */
.heading{
    text-align:center;
    margin-bottom:28px;
}

.heading h2{
    font-size:24px;
    font-weight:700;
    color:#222;
    margin-bottom:6px;
}

.heading p{
    font-size:13px;
    color:#777;
}

/* FORM */
.form-group{
    margin-bottom:16px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-size:13px;
    font-weight:600;
    color:#444;
}

.input-wrap{
    position:relative;
}

.input-wrap input{
    width:100%;
    height:48px;

    border:1px solid #E6E6E6;
    border-radius:12px;

    background:#FAFAFA;

    padding:0 16px 0 44px;

    font-size:13px;

    transition:.2s;
    outline:none;
}

.input-wrap input:focus{
    border-color:#F5C518;
    background:#fff;
    box-shadow:0 0 0 4px rgba(245,197,24,.10);
}

.input-icon{
    position:absolute;
    left:16px;
    top:50%;
    transform:translateY(-50%);
    font-size:15px;
    color:#999;
}

.toggle-password{
    position:absolute;
    right:16px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    color:#999;
    font-size:14px;
}

/* ERROR */
.input-error input{
    border-color:#E53935;
    background:#FFF5F5;
}

.error-text{
    margin-top:6px;
    font-size:12px;
    color:#E53935;
}

/* BUTTON */
.btn-register{
    width:100%;
    height:54px;
    border:none;
    border-radius:16px;
    background:linear-gradient(to right,#F5C518,#F2D76B);
    color:#5A4600;
    font-weight:700;
    font-size:15px;
    cursor:pointer;
    margin-top:8px;
    transition:.25s;
    box-shadow:0 8px 18px rgba(245,197,24,.25);
}

.btn-register:hover{
    transform:translateY(-2px);
    box-shadow:0 14px 24px rgba(245,197,24,.35);
}

/* LOGIN */
.login-text{
    text-align:center;
    margin-top:24px;
    font-size:13px;
    color:#777;
}

.login-text a{
    color:#D4A900;
    text-decoration:none;
    font-weight:700;
}

/* POPUP */
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
    width:320px;
    background:#fff;
    border-radius:18px;
    padding:28px;
    text-align:center;
    box-shadow:0 10px 25px rgba(0,0,0,.15);
}

.popup-box p{
    font-size:14px;
    margin-bottom:18px;
}

.popup.success p{
    color:#15803D;
}

.popup.error p{
    color:#DC2626;
}

.popup-box button{
    border:none;
    background:#F5C518;
    padding:10px 18px;
    border-radius:10px;
    font-weight:600;
    cursor:pointer;
}

@media(max-width:500px){

    body{
        align-items:flex-start;
        padding:20px 0;
    }

    .register-card{
        margin:20px;
        padding:26px 20px;
        border-radius:20px;
    }

    .heading h2{
        font-size:22px;
    }

    .input-wrap input{
        height:46px;
        font-size:13px;
    }

    .btn-register{
        height:48px;
    }
}
</style>
</head>

<body>

<div class="register-card">

    <!-- LOGO -->
    <div class="logo-area">

        <img src="{{ asset('logo.png') }}" alt="Logo">

        <div class="logo-title">
            SITASI - TA
        </div>

        <div class="logo-subtitle">
            Sistem Informasi Bimbingan Tugas Akhir
        </div>

    </div>

    <!-- HEADING -->
    <div class="heading">

        <h2>Registrasi Admin</h2>

        <p>
            Buat akun admin untuk mengakses sistem SITASI-TA.
        </p>

    </div>

    <!-- FORM -->
    <form method="POST" action="/register-admin">

        @csrf

        <!-- NID ADMIN -->
        <div class="form-group @error('nim_nid') input-error @enderror">

            <label>NID / Username Admin</label>

            <div class="input-wrap">

                <span class="input-icon">🪪</span>

                <input
                    type="text"
                    name="nim_nid"
                    placeholder="Masukkan NID / Username"
                    value="{{ old('nim_nid') }}"
                >

            </div>

            @error('nim_nid')
                <div class="error-text">{{ $message }}</div>
            @enderror

        </div>

        <!-- NAMA -->
        <div class="form-group @error('nama') input-error @enderror">

            <label>Nama Lengkap</label>

            <div class="input-wrap">

                <span class="input-icon">👤</span>

                <input
                    type="text"
                    name="nama"
                    placeholder="Masukkan nama lengkap"
                    value="{{ old('nama') }}"
                >

            </div>

            @error('nama')
                <div class="error-text">{{ $message }}</div>
            @enderror

        </div>

        <!-- EMAIL -->
        <div class="form-group @error('email') input-error @enderror">

            <label>Email</label>

            <div class="input-wrap">

                <span class="input-icon">✉️</span>

                <input
                    type="email"
                    name="email"
                    placeholder="Masukkan email"
                    value="{{ old('email') }}"
                >

            </div>

            @error('email')
                <div class="error-text">{{ $message }}</div>
            @enderror

        </div>

        <!-- ROLE -->
        <div class="form-group">

            <label>Role</label>

            <div class="input-wrap">

                <span class="input-icon">⚙️</span>

                <input
                    type="text"
                    value="Admin"
                    readonly
                >

                <input
                    type="hidden"
                    name="role"
                    value="admin"
                >

            </div>

        </div>

        <!-- PASSWORD -->
        <div class="form-group @error('password') input-error @enderror">

            <label>Password</label>

            <div class="input-wrap">

                <span class="input-icon">🔒</span>

                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Masukkan password"
                >

                <span
                    class="toggle-password"
                    onclick="togglePassword('password')"
                >
                    👁
                </span>

            </div>

            @error('password')
                <div class="error-text">{{ $message }}</div>
            @enderror

        </div>

        <!-- KONFIRMASI -->
        <div class="form-group">

            <label>Konfirmasi Password</label>

            <div class="input-wrap">

                <span class="input-icon">🔒</span>

                <input
                    type="password"
                    name="password_confirmation"
                    id="confirm"
                    placeholder="Masukkan konfirmasi password"
                >

                <span
                    class="toggle-password"
                    onclick="togglePassword('confirm')"
                >
                    👁
                </span>

            </div>

        </div>

        <button type="submit" class="btn-register">

            Daftar Admin →

        </button>

    </form>

    <div class="login-text">

        Sudah memiliki akun?
        <a href="/login">Login</a>

    </div>

</div>

{{-- SUCCESS --}}
@if(session('success'))

<div class="popup success" id="popup">

    <div class="popup-box">

        <p>{{ session('success') }}</p>

        <button onclick="closePopup()">
            OK
        </button>

    </div>

</div>

@endif

{{-- ERROR --}}
@if($errors->any())

<div class="popup error" id="popup">

    <div class="popup-box">

        <p>{{ $errors->first() }}</p>

        <button onclick="closePopup()">
            OK
        </button>

    </div>

</div>

@endif

<script>

function togglePassword(id){

    const input = document.getElementById(id);

    input.type =
        input.type === 'password'
        ? 'text'
        : 'password';
}

function closePopup(){

    document.getElementById('popup').style.display='none';
}

</script>

</body>
</html>