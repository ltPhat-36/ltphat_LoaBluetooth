@extends('layouts.user')
@section('title', 'Tin tức')

@section('content')
<h1 class="mb-4">Tin tức & Tuyển dụng Phát Store</h1>

@php
    // Tin nổi bật
    $featuredNews = [
        [
            'title' => 'Loa Bluetooth XYZ ra mắt phiên bản 2025',
            'slug' => 'loa-bluetooth-xyz-2025',
            'content' => 'Loa Bluetooth XYZ 2025 mang đến âm thanh sống động, pin 20h và chống nước hoàn hảo cho mọi party ngoài trời.',
            'image' => 'https://picsum.photos/seed/featured1/800/450',
        ],
        [
            'title' => 'Loa mini nhưng âm thanh cực chất',
            'slug' => 'loa-mini-am-thanh-chat',
            'content' => 'Mặc dù nhỏ gọn, loa Bluetooth mini này vẫn đem lại âm thanh sống động, bass mạnh và kết nối ổn định.',
            'image' => 'https://picsum.photos/seed/featured2/800/450',
        ],
    ];

    // Tin mới nhất
    $latestNews = [];
    for($i=1; $i<=4; $i++){
        $latestNews[] = [
            'title' => "Tin mới nhất về Loa Bluetooth #$i",
            'slug' => "tin-moi-nhat-$i",
            'content' => "Thông tin chi tiết về loa Bluetooth #$i. Tính năng, pin, âm thanh và đánh giá thực tế.",
            'image' => "https://picsum.photos/seed/latest$i/400/250",
        ];
    }

    // Sản phẩm mới
    $newProducts = [];
    for($i=1; $i<=4; $i++){
        $newProducts[] = [
            'title' => "Sản phẩm mới Loa Bluetooth #$i",
            'slug' => "san-pham-moi-$i",
            'content' => "Loa Bluetooth mới với thiết kế hiện đại, âm thanh chất lượng và giá hợp lý.",
            'image' => "https://picsum.photos/seed/product$i/400/250",
        ];
    }

    // Tuyển dụng
    $careers = [
        [
            'title' => 'Tuyển nhân viên bán hàng tại Phát Store',
            'slug' => 'tuyen-nhan-vien-ban-hang',
            'content' => 'Mức lương hấp dẫn, môi trường thân thiện, đào tạo kỹ năng bán hàng chuyên nghiệp.',
            'image' => 'https://picsum.photos/seed/career1/400/250',
        ],
        [
            'title' => 'Tuyển lập trình viên Laravel',
            'slug' => 'tuyen-lap-trinh-vien-laravel',
            'content' => 'Tham gia dự án thương mại điện tử, môi trường năng động, cơ hội thăng tiến.',
            'image' => 'https://picsum.photos/seed/career2/400/250',
        ],
    ];
@endphp

{{-- Tin nổi bật --}}
<h2 class="mb-3">Tin nổi bật</h2>
<div class="row mb-5">
    @foreach($featuredNews as $item)
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-lg">
                <img src="{{ $item['image'] }}" class="card-img-top" alt="{{ $item['title'] }}">
                <div class="card-body">
                    <h3 class="card-title">{{ $item['title'] }}</h3>
                    <p class="card-text">{{ Str::limit(strip_tags($item['content']), 180) }}</p>
                    <a href="{{ route('news.show', $item['slug']) }}" class="btn btn-primary btn-sm">Xem chi tiết</a>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Tin mới nhất & Tuyển dụng --}}
<div class="row mb-5">
    <div class="col-md-8">
        <h2 class="mb-3">Tin mới nhất</h2>
        <div class="row">
            @foreach($latestNews as $item)
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ $item['image'] }}" class="card-img-top" alt="{{ $item['title'] }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $item['title'] }}</h5>
                            <p class="card-text">{{ Str::limit(strip_tags($item['content']), 100) }}</p>
                            <a href="{{ route('news.show', $item['slug']) }}" class="btn btn-primary btn-sm mt-auto">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-4">
        <h2 class="mb-3">Tuyển dụng</h2>
        @foreach($careers as $item)
            <div class="card mb-4 shadow-sm">
                <img src="{{ $item['image'] }}" class="card-img-top" alt="{{ $item['title'] }}">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title">{{ $item['title'] }}</h6>
                    <p class="card-text">{{ Str::limit(strip_tags($item['content']), 80) }}</p>
                    <a href="{{ route('news.show', $item['slug']) }}" class="btn btn-success btn-sm mt-auto">Chi tiết</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Sản phẩm mới nhất --}}
<h2 class="mb-3">Sản phẩm mới nhất</h2>
<div class="row">
    @foreach($newProducts as $item)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ $item['image'] }}" class="card-img-top" alt="{{ $item['title'] }}">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title">{{ $item['title'] }}</h6>
                    <p class="card-text">{{ Str::limit(strip_tags($item['content']), 80) }}</p>
                    <a href="{{ route('news.show', $item['slug']) }}" class="btn btn-primary btn-sm mt-auto">Xem chi tiết</a>
                </div>
            </div>
        </div>
    @endforeach
</div>

@endsection
