@extends('layouts.admin')

@section('admin-content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.catalog') }}" style="color: #B07051; text-decoration: none;">
            ← Back to Catalog
        </a>
    </div>

    <div style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0;">
            {{-- Product Image --}}
            <div style="background: #FDF9F0; display: flex; align-items: center; justify-content: center; min-height: 300px;">
                @if($product->image_url)
                    <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i class="fa-solid fa-mug-hot" style="font-size: 80px; color: #AEA9A0;"></i>
                @endif
            </div>

            {{-- Product Details --}}
            <div style="padding: 40px; font-family: 'Poppins', sans-serif;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                    <span style="background: {{ $product->is_active ? '#E6FFFB' : '#FFF1F0' }}; color: {{ $product->is_active ? '#08979C' : '#CF1322' }}; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <span style="color: #AEA9A0; font-size: 12px; text-transform: uppercase;">
                        {{ $product->category->name ?? 'Uncategorized' }}
                    </span>
                </div>

                <h1 style="font-family: 'Cooper Black', serif; color: #4A2C2A; margin: 0 0 8px 0; font-size: 2rem;">
                    {{ $product->name }}
                </h1>

                <p style="color: #B07051; font-size: 1.8rem; font-weight: 700; margin: 0 0 20px 0;">
                    ₱{{ number_format($product->price, 2) }}
                </p>

                @if($product->description)
                    <div style="margin-bottom: 24px;">
                        <h4 style="color: #4A2C2A; margin: 0 0 8px 0; font-size: 14px;">Description</h4>
                        <p style="color: #666; margin: 0; line-height: 1.6;">{{ $product->description }}</p>
                    </div>
                @endif

                <div style="border-top: 1px solid #F0F2F5; padding-top: 20px; margin-top: 20px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 13px;">
                        <div>
                            <span style="color: #AEA9A0;">Product ID</span>
                            <p style="margin: 4px 0 0; color: #4A2C2A; font-weight: 500;">#{{ $product->id }}</p>
                        </div>
                        <div>
                            <span style="color: #AEA9A0;">Created</span>
                            <p style="margin: 4px 0 0; color: #4A2C2A; font-weight: 500;">{{ $product->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <span style="color: #AEA9A0;">Updated</span>
                            <p style="margin: 4px 0 0; color: #4A2C2A; font-weight: 500;">{{ $product->updated_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 30px;">
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       style="flex: 1; background: #B07051; color: white; padding: 14px; border-radius: 12px; text-decoration: none; text-align: center; font-weight: 600;">
                        <i class="fa-solid fa-pen"></i> Edit Product
                    </a>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="flex: 1;" onsubmit="return confirm('Delete this product?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="width: 100%; background: #FFF1F0; color: #CF1322; border: 1px solid #CF1322; padding: 14px; border-radius: 12px; font-weight: 600; cursor: pointer; font-family: 'Poppins', sans-serif;">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
