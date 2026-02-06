<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['user_id', 'album_id', 'title', 'caption', 'image', 'views'];

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
}
