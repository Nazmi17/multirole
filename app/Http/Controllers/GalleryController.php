<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Gallery;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::latest();

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', "%" . $request->search . "%");
        }

        $galleries = $query->paginate(10)->withQueryString();
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
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5048', 
            'categories' => 'array',
        ]);

        $imagePath = $request->file('image')->store('galleries', 'public');

        $gallery = Gallery::create([
            'user_id' => Auth::id(),
            'album_id' => $request->album_id,
            'title' => $request->title,
            'caption' => $request->caption,
            'image' => $imagePath,
        ]);

        // Simpan Relasi Categories (Attach)
        if ($request->has('categories')) {
            $gallery->categories()->attach($request->categories);
        }

        return redirect()->route('galleries.index')->with('success', 'Foto berhasil diupload!');
    }

    public function edit(Gallery $gallery)
    {
        $albums = Album::all();
        $categories = Category::all();
        return view('gallery.edit', compact('gallery', 'albums', 'categories'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'album_id' => 'nullable|exists:albums,id',
            'title' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg|max:5048', 
        ]);

        $data = [
            'album_id' => $request->album_id,
            'title' => $request->title,
            'caption' => $request->caption,
        ];
        
        if ($request->hasFile('image')) {
            if (Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }

            $imagePath = $request->file('image')->store('galleries', 'public');
            $data['image'] = $imagePath;
        }

        $gallery->update($data);

        $gallery->categories()->sync($request->categories ?? []);

        return redirect()->route('galleries.index')->with('success', 'Foto berhasil diupdate!');
    }

    public function destroy(Gallery $gallery)
    {
        if (Storage::disk('public')->exists($gallery->image)) {
            Storage::disk('public')->delete($gallery->image);
        }

        $gallery->delete();

        return redirect()->route('galleries.index')->with('success', 'Foto berhasil dihapus!');
    }
}
