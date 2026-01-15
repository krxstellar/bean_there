@extends('layouts.admin')

@section('admin-content')
<div class="modal-overlay" id="edit-product-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div>
                <h1>Edit Product</h1>
                <p>Update the details for this pastry or drink.</p>
            </div>
            <a href="{{ route('admin.products.show', $product) }}" class="modal-close">
                <i class="fa-solid fa-xmark"></i>
            </a>
        </div>

        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" placeholder="e.g. Chocolate Croissant" required>
                @error('name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Price (₱)</label>
                    <input type="number" step="0.01" min="0" name="price" id="price" inputmode="decimal" value="{{ old('price', $product->price) }}" placeholder="0.00" required>
                    @error('price')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" id="category_id">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label>Subcategory</label>
                <div class="subcategory-wrapper">
                    <select name="subcategory_id" id="subcategory_id" required>
                        <option value="">-- Select Subcategory --</option>
                        @foreach($subcategories ?? [] as $sub)
                            <option value="{{ $sub->id }}" data-category="{{ $sub->category_id }}" {{ old('subcategory_id', $product->subcategory_id) == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="add-subcategory-btn" onclick="showNewSubcategoryForm()" title="Create New Subcategory">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
                @error('subcategory_id')<div class="error-msg">{{ $message }}</div>@enderror

                <div id="new-subcategory-form" class="new-subcategory-form" style="display: none;">
                    <div class="new-subcat-header">
                        <span><i class="fa-solid fa-folder-plus"></i> New Subcategory</span>
                        <button type="button" onclick="hideNewSubcategoryForm()" class="close-subcat-form">&times;</button>
                    </div>
                    <input type="text" id="new_subcategory_name" placeholder="Subcategory name (e.g. Cakes & Slices)">
                    <button type="button" onclick="createSubcategory()" class="create-subcat-btn">
                        <i class="fa-solid fa-check"></i> Create
                    </button>
                    <div id="subcat-error" class="error-msg" style="display: none;"></div>
                    <div id="subcat-success" class="success-msg" style="display: none;"></div>
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Product description (optional)">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="form-group">
                <label>Product Image</label>
                @if($product->image_url)
                    <div style="margin-bottom:8px; text-align:center;">
                        <img src="{{ asset($product->image_url) }}" alt="Current image" style="max-width:150px; border-radius:12px; border:1px solid #F0F2F5; display:block; margin:0 auto;">
                        <p style="color:#888; font-size:12px; margin:6px 0 0;">Current image</p>
                    </div>
                @endif
                <label for="image-upload" class="upload-area" id="upload-area">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <p id="upload-text">Click to upload or drag image</p>
                    <input type="file" name="image" id="image-upload" accept="image/*" style="display: none;">
                </label>
                <div id="image-preview" style="display: none; margin-top: 10px; text-align: center;">
                    <img id="preview-img" src="" alt="Preview">
                </div>
                <div id="image-error" class="error-msg" style="display: none; margin-top: 8px;"></div>
                @error('image')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                    <span>Active (visible to customers)</span>
                </label>
            </div>

            <button type="submit" class="btn-submit">Update Product</button>
            
            <div class="cancel-link">
                <a href="{{ route('admin.products.show', $product) }}">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
    .modal-overlay { position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(5px); display:flex; justify-content:center; align-items:center; z-index:9999; }
    .modal-container { background:#FFFFFF; border-radius:25px; padding:35px 40px; max-width:500px; width:90%; max-height:90vh; overflow-y:auto; font-family:'Poppins',sans-serif; color:#4A2C2A; box-shadow:0 25px 80px rgba(0,0,0,0.25); }
    .modal-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:25px; }
    .modal-header h1 { font-family:'Cooper Black', serif; color:#4A2C2A; margin:0 0 8px 0; font-size:1.6rem; }
    .modal-header p { color:#666; font-size:13px; margin:0; }
    .modal-close { background:#F0F2F5; border:none; width:38px; height:38px; border-radius:50%; cursor:pointer; font-size:18px; color:#666; display:flex; align-items:center; justify-content:center; text-decoration:none; }
    .modal-close:hover { background:#FFE5E5; color:#E74C3C; }
    .form-group { margin-bottom:18px; }
    .form-group label { display:block; font-size:13px; font-weight:600; margin-bottom:8px; color:#4A2C2A; }
    .form-group input[type="text"], .form-group input[type="number"], .form-group select, .form-group textarea { width:100%; padding:12px 15px; border-radius:12px; border:1.5px solid #F0F2F5; outline:none; box-sizing:border-box; font-family:'Poppins',sans-serif; font-size:14px; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color:#B07051; }
    .form-row { display:grid; grid-template-columns:1fr 1fr; gap:15px; }
    .error-msg { color:#c0392b; font-size:12px; margin-top:4px; }
    .upload-area { border:2px dashed #AEA9A0; border-radius:15px; padding:25px; text-align:center; cursor:pointer; display:block; }
    .upload-area i { font-size:24px; color:#AEA9A0; }
    .upload-area p { margin:10px 0 0; font-size:13px; color:#AEA9A0; }
    .upload-area:hover { background:#FDF9F0; border-color:#B07051; }
    #preview-img { max-width:100%; max-height:120px; border-radius:12px; border:1px solid #F0F2F5; }
    .checkbox-label { display:flex !important; align-items:center; gap:8px; cursor:pointer; margin-bottom:0 !important; }
    .checkbox-label input { width:18px; height:18px; accent-color:#4A2C2A; }
    .btn-submit { width:100%; background:#4A2C2A; color:white; border:none; padding:15px; border-radius:15px; font-weight:700; cursor:pointer; font-size:15px; margin-top:10px; }
    .btn-submit:hover { background:#633d3a; transform:translateY(-2px); }
    .cancel-link { text-align:center; margin-top:15px; }
    .cancel-link a { color:#AEA9A0; text-decoration:none; font-size:14px; }
    .cancel-link a:hover { color:#4A2C2A; }
    .subcategory-wrapper { display:flex; gap:8px; }
    .subcategory-wrapper select { flex:1; }
    .add-subcategory-btn { background:#B07051; color:white; border:none; width:44px; height:44px; border-radius:12px; cursor:pointer; display:flex; align-items:center; justify-content:center; }
    .add-subcategory-btn:hover { background:#4A2C2A; transform:scale(1.05); }
    .new-subcategory-form { margin-top:12px; padding:15px; background:#FDF9F0; border-radius:12px; border:1.5px solid #E8E0D5; }
    .new-subcat-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; font-size:13px; font-weight:600; color:#4A2C2A; }
    .new-subcat-header i { margin-right:6px; color:#B07051; }
    .close-subcat-form { background:none; border:none; font-size:20px; color:#999; cursor:pointer; padding:0; line-height:1; }
    .new-subcategory-form input { width:100%; padding:10px 12px; border-radius:10px; border:1.5px solid #E0D8CC; outline:none; font-size:13px; margin-bottom:10px; }
    .create-subcat-btn { background:#4A2C2A; color:white; border:none; padding:10px 20px; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer; }
    .success-msg { color:#27ae60; font-size:12px; margin-top:8px; }
</style>

<script>
    // IMAGE UPLOAD PREVIEW + DRAG/DROP
    const uploadArea = document.getElementById('upload-area');
    const imageInput = document.getElementById('image-upload');
    const uploadText = document.getElementById('upload-text');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    if (uploadArea) {
        uploadArea.addEventListener('dragover', (e) => { e.preventDefault(); uploadArea.style.background = '#FDF9F0'; uploadArea.style.borderColor = '#B07051'; });
        uploadArea.addEventListener('dragleave', () => { uploadArea.style.background = ''; uploadArea.style.borderColor = ''; });
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault(); uploadArea.style.background = ''; uploadArea.style.borderColor = ''; const files = e.dataTransfer.files; if (files.length > 0) { imageInput.files = files; showPreview(files[0]); }
        });
    }

    if (imageInput) {
        imageInput.addEventListener('change', (e) => { if (e.target.files.length > 0) showPreview(e.target.files[0]); });
    }

    function showPreview(file) {
        const errDiv = document.getElementById('image-error');
        if (!errDiv) return;
        errDiv.style.display = 'none'; errDiv.textContent = '';

        if (file && file.type.startsWith('image/')) {
            const MAX_BYTES = 5 * 1024 * 1024; 
            if (file.size > MAX_BYTES) {
                imagePreview.style.display = 'none'; uploadText.textContent = 'Click to upload or drag image'; imageInput.value = ''; errDiv.textContent = 'File is too large. Maximum size is 5 MB.'; errDiv.style.display = 'block'; return;
            }

            const reader = new FileReader();
            reader.onload = (e) => { previewImg.src = e.target.result; imagePreview.style.display = 'block'; uploadText.textContent = file.name; };
            reader.readAsDataURL(file);
        }
    }

        const categorySelect = document.getElementById('category_id');
        const subcategorySelect = document.getElementById('subcategory_id');
        const subcategoryOptions = subcategorySelect ? subcategorySelect.querySelectorAll('option[data-category]') : [];

        function filterSubcategories() {
            if (!categorySelect) return;
            const selectedCategory = categorySelect.value;
            if (!subcategorySelect) return;

            const currentVal = subcategorySelect.value;
            let currentStillValid = false;

            subcategoryOptions.forEach(option => {
                if (option.dataset.category === selectedCategory) {
                    option.style.display = '';
                    if (option.value === currentVal) currentStillValid = true;
                } else {
                    option.style.display = 'none';
                }
            });

            if (currentStillValid) {
                subcategorySelect.value = currentVal;
            } else {
                const firstVisible = Array.from(subcategoryOptions).find(o => o.dataset.category === selectedCategory);
                if (firstVisible) {
                    if (!currentVal) subcategorySelect.value = firstVisible.value;
                } else {
                    subcategorySelect.value = '';
                }
            }
        }

        if (categorySelect) { categorySelect.addEventListener('change', filterSubcategories); filterSubcategories(); }

    function showNewSubcategoryForm() { document.getElementById('new-subcategory-form').style.display = 'block'; document.getElementById('new_subcategory_name').focus(); }
    function hideNewSubcategoryForm() { document.getElementById('new-subcategory-form').style.display = 'none'; document.getElementById('new_subcategory_name').value = ''; document.getElementById('subcat-error').style.display = 'none'; document.getElementById('subcat-success').style.display = 'none'; }

    function createSubcategory() {
        const name = document.getElementById('new_subcategory_name').value.trim();
        const categoryId = document.getElementById('category_id').value;
        const errorDiv = document.getElementById('subcat-error');
        const successDiv = document.getElementById('subcat-success');
        errorDiv.style.display = 'none'; successDiv.style.display = 'none';
        if (!name) { errorDiv.textContent = 'Please enter a subcategory name.'; errorDiv.style.display = 'block'; return; }

        fetch('{{ route("admin.subcategories.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ name: name, category_id: categoryId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const newOption = document.createElement('option');
                newOption.value = data.subcategory.id; newOption.textContent = data.subcategory.name; newOption.dataset.category = categoryId; subcategorySelect.appendChild(newOption); subcategorySelect.value = data.subcategory.id; successDiv.textContent = '✓ Subcategory created!'; successDiv.style.display = 'block'; setTimeout(() => { hideNewSubcategoryForm(); }, 1000);
            } else { errorDiv.textContent = data.message || 'Failed to create subcategory.'; errorDiv.style.display = 'block'; }
        })
        .catch(error => { errorDiv.textContent = 'An error occurred. Please try again.'; errorDiv.style.display = 'block'; });
    }

    document.getElementById('new_subcategory_name')?.addEventListener('keypress', (e) => { if (e.key === 'Enter') { e.preventDefault(); createSubcategory(); } });

    const priceInput = document.getElementById('price');
    if (priceInput) {
        priceInput.addEventListener('keydown', (e) => { if (e.key === '-' || e.key === 'Subtract' || e.keyCode === 189 || e.keyCode === 109) e.preventDefault(); });
        priceInput.addEventListener('input', (e) => { const sanitized = e.target.value.replace(/-/g, ''); if (sanitized !== e.target.value) e.target.value = sanitized; });
        priceInput.addEventListener('paste', (e) => { const paste = (e.clipboardData || window.clipboardData).getData('text'); if (paste.includes('-')) { e.preventDefault(); const sanitized = paste.replace(/-/g, ''); const start = e.target.selectionStart || 0; const end = e.target.selectionEnd || 0; const val = e.target.value; e.target.value = val.slice(0, start) + sanitized + val.slice(end); } });
    }

    // CLOSE ON ESCAPE OR OVERLAY CLICK
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') { if (document.getElementById('new-subcategory-form').style.display === 'block') { hideNewSubcategoryForm(); } else { window.location.href = '{{ route("admin.products.show", $product) }}'; } } });
    document.getElementById('edit-product-overlay')?.addEventListener('click', (e) => { if (e.target.id === 'edit-product-overlay') { window.location.href = '{{ route("admin.products.show", $product) }}'; } });
</script>
@endsection
