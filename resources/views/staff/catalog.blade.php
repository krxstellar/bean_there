@extends('layouts.staff')

@section('staff-content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div>
        <h1 style="font-family:'Cooper Black', serif; color:#4A2C2A; margin:0;">Product Catalog</h1>
        <p style="color:#888; margin:5px 0 0;">View available products</p>
    </div>
</div>

{{-- Quick Stats --}}
<div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-bottom:24px;">
    @php
        $total = $products->total();
        $active = App\Models\Product::where('is_active', true)->count();
        $inactive = $total - $active;
    @endphp
    <div style="background:#E6FFFB; padding:20px; border-radius:12px; text-align:center;">
        <div style="font-size:28px; font-weight:700; color:#08979C;">{{ $total }}</div>
        <div style="color:#08979C; font-size:13px;">Total Products</div>
    </div>
    <div style="background:#E6F7FF; padding:20px; border-radius:12px; text-align:center;">
        <div style="font-size:28px; font-weight:700; color:#1890FF;">{{ $active }}</div>
        <div style="color:#1890FF; font-size:13px;">Active</div>
    </div>
    <div style="background:#FFF1F0; padding:20px; border-radius:12px; text-align:center;">
        <div style="font-size:28px; font-weight:700; color:#CF1322;">{{ $inactive }}</div>
        <div style="color:#CF1322; font-size:13px;">Inactive</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 25px;">
    @forelse($products as $p)
    <div class="product-card" style="background: #FFFFFF; border: 1.5px solid #F0F2F5; border-radius: 20px; overflow: hidden; transition: 0.3s ease; font-family: 'Poppins', sans-serif;">
        <div style="height: 160px; background: #FDF9F0; display: flex; align-items: center; justify-content: center; color: #AEA9A0;">
            @if($p->image_url)
                <img src="{{ asset($p->image_url) }}" alt="{{ $p->name }}" style="width:100%; height:160px; object-fit:cover;">
            @else
                <i class="fa-solid fa-mug-hot fa-3x"></i>
            @endif
        </div>
        <div style="padding: 20px;">
            <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #4A2C2A;">{{ $p->name }}</h3>
            <p style="margin: 5px 0 15px 0; font-size: 12px; color: #AEA9A0; text-transform: uppercase; letter-spacing: 0.5px;">
                {{ $p->category->name ?? 'Uncategorized' }}
            </p>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: 700; font-size: 18px; color: #4A2C2A;">₱{{ number_format($p->price, 2) }}</span>
                <span style="padding:6px 12px; border-radius:20px; font-size:11px; font-weight:600; {{ $p->is_active ? 'background:#E6FFFB; color:#08979C;' : 'background:#FFF1F0; color:#CF1322;' }}">
                    {{ $p->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
    </div>
    @empty
        <p style="grid-column: 1/-1; text-align:center; color:#999; padding:40px;">No products yet.</p>
    @endforelse
</div>

<div style="margin-top:20px;">
    {{ $products->links() }}
</div>

<style>
    .product-card:hover {
        transform: translateY(-5px);
        border-color: #AEA9A0;
        box-shadow: 0 12px 20px rgba(0,0,0,0.05);
    }
</style>
@endsection