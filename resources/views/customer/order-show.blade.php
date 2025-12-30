@extends('layouts.customer')

@section('content')
<style>
    .order-detail-section {
        padding: 60px 8%;
        max-width: 1200px;
        margin: 0 auto;
        color: #4A2C2A;
    }

    .back-link {
        font-family: 'Poppins', sans-serif;
        color: #4A2C2A;
        text-decoration: none;
        font-weight: 500;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 30px;
        transition: opacity 0.3s;
    }

    .back-link:hover {
        opacity: 0.7;
        color: #4A2C2A;
    }

    .order-detail-title {
        font-family: 'Cooper Black', serif;
        font-size: 2.5rem;
        margin: 0 0 30px 0;
        color: #4A2C2A;
    }

    .order-detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }

    .detail-card {
        background-color: #FDF9F0;
        border: 2px solid #4A2C2A;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 25px;
    }

    .detail-card-title {
        font-family: 'Cooper Black', serif;
        font-size: 1.3rem;
        color: #4A2C2A;
        margin: 0 0 20px 0;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(74, 44, 42, 0.2);
    }

    .order-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .meta-label {
        font-family: 'Poppins', sans-serif;
        font-size: 0.85rem;
        color: #7a5c5a;
        margin-bottom: 5px;
    }

    .meta-value {
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

    .status-pending { background-color: #F5E6D3; color: #8B6914; }
    .status-processing { background-color: #D4EDDA; color: #155724; }
    .status-shipped { background-color: #CCE5FF; color: #004085; }
    .status-delivered { background-color: #C3E6CB; color: #155724; }
    .status-cancelled { background-color: #F8D7DA; color: #721C24; }

    .items-table {
        width: 100%;
        border-collapse: collapse;
    }

    .items-table th {
        font-family: 'Poppins', sans-serif;
        font-size: 0.85rem;
        color: #7a5c5a;
        font-weight: 500;
        text-align: left;
        padding: 10px 0;
        border-bottom: 1px solid rgba(74, 44, 42, 0.2);
    }

    .items-table th:last-child {
        text-align: right;
    }

    .items-table td {
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        padding: 15px 0;
        border-bottom: 1px solid rgba(74, 44, 42, 0.1);
    }

    .items-table td:last-child {
        text-align: right;
        font-weight: 600;
    }

    .items-table a {
        color: #4A2C2A;
        text-decoration: none;
        font-weight: 500;
    }

    .items-table a:hover {
        text-decoration: underline;
    }

    .address-text {
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        line-height: 1.7;
    }

    .address-text strong {
        font-weight: 600;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        margin-bottom: 12px;
    }

    .summary-row.total {
        font-weight: 700;
        font-size: 1.1rem;
        padding-top: 15px;
        margin-top: 15px;
        border-top: 2px solid #4A2C2A;
    }

    .tracking-box {
        background-color: rgba(74, 44, 42, 0.08);
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
    }

    .tracking-box strong {
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        display: block;
        margin-bottom: 8px;
    }

    .tracking-box p {
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        margin: 0;
        color: #5a4a48;
    }

    .back-btn {
        background-color: #4A2C2A;
        color: #FDF9F0;
        border: none;
        padding: 14px 30px;
        border-radius: 12px;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: block;
        text-align: center;
        margin-top: 25px;
        transition: opacity 0.3s;
    }

    .back-btn:hover {
        opacity: 0.9;
        color: #FDF9F0;
    }

    @media (max-width: 850px) {
        .order-detail-section {
            padding: 40px 5%;
        }

        .order-detail-title {
            font-size: 2rem;
        }

        .order-detail-grid {
            grid-template-columns: 1fr;
        }

        .order-meta {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="order-detail-section">
    <h1 class="order-detail-title">Order #{{ $order->id }}</h1>

    <div class="order-detail-grid">
        <div class="order-main">
            <!-- Order Information -->
            <div class="detail-card">
                <h2 class="detail-card-title">Order Information</h2>
                <div class="order-meta">
                    <div>
                        <p class="meta-label">Order Date</p>
                        <p class="meta-value">{{ $order->placed_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    <div>
                        <p class="meta-label">Status</p>
                        <span class="status-badge status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="detail-card">
                <h2 class="detail-card-title">Order Items</h2>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('products.show', $item->product->slug) }}">
                                        {{ $item->product->name }}
                                    </a>
                                </td>
                                <td>₱{{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₱{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Shipping Address -->
            @if($order->shippingAddress)
                <div class="detail-card">
                    <h2 class="detail-card-title">Shipping Address</h2>
                    <div class="address-text">
                        <strong>{{ $order->shippingAddress->full_name }}</strong><br>
                        {{ $order->shippingAddress->line1 }}<br>
                        @if($order->shippingAddress->line2)
                            {{ $order->shippingAddress->line2 }}<br>
                        @endif
                        {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->province }} {{ $order->shippingAddress->postal_code }}<br>
                        <strong>Phone:</strong> {{ $order->shippingAddress->phone }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Order Summary Sidebar -->
        <div class="order-sidebar">
            <div class="detail-card">
                <h2 class="detail-card-title">Order Summary</h2>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>₱{{ number_format($order->total, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span>FREE</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span>₱{{ number_format($order->total, 2) }}</span>
                </div>

                <div class="tracking-box">
                    <strong>Tracking Status</strong>
                    <p>
                        @switch($order->status)
                            @case('pending')
                                Your order is being prepared.
                                @break
                            @case('processing')
                                Your order is being processed.
                                @break
                            @case('shipped')
                                Your order has been shipped!
                                @break
                            @case('delivered')
                                Your order has been delivered.
                                @break
                            @case('cancelled')
                                Your order was cancelled.
                                @break
                            @default
                                Check back soon for updates.
                        @endswitch
                    </p>
                </div>

                <a href="{{ route('orders.index') }}" class="back-btn">Back to Orders</a>
            </div>
        </div>
    </div>
</div>
@endsection
