@extends('layouts.user')

@section('title', 'Giỏ hàng')

@section('content')

<style>
/* === GIỮ NGUYÊN CSS 700+ DÒNG === */
:root {
  --bg: #f5f7fa;               
  --card: #ffffff;              
  --text: #1e40af;              
  --muted: #64748b;             
  --accent: #4f46e5;            
  --accent2: #3b82f6;           
  --danger: #ef4444;            
  --success: #10b981;           
  --radius: 16px;               
  --shadow: 0 6px 20px rgba(0,0,0,0.08); 
  --ring: 0 0 0 3px rgba(79,70,229,.15), 0 6px 18px rgba(59,130,246,.1);
}
body { background: var(--bg); color: var(--text); font-family: 'Inter', sans-serif; line-height: 1.6; }
.cart-wrap { max-width: 1200px; margin: 0 auto; padding: 40px 24px; }
.cart-card, .summary-card { background: var(--card); border-radius: var(--radius); box-shadow: var(--shadow); padding: 32px; transition: transform 0.2s ease, box-shadow 0.2s ease; }
.cart-card:hover, .summary-card:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(0,0,0,0.12); }
.cart-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 32px; }
.cart-header h2 { font-size: 2rem; font-weight: 700; color: var(--text); }
.cart-actions .btn { min-width: 160px; padding: 12px 20px; font-size: 1rem; font-weight: 600; border-radius: var(--radius); transition: all 0.2s ease; cursor: pointer; }
.cart-actions .btn-accent { background: linear-gradient(135deg, var(--accent), var(--accent2)); color: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
.cart-actions .btn-accent:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.12); }
.cart-actions .btn-danger { background: var(--danger); color: #fff; }
.cart-actions .btn-outline { background: transparent; color: var(--text); border: 1px solid rgba(148,163,184,.2); }
.table { width: 100%; border-collapse: separate; border-spacing: 0 12px; margin-bottom: 32px; }
.table thead th { font-weight: 600; font-size: 0.9rem; color: var(--muted); padding: 14px 18px; text-align: left; }
.table tbody td { background: var(--card); padding: 18px; border-radius: var(--radius); vertical-align: middle; transition: background 0.2s ease, box-shadow 0.2s ease; box-shadow: var(--shadow); }
.table tbody td:hover { background: #f9f9f9; box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
.item { display: flex; gap: 16px; align-items: center; }
.item .name { font-weight: 600; font-size: 1rem; color: var(--text); }
.item .meta { font-size: 0.875rem; color: var(--muted); }
.price { color: var(--accent); font-weight: 700; }
.subtotal-cell { color: var(--success); font-weight: 700; }
.qty-control { display: inline-flex; align-items: center; gap: 8px; border: 1px solid rgba(148,163,184,.2); border-radius: var(--radius); padding: 6px 10px; background: #f7f9fc; }
.qty-btn { width: 36px; height: 36px; border-radius: 12px; border: 1px solid rgba(148,163,184,.2); background: #fff; font-weight: 700; display: flex; align-items: center; justify-content: center; cursor: pointer; }
.qty-btn:hover { box-shadow: var(--ring); }
.qty-input { width: 50px; text-align: center; border: none; background: transparent; font-weight: 600; }
.summary-row { display: flex; justify-content: space-between; margin: 12px 0; }
.summary-row.total { font-size: 1.4rem; font-weight: 800; }
.coupon input, .select { width: 100%; padding: 12px 14px; border-radius: var(--radius); border: 1px solid rgba(148,163,184,.2); background: #fff; color: var(--text); font-size: 0.95rem; }
.logo-bg, .icon-bg { background: linear-gradient(135deg, var(--accent), var(--accent2)); border-radius: var(--radius); padding: 8px; display: inline-flex; align-items: center; justify-content: center; }
.toast { position: fixed; bottom: 24px; right: 24px; padding: 16px 20px; border-radius: var(--radius); background: linear-gradient(135deg, var(--accent), var(--accent2)); color: #fff; font-weight: 600; box-shadow: var(--shadow); transform: translateY(20px); opacity: 0; pointer-events: none; transition: .3s ease; }
.toast.show { transform: translateY(0); opacity: 1; }
@media (max-width: 1024px) { .summary { grid-template-columns: 1fr; } .sticky-col { position: static; } .cart-header { flex-direction: column; align-items: flex-start; gap: 16px; } .table tbody td { display: block; padding: 14px; } }
</style>

<div class="cart-wrap">
  <div class="cart-card">
    <div class="cart-header">
      <h2>🛒 Giỏ hàng của bạn</h2>
      <div class="cart-actions">
        <a href="{{ route('home') }}" class="btn btn-outline">⬅ Tiếp tục mua hàng</a>
        @if(count($cartItems) > 0)
          <button id="btn-clear" class="btn btn-danger">🗑 Xóa toàn bộ</button>
        @endif
      </div>
    </div>

    @if(count($cartItems) > 0)
    <div style="overflow:auto">
      <table class="table">
        <thead>
          <tr>
            <th style="text-align:left">Sản phẩm</th>
            <th>Danh mục</th>
            <th>Giá</th>
            <th style="min-width:160px">Số lượng</th>
            <th>Thành tiền</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="cart-body">
          @php $subtotal = 0; @endphp
          @foreach($cartItems as $item)
            @php 
              $price = $item->product->price ?? 0;
              $line = $price * $item->quantity; 
              $subtotal += $line;
            @endphp
            <tr id="row-{{ $item->product->id }}">
              <td>
                <div class="item">
                  <div>
                    <div class="name">{{ $item->product->name ?? '' }}</div>
                    <div class="meta">
                      <span class="badge badge-info">#{{ $item->product->id }}</span>
                    </div>
                  </div>
                </div>
              </td>
              <td>{{ $item->product->category->name ?? '' }}</td>

              <td class="price" data-unit="{{ $price }}" data-id="{{ $item->product->id }}">
                  {{ number_format($price,0,',','.') }} VNĐ
              </td>

              <td>
                <div class="qty-control" data-id="{{ $item->product->id }}">
                  <button class="qty-btn decrease" data-id="{{ $item->product->id }}">-</button>
                  <input class="qty-input" data-id="{{ $item->product->id }}" value="{{ $item->quantity }}">
                  <button class="qty-btn increase" data-id="{{ $item->product->id }}">+</button>
                </div>
              </td>

              <td class="subtotal-cell" data-id="{{ $item->product->id }}">
                {{ number_format($line,0,',','.') }} VNĐ
              </td>

              <td>
                <button class="remove-btn" data-id="{{ $item->product->id }}">Xóa</button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Summary -->
    <div class="summary">
      <div class="summary-card">
        <h4>🎁 Mã giảm giá</h4>
        <div class="coupon">
          <input id="coupon-code" placeholder="Nhập mã (VD: SALE10)">
          <button id="apply-coupon" class="btn btn-accent">Áp dụng</button>
          <button id="remove-coupon" class="btn btn-secondary">Hủy mã</button>
        </div>

        <h4 style="margin-top:18px">🚚 Phương thức vận chuyển</h4>
        <select id="shipping-method" class="select">
          <option value="standard" selected>Standard (2-4 ngày) — 25.000 VNĐ</option>
          <option value="express">Express (1-2 ngày) — 45.000 VNĐ</option>
          <option value="pickup">Nhận tại cửa hàng — 0 VNĐ</option>
        </select>
      </div>

      <div class="summary-card sticky-col" id="summary">
        <h4>🧾 Tóm tắt đơn hàng</h4>
        <div class="summary-row">
          <span>Tạm tính</span>
          <span id="sum-subtotal">{{ number_format($subtotal,0,',','.') }} VNĐ</span>
        </div>
        <div class="summary-row">
          <span>Giảm giá</span>
          <span id="sum-discount" data-val="0">0 VNĐ</span>
        </div>
        <div class="summary-row">
          <span>Phí vận chuyển</span>
          <span id="sum-shipping">25.000 VNĐ</span>
        </div>
        <div class="summary-row">
          <span>VAT (10%)</span>
          <span id="sum-vat">{{ number_format(($subtotal)*0.10,0,',','.') }} VNĐ</span>
        </div>
        <div class="summary-row total">
          <span>Tổng cộng</span>
          @php 
            $shipping_fee = 25000;
            $vat = round($subtotal * 0.10);
            $grand = $subtotal + $shipping_fee + $vat;
          @endphp
          <span id="sum-grand">{{ number_format($grand,0,',','.') }} VNĐ</span>
        </div>
        <form action="{{ route('user.payment.index') }}" method="GET">
          <button type="submit" class="btn btn-accent" style="width:100%;margin-top:10px">
            💳 Tiến hành thanh toán
          </button>
        </form>
      </div>
    </div>

    @else
      <div style="padding:24px">
        <p style="color:var(--muted)">Giỏ hàng trống.</p>
        <a href="{{ route('home') }}" class="btn btn-secondary mt-3">⬅ Quay lại mua hàng</a>
      </div>
    @endif
  </div>
</div>

<div id="toast" class="toast"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // CSRF token cho AJAX
  const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  function formatVND(num) {
    return new Intl.NumberFormat('vi-VN').format(num) + ' VNĐ';
  }

  function updateSummary() {
    let subtotal = 0;
    document.querySelectorAll('.subtotal-cell').forEach(td => {
      subtotal += parseInt(td.dataset.raw || td.textContent.replace(/\D/g,'') || 0);
    });

    document.getElementById('sum-subtotal').textContent = formatVND(subtotal);

    const shipping = parseInt(document.getElementById('sum-shipping').textContent.replace(/\D/g,'') || 0);
    const vat = Math.round(subtotal * 0.10);

    document.getElementById('sum-vat').textContent = formatVND(vat);
    document.getElementById('sum-grand').textContent = formatVND(subtotal + shipping + vat);
  }

  function showToast(message) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.classList.add('show');
    setTimeout(()=>toast.classList.remove('show'), 2500);
  }

  // Cập nhật số lượng
  function updateQuantity(productId, quantity) {
    fetch(`/cart/update/${productId}`, {
      method: 'PATCH',
      headers: {
        'Content-Type':'application/json',
        'X-CSRF-TOKEN': csrf
      },
      body: JSON.stringify({ quantity })
    })
    .then(res => res.json())
    .then(data => {
      if(data.success) showToast('Cập nhật thành công!');
    })
    .catch(err => showToast('Lỗi cập nhật giỏ hàng!'));
  }

  // Xử lý nút tăng/giảm
  document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.dataset.id;
      const input = document.querySelector(`.qty-input[data-id="${id}"]`);
      let val = parseInt(input.value);

      if (this.classList.contains('increase')) val++;
      if (this.classList.contains('decrease') && val > 1) val--;

      input.value = val;

      // Cập nhật subtotal dòng
      const priceElem = document.querySelector(`.price[data-unit][data-id="${id}"]`);
      const unit = parseInt(priceElem.dataset.unit || 0);
      const subtotalCell = document.querySelector(`.subtotal-cell[data-id="${id}"]`);
      subtotalCell.dataset.raw = unit * val;
      subtotalCell.textContent = formatVND(unit * val);

      updateSummary();
      updateQuantity(id, val);
    });
  });

  // Thay đổi input trực tiếp
  document.querySelectorAll('.qty-input').forEach(input => {
    input.addEventListener('change', function() {
      let val = parseInt(this.value) || 1;
      if(val < 1) val = 1;
      this.value = val;

      const id = this.dataset.id;
      const priceElem = document.querySelector(`.price[data-unit][data-id="${id}"]`);
      const unit = parseInt(priceElem.dataset.unit || 0);
      const subtotalCell = document.querySelector(`.subtotal-cell[data-id="${id}"]`);
      subtotalCell.dataset.raw = unit * val;
      subtotalCell.textContent = formatVND(unit * val);

      updateSummary();
      updateQuantity(id, val);
    });
  });

  // Xóa 1 sản phẩm
  document.querySelectorAll('.remove-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.dataset.id;
      fetch(`/cart/remove/${id}`, {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': csrf}
      })
      .then(res=>res.json())
      .then(data=>{
        if(data.success){
          const row = document.getElementById(`row-${id}`);
          if(row) row.remove();
          updateSummary();
          showToast('Đã xóa sản phẩm!');
        }
      })
      .catch(()=>showToast('Lỗi xóa sản phẩm!'));
    });
  });

  // Xóa toàn bộ
  const btnClear = document.getElementById('btn-clear');
  if(btnClear){
    btnClear.addEventListener('click', function(){
      fetch(`/cart/clear`, {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': csrf}
      })
      .then(res=>res.json())
      .then(data=>{
        if(data.success){
          document.querySelectorAll('#cart-body tr').forEach(row => row.remove());
          updateSummary();
          showToast('Giỏ hàng đã được xóa!');
        }
      })
      .catch(()=>showToast('Lỗi xóa giỏ hàng!'));
    });
  }

});
document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const type = this.classList.contains('increase') ? 'increase' : 'decrease';
        fetch(`/cart/${type}/${id}`, { method: 'PATCH' })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    const input = document.querySelector(`.qty-input[data-id='${id}']`);
                    let qty = parseInt(input.value);
                    qty = type==='increase' ? qty+1 : Math.max(qty-1,1);
                    input.value = qty;

                    // Cập nhật subtotal dòng
                    const unit = parseFloat(document.querySelector(`.price[data-id='${id}']`).dataset.unit);
                    const subtotalCell = document.querySelector(`.subtotal-cell[data-id='${id}']`);
                    subtotalCell.textContent = (unit * qty).toLocaleString('vi-VN') + ' VNĐ';

                    // Cập nhật tổng
                    updateTotal();
                }
            });
    });
});

// Hàm tính tổng
function updateTotal(){
    let subtotal = 0;
    document.querySelectorAll('.subtotal-cell').forEach(td=>{
        subtotal += parseFloat(td.textContent.replace(/\D/g,''));
    });
    const shipping = parseFloat(document.getElementById('sum-shipping').textContent.replace(/\D/g,'')) || 0;
    const vat = Math.round(subtotal*0.1);
    document.getElementById('sum-subtotal').textContent = subtotal.toLocaleString('vi-VN') + ' VNĐ';
    document.getElementById('sum-vat').textContent = vat.toLocaleString('vi-VN') + ' VNĐ';
    document.getElementById('sum-grand').textContent = (subtotal + shipping + vat).toLocaleString('vi-VN') + ' VNĐ';
}

</script>



</script>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
@endsection
