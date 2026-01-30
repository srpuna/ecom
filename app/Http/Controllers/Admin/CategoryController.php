<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('subCategories')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        // Handle SubCategory Store (if creating sub) or Main Category
        if ($request->has('parent_id') && $request->parent_id) {
            $request->validate(['name' => 'required', 'parent_id' => 'exists:categories,id']);
            SubCategory::create([
                'category_id' => $request->parent_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ]);
            return back()->with('success', 'Sub-Category created.');
        } else {
            $request->validate(['name' => 'required']);
            Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description
            ]);
            return redirect()->route('admin.categories.index')->with('success', 'Category created.');
        }
    }

    // Simplified for this task: We'll assume editing is simple or just show index
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required']);
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description
        ]);
        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }
}
