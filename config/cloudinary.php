<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration URL
    |--------------------------------------------------------------------------
    |
    | Kita rakit URL-nya di sini menggunakan 3 variabel dari .env
    | Format: cloudinary://API_KEY:API_SECRET@CLOUD_NAME
    |
    */
    'cloud_url' => 'cloudinary://' . env('CLOUDINARY_API_KEY') . ':' . env('CLOUDINARY_API_SECRET') . '@' . env('CLOUDINARY_CLOUD_NAME'),

    /*
    |--------------------------------------------------------------------------
    | Upload Preset
    |--------------------------------------------------------------------------
    */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET', 'ml_default'),

    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),
];