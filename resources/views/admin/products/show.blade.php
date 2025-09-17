@extends('layouts.admin')

@section('title', $product->name)

@section('content')
<style>
:root {
    --bg: #f5f7fa;
    --card: #ffffff;
    --card-overlay: rgba(255,255,255,0.85);
    --text: #212529;
    --text-invert: #fff;
    --primary: #5b86e5;
    --primary-2: #36d1dc;
    --accent: #ff6a88;
    --accent-2: #ffc371;
    --radius-lg: 18px;
    --shadow-sm: 0 4px 16px rgba(0,0,0,0.08);
    --shadow-md: 0 10px 30px rgba(0,0,0,0.15);
    --shadow-lg: 0 24px 60px rgba(15,18,32,0.2);
}

body { background: var(--bg); }
.container-custom { max-width: 960px; margin: 0 auto; padding: 20px; }

.header-section {
    position: relative;
    overflow: hidden;
    border-radius: var(--radius-lg);
    margin-bottom: 50px;
    box-shadow: var(--shadow-lg);
    background: linear-gradient(135deg, var(--primary), var(--primary-2));
    text-align: center;
    color: var(--text-invert);
    padding: 80px 20px;
}
.header-section::before {
    content: "";
    position: absolute; inset: 0;
    background: url('https://images.unsplash.com/photo-1612831455546-68f2122b0332?auto=format&fit=crop&w=1950&q=80') no-repeat center/cover;
    opacity: 0.15;
}
.header-section h1, .header-section p { position: relative; z-index: 1; }
.header-section h1 { font-size: 2.5rem; animation: fadeDown 1s ease forwards; }
.header-section p { animation: fadeDown 1.2s ease forwards; }
@keyframes fadeDown { 0% { opacity: 0; transform: translateY(-30px); } 100% { opacity: 1; transform: translateY(0); } }

.product-images { position: relative; }
.product-images img {
    width: 100%;
    border-radius: var(--radius-lg);
    margin-bottom: 20px;
    transition: transform 0.3s ease;
}
.product-images img:hover { transform: scale(1.05); }
.badge {
    position: absolute; top: 10px; left: 10px;
    background: var(--accent);
    color: white; padding: 6px 12px;
    border-radius: 10px;
    font-size: 0.8rem; font-weight: bold; text-transform: uppercase;
    animation: badgePulse 1.5s infinite;
}
.badge-discount {
    background: linear-gradient(135deg, var(--accent), var(--accent-2));
}
@keyframes badgePulse { 0% { transform: scale(1); } 50% { transform: scale(1.15); } 100% { transform: scale(1); } }

.nav-tabs .nav-link { color: var(--text); font-weight: 500; }
.nav-tabs .nav-link.active {
    background: var(--primary);
    color: white;
    border-radius: 8px;
}

#backTop {
    position: fixed;
    bottom: 20px; right: 20px;
    display: none;
    border: none;
    background: linear-gradient(45deg, var(--primary), var(--primary-2));
    color: white; padding: 12px 16px;
    border-radius: 50%;
    font-size: 18px;
    cursor: pointer;
    box-shadow: var(--shadow-md);
}
#backTop:hover { transform: scale(1.1); transition: 0.3s; }

.countdown {
    margin-top: 10px;
    font-size: 0.9rem;
    color: #dc3545;
    font-weight: bold;
}
</style>

<div class="container-custom">

    <div class="header-section mb-4">
        <h1>{{ $product->name }}</h1>
        <p>Khám phá chi tiết sản phẩm!</p>
    </div>

    <div class="product-images">
        @if($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
        @else
            <img src="https://via.placeholder.com/600x400" alt="No image">
        @endif

        @php
            $discount = ($product->old_price && $product->old_price > $product->price)
                        ? round((($product->old_price - $product->price) / $product->old_price) * 100)
                        : null;
        @endphp

        @if($discount)
            <span class="badge badge-discount">-{{ $discount }}%</span>
        @elseif($product->is_new)
            <span class="badge">New</span>
        @else
            <span class="badge">Hot</span>
        @endif
    </div>

    <div class="mb-3">
        <p class="fs-4 text-success fw-bold">
            {{ number_format($product->price,0,',','.') }} VNĐ
            @if($product->old_price)
                <span class="text-muted text-decoration-line-through ms-2">{{ number_format($product->old_price,0,',','.') }} VNĐ</span>
            @endif
        </p>
        @if($product->sale_end)
            <div class="countdown" data-end="{{ $product->sale_end }}"></div>
        @endif
    </div>

    {{-- Nút quay lại danh sách admin --}}
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mb-4">
        ⬅ Quay lại danh sách sản phẩm
    </a>

    {{-- Tabs chi tiết --}}
    <ul class="nav nav-tabs mb-3" id="productTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc" type="button">Mô tả</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="spec-tab" data-bs-toggle="tab" data-bs-target="#spec" type="button">Thông số kỹ thuật</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review" type="button">Đánh giá</button>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="desc">{!! $product->description !!}</div>
        <div class="tab-pane fade" id="spec">
            <ul>
                <li>Danh mục: {{ $product->category->name ?? 'Chưa phân loại' }}</li>
                <li>Số lượng còn: {{ $product->quantity }}</li>
                <li>Giá: {{ number_format($product->price,0,',','.') }} VNĐ</li>
            </ul>
        </div>
        <div class="tab-pane fade" id="review">
            <p>Chưa có đánh giá nào.</p>
        </div>
    </div>

</div>

<button id="backTop" title="Back to Top">⬆</button>

<script>
// Back to Top
const backTop = document.getElementById('backTop');
window.addEventListener('scroll', () => {
    backTop.style.display = window.scrollY > 200 ? 'block' : 'none';
});
backTop.onclick = () => window.scrollTo({ top:0, behavior:'smooth' });

// Countdown sale
document.querySelectorAll('.countdown').forEach(el => {
    const endTime = new Date(el.dataset.end).getTime();
    const timer = setInterval(() => {
        const now = new Date().getTime();
        const distance = endTime - now;
        if (distance <= 0) {
            clearInterval(timer);
            el.textContent = "Hết hạn khuyến mãi";
            return;
        }
        const d = Math.floor(distance / (1000*60*60*24));
        const h = Math.floor((distance % (1000*60*60*24)) / (1000*60*60));
        const m = Math.floor((distance % (1000*60*60)) / (1000*60));
        const s = Math.floor((distance % (1000*60)) / 1000);
        el.textContent = `Kết thúc sau: ${d}d ${h}h ${m}m ${s}s`;
    }, 1000);
});
</script>
@endsection
