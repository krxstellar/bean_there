@extends('layouts.customer')

@section('content')
<style>
    .about-section {
        padding: 40px 5%;
        max-width: 900px; 
        margin: 0 auto;
        text-align: center;
    }

    .page-title {
        font-family: 'Cooper Black', serif;
        font-size: 2.2rem; 
        color: #4A2C2A; 
        margin-bottom: 30px;
        line-height: 1.2;
    }

    /* MAIN BOX CONTAINER */
    .about-container {
        background-color: transparent;
        border: 2px solid #4A2C2A;
        border-radius: 30px;
        overflow: hidden;
        display: flex;
        text-align: left;
        box-shadow: 0px 8px 20px rgba(74, 44, 42, 0.05);
    }

    .about-image {
        flex: 1;
        background-image: url('{{ asset('images/about-bakeshop.png') }}'); 
        background-size: cover;
        background-position: center;
        border-right: 2px solid #4A2C2A;
        min-height: 400px;
    }

    .about-text {
        flex: 1;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .about-text p {
        font-family: 'Poppins', sans-serif;
        font-weight: 400;
        font-size: 0.85rem;
        color: #4A2C2A;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .browse-btn {
        display: inline-block;
        width: fit-content;
        background-color: #AEA9A0;
        color: #4A2C2A;
        padding: 10px 25px;
        border-radius: 20px;
        text-decoration: none;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
        border: 1px solid #4A2C2A;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .browse-btn:hover {
        background-color: #4A2C2A;
        color: #FDF9F0;
    }

    /* STACK COLUMNS VERTICALLY ON SMALLER SCREENS */
    @media (max-width: 850px) {
        .about-container {
            flex-direction: column;
        }
        .about-image {
            border-right: none;
            border-bottom: 2px solid #4A2C2A;
            min-height: 250px;
        }
        .page-title {
            font-size: 1.8rem;
        }
    }
</style>

<div class="about-section">
    <h1 class="page-title">We believe in the power of great coffee and fresh pastries</h1>

    <div class="about-container">
        {{-- LEFT SIDE: VISUAL BRAND IMAGE --}}
        <div class="about-image"></div>

        {{-- RIGHT SIDE: BRAND STORY AND LINK --}}
        <div class="about-text">
            <p>
                We are a coffee and bakeshop brand built on the love for quality, comfort, and everyday indulgence. Where every cup of coffee is brewed with passion and every bite of our artisanal baked goods is crafted with precision, we make sure each order delivers both flavor and care.
            </p>
            <p>
                From rich, handcrafted coffee to freshly baked pastries, our products are thoughtfully made to satisfy your cravings—whether you’re starting your day or treating yourself. We believe good coffee and great bakes should be easy to enjoy, anytime and anywhere.
            </p>
            
            {{-- DYNAMIC LINK TO THE PASTRIES MENU --}}
            <a href="{{ route('menu.pastries') }}" class="browse-btn">Browse Our Products</a>

            @php
                $settings = cache('admin.settings', [
                    'store_address' => 'Quezon City, Metro Manila',
                    'hours_weekdays' => '8:00 AM - 7:00 PM',
                    'hours_weekend' => '9:00 AM - 5:00 PM',
                ]);
            @endphp

            <div style="border-radius:16px; padding:20px; margin-top:20px; box-shadow:0 6px 18px rgba(74,44,42,0.06); border:2px solid rgba(74,44,42,0.06);">
                <p style="margin:6px 0; color:#5a4a48; font-size:0.95rem;"><strong>Location:</strong> {{ $settings['store_address'] }}</p>
                <p style="margin:6px 0; color:#5a4a48; font-size:0.95rem;"><strong>Weekdays:</strong> {{ $settings['hours_weekdays'] }}</p>
                <p style="margin:6px 0 0 0; color:#5a4a48; font-size:0.95rem;"><strong>Weekends:</strong> {{ $settings['hours_weekend'] }}</p>
            </div>
        </div>
    </div>
</div>
@endsection