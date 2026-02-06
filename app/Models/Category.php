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
}
