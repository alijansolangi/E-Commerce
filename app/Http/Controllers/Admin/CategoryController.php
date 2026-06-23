<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return redirect()->route('category.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        
        // dd(Str::slug($request->slug));
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->name = $request->name;
        $category->slug = Str::slug($request->slug);
        $category->update();

        return redirect()
            ->route('category.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('category.index')
            ->with('success', 'Category deleted successfully.');
    }
}
