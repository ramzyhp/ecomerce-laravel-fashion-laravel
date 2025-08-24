<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar"
    style="border-radius: 0px 15px 10px 0px;">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('user-profile') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-store"></i>
        </div>
        <div class="sidebar-brand-text mx-2">User Profile</div>
    </a>

    <hr class="sidebar-divider my-0">
    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Akun Saya
    </div>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('user') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('user.order.index') }}">
            <i class="fas fa-shopping-cart"></i>
            <span>Pesanan Saya</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('user.productreview.index') }}">
            <i class="fas fa-comments"></i>
            <span>Ulasan</span></a>
    </li>


    <hr class="sidebar-divider">

    <li class="nav-item">
        <a class="nav-link" href="#"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </li>
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
