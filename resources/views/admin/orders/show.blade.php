@extends('layouts.admin')

@section('admin-content')
<div>
    <h1 style="font-family:'Cooper Black', serif; color:#4A2C2A;">Order #{{ $order->id }}</h1>
    <p>Status: <strong>{{ ucfirst($order->status) }}</strong></p>
    <p>Total: <strong>₱{{ number_format($order->total, 2) }}</strong></p>
    <p>Customer: <strong>{{ $order->user->name ?? 'Guest' }}</strong></p>
    <p>Placed: <strong>{{ $order->placed_at?->format('Y-m-d H:i') }}</strong></p>

    <h3 style="margin-top:24px;">Shipping Address</h3>
    @if($shipping)
        <div>
            <div>{{ $shipping->full_name }}</div>
            <div>{{ $shipping->phone }}</div>
            <div>{{ $shipping->line1 }} {{ $shipping->line2 }}</div>
            <div>{{ $shipping->city }}, {{ $shipping->province }} {{ $shipping->postal_code }}</div>
        </div>
    @else
        <p>No shipping address.</p>
    @endif

    <h3 style="margin-top:24px;">Items</h3>
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="text-align:left; border-bottom:1px solid #eee;">
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr style="border-bottom:1px solid #f3f3f3;">
                    <td>{{ $item->product->name ?? ('#'.$item->product_id) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₱{{ number_format($item->unit_price, 2) }}</td>
                    <td>₱{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
