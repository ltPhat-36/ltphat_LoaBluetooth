<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Phát Store')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        /* Sidebar */
        .sidebar {
            height: 100vh;
            background: #212529;
            color: #fff;
            position: fixed;
            top: 0; left: 0;
            width: 240px;
            transition: all 0.3s;
        }
        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background: #495057;
            color: #fff;
        }
        .sidebar .active {
            background: #0d6efd;
            color: #fff;
        }
        .content {
            margin-left: 240px;
            padding: 20px;
        }
        /* Navbar */
        .navbar {
            margin-left: 240px;
            background: #fff;
            border-bottom: 1px solid #dee2e6;
        }
        /* Footer */
        footer {
            margin-left: 240px;
            background: #fff;
            border-top: 1px solid #dee2e6;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
        /* Dark mode */
        body.dark-mode {
            background-color: #212529;
            color: #f8f9fa;
        }
        body.dark-mode .navbar, 
        body.dark-mode footer {
            background: #343a40;
            color: #adb5bd;
        }
        body.dark-mode .sidebar {
            background: #000;
        }
        body.dark-mode .sidebar a {
            color: #adb5bd;
        }
        body.dark-mode .sidebar a:hover {
            background: #343a40;
            color: #fff;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column p-3">
        <h3 class="text-white mb-4">🛍️ Phát Store</h3>
        <a href="{{ route('categories.index') }}" class="{{ request()->is('categories*') ? 'active' : '' }}">
            <i class="fa fa-tags"></i> Danh mục
        </a>
        <a href="{{ route('products.index') }}" class="{{ request()->is('products*') ? 'active' : '' }}">
            <i class="fa fa-box"></i> Sản phẩm
        </a>
        <a href="#">
            <i class="fa fa-users"></i> Khách hàng
        </a>
        <a href="#">
            <i class="fa fa-cash-register"></i> Đơn hàng
        </a>
        <a href="#">
            <i class="fa fa-cog"></i> Cài đặt
        </a>
        <hr class="text-secondary">
        <button id="darkModeToggle" class="btn btn-sm btn-outline-light">
            <i class="fa fa-moon"></i> Dark Mode
        </button>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg shadow-sm px-3">
        <div class="container-fluid">
            <button class="btn btn-outline-primary d-lg-none" id="sidebarToggle">
                <i class="fa fa-bars"></i>
            </button>
            <span class="navbar-brand fw-bold">@yield('title', 'Phát Store')</span>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fa fa-bell"></i></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="fa fa-user-circle"></i> Quản trị viên
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Hồ sơ</a></li>
                        <li><a class="dropdown-item" href="#">Cài đặt</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fa fa-sign-out-alt"></i> Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Content -->
    <main class="content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; {{ date('Y') }} Phát Store. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Dark mode toggle -->
    <script>
        document.getElementById("darkModeToggle").addEventListener("click", function () {
            document.body.classList.toggle("dark-mode");
        });
    </script>
</body>
</html>
