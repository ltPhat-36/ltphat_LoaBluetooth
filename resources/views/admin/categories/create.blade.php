@extends('layouts.admin')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Create New Category</h4>
      <a class="btn btn-outline-primary" href="{{ route('categories.index') }}">Back</a>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.categories.store') }}" method="POST">
      @csrf
      <div class="form-group">
        <label for="name"><strong>Name</strong></label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}">
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</div>
@endsection
