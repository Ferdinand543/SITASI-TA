<!DOCTYPE html>
<html>
<head>
<style>
body { font-family: Arial; background: #f5f5f5; margin: 0; padding: 0; }
.wrap { max-width: 500px; margin: 40px auto; background: white; border-radius: 12px; padding: 40px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
h2 { color: #333; }
p { color: #555; font-size: 15px; line-height: 1.6; }
.btn { display: inline-block; margin-top: 20px; padding: 12px 30px; background: #f4b400; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 15px; }
.footer { margin-top: 30px; font-size: 12px; color: #aaa; }
</style>
</head>
<body>
<div class="wrap">
    <h2>Reset Password - SITASI-TA</h2>
    <p>Halo <strong>{{ $user->nama }}</strong>,</p>
    <p>Kamu meminta reset password untuk akun SITASI-TA. Klik tombol di bawah untuk membuat password baru:</p>
    <a class="btn" href="{{ url('/reset-password/' . $token) }}">Reset Password</a>
    <p style="margin-top:20px;">Link ini hanya berlaku sekali. Jika kamu tidak meminta reset password, abaikan email ini.</p>
    <div class="footer">SITASI-TA — Sistem Informasi Tugas Akhir</div>
</div>
</body>
</html>