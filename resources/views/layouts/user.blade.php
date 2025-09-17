<!-- resources/views/layouts/user.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Phát Store')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #f9fafb;
            --primary: #111827;
            --muted: #6b7280;
            --accent: linear-gradient(135deg, #6d28d9 0%, #06b6d4 100%);
            --accent-solid: #6d28d9;
            --glass-border: rgba(16,24,40,0.08);
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--primary);
            line-height: 1.6;
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--glass-border);
            padding: 12px 0;
        }
        .navbar-brand {
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.25rem;
        }
        .logo-badge {
            display: grid;
            place-items: center;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--accent);
            color: #fff;
            font-weight: 800;
            font-size: 1rem;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }
        .nav-link {
            font-weight: 500;
            border-radius: 8px;
            padding: 8px 14px !important;
            transition: 0.2s;
        }
        .nav-link:hover {
            background: rgba(0,0,0,0.05);
        }
        .nav-link.active {
            background: var(--accent-solid);
            color: #fff !important;
        }

        /* Flash message */
        .alert {
            border-radius: 10px;
            font-size: 0.95rem;
        }

        /* Nội dung */
        .container {
            max-width: 1100px;
        }

        /* Footer */
        footer {
            margin-top: 60px;
            padding: 28px 0;
            text-align: center;
            color: var(--muted);
            border-top: 1px solid var(--glass-border);
            font-size: 0.95rem;
        }
        footer a {
            color: var(--accent-solid);
            text-decoration: none;
            font-weight: 500;
        }
        footer a:hover {
            text-decoration: underline;
        }

        /* Button logout */
        .btn-link.nav-link {
            color: var(--primary);
            font-weight: 500;
            transition: 0.2s;
        }
        .btn-link.nav-link:hover {
            color: var(--accent-solid);
            background: rgba(0,0,0,0.05);
        }
    </style>

    @stack('styles')
</head>
<body>

@php
    // Đếm giỏ hàng
    $cartCount = 0;
    if(auth()->check()){
        $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity');
    } else {
        $cartCount = count(session('cart', []));
    }
@endphp

<nav class="navbar navbar-expand-lg shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <span class="logo-badge">PS</span> Phát Store
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Menu trái -->
            <ul class="navbar-nav me-auto">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Trang chủ</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">Đơn hàng</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}" href="{{ route('news.index') }}">Tin tức</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}" href="{{ route('chat.index') }}">
            💬 Chat
        </a>
    </li>
</ul>


            <!-- Menu phải -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('cart.index') ? 'active' : '' }}" href="{{ route('cart.index') }}">
                        🛒 Giỏ hàng ({{ $cartCount }})
                    </a>
                </li>

                @auth
                    <li class="nav-item">
                        <span class="nav-link">👋 {{ auth()->user()->name }}</span>
                    </li>
                    @if(auth()->user()->role === 'customer')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">📜 Lịch sử</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link p-0">🚪 Đăng xuất</button>
                        </form>
                    </li>
                @endauth

                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Đăng ký</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Đăng nhập</a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- Flash message -->
<div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
    @endif
</div>

<!-- Nội dung -->
<div class="container mt-4">
    @yield('content')
</div>

<footer>
    <p>© {{ date('Y') }} Phát Store &nbsp;|&nbsp; 
       <a href="#">Chính sách bảo mật</a> &nbsp;|&nbsp; 
       <a href="#">Liên hệ</a></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
