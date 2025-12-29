@extends('layouts.admin')

@section('admin-content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <h1 style="font-family:'Cooper Black', serif; color:#4A2C2A;">Orders</h1>
</div>

<table style="width:100%; border-collapse:collapse; font-family:'Poppins', sans-serif;">
    <thead>
        <tr style="text-align:left; border-bottom:1px solid #eee;">
            <th>ID</th>
            <th>Status</th>
            <th>Total</th>
            <th>Customer</th>
            <th>Placed</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $o)
        <tr style="border-bottom:1px solid #f3f3f3;">
            <td>{{ $o->id }}</td>
            <td>{{ ucfirst($o->status) }}</td>
            <td>₱{{ number_format($o->total, 2) }}</td>
            <td>{{ $o->user->name ?? 'Guest' }}</td>
            <td>{{ $o->placed_at?->format('Y-m-d H:i') }}</td>
            <td><a href="{{ route('admin.orders.show', $o) }}">View</a></td>
        </tr>
        @empty
        <tr><td colspan="6">No orders yet.</td></tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top:16px;">
    {{ $orders->links() }}
</div>
@endsection
