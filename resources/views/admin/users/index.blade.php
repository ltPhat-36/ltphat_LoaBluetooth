@extends('layouts.admin')

@section('title', 'Danh sách người dùng')

@section('styles')
<style>
/* ===== Container ===== */
.user-container {
    margin-top: 20px; /* tránh topbar che */
    padding: 20px;
    background-color: #fdfdfd;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

/* ===== Summary box ===== */
.summary-box {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 20px;
}
.summary-box div {
    background: #0d6efd;
    color: #fff;
    padding: 15px;
    border-radius: 12px;
    flex: 1;
    min-width: 150px;
    text-align: center;
    font-weight: bold;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

/* ===== Filter & Export ===== */
.filter-container {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 15px;
}
.filter-container input,
.filter-container select,
.filter-container button {
    padding: 6px 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

/* ===== Table ===== */
.table-wrapper {
    overflow-x: auto;
}
table.user-table {
    width: 100%;
    border-collapse: collapse;
}
table.user-table th,
table.user-table td {
    padding: 12px 15px;
    text-align: left;
}
table.user-table th {
    background-color: #0d6efd;
    color: #fff;
    position: sticky;
    top: 0;
    z-index: 2;
}
table.user-table tr:nth-child(even) {
    background-color: #f6f6f6;
}
table.user-table tr:hover {
    background-color: #e2f0ff;
}

/* ===== Buttons ===== */
.btn-action {
    margin-right: 5px;
}

/* ===== Responsive ===== */
@media (max-width: 768px) {
    .summary-box { flex-direction: column; }
    .filter-container { flex-direction: column; gap: 10px; }
    table.user-table th, table.user-table td { font-size: 14px; padding: 8px 10px; }
}
</style>
@endsection

@section('content')
<div class="container user-container">
    <h2 class="mb-4">Danh sách người dùng</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tổng quan -->
    <div class="summary-box">
        <div>Tổng: {{ $users->count() }}</div>
        <div>Admin: {{ $users->where('role','admin')->count() }}</div>
        <div>User: {{ $users->where('role','customer')->count() }}</div>
    </div>

    <!-- Filter + Export -->
    <div class="filter-container">
        <input type="text" id="searchInput" placeholder="Tìm kiếm theo tên, email...">
        <select id="roleFilter">
            <option value="">Tất cả vai trò</option>
            <option value="admin">Admin</option>
            <option value="customer">User</option>
        </select>
        <button class="btn btn-success btn-export" onclick="exportTableToCSV('users.csv')">Xuất CSV</button>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Thêm người dùng</a>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <table class="user-table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm btn-action">Xem</a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm btn-action">Sửa</a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Bạn chắc chắn xóa?')" class="btn btn-danger btn-sm btn-action">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Không có người dùng nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Tìm kiếm live
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    document.querySelectorAll('#userTableBody tr').forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(filter) ? '' : 'none';
    });
});

// Filter theo vai trò
document.getElementById('roleFilter').addEventListener('change', function() {
    let filter = this.value.toLowerCase();
    document.querySelectorAll('#userTableBody tr').forEach(row => {
        let role = row.cells[3]?.innerText.toLowerCase() || '';
        row.style.display = (!filter || role === filter) ? '' : 'none';
    });
});

// Xuất CSV
function exportTableToCSV(filename) {
    let csv = [];
    let table = document.querySelector('.user-table');
    for (let row of table.rows) {
        let rowData = [];
        for (let cell of row.cells) {
            rowData.push('"' + cell.innerText + '"');
        }
        csv.push(rowData.join(','));
    }
    let csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
    let downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
}
</script>
@endsection
