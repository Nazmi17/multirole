<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = ['user_id', 'title', 'cover_image', 'description', 'slug', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    
}
