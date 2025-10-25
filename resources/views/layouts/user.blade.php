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
        body { margin: 0; font-family: 'Inter', sans-serif; background: var(--bg); color: var(--primary); line-height:1.6; }
        .navbar { background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(8px); border-bottom:1px solid var(--glass-border); padding:12px 0; }
        .navbar-brand { font-weight:700; display:flex; align-items:center; gap:10px; font-size:1.25rem; }
        .logo-badge { display:grid; place-items:center; width:40px; height:40px; border-radius:12px; background:var(--accent); color:#fff; font-weight:800; font-size:1rem; box-shadow:0 3px 6px rgba(0,0,0,0.1);}
        .nav-link { font-weight:500; border-radius:8px; padding:8px 14px !important; transition:0.2s; }
        .nav-link:hover { background: rgba(0,0,0,0.05);}
        .nav-link.active { background: var(--accent-solid); color:#fff !important; }
        .alert { border-radius:10px; font-size:0.95rem; }
        .container { max-width:1100px; }
        footer { margin-top:60px; padding:28px 0; text-align:center; color:var(--muted); border-top:1px solid var(--glass-border); font-size:0.95rem; }
        footer a { color:var(--accent-solid); text-decoration:none; font-weight:500; }
        footer a:hover { text-decoration:underline; }
        .btn-link.nav-link { color:var(--primary); font-weight:500; transition:0.2s; }
        .btn-link.nav-link:hover { color:var(--accent-solid); background: rgba(0,0,0,0.05);}
    </style>

    @stack('styles')
</head>
<body>

@php
    $cartCount = auth()->check() ? \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity') : count(session('cart', []));
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
    <a class="nav-link {{ request()->routeIs('frontend.products.index') ? 'active' : '' }}" 
       href="{{ route('frontend.products.index') }}">
        Sản phẩm
    </a>
</li>


            </ul>
            <ul class="navbar-nav">
            <li class="nav-item cart-icon" style="position:relative; cursor:pointer;">
    <a class="nav-link {{ request()->routeIs('cart.index') ? 'active' : '' }}" href="{{ route('cart.index') }}">
        🛒 Giỏ hàng
        <span class="cart-count" style="position:absolute; top:-8px; right:-8px; 
              background:#ef4444; color:#fff; border-radius:50%; padding:2px 6px; font-size:0.8rem;">
            {{ $cartCount ?? 0 }}
        </span>
    </a>
</li>

                @auth
                <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        👋 {{ auth()->user()->name }}
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
    <li>
        <a class="dropdown-item" href="{{ route('user.profile.index') }}">🧑 Hồ sơ cá nhân</a>
    </li>
    <li>
        <a class="dropdown-item" href="{{ route('wishlist.index') }}">❤️ Yêu thích</a>
    </li>
    <li>
        <a class="dropdown-item" href="{{ route('game.index') }}">🎮 Mini Game</a>
    </li>
    <li><hr class="dropdown-divider"></li>
    <li>
        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="dropdown-item">🚪 Đăng xuất</button>
        </form>
    </li>
</ul>
`   
</li>

                @endauth
                @guest
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Đăng ký</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Đăng nhập</a></li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-3">
    @if(session('success'))<div class="alert alert-success shadow-sm">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger shadow-sm">{{ session('error') }}</div>@endif
</div>

<div class="container mt-4">@yield('content')</div>

<footer>
    <p>© {{ date('Y') }} Phát Store &nbsp;|&nbsp; <a href="#">Chính sách bảo mật</a> &nbsp;|&nbsp; <a href="#">Liên hệ</a></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

<!-- Floating Chat Button -->
<div id="chat-widget" style="position:fixed; bottom:20px; right:20px; z-index:9999;">
    <button id="chat-toggle" style="width:60px; height:60px; border-radius:50%; background:#5b86e5; border:none; color:#fff; font-size:28px; cursor:pointer; box-shadow:0 4px 12px rgba(0,0,0,0.2);">💬</button>

    <div id="chat-box" style="display:none; width:320px; max-width:90vw; height:400px; 
    background:#fff; border-radius:12px; box-shadow:0 8px 25px rgba(0,0,0,0.2); 
    overflow:hidden; display:flex; flex-direction:column; margin-bottom:10px;">

        <div id="chat-header" style="background:#5b86e5; color:#fff; padding:12px; font-weight:600;">Chat với Admin</div>
        <div id="chat-messages" style="flex:1; padding:12px; overflow-y:auto; background:#f5f5f5;"></div>
        <form id="chat-form" style="display:flex; border-top:1px solid #eee;">
            <input type="text" id="chat-input" placeholder="Nhập tin nhắn..." style="flex:1; padding:8px; border:none; outline:none;">
            <button type="submit" style="padding:0 12px; border:none; background:#5b86e5; color:#fff; cursor:pointer;">Gửi</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const USER_ID = @json(Auth::id());
    const FETCH_URL = "{{ route('chat.fetch') }}";
    const SEND_URL = "{{ route('chat.send') }}";
    let ADMIN_ID = null; 

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

    const $chatBox = $('#chat-box');
    const $chatMessages = $('#chat-messages');
    const $chatInput = $('#chat-input');

    // Mặc định đóng chat box
    $chatBox.hide();

    // Toggle chat box
    $('#chat-toggle').click(() => {
        if($chatBox.is(':visible')) {
            $chatBox.hide();
        } else {
            $chatBox.css('display','flex'); // mở flex
            loadMessages();
        }
    });

    // Load messages từ server
    function loadMessages() {
        $.get(FETCH_URL, { customer_id: USER_ID })
            .done(renderMessages)
            .fail(err => console.error(err));
    }

    // Hiển thị tin nhắn
    function renderMessages(data) {
        let html = '';
        data.forEach(msg => {
            if(msg.sender_id == USER_ID){
                html += `<div style="text-align:right; margin-bottom:6px;">
                            <span style="background:#5b86e5; color:#fff; padding:6px 10px; border-radius:12px; display:inline-block;">${msg.message}</span>
                         </div>`;
            } else {
                html += `<div style="text-align:left; margin-bottom:6px;">
                            <span style="background:#eee; padding:6px 10px; border-radius:12px; display:inline-block;">${msg.message}</span>
                         </div>`;
            }
        });
        $chatMessages.html(html);
        $chatMessages.scrollTop($chatMessages[0].scrollHeight);
    }

    // Gửi tin nhắn
    $('#chat-form').on('submit', function(e){
        e.preventDefault();
        const text = $chatInput.val().trim();
        if(!text) return;

        $.post(SEND_URL, { message: text, receiver_id: ADMIN_ID })
            .done(() => { 
                $chatInput.val(''); 
                loadMessages(); 
            })
            .fail(err => { 
                if(err.status === 419) alert('Phiên làm việc hết hạn.'); 
                console.error(err); 
            });
    });

    // Auto refresh messages khi chat mở
    setInterval(() => { 
        if($chatBox.is(':visible')) loadMessages(); 
    }, 3000);
});
</script>

</body>
</html>
