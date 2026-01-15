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
                       style="flex: 1; background: #B07051; color: white; padding: 15px 12px 12px 12px; border-radius: 12px; text-decoration: none; text-align: center; font-weight: 600; font-size: 14px;">
                        <i class="fa-solid fa-pen"></i> Edit Product
                    </a>

                    <button id="open-delete-modal" type="button" style="flex: 1; width: 100%; background: #FFF1F0; color: #CF1322; border: 1px solid #CF1322; padding: 14px; border-radius: 12px; font-weight: 600; cursor: pointer; font-family: 'Poppins', sans-serif; font-size: 14px;">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>

                    <form id="delete-product-form" method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-overlay" id="delete-product-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <div>
                <h1>Delete Product</h1>
                <p>Are you sure you want to delete "{{ $product->name }}"? This action cannot be undone.</p>
            </div>
            <button type="button" class="modal-close" id="close-delete-modal">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div style="display:flex; gap:12px; margin-top: 10px;">
            <button type="button" id="cancel-delete" style="flex:1; background:#F0F2F5; color:#4A2C2A; padding:12px; border-radius:12px; border:none; font-weight:600;">Cancel</button>
            <button type="button" id="confirm-delete" style="flex:1; background:#CF1322; color:white; padding:12px; border-radius:12px; border:none; font-weight:700;">Delete Product</button>
        </div>
    </div>
</div>

<style>
    .modal-overlay { position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px); display:flex; justify-content:center; align-items:center; z-index:9999; }
    .modal-container { background:#FFFFFF; border-radius:25px; padding:30px 34px; max-width:480px; width:90%; max-height:90vh; overflow-y:auto; font-family:'Poppins',sans-serif; color:#4A2C2A; box-shadow:0 25px 80px rgba(0,0,0,0.25); }
    .modal-container button { cursor: pointer; }
    .modal-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:18px; }
    .modal-header h1 { font-family:'Cooper Black', serif; color:#4A2C2A; margin:0 0 6px 0; font-size:1.3rem; }
    .modal-header p { color:#666; font-size:13px; margin:0; }
    .modal-close { background:#F0F2F5; border:none; width:38px; height:38px; border-radius:50%; cursor:pointer; font-size:18px; color:#666; display:flex; align-items:center; justify-content:center; }
    .modal-close:hover { background:#FFE5E5; color:#E74C3C; }
</style>

<script>
    (function(){
        var openBtn = document.getElementById('open-delete-modal');
        var overlay = document.getElementById('delete-product-overlay');
        var closeBtn = document.getElementById('close-delete-modal');
        var cancelBtn = document.getElementById('cancel-delete');
        var confirmBtn = document.getElementById('confirm-delete');
        var form = document.getElementById('delete-product-form');

        if(!openBtn) return;

        openBtn.addEventListener('click', function(){ overlay.style.display = 'flex'; document.body.style.overflow = 'hidden'; });
        closeBtn && closeBtn.addEventListener('click', function(){ overlay.style.display = 'none'; document.body.style.overflow = ''; });
        cancelBtn && cancelBtn.addEventListener('click', function(){ overlay.style.display = 'none'; document.body.style.overflow = ''; });
        confirmBtn && confirmBtn.addEventListener('click', function(){ form.submit(); });
    })();
</script>
@endsection
