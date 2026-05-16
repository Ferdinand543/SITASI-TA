{{-- ============================================================
     SIDEBAR
     ============================================================ --}}
<div id="sidebarOverlay" onclick="tutupSidebar()" style="
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.35);
    z-index:1040;
"></div>

<div id="sidebar" style="
    position:fixed;
    top:0; left:0;
    width:240px;
    height:100vh;
    background:#fff;
    z-index:1050;
    transform:translateX(-100%);
    transition:transform 0.28s cubic-bezier(.4,0,.2,1);
    display:flex;
    flex-direction:column;
    box-shadow:4px 0 24px rgba(0,0,0,0.10);
">
    {{-- Header Sidebar --}}
    <div style="padding:22px 20px 14px; border-bottom:1px solid #f0ebe0;">
        <div style="font-size:1.05rem; font-weight:800; color:#111C2D; letter-spacing:0.3px;">SITASITA</div>
        <div style="font-size:0.72rem; color:#735C00; margin-top:2px;">Sistem Pengelolaan TA Mahasiswa</div>
    </div>

    {{-- Menu --}}
    <nav style="flex:1; overflow-y:auto; padding:12px 0;">
        @php
            $path      = request()->path();
            $roleNav   = strtolower(trim(session('user')->role ?? ''));

            // Badge proposal untuk dosen reviewer di navbar
            $badgeProposalNav = false;
            if ($roleNav === 'dosen reviewer') {
                $nimRevNav = session('user')->nim_nid;
                $badgeProposalNav = \Illuminate\Support\Facades\DB::table('proposal')
                    ->where('proposal.status', 'selesai')
                    ->whereNotExists(function ($q) use ($nimRevNav) {
                        $q->select(\Illuminate\Support\Facades\DB::raw(1))
                          ->from('tinjauan_proposal')
                          ->whereColumn('tinjauan_proposal.proposal_id', 'proposal.id')
                          ->where('tinjauan_proposal.nim_nid_reviewer', $nimRevNav);
                    })
                    ->exists();
            }
        @endphp

        <a href="/dashboard/dosen" class="sidebar-link {{ $path === 'dashboard/dosen' ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i> Beranda
        </a>

        <a href="{{ route('pengajuan') }}"
           class="sidebar-link {{ str_starts_with($path, 'pengajuan') ? 'active' : '' }}">
            <i class="fa-solid fa-file-pen"></i> Pengajuan Judul
        </a>

        <a href="{{ $roleNav === 'dosen reviewer' ? route('reviewer.proposal') : route('proposal.index') }}"
           class="sidebar-link {{ str_starts_with($path, 'proposal') || str_starts_with($path, 'reviewer') ? 'active' : '' }}"
           style="position:relative;">
            <i class="fa-solid fa-file-lines"></i> Proposal
            @if($badgeProposalNav)
                <span style="
                    width:8px;
                    height:8px;
                    background:red;
                    border-radius:50%;
                    display:inline-block;
                    margin-left:auto;
                    flex-shrink:0;
                "></span>
            @endif
        </a>

        <a href="#" class="sidebar-link">
            <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Bimbingan
        </a>

        <a href="#" class="sidebar-link">
            <i class="fa-solid fa-chart-bar"></i> Nilai
        </a>

        <div style="height:1px; background:#f0ebe0; margin:10px 16px;"></div>

        <a href="#" class="sidebar-link">
            <i class="fa-regular fa-calendar"></i> Jadwal
        </a>

        <a href="{{ route('admin.profil') }}" class="sidebar-link {{ request()->is('admin/profil') ? 'active' : '' }}">
    <i class="fa-regular fa-user"></i> Profil
        </a>
    </nav>

    {{-- Logout --}}
    <div style="padding:16px 20px; border-top:1px solid #f0ebe0;">
        <a href="/logout" class="sidebar-link" style="color:#c0392b;">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
        </a>
    </div>
</div>

{{-- ============================================================
     NAVBAR
     ============================================================ --}}
<nav style="
    background:#fff;
    border-bottom:1px solid #f0ebe0;
    padding:0 24px;
    height:58px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    position:sticky;
    top:0;
    z-index:1030;
    box-shadow:0 2px 8px rgba(0,0,0,0.06);
">
    {{-- KIRI: Hamburger + Logo --}}
    <div style="display:flex; align-items:center; gap:14px;">
        <button onclick="toggleSidebar()" style="
            background:none;
            border:none;
            cursor:pointer;
            padding:6px;
            border-radius:8px;
            color:#4D4632;
            font-size:1.2rem;
            line-height:1;
            transition:0.2s;
        " onmouseover="this.style.background='#FEF3C7'" onmouseout="this.style.background='none'">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div style="display:flex; align-items:center; gap:8px;">
            <img src="{{ asset('images/SI.jpeg') }}" width="30" style="border-radius:6px;">
            <span style="font-weight:700; color:#111C2D; font-size:0.95rem;">S1 - Sistem Informasi Unjani</span>
        </div>
    </div>

    {{-- KANAN: User info --}}
    @if(session('user'))
    <div style="display:flex; align-items:center; gap:10px;">
        <div style="text-align:right; line-height:1.25;">
            <div style="font-weight:700; font-size:0.88rem; color:#111C2D;">{{ session('user')->nim_nid }}</div>
            <div style="font-size:0.74rem; color:#735C00;">{{ session('user')->role }}</div>
        </div>
        <i class="fa-regular fa-circle-user" style="font-size:1.6rem; color:#4D4632;"></i>
    </div>
    @else
    <a href="/login" style="
        padding:7px 18px;
        border-radius:8px;
        border:1px solid #735C00;
        color:#735C00;
        text-decoration:none;
        font-size:0.88rem;
        font-weight:600;
        transition:0.2s;
    ">Masuk</a>
    @endif
</nav>

{{-- ============================================================
     STYLE SIDEBAR LINKS
     ============================================================ --}}
<style>
.sidebar-link {
    display:flex;
    align-items:center;
    gap:12px;
    padding:10px 20px;
    text-decoration:none;
    color:#4D4632;
    font-size:0.88rem;
    font-weight:500;
    border-radius:0;
    transition:0.18s;
    border-left:3px solid transparent;
}
.sidebar-link:hover {
    background:#FEF3C7;
    color:#735C00;
    border-left-color:#735C00;
}
.sidebar-link.active {
    background:#FEF3C7;
    color:#735C00;
    font-weight:700;
    border-left-color:#735C00;
}
.sidebar-link i {
    width:18px;
    text-align:center;
    font-size:0.9rem;
}
</style>

{{-- ============================================================
     SCRIPT TOGGLE SIDEBAR
     ============================================================ --}}
<script>
function toggleSidebar() {
    var sb   = document.getElementById('sidebar');
    var ov   = document.getElementById('sidebarOverlay');
    var open = sb.style.transform === 'translateX(0px)' || sb.style.transform === 'translateX(0)';
    if (open) {
        sb.style.transform = 'translateX(-100%)';
        ov.style.display   = 'none';
    } else {
        sb.style.transform = 'translateX(0)';
        ov.style.display   = 'block';
    }
}
function tutupSidebar() {
    document.getElementById('sidebar').style.transform = 'translateX(-100%)';
    document.getElementById('sidebarOverlay').style.display = 'none';
}
</script>