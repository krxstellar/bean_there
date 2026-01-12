@extends('layouts.staff')

@section('staff-content')
    <h1 style="font-family: 'Cooper Black', serif; font-size: 2.5rem; color: #4A2C2A; margin-bottom: 10px;">Dashboard</h1>
    <p style="font-family: 'Poppins'; color: #666; margin-bottom: 30px;">Welcome back! Here is what's happening in the kitchen today.</p>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; font-family: 'Poppins', sans-serif;">
        <div style="background: #FDF9F0; padding: 25px; border-radius: 20px; border: 1.5px solid #EEE;">
            <span style="color: #AEA9A0; font-size: 12px; font-weight: 600; text-transform: uppercase;">To Process</span>
            <h2 style="margin: 10px 0 0; color: #4A2C2A; font-size: 2rem;">{{ $toProcessCount ?? 0 }} Orders</h2>
        </div>
        <div style="background: #FFFFFF; padding: 25px; border-radius: 20px; border: 1.5px solid #F0F2F5;">
            <span style="color: #AEA9A0; font-size: 12px; font-weight: 600; text-transform: uppercase;">In Preparation</span>
            <h2 style="margin: 10px 0 0; color: #4A2C2A; font-size: 2rem;">{{ $inPreparationCount ?? 0 }} Items</h2>
        </div>
    </div>
@endsection