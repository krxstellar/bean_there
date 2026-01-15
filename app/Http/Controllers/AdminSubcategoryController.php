<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with('category', 'products')->orderBy('category_id')->orderBy('name')->get();
        return view('admin.subcategories.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.subcategories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $count = Subcategory::where('slug', 'like', $validated['slug'] . '%')->count();
        if ($count > 0) {
            $validated['slug'] .= '-' . ($count + 1);
        }

        $subcategory = Subcategory::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'subcategory' => [
                    'id' => $subcategory->id,
                    'name' => $subcategory->name,
                    'category_id' => $subcategory->category_id
                ]
            ]);
        }

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory created successfully.');
    }

    public function edit(Subcategory $subcategory)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        if ($validated['name'] !== $subcategory->name) {
            $validated['slug'] = Str::slug($validated['name']);
            $count = Subcategory::where('slug', 'like', $validated['slug'] . '%')->where('id', '!=', $subcategory->id)->count();
            if ($count > 0) {
                $validated['slug'] .= '-' . ($count + 1);
            }
        }

        $subcategory->update($validated);
        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory updated successfully.');
    }

    public function destroy(Subcategory $subcategory)
    {
        try {
            $subcategory->delete();

            if (request()->expectsJson()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory deleted successfully.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unable to delete subcategory. It may have related products.'], 400);
            }

            return redirect()->route('admin.subcategories.index')->with('error', 'Subcategory could not be deleted.');
        }
    }
}
