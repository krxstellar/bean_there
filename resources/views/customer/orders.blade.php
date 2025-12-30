@extends('layouts.customer')

@section('content')
<style>
    .orders-section {
        padding: 60px 8%;
        max-width: 1200px;
        margin: 0 auto;
        color: #4A2C2A;
    }

    .orders-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 40px;
    }

    .orders-title {
        font-family: 'Cooper Black', serif;
        font-size: 3rem;
        margin: 0;
        color: #4A2C2A;
    }

    .continue-shopping {
        font-family: 'Poppins', sans-serif;
        color: #4A2C2A;
        text-decoration: underline;
        font-weight: 500;
        font-size: 1rem;
    }

    .empty-orders {
        background-color: #FDF9F0;
        border: 2px solid #4A2C2A;
        border-radius: 20px;
        padding: 60px 40px;
        text-align: center;
    }

    .empty-orders p {
        font-family: 'Poppins', sans-serif;
        font-size: 1.1rem;
        margin-bottom: 20px;
    }

    .shop-btn {
        background-color: #4A2C2A;
        color: #FDF9F0;
        border: none;
        padding: 14px 40px;
        border-radius: 12px;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: opacity 0.3s;
    }

    .shop-btn:hover {
        opacity: 0.9;
        color: #FDF9F0;
    }

    .order-card {
        background-color: #FDF9F0;
        border: 2px solid #4A2C2A;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }

    .order-card:hover {
        box-shadow: 0px 8px 20px rgba(74, 44, 42, 0.15);
        transform: translateY(-3px);
    }

    .order-top {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(74, 44, 42, 0.2);
    }

    .order-info-label {
        font-family: 'Poppins', sans-serif;
        font-size: 0.85rem;
        color: #7a5c5a;
        margin-bottom: 5px;
    }

    .order-info-value {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        margin: 0;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-pending {
        background-color: #F5E6D3;
        color: #8B6914;
    }

    .status-processing {
        background-color: #D4EDDA;
        color: #155724;
    }

    .status-shipped {
        background-color: #CCE5FF;
        color: #004085;
    }

    .status-delivered {
        background-color: #C3E6CB;
        color: #155724;
    }

    .status-cancelled {
        background-color: #F8D7DA;
        color: #721C24;
    }

    .order-bottom {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        padding-top: 20px;
    }

    .order-items-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .order-items-list li {
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        color: #4A2C2A;
        margin-bottom: 6px;
    }

    .details-btn {
        background-color: #4A2C2A;
        color: #FDF9F0;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: opacity 0.3s;
    }

    .details-btn:hover {
        opacity: 0.9;
        color: #FDF9F0;
    }

    @media (max-width: 850px) {
        .orders-section {
            padding: 40px 5%;
        }

        .orders-title {
            font-size: 2.2rem;
        }

        .order-top {
            grid-template-columns: 1fr 1fr;
        }

        .order-bottom {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        .details-btn {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="orders-section">
    <div class="orders-header">
        <h1 class="orders-title">Your Orders</h1>
        <a href="{{ route('welcome') }}" class="continue-shopping">Continue Shopping</a>
    </div>

    @if($orders->isEmpty())
        <div class="empty-orders">
            <p>You haven't placed any orders yet.</p>
            <a href="{{ route('welcome') }}" class="shop-btn">Start Shopping</a>
        </div>
    @else
        <div class="orders-list">
            @foreach($orders as $order)
                <div class="order-card">
                    <div class="order-top">
                        <div>
                            <p class="order-info-label">Order ID</p>
                            <p class="order-info-value">#{{ $order->id }}</p>
                        </div>
                        <div>
                            <p class="order-info-label">Date</p>
                            <p class="order-info-value">{{ $order->placed_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="order-info-label">Status</p>
                            <span class="status-badge status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="order-info-label">Total</p>
                            <p class="order-info-value">₱{{ number_format($order->total, 2) }}</p>
                        </div>
                    </div>

                    <div class="order-bottom">
                        <div>
                            <p class="order-info-label">Items ({{ $order->items->count() }})</p>
                            <ul class="order-items-list">
                                @foreach($order->items->take(3) as $item)
                                    <li>{{ $item->product->name }} x{{ $item->quantity }}</li>
                                @endforeach
                                @if($order->items->count() > 3)
                                    <li style="color: #7a5c5a;">+{{ $order->items->count() - 3 }} more item(s)</li>
                                @endif
                            </ul>
                        </div>
                        <a href="{{ route('orders.show', $order) }}" class="details-btn">View Details</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
