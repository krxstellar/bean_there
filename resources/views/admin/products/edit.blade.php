@extends('layouts.admin')

@section('admin-content')
<div style="max-width: 720px; margin: 0 auto;">
    <h1 style="font-family:'Cooper Black', serif; color:#4A2C2A;">Edit Product</h1>
    <form method="POST" action="{{ route('admin.products.update', $product) }}" style="margin-top:20px;">
        @csrf
        @method('PUT')
        <div style="margin-bottom:12px;">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
            @error('name')<div style="color:#c0392b;">{{ $message }}</div>@enderror
        </div>
        <div style="margin-bottom:12px;">
            <label>Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
            @error('slug')<div style="color:#c0392b;">{{ $message }}</div>@enderror
        </div>
        <div style="margin-bottom:12px;">
            <label>Category</label>
            <select name="category_id" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected($product->category_id === $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
            @error('category_id')<div style="color:#c0392b;">{{ $message }}</div>@enderror
        </div>
        <div style="margin-bottom:12px;">
            <label>Price</label>
            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
            @error('price')<div style="color:#c0392b;">{{ $message }}</div>@enderror
        </div>
        <div style="margin-bottom:12px;">
            <label>Image URL</label>
            <input type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
            @error('image_url')<div style="color:#c0392b;">{{ $message }}</div>@enderror
        </div>
        <div style="margin-bottom:12px;">
            <label>Description</label>
            <textarea name="description" rows="4" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">{{ old('description', $product->description) }}</textarea>
        </div>
        <div style="margin-bottom:12px;">
            <label><input type="checkbox" name="is_active" value="1" @checked($product->is_active)> Active</label>
        </div>
        <div>
            <button type="submit" style="background:#4A2C2A; color:white; border:none; padding:10px 16px; border-radius:10px;">Update</button>
            <a href="{{ route('admin.products.index') }}" style="margin-left:8px;">Cancel</a>
        </div>
    </form>
</div>
@endsection
