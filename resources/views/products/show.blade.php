@extends('layouts.user')

@section('title', $product->name)

@section('content')
<style>
:root {
    --bg: #f5f7fa;
    --card: #ffffff;
    --text: #212529;
    --primary: #5b86e5;
    --accent: #ff6a88;
    --accent-2: #ffc371;
    --radius: 15px;
    --shadow: 0 10px 30px rgba(0,0,0,0.15);
}

body { background: var(--bg); }

.container-custom {
    max-width: 1100px;
    margin: 30px auto;
    padding: 0 20px;
}

.product-show {
    display: flex;
    flex-wrap: wrap;
    background: var(--card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.product-images {
    flex: 1 1 45%;
    min-width: 300px;
    max-width: 500px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    margin: auto 0;
}

.product-images img {
    width: 100%;
    height: auto;
    object-fit: contain;
    border-radius: var(--radius);
    transition: transform 0.3s ease;
}

.product-images img:hover {
    transform: scale(1.05);
}

.badge {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 6px 14px;
    border-radius: 12px;
    color: #fff;
    font-weight: bold;
    font-size: 0.9rem;
}
.badge-discount { background: linear-gradient(135deg, var(--accent), var(--accent-2)); }
.badge-new { background: var(--primary); }

.product-info {
    flex: 1 1 55%;
    min-width: 300px;
    padding: 25px;
}

.product-info h1 {
    font-size: 2rem;
    margin-bottom: 15px;
}

.product-info .price {
    font-size: 1.8rem;
    font-weight: bold;
    color: #28a745;
    margin-bottom: 10px;
}
.product-info .old-price {
    font-size: 1.1rem;
    color: #888;
    text-decoration: line-through;
    margin-left: 12px;
}

.countdown {
    margin-bottom: 15px;
    color: #dc3545;
    font-weight: bold;
}

.btn-custom {
    border-radius: 30px;
    font-weight: 500;
    padding: 10px 25px;
    background: linear-gradient(45deg, #28a745, #2ecc71);
    color: #fff;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 10px;
}
.btn-custom:hover {
    transform: scale(1.05);
}

.review-summary {
    margin: 20px 0;
    font-size: 1.1rem;
}

.review-item {
    border-top: 1px solid #eee;
    padding: 10px 0;
}
.review-item:first-child { border-top: none; }
.review-item strong { display: block; margin-bottom: 5px; font-size: 0.95rem; }
.review-item p { margin: 0; font-size: 0.9rem; }
.review-item small { color: #888; }

ul.specs {
    margin-top: 20px;
    padding-left: 20px;
}
ul.specs li { margin-bottom: 8px; }

.product-description {
    margin-top: 20px;
    font-size: 1rem;
    line-height: 1.6;
    color: #333;
}

@media(max-width:992px){
    .product-show { flex-direction: column; }
    .product-images, .product-info { flex: 1 1 100%; max-width: 100%; }
    .product-images { margin-bottom: 20px; }
}
</style>

<div class="container-custom">
    <div class="product-show">
        <div class="product-images position-relative">
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
            @else
                <img src="https://via.placeholder.com/500x500" alt="No image">
            @endif

            @php
                $discount = ($product->old_price && $product->old_price > $product->price)
                            ? round((($product->old_price - $product->price)/$product->old_price)*100)
                            : null;
            @endphp

            @if($discount)
                <span class="badge badge-discount">-{{ $discount }}%</span>
            @elseif($product->is_new)
                <span class="badge badge-new">New</span>
            @endif
        </div>

        <div class="product-info">
            <h1>{{ $product->name }}</h1>
            <div class="price">
                {{ number_format($product->price,0,',','.') }} VNĐ
                @if($product->old_price)
                    <span class="old-price">{{ number_format($product->old_price,0,',','.') }} VNĐ</span>
                @endif
            </div>

            @if($product->sale_end)
                <div class="countdown" data-end="{{ $product->sale_end }}"></div>
            @endif

            @auth
<form action="{{ route('cart.add',$product->id) }}" method="POST" class="mb-3">
    @csrf
    <button type="submit" class="btn btn-custom"><i class="bi bi-cart-plus"></i> Thêm vào giỏ hàng</button>
</form>

{{-- Wishlist --}}
<form action="{{ route('wishlist.toggle',$product->id) }}" method="POST" class="mb-3">
    @csrf
    <button type="submit" class="btn btn-outline-danger" style="border-radius:30px; padding:10px 25px;">
        <i class="bi bi-heart"></i> 
        {{ auth()->user()->wishlist->contains($product->id) ? 'Xóa khỏi Yêu thích' : 'Thêm vào Yêu thích' }}
    </button>
</form>
@else
    <p>Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để thêm sản phẩm vào giỏ hàng hoặc yêu thích.</p>
@endauth

            {{-- Đánh giá trung bình --}}
            <div class="review-summary">
                <strong>Đánh giá trung bình:</strong>
                @php
                    $avg = $product->averageRating();
                    $fullStars = floor($avg);
                    $halfStar = $avg - $fullStars >= 0.5;
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                @endphp
                @for($i=0;$i<$fullStars;$i++) ⭐ @endfor
                @if($halfStar) ✨ @endif
                @for($i=0;$i<$emptyStars;$i++) ☆ @endfor
                <span>({{ number_format($avg,1) }}/5 - {{ $product->reviews->count() }} đánh giá)</span>
            </div>

            {{-- Thông số kỹ thuật --}}
            <ul class="specs">
                <li>Danh mục: {{ $product->category->name ?? 'Chưa phân loại' }}</li>
                <li>Số lượng còn: {{ $product->quantity }}</li>
                
            </ul>

            {{-- Mô tả sản phẩm --}}
            <div class="product-description">
                {!! $product->description ?? 'Chưa có mô tả chi tiết cho sản phẩm này.' !!}
            </div>
        </div>
    </div>

    {{-- Danh sách đánh giá --}}
    <h3 style="margin-top:30px;">Đánh giá sản phẩm</h3>
    @if($product->reviews->count() > 0)
    @foreach($product->reviews as $review)
    <div class="review-item">
        <strong>{{ $review->user->name }}</strong>
        @for($i=1;$i<=5;$i++)
            {!! $i <= $review->rating ? '⭐' : '☆' !!}
        @endfor
        <p>{{ $review->comment }}</p>
        <small>{{ $review->created_at->diffForHumans() }}</small>

        {{-- Hiển thị trả lời admin --}}
        @if($review->admin_reply)
            <div class="review-item" style="background:#f8f9fa; padding:10px; margin:5px 0; border-radius:8px;">
                <strong>Trả lời từ Admin:</strong>
                <p>{{ $review->admin_reply }}</p>
            </div>
        @endif
    </div>
@endforeach

    @else
        <p>Chưa có đánh giá nào.</p>
    @endif
    {{-- Gợi ý sản phẩm --}}
<h3 style="margin-top:40px;">Có thể bạn cũng thích</h3>
<div class="row">
    @foreach($relatedProducts as $item)
        <div class="col-md-3 mb-4">
            <div class="card h-100 text-center" style="border-radius:15px; overflow:hidden;">
                <a href="{{ route('products.show',$item->id) }}">
                    <img src="{{ $item->image ? asset('storage/'.$item->image) : 'https://via.placeholder.com/300x300' }}" 
                         class="card-img-top" alt="{{ $item->name }}" style="height:200px; object-fit:cover;">
                </a>
                <div class="card-body">
                    <h6 class="card-title">{{ $item->name }}</h6>
                    <p class="text-success fw-bold">{{ number_format($item->price,0,',','.') }} VNĐ</p>
                </div>
            </div>
        </div>
    @endforeach
</div>
</div>

<script>
// Countdown sale
document.querySelectorAll('.countdown').forEach(el => {
    const endTime = new Date(el.dataset.end).getTime();
    const timer = setInterval(() => {
        const now = new Date().getTime();
        const distance = endTime - now;
        if(distance <= 0){
            clearInterval(timer);
            el.textContent = "Hết hạn khuyến mãi";
            return;
        }
        const d = Math.floor(distance / (1000*60*60*24));
        const h = Math.floor((distance % (1000*60*60*24)) / (1000*60*60));
        const m = Math.floor((distance % (1000*60*60)) / (1000*60));
        const s = Math.floor((distance % (1000*60)) / 1000);
        el.textContent = `Kết thúc sau: ${d}d ${h}h ${m}m ${s}s`;
    },1000);
});
</script>
@endsection
