@extends('layouts.admin')

@section('admin-content')
<div>
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
        <h1 style="font-family:'Cooper Black', serif; color:#4A2C2A; margin:0;">Order #{{ $order->id }}</h1>
        <a href="{{ route('admin.orders') }}" style="color:#4A2C2A; text-decoration:none;">&larr; Back to Orders</a>
    </div>

    @if(session('success'))
        <div style="background:#d4edda; border:1px solid #c3e6cb; color:#155724; padding:12px 16px; border-radius:8px; margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Order Status Update Form --}}
    <div style="background:#FDF9F0; padding:20px; border-radius:12px; margin-bottom:24px;">
        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
            @csrf
            @method('PATCH')
            <label style="font-weight:600; color:#4A2C2A;">Status:</label>
            <select name="status" style="padding:10px 16px; border-radius:8px; border:1px solid #ccc; font-size:14px; min-width:160px;">
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" style="background:#4A2C2A; color:white; padding:10px 24px; border:none; border-radius:8px; cursor:pointer; font-weight:600;">
                Update Status
            </button>
            
            {{-- Status Badge --}}
            @php
                $badgeColors = [
                    'pending' => 'background:#FFF4E5; color:#D48806;',
                    'processing' => 'background:#E6F7FF; color:#1890FF;',
                    'completed' => 'background:#E6FFFB; color:#08979C;',
                    'cancelled' => 'background:#FFF1F0; color:#CF1322;',
                ];
            @endphp
            <span style="{{ $badgeColors[$order->status] ?? '' }} padding:8px 16px; border-radius:20px; font-size:13px; font-weight:600;">
                {{ ucfirst($order->status) }}
            </span>
        </form>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:24px;">
        {{-- Order Info --}}
        <div style="background:white; padding:20px; border-radius:12px; border:1px solid #eee;">
            <h3 style="margin-top:0; color:#4A2C2A;">Order Details</h3>
            @php
                $displayTotal = $order->latestPayment->amount ?? (($order->discount_status ?? '') === 'approved' ? $order->total_after_discount : $order->total);
                $originalTotal = $order->total;
            @endphp
            <p><strong>Total:</strong>
                @if(number_format($displayTotal, 2) !== number_format($originalTotal, 2))
                    <span style="color:#888; text-decoration:line-through; margin-right:8px;">₱{{ number_format($originalTotal, 2) }}</span>
                    <span style="color:#4A2C2A; font-weight:700;">₱{{ number_format($displayTotal, 2) }}</span>
                @else
                    ₱{{ number_format($displayTotal, 2) }}
                @endif
            </p>
            <p><strong>Customer:</strong> {{ $order->user->name ?? 'Guest' }} ({{ $order->user->email ?? '-' }})</p>
            <p><strong>Placed:</strong> {{ $order->placed_at?->format('M d, Y h:i A') }}</p>
            @if($order->discount_proof)
                @php $dstatus = $order->discount_status ?? 'none'; @endphp
                @if($dstatus === 'pending')
                    <p><strong>Discount:</strong> <span style="color:#D48806; font-weight:600;">Pending Approval</span>
                        <a href="{{ asset('storage/' . $order->discount_proof) }}" target="_blank" style="color:#1890FF; text-decoration:underline; margin-left:8px;">View Proof</a>
                    </p>
                    <div style="margin-top:8px; display:flex; gap:8px; align-items:center;">
                        <form action="{{ route('admin.orders.discount.approve', $order) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" style="background:#08979C; color:white; padding:8px 12px; border-radius:6px; border:none; cursor:pointer; font-weight:600;">Approve</button>
                        </form>

                        <form action="{{ route('admin.orders.discount.reject', $order) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" style="background:#CF1322; color:white; padding:8px 12px; border-radius:6px; border:none; cursor:pointer; font-weight:600;">Reject</button>
                        </form>
                    </div>
                @elseif($dstatus === 'approved')
                    <p><strong>Discount:</strong> <span style="color:#4A2C2A; font-weight:600;">Approved</span>
                        <a href="{{ asset('storage/' . $order->discount_proof) }}" target="_blank" style="color:#1890FF; text-decoration:underline; margin-left:8px;">View Proof</a>
                    </p>
                    
                @elseif($dstatus === 'rejected')
                    <p><strong>Discount:</strong> <span style="color:#CF1322; font-weight:600;">Rejected</span>
                        <a href="{{ asset('storage/' . $order->discount_proof) }}" target="_blank" style="color:#1890FF; text-decoration:underline; margin-left:8px;">View Proof</a>
                    </p>
                    
                @else
                    <p><strong>Discount:</strong> <span style="color:#888;">None</span></p>
                @endif
            @else
                <p><strong>Discount:</strong> <span style="color:#888;">None</span></p>
            @endif
        </div>

        {{-- Shipping Address --}}
        <div style="background:white; padding:20px; border-radius:12px; border:1px solid #eee;">
            <h3 style="margin-top:0; color:#4A2C2A;">Shipping Address</h3>
            @if($shipping)
                <div>{{ $shipping->full_name }}</div>
                <div>{{ $shipping->phone }}</div>
                <div>{{ $shipping->line1 }} {{ $shipping->line2 }}</div>
                <div>{{ $shipping->city }}, {{ $shipping->province }} {{ $shipping->postal_code }}</div>
            @else
                <p style="color:#999;">No shipping address.</p>
            @endif
        </div>
    </div>

    {{-- Order Items --}}
    @if($order->instructions)
        <div style="background:white; padding:20px; border-radius:12px; border:1px solid #eee; margin-bottom:24px;">
            <h3 style="margin-top:0; color:#4A2C2A;">Special Instructions</h3>
            <p style="white-space:pre-wrap; color:#333; margin:0;">{{ $order->instructions }}</p>
        </div>
    @endif

    <div style="background:white; padding:20px; border-radius:12px; border:1px solid #eee;">
        <h3 style="margin-top:0; color:#4A2C2A;">Items</h3>
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="text-align:left; border-bottom:2px solid #eee; color:#888; font-size:13px; text-transform:uppercase;">
                    <th style="padding:12px 8px;">Product</th>
                    <th style="padding:12px 8px;">Qty</th>
                    <th style="padding:12px 8px;">Unit Price</th>
                    <th style="padding:12px 8px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr style="border-bottom:1px solid #f3f3f3;">
                        <td style="padding:14px 8px;">{{ $item->product->name ?? ('#'.$item->product_id) }}</td>
                        <td style="padding:14px 8px;">{{ $item->quantity }}</td>
                        <td style="padding:14px 8px;">₱{{ number_format($item->unit_price, 2) }}</td>
                        <td style="padding:14px 8px; font-weight:600;">₱{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="border-top:2px solid #4A2C2A;">
                    <td colspan="3" style="padding:14px 8px; text-align:right; font-weight:700;">Total:</td>
                    <td style="padding:14px 8px; font-weight:700; font-size:18px;">
                        @if(number_format($displayTotal, 2) !== number_format($originalTotal, 2))
                            <span style="color:#888; text-decoration:line-through; margin-right:8px;">₱{{ number_format($originalTotal, 2) }}</span>
                            <span style="color:#4A2C2A; font-weight:700;">₱{{ number_format($displayTotal, 2) }}</span>
                        @else
                            ₱{{ number_format($displayTotal, 2) }}
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
