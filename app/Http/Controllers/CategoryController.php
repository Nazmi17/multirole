<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::latest();
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', "%" . $request->search . "%");
        }

        $categories = $query->paginate(10)->withQueryString();  
        return view('category.index', compact('categories'));
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('categories', 'title')],
        ]);

        Category::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('categories', 'title')->ignore($category->id),],
        ]);

        $category->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title)
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
