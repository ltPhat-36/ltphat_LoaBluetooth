@extends('layouts.admin')

@section('title','Sửa Danh mục')

@section('content')
<div class="container py-5">

    <!-- Header + Back Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">✏️ Sửa Danh mục</h2>
        <a href="{{ route('admin.categories.index') }}" 
           class="btn btn-outline-primary btn-lg shadow-sm fw-bold">
           <i class="fa fa-arrow-left me-2"></i> Quay lại
        </a>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
          <i class="fa fa-exclamation-triangle me-2"></i> 
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Edit Form -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
              @csrf
              @method('PUT')

              <div class="mb-3">
                <label for="name" class="form-label fw-bold">Tên danh mục</label>
                <input type="text" id="name" name="name" 
                       class="form-control form-control-lg" 
                       placeholder="Nhập tên danh mục" 
                       value="{{ old('name', $category->name) }}" required>
              </div>

              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-lg shadow-sm fw-bold">
                    <i class="fa fa-save me-2"></i> Cập nhật
                </button>
                <a href="{{ route('admin.categories.index') }}" 
                   class="btn btn-secondary btn-lg shadow-sm fw-bold">
                   <i class="fa fa-times me-2"></i> Hủy
                </a>
              </div>
            </form>
        </div>
    </div>

</div>
@endsection
