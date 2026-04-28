<nav class="navbar navbar-light bg-white shadow-sm">
    <div class="container d-flex align-items-center justify-content-between">

        <!-- KIRI (LOGO) -->
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/SI.jpeg') }}" width="35" class="me-2">
            <strong>S1 - Sistem Informasi Unjani</strong>
        </div>

        <!-- TENGAH (SEARCH) -->
        <div class="search-box mx-3">
            <input type="text" placeholder="Search" class="search-input">
            <i class="fa fa-search search-icon"></i>
        </div>

        <!-- KANAN (BUTTON) -->
        <div class="d-flex align-items-center gap-2">

            @if(session('user'))
            <span class="fw-bold">Halo, {{ session('user')->name }}</span>

            
            @else
            <a href="/login" class="btn btn-outline-dark btn-sm">Masuk</a>
            @endif

        </div>

        
    </div>
</nav>