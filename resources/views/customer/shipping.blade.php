@extends('layouts.customer')

@section('content')
<style>
    .checkout-section { padding: 40px 5%; max-width: 900px; margin: 0 auto; }
    .page-title { font-family: "Cooper Black", serif; font-size: 2.2rem; color: #4A2C2A; margin-bottom: 30px; text-align: center; }
    .checkout-container { background-color: white; border: 2px solid #4A2C2A; border-radius: 20px; padding: 40px; box-shadow: 0px 8px 20px rgba(74, 44, 42, 0.05); }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-family: "Poppins", sans-serif; font-weight: 600; color: #4A2C2A; margin-bottom: 8px; }
    .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: "Poppins", sans-serif; box-sizing: border-box; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .checkout-btn { background-color: #4A2C2A; color: #FDF9F0; border: none; padding: 14px 50px; border-radius: 12px; font-family: "Poppins", sans-serif; font-weight: 600; cursor: pointer; width: 100%; margin-top: 20px; transition: opacity 0.3s; }
    .checkout-btn:hover { opacity: 0.9; }
    .error { color: #c0392b; font-size: 12px; }
    @media (max-width: 850px) { .form-row { grid-template-columns: 1fr; } }
</style>

<div class="checkout-section">
    <h1 class="page-title">Checkout</h1>
    <div class="checkout-container">
        <h2 style="color:#4A2C2A; margin-top:0;">Shipping Address</h2>
        @if($errors->any())
            <div style="background:#ffe5e5;color:#c0392b;padding:12px;border-radius:8px;margin-bottom:16px;">
                @foreach($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif
        <form method="POST" action="{{ route("checkout.store") }}">
            @csrf
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="shipping[full_name]" value="{{ old("shipping.full_name", auth()->user()->name ?? "") }}" required>
            </div>
            <div class="form-group">
                <label>Phone Number *</label>
                <input type="text" name="shipping[phone]" placeholder="09xx-xxx-xxxx" value="{{ old("shipping.phone") }}" required>
            </div>
            <div class="form-group">
                <label>Address Line 1 *</label>
                <input type="text" name="shipping[line1]" placeholder="Street address" value="{{ old("shipping.line1") }}" required>
            </div>
            <div class="form-group">
                <label>Address Line 2 (optional)</label>
                <input type="text" name="shipping[line2]" placeholder="Apt, suite, etc" value="{{ old("shipping.line2") }}">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>City *</label>
                    <input type="text" name="shipping[city]" placeholder="Quezon City" value="{{ old("shipping.city") }}" required>
                </div>
                <div class="form-group">
                    <label>Province *</label>
                    <input type="text" name="shipping[province]" placeholder="NCR" value="{{ old("shipping.province") }}" required>
                </div>
            </div>
            <div class="form-group">
                <label>Postal Code *</label>
                <input type="text" name="shipping[postal_code]" placeholder="1100" value="{{ old("shipping.postal_code") }}" required>
            </div>
            <button type="submit" class="checkout-btn">Place Order</button>
        </form>
    </div>
</div>
@endsection
