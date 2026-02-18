<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Gallery;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate; // Gunakan Gate agar sama dengan ArticleController

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::with('categories'); 

        if ($request->filled('search')) {
            $query->where('title', 'like', "%" . $request->search . "%");
        }

        $galleries = $query->latest()->paginate(10)->withQueryString();
        return view('gallery.index', compact('galleries'));
    }

    public function create()
    {
        $albums = Album::all();
        $categories = Category::all();
        return view('gallery.create', compact('albums', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'album_id' => 'nullable|exists:albums,id',
            'title' => 'required|string|max:255',
            'caption' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5048',
            
            // Validasi Array Kategori
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $imagePath = $request->file('image')->store('galleries', 'public');

        $gallery = Gallery::create([
            'user_id' => Auth::id(),
            'album_id' => $request->album_id,
            'title' => $request->title,
            'caption' => $request->caption,
            'image' => $imagePath,
        ]);

        // Gunakan sync() agar konsisten. 
        // Logic: Jika ada categories, sync. Jika null, sync array kosong [].
        $gallery->categories()->sync($request->categories ?? []);

        return redirect()->route('galleries.index')->with('success', 'Foto berhasil diupload!');
    }

    public function edit(Gallery $gallery)
    {
        // Opsional: Tambahkan Gate Authorization
        // Gate::authorize('update', $gallery); 

        $albums = Album::all();
        $categories = Category::all();
        
        return view('gallery.edit', compact('gallery', 'albums', 'categories'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        // Opsional: Tambahkan Gate Authorization
        // Gate::authorize('update', $gallery);

        $request->validate([
            'album_id' => 'nullable|exists:albums,id',
            'title' => 'required|string|max:255',
            'caption' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5048',
            
            // Validasi Array Kategori
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $data = [
            'album_id' => $request->album_id,
            'title' => $request->title,
            'caption' => $request->caption,
        ];

        if ($request->hasFile('image')) {
            if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }
            $data['image'] = $request->file('image')->store('galleries', 'public');
        }

        $gallery->update($data);

        // SYNC: Otomatis hapus yang tidak dicentang, tambah yang dicentang baru
        $gallery->categories()->sync($request->categories ?? []);

        return redirect()->route('galleries.index')->with('success', 'Foto berhasil diupdate!');
    }

    public function destroy(Gallery $gallery)
    {
        // Gate::authorize('delete', $gallery);

        if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
            Storage::disk('public')->delete($gallery->image);
        }

        // Relasi pivot di table 'category_gallery' akan otomatis terhapus 
        // JIKA di migration kamu sudah set ->onDelete('cascade')
        
        $gallery->delete();

        return redirect()->route('galleries.index')->with('success', 'Foto berhasil dihapus!');
    }
}