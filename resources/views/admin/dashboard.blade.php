@extends('layouts.admin')

@section('admin-content')
    <h1 style="font-size: 2.5rem; margin-bottom: 10px;">Dashboard</h1>
    <p style="color: #666; margin-bottom: 40px;">Ready to start the day? Here is your dashboard view.</p>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 50px;">
        <div style="background: #F8F9FA; padding: 30px; border-radius: 15px; border: 1px solid #EEE;">
            <small>TODAY'S SALES</small>
            <h2 style="margin: 10px 0;">₱ {{ number_format($todayRevenue ?? 0, 2) }}</h2>
        </div>
        <div style="background: #F8F9FA; padding: 30px; border-radius: 15px; border: 1px solid #EEE;">
            <small>PENDING ORDERS</small>
            <h2 style="margin: 10px 0;">{{ $pendingOrders ?? 0 }} Orders</h2>
        </div>
        <div style="background: #F8F9FA; padding: 30px; border-radius: 15px; border: 1px solid #EEE;">
            <small>OUT OF STOCK</small>
            <h2 style="margin: 10px 0; color: {{ ($outOfStock ?? 0) > 0 ? '#E74C3C' : '#333' }};">{{ $outOfStock ?? 0 }} Items</h2>
        </div>
    </div>

    <div style="height: 1200px; background: repeating-linear-gradient(45deg, #fff, #fff 10px, #fafafa 10px, #fafafa 20px); border-radius: 15px; display: flex; align-items: center; justify-content: center; color: #DDD; font-size: 2rem; font-weight: bold;">
        REPORTS AND ANALYTICS SCROLL AREA
    </div>
@endsection