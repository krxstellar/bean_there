@extends('layouts.customer')

@section('content')
<style>
    .drinks-section {
        padding: 60px 8%; 
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-title {
        font-family: 'Cooper Black', serif;
        font-size: 2.5rem; 
        letter-spacing: -1px;
        color: #4A2C2A;
        margin-bottom: 40px;
        text-align: left;
    }

    .sub-category {
        border-bottom: 1px solid rgba(74, 44, 42, 0.2);
        margin-bottom: 35px;
        padding-bottom: 10px;
    }

    .sub-category h2 {
        font-family: 'Cooper Black', serif;
        font-size: 1.8rem;
        color: #4A2C2A;
        margin: 0;
    }

    /* --- PRODUCT GRID --- */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr); 
        gap: 30px 20px;
        margin-bottom: 80px;
    }

    .product-card {
        display: flex;
        flex-direction: column;
        max-width: 220px; 
    }

    .product-image {
        width: 100%;
        aspect-ratio: 1 / 1;
        border-radius: 25px; 
        object-fit: cover;
        margin-bottom: 12px;
        border: 2px solid #4A2C2A; 
        box-shadow: 0px 6px 12px rgba(74, 44, 42, 0.15);
        box-sizing: border-box;
    }

    .product-info h3 {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 0.95rem; 
        margin: 0;
        color: #4A2C2A;
    }

    .product-info p {
        font-family: 'Poppins', sans-serif;
        font-size: 0.85rem;
        margin: 2px 0 12px 0;
        color: #9B8173; 
        font-weight: 600;
    }

    .add-to-cart-btn {
        background-color: transparent; 
        color: #4A2C2A;
        border: 1.5px solid #4A2C2A; 
        padding: 8px;
        border-radius: 12px;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 0.7rem;
        cursor: pointer;
        text-transform: capitalize;
        width: 100%;
        transition: all 0.3s ease;
    }

    .add-to-cart-btn:hover {
        background-color: #4A2C2A;
        color: #FDF9F0;
        transform: scale(1.05); 
    }

    #no-results-container {
        display: none;
        text-align: center;
        padding: 100px 20px;
    }

    #no-results-container h2 {
        font-family: 'Cooper Black', serif;
        color: #4A2C2A;
        font-size: 1.8rem;
    }

    @media (max-width: 900px) {
        .product-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<div class="drinks-section">
    <h1 class="page-title">Drinks</h1>

    {{-- CHECK IF THERE ARE ANY PRODUCTS --}}
    @php
        $hasProducts = ($subcategories ?? collect())->flatMap->products->isNotEmpty() || ($productsWithoutSubcategory ?? collect())->isNotEmpty();
    @endphp

    @if($hasProducts)
        {{-- PRODUCTS WITHOUT SUBCATEGORY --}}
        @if(($productsWithoutSubcategory ?? collect())->isNotEmpty())
            <div class="category-section">
                <div class="product-grid">
                    @foreach($productsWithoutSubcategory as $p)
                        <div class="product-card">
                            <a href="{{ route('products.show', $p->slug) }}">
                                <img src="{{ $p->image_url ? asset($p->image_url) : asset('images/Photo Unavailable..png') }}" class="product-image" alt="{{ $p->name }}">
                            </a>
                            <div class="product-info">
                                <h3>{{ $p->name }}</h3>
                                <p>{{ number_format($p->price, 2) }} PHP</p>
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $p->id }}">
                                    <div class="qty-controls" style="display:inline-flex; align-items:center; gap:8px; margin-right:8px;">
                                        <button type="button" class="qty-decrement" aria-label="Decrease" style="background:transparent; border:1.5px solid #4A2C2A; color:#4A2C2A; padding:6px 10px; border-radius:8px; cursor:pointer;">-</button>
                                        <input type="number" name="quantity" value="1" min="1" class="qty-input" style="width: 70px; text-align:center;">
                                        <button type="button" class="qty-increment" aria-label="Increase" style="background:transparent; border:1.5px solid #4A2C2A; color:#4A2C2A; padding:6px 10px; border-radius:8px; cursor:pointer;">+</button>
                                    </div>
                                    <button type="submit" class="add-to-cart-btn">Add to cart</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- PRODUCTS GROUPED BY SUBCATEGORY --}}
        @foreach($subcategories ?? [] as $subcategory)
            @if($subcategory->products->isNotEmpty())
                <div class="category-section">
                    <div class="sub-category">
                        <h2>{{ $subcategory->name }}</h2>
                    </div>
                    <div class="product-grid">
                        @foreach($subcategory->products as $p)
                            <div class="product-card">
                                <a href="{{ route('products.show', $p->slug) }}">
                                    <img src="{{ $p->image_url ? asset($p->image_url) : asset('images/Photo Unavailable..png') }}" class="product-image" alt="{{ $p->name }}">
                                </a>
                                <div class="product-info">
                                    <h3>{{ $p->name }}</h3>
                                    <p>{{ number_format($p->price, 2) }} PHP</p>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $p->id }}">
                                            <div class="qty-controls" style="display:inline-flex; align-items:center; gap:8px; margin-right:8px;">
                                                <button type="button" class="qty-decrement" aria-label="Decrease" style="background:transparent; border:1.5px solid #4A2C2A; color:#4A2C2A; padding:6px 10px; border-radius:8px; cursor:pointer;">-</button>
                                                <input type="number" name="quantity" value="1" min="1" class="qty-input" style="width: 70px; text-align:center;">
                                                <button type="button" class="qty-increment" aria-label="Increase" style="background:transparent; border:1.5px solid #4A2C2A; color:#4A2C2A; padding:6px 10px; border-radius:8px; cursor:pointer;">+</button>
                                            </div>
                                            <button type="submit" class="add-to-cart-btn">Add to cart</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    @else
        {{-- NO PRODUCTS FOUND --}}
        <div style="display:flex; justify-content:center; align-items:center; min-height:30vh; width:100%;">
            <div style="text-align:center;">
                <h2>No drinks found</h2>
                <p style="color: #9B8173;">Please check back later.</p>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('navSearchInput');
        const noResults = document.getElementById('no-results-container');
        const searchTermDisplay = document.getElementById('search-term-display');
        const categories = document.querySelectorAll('.category-section');

        function filterDrinks(query) {
            const filter = query.toLowerCase().trim();
            let totalVisible = 0;

            categories.forEach(section => {
                const cards = section.querySelectorAll('.product-card');
                let sectionHasMatch = false;

                cards.forEach(card => {
                    const h3 = card.querySelector('h3');
                    if (h3) {
                        const productName = h3.textContent.toLowerCase();
                        if (productName.includes(filter)) {
                            card.style.display = "";
                            sectionHasMatch = true;
                            totalVisible++;
                        } else {
                            card.style.display = "none";
                        }
                    }
                });
                section.style.display = sectionHasMatch ? "block" : "none";
            });

            if (totalVisible === 0 && filter !== "") {
                noResults.style.display = "block";
                searchTermDisplay.textContent = filter;
            } else {
                noResults.style.display = "none";
            }
        }

        if(searchInput) {
            searchInput.addEventListener('input', function() {
                filterDrinks(this.value);
            });
        }

        const urlParams = new URLSearchParams(window.location.search);
        const searchQuery = urlParams.get('search');
        
        if (searchQuery) {
            if (searchInput) {
                searchInput.value = searchQuery;
                searchInput.classList.add('active'); 
            }
            filterDrinks(searchQuery);
        }
    });
</script>
@endsection