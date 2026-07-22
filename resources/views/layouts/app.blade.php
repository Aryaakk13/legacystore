<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LegacySMP Store') - LegacySMP Store</title>

    <!-- Cloudflare Turnstile -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <i class="bi bi-shop fs-3 me-2"></i>
                <span class="fw-bold">Legacy<span class="text-warning">SMP</span> Store</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('shop.index') || request()->routeIs('home') ? 'active' : '' }}" href="{{ route('shop.index') }}">
                            <i class="bi bi-grid-fill"></i> Shop
                        </a>
                    </li>
                    @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('player.dashboard') ? 'active' : '' }}" href="{{ route('player.dashboard') }}">
                            <i class="bi bi-person-fill"></i> Dashboard
                        </a>
                    </li>
                    @endauth
                </ul>
                <ul class="navbar-nav align-items-center">
                    <!-- Cart -->
                    <li class="nav-item me-2">
                        <a class="nav-link position-relative" href="{{ route('shop.checkout') }}" title="Cart">
                            <i class="bi bi-cart-fill fs-5"></i>
                            <span class="cart-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
                                0
                            </span>
                        </a>
                    </li>

                    @auth
                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="{{ Auth::user()->avatar_url ?? 'https://crafatar.com/avatars/' . (Auth::user()->primaryMcAccount->username ?? 'steve') . '?size=32&overlay' }}"
                                 alt="Avatar" class="rounded-circle me-2" width="28" height="28">
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('player.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('player.profile') }}"><i class="bi bi-person-gear"></i> Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-warning btn-sm text-dark fw-bold" href="{{ route('register') }}">Register</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if (session('success') || session('error') || session('info'))
    <div class="container mt-3">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
    </div>
    @endif

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="bi bi-shop me-2"></i>LegacySMP Store</h5>
                    <p class="text-muted">The official store for LegacySMP. Purchase ranks, items, keys, and more to enhance your gameplay experience.</p>
                    <div class="d-flex gap-2">
                        <a href="https://discord.gg/legacysmp" class="btn btn-outline-light btn-sm" target="_blank"><i class="bi bi-discord"></i></a>
                        <a href="https://twitter.com/legacysmp" class="btn btn-outline-light btn-sm" target="_blank"><i class="bi bi-twitter-x"></i></a>
                        <a href="https://youtube.com/@legacysmp" class="btn btn-outline-light btn-sm" target="_blank"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>Shop</h6>
                    <ul class="list-unstyled text-muted">
                        <li><a href="#" class="text-decoration-none text-muted">Ranks</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Items</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Crates</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Keys</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>Support</h6>
                    <ul class="list-unstyled text-muted">
                        <li><a href="#" class="text-decoration-none text-muted">FAQ</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Terms of Service</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Privacy Policy</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Refund Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h6>Server Status</h6>
                    <div class="server-status p-3 bg-dark border border-secondary rounded">
                        <div class="d-flex align-items-center">
                            <span class="status-indicator online me-2"></span>
                            <span class="fw-bold" id="server-status-text">Checking...</span>
                        </div>
                        <small class="text-muted">play.legacysmp.com</small>
                        <div class="mt-2">
                            <span class="badge bg-success me-1" id="online-players">0</span>
                            <span class="text-muted">players online</span>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="text-center text-muted">
                <small>&copy; {{ date('Y') }} LegacySMP. All rights reserved. Not affiliated with Mojang AB.</small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Alpine.js for reactive components -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Custom Scripts -->
    <script src="{{ asset('js/script.js') }}"></script>

    @stack('scripts')
</body>
</html>

