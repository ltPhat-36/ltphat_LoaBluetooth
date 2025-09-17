<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #0d6efd, #6610f2);
            color: #fff;
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: 240px;
            padding-top: 60px;
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #fff;
            text-decoration: none;
            transition: background .3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
        }
        .content {
    margin-left: 240px;
    padding: 50px 20px 20px 20px; /* padding-top: 120px để tránh topbar */
}

        .topbar {
            position: fixed;
            top: 0; left: 240px; right: 0;
            height: 60px;
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 50px;
            z-index: 1000;
        }
        footer {
            margin-top: 30px;
            padding: 15px 0;
            background: #fff;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center mb-4">
            <h4 class="fw-bold">QUẢN TRỊ</h4>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
        <a href="{{ route('admin.categories.index') }}">
            <i class="bi bi-folder-fill me-2"></i> Danh mục
        </a>
        <a href="{{ route('admin.products.index') }}">
            <i class="bi bi-box-seam me-2"></i> Sản phẩm
        </a>
        <a href="{{ route('admin.users.index') }}">
            <i class="bi bi-people-fill me-2"></i> Người dùng
        </a>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
    <i class="bi bi-basket-fill me-2"></i> Đơn hàng
</a>
<a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
    <i class="bi bi-bar-chart-line-fill me-2"></i> Báo cáo
</a>
<a href="{{ route('admin.reports.charts') }}" class="{{ request()->routeIs('admin.reports.charts') ? 'active' : '' }}">
    <i class="bi bi-graph-up-arrow me-2"></i> Biểu đồ
</a>
<a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
    <i class="bi bi-chat-left-text-fill me-2"></i> Đánh giá
    @if($unreadReviewsCount > 0)
        <span class="badge bg-danger ms-2">{{ $unreadReviewsCount }}</span>
    @endif
</a>
<a href="{{ route('admin.chat.index') }}" class="{{ request()->routeIs('admin.chat.*') ? 'active' : '' }}">
    <i class="bi bi-chat-dots me-2"></i> Tin nhắn
</a>

        <a href="#">
            <i class="bi bi-gear-fill me-2"></i> Cài đặt
        </a>
    </div>

    <!-- Topbar -->
    <div class="topbar">
        <span class="fw-bold">Xin chào, {{ auth()->user()->name }}</span>
        <div>
            <a href="#" class="btn btn-sm btn-outline-secondary"><i class="bi bi-bell"></i></a>
            <a href="{{ route('logout') }}" class="btn btn-sm btn-danger"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i> Đăng xuất
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        @yield('content')
        <footer>
            &copy; {{ date('Y') }} - Trang quản trị bởi <strong>Laravel</strong>.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
