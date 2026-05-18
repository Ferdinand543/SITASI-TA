<!DOCTYPE html>
<html lang="id">
<head>
<title>Password Berhasil Direset - SITASI-TA</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Hanken Grotesk', sans-serif; background: #faf8f2; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px; }

.brand-wrap { text-align: center; margin-bottom: 24px; }
.brand-wrap img { width: 72px; height: 72px; border-radius: 50%; margin-bottom: 12px; display: block; margin-left: auto; margin-right: auto; }
.brand-title { font-size: 1.3rem; font-weight: 800; color: #4D4632; margin-bottom: 2px; }
.brand-subtitle { font-size: 0.78rem; color: #5D5F5F; text-transform: uppercase; letter-spacing: 0.5px; }

.card { background: white; border-radius: 20px; padding: 48px 40px; width: 100%; max-width: 440px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); text-align: center; }
.icon-wrap { width: 80px; height: 80px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; }
.icon-wrap i { font-size: 32px; color: #16a34a; }
h2 { font-size: 1.4rem; font-weight: 800; color: #111C2D; margin-bottom: 12px; }
p { font-size: 0.88rem; color: #5D5F5F; line-height: 1.6; margin-bottom: 28px; }
.btn { display: flex; align-items: center; justify-content: center; width: 100%; height: 48px; border-radius: 50px; background: linear-gradient(90deg, #FACC15, #FDE047); color: #6C5700; font-weight: 700; font-size: 1rem; font-family: 'Hanken Grotesk', sans-serif; text-decoration: none; transition: filter 0.2s, transform 0.15s; box-shadow: 0 4px 16px rgba(0,0,0,0.1); }
.btn:hover { filter: brightness(0.96); transform: translateY(-1px); }
</style>
</head>
<body>

<div class="brand-wrap">
    <img src="{{ asset('logo.png') }}" alt="Logo SITASI">
    <div class="brand-title">SITASI - TA</div>
    <div class="brand-subtitle">Sistem Informasi Bimbingan Tugas Akhir</div>
</div>

<div class="card">
    <div class="icon-wrap">
        <i class="fa-solid fa-check"></i>
    </div>
    <h2>Password Berhasil Diperbarui</h2>
    <p>Password akun Anda berhasil diperbarui. Silakan login menggunakan password baru.</p>
    <a href="/login" class="btn">Kembali ke Login</a>
</div>

</body>
</html>