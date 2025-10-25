@extends('layouts.user')
@section('title', 'Trang chủ')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
<style>
:root{
  --bg:#f8f9fa; --card:#fff; --text:#1e293b; --muted:#6b7280;
  --accent:#4f46e5; --accent2:#3b82f6; --success:#10b981; --radius:16px;
  --shadow:0 6px 20px rgba(0,0,0,.08); --shadow-lg:0 12px 40px rgba(0,0,0,.12);
  --transition:.28s;
}
body{background:var(--bg);color:var(--text);font-family:'Inter',sans-serif;margin:0;line-height:1.5;scroll-behavior:smooth;}
.banner-welcome{
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    color:#fff;
    text-align:center;
    padding:60px 20px;
    border-radius:var(--radius);
    margin-bottom:36px;
    box-shadow:var(--shadow);
    position:relative;
    overflow:hidden;
}
.banner-welcome::after{
    content:'';
    position:absolute;
    top:0; left:0; width:100%; height:100%;
    background:radial-gradient(circle at top left, rgba(255,255,255,.2), transparent 70%);
    pointer-events:none;
}
.banner-welcome h1{font-size:3rem;font-weight:900;margin:0;}
.banner-welcome p{margin-top:12px;font-size:1.3rem;font-weight:500;}
.banner-welcome button{
    margin-top:24px;
    padding:14px 28px;
    font-size:1.1rem;
    background: linear-gradient(135deg,#f97316,#facc15);
    color:#1e293b;
    font-weight:700;
    border:none;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,.2);
    cursor:pointer;
    transition: all 0.3s ease;
}
.banner-welcome button:hover{transform:scale(1.05);}
.section-title{text-align:center;font-size:2.5rem;font-weight:800;margin:48px 0 12px;}
.section-sub{color:var(--muted);font-size:1rem;margin-top:4px;}
.products-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:24px;}
.product-card{
    background:var(--card);
    border-radius:var(--radius);
    overflow:hidden;
    box-shadow:var(--shadow);
    transition: transform var(--transition) cubic-bezier(.2,.8,.2,1),box-shadow var(--transition);
    display:flex;
    flex-direction:column;
    position:relative;
}
.product-card:hover{transform:translateY(-8px);box-shadow:var(--shadow-lg);}
.product-img img{width:100%;height:260px;object-fit:cover;transition:transform .35s;}
.product-card:hover .product-img img{transform:scale(1.08);}
.badge-wrap{position:absolute;left:12px;top:12px;display:flex;gap:8px;z-index:2;}
.badge{font-weight:700;font-size:.85rem;padding:6px 12px;border-radius:14px;color:#fff;text-transform:uppercase;box-shadow:0 4px 18px rgba(0,0,0,.1);}
.badge-sale{background:linear-gradient(90deg,#ef4444,#f97316);}
.badge-new{background:linear-gradient(90deg,var(--accent),var(--accent2));}
.product-body{padding:16px;display:flex;flex-direction:column;gap:10px;flex:1;}
.product-title{font-weight:700;font-size:1.1rem;line-height:1.2;}
.price-row{display:flex;gap:12px;align-items:center;margin-top:6px;flex-wrap:wrap;}
.product-price{font-weight:800;color:var(--success);font-size:1.2rem;}
.old-price{color:#94a3b8;text-decoration:line-through;font-weight:600;font-size:1rem;}
.product-actions{display:flex;gap:10px;margin-top:12px;}
.btn{flex:1;padding:12px 14px;border-radius:12px;font-weight:700;border:none;text-align:center;transition:.18s;font-size:.95rem;cursor:pointer;display:inline-block;}
.btn-outline{background:transparent;border:1px solid rgba(14,165,233,.2);color:var(--text);}
.btn-outline:hover{background:#f1f5f9;}
.btn-accent{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;box-shadow:0 8px 25px rgba(59,130,246,.18);}
.card-top-right{position:absolute;right:12px;top:12px;display:flex;gap:8px;z-index:2;}
.icon-btn{width:38px;height:38px;border-radius:12px;display:grid;place-items:center;background:rgba(255,255,255,.95);border:1px solid rgba(2,6,23,.05);cursor:pointer;transition:.15s;}
.icon-heart.active{color:#ef4444;transform:scale(1.2);}
.qv-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.5);display:none;align-items:center;justify-content:center;z-index:9999;padding:20px;}
.qv-backdrop.show{display:flex;}
.qv-modal{width:100%;max-width:1000px;background:var(--card);border-radius:var(--radius);display:grid;grid-template-columns:1fr 1fr;overflow:hidden;box-shadow:var(--shadow-lg);}
.qv-left{padding:20px;background:#f8f9fa;display:flex;align-items:center;justify-content:center;}
.qv-left img{max-width:100%;max-height:520px;object-fit:contain;border-radius:12px;}
.qv-right{padding:24px;display:flex;flex-direction:column;gap:12px;}
.fly-img {
  position: fixed;
  width: 120px;
  height: 120px;
  object-fit: cover;
  border-radius: 12px;
  z-index: 9999;
  pointer-events: none;
  transition: transform 0.8s ease, opacity 0.8s ease;
}

@media(max-width:900px){.qv-modal{grid-template-columns:1fr}.qv-left,.qv-right{padding:16px}.product-img img{height:220px;}}
</style>


@endpush

@push('scripts')
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded',()=>AOS.init({duration:600,once:true,offset:80}));

function nf(n){return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g,".")}
const WKEY='ps_wishlist_v2';
function getWishlist(){try{return JSON.parse(localStorage.getItem(WKEY)||'[]')}catch(e){return[]}}
function saveWishlist(arr){localStorage.setItem(WKEY,JSON.stringify(arr))}
function toggleWishlist(id,btn){let arr=getWishlist();const idx=arr.indexOf(id);if(idx===-1){arr.push(id);btn.classList.add('active')}else{arr.splice(idx,1);btn.classList.remove('active')}saveWishlist(arr)}

document.addEventListener('DOMContentLoaded',()=>{
  const saved=getWishlist();
  document.querySelectorAll('.icon-wishlist').forEach(el=>{
    const id=el.getAttribute('data-id');
    if(saved.includes(String(id))) el.classList.add('active');
    el.addEventListener('click',()=>toggleWishlist(id,el));
  });

  document.querySelectorAll('.btn-quickview').forEach(btn=>btn.addEventListener('click',openQuickViewFromCard));
  document.getElementById('qvClose').addEventListener('click',()=>document.getElementById('qvBackdrop').classList.remove('show'));
  document.getElementById('qvBackdrop').addEventListener('click',e=>{if(e.target.id==='qvBackdrop')document.getElementById('qvBackdrop').classList.remove('show')});
  document.getElementById('qvWishlist').addEventListener('click',()=>{toggleWishlist(document.getElementById('qvBackdrop').dataset.productId,document.getElementById('qvWishlist'))});
  document.getElementById('qvAdd').addEventListener('click',()=>{
    const id=document.getElementById('qvBackdrop').dataset.productId;
    fetch("{{ url('/cart/add') }}/"+id,{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify({})})
    .finally(()=>{animateCartCount();document.getElementById('qvBackdrop').classList.remove('show')});
  });

  document.querySelectorAll('.add-to-cart-form').forEach(form=>{
  form.addEventListener('submit',function(e){
    e.preventDefault();
    const id = form.getAttribute('action').split('/').pop();
    flyToCart(form.querySelector('button').dataset.img,form.querySelector('button'));

    fetch(form.action,{
  method:'POST',
  headers:{
    'X-CSRF-TOKEN':'{{ csrf_token() }}',
    'Accept':'application/json',
    'Content-Type':'application/json'
  },
  body: JSON.stringify({})
})
.then(res => res.json())
.then(data=>{
  if(data.success){
    const el = document.querySelector('.cart-count');
    if(el) {
      el.textContent = data.cart_count;
      el.animate([{transform:'scale(1)'},{transform:'scale(1.4)'},{transform:'scale(1)'}],{duration:350,easing:'ease-out'});
    }
  }
})
.catch(err=>console.error(err));
  });
});

});

function openQuickViewFromCard(){
  const id=this.dataset.id,card=document.querySelector(`.product-card[data-id="${id}"]`);
  document.getElementById('qvImage').src=card.dataset.img;
  document.getElementById('qvTitle').textContent=card.dataset.name;
  document.getElementById('qvDesc').textContent=card.dataset.desc;
  document.getElementById('qvPrice').textContent=nf(card.dataset.price)+'₫';
  document.getElementById('qvOld').textContent=card.dataset.old?nf(card.dataset.old)+'₫':'';
  document.getElementById('qvBackdrop').dataset.productId=id;
  const qvWishlistBtn=document.getElementById('qvWishlist');
  qvWishlistBtn.classList.toggle('active',getWishlist().includes(String(id)));
  document.getElementById('qvBackdrop').classList.add('show');
}

function flyToCart(imgSrc,btn){
  const fly=document.createElement('img');fly.src=imgSrc;fly.className='fly-img';document.body.appendChild(fly);
  const start=btn.getBoundingClientRect(),end=document.querySelector('.cart-icon').getBoundingClientRect();
  fly.style.left=start.left+'px';fly.style.top=start.top+'px';
  requestAnimationFrame(()=>{fly.style.transform=`translate(${end.left-start.left}px,${end.top-start.top}px) scale(.15)`;fly.style.opacity=0;});
  setTimeout(()=>{fly.remove();animateCartCount();},850);
}
function animateCartCount(){
  const el=document.querySelector('.cart-count');if(!el)return;
  let n=parseInt(el.textContent||'0')||0;n++;el.textContent=n;
  el.animate([{transform:'scale(1)'},{transform:'scale(1.4)'},{transform:'scale(1)'}],{duration:350,easing:'ease-out'});
}
</script>
@endpush

@section('content')
<div class="container" style="padding:24px 0;">
  {{-- Banner Welcome --}}
  <div class="banner-welcome" data-aos="fade-down">
    <h1>Chào mừng đến với cửa hàng</h1>
    <p>Khám phá ngay các sản phẩm nổi bật của chúng tôi</p>
    <button id="btnExplore">Khám phá ngay</button>
    <script>
      const btn = document.getElementById('btnExplore');
      btn.addEventListener('click',()=>{document.getElementById('productsSection').scrollIntoView({behavior:'smooth'})});
    </script>
  </div>

  {{-- Categories --}}
  <div style="margin:18px 0;display:flex;gap:10px;flex-wrap:wrap;">
    @foreach(\App\Models\Category::all() as $cat)
      <a href="{{ route('user.categories.show',$cat->id) }}" style="padding:10px 18px;background:#fff;border-radius:12px;box-shadow:var(--shadow);font-weight:600;color:var(--text);text-decoration:none;transition:.2s;">{{ $cat->name }}</a>
    @endforeach
  </div>

  {{-- Products Sections --}}
  <div id="productsSection">
    @foreach([['title'=>'Sản phẩm nổi bật','products'=>$featuredProducts],['title'=>'Sản phẩm mới','products'=>$newProducts]] as $section)
      <h2 class="section-title" data-aos="fade-up">{{ $section['title'] }}</h2>
      @if($section['products']->count())
        <div class="products-grid">
          @foreach($section['products'] as $product)
            @php
              $price = $product->price ?? 0;
              $old = $product->old_price ?? null;
              $is_new = !empty($product->is_new);
              $hasOld = $old && $old > $price;
              $discount = $hasOld ? max(0, round((1-($price/$old))*100)) : null;
              $img = $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/800x600?text=No+Image';
            @endphp
            <div class="product-card" data-aos="zoom-in" data-id="{{ $product->id }}" data-name="{{ e($product->name) }}" data-desc="{{ e($product->description ?? '') }}" data-price="{{ $price }}" data-old="{{ $old ?? '' }}" data-img="{{ $img }}" data-is-new="{{ $is_new ? '1' : '0' }}">
              <div class="badge-wrap">
                @if($hasOld && $discount>0)<span class="badge badge-sale">-{{ $discount }}%</span>@endif
                @if($is_new)<span class="badge badge-new">Mới về</span>@endif
              </div>
              <div class="card-top-right">
                <div class="icon-btn btn-quickview" title="Xem nhanh" data-id="{{ $product->id }}">🔍</div>
                <div class="icon-btn icon-wishlist" title="Yêu thích" data-id="{{ $product->id }}">❤</div>
              </div>
              <div class="product-img"><img src="{{ $img }}" alt="{{ $product->name }}" loading="lazy"></div>
              <div class="product-body">
                <div class="product-title">{{ $product->name }}</div>
                <div class="price-row"><div class="product-price">{{ number_format($price,0,',','.') }}₫</div>@if($hasOld)<div class="old-price">{{ number_format($old,0,',','.') }}₫</div>@endif</div>
                <div class="product-actions">
                  <a href="{{ route('frontend.products.show',$product->id) }}" class="btn btn-outline">🔍 Xem</a>
                  <form action="{{ route('cart.add',$product->id) }}" method="POST" class="add-to-cart-form" style="margin:0;">@csrf<button type="submit" class="btn btn-accent" data-img="{{ $img }}">🛒 Thêm</button></form>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="empty" data-aos="fade-up">Không có sản phẩm.</div>
      @endif
    @endforeach
  </div>

  {{-- Quick View Modal --}}
  <div class="qv-backdrop" id="qvBackdrop">
    <div class="qv-modal">
      <div class="qv-left"><img id="qvImage" src=""></div>
      <div class="qv-right">
        <div style="display:flex;justify-content:space-between;align-items:start;">
          <div><div class="qv-title" id="qvTitle"></div><div class="qv-desc" id="qvDesc"></div></div>
          <button id="qvClose" class="icon-btn" aria-label="Đóng">✕</button>
        </div>
        <div style="margin-top:12px;"><span class="qv-price" id="qvPrice"></span><span class="qv-old" id="qvOld"></span></div>
        <div style="margin-top:16px;display:flex;gap:10px;">
          <button id="qvAdd" class="btn btn-accent">🛒 Thêm vào giỏ</button>
          <button id="qvWishlist" class="btn btn-outline">❤ Yêu thích</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
