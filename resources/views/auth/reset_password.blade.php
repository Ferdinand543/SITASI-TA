<!DOCTYPE html>
<html lang="id">
<head>
<title>Reset Password - SITASI-TA</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Hanken Grotesk', sans-serif; background: #faf8f2; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }

.card { background: white; border-radius: 20px; padding: 48px 40px; width: 100%; max-width: 440px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); text-align: center; }

/* FORM STATE */
.state-form .logo img { width: 72px; height: 72px; border-radius: 50%; margin-bottom: 14px; }
.state-form h1 { font-size: 1.3rem; font-weight: 800; color: #4D4632; margin-bottom: 2px; }
.state-form .subtitle-brand { font-size: 0.82rem; color: #5D5F5F; margin-bottom: 28px; }
.state-form h2 { font-size: 1.5rem; font-weight: 800; color: #111C2D; margin-bottom: 8px; }
.state-form .subtitle-form { font-size: 0.85rem; color: #5D5F5F; line-height: 1.5; margin-bottom: 24px; }

.input-group { position: relative; margin-bottom: 14px; text-align: left; }
.input-group label { display: block; font-size: 0.82rem; font-weight: 600; color: #111C2D; margin-bottom: 6px; }
.input-group input { width: 100%; height: 48px; padding: 0 44px 0 16px; border: 1.5px solid #D1C6AB; border-radius: 12px; font-size: 0.9rem; font-family: 'Hanken Grotesk', sans-serif; color: #111C2D; outline: none; }
.input-group input:focus { border-color: #b8860b; box-shadow: 0 0 0 3px rgba(184,134,11,0.1); }
.eye { position: absolute; right: 14px; bottom: 13px; cursor: pointer; color: #5D5F5F; display: none; background: none; border: none; font-size: 0.9rem; }

.btn-primary { width: 100%; height: 48px; border: none; border-radius: 50px; background: linear-gradient(90deg, #FACC15, #FDE047); color: #6C5700; font-weight: 700; font-size: 1rem; font-family: 'Hanken Grotesk', sans-serif; cursor: pointer; margin-top: 8px; transition: filter 0.2s, transform 0.15s; box-shadow: 0 4px 16px rgba(0,0,0,0.1); }
.btn-primary:hover { filter: brightness(0.96); transform: translateY(-1px); }

.back-link { display: block; margin-top: 16px; font-size: 0.82rem; color: #5D5F5F; text-decoration: none; transition: color 0.2s; }
.back-link:hover { color: #111C2D; }

.error-msg { background: #fff5f5; border: 1px solid #fca5a5; border-radius: 10px; padding: 10px 14px; font-size: 0.82rem; color: #dc2626; margin-bottom: 14px; text-align: left; }

/* SUCCESS STATE */
.state-success { display: none; }
.state-success .icon-wrap { width: 80px; height: 80px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; }
.state-success .icon-wrap i { font-size: 32px; color: #16a34a; }
.state-success h2 { font-size: 1.4rem; font-weight: 800; color: #111C2D; margin-bottom: 12px; }
.state-success p { font-size: 0.88rem; color: #5D5F5F; line-height: 1.6; margin-bottom: 28px; }
.btn-success { display: flex; align-items: center; justify-content: center; width: 100%; height: 48px; border: none; border-radius: 50px; background: linear-gradient(90deg, #FACC15, #FDE047); color: #6C5700; font-weight: 700; font-size: 1rem; font-family: 'Hanken Grotesk', sans-serif; cursor: pointer; text-decoration: none; transition: filter 0.2s; }
.btn-success:hover { filter: brightness(0.96); }
</style>
</head>
<body>
<div class="card">

    {{-- FORM RESET --}}
    <div class="state-form" id="stateForm">
        <div class="logo"><img src="{{ asset('logo.png') }}" alt="Logo"></div>
        <h1>SITASI - TA</h1>
        <div class="subtitle-brand">Sistem Informasi Bimbingan Tugas Akhir</div>
        <h2>Reset Password</h2>
        <p class="subtitle-form">Buat password baru untuk melanjutkan akses ke sistem.</p>

        @if(session('error'))
        <div class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</div>
        @endif

        <form method="POST" action="/reset-password" onsubmit="return validateForm()">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="input-group">
                <label for="password">Password Baru</label>
                <input type="password" name="password" id="password" placeholder="Masukkan password baru" oninput="toggleEye('password','eye1')">
                <button type="button" class="eye" id="eye1" onclick="togglePassword('password', this)"><i class="fa-solid fa-eye"></i></button>
            </div>
            <div class="input-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Ulangi password baru" oninput="toggleEye('confirm_password','eye2')">
                <button type="button" class="eye" id="eye2" onclick="togglePassword('confirm_password', this)"><i class="fa-solid fa-eye"></i></button>
            </div>

            <button type="submit" class="btn-primary">Simpan Password</button>
        </form>

        <a href="/login" class="back-link">← Kembali ke halaman login</a>
    </div>

    {{-- SUCCESS STATE --}}
    <div class="state-success" id="stateSuccess">
        <div class="icon-wrap">
            <i class="fa-solid fa-check"></i>
        </div>
        <h2>Password Berhasil Diperbarui</h2>
        <p>Password akun Anda berhasil diperbarui. Silakan login menggunakan password baru.</p>
        <a href="/login" class="btn-success">Kembali ke Login</a>
    </div>

</div>

<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
function toggleEye(inputId, eyeId) {
    document.getElementById(eyeId).style.display = document.getElementById(inputId).value.length > 0 ? 'block' : 'none';
}
function validateForm() {
    const p = document.getElementById('password').value;
    const c = document.getElementById('confirm_password').value;
    if (p.length < 6) { alert('Password minimal 6 karakter.'); return false; }
    if (p !== c) { alert('Password dan konfirmasi tidak sama.'); return false; }
    return true;
}
</script>

@if(session('success'))
<script>
    document.getElementById('stateForm').style.display = 'none';
    document.getElementById('stateSuccess').style.display = 'block';
</script>
@endif

</body>
</html>