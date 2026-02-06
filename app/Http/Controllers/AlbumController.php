<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    public function index(Request $request)
    {
        $query = Album::with('user')->withCount('galleries')->latest();

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', "%" . $request->search . "%");
        }

        $albums = $query->paginate(10)->withQueryString();
        return view('album.index', compact('albums'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('album.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg|max:5048',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
        ];

        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('albums', 'public');
            $data['cover_image'] = $imagePath;
        }

        Album::create($data);
        return redirect()->route('albums.index')->with('success', 'Album berhasil dibuat!');
    }

    public function edit(Album $album)
    {
        $categories = Category::all();
        return view('album.edit', compact('album', 'categories'));
    }

    public function update(Request $request, Album $album)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'image|mimes:jpeg,png,jpg|max:5048',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
        ];
        
        if ($request->hasFile('cover_image')) {
            if (Storage::disk('public')->exists($album->cover_image)) {
                Storage::disk('public')->delete($album->cover_image);
            }

            $imagePath = $request->file('cover_image')->store('albums', 'public');
            $data['cover_image'] = $imagePath;
        }

        $album->update($data);

        return redirect()->route('albums.index')->with('success', 'Album berhasil diupdate!');
    }

    public function destroy(Album $album)
    {
        if (Storage::disk('public')->exists($album->cover_image)) {
            Storage::disk('public')->delete($album->cover_image);
        }

        $album->delete();
        return redirect()->route('album.index')->with('success', 'Album berhasil dihapus!');
    }
}
