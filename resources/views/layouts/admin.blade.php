<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - LegacySMP Admin</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Admin Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <div class="d-flex" style="min-height: 100vh;">
        <!-- Sidebar -->
        <nav class="admin-sidebar bg-dark text-white d-flex flex-column flex-shrink-0 p-3" style="width: 260px;">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 text-white text-decoration-none">
                <i class="bi bi-shield-fill fs-3 me-2"></i>
                <span class="fs-5 fw-bold">LegacySMP <span class="text-warning">Admin</span></span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.statistics') }}"
                       class="nav-link text-white {{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-fill me-2"></i> Statistics
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users') }}"
                       class="nav-link text-white {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill me-2"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.orders') }}"
                       class="nav-link text-white {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                        <i class="bi bi-cart-check-fill me-2"></i> Orders
                    </a>
                </li>
                <hr>
                <li class="nav-item">
                    <span class="nav-link text-muted small">Products</span>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.products.create') }}"
                       class="nav-link text-white {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                        <i class="bi bi-plus-circle me-2"></i> Add Product
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('shop.index') }}"
                       class="nav-link text-white">
                        <i class="bi bi-grid-fill me-2"></i> View Shop
                    </a>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown">
                    <img src="{{ Auth::user()->avatar_url ?? 'https://crafatar.com/avatars/steve?size=32&overlay' }}"
                         alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong>{{ Auth::user()->name }}</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('player.dashboard') }}"><i class="bi bi-person"></i> Player Dashboard</a></li>
                    <li><a class="dropdown-item" href="{{ route('player.profile') }}"><i class="bi bi-gear"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-grow-1 d-flex flex-column">
            <!-- Top Nav -->
            <nav class="navbar navbar-expand navbar-light bg-white shadow-sm px-4">
                <div class="container-fluid">
                    <span class="navbar-text">
                        @yield('page-title', 'Dashboard')
                    </span>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-primary" id="live-time"></span>
                    </div>
                </div>
            </nav>

            <!-- Flash Messages -->
            <div class="px-4 pt-3">
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
            </div>

            <!-- Content -->
            <div class="p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <script>
        // Live clock for admin
        function updateClock() {
            const now = new Date();
            document.getElementById('live-time').textContent = now.toLocaleString('id-ID', {
                dateStyle: 'full',
                timeStyle: 'medium'
            });
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>

    @stack('scripts')
</body>
</html>

