<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

class PublicGalleryAlbumController extends Controller
{
    public function gallery(Request $request)
    {
        // Ambil album dengan jumlah foto di dalamnya
        $query = Album::withCount('galleries')->latest();

        // 1. Filter Search
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', "%" . $request->search . "%")
                  ->orWhere('description', 'like', "%" . $request->search . "%");
        }

        // 2. Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'oldest':
                    $query->oldest();
                    break;
                case 'popular': // Asumsi populer berdasarkan jumlah foto terbanyak
                    $query->orderBy('galleries_count', 'desc');
                    break;
                default: // recent
                    $query->latest();
                    break;
            }
        }

        $albums = $query->paginate(9)->withQueryString();

        return view('pages.gallery', compact('albums'));
    }

    /**
     * Menampilkan detail foto-foto dalam satu Album
     */
    public function showAlbum(Album $album)
    {
        // Load foto-foto di dalam album ini
        $album->load(['galleries' => function($q) {
            $q->latest();
        }]);

        return view('pages.gallery-detail', compact('album'));
    }
}
