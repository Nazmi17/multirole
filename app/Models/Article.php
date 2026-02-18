<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'image', 
        'featured_image',   
        'status',
        'excerpt',
        'views_count',
        'likes_count',
        'admin_feedback',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
        ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Relasi: Artikel dimiliki oleh User (Penulis)
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi: Artikel memiliki banyak Kategori (Many-to-Many)
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'article_category');
    }

    // Relasi: Artikel memiliki banyak Komentar
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getReadTimeAttribute()
    {
        // Asumsi kecepatan baca rata-rata: 200 kata per menit
        $words = str_word_count(strip_tags($this->content));
        $minutes = ceil($words / 200);
        
        return $minutes . ' min read';
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'article_likes');
    }

    // Helper: Cek apakah user yang sedang login sudah like artikel ini?
    public function isLikedBy(User $user = null)
    {
        if (!$user) {
            return false;
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
