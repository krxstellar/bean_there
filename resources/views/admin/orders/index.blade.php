@extends('layouts.admin')

@section('admin-content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <h1 style="font-family:'Cooper Black', serif; color:#4A2C2A;">Orders</h1>
</div>

@if(session('success'))
    <div style="background:#d4edda; border:1px solid #c3e6cb; color:#155724; padding:12px 16px; border-radius:8px; margin-bottom:20px;">
        {{ session('success') }}
    </div>
@endif

<div style="background:white; border-radius:12px; overflow:hidden;">
    <table style="width:100%; border-collapse:collapse; font-family:'Poppins', sans-serif;">
        <thead>
            <tr style="text-align:left; border-bottom:2px solid #eee; background:#FAFAFA;">
                <th style="padding:16px 12px; color:#888; font-size:12px; text-transform:uppercase;">ID</th>
                <th style="padding:16px 12px; color:#888; font-size:12px; text-transform:uppercase;">Status</th>
                <th style="padding:16px 12px; color:#888; font-size:12px; text-transform:uppercase;">Total</th>
                <th style="padding:16px 12px; color:#888; font-size:12px; text-transform:uppercase;">Customer</th>
                <th style="padding:16px 12px; color:#888; font-size:12px; text-transform:uppercase;">Items</th>
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
            @forelse($orders as $o)
            <tr style="border-bottom:1px solid #f3f3f3;" onmouseover="this.style.background='#FDF9F0'" onmouseout="this.style.background='white'">
                <td style="padding:16px 12px; font-weight:700;">#{{ $o->id }}</td>
                <td style="padding:16px 12px;">
                    <span style="{{ $badgeColors[$o->status] ?? '' }} padding:6px 14px; border-radius:20px; font-size:12px; font-weight:600;">
                        {{ ucfirst($o->status) }}
                    </span>
                </td>
                @php
                    $rowTotal = $o->paid_total ?? (($o->discount_status ?? '') === 'approved' ? $o->total_after_discount : $o->total);
                    $original = $o->total;
                @endphp
                <td style="padding:16px 12px; font-weight:600;">
                    @if(number_format($rowTotal, 2) !== number_format($original, 2))
                        <span style="color:#888; text-decoration:line-through; margin-right:6px;">₱{{ number_format($original, 2) }}</span>
                        <span style="color:#4A2C2A; font-weight:700;">₱{{ number_format($rowTotal, 2) }}</span>
                    @else
                        ₱{{ number_format($rowTotal, 2) }}
                    @endif
                </td>
                <td style="padding:16px 12px;">{{ $o->user->name ?? 'Guest' }}</td>
                <td style="padding:16px 12px;">{{ $o->items->count() }} item(s)</td>
                <td style="padding:16px 12px; color:#666;">{{ $o->placed_at?->format('M d, Y') }}</td>
                <td style="padding:16px 12px;">
                    @if($o->discount_proof)
                        @if(($o->discount_status ?? 'none') === 'pending')
                            <span style="color:#D48806; font-weight:600;">Pending</span>
                        @else
                            <span style="color:#4A2C2A; font-weight:600;">Applied</span>
                        @endif
                    @else
                        <span style="color:#888;">None</span>
                    @endif
                </td>
                <td style="padding:16px 12px;">
                    <a href="{{ route('admin.orders.show', $o) }}" 
                       style="background:#4A2C2A; color:white; padding:8px 16px; border-radius:6px; text-decoration:none; font-size:13px; font-weight:500;">
                        View
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="padding:40px; text-align:center; color:#999;">No orders yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:20px;">
    {{ $orders->links() }}
</div>
@endsection
