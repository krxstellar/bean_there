@extends('layouts.customer')

@section('content')
<style>
    .product-page { padding: 60px 8%; max-width: 1000px; margin: 0 auto; }
    .product-header { display:flex; gap:32px; align-items:flex-start; }
    .product-img { width: 380px; max-width: 45%; aspect-ratio: 1/1; height: auto; object-fit: cover; display:block; border-radius: 25px; border:2px solid #4A2C2A; box-shadow: 0 6px 12px rgba(74,44,42,0.15); }
    .product-info h1 { font-family:'Cooper Black', serif; color:#4A2C2A; margin:0 0 10px; }
    .product-info p.price { font-family:'Poppins', sans-serif; color:#9B8173; font-weight:700; margin:0 0 16px; }
    .add-to-cart-btn { background-color: transparent; color:#4A2C2A; border:1.5px solid #4A2C2A; padding:8px 12px; border-radius:12px; font-family:'Poppins', sans-serif; font-weight:600; cursor:pointer; }
</style>

<div class="product-page">
    <div class="product-header">
        <img class="product-img" src="{{ $product->image_url ? asset($product->image_url) : asset('images/Photo Unavailable..png') }}" alt="{{ $product->name }}">
        <div class="product-info">
            <h1>{{ $product->name }}</h1>
            <p class="price">{{ number_format($product->price, 2) }} PHP</p>
            @if($product->description)
                <p>{{ $product->description }}</p>
            @endif
            <form method="POST" action="{{ route('cart.add') }}" style="margin-top:16px;">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">
                <div class="qty-controls" style="display:inline-flex; align-items:center; gap:8px; margin-left:8px;">
                    <button type="button" class="qty-decrement" aria-label="Decrease" style="background:transparent; border:1.5px solid #4A2C2A; color:#4A2C2A; padding:6px 10px; border-radius:8px; cursor:pointer;">-</button>
                    <input type="number" name="quantity" value="1" min="1" class="qty-input" style="width: 70px; text-align:center;">
                    <button type="button" class="qty-increment" aria-label="Increase" style="background:transparent; border:1.5px solid #4A2C2A; color:#4A2C2A; padding:6px 10px; border-radius:8px; cursor:pointer;">+</button>
                </div>
                    <button type="submit" class="add-to-cart-btn" style="margin-left: 8px;">Add to cart</button>
            </form>
        </div>
    </div>
</div>
@endsection
