@extends('layouts.user')

@section('title', 'Trang chủ')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
@endpush
@push('scripts')
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>document.addEventListener('DOMContentLoaded',()=>AOS.init({duration:600, once:true, offset:80}));</script>
@endpush

@section('content')
<style>
:root{
  --bg:#f8f9fa;
  --card:#ffffff;
  --text:#1e293b;
  --muted:#6b7280;
  --accent:#4f46e5;
  --accent2:#3b82f6;
  --danger:#ef4444;
  --success:#10b981;
  --radius:16px;
  --shadow:0 6px 20px rgba(0,0,0,.08);
  --shadow-lg:0 12px 40px rgba(0,0,0,.12);
  --glass: rgba(255,255,255,0.85);
  --transition-speed:0.28s;
}

body {
  background:var(--bg);
  color:var(--text);
  font-family:'Inter',system-ui,Segoe UI,Roboto,Helvetica,Arial,sans-serif;
  line-height:1.5;
  font-size:1rem;
  margin:0;
}

/* HERO */
.product-hero {
  display:flex;
  gap:20px;
  align-items:center;
  background: linear-gradient(135deg, rgba(79,70,229,.08), rgba(59,130,246,.06));
  padding:24px;
  border-radius:var(--radius);
  margin:20px 0;
  box-shadow:var(--shadow);
  transition: all var(--transition-speed) ease;
}
.product-hero:hover { box-shadow:var(--shadow-lg); }
.product-hero .logo {
  width:60px;
  height:60px;
  border-radius:14px;
  background:linear-gradient(135deg,var(--accent),var(--accent2));
  display:grid;
  place-items:center;
  color:#fff;
  font-weight:800;
  font-size:20px;
}

/* Section Titles */
.section-title {
  text-align:center;
  font-size:2.1rem;
  font-weight:800;
  margin:24px 0 12px;
}
.section-sub {
  color:var(--muted);
  font-size:1rem;
  margin-top:4px;
}

/* Grid */
.products-grid {
  display:grid;
  grid-template-columns:repeat(auto-fill, minmax(260px, 1fr));
  gap:20px;
}

/* Product Card */
.product-card {
  background:var(--card);
  border-radius:var(--radius);
  overflow:hidden;
  box-shadow:var(--shadow);
  transition: transform var(--transition-speed) cubic-bezier(.2,.8,.2,1), box-shadow var(--transition-speed) ease;
  position:relative;
  display:flex;
  flex-direction:column;
}
.product-card:hover { transform:translateY(-6px); box-shadow:var(--shadow-lg); }

/* Product Image */
.product-img {
  position:relative;
  overflow:hidden;
  border-bottom:1px solid #e5e7eb;
}
.product-img img {
  width:100%;
  height:220px;
  object-fit:cover;
  transition: transform 0.35s ease;
  display:block;
}
.product-card:hover .product-img img { transform:scale(1.05); }

/* Badges */
.badge-wrap {
  position:absolute;
  left:12px;
  top:12px;
  display:flex;
  gap:8px;
  z-index:4;
}
.badge {
  font-weight:700;
  font-size:.78rem;
  padding:5px 10px;
  border-radius:12px;
  color:#fff;
  box-shadow:0 4px 18px rgba(0,0,0,.1);
  text-transform:uppercase;
}
.badge-sale { background: linear-gradient(90deg,#ef4444,#f97316); }
.badge-new { background: linear-gradient(90deg,var(--accent),var(--accent2)); }

/* Product Body */
.product-body {
  padding:16px;
  display:flex;
  flex-direction:column;
  gap:10px;
  flex:1;
}
.product-title {
  font-weight:700;
  font-size:1.05rem;
  line-height:1.4;
  color:var(--text);
}
.price-row {
  display:flex;
  gap:12px;
  align-items:center;
  margin-top:6px;
}
.product-price {
  font-weight:800;
  color:var(--success);
  font-size:1.1rem;
}
.old-price {
  color:#94a3b8;
  text-decoration:line-through;
  font-weight:600;
  font-size:.95rem;
}

/* Actions Row */
.product-actions {
  display:flex;
  gap:10px;
  margin-top:12px;
}
.btn {
  flex:1;
  padding:12px 14px;
  border-radius:12px;
  font-weight:700;
  cursor:pointer;
  border:none;
  text-align:center;
  transition: all .18s ease;
  font-size:0.95rem;
}
.btn-outline {
  background:transparent;
  border:1px solid rgba(14,165,233,.1);
  color:var(--text);
}
.btn-outline:hover { background:#f1f5f9; }
.btn-accent {
  background:linear-gradient(135deg,var(--accent),var(--accent2));
  color:#fff;
  box-shadow:0 8px 25px rgba(59,130,246,.18);
}
.btn-accent:active { transform:translateY(1px); }

/* Quickview Icon */
.card-top-right {
  position:absolute;
  right:12px;
  top:12px;
  display:flex;
  gap:8px;
  z-index:5;
}
.icon-btn {
  width:36px;
  height:36px;
  border-radius:10px;
  display:grid;
  place-items:center;
  background:var(--glass);
  border:1px solid rgba(2,6,23,.05);
  cursor:pointer;
  transition:all .15s ease;
}
.icon-btn.active { box-shadow:0 8px 25px rgba(0,0,0,.12); transform:translateY(-2px); }
.icon-heart.active { color:#ef4444; transform:scale(1.05); }

/* Quick View Modal */
.qv-backdrop {
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.5);
  display:none;
  align-items:center;
  justify-content:center;
  z-index:9999;
  padding:20px;
}
.qv-backdrop.show { display:flex; }
.qv-modal {
  width:100%;
  max-width:1000px;
  background:var(--card);
  border-radius:var(--radius);
  overflow:hidden;
  display:grid;
  grid-template-columns:1fr 1fr;
  box-shadow:var(--shadow-lg);
}
.qv-left {
  padding:20px;
  background:#f8f9fa;
  display:flex;
  align-items:center;
  justify-content:center;
}
.qv-left img {
  max-width:100%;
  max-height:520px;
  object-fit:contain;
  border-radius:12px;
}
.qv-right { padding:24px; }
.qv-title { font-size:1.35rem; font-weight:800; margin-bottom:10px; }
.qv-desc{ color:var(--muted); margin-bottom:14px; }
.qv-price{ font-weight:900; color:var(--success); font-size:1.3rem; margin-bottom:10px; }
.qv-old{ color:#94a3b8; text-decoration:line-through; margin-left:10px; font-weight:700; }

/* Countdown */
.countdown {
  font-weight:700;
  font-size:.9rem;
  color:#fff;
  padding:6px 10px;
  border-radius:8px;
  background:#111827;
  display:inline-block;
  margin-left:8px;
}

/* Fly Image Animation */
.fly-img {
  position:fixed;
  z-index:20000;
  width:64px;
  height:64px;
  object-fit:cover;
  border-radius:12px;
  pointer-events:none;
  transition:transform .85s cubic-bezier(.2,.8,.2,1), opacity .85s ease;
}

/* Empty State */
.empty { text-align:center; padding:40px; color:var(--muted); font-weight:500; }

/* Responsive */
@media(max-width:900px){
  .qv-modal{ grid-template-columns: 1fr; }
  .qv-left{ padding:16px; }
  .qv-right{ padding:18px; }
  .product-img img{ height:200px; }
}
</style>


<div class="container" style="padding:18px 0;">
  {{-- top hero --}}
  <div class="product-hero" data-aos="fade-up">
    <div class="logo">PS</div>
    <div>
      <div style="font-weight:900;font-size:1.05rem">Ưu đãi & sản phẩm nổi bật</div>
      <div style="color:var(--muted); margin-top:6px">Miễn phí giao hàng & đổi trả trong 7 ngày</div>
    </div>
    <div style="margin-left:auto; display:flex; gap:16px; align-items:center">
      <div style="text-align:right">
        <div style="font-weight:800">Sinh nhật giảm giá</div>
        <div style="color:var(--muted); font-size:.9rem">Ưu đãi thêm cho thành viên</div>
      </div>
      <a href="{{ route('cart.index') }}" class="cart-link" style="position:relative">
        <svg class="cart-icon" width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M3 3h2l.4 2M7 13h10l4-8H5.4" stroke="#0f172a" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
          <circle cx="10" cy="20" r="1" fill="#0f172a"/><circle cx="18" cy="20" r="1" fill="#0f172a"/>
        </svg>
        <div class="cart-count" style="position:absolute; right:-6px; top:-6px; background:#ef4444; color:#fff; min-width:22px; height:22px; border-radius:999px; display:grid; place-items:center; font-weight:800; font-size:12px;">{{ session('cart_count', 0) ?? 0 }}</div>
      </a>
    </div>
  </div>
  <div style="margin: 18px 0; display:flex; gap:10px; flex-wrap:wrap;">
    @foreach(\App\Models\Category::all() as $cat)
    <a href="{{ route('user.categories.show', $cat->id) }}" 
   style="padding:8px 14px; background:#fff; border-radius:12px; box-shadow:var(--shadow); font-weight:600; color:var(--text); text-decoration:none;">
    {{ $cat->name }}
</a>
    @endforeach
  </div>
  <h2 class="section-title" data-aos="fade-up">
    🛍️ Sản phẩm mới nhất
    <span class="section-sub">Chọn nhanh, giao nhanh — hàng chất lượng</span>
  </h2>

  @if($products->count())
    <div class="products-grid">
      @foreach($products as $product)
        @php
          // safe fallbacks
          $price = $product->price ?? 0;
          $old = $product->old_price ?? null;
          $is_new = !empty($product->is_new);
          $sale_end = $product->sale_end ?? null; // expect ISO string or null
          $hasOld = $old && $old > $price;
          $discount = $hasOld ? max(0, round((1 - ($price / $old)) * 100)) : null;
          $img = $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/800x600?text=No+Image';
        @endphp

        <div class="product-card" data-aos="zoom-in" 
             data-id="{{ $product->id }}"
             data-name="{{ e($product->name) }}"
             data-desc="{{ e($product->description ?? '') }}"
             data-price="{{ $price }}"
             data-old="{{ $old ?? '' }}"
             data-img="{{ $img }}"
             data-is-new="{{ $is_new ? '1' : '0' }}"
             data-sale-end="{{ $sale_end ?? '' }}"
        >
          <div class="badge-wrap">
            @if($hasOld && $discount > 0)
              <span class="badge badge-sale">-{{ $discount }}%</span>
            @endif
            @if($is_new)
              <span class="badge badge-new">Mới về</span>
            @endif
          </div>

          <div class="card-top-right">
            <div class="icon-btn btn-quickview" title="Xem nhanh" data-id="{{ $product->id }}">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M12 5C7 5 2.73 8.11 1 12c1.73 3.89 6 7 11 7s9.27-3.11 11-7c-1.73-3.89-6-7-11-7z" stroke="#07112b" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="#07112b" stroke-width="1.2"/></svg>
            </div>
            <div class="icon-btn icon-wishlist" title="Yêu thích" data-id="{{ $product->id }}">
              <svg class="icon-heart" width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M20.8 6.6a5.4 5.4 0 0 0-7.6 0L12 7.8l-1.2-1.2a5.4 5.4 0 1 0-7.6 7.6L12 21l8.8-6.8a5.4 5.4 0 0 0 0-7.6z" stroke="#07112b" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
          </div>

          <div class="product-img">
            <img src="{{ $img }}" alt="{{ $product->name }}">
          </div>

          <div class="product-body">
            <div class="product-title">{{ $product->name }}</div>
            <div class="price-row">
              <div class="product-price">{{ number_format($price, 0, ',', '.') }}₫</div>
              @if($hasOld)
                <div class="old-price">{{ number_format($old, 0, ',', '.') }}₫</div>
              @endif

              {{-- countdown placeholder --}}
              @if($sale_end)
                <div style="margin-left:auto">
                  <span class="countdown" data-sale-end="{{ $sale_end }}">--:--:--</span>
                </div>
              @endif
            </div>

            <div class="product-actions">
            <a href="{{ route('frontend.products.show', $product->id) }}" class="btn btn-outline">🔍 Xem</a>

              <form action="{{ route('cart.add', $product->id) }}" method="POST" class="add-to-cart-form" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-accent" data-img="{{ $img }}">🛒 Thêm</button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="empty" data-aos="fade-up">Không có sản phẩm nào.</div>
  @endif
</div>

<!-- QUICK VIEW MODAL (single, reused) -->
<div class="qv-backdrop" id="qvBackdrop" aria-hidden="true">
  <div class="qv-modal" role="dialog" aria-modal="true">
    <div class="qv-left">
      <img src="" alt="product" id="qvImage">
    </div>
    <div class="qv-right">
      <div style="display:flex; justify-content:space-between; align-items:start;">
        <div>
          <div class="qv-title" id="qvTitle"></div>
          <div class="qv-desc" id="qvDesc"></div>
        </div>
        <button id="qvClose" class="icon-btn" aria-label="Đóng">✕</button>
      </div>

      <div style="margin-top:12px;">
        <span class="qv-price" id="qvPrice"></span>
        <span class="qv-old" id="qvOld"></span>
      </div>

      <div style="margin-top:16px; display:flex; gap:10px;">
        <button id="qvAdd" class="btn btn-accent">🛒 Thêm vào giỏ</button>
        <button id="qvWishlist" class="btn btn-outline">❤ Yêu thích</button>
      </div>
    </div>
  </div>
</div>

<script>
/* Helper: format number */
function nf(n){ return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }

/* Wishlist (localStorage) */
const WKEY = 'ps_wishlist_v1';
function getWishlist(){ try{ return JSON.parse(localStorage.getItem(WKEY) || '[]'); }catch(e){ return []; } }
function saveWishlist(arr){ localStorage.setItem(WKEY, JSON.stringify(arr)); }

function toggleWishlist(id, btn){
  let arr = getWishlist();
  const idx = arr.indexOf(id);
  if(idx === -1){ arr.push(id); btn.classList.add('active'); } else { arr.splice(idx,1); btn.classList.remove('active'); }
  saveWishlist(arr);
}

/* Initialize wishlist icons */
document.addEventListener('DOMContentLoaded', () => {
  const saved = getWishlist();
  document.querySelectorAll('.icon-wishlist').forEach(el=>{
    const id = el.getAttribute('data-id');
    if(saved.includes(String(id))) el.classList.add('active');
    el.addEventListener('click', ()=> toggleWishlist(id, el));
  });

  // quickview buttons
  document.querySelectorAll('.btn-quickview').forEach(btn=>{
    btn.addEventListener('click', openQuickViewFromCard);
  });

  // quick view close
  document.getElementById('qvClose').addEventListener('click', ()=>{ document.getElementById('qvBackdrop').classList.remove('show'); });

  // close on backdrop click
  document.getElementById('qvBackdrop').addEventListener('click', (e)=>{ if(e.target.id === 'qvBackdrop') document.getElementById('qvBackdrop').classList.remove('show'); });

  // wire up wishlist toggles inside quickview
  document.getElementById('qvWishlist').addEventListener('click', ()=>{
    const id = document.getElementById('qvBackdrop').dataset.productId;
    toggleWishlist(id, document.getElementById('qvWishlist'));
  });

  // Add to cart from quickview
  document.getElementById('qvAdd').addEventListener('click', ()=>{
    const id = document.getElementById('qvBackdrop').dataset.productId;
    // Attempt to POST to add route via fetch, then animate cart count
    fetch("{{ url('/cart/add') }}/" + id, { method:'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }, body: JSON.stringify({}) })
      .then(r=>{ /* ignore */ })
      .catch(()=>{ /* ignore */ })
      .finally(()=>{ animateCartCount(); document.getElementById('qvBackdrop').classList.remove('show'); });
  });

  // fly-to-cart & submit forms
  document.querySelectorAll('.add-to-cart-form').forEach(form=>{
    form.addEventListener('submit', function(e){
      e.preventDefault();
      const btn = form.querySelector('button');
      const imgSrc = btn.getAttribute('data-img');
      flyToCart(imgSrc, btn);
      // submit after animation
      setTimeout(()=> form.submit(), 850);
    });
  });

  // countdown init
  document.querySelectorAll('.countdown').forEach(initCountdown);

  // cart count initialization from server-side value or 0
  // (we only update visually on client when adding)
});

/* Quick View: opens modal using data attributes from the card's parent */
function openQuickViewFromCard(e){
  const id = this.getAttribute('data-id');
  const card = document.querySelector('.product-card[data-id="'+id+'"]');
  if(!card) return;
  const title = card.getAttribute('data-name');
  const desc = card.getAttribute('data-desc');
  const price = card.getAttribute('data-price');
  const old = card.getAttribute('data-old');
  const img = card.getAttribute('data-img');

  document.getElementById('qvImage').src = img;
  document.getElementById('qvTitle').textContent = title;
  document.getElementById('qvDesc').textContent = desc;
  document.getElementById('qvPrice').textContent = nf(price) + '₫';
  document.getElementById('qvOld').textContent = (old && old!="") ? nf(old)+'₫' : '';
  document.getElementById('qvBackdrop').dataset.productId = id;

  // set wishlist button active if in wishlist
  const qvWishlistBtn = document.getElementById('qvWishlist');
  const saved = getWishlist();
  if(saved.includes(String(id))) qvWishlistBtn.classList.add('active'); else qvWishlistBtn.classList.remove('active');

  document.getElementById('qvBackdrop').classList.add('show');
}

/* Fly to cart animation and badge increment */
function flyToCart(imgSrc, originBtn){
  const fly = document.createElement('img');
  fly.src = imgSrc; fly.className = 'fly-img';
  document.body.appendChild(fly);
  const start = originBtn.getBoundingClientRect();
  fly.style.left = start.left + 'px'; fly.style.top = start.top + 'px';

  const cartIcon = document.querySelector('.cart-icon') || document.querySelector('header') || document.body;
  const end = cartIcon.getBoundingClientRect();

  requestAnimationFrame(()=> {
    fly.style.transform = `translate(${end.left - start.left}px, ${end.top - start.top}px) scale(.15)`;
    fly.style.opacity = 0;
  });

  setTimeout(()=> {
    fly.remove();
    animateCartCount();
  }, 850);
}

/* Animate cart count: increment visually */
function animateCartCount(){
  const el = document.querySelector('.cart-count');
  if(!el) return;
  let n = parseInt(el.textContent || '0') || 0;
  n = n + 1;
  el.textContent = n;
  el.animate([{ transform:'scale(1)' }, { transform:'scale(1.4)' }, { transform:'scale(1)' }], { duration:350, easing:'ease-out' });
}

/* Countdown initializer */
function initCountdown(el){
  const iso = el.getAttribute('data-sale-end');
  if(!iso) return el.style.display='none';
  let end = new Date(iso);
  if(isNaN(end)) return el.style.display='none';
  updateCountdown(el, end);
  const iv = setInterval(()=> {
    updateCountdown(el, end);
  }, 1000);
  // store interval so could clear if needed
  el.dataset.iv = iv;
}
function updateCountdown(el, end){
  const now = new Date();
  const diff = end - now;
  if(diff <= 0){ el.textContent = 'HẾT KM'; el.style.background = '#64748b'; clearInterval(el.dataset.iv); return; }
  const d = Math.floor(diff / (1000*60*60*24));
  const h = Math.floor((diff % (1000*60*60*24)) / (1000*60*60));
  const m = Math.floor((diff % (1000*60*60)) / (1000*60));
  const s = Math.floor((diff % (1000*60)) / 1000);
  if(d>0) el.textContent = `${d}d ${h}h ${m}m`;
  else el.textContent = `${h}h ${m}m ${s}s`;
}

/* Optional: allow syncing wishlist to server when user logs in */
/* Example (not activated): 
function syncWishlistToServer(){ fetch('/wishlist/sync', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}, body:JSON.stringify(getWishlist())}) }
*/
</script>
@endsection
