@extends('layouts.admin')

@section('content')
<style>
/* Tổng thể */
body {
    background: #f5f7fa;
    font-family: 'Nunito', sans-serif;
}
.card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    padding: 30px;
    margin-top: 30px;
    background: #fff;
}
.card h4 {
    font-weight: 700;
    color: #333;
}

/* Button */
.btn-primary {
    background: linear-gradient(45deg,#5b86e5,#36d1dc);
    border: none;
    color: #fff;
    border-radius: 50px;
    padding: 10px 25px;
    font-weight: 500;
    transition: all 0.3s ease;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

.btn-outline-primary {
    border-radius: 50px;
    padding: 8px 20px;
    transition: all 0.3s ease;
}
.btn-outline-primary:hover {
    background: #5b86e5;
    color: #fff;
    border-color: #5b86e5;
}

/* Form */
.form-group label {
    font-weight: 600;
    color: #555;
}
.form-control, .form-control-file, select, textarea {
    border-radius: 12px;
    border: 1px solid #ddd;
    padding: 10px 15px;
    transition: all 0.3s ease;
}
.form-control:focus, select:focus, textarea:focus {
    border-color: #5b86e5;
    box-shadow: 0 0 5px rgba(91,134,229,0.3);
}

/* Hình ảnh */
img {
    border-radius: 12px;
    border: 1px solid #eee;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

/* Responsive */
@media (max-width: 768px) {
    .form-row {
        display: block;
    }
    .form-row > .form-group {
        width: 100%;
        margin-bottom: 15px;
    }
}

/* Error */
.alert-danger {
    border-radius: 12px;
    padding: 15px 20px;
}
</style>

<div class="container">
  <div class="card mx-auto" style="max-width: 900px;">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Edit Product</h4>
        <a class="btn btn-outline-primary" href="{{ route('admin.products.index') }}">⬅ Back</a>
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

      <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
          <label><strong>Category</strong></label>
          <select name="category_id" class="form-control" required>
            <option value="">-- Select category --</option>
            @foreach ($categories as $c)
              <option value="{{ $c->id }}" {{ old('category_id', $product->category_id) == $c->id ? 'selected' : '' }}>
                {{ $c->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group mb-3">
          <label><strong>Name</strong></label>
          <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="form-group mb-3">
          <label><strong>Description</strong></label>
          <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="form-row">
          <div class="form-group col-md-4 mb-3">
            <label><strong>Quantity</strong></label>
            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $product->quantity) }}" min="0" required>
          </div>
          <div class="form-group col-md-4 mb-3">
            <label><strong>Price</strong></label>
            <input type="text" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
          </div>
          <div class="form-group col-md-4 mb-3">
            <label><strong>Image</strong></label>
            <input type="file" name="image" class="form-control-file">
            @if($product->image)
              <div class="mt-2">
                <img src="{{ asset('storage/'.$product->image) }}" style="width:120px;height:auto;">
              </div>
            @endif
          </div>
        </div>

        <div class="form-group mb-4">
          <label><strong>Features</strong></label>
          <textarea name="features" class="form-control" rows="2">{{ old('features', $product->features) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100">Update Product</button>
      </form>
    </div>
  </div>
</div>
@endsection
