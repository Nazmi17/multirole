<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
 public function validate(string $attribute, mixed $value, Closure $fail): void
{
    $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        'secret'   => env('RECAPTCHA_SECRET_KEY'), 
        'response' => $value,
        'remoteip' => request()->ip(),
    ]);

    // Debugging (Opsional): Kalau mau lihat pesan error asli dari Google
    // dd($response->json()); 

    if (!$response->json('success')) {
        $fail('Recaptcha Tidak Valid');
    }
}
}
