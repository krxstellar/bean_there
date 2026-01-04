<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSubcategoryController extends Controller
{
    // DISPLAY ALL SUBCATEGORIES
    public function index()
    {
        $subcategories = Subcategory::with('category', 'products')->orderBy('category_id')->orderBy('name')->get();
        return view('admin.subcategories.index', compact('subcategories'));
    }

    // SHOW CREATE FORM
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.subcategories.create', compact('categories'));
    }

    // STORE NEW SUBCATEGORY
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        // AUTO-GENERATE SLUG FROM NAME
        $validated['slug'] = Str::slug($validated['name']);
        $count = Subcategory::where('slug', 'like', $validated['slug'] . '%')->count();
        if ($count > 0) {
            $validated['slug'] .= '-' . ($count + 1);
        }

        $subcategory = Subcategory::create($validated);

        // RETURN JSON FOR AJAX REQUESTS
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

    // SHOW EDIT FORM
    public function edit(Subcategory $subcategory)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    // UPDATE SUBCATEGORY
    public function update(Request $request, Subcategory $subcategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        // AUTO-GENERATE SLUG FROM NAME IF NAME CHANGED
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

    // DELETE SUBCATEGORY
    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();
        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory deleted successfully.');
    }
}
