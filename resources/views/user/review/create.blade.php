@extends('layouts.user')

@section('title', '⭐ Đánh giá sản phẩm')

@section('content')
<style>
:root {
    --primary-color: #0d6efd;
    --star-color: #facc15;
    --input-radius: 8px;
    --button-radius: 8px;
}

/* Container */
.review-container {
    max-width: 600px;
    margin: 50px auto;
    padding: 25px;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

/* Product name */
.review-container h2 {
    margin-bottom: 20px;
    font-size: 1.5rem;
    text-align: center;
}

/* Star rating */
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
    margin-bottom: 20px;
}

.star-rating input[type="radio"] {
    display: none;
}

.star-rating label {
    font-size: 2rem;
    color: #ccc;
    cursor: pointer;
    transition: color 0.2s;
}

.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input[type="radio"]:checked ~ label {
    color: var(--star-color);
}

/* Comment box */
textarea {
    width: 100%;
    padding: 12px;
    border-radius: var(--input-radius);
    border: 1px solid #ced4da;
    resize: vertical;
    margin-bottom: 20px;
}

/* Submit button */
.btn-submit {
    display: block;
    width: 100%;
    padding: 12px;
    background: var(--primary-color);
    color: #fff;
    font-weight: bold;
    border: none;
    border-radius: var(--button-radius);
    cursor: pointer;
    transition: background 0.2s;
}

.btn-submit:hover {
    background: #0b5ed7;
}
</style>

<div class="review-container">
    <h2>Đánh giá sản phẩm: <strong>{{ $product->name ?? 'Sản phẩm' }}</strong></h2>

    <form action="{{ route('reviews.store', $product->id) }}" method="POST">
        @csrf

        <!-- Star Rating -->
        <div class="star-rating">
            @for($i=5; $i>=1; $i--)
                <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" required>
                <label for="star{{ $i }}">★</label>
            @endfor
        </div>

        <!-- Comment -->
        <textarea name="comment" rows="4" placeholder="Viết nhận xét của bạn..." required></textarea>

        <!-- Submit -->
        <button type="submit" class="btn-submit">Gửi đánh giá</button>
    </form>
</div>
@endsection
