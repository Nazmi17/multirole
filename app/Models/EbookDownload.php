<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EbookDownload extends Model
{
    protected $fillable = [
        'user_id',
        'ebook_id',
        'downloaded_at',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
