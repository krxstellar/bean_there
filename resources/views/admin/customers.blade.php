@extends('layouts.admin')

@section('admin-content')
    <h1 style="font-family: 'Cooper Black', serif; font-size: 2.5rem; color: #4A2C2A; margin-bottom: 25px;">Customers</h1>

    <div style="background: white; border-radius: 20px; overflow: hidden; font-family: 'Poppins', sans-serif;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #F0F2F5; color: #AEA9A0; font-size: 13px; text-transform: uppercase;">
                    <th style="padding: 20px;">Customer Name</th>
                    <th style="padding: 20px;">Email</th>
                    <th style="padding: 20px;">Total Orders</th>
                    <th style="padding: 20px;">Total Spent</th>
                    <th style="padding: 20px;">Status</th>
                </tr>
            </thead>
            <tbody style="font-size: 14px; color: #4A2C2A;">
                @forelse($customers as $customer)
                    @php
                        $parts = preg_split('/\s+/', trim($customer->name));
                        $initials = strtoupper(substr($parts[0] ?? '', 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                        $completedOrders = $customer->orders()->where('status', 'completed')->get();
                        $ordersCount = $completedOrders->count();
                        $totalSpent = $completedOrders->sum(function($o) {
                            return (($o->discount_status ?? '') === 'approved') ? (float) $o->total_after_discount : (float) $o->total;
                        });
                        $statusLabel = $ordersCount > 0 ? 'Active' : 'New';
                        $statusStyle = $ordersCount > 0 ? 'background: #E6FFFB; color: #08979C;' : 'background: #F0F2F5; color: #666;';
                    @endphp
                    <tr class="customer-row" style="border-bottom: 1px solid #F0F2F5;">
                        <td style="padding: 20px; display: flex; align-items: center; gap: 12px;">
                            <div style="width: 35px; height: 35px; background: #FDF9F0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #AEA9A0; font-weight: bold;">{{ $initials }}</div>
                            {{ $customer->name }}
                        </td>
                        <td style="padding: 20px;">{{ $customer->email }}</td>
                        <td style="padding: 20px;">{{ $ordersCount }} {{ \Illuminate\Support\Str::plural('Order', $ordersCount) }}</td>
                        <td style="padding: 20px; font-weight: 600;">₱{{ number_format($totalSpent, 2) }}</td>
                        <td style="padding: 20px;">
                            <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; {{ $statusStyle }}">{{ $statusLabel }}</span>
                        </td>
                        
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 20px; text-align: center; color: #666;">No customers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="padding: 16px; display: flex; justify-content: center;">
            {{ $customers->links() }}
        </div>
    </div>

    <style>
        .customer-row:hover {
            background-color: #FDF9F0 !important;
            transition: 0.2s;
        }
    </style>
@endsection