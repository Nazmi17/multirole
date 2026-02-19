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
            'title'    => 'required|string|max:255',
            'caption'  => 'nullable|string',
            
            // Validasi Kondisional
            // Kita akan kirim input 'type' ('photo' atau 'video') dari form
            'type'      => 'required|in:photo,video',
            'image'     => 'required_if:type,photo|image|mimes:jpeg,png,jpg,webp|max:5048',
            'video_url' => 'required_if:type,video|url',
            
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $data = [
            'user_id'   => Auth::id(),
            'album_id'  => $request->album_id,
            'title'     => $request->title,
            'caption'   => $request->caption,
            'video_url' => ($request->type == 'video') ? $request->video_url : null,
        ];

        // Handle Upload Gambar (Jika tipe photo, atau tipe video tapi user upload cover custom)
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('galleries', 'public');
        }

        $gallery = Gallery::create($data);
        $gallery->categories()->sync($request->categories ?? []);

        return redirect()->route('galleries.index')->with('success', 'Galeri berhasil ditambahkan!');
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
        $request->validate([
            'album_id'  => 'nullable|exists:albums,id',
            'title'     => 'required|string|max:255',
            'type'      => 'required|in:photo,video',
            
            // Saat update, image wajib jika tipe photo DAN belum ada gambar sebelumnya
            'image'     => ['nullable', 'image', 'max:5048', 
                            function ($attribute, $value, $fail) use ($request, $gallery) {
                                if ($request->type == 'photo' && !$value && !$gallery->image) {
                                    $fail('Gambar wajib diupload untuk tipe Foto.');
                                }
                            }],
            'video_url' => 'required_if:type,video|url',
        ]);

        $data = [
            'album_id' => $request->album_id,
            'title'    => $request->title,
            'caption'  => $request->caption,
        ];

        // Logika Ganti Tipe
        if ($request->type == 'video') {
            $data['video_url'] = $request->video_url;
            // Opsional: Hapus image lama jika beralih ke video murni (tergantung kebutuhan)
            // Tapi saran saya biarkan saja image lama sebagai backup/cover
        } else {
            $data['video_url'] = null; // Hapus URL jika beralih ke foto
        }

        if ($request->hasFile('image')) {
            if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }
            $data['image'] = $request->file('image')->store('galleries', 'public');
        }

        $gallery->update($data);
        $gallery->categories()->sync($request->categories ?? []);

        return redirect()->route('galleries.index')->with('success', 'Galeri berhasil diupdate!');
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