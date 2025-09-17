@extends('layouts.app')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Create New Product</h4>
      <a class="btn btn-outline-primary" href="{{ route('admin.products.index') }}">Back</a>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="form-group">
        <label><strong>Category</strong></label>
        <select name="category_id" class="form-control" required>
          <option value="">-- Select category --</option>
          @foreach ($categories as $c)
            <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>
              {{ $c->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label><strong>Name</strong></label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
      </div>

      <div class="form-group">
        <label><strong>Description</strong></label>
        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
      </div>

      <div class="form-row">
        <div class="form-group col-md-4">
          <label><strong>Quantity</strong></label>
          <input type="number" name="quantity" class="form-control" value="{{ old('quantity',0) }}" min="0" required>
        </div>
        <div class="form-group col-md-4">
          <label><strong>Price</strong></label>
          <input type="text" name="price" class="form-control" value="{{ old('price',0) }}" required>
        </div>
        <div class="form-group col-md-4">
          <label><strong>Image</strong></label>
          <input type="file" name="image" class="form-control-file">
        </div>
      </div>

      <div class="form-group">
        <label><strong>Features</strong></label>
        <textarea name="features" class="form-control" rows="2" placeholder="Các đặc điểm: bluetooth 5.0, pin 10h,...">{{ old('features') }}</textarea>
      </div>

      <button type="submit" class="btn btn-primary">Create</button>
    </form>
  </div>
</div>
@endsection
