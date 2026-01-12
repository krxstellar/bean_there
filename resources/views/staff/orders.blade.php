@extends('layouts.staff')

@section('staff-content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div>
        <h1 style="font-family:'Cooper Black', serif; color:#4A2C2A; margin:0;">Orders</h1>
        <p style="color:#888; margin:5px 0 0;">Process and manage customer orders</p>
    </div>
</div>

@if(session('success'))
    <div style="background:#d4edda; border:1px solid #c3e6cb; color:#155724; padding:12px 16px; border-radius:8px; margin-bottom:20px;">
        {{ session('success') }}
    </div>
@endif

{{-- Quick Stats --}}
<div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:24px;">
    @php
        $pending = $orders->where('status', 'pending')->count();
        $processing = $orders->where('status', 'processing')->count();
        $completed = $orders->where('status', 'completed')->count();
        $cancelled = $orders->where('status', 'cancelled')->count();
    @endphp
    <div style="background:#FFF4E5; padding:20px; border-radius:12px; text-align:center;">
        <div style="font-size:28px; font-weight:700; color:#D48806;">{{ $pending }}</div>
        <div style="color:#D48806; font-size:13px;">Pending</div>
    </div>
    <div style="background:#E6F7FF; padding:20px; border-radius:12px; text-align:center;">
        <div style="font-size:28px; font-weight:700; color:#1890FF;">{{ $processing }}</div>
        <div style="color:#1890FF; font-size:13px;">Processing</div>
    </div>
    <div style="background:#E6FFFB; padding:20px; border-radius:12px; text-align:center;">
        <div style="font-size:28px; font-weight:700; color:#08979C;">{{ $completed }}</div>
        <div style="color:#08979C; font-size:13px;">Completed</div>
    </div>
    <div style="background:#FFF1F0; padding:20px; border-radius:12px; text-align:center;">
        <div style="font-size:28px; font-weight:700; color:#CF1322;">{{ $cancelled }}</div>
        <div style="color:#CF1322; font-size:13px;">Cancelled</div>
    </div>
</div>

<div style="background:white; border-radius:12px; overflow:hidden;">
    <table style="width:100%; border-collapse:collapse; font-family:'Poppins', sans-serif;">
        <thead>
            <tr style="text-align:left; border-bottom:2px solid #eee; background:#FAFAFA;">
                <th style="padding:16px 12px; color:#888; font-size:12px; text-transform:uppercase;">Order</th>
                <th style="padding:16px 12px; color:#888; font-size:12px; text-transform:uppercase;">Status</th>
                <th style="padding:16px 12px; color:#888; font-size:12px; text-transform:uppercase;">Customer</th>
                <th style="padding:16px 12px; color:#888; font-size:12px; text-transform:uppercase;">Items</th>
                <th style="padding:16px 12px; color:#888; font-size:12px; text-transform:uppercase;">Total</th>
                <th style="padding:16px 12px; color:#888; font-size:12px; text-transform:uppercase;">Placed</th>
                <th style="padding:16px 12px; color:#888; font-size:12px; text-transform:uppercase;">Discount</th>
                <th style="padding:16px 12px; color:#888; font-size:12px; text-transform:uppercase;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @php
                $badgeColors = [
                    'pending' => 'background:#FFF4E5; color:#D48806;',
                    'processing' => 'background:#E6F7FF; color:#1890FF;',
                    'completed' => 'background:#E6FFFB; color:#08979C;',
                    'cancelled' => 'background:#FFF1F0; color:#CF1322;',
                ];
            @endphp
            @forelse($orders as $order)
            <tr style="border-bottom:1px solid #f3f3f3;" onmouseover="this.style.background='#FDF9F0'" onmouseout="this.style.background='white'">
                <td style="padding:16px 12px; font-weight:700;">#{{ $order->id }}</td>
                <td style="padding:16px 12px;">
                    <span style="{{ $badgeColors[$order->status] ?? '' }} padding:6px 14px; border-radius:20px; font-size:12px; font-weight:600;">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td style="padding:16px 12px;">{{ $order->user->name ?? 'Guest' }}</td>
                <td style="padding:16px 12px;">{{ $order->items->count() }} item(s)</td>
                @php
                    $displayTotal = ($order->discount_status ?? '') === 'approved' ? $order->total_after_discount : $order->total;
                    $originalTotal = $order->total;
                @endphp
                <td style="padding:16px 12px; font-weight:600;">
                    @if(number_format($displayTotal, 2) !== number_format($originalTotal, 2))
                        <span style="color:#888; text-decoration:line-through; margin-right:6px;">₱{{ number_format($originalTotal, 2) }}</span>
                        <span style="color:#4A2C2A; font-weight:700;">₱{{ number_format($displayTotal, 2) }}</span>
                    @else
                        ₱{{ number_format($displayTotal, 2) }}
                    @endif
                </td>
                <td style="padding:16px 12px; color:#666;">{{ $order->placed_at?->format('M d, h:i A') }}</td>
                <td style="padding:16px 12px;">
                    @if($order->discount_proof)
                        <span style="color:#4A2C2A; font-weight:600;">Applied</span>
                        <a href="{{ asset('storage/' . $order->discount_proof) }}" target="_blank" style="color:#1890FF; text-decoration:underline;">View Proof</a>
                    @else
                        <span style="color:#888;">None</span>
                    @endif
                </td>
                <td style="padding:16px 12px;">
                    <a href="{{ route('staff.orders.show', $order) }}" 
                       style="background:#B07051; color:white; padding:8px 16px; border-radius:6px; text-decoration:none; font-size:13px; font-weight:500;">
                        Process
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="padding:40px; text-align:center; color:#999;">No orders to process.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:20px;">
    {{ $orders->links() }}
</div>
@endsection