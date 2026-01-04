@extends('layouts.admin')

@section('admin-content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 style="font-family: 'Cooper Black', serif; font-size: 2.5rem; color: #4A2C2A; margin: 0;">Product Catalog</h1>
            <p style="color:#888; margin:5px 0 0;">Manage your products</p>
        </div>
        
        <a href="{{ route('admin.products.create') }}" style="background-color: #B07051; color: white; border: none; padding: 12px 24px; border-radius: 12px; font-family: 'Poppins', sans-serif; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; text-decoration: none;">
            <i class="fa-solid fa-plus"></i> Add New Product
        </a>
    </div>

    @if(session('success'))
        <div style="background:#d4edda; border:1px solid #c3e6cb; color:#155724; padding:12px 16px; border-radius:8px; margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

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
        <div class="product-card">
            <div class="product-image">
                @if($p->image_url)
                    <img src="{{ asset($p->image_url) }}" alt="{{ $p->name }}" style="width:100%; height:160px; object-fit:cover;">
                @else
                    <i class="fa-solid fa-mug-hot fa-3x"></i>
                @endif
            </div>
            <div class="product-info">
                <h3>{{ $p->name }}</h3>
                <p class="category-tag">{{ $p->category->name ?? 'Uncategorized' }}</p>
                <div class="price-row">
                    <span>₱{{ number_format($p->price, 2) }}</span>
                    <div class="action-btns">
                        <a class="edit" href="{{ route('admin.products.edit', $p) }}" style="display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-pen"></i></a>
                        <form method="POST" action="{{ route('admin.products.destroy', $p) }}" onsubmit="return confirm('Delete this product?')">
                            @csrf
                            @method('DELETE')
                            <button class="delete" type="submit"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
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
        /* CARD STYLES */
        .product-card {
            background: #FFFFFF;
            border: 1.5px solid #F0F2F5;
            border-radius: 20px;
            overflow: hidden;
            transition: 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .product-card:hover {
            transform: translateY(-5px);
            border-color: #AEA9A0;
            box-shadow: 0 12px 20px rgba(0,0,0,0.05);
        }

        .product-image {
            height: 160px;
            background: #FDF9F0; 
            display: flex;
            align-items: center;
            justify-content: center;
            color: #AEA9A0;
        }

        .product-image.drink { background: #F0F4F8; color: #9AB6D6; }

        .product-info { padding: 20px; }

        .product-info h3 { margin: 0; font-size: 16px; font-weight: 600; color: #4A2C2A; }

        .category-tag { 
            margin: 5px 0 15px 0; 
            font-size: 12px; 
            color: #AEA9A0; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .price-row { display: flex; justify-content: space-between; align-items: center; }

        .price-row span { font-weight: 700; font-size: 18px; color: #4A2C2A; }

        .action-btns { display: flex; gap: 8px; }

        .action-btns button {
            background: #F8F9FA;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 10px;
            cursor: pointer;
            color: #4A2C2A;
            transition: 0.2s;
        }

        .action-btns button.delete:hover { background: #FFE5E5; color: #E74C3C; }
        .action-btns button.edit:hover, .action-btns a.edit:hover { background: #E5F0FF; color: #3498DB; }

        .action-btns a.edit {
            background: #F8F9FA;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 10px;
            cursor: pointer;
            color: #4A2C2A;
            transition: 0.2s;
            text-decoration: none;
        }
    </style>
@endsection