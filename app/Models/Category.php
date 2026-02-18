<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['title', 'slug'];

    public function galery()
    {
        return $this->belongsToMany(Gallery::class, 'category_gallery');
    }

    // Relasi: Kategori memiliki banyak Artikel
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_category');
    }
}
