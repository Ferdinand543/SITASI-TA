<nav class="navbar navbar-light bg-white shadow-sm">
    <div class="container d-flex align-items-center justify-content-between">

        <!-- KIRI: LOGO -->
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/SI.jpeg') }}" width="35" class="me-2">
            <strong>S1 - Sistem Informasi</strong>
        </div>

        <!-- KANAN: USER INFO -->
        <div class="d-flex align-items-center gap-2">
            @if(session('user'))
                <div class="text-end me-1" style="line-height:1.2;">
                    <div class="fw-bold" style="font-size:0.9rem;">{{ session('user')->nim_nid }}</div>
                    <div class="text-muted" style="font-size:0.78rem;">{{ session('user')->role }}</div>
                </div>
                <i class="fa-regular fa-circle-user" style="font-size:1.5rem; color:#555;"></i>
                <a href="/logout" class="text-dark ms-1" title="Logout">
                    <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:1.3rem;"></i>
                </a>
            @else
                <a href="/login" class="btn btn-outline-dark btn-sm">Masuk</a>
            @endif
        </div>

    </div>
</nav>