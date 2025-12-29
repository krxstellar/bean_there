@extends('layouts.admin')

@section('admin-content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="font-family: 'Cooper Black', serif; font-size: 2.5rem; color: #4A2C2A; margin: 0;">Product Catalog</h1>
        
        <button onclick="openModal()" style="background-color: #AEA9A0; color: white; border: none; padding: 12px 24px; border-radius: 12px; font-family: 'Poppins', sans-serif; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: 0.3s;">
            <i class="fa-solid fa-plus"></i> Add New Product
        </button>
    </div>

    @if(isset($products))
    <div style="margin-top: 40px;">
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px;">
            <h2 style="font-family: 'Cooper Black', serif; color: #4A2C2A; margin: 0; font-size: 1.8rem;">Products</h2>
            <div style="flex-grow: 1; height: 2px; background: #F0F2F5;"></div>
            <a href="{{ route('admin.products.create') }}" style="background-color: #AEA9A0; color: white; padding: 10px 16px; border-radius: 10px; text-decoration: none; font-family: 'Poppins', sans-serif; font-weight: 600;">Add Product</a>
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
                            <form method="POST" action="{{ route('admin.products.destroy', $p) }}">
                                @csrf
                                @method('DELETE')
                                <button class="delete" type="submit"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
                <p>No products yet.</p>
            @endforelse
        </div>
        <div style="margin-top:20px;">
            {{ $products->links() }}
        </div>
    </div>
    @else
    <!-- Fallback to static content when no $products provided -->
    <div style="margin-top: 40px;">
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px;">
            <h2 style="font-family: 'Cooper Black', serif; color: #4A2C2A; margin: 0; font-size: 1.8rem;">Pastries</h2>
            <div style="flex-grow: 1; height: 2px; background: #F0F2F5;"></div>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 25px;">
            <div class="product-card">
                <div class="product-image"><i class="fa-solid fa-cookie-bite fa-3x"></i></div>
                <div class="product-info">
                    <h3>Ube Cheese Pandesal</h3>
                    <p class="category-tag">Pastry • 12pcs/box</p>
                    <div class="price-row">
                        <span>₱450.00</span>
                        <div class="action-btns">
                            <button class="edit"><i class="fa-solid fa-pen"></i></button>
                            <button class="delete"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div style="margin-top: 60px; margin-bottom: 40px;">
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px;">
            <h2 style="font-family: 'Cooper Black', serif; color: #4A2C2A; margin: 0; font-size: 1.8rem;">Drinks</h2>
            <div style="flex-grow: 1; height: 2px; background: #F0F2F5;"></div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 25px;">
            <div class="product-card">
                <div class="product-image drink"><i class="fa-solid fa-mug-hot fa-3x"></i></div>
                <div class="product-info">
                    <h3>Iced Spanish Latte</h3>
                    <p class="category-tag">Coffee • 16oz</p>
                    <div class="price-row">
                        <span>₱160.00</span>
                        <div class="action-btns">
                            <button class="edit"><i class="fa-solid fa-pen"></i></button>
                            <button class="delete"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="addProductModal" style="display: none; position: fixed; inset: 0; background: rgba(74, 44, 42, 0.5); z-index: 9999; backdrop-filter: blur(4px); align-items: center; justify-content: center;">
        
        <div style="background: white; width: 500px; border-radius: 25px; padding: 40px; position: relative; box-shadow: 0 20px 40px rgba(0,0,0,0.2); font-family: 'Poppins', sans-serif;">
            
            <button onclick="closeModal()" style="position: absolute; top: 20px; right: 20px; background: none; border: none; font-size: 20px; cursor: pointer; color: #AEA9A0;">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <h2 style="font-family: 'Cooper Black', serif; color: #4A2C2A; margin-top: 0;">Add New Product</h2>
            <p style="color: #666; font-size: 14px; margin-bottom: 25px;">Enter the details of the new pastry or drink.</p>

            <form id="productForm" onsubmit="event.preventDefault();">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Product Name</label>
                    <input type="text" placeholder="e.g. Chocolate Croissant" style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #F0F2F5; outline: none; box-sizing: border-box;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Price (₱)</label>
                        <input type="number" placeholder="0.00" style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #F0F2F5; outline: none; box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Category</label>
                        <select style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #F0F2F5; outline: none; background: white; cursor: pointer;">
                            <option value="pastry">Pastries</option>
                            <option value="drink">Drinks</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 30px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Product Image</label>
                    <div class="upload-area">
                        <i class="fa-solid fa-cloud-arrow-up fa-2x"></i>
                        <p style="margin: 10px 0 0; font-size: 12px;">Click to upload or drag image</p>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Save Product</button>
            </form>
        </div>
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
        .action-btns button.edit:hover { background: #E5F0FF; color: #3498DB; }

        /* Modal Styles */
        .upload-area {
            border: 2px dashed #AEA9A0;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            color: #AEA9A0;
            cursor: pointer;
            transition: 0.2s;
        }
        .upload-area:hover { background: #FDF9F0; }

        .submit-btn {
            width: 100%;
            background: #4A2C2A;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
        }
        .submit-btn:hover { background: #633d3a; }
    </style>

    <script>
        function openModal() {
            document.getElementById('addProductModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('addProductModal').style.display = 'none';
        }

        window.onclick = function(event) {
            let modal = document.getElementById('addProductModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection