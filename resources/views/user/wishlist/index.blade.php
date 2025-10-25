@extends('layouts.user')

@section('title','Danh sách Yêu thích')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 fw-bold text-center text-primary">💖 Danh sách Yêu thích</h2>

    @if($wishlist->count() > 0)
        <div class="products-grid">
            @foreach($wishlist as $item)
                @php
                    $price = $item->price ?? 0;
                    $old = $item->old_price ?? null;
                    $is_new = !empty($item->is_new);
                    $hasOld = $old && $old > $price;
                    $discount = $hasOld ? max(0, round((1-($price/$old))*100)) : null;
                    $img = $item->image ? asset('storage/'.$item->image) : 'https://via.placeholder.com/800x600?text=No+Image';
                @endphp

                <div class="product-card wishlist-card" data-id="{{ $item->id }}" data-name="{{ e($item->name) }}" data-desc="{{ e($item->description ?? '') }}" data-price="{{ $price }}" data-old="{{ $old ?? '' }}" data-img="{{ $img }}" data-is-new="{{ $is_new ? '1' : '0' }}">
                    <div class="badge-wrap">
                        @if($hasOld && $discount>0)
                            <span class="badge badge-sale">-{{ $discount }}%</span>
                        @endif
                        @if($is_new)
                            <span class="badge badge-new">Mới về</span>
                        @endif
                    </div>
                    <div class="card-top-right">
                        <div class="icon-btn icon-wishlist" title="Yêu thích" data-id="{{ $item->id }}">❤</div>
                    </div>
                    <div class="product-img">
                        <img src="{{ $img }}" alt="{{ $item->name }}" loading="lazy">
                    </div>
                    <div class="product-body">
                        <div class="product-title">{{ $item->name }}</div>
                        <div class="price-row">
                            <div class="product-price">{{ number_format($price,0,',','.') }}₫</div>
                            @if($hasOld)
                                <div class="old-price">{{ number_format($old,0,',','.') }}₫</div>
                            @endif
                        </div>
                        <div class="product-actions">
                            <a href="{{ route('frontend.products.show',$item->id) }}" class="btn btn-outline">🔍 Xem</a>
                            <form action="{{ route('cart.add',$item->id) }}" method="POST" class="add-to-cart-form" style="margin:0;">
                                @csrf
                                <button type="submit" class="btn btn-accent" data-img="{{ $img }}">🛒 Thêm</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info text-center mt-4">
            Bạn chưa thêm sản phẩm nào vào Yêu thích.
        </div>
    @endif
</div>

{{-- Custom CSS đồng bộ với product-card --}}
<style>
/* Grid chung */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
}

/* Card */
.wishlist-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0,0,0,.08);
    transition: transform 0.28s cubic-bezier(.2,.8,.2,1), box-shadow 0.28s;
    display: flex;
    flex-direction: column;
    position: relative;
}
.wishlist-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,.12);
}

/* Hình ảnh */
.product-img img, .wishlist-img {
    width: 100%;
    height: 260px;
    object-fit: cover;
    transition: transform 0.35s;
}
.wishlist-card:hover .product-img img, 
.wishlist-card:hover .wishlist-img {
    transform: scale(1.08);
}

/* Badges */
.badge-wrap {
    position: absolute;
    left: 12px;
    top: 12px;
    display: flex;
    gap: 8px;
    z-index: 2;
}
.badge {
    font-weight: 700;
    font-size: .85rem;
    padding: 6px 12px;
    border-radius: 14px;
    color: #fff;
    text-transform: uppercase;
    box-shadow: 0 4px 18px rgba(0,0,0,.1);
}
.badge-sale {
    background: linear-gradient(90deg,#ef4444,#f97316);
}
.badge-new {
    background: linear-gradient(90deg,#4f46e5,#3b82f6);
}

/* Top-right icons */
.card-top-right {
    position: absolute;
    right: 12px;
    top: 12px;
    display: flex;
    gap: 8px;
    z-index: 2;
}
.icon-btn {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    background: rgba(255,255,255,.95);
    border: 1px solid rgba(2,6,23,.05);
    cursor: pointer;
    transition: .15s;
}
.icon-wishlist.active {
    color: #ef4444;
    transform: scale(1.2);
}

/* Card body */
.product-body {
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    flex: 1;
}
.product-title {
    font-weight: 700;
    font-size: 1.1rem;
    line-height: 1.2;
}
.price-row {
    display: flex;
    gap: 12px;
    align-items: center;
    margin-top: 6px;
    flex-wrap: wrap;
}
.product-price {
    font-weight: 800;
    color: #10b981;
    font-size: 1.2rem;
}
.old-price {
    color: #94a3b8;
    text-decoration: line-through;
    font-weight: 600;
    font-size: 1rem;
}

/* Buttons */
.product-actions {
    display: flex;
    gap: 10px;
    margin-top: 12px;
}
.btn {
    flex: 1;
    padding: 12px 14px;
    border-radius: 12px;
    font-weight: 700;
    border: none;
    text-align: center;
    transition: .18s;
    font-size: .95rem;
    cursor: pointer;
    display: inline-block;
}
.btn-outline {
    background: transparent;
    border: 1px solid rgba(14,165,233,.2);
    color: #1e293b;
}
.btn-outline:hover {
    background: #f1f5f9;
}
.btn-accent {
    background: linear-gradient(135deg,#4f46e5,#3b82f6);
    color: #fff;
    box-shadow: 0 8px 25px rgba(59,130,246,.18);
}

/* Wishlist button riêng */
.wishlist-btn {
    border-radius: 25px;
    font-weight: 500;
    transition: background 0.3s;
}
.wishlist-btn:hover {
    background: #28a745;
    color: #fff;
}

/* Responsive */
@media(max-width:900px){
    .product-img img, .wishlist-img {
        height: 220px;
    }
}
</style>
@endsection
