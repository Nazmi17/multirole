<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Category;
use App\Models\Gallery;
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

    public function show(Album $album)
    {
        // Eager load: ambil relasi galleries beserta kategorinya, dan user pembuatnya
        $album->load(['galleries.categories', 'user']);
        
        return view('album.show', compact('album'));
    }

    public function create()
    {
        $galleries = Gallery::where('user_id', Auth::id())
                            ->whereNull('album_id')
                            ->latest()
                            ->get();
        return view('album.create', compact('galleries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg|max:5048',
            'gallery_ids' => 'nullable|array',
            'gallery_ids.*' => 'exists:galleries,id',
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

        $album = Album::create($data);

        if ($request->has('gallery_ids')) {
            Gallery::whereIn('id', $request->gallery_ids)->update(['album_id' => $album->id]);
        }

        return redirect()->route('albums.index')->with('success', 'Album berhasil dibuat!');
    }

    public function edit(Album $album)
    {
        $availableGalleries = Gallery::where('user_id', Auth::id())
                                     ->whereNull('album_id')
                                     ->latest()
                                     ->get();

        return view('album.edit', compact('album', 'availableGalleries'));
    }

    public function update(Request $request, Album $album)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'image|mimes:jpeg,png,jpg|max:5048',
            'gallery_ids' => 'nullable|array',
            'gallery_ids.*' => 'exists:galleries,id',
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

        if ($request->has('gallery_ids')) {
            Gallery::whereIn('id', $request->gallery_ids)->update(['album_id' => $album->id]);
        }

        return redirect()->route('albums.index')->with('success', 'Album berhasil diupdate!');
    }

    public function destroy(Album $album)
    {
        if (Storage::disk('public')->exists($album->cover_image)) {
            Storage::disk('public')->delete($album->cover_image);
        }

        $album->galleries()->update(['album_id' => null]);

        $album->delete();
        return redirect()->route('album.index')->with('success', 'Album berhasil dihapus!');
    }

    public function removeGallery($album_id, $gallery_id)
    {
        $gallery = Gallery::where('id', $gallery_id)->where('album_id', $album_id)->firstOrFail();
        
        // Set album_id jadi null (keluarkan dari album, TAPI file tetap ada di galeri)
        $gallery->update(['album_id' => null]);

        return back()->with('success', 'Foto berhasil dikeluarkan dari album.');
    }
}
