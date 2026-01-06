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

    .orders-table-container {
        background-color: #FDF9F0;
        border: 2px solid #4A2C2A;
        border-radius: 20px;
        overflow: hidden;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Poppins', sans-serif;
    }

    .orders-table thead {
        background-color: #4A2C2A;
        color: #FDF9F0;
    }

    .orders-table th {
        padding: 18px 20px;
        text-align: left;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .orders-table th:last-child {
        text-align: center;
    }

    .orders-table tbody tr {
        border-bottom: 1px solid rgba(74, 44, 42, 0.15);
        transition: background-color 0.2s;
    }

    .orders-table tbody tr:last-child {
        border-bottom: none;
    }

    .orders-table tbody tr:hover {
        background-color: rgba(74, 44, 42, 0.05);
    }

    .orders-table td {
        padding: 20px;
        font-size: 0.95rem;
    }

    .orders-table td:last-child {
        text-align: center;
    }

    .order-id {
        font-weight: 600;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.8rem;
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

    .details-btn {
        background-color: #4A2C2A;
        color: #FDF9F0;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
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

        .orders-table-container {
            overflow-x: auto;
        }

        .orders-table {
            min-width: 600px;
        }

        .orders-table th,
        .orders-table td {
            padding: 15px 12px;
            font-size: 0.85rem;
        }

        .details-btn {
            padding: 8px 16px;
            font-size: 0.8rem;
        }
    }
</style>

<div class="orders-section">
    <div class="orders-header">
        <h1 class="orders-title">Your Orders</h1>
        @if(!$orders->isEmpty())
            <a href="{{ route('welcome') }}" class="continue-shopping">Continue Shopping</a>
        @endif
    </div>

    @if($orders->isEmpty())
        <div class="empty-orders">
            <p>You haven't placed any orders yet.</p>
            <a href="{{ route('welcome') }}" class="shop-btn">Start Shopping</a>
        </div>
    @else
        <div class="orders-table-container">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td class="order-id">#{{ $order->id }}</td>
                            <td>{{ $order->placed_at->format('M d, Y') }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>₱{{ number_format($order->total, 2) }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="details-btn">View Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
