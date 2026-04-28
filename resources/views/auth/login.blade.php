<!DOCTYPE html>
<html>
<head>
    <title>Login - SITASITA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
        }

        .container {
            width: 400px;
            margin: 100px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .logo img {
            width: 60px;
            margin-bottom: 10px;
        }

        h2 { margin: 5px 0; }

        p {
            font-size: 14px;
            color: #777;
        }

        .input-group {
            position: relative;
            margin: 10px 0;
        }

        input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #f4b400;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            margin-top: 10px;
            cursor: pointer;
        }

        a {
            font-size: 14px;
            display: block;
            margin-top: 10px;
            color: #555;
            text-align: left;
        }

        /* ===================== */
        /*     MODAL OVERLAY     */
        /* ===================== */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 999;
            justify-content: center;
            align-items: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-box {
            background: white;
            border-radius: 16px;
            padding: 40px 50px;
            text-align: center;
            width: 380px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            animation: popIn 0.25s ease;
        }

        @keyframes popIn {
            from { transform: scale(0.8); opacity: 0; }
            to   { transform: scale(1);   opacity: 1; }
        }

        /* Icon lingkaran */
        .modal-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 36px;
        }

        .modal-icon.error {
            border: 3px solid #f44336;
            color: #f44336;
        }

        .modal-icon.success {
            border: 3px solid #4caf50;
            color: #4caf50;
        }

        .modal-title {
            font-size: 26px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        .modal-message {
            font-size: 15px;
            color: #666;
            margin-bottom: 24px;
        }

        .modal-btn {
            padding: 12px 40px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            cursor: pointer;
        }

        .modal-btn.error  { background: #f44336; }
        .modal-btn.success { background: #f4b400; }
    </style>
</head>

<body>

{{-- ===================== --}}
{{--   MODAL ERROR/SUCCESS --}}
{{-- ===================== --}}

{{-- MODAL GAGAL --}}
@if(session('error') || $errors->any())
<div class="modal-overlay active" id="modalError">
    <div class="modal-box">
        <div class="modal-icon error">
            <i class="fa-solid fa-xmark"></i>
        </div>
        <div class="modal-title">Gagal!</div>
        <div class="modal-message">
            @if(session('error'))
                {{ session('error') }}
            @else
                {{ $errors->first() }}
            @endif
        </div>
        <button class="modal-btn error" onclick="closeModal('modalError')">OK</button>
    </div>
</div>
@endif

{{-- MODAL BERHASIL (opsional, kalau mau di halaman lain pake session success) --}}
@if(session('success'))
<div class="modal-overlay active" id="modalSuccess">
    <div class="modal-box">
        <div class="modal-icon success">
            <i class="fa-solid fa-check"></i>
        </div>
        <div class="modal-title">Berhasil!</div>
        <div class="modal-message">{{ session('success') }}</div>
        <button class="modal-btn success" onclick="closeModal('modalSuccess')">OK</button>
    </div>
</div>
@endif

{{-- ===================== --}}
{{--      FORM LOGIN       --}}
{{-- ===================== --}}
<div class="container">
    <div class="logo">
        <img src="{{ asset('logo.png') }}" alt="logo">
    </div>

    <h2>SITASI - TA</h2>
    <p>Sistem Informasi Bimbingan Tugas Akhir</p>

    <form method="POST" action="/login">
        @csrf

        <input type="text" name="username" placeholder="Username"
               value="{{ old('username') }}">

        <div class="input-group">
            <input type="password" name="password" id="password" placeholder="Password">
            <i class="fa-solid fa-eye eye-icon" onclick="togglePassword()"></i>
        </div>

        <button type="submit">Login</button>

        <a href="/forgot-password">Forgot password?</a>
    </form>
</div>

<script>
function togglePassword() {
    const password = document.getElementById("password");
    password.type = password.type === "password" ? "text" : "password";
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}
</script>

</body>
</html>