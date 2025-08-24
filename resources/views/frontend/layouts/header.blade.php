<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <img src="{{ asset('backend/img/logo_primary.png') }}" alt="Logo Prolific" class="logo-img">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('product-grids') }}">Toko</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('about-us') }}">Tentang Kami</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('wishlist') }}">Favorit</a></li>
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.order.index') }}">Pesanan Saya</a></li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login.form') }}">Pesanan Saya</a></li>
                @endauth

            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item position-relative">
                    <a class="nav-link" href="{{ route('cart') }}">
                        <i class="fa-solid fa-shopping-cart"></i>
                        <span id="cart-count" class="badge bg-danger badge-sm">{{ Helper::cartCount() }}</span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        @auth
                            @if (Auth::user()->role == 'admin')
                                <li><a class="dropdown-item" href="{{ route('admin') }}" target="_blank">Admin Dashboard</a>
                                </li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('user') }}">Akun Saya</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('user.logout') }}">Logout</a></li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('login.form') }}">Masuk</a></li>
                            <li><a class="dropdown-item" href="{{ route('register.form') }}">Register</a></li>
                        @endauth
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .logo-img {
        height: 30px;
        width: auto;
    }

    .badge-sm {
        font-size: 0.6rem;
        padding: 0.2em 0.4em;
    }

    @media (max-width: 991px) {
        .navbar-collapse {
            background-color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            margin-top: 0.5rem;
        }

        .navbar-nav .nav-item {
            border-bottom: 1px solid #f8f9fa;
            padding: 0.5rem 0;
        }

        .navbar-nav .nav-item:last-child {
            border-bottom: none;
        }

        .navbar-nav .nav-link {
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
        }

        .navbar-nav .dropdown-menu {
            border: none;
            box-shadow: none;
        }

        .navbar-nav .dropdown-item {
            padding: 0.5rem 1rem;
        }
    }

    .nav-link.active {
        font-weight: 600;
        color: #000 !important;
    }

    .nav-link:hover {
        color: #000 !important;
    }
</style>
