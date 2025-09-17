@extends('layouts.admin')

@section('title','Quản lý Sản phẩm')

@section('content')
<div class="container py-5">

    <!-- Header + Create Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">📦 Quản lý Sản phẩm (Loa Bluetooth)</h2>
        <a href="{{ route('admin.products.create') }}" 
           class="btn btn-success btn-lg shadow-sm fw-bold">
           <i class="fa fa-plus-circle me-2"></i> Thêm sản phẩm
        </a>
    </div>

    <!-- Alert Success -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Product Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light text-uppercase small">
                        <tr>
                            <th style="width:60px">ID</th>
                            <th style="width:120px">Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th style="width:100px">Quantity</th>
                            <th style="width:120px">Price</th>
                            <th style="width:220px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $p)
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td>
                                @if ($p->image)
                                    <img src="{{ asset('storage/'.$p->image) }}" 
                                         alt="{{ $p->name }}" 
                                         class="rounded shadow-sm" 
                                         style="width:100px;height:auto;">
                                @else
                                    <span class="text-muted small">No image</span>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $p->name }}</td>
                            <td>{{ $p->category->name ?? '-' }}</td>
                            <td>{{ $p->quantity }}</td>
                            <td>{{ number_format($p->price, 0, ',', '.') }} ₫</td>
                            <td>
                                <a class="btn btn-info btn-sm shadow-sm mb-1" href="{{ route('admin.products.show', $p->id) }}">
                                    <i class="fa fa-eye me-1"></i> Show
                                </a>
                                <a class="btn btn-primary btn-sm shadow-sm mb-1" href="{{ route('admin.products.edit', $p->id) }}">
                                    <i class="fa fa-edit me-1"></i> Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm shadow-sm mb-1">
                                        <i class="fa fa-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Không có sản phẩm</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
