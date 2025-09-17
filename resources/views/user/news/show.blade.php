@extends('layouts.user')
@section('title', $newsItem->title)

@section('content')
<h1>{{ $newsItem->title }}</h1>
<p><small>Ngày đăng: {{ $newsItem->created_at->format('d/m/Y') }}</small></p>
@if($newsItem->image)
    <img src="{{ asset('storage/' . $newsItem->image) }}" class="img-fluid mb-3" alt="{{ $newsItem->title }}">
@endif
<div>{!! $newsItem->content !!}</div>
<a href="{{ route('news.index') }}" class="btn btn-secondary mt-3">← Quay lại danh sách</a>
@endsection
