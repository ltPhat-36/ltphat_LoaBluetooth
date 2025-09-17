@extends('layouts.admin')

@section('title','Quản lý Danh mục')

@section('content')
<div class="container py-5">

    <!-- Header + Create Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">📂 Quản lý Danh mục</h2>
        <a href="{{ route('admin.categories.create') }}" 
           class="btn btn-success btn-lg shadow-sm text-white fw-bold">
           <i class="fa fa-plus-circle me-2"></i> Thêm Danh mục mới
        </a>
    </div>

    <!-- Alert -->
    @if ($message = Session::get('success'))
      <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
          <i class="fa fa-check-circle me-2"></i> {{ $message }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase">
                        <tr>
                            <th style="width:80px">ID</th>
                            <th>Tên danh mục</th>
                            <th style="width:300px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td class="fw-medium">{{ $category->name }}</td>
                            <td>
                                <a class="btn btn-info btn-sm me-1" 
                                   href="{{ route('admin.categories.show', $category->id) }}">
                                   <i class="fa fa-eye me-1"></i> Xem
                                </a>
                                <a class="btn btn-primary btn-sm me-1" 
                                   href="{{ route('admin.categories.edit', $category->id) }}">
                                   <i class="fa fa-edit me-1"></i> Sửa
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Xóa danh mục này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash me-1"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">Chưa có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    // Optional: có thể thêm tooltip
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    tooltipTriggerList.forEach(t => new bootstrap.Tooltip(t))
</script>
@endsection
