<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SITASI TA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Hanken Grotesk', sans-serif;
            background: #FFFFFF;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        /* ── BRAND ── */
        .brand-wrap {
            text-align: center;
            margin-bottom: 28px;
            animation: fadeDown 0.45s ease both;
        }

        .brand-logo {
            width: 76px;
            height: 76px;
            margin: 0 auto 14px;
            display: block;
            border-radius: 50%;
        }

        .brand-title {
            font-family: 'Hanken Grotesk', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: #4D4632;   /* dari mockup */
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .brand-subtitle {
            font-family: 'Hanken Grotesk', sans-serif;
            font-size: 20px;          /* dari mockup: 20px */
            font-weight: 500;         /* Medium dari mockup */
            line-height: 28px;        /* dari mockup */
            color: #4D4632;           /* dari mockup */
            letter-spacing: 0px;
        }

        /* ── CARD ── */
        .login-card {
            background: #FFFFFF;
            border-radius: 20px;
            padding: 36px 32px 40px;
            width: 100%;
            max-width: 448px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            animation: fadeUp 0.45s ease 0.1s both;
        }

        /* "Login" judul card — #111C2D */
        .card-title {
            font-family: 'Hanken Grotesk', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: #111C2D;   /* dari mockup */
            margin-bottom: 4px;
            text-align: center;
        }

        /* "Masuk untuk melanjutkan ke sistem." — #5D5F5F */
        .card-desc {
            font-family: 'Hanken Grotesk', sans-serif;
            font-size: 0.85rem;
            color: #5D5F5F;   /* dari mockup */
            font-weight: 400;
            margin-bottom: 28px;
            text-align: center;
        }

        /* ── FIELD ── */
        .field-group {
            margin-bottom: 18px;
        }

        /* Label "Username", "Password" — #111C2D */
        .field-label {
            display: block;
            font-family: 'Hanken Grotesk', sans-serif;
            font-size: 0.82rem;
            font-weight: 600;
            color: #111C2D;   /* dari mockup */
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        /* Icon dalam input — #5D5F5F */
        .input-icon {
            position: absolute;
            left: 14px;
            color: #5D5F5F;   /* dari mockup */
            font-size: 0.88rem;
            pointer-events: none;
        }

        .input-wrap input {
            width: 100%;
            padding: 13px 14px 13px 42px;
            border: 1.5px solid #D1C6AB;   /* dari mockup */
            border-radius: 12px;
            font-size: 0.9rem;
            font-family: 'Hanken Grotesk', sans-serif;
            color: #111C2D;      /* dari mockup */
            background: #FFFFFF;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        /* Placeholder — #5D5F5F */
        .input-wrap input::placeholder {
            color: #5D5F5F;   /* dari mockup */
            font-weight: 400;
        }

        .input-wrap input:focus {
            border-color: #6C5700;
            box-shadow: 0 0 0 3px rgba(108, 87, 0, 0.08);
        }

        /* Eye icon — #5D5F5F */
        .eye-btn {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            cursor: pointer;
            color: #5D5F5F;   /* dari mockup */
            font-size: 0.9rem;
            padding: 0;
            transition: color 0.2s;
        }

        .eye-btn:hover {
            color: #111C2D;
        }

        /* ── FORGOT — #5D5F5F ── */
        .forgot-link {
            display: block;
            text-align: right;
            font-size: 0.78rem;
            color: #5D5F5F;   /* dari mockup */
            text-decoration: none;
            margin-top: 8px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #111C2D;
        }

        /* ── BUTTON — gradient #FACC15 → #FDE047 ── */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(90deg, #FACC15 0%, #FDE047 100%);   /* dari mockup */
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Hanken Grotesk', sans-serif;
            color: #6C5700;   /* dari mockup */
            cursor: pointer;
            margin-top: 24px;
            letter-spacing: 0.2px;
            transition: filter 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        }

        .btn-login:hover {
            filter: brightness(0.96);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .btn-login:active {
            transform: translateY(0);
            filter: brightness(0.9);
        }

        /* ── MODAL ── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(17, 28, 45, 0.45);
            z-index: 999;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(3px);
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-box {
            background: #FFFFFF;
            border-radius: 20px;
            padding: 40px 36px;
            text-align: center;
            width: 340px;
            box-shadow: 0 20px 60px rgba(17, 28, 45, 0.15);
            animation: popIn 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes popIn {
            from { transform: scale(0.8); opacity: 0; }
            to   { transform: scale(1);   opacity: 1; }
        }

        .modal-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            font-size: 28px;
        }

        .modal-icon.error {
            border: 2.5px solid #f44336;
            color: #f44336;
            background: #fff5f5;
        }

        .modal-icon.success {
            border: 2.5px solid #4caf50;
            color: #4caf50;
            background: #f5fff5;
        }

        .modal-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: #111C2D;
            margin-bottom: 8px;
        }

        .modal-message {
            font-size: 0.88rem;
            color: #5D5F5F;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .modal-btn {
            padding: 11px 36px;
            border: none;
            border-radius: 50px;
            font-size: 0.92rem;
            font-weight: 700;
            font-family: 'Hanken Grotesk', sans-serif;
            cursor: pointer;
            transition: filter 0.2s;
        }

        .modal-btn:hover { filter: brightness(0.9); }

        .modal-btn.error {
            background: #f44336;
            color: #fff;
        }

        .modal-btn.success {
            background: linear-gradient(90deg, #FACC15 0%, #FDE047 100%);
            color: #6C5700;
        }

        /* ── ANIMATIONS ── */
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

    {{-- ===================== --}}
    {{--   MODAL ERROR/SUCCESS --}}
    {{-- ===================== --}}

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
    {{--      BRAND HEADER     --}}
    {{-- ===================== --}}
    <div class="brand-wrap">
        <img src="{{ asset('logo.png') }}" alt="Logo SITASI" class="brand-logo">
        <div class="brand-title">SITASI - TA</div>
        <div class="brand-subtitle">Sistem Informasi Bimbingan Tugas Akhir</div>
    </div>

    {{-- ===================== --}}
    {{--      FORM LOGIN       --}}
    {{-- ===================== --}}
    <div class="login-card">
        <div class="card-title">Login</div>
        <div class="card-desc">Masuk untuk melanjutkan ke sistem.</div>

        <form method="POST" action="/login">
            @csrf

            {{-- USERNAME --}}
            <div class="field-group">
                <label class="field-label" for="username">Username</label>
                <div class="input-wrap">
                    <i class="fa-regular fa-user input-icon"></i>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Masukkan username"
                        value="{{ old('username') }}"
                        autocomplete="username"
                    >
                </div>
            </div>

            {{-- PASSWORD --}}
            <div class="field-group">
                <label class="field-label" for="password">Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                    >
                    <button type="button" class="eye-btn" onclick="togglePassword()">
                        <i class="fa-regular fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
                <a href="/forgot-password" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>

    <script>
        function togglePassword() {
            const input    = document.getElementById('password');
            const icon     = document.getElementById('eyeIcon');
            const isHidden = input.type === 'password';
            input.type     = isHidden ? 'text' : 'password';
            icon.className = isHidden ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }
    </script>

</body>
</html>