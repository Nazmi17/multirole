<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    protected $fillable = ['user_id', 'album_id', 'title', 'caption', 'image', 'views', 'video_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_gallery');
    }

  /**
     * MUTATOR: Dijalankan otomatis saat data disimpan/diupdate.
     */
    public function setVideoUrlAttribute($value)
    {
        $url = $value;

        // 1. Expand jika Short Link (TikTok)
        if (str_contains($url, 'vm.tiktok.com') || str_contains($url, 'vt.tiktok.com')) {
            $url = $this->expandUrl($url);
        }

        // 2. PEMBERSIHAN LINK (FIX ERROR DATA TOO LONG)
        // Kita buang parameter tracking (?share_id=..., ?igsh=...) 
        // yang bikin link jadi ribuan karakter.
        
        // Hanya lakukan ini untuk TikTok dan Instagram (YouTube JANGAN, karena ID-nya ada di ?v=)
        if (str_contains($url, 'tiktok.com') || str_contains($url, 'instagram.com')) {
             $parts = explode('?', $url);
             $url = $parts[0]; // Ambil bagian depan saja
        }

        $this->attributes['video_url'] = $url;
    }

    /**
     * Helper untuk mendapatkan URL asli dari short link
     */
    private function expandUrl($url)
    {
        $headers = @get_headers($url, 1);
        if (!$headers || !isset($headers['Location'])) return $url;
        
        $location = $headers['Location'];
        // Jika redirect berkali-kali, ambil yang terakhir
        return is_array($location) ? end($location) : $location;
    }

    /**
     * ACCESSOR: Logic Pintar untuk Mendeteksi Tipe Video & URL Embed
     */
    public function getVideoEmbedAttribute()
    {
        $url = $this->attributes['video_url']; // Ambil langsung dari atribut raw
        if (!$url) return null;

        // 1. Cek YOUTUBE
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            return [
                'type' => 'youtube',
                'url'  => "https://www.youtube.com/embed/" . $match[1]
            ];
        }

        // 2. Cek INSTAGRAM (Post & Reels)
        // UPDATE: Regex diperkuat untuk menangani query string (?igsh=...)
        if (preg_match('/instagram\.com\/(?:p|reel)\/([a-zA-Z0-9_-]+)/', $url, $match)) {
            return [
                'type' => 'instagram',
                // UPDATE: Menambahkan /captioned/ agar lebih jarang diblokir browser
                'url'  => "https://www.instagram.com/p/" . $match[1] . "/embed/captioned/"
            ];
        }

        // 3. Cek TIKTOK
        // Regex ini sekarang akan berhasil KARENA link sudah dipanjangkan oleh Mutator di atas
        if (preg_match('/tiktok\.com\/@?[^\/]+\/video\/(\d+)/', $url, $match)) {
            return [
                'type' => 'tiktok',
                'url'  => "https://www.tiktok.com/embed/v2/" . $match[1]
            ];
        }

        return null;
    }

    // Tambahkan accessor ini di App\Models\Gallery.php
// Untuk mendapatkan URL thumbnail secara otomatis
    public function getThumbnailUrlAttribute()
    {
        // 1. Jika ada gambar upload, pakai itu
        if ($this->image) {
            return Storage::url($this->image);
        }
        
        // 2. Jika tidak ada gambar, tapi ada link YouTube, ambil thumbnail YouTube
        if ($this->video_url && $this->youtube_embed_url) {
            // Ambil ID dari URL Embed yg sudah kita buat logicnya sebelumnya
            // Format embed: https://www.youtube.com/embed/VIDEO_ID
            $parts = explode('/', $this->youtube_embed_url);
            $videoId = end($parts);
            return "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
        }

        // 3. Fallback jika video non-youtube dan tidak ada cover
        return asset('images/video-placeholder.jpg'); // Pastikan kamu punya gambar ini di public/images
    }
}
