@extends('layouts.admin')

@section('title', 'Báo cáo')

@section('styles')
<style>
/* Container tổng thể */
.report-container {
    padding: 20px;
    background-color: #fdfdfd;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-top: 20px; /* tránh topbar che nội dung */
}

/* Summary boxes */
.summary-box {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 30px;
}
.summary-box div {
    background: #0d6efd;
    color: #fff;
    padding: 20px;
    border-radius: 12px;
    flex: 1;
    min-width: 180px;
    text-align: center;
    font-weight: bold;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

/* Bảng báo cáo */
.table-wrapper {
    overflow-x: auto;
    margin-bottom: 40px;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
}

.report-table th,
.report-table td {
    padding: 12px 15px;
    text-align: left;
}

.report-table th {
    background-color: #0d6efd;
    color: #fff;
    position: sticky;
    top: 0; /* giữ header khi scroll */
    z-index: 2;
}

.report-table tr:nth-child(even) {
    background-color: #f6f6f6;
}

.report-table tr:hover {
    background-color: #e2f0ff;
}

/* Button xuất CSV */
.btn-export {
    margin-bottom: 15px;
}

/* Responsive nhỏ hơn 768px */
@media (max-width: 768px) {
    .summary-box {
        flex-direction: column;
    }
}
</style>
@endsection

@section('content')
<div class="container report-container">
    <h1 class="mb-4">Báo cáo</h1>

    <!-- Summary -->
    <div class="summary-box">
        <div>Tổng đơn hàng: {{ $totalOrders }}</div>
        <div>Tổng khách hàng: {{ $totalCustomers }}</div>
        <div>Tổng doanh thu: {{ number_format($revenueByYear->sum('total_revenue'), 0, ',', '.') }} VND</div>
    </div>

    <!-- Export CSV -->
    <button class="btn btn-success btn-export" onclick="exportTableToCSV('report.csv')">
        <i class="bi bi-download me-2"></i> Xuất báo cáo CSV
    </button>

    <!-- Báo cáo theo danh mục -->
    <h3>Doanh thu theo từng danh mục</h3>
    <div class="table-wrapper">
        <table class="report-table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Danh mục</th>
                    <th>Tổng doanh thu</th>
                    <th>Tổng sản phẩm bán ra</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categoryRevenue as $revenue)
                <tr>
                    <td>{{ $revenue->category_name ?? $revenue->category_id }}</td>
                    <td>{{ number_format((float)$revenue->total_revenue, 0, ',', '.') }} VND</td>
                    <td>{{ $revenue->total_qty }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Báo cáo theo ngày -->
    <h3>Doanh thu theo ngày</h3>
    <div class="table-wrapper">
        <table class="report-table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Ngày</th>
                    <th>Tổng doanh thu</th>
                    <th>Số đơn hàng</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revenueByDate as $revenue)
                <tr>
                    @php
                        try {
                            $d = \Carbon\Carbon::parse($revenue->date)->format('d/m/Y');
                        } catch (\Exception $e) {
                            $d = $revenue->date;
                        }
                    @endphp
                    <td>{{ $d }}</td>
                    <td>{{ number_format((float)$revenue->total_revenue, 0, ',', '.') }} VND</td>
                    <td>{{ $revenue->order_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Báo cáo theo tháng -->
    <h3>Doanh thu theo tháng</h3>
    <div class="table-wrapper">
        <table class="report-table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tháng</th>
                    <th>Tổng doanh thu</th>
                    <th>Số đơn hàng</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revenueByMonth as $revenue)
                <tr>
                    <td>{{ $revenue->month ?? '' }}</td>
                    <td>{{ number_format((float)$revenue->total_revenue, 0, ',', '.') }} VND</td>
                    <td>{{ $revenue->order_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Báo cáo theo năm -->
    <h3>Doanh thu theo năm</h3>
    <div class="table-wrapper">
        <table class="report-table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Năm</th>
                    <th>Tổng doanh thu</th>
                    <th>Số đơn hàng</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revenueByYear as $revenue)
                <tr>
                    <td>{{ $revenue->year }}</td>
                    <td>{{ number_format((float)$revenue->total_revenue, 0, ',', '.') }} VND</td>
                    <td>{{ $revenue->order_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
function exportTableToCSV(filename) {
    let csv = [];
    document.querySelectorAll('.report-table').forEach(table => {
        for (let row of table.rows) {
            let rowData = [];
            for (let cell of row.cells) {
                rowData.push('"' + cell.innerText.replace(/"/g, '""') + '"');
            }
            csv.push(rowData.join(','));
        }
        csv.push(''); // dòng trống giữa các bảng
    });
    let csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
    let downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
</script>
@endsection
