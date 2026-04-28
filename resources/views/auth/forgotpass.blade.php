<!DOCTYPE html>
<html>
<head>
<title>Buat Password Baru - SITASITA</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>

body{
    font-family: Arial;
    background:#f5f5f5;
}

.container{
    width:380px;
    margin:90px auto;
    background:white;
    padding:35px;
    border-radius:12px;
    text-align:center;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

.logo img{
    width:70px;
    margin-bottom:10px;
}

h1{
    font-size:18px;
    margin:0;
}

.subtitle{
    font-size:14px;
    color:#777;
    margin-bottom:25px;
}

.title{
    font-size:22px;
    font-weight:bold;
    margin-bottom:20px;
}

.input-group{
    position:relative;
    margin-bottom:5px;
}

.input-group input{
    width:100%;
    height:44px;
    padding:0 40px 0 12px;
    border-radius:10px;
    border:1px solid #ccc;
    font-size:14px;
    box-sizing:border-box;
}

.eye{
    position:absolute;
    right:12px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    color:#666;
    display:none;
}

.error{
    font-size:13px;
    color:red;
    text-align:left;
    margin-bottom:10px;
}

button{
    width:100%;
    height:44px;
    border:none;
    border-radius:10px;
    background:#f4b400;
    color:white;
    font-weight:bold;
    font-size:14px;
    margin-top:10px;
    transition:transform 0.2s ease;
}

button:hover{
    transform:scale(1.05);
}

.back{
    display:block;
    margin-top:15px;
    font-size:14px;
    color:#888;
    text-decoration:none;
    text-align:left;
}

</style>
</head>

<body>

<div class="container">

<div class="logo">
<img src="{{ asset('logo.png') }}">
</div>

<h1>SITASI - TA</h1>
<div class="subtitle">Sistem Informasi Bimbingan Tugas Akhir</div>

<div class="title">Buat Password Baru</div>

<form method="POST" action="/reset-password" onsubmit="return validateForm()">
@csrf

<div class="input-group">
<input type="text" name="name" id="name" placeholder="Masukkan Username">
</div>
<div id="errorNim" class="error"></div>

<div class="input-group">
<input type="password" name="password" id="password" placeholder="Buat Password Baru" oninput="toggleEye('password','eye1')">
<i id="eye1" class="fa-solid fa-eye eye" onclick="togglePassword('password', this)"></i>
</div>
<div id="errorPassword" class="error"></div>

<div class="input-group">
<input type="password" name="confirm_password" id="confirm_password" placeholder="Konfirmasi Password" oninput="toggleEye('confirm_password','eye2')">
<i id="eye2" class="fa-solid fa-eye eye" onclick="togglePassword('confirm_password', this)"></i>
</div>
<div id="errorConfirm" class="error"></div>

<button type="submit">Simpan Password</button>

<a class="back" href="/login">← Kembali ke Login</a>

</form>

</div>

<script>

function togglePassword(id, icon){
    const input = document.getElementById(id);

    if(input.type === "password"){
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }else{
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}

function toggleEye(inputId, eyeId){
    const input = document.getElementById(inputId);
    const eye = document.getElementById(eyeId);

    if(input.value.length > 0){
        eye.style.display = "block";
    }else{
        eye.style.display = "none";
    }
}

function validateForm(){

    const nim = document.getElementById("nim_nid").value;
    const password = document.getElementById("password").value;
    const confirm = document.getElementById("confirm_password").value;

    const errorNim = document.getElementById("errorNim");
    const errorPassword = document.getElementById("errorPassword");
    const errorConfirm = document.getElementById("errorConfirm");

    errorNim.innerHTML = "";
    errorPassword.innerHTML = "";
    errorConfirm.innerHTML = "";

    let valid = true;

    if(nim === ""){
        errorNim.innerHTML = "Username wajib diisi.";
        valid = false;
    }

    if(password === ""){
        errorPassword.innerHTML = "Password wajib diisi.";
        valid = false;
    }

    if(confirm === ""){
        errorConfirm.innerHTML = "Konfirmasi password wajib diisi.";
        valid = false;
    }

    if(password !== "" && confirm !== "" && password !== confirm){
        errorConfirm.innerHTML = "Konfirmasi password harus sama dengan password.";
        valid = false;
    }

    return valid;
}

</script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session("success") }}',
    confirmButtonText: 'OK',
    confirmButtonColor: '#f4b400'
}).then(() => {
    window.location.href = "/login";
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: '{{ session("error") }}',
    confirmButtonText: 'OK',
    confirmButtonColor: '#f4b400'
});
</script>
@endif


</body>
</html>