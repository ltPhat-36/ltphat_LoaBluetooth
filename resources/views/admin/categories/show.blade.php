@extends('layouts.admin')

@section('title','Chi tiết Danh mục')

@section('content')
<div class="container py-5">

    <!-- Header + Back Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">📂 Chi tiết Danh mục</h2>
        <a href="{{ route('admin.categories.index') }}" 
           class="btn btn-outline-primary btn-lg shadow-sm fw-bold">
           <i class="fa fa-arrow-left me-2"></i> Quay lại
        </a>
    </div>

    <!-- Card Show -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-bold text-muted">ID</label>
                <p class="fs-5">{{ $category->id }}</p>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold text-muted">Tên danh mục</label>
                <p class="fs-5">{{ $category->name }}</p>
            </div>

            <a href="{{ route('admin.categories.edit', $category->id) }}" 
               class="btn btn-warning btn-lg shadow-sm fw-bold">
               <i class="fa fa-edit me-2"></i> Chỉnh sửa
            </a>
        </div>
    </div>

</div>
@endsection
