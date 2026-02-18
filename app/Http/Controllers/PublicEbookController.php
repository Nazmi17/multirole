<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\EbookDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PublicEbookController extends Controller
{
    public function index()
    {
        $ebooks = Ebook::with('creator')->latest()->paginate(10);
        return view('pages.ebooks', compact('ebooks'));
    }

    public function download(Ebook $ebook)
    {
        // 1. Cek Requirement Login
        if ($ebook->is_login_required) {
            
            // Jika user belum login, redirect ke login page
            if (!Auth::check()) {
                return redirect()->route('login')
                    ->with('error', 'E-book ini hanya untuk member terdaftar. Silakan login terlebih dahulu.');
            }

            $user = Auth::user();

            // 2. Cek History / Pencatatan
            // Kita gunakan firstOrCreate untuk memastikan data unik (sesuai struktur table)
            // Ini mencegah satu user tercatat berkali-kali untuk buku yang sama
            EbookDownload::firstOrCreate(
                [
                    'user_id'  => $user->id,
                    'ebook_id' => $ebook->id,
                ],
                [
                    'downloaded_at' => now(),
                ]
            );
        }

        // 3. Serve File Secara Aman
        // Cek fisik file di storage
        if (!Storage::exists($ebook->file_path)) {
            abort(404, 'Maaf, file e-book tidak ditemukan di server.');
        }

        // Download file dengan nama yang rapi (slug.pdf)
        return Storage::download($ebook->file_path, $ebook->slug . '.pdf');
    }

    public function stream(Ebook $ebook)
        {
            // 1. Cek Login (Security tetap jalan!)
            if ($ebook->is_login_required) {
                if (!Auth::check()) {
                    return redirect()->route('login')
                        ->with('error', 'Silakan login untuk membaca e-book ini.');
                }

                // Cek/Catat History (Opsional: kalau mau catat orang yang 'baca' juga)
                $user = Auth::user();
                
                EbookDownload::firstOrCreate(
                [
                    'user_id'  => $user->id,
                    'ebook_id' => $ebook->id,
                ],
                [
                    'downloaded_at' => now(),
                ]
                );
            }

            // 2. Cek File
            if (!Storage::exists($ebook->file_path)) {
                abort(404);
            }

            // 3. TAMPILKAN DI BROWSER (INLINE)
            // Kita ambil path lengkap filenya
            $path = Storage::path($ebook->file_path);

            return response()->file($path, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $ebook->slug . '.pdf"'
            ]);
        }
}
