@extends('layouts.admin')

@section('admin-content')
    <h1 style="font-family: 'Cooper Black', serif; font-size: 2.5rem; color: #4A2C2A; margin-bottom: 25px;">Payment Transaction</h1>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; font-family: 'Poppins', sans-serif;">
        <div style="background: #FDF9F0; padding: 20px; border-radius: 15px; border: 1px solid #EEE;">
            <p style="margin: 0; font-size: 12px; color: #AEA9A0; text-transform: uppercase;">Total Revenue</p>
            <h3 style="margin: 5px 0 0; color: #4A2C2A;">₱{{ number_format($totalRevenue ?? 0, 2) }}</h3>
        </div>
        <div style="background: #FDF9F0; padding: 20px; border-radius: 15px; border: 1px solid #EEE;">
            <p style="margin: 0; font-size: 12px; color: #AEA9A0; text-transform: uppercase;">Completed</p>
            <h3 style="margin: 5px 0 0; color: #4A2C2A;">{{ $completedCount ?? 0 }} Payments</h3>
        </div>
    </div>

    <div style="background: white; border-radius: 20px; overflow: hidden; font-family: 'Poppins', sans-serif;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #F0F2F5; color: #AEA9A0; font-size: 13px; text-transform: uppercase;">
                    <th style="padding: 20px;">Transaction ID</th>
                    <th style="padding: 20px;">Order No.</th>
                    <th style="padding: 20px;">Amount</th>
                    <th style="padding: 20px;">Status</th>
                    <th style="padding: 20px;">Date</th>
                    <th style="padding: 20px;">Receipts</th>
                </tr>
            </thead>
            <tbody style="font-size: 14px; color: #4A2C2A;">
                @forelse($payments as $payment)
                    <tr class="payment-row" style="border-bottom: 1px solid #F0F2F5;">
                        <td style="padding: 20px; font-weight: 500;">{{ $payment->transaction_id ?? ('TXN-'.str_pad($payment->id, 6, '0', STR_PAD_LEFT)) }}</td>
                        <td style="padding: 20px;">#{{ $payment->order->id ?? $payment->order_id }}</td>
                        <td style="padding: 20px; font-weight: 700;">₱{{ number_format($payment->amount, 2) }}</td>
                        <td style="padding: 20px;">
                            @if(($payment->status ?? '') === 'paid')
                                <span style="background: #E6FFFB; color: #08979C; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Paid</span>
                            @else
                                <span style="background: #FFF4E5; color: #D48806; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ ucfirst($payment->status) }}</span>
                            @endif
                        </td>
                        <td style="padding: 20px; color: #666;">{{ optional($payment->paid_at)->format('M d, Y') }}</td>
                        <td style="padding: 20px;">
                            <a href="{{ url('admin/payments/'.$payment->id.'/receipt') }}" style="background: #4A2C2A; color: #FFF; padding: 8px 12px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 13px;">Generate</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:20px; text-align:center; color:#999;">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <style>
        .payment-row:hover {
            background-color: #FDF9F0 !important;
            transition: 0.2s;
        }
    </style>
@endsection