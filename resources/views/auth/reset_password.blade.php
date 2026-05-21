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

.input-group { position: relative; margin-bottom: 4px; text-align: left; }
.input-group label { display: block; font-size: 0.82rem; font-weight: 600; color: #111C2D; margin-bottom: 6px; }
.input-group input { width: 100%; height: 48px; padding: 0 44px 0 16px; border: 1.5px solid #D1C6AB; border-radius: 12px; font-size: 0.9rem; font-family: 'Hanken Grotesk', sans-serif; color: #111C2D; outline: none; transition: border-color 0.2s, box-shadow 0.2s; }
.input-group input:focus { border-color: #b8860b; box-shadow: 0 0 0 3px rgba(184,134,11,0.1); }
.eye { position: absolute; right: 14px; bottom: 13px; cursor: pointer; color: #5D5F5F; display: none; background: none; border: none; font-size: 0.9rem; }

/* Modal */
.modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.35); z-index: 999; align-items: center; justify-content: center; padding: 24px; backdrop-filter: blur(2px); }
.modal-overlay.active { display: flex; }
.modal-box { background: white; border-radius: 20px; padding: 36px 32px; width: 100%; max-width: 380px; box-shadow: 0 8px 40px rgba(0,0,0,0.18); text-align: center; animation: modalIn 0.22s ease; }
@keyframes modalIn { from { transform: scale(0.92) translateY(10px); opacity: 0; } to { transform: scale(1) translateY(0); opacity: 1; } }
.modal-icon { width: 64px; height: 64px; background: #fff5f5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
.modal-icon i { font-size: 26px; color: #dc2626; }
.modal-box h3 { font-size: 1.1rem; font-weight: 800; color: #111C2D; margin-bottom: 10px; }
.modal-box ul { list-style: none; text-align: left; margin-bottom: 24px; display: flex; flex-direction: column; gap: 8px; }
.modal-box ul li { font-size: 0.84rem; color: #374151; display: flex; align-items: flex-start; gap: 8px; background: #fef2f2; border-radius: 8px; padding: 8px 12px; }
.modal-box ul li i { color: #dc2626; font-size: 0.75rem; margin-top: 2px; flex-shrink: 0; }
.btn-modal-close { width: 100%; height: 44px; border: none; border-radius: 50px; background: linear-gradient(90deg, #FACC15, #FDE047); color: #6C5700; font-weight: 700; font-size: 0.95rem; font-family: 'Hanken Grotesk', sans-serif; cursor: pointer; transition: filter 0.2s; }
.btn-modal-close:hover { filter: brightness(0.96); }

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

        <form method="POST" action="/reset-password" onsubmit="return validateForm()" novalidate>
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="input-group">
                <label for="password">Password Baru</label>
                <input type="password" name="password" id="password" placeholder="Masukkan password baru"
                    oninput="toggleEye('password','eye1')">
                <button type="button" class="eye" id="eye1" onclick="togglePassword('password', this)"><i class="fa-solid fa-eye"></i></button>
            </div>

            <div class="input-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Ulangi password baru"
                    oninput="toggleEye('confirm_password','eye2')">
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

{{-- MODAL VALIDASI --}}
<div class="modal-overlay" id="validasiModal">
    <div class="modal-box">
        <div class="modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <h3>Periksa Kembali</h3>
        <ul id="modalErrorList"></ul>
        <button class="btn-modal-close" onclick="closeModal()">Oke</button>
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
    document.getElementById(eyeId).style.display =
        document.getElementById(inputId).value.length > 0 ? 'block' : 'none';
}

function showError(inputId, errorId, message) {
    const input = document.getElementById(inputId);
    const errEl = document.getElementById(errorId);
    input.classList.add('is-invalid');
    errEl.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + message;
}

function clearError(inputId, errorId) {
    const input = document.getElementById(inputId);
    const errEl = document.getElementById(errorId);
    input.classList.remove('is-invalid');
    errEl.innerHTML = '';
}

function validateForm() {
    const p = document.getElementById('password').value;
    const c = document.getElementById('confirm_password').value;
    const errors = [];

    if (p === '') errors.push('Password baru harus diisi.');
    else if (p.length < 6) errors.push('Password minimal 6 karakter.');

    if (c === '') errors.push('Konfirmasi password harus diisi.');
    else if (p !== '' && p !== c) errors.push('Password dan konfirmasi password tidak sama.');

    if (errors.length > 0) {
        const list = document.getElementById('modalErrorList');
        list.innerHTML = errors.map(e => `<li><i class="fa-solid fa-circle-exclamation"></i>${e}</li>`).join('');
        document.getElementById('validasiModal').classList.add('active');
        return false;
    }
    return true;
}

function closeModal() {
    document.getElementById('validasiModal').classList.remove('active');
}

// Tutup modal kalau klik di luar box
document.getElementById('validasiModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

@if(session('success'))
<script>
    document.getElementById('stateForm').style.display = 'none';
    document.getElementById('stateSuccess').style.display = 'block';
</script>
@endif

</body>
</html>