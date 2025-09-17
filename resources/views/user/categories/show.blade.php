@extends('layouts.user')

@section('title', $category->name)

@section('content')
<div class="container" style="padding: 24px 0;">

  <!-- Breadcrumb / Back link -->
  <div style="margin-bottom: 20px; font-size: 14px;">
    <a href="{{ route('home') }}" style="text-decoration: none; color: #3490dc;">← Quay lại danh sách danh mục</a>
  </div>

  <!-- Category Title -->
  <h2 class="section-title" style="font-size: 28px; font-weight:700; margin-bottom:8px; color:#2c3e50;">
    {{ $category->name }}
  </h2>
  <p class="section-sub" style="font-size:16px; color:#7f8c8d; margin-bottom:24px;">
    Các sản phẩm thuộc danh mục này
  </p>

  <!-- Products Grid -->
  @if($category->products->count())
    <div class="products-grid" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap:20px; align-items:stretch;">
      @foreach($category->products as $product)
        <div class="product-card" style="display:flex; flex-direction:column; border:1px solid #e0e0e0; border-radius:12px; overflow:hidden; transition: transform 0.2s; background:#fff;">
          
          <!-- Image -->
          <div class="product-img" style="text-align:center; padding:12px; flex-shrink:0;">
            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x200' }}" 
                 alt="{{ $product->name }}" 
                 style="width:100%; height:200px; object-fit:cover; border-radius:8px;">
          </div>

          <!-- Body -->
          <div class="product-body" style="padding:12px; display:flex; flex-direction:column; flex-grow:1;">
            <div class="product-title" style="font-weight:600; font-size:16px; color:#34495e; margin-bottom:6px; min-height:48px;">
              {{ $product->name }}
            </div>
            <div class="product-price" style="color:#e74c3c; font-weight:600; margin-bottom:12px;">
              {{ number_format($product->price, 0, ',', '.') }}₫
            </div>
            <div style="margin-top:auto;">
              <a href="{{ route('frontend.products.show', $product->id) }}" 
                 class="btn btn-accent" 
                 style="display:inline-block; width:100%; text-align:center; padding:8px 0; background:#3490dc; color:#fff; border-radius:6px; text-decoration:none; font-weight:600; transition: background 0.2s;">
                 Xem chi tiết
              </a>
            </div>
          </div>

        </div>
      @endforeach
    </div>
  @else
    <div class="empty" style="padding:24px; background:#f5f5f5; border-radius:12px; text-align:center; color:#7f8c8d; font-size:16px;">
      Hiện tại chưa có sản phẩm nào trong danh mục này.
    </div>
  @endif
</div>

<!-- Hover effect -->
<style>
  .product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  }
</style>
@endsection
