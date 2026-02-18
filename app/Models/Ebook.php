<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'file_path',
        'is_login_required',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function downloads()
    {
        return $this->hasMany(EbookDownload::class);
    }   
}
