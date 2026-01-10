<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $payment->id }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .meta { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f7f7f7; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Bean There</h2>
        <p>Receipt for Payment #{{ $payment->id }}</p>
    </div>

    <div class="meta">
        <strong>Transaction ID:</strong> {{ $payment->transaction_id ?? 'TXN-'.str_pad($payment->id,6,'0',STR_PAD_LEFT) }}<br>
        <strong>Order:</strong> #{{ $payment->order->id ?? $payment->order_id }}<br>
        <strong>Paid At:</strong> {{ optional($payment->paid_at)->format('M d, Y h:i A') }}<br>
        <strong>Amount:</strong> ₱{{ number_format($payment->amount, 2) }}
    </div>

    <h4>Order Items</h4>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Unit Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payment->order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Item' }}</td>
                    <td>₱{{ number_format($item->unit_price,2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₱{{ number_format($item->unit_price * $item->quantity,2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @php
        $itemsTotal = $payment->order->items->sum(function($i) { return $i->unit_price * $i->quantity; });
        $discountAmount = $itemsTotal - (float) $payment->amount;
        if ($discountAmount < 0) {
            $discountAmount = 0; // avoid negative discount if payment amount > items total
        }
    @endphp

    <div style="width:40%; margin-left:auto; margin-top:12px;">
        <table style="width:100%; border-collapse:collapse;">
            <tbody>
                <tr>
                    <td style="padding:6px 8px; border:none;">Subtotal</td>
                    <td style="padding:6px 8px; text-align:right; border:none;">₱{{ number_format($itemsTotal,2) }}</td>
                </tr>

                @if($discountAmount > 0)
                    <tr>
                        <td style="padding:6px 8px; border:none;">Discount</td>
                        <td style="padding:6px 8px; text-align:right; border:none;">-₱{{ number_format($discountAmount,2) }}</td>
                    </tr>
                @endif

                <tr>
                    <td style="padding:8px 8px; border-top:1px solid #ddd; font-weight:700;">Total</td>
                    <td style="padding:8px 8px; border-top:1px solid #ddd; text-align:right; font-weight:700;">₱{{ number_format($payment->amount,2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
