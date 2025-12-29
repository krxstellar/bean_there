s@extends('layouts.staff')

@section('staff-content')
<div style="margin-bottom:24px;">
    <a href="{{ route('staff.orders') }}" style="color:#B07051; text-decoration:none; font-size:14px;">
        ← Back to Orders
    </a>
</div>


<div style="display:flex; gap:24px; flex-wrap:wrap;">
    {{-- Order Details --}}
    <div style="flex:2; min-width:400px;">
        <div style="background:white; border-radius:12px; padding:24px; margin-bottom:20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h1 style="font-family:'Cooper Black', serif; color:#4A2C2A; margin:0;">Order #{{ $order->id }}</h1>
                @php
                    $badgeColors = [
                        'pending' => 'background:#FFF4E5; color:#D48806;',
                        'processing' => 'background:#E6F7FF; color:#1890FF;',
                        'completed' => 'background:#E6FFFB; color:#08979C;',
                        'cancelled' => 'background:#FFF1F0; color:#CF1322;',
                    ];
                @endphp
                <span style="{{ $badgeColors[$order->status] ?? '' }} padding:8px 18px; border-radius:20px; font-size:13px; font-weight:600;">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:24px;">
                <div>
                    <div style="color:#888; font-size:12px; text-transform:uppercase; margin-bottom:4px;">Customer</div>
                    <div style="font-weight:600;">{{ $order->user->name ?? 'Guest' }}</div>
                    <div style="color:#666; font-size:13px;">{{ $order->user->email ?? '-' }}</div>
                </div>
                <div>
                    <div style="color:#888; font-size:12px; text-transform:uppercase; margin-bottom:4px;">Placed At</div>
                    <div style="font-weight:600;">{{ $order->placed_at?->format('F d, Y') }}</div>
                    <div style="color:#666; font-size:13px;">{{ $order->placed_at?->format('h:i A') }}</div>
                </div>
            </div>

            <h3 style="font-family:'Cooper Black', serif; color:#4A2C2A; margin:0 0 16px;">Order Items</h3>
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:2px solid #eee; text-align:left;">
                        <th style="padding:12px 8px; color:#888; font-size:12px; text-transform:uppercase;">Item</th>
                        <th style="padding:12px 8px; color:#888; font-size:12px; text-transform:uppercase;">Qty</th>
                        <th style="padding:12px 8px; color:#888; font-size:12px; text-transform:uppercase; text-align:right;">Price</th>
                        <th style="padding:12px 8px; color:#888; font-size:12px; text-transform:uppercase; text-align:right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->items as $item)
                    <tr style="border-bottom:1px solid #f3f3f3;">
                        <td style="padding:14px 8px;">
                            <div style="font-weight:600;">{{ $item->product->name ?? 'Product Unavailable' }}</div>
                            @if($item->notes)
                                <div style="color:#888; font-size:12px;">{{ $item->notes }}</div>
                            @endif
                        </td>
                        <td style="padding:14px 8px;">{{ $item->quantity }}</td>
                        <td style="padding:14px 8px; text-align:right;">₱{{ number_format($item->unit_price, 2) }}</td>
                        <td style="padding:14px 8px; text-align:right; font-weight:600;">₱{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding:20px; text-align:center; color:#999;">No items in this order.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr style="border-top:2px solid #eee;">
                        <td colspan="3" style="padding:14px 8px; text-align:right; font-weight:700; color:#4A2C2A;">Total</td>
                        <td style="padding:14px 8px; text-align:right; font-weight:700; font-size:18px; color:#B07051;">₱{{ number_format($order->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Status Update Panel --}}
    <div style="flex:1; min-width:280px;">
        <div style="background:white; border-radius:12px; padding:24px; position:sticky; top:20px;">
            <h3 style="font-family:'Cooper Black', serif; color:#4A2C2A; margin:0 0 16px;">Update Status</h3>
            
            @if(session('success'))
                <div style="background:#d4edda; border:1px solid #c3e6cb; color:#155724; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:13px;">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('staff.orders.updateStatus', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div style="margin-bottom:16px;">
                    @php
                        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
                    @endphp
                    @foreach($statuses as $status)
                    <label style="display:flex; align-items:center; padding:12px; margin-bottom:8px; border:2px solid {{ $order->status === $status ? '#B07051' : '#eee' }}; border-radius:8px; cursor:pointer; transition:all 0.2s;"
                           onmouseover="if(!this.querySelector('input').checked) this.style.borderColor='#D4A574'" 
                           onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='#eee'">
                        <input type="radio" name="status" value="{{ $status }}" {{ $order->status === $status ? 'checked' : '' }}
                               style="width:18px; height:18px; accent-color:#B07051; margin-right:12px;">
                        <div>
                            <div style="font-weight:600; color:#4A2C2A;">{{ ucfirst($status) }}</div>
                            <div style="font-size:11px; color:#888;">
                                @switch($status)
                                    @case('pending') Order received, awaiting processing @break
                                    @case('processing') Order is being prepared @break
                                    @case('completed') Order ready for pickup/delivered @break
                                    @case('cancelled') Order has been cancelled @break
                                @endswitch
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>

                <button type="submit" style="width:100%; background:#B07051; color:white; border:none; padding:14px; border-radius:8px; font-size:15px; font-weight:600; cursor:pointer; font-family:'Poppins', sans-serif;"
                        onmouseover="this.style.background='#8B5A3C'" onmouseout="this.style.background='#B07051'">
                    Update Order Status
                </button>
            </form>

            <div style="margin-top:20px; padding-top:20px; border-top:1px solid #eee;">
                <h4 style="color:#4A2C2A; margin:0 0 12px; font-size:14px;">Quick Actions</h4>
                <div style="display:flex; flex-direction:column; gap:8px;">
                    @if($order->status === 'pending')
                    <form action="{{ route('staff.orders.updateStatus', $order) }}" method="POST" style="margin:0;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="processing">
                        <button type="submit" style="width:100%; background:#E6F7FF; color:#1890FF; border:1px solid #1890FF; padding:10px; border-radius:6px; font-size:13px; font-weight:600; cursor:pointer;">
                            Start Processing
                        </button>
                    </form>
                    @endif
                    @if($order->status === 'processing')
                    <form action="{{ route('staff.orders.updateStatus', $order) }}" method="POST" style="margin:0;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" style="width:100%; background:#E6FFFB; color:#08979C; border:1px solid #08979C; padding:10px; border-radius:6px; font-size:13px; font-weight:600; cursor:pointer;">
                            Mark as Completed
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
