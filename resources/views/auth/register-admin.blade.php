<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Registrasi Admin</title>

<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: 'Segoe UI', sans-serif;
  background: linear-gradient(135deg, #f5c51820, #ebebeb);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* CARD */
.card {
  background: #fff;
  border-radius: 16px;
  padding: 2.5rem;
  width: 100%;
  max-width: 480px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.12);
}

/* HEADER */
.header {
  text-align: center;
  margin-bottom: 1.8rem;
}

.logo img {
  width: 55px;
  margin-bottom: 10px;
}

.app-title {
  font-size: 20px;
  font-weight: 800;
}

.app-subtitle {
  font-size: 13px;
  color: #888;
}

.page-heading {
  margin-top: 6px;
  font-weight: 600;
  color: #444;
}

/* FORM */
.field {
  margin-bottom: 14px;
}

.field label {
  font-size: 12px;
  font-weight: 600;
  color: #555;
}

.field input {
  width: 100%;
  height: 42px;
  margin-top: 5px;
  padding: 0 12px;
  border-radius: 8px;
  border: 1px solid #ddd;
  background: #fafafa;
  transition: 0.2s;
}

.field input:focus {
  border-color: #f5c518;
  background: #fff;
}

/* ERROR */
.field.error input {
  border-color: #e53935;
  background: #fff5f5;
}

.error-text {
  font-size: 12px;
  color: #e53935;
  margin-top: 3px;
}

/* PASSWORD */
.pass-wrap {
  position: relative;
}

.toggle-pw {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  font-size: 13px;
  color: #888;
}

/* BUTTON */
.btn-register {
  width: 100%;
  height: 45px;
  margin-top: 18px;
  border: none;
  border-radius: 10px;
  background: #f5c518;
  font-weight: 700;
  cursor: pointer;
  transition: 0.2s;
}

.btn-register:hover {
  background: #e0b400;
}

/* POPUP */
.popup {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.4);
  display: flex;
  align-items: center;
  justify-content: center;
}

.popup-box {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  text-align: center;
  width: 280px;
}

.popup.success p { color: #2e7d32; }
.popup.error p { color: #c62828; }

.popup button {
  margin-top: 10px;
  padding: 6px 14px;
  border: none;
  border-radius: 6px;
  background: #f5c518;
  cursor: pointer;
}

</style>
</head>

<body>

<div class="card">

<div class="header">
    <div class="logo">
        <img src="{{ asset('images/logo si.jpeg') }}">
    </div>
    <div class="app-title">SITASI - TA</div>
    <div class="app-subtitle">Sistem Informasi Bimbingan Tugas Akhir</div>
    <div class="page-heading">Registrasi Admin</div>
</div>

<form method="POST" action="/register-admin">
@csrf

<div class="field @error('username') error @enderror">
    <label>Username *</label>
    <input type="text" name="username" value="{{ old('username') }}">
    @error('username')
        <div class="error-text">{{ $message }}</div>
    @enderror
</div>

<div class="field @error('email') error @enderror">
    <label>Email *</label>
    <input type="email" name="email" value="{{ old('email') }}">
    @error('email')
        <div class="error-text">{{ $message }}</div>
    @enderror
</div>

<div class="field">
    <label>Role</label>
    <input type="text" value="Admin" readonly>
</div>

<div class="field @error('password') error @enderror">
    <label>Password *</label>
    <div class="pass-wrap">
        <input type="password" name="password" id="password">
        <span class="toggle-pw" onclick="togglePassword('password')">👁</span>
    </div>
</div>

<div class="field">
    <label>Konfirmasi Password *</label>
    <div class="pass-wrap">
        <input type="password" name="password_confirmation" id="confirm">
        <span class="toggle-pw" onclick="togglePassword('confirm')">👁</span>
    </div>
</div>

<button type="submit" class="btn-register">Daftar Sekarang</button>

</form>

{{-- POPUP SUCCESS --}}
@if(session('success'))
<div class="popup success" id="popup">
    <div class="popup-box">
        <p>{{ session('success') }}</p>
        <button onclick="closePopup()">OK</button>
    </div>
</div>
@endif

{{-- POPUP ERROR --}}
@if($errors->any())
<div class="popup error" id="popup">
    <div class="popup-box">
        <p>{{ $errors->first() }}</p>
        <button onclick="closePopup()">OK</button>
    </div>
</div>
@endif

</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}

function closePopup() {
    document.getElementById("popup").style.display = "none";
}
</script>

</body>
</html>