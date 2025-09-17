@extends('layouts.admin')
@section('title','Dashboard Quản Trị')

@section('content')
<div class="container-fluid py-4">
    <h2 class="fw-bold mb-4"><i class="bi bi-speedometer2 me-2"></i> Dashboard</h2>

    <!-- Thống kê nhanh -->
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(135deg,#6f42c1,#d63384);">
                <div class="card-body">
                    <h6 class="text-uppercase">Người dùng</h6>
                    <h3 class="fw-bold">{{ number_format($userCount) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(135deg,#198754,#20c997);">
                <div class="card-body">
                    <h6 class="text-uppercase">Đơn hàng</h6>
                    <h3 class="fw-bold">{{ number_format($orderCount) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-dark" style="background: linear-gradient(135deg,#ffc107,#fd7e14);">
                <div class="card-body">
                    <h6 class="text-uppercase">Doanh thu</h6>
                    <h3 class="fw-bold">{{ number_format($revenue, 0, ',', '.') }} VNĐ</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(135deg,#dc3545,#fd7e14);">
                <div class="card-body">
                    <h6 class="text-uppercase">Sản phẩm</h6>
                    <h3 class="fw-bold">{{ number_format($productCount) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ & Tác vụ nhanh -->
    <div class="row mt-5">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold bg-white">
                    <i class="bi bi-graph-up me-2"></i> Thống kê doanh thu
                </div>
                <div class="card-body">
                    <canvas id="dashboardChart" height="150"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold bg-white">
                    <i class="bi bi-lightning-fill text-warning me-2"></i> Tác vụ nhanh
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-primary"><i class="bi bi-folder-fill me-2"></i> Danh mục</a>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-warning text-dark"><i class="bi bi-box-seam me-2"></i> Sản phẩm</a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-info text-white"><i class="bi bi-people-fill me-2"></i> Người dùng</a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-success"><i class="bi bi-basket-fill me-2"></i> Đơn hàng</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = JSON.parse('@json($labels)');
const data = JSON.parse('@json($data)');

const ctx = document.getElementById('dashboardChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: data,
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13,110,253,0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true
            }
        },
        scales: {
            y: {
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString('vi-VN') + ' đ';
                    }
                }
            }
        }
    }
});
</script>

@endsection
