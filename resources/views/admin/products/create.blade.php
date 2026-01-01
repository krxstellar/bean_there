@extends('layouts.admin')

@section('admin-content')
<div style="max-width: 500px; margin: 40px auto;">
    <div style="background: white; border-radius: 25px; padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); font-family: 'Poppins', sans-serif;">
        <h1 style="font-family: 'Cooper Black', serif; color: #4A2C2A; margin: 0 0 8px 0; font-size: 1.8rem;">Add New Product</h1>
        <p style="color: #666; font-size: 14px; margin-bottom: 25px;">Enter the details of the new pastry or drink.</p>

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #4A2C2A;">Product Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Chocolate Croissant" 
                       style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #F0F2F5; outline: none; box-sizing: border-box; font-family: 'Poppins', sans-serif;">
                @error('name')<div style="color:#c0392b; font-size:12px; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #4A2C2A;">Price (₱)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" placeholder="0.00" 
                           style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #F0F2F5; outline: none; box-sizing: border-box; font-family: 'Poppins', sans-serif;">
                    @error('price')<div style="color:#c0392b; font-size:12px; margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #4A2C2A;">Category</label>
                    <select name="category_id" style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #F0F2F5; outline: none; background: white; cursor: pointer; font-family: 'Poppins', sans-serif;">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<div style="color:#c0392b; font-size:12px; margin-top:4px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #4A2C2A;">Description</label>
                <textarea name="description" rows="3" placeholder="Product description (optional)"
                          style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #F0F2F5; outline: none; box-sizing: border-box; font-family: 'Poppins', sans-serif; resize: vertical;">{{ old('description') }}</textarea>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #4A2C2A;">Product Image</label>
                <label for="image-upload" class="upload-area" id="upload-area">
                    <i class="fa-solid fa-cloud-arrow-up" style="font-size: 24px; color: #AEA9A0;"></i>
                    <p style="margin: 10px 0 0; font-size: 13px; color: #AEA9A0;" id="upload-text">Click to upload or drag image</p>
                    <input type="file" name="image" id="image-upload" accept="image/*" style="display: none;">
                </label>
                <div id="image-preview" style="display: none; margin-top: 10px; text-align: center;">
                    <img id="preview-img" src="" alt="Preview" style="max-width: 100%; max-height: 150px; border-radius: 12px; border: 1px solid #F0F2F5;">
                </div>
                @error('image')<div style="color:#c0392b; font-size:12px; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" checked style="width: 18px; height: 18px; accent-color: #4A2C2A;">
                    <span style="font-size: 14px; color: #4A2C2A;">Active (visible to customers)</span>
                </label>
            </div>

            <button type="submit" style="width: 100%; background: #4A2C2A; color: white; border: none; padding: 15px; border-radius: 15px; font-weight: 700; cursor: pointer; font-family: 'Poppins', sans-serif; font-size: 15px; transition: 0.3s;"
                    onmouseover="this.style.background='#633d3a'" onmouseout="this.style.background='#4A2C2A'">
                Save Product
            </button>
            
            <div style="text-align: center; margin-top: 15px;">
                <a href="{{ route('admin.catalog') }}" style="color: #AEA9A0; text-decoration: none; font-size: 14px;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
    .upload-area {
        border: 2px dashed #AEA9A0;
        border-radius: 15px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: 0.2s;
        display: block;
    }
    .upload-area:hover {
        background: #FDF9F0;
        border-color: #B07051;
    }
    .upload-area.dragover {
        background: #FDF9F0;
        border-color: #B07051;
    }
</style>

<script>
    const uploadArea = document.getElementById('upload-area');
    const imageInput = document.getElementById('image-upload');
    const uploadText = document.getElementById('upload-text');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    // DRAG AND DROP
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            imageInput.files = files;
            showPreview(files[0]);
        }
    });

    // FILE INPUT CHANGE
    imageInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            showPreview(e.target.files[0]);
        }
    });

    function showPreview(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
                uploadText.textContent = file.name;
            };
            reader.readAsDataURL(file);
        }
    }

</script>
@endsection
