<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SITASI TA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: #f5f6fa;
        }

        .hero {
            background: linear-gradient(to right, #ffffff, #f9d976);
            padding: 40px;
            border-radius: 12px;
        }

        .hero-section {
            position: relative;
            background: url('/images/bg.jpeg') no-repeat right center;
            background-size: 55%;
            border-radius: 20px;
            padding: 70px 60px;
            min-height: 320px;
            display: flex;
            align-items: center;
            background-color: #f8f9fa;
        }

        .menu-wrapper {
            display: flex;
            background: #f9f9f9;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 20px;
        }

        .menu-item {
            flex: 1;
            text-align: center;
            padding: 30px 20px;
        }

        .menu-item:not(:last-child) {
            border-right: 1px dashed #ccc;
        }

        .menu-item img {
            max-height: 140px;
            width: auto;
            object-fit: contain;
            margin-bottom: 20px;
        }

        .menu-item h5 {
            font-weight: 600;
        }

        .menu-item p {
            color: #666;
            font-size: 14px;
        }

        .menu-item:hover {
            background: #ffffff;
            transition: 0.3s;
        }

        .footer {
            background: #4b4453;
            color: white;
            padding: 30px;
        }

        .menu-bottom-wrapper {
            display: flex;
            gap: 20px;
            background: #f1f1f1;
            padding: 12px 20px;
            border-radius: 30px;
            width: fit-content;
            margin-top: -20px;
        }

        .menu-bottom {
            text-decoration: none;
            color: #555;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 14px;
        }

        .menu-bottom.active {
            background: #f7d27c;
            color: #000;
            font-weight: 500;
        }

        .search-box {
            position: relative;
            width: 600px;
        }

        .search-input {
            width: 100%;
            padding: 8px 35px 8px 15px;
            border-radius: 20px;
            border: 1px solid #ddd;
            outline: none;
            background: #f5f5f5;
        }

        .search-input:focus {
            border-color: #999;
        }

        .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: #666;
        }

        .social-icons {
            display: flex;
            gap: 15px;
        }

        .social-icons a {
            color: white;
            font-size: 18px;
            transition: 0.3s;
        }

        .social-icons a:hover {
            color: #f7d27c;
        }

        .pengajuan-card {
            border-radius: 20px;
            padding: 40px;
            border: 1px solid #ccc;
        }

        .btn-ajukan {
            padding: 10px 30px;
            border-radius: 8px;
            font-weight: 500;
        }

        .badge-status {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
        }

        .approved {
            background: #c8e6c9;
            color: #2e7d32;
        }

        .rejected {
            background: #ffcdd2;
            color: #c62828;
        }

        .pending {
            background: #ffe082;
            color: #8d6e00;
        }

        .modal-content {
            border-radius: 15px;
            padding: 10px;
        }

        .modal-body label {
            font-size: 14px;
            font-weight: 500;
        }

        .modal-body input,
        .modal-body textarea {
            border-radius: 8px;
        }

        .form-header-box {
            background: #f1f1f1;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            min-width: 400px;
        }

        .form-header-box h3 {
            margin-bottom: 5px;
        }

        .form-header-box p {
            font-size: 14px;
        }

        .is-invalid {
            border: 2px solid #dc3545 !important;
        }
    </style>
</head>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<body>

    @include('partials.navbar')

    <div class="container mt-4">
        @yield('content')
    </div>

    @include('partials.footer')

    <!-- 🔥 POPUP LOGIN BERHASIL -->
    @if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                title: 'Berhasil!',
               text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#f4b400',
                confirmButtonText: 'OK'
            });
        });
    </script>
    @endif

</body>

</html>