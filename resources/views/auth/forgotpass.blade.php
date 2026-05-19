<!DOCTYPE html>
<html lang="id">
<head>
<title>Lupa Password - SITASI-TA</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Hanken Grotesk', sans-serif; background: #faf8f2; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px; }

.card { background: white; border-radius: 20px; padding: 48px 40px; width: 100%; max-width: 440px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); text-align: center; }

/* STATE: FORM */
.state-form .icon-wrap { width: 80px; height: 80px; background: #fdf6d8; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; }
.state-form .icon-wrap i { font-size: 32px; color: #b8860b; }
.state-form h2 { font-size: 1.5rem; font-weight: 800; color: #111C2D; margin-bottom: 8px; }
.state-form p { font-size: 0.88rem; color: #5D5F5F; margin-bottom: 28px; line-height: 1.6; }
.state-form input { width: 100%; height: 48px; padding: 0 16px; border: 1.5px solid #D1C6AB; border-radius: 12px; font-size: 0.9rem; font-family: 'Hanken Grotesk', sans-serif; color: #111C2D; outline: none; }
.state-form input:focus { border-color: #b8860b; box-shadow: 0 0 0 3px rgba(184,134,11,0.1); }
.btn-primary { width: 100%; height: 48px; border: none; border-radius: 50px; background: linear-gradient(90deg, #FACC15, #FDE047); color: #6C5700; font-weight: 700; font-size: 1rem; font-family: 'Hanken Grotesk', sans-serif; cursor: pointer; margin-top: 16px; transition: filter 0.2s, transform 0.15s; }
.btn-primary:hover { filter: brightness(0.96); transform: translateY(-1px); }
.back-link { display: block; margin-top: 16px; font-size: 0.82rem; color: #5D5F5F; text-decoration: none; }
.back-link:hover { color: #111C2D; }

/* STATE: SUKSES KIRIM EMAIL */
.state-success { display: none; }
.state-success .icon-wrap { width: 80px; height: 80px; background: #fdf6d8; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; }
.state-success .icon-wrap i { font-size: 32px; color: #b8860b; }
.state-success h2 { font-size: 1.4rem; font-weight: 800; color: #111C2D; margin-bottom: 12px; }
.state-success p { font-size: 0.88rem; color: #5D5F5F; line-height: 1.6; margin-bottom: 20px; }
.info-badge { display: inline-flex; align-items: center; gap: 6px; background: #f5f5f5; border-radius: 20px; padding: 8px 16px; font-size: 0.8rem; color: #5D5F5F; margin-bottom: 28px; }
.btn-outline { width: 100%; height: 48px; border: 1.5px solid #D1C6AB; border-radius: 50px; background: white; color: #111C2D; font-weight: 600; font-size: 0.9rem; font-family: 'Hanken Grotesk', sans-serif; cursor: pointer; margin-top: 10px; transition: background 0.2s; }
.btn-outline:hover { background: #faf8f2; }

/* ERROR */
.error-msg { background: #fff5f5; border: 1px solid #fca5a5; border-radius: 10px; padding: 10px 14px; font-size: 0.82rem; color: #dc2626; margin-bottom: 14px; text-align: left; }
</style>
</head>
<body>
<div class="card">

    {{-- STATE: FORM --}}
    <div class="state-form" id="stateForm">
        <div class="icon-wrap">
            <i class="fa-regular fa-envelope"></i>
        </div>
        <h2>Forgot Password</h2>
        <p>Masukkan email kamu, kami akan kirim link reset password ke inbox kamu.</p>

        @if(session('error'))
        <div class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</div>
        @endif

        <form method="POST" action="/forgot-password">
            @csrf
            <input type="email" name="email" placeholder="Masukkan Email" required value="{{ old('email') }}">
            <button type="submit" class="btn-primary">Kirim Link Reset</button>
        </form>
        <a class="back-link" href="/login">← Kembali ke Login</a>
    </div>

    {{-- STATE: SUKSES --}}
    <div class="state-success" id="stateSuccess">
        <div class="icon-wrap">
            <i class="fa-regular fa-envelope-open"></i>
        </div>
        <h2>Cek Email Anda</h2>
        <p>Link reset password telah dikirim ke email Anda. Silakan periksa inbox atau folder spam untuk melanjutkan proses reset password.</p>
        <div class="info-badge">
            <i class="fa-solid fa-circle-info"></i>
            Tautan akan kedaluwarsa dalam 60 menit.
        </div>
        <a href="/login" class="btn-primary" style="display:flex;align-items:center;justify-content:center;text-decoration:none;">Kembali ke Login</a>
        <button class="btn-outline" onclick="document.getElementById('stateForm').style.display='block'; document.getElementById('stateSuccess').style.display='none';">Kirim Ulang Email</button>
    </div>

</div>

@if(session('success'))
<script>
    document.getElementById('stateForm').style.display = 'none';
    document.getElementById('stateSuccess').style.display = 'block';
</script>
@endif

</body>
</html>