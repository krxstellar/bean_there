@extends('layouts.customer')

@section('content')
<style>
    .shipping-section {
        padding: 40px 5%;
        max-width: 900px; 
        margin: 0 auto;
        text-align: center;
    }

    .page-title {
        font-family: 'Cooper Black', serif;
        font-size: 2.2rem; 
        color: #4A2C2A;
        margin-bottom: 20px;
        letter-spacing: -0.5px;
    }

    .shipping-intro {
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        color: #4A2C2A;
        margin-bottom: 30px;
        line-height: 1.6;
    }

    .shipping-container {
        background-color: transparent;
        border: 2px solid #4A2C2A; 
        border-radius: 30px; 
        padding: 40px;
        text-align: left;
        box-shadow: 0px 8px 20px rgba(74, 44, 42, 0.05);
    }

    .shipping-item {
        margin-bottom: 25px;
    }

    .shipping-item h3 {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 1rem;
        color: #4A2C2A;
        margin-bottom: 6px;
    }

    .shipping-item p {
        font-family: 'Poppins', sans-serif;
        font-weight: 400;
        font-size: 0.85rem; 
        color: #4A2C2A;
        line-height: 1.5;
        margin: 0;
    }

    .read-btn {
        display: inline-block;
        background-color: #937C6F; 
        color: #FDF9F0;
        padding: 12px 35px;
        border-radius: 25px;
        text-decoration: none;
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        font-size: 0.9rem;
        transition: opacity 0.3s ease;
        margin-top: 30px;
    }

    .read-btn:hover {
        opacity: 0.85;
    }

    @media (max-width: 850px) {
        .shipping-container {
            padding: 25px;
        }
        .page-title {
            font-size: 1.8rem;
        }
    }
</style>

<div class="shipping-section">
    <h1 class="page-title">Shipping Policy</h1>
    
    <p class="shipping-intro">
        Read our shipping policy to understand how we process our deliveries and pick-ups.
    </p>

    <div class="shipping-container">
        <div class="shipping-item">
            <h3>How we process deliveries</h3>
            <p>We ensure all baked goods are packed securely to maintain freshness. Deliveries are scheduled within Metro Manila from Monday to Saturday to get your favorites to you as quickly as possible.</p>
        </div>

        <div class="shipping-item">
            <h3>Pick-up Details</h3>
            <p>For those who prefer to pick up their orders, you can visit our designated location during operating hours. Please wait for your "Ready for Pick-up" notification before heading over.</p>
        </div>

        <div class="shipping-item">
            <h3>Standard Lead Time</h3>
            <p>Most orders require a 1-day lead time. This allows our bakers to prepare your items fresh from the oven before they reach your doorstep.</p>
        </div>
    </div>
</div>
@endsection