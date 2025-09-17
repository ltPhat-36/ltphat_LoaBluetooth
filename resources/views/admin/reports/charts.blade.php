@extends('layouts.admin')

@section('title', 'Biểu đồ - Báo cáo')
<style>
    .card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        background: linear-gradient(145deg, #ffffff, #f9fafc);
        box-shadow: 0 6px 15px rgba(0,0,0,0.06);
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.10);
    }
    .card-header {
        font-weight: bold;
        font-size: 1.05rem;
        background: linear-gradient(135deg, #a1c4fd, #c2e9fb); /* xanh pastel nhạt */
        color: #2f4f6f; /* xanh ghi nhạt */
        padding: 12px 18px;
    }
    canvas {
        animation: fadeIn 1s ease-in-out;
    }
    @keyframes fadeIn {
        from {opacity: 0; transform: scale(0.97);}
        to {opacity: 1; transform: scale(1);}
    }
</style>


@section('content')
<div class="container-fluid py-4">
    <h2 class="fw-bold mb-4"><i class="bi bi-bar-chart me-2"></i> Biểu đồ Báo cáo</h2>

    {{-- Nơi lưu dữ liệu JSON (ẩn). --}}
    <div id="report-data"
         data-cat-labels='@json($catLabels ?? [])'
         data-cat-revenue='@json($catRevenue ?? [])'
         data-rev-date-labels='@json($revDateLabels ?? [])'
         data-rev-date-data='@json($revDateData ?? [])'
         data-rev-month-labels='@json($revMonthLabels ?? [])'
         data-rev-month-data='@json($revMonthData ?? [])'
         data-rev-year-labels='@json($revYearLabels ?? [])'
         data-rev-year-data='@json($revYearData ?? [])'
         data-payment-status-labels='@json($paymentStatusLabels ?? [])'
         data-payment-status-revenue='@json($paymentStatusRevenue ?? [])'
         style="display:none;"></div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">Doanh thu theo danh mục</div>
                <div class="card-body" style="height:280px;"><canvas id="categoryRevenueChart"></canvas></div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">Doanh thu theo ngày (30 ngày)</div>
                <div class="card-body" style="height:280px;"><canvas id="revenueByDateChart"></canvas></div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">Doanh thu theo tháng (12 tháng)</div>
                <div class="card-body" style="height:280px;"><canvas id="revenueByMonthChart"></canvas></div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">Doanh thu theo năm</div>
                <div class="card-body" style="height:280px;"><canvas id="revenueByYearChart"></canvas></div>
            </div>
        </div>

        <div class="col-lg-12 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">Doanh thu theo trạng thái thanh toán</div>
                <div class="card-body" style="height:360px;"><canvas id="revenueByPaymentStatusChart"></canvas></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dataEl = document.getElementById('report-data');

    const catLabels = JSON.parse(dataEl.dataset.catLabels || '[]');
    const catRevenue = JSON.parse(dataEl.dataset.catRevenue || '[]');

    const revDateLabels = JSON.parse(dataEl.dataset.revDateLabels || '[]');
    const revDateData = JSON.parse(dataEl.dataset.revDateData || '[]');

    const revMonthLabels = JSON.parse(dataEl.dataset.revMonthLabels || '[]');
    const revMonthData = JSON.parse(dataEl.dataset.revMonthData || '[]');

    const revYearLabels = JSON.parse(dataEl.dataset.revYearLabels || '[]');
    const revYearData = JSON.parse(dataEl.dataset.revYearData || '[]');

    // Dữ liệu status
    const paymentStatusLabels = JSON.parse(dataEl.dataset.paymentStatusLabels || '[]');
    const paymentStatusRevenue = JSON.parse(dataEl.dataset.paymentStatusRevenue || '[]');

    const formatVND = (value) => {
        if (value === null || value === undefined) return '';
        return Number(value).toLocaleString('vi-VN') + ' ₫';
    };

    function makeChart(el, type, labels, data, label, opts = {}) {
    const dataset = {
        label,
        data,
        backgroundColor: Array.isArray(data) && type === 'pie' ? 
            ['#0d6efd','#198754','#ffc107','#dc3545','#6f42c1','#20c997'] :
            'rgba(13,110,253,0.6)',
        borderColor: Array.isArray(data) && type === 'pie' ? '#fff' : '#0d6efd',
        borderWidth: 1,
        tension: 0.3
    };

    const config = {
        type,
        data: { labels, datasets: [dataset] },
        options: Object.assign({
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#0d6efd',
                    bodyColor: '#fff',
                    padding: 12,
                    borderColor: '#0d6efd',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            let val = 0;
                            if (typeof context.parsed === 'object' && context.parsed !== null) {
                                val = context.parsed.y;
                            } else {
                                val = context.parsed ?? context.raw ?? 0;
                            }
                            const displayVal = (val === 0.01 ? 0 : val);
                            return context.label + ': ' + formatVND(displayVal);
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#333',
                        font: { size: 13, weight: '600' },
                        padding: 20
                    }
                }
            },
            scales: type === 'pie' ? {} : {
                x: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { color: '#555' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        color: '#555',
                        callback: function(value) {
                            return Number(value).toLocaleString('vi-VN') + ' ₫';
                        }
                    }
                }
            },
            elements: {
                line: {
                    borderWidth: 3,
                    borderColor: 'rgba(13,110,253,0.9)',
                    fill: true,
                    backgroundColor: 'rgba(13,110,253,0.1)'
                },
                point: {
                    radius: 4,
                    hoverRadius: 6,
                    backgroundColor: '#0d6efd',
                    borderWidth: 2,
                    borderColor: '#fff'
                }
            }
        }, opts)
    };

    return new Chart(el, config);
}



    if (document.getElementById('categoryRevenueChart'))
        makeChart(categoryRevenueChart, 'bar', catLabels, catRevenue, 'Doanh thu (VNĐ)');

    if (document.getElementById('revenueByDateChart'))
        makeChart(revenueByDateChart, 'line', revDateLabels, revDateData, 'Doanh thu (VNĐ)', { elements: { line: { fill: true } } });

    if (document.getElementById('revenueByMonthChart'))
        makeChart(revenueByMonthChart, 'bar', revMonthLabels, revMonthData, 'Doanh thu (VNĐ)');

    if (document.getElementById('revenueByYearChart'))
        makeChart(revenueByYearChart, 'bar', revYearLabels, revYearData, 'Doanh thu (VNĐ)');

    // Biểu đồ theo status
    if (document.getElementById('revenueByPaymentStatusChart'))
        makeChart(revenueByPaymentStatusChart, 'pie', paymentStatusLabels, paymentStatusRevenue, 'Doanh thu (VNĐ)');
});
</script>
@endsection
