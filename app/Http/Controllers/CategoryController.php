<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('childrenRecursive')->whereNull('parent_id')->paginate(10);
        
        return Inertia::render('Categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        return Inertia::render('Categories/Create', [
            'categories' => Category::with('childrenRecursive')->whereNull('parent_id')->where('status', 1)->get(),
        ]);
    }

    public function store(CategoryRequest $request)
    {
        if ($request->hasFile('image')) {
            $request->image = $request->file('image')->store('categories', 'public');
        }
        Category::create([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'status'    => (bool) $request->status,
            'image'     => $request->image,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }
/*
    public function edit(Category $category)
    {
        return Inertia::render('Categories/Edit', [
            'category'   => $category,
            'categories' => Category::whereNull('parent_id')->where('id', '!=', $category->id)->get(),
        ]);
    }
*/
    public function edit(Category $category)
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return Inertia::render('Categories/Edit', compact('category', 'categories'));
    }


    public function update(CategoryRequest $request, Category $category)
    {
        if ($request->hasFile('image')) {
            // delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $request->image = $request->file('image')->store('categories', 'public');
        }
        $category->update([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'image'     => $request->image ?? $category->image,    
            'description' => $request->description,
            'status'    => (bool) $request->status,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}