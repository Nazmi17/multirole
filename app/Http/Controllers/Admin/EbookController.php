<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EbookController extends Controller
{
    /**
     * Menampilkan daftar E-book (Admin Dashboard).
     */
    public function index()
    {
        Gate::authorize('viewAny', Ebook::class);

        $ebooks = Ebook::latest()->paginate(10);
        return view('ebooks.index', compact('ebooks'));
    }

    public function history(Ebook $ebook)
    {
        Gate::authorize('viewAny', Ebook::class);

        // Ambil data download, urutkan dari yang terbaru
        // Kita gunakan 'with' untuk eager loading data User agar query ringan
        $downloads = $ebook->downloads()
            ->with('user')
            ->latest('downloaded_at')
            ->paginate(20);

        return view('ebooks.history', compact('ebook', 'downloads'));
    }

    /**
     * Menampilkan form create.
     */
    public function create()
    {
        Gate::authorize('create', Ebook::class);

        return view('ebooks.create');
    }

    /**
     * Menyimpan E-book baru ke database.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Ebook::class);

        $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'nullable|string',
            'cover_image'       => 'nullable|image|mimes:jpeg,png,jpg|max:5048', // Max 2MB
            'file'              => 'required|mimes:pdf|max:20480', // Max 20MB, PDF Only
            'is_login_required' => 'boolean',
        ]);

        // 1. Upload Cover (Public Storage)
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('ebook-covers', 'public');
        }

        // 2. Upload File E-book (Private/Local Storage)
        // Disimpan di storage/app/ebook-files (Tidak bisa diakses via browser langsung)
        $filePath = $request->file('file')->store('ebook-files');

        // 3. Generate Slug Unik
        $slug = $this->generateUniqueSlug($request->title);

        Ebook::create([
            'title'             => $request->title,
            'slug'              => $slug,
            'description'       => $request->description,
            'cover_image'       => $coverPath,
            'file_path'         => $filePath,
            'is_login_required' => $request->has('is_login_required'), // Checkbox value
            'created_by'        => auth()->id(),
        ]);

        return redirect()->route('ebooks.index')
            ->with('success', 'E-book berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit.
     */
    public function edit(Ebook $ebook)
    {
        Gate::authorize('update', $ebook);

        return view('ebooks.edit', compact('ebook'));
    }

    /**
     * Update data E-book.
     */
    public function update(Request $request, Ebook $ebook)
    {
        Gate::authorize('update', $ebook);

        $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'nullable|string',
            'cover_image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file'              => 'nullable|mimes:pdf|max:20480', // Nullable karena user mungkin tidak ganti file
            'is_login_required' => 'boolean',
        ]);

        $data = [
            'title'             => $request->title,
            'description'       => $request->description,
            'is_login_required' => $request->has('is_login_required'),
        ];

        // 1. Cek Ganti Judul -> Regenerate Slug
        if ($request->title !== $ebook->title) {
            $data['slug'] = $this->generateUniqueSlug($request->title, $ebook->id);
        }

        // 2. Cek Ganti Cover
        if ($request->hasFile('cover_image')) {
            // Hapus cover lama jika ada
            if ($ebook->cover_image && Storage::disk('public')->exists($ebook->cover_image)) {
                Storage::disk('public')->delete($ebook->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('ebook-covers', 'public');
        }

        // 3. Cek Ganti File PDF
        if ($request->hasFile('file')) {
            // Hapus file lama (Penting agar storage tidak penuh sampah)
            if ($ebook->file_path && Storage::exists($ebook->file_path)) {
                Storage::delete($ebook->file_path);
            }
            $data['file_path'] = $request->file('file')->store('ebook-files');
        }

        $ebook->update($data);

        return redirect()->route('ebooks.index')
            ->with('success', 'E-book berhasil diperbarui.');
    }

    /**
     * Hapus E-book dan filenya.
     */
    public function destroy(Ebook $ebook)
    {
        Gate::authorize('delete', $ebook);

        // Hapus Cover
        if ($ebook->cover_image && Storage::disk('public')->exists($ebook->cover_image)) {
            Storage::disk('public')->delete($ebook->cover_image);
        }

        // Hapus File PDF
        if ($ebook->file_path && Storage::exists($ebook->file_path)) {
            Storage::delete($ebook->file_path);
        }

        $ebook->delete();

        return redirect()->route('ebooks.index')
            ->with('success', 'E-book berhasil dihapus.');
    }

    /**
     * Generate Unique Slug (Sesuai request).
     */
    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // Cek apakah slug sudah ada di database (Model Ebook)?
        // Jika ada, tambahkan angka -1, -2, dst sampai nemu yang kosong.
        // Parameter $ignoreId dipakai saat Edit (biar gak bentrok sama diri sendiri)
        while (Ebook::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}