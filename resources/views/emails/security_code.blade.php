<!DOCTYPE html>
<html>
<head>
    <title>Kode Verifikasi</title>
    <style>
        body { font-family: sans-serif; background-color: #f3f4f6; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .code-box { background-color: #f0fdf4; border: 2px dashed #16a34a; color: #16a34a; font-size: 32px; font-weight: bold; text-align: center; padding: 15px; margin: 20px 0; letter-spacing: 5px; border-radius: 5px; }
        .footer { font-size: 12px; color: #6b7280; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="color: #1f2937; text-align: center;">Konfirmasi Perubahan</h2>
        <p style="color: #4b5563; line-height: 1.5;">
            Halo, kami mendeteksi permintaan perubahan profil pada akun Anda. 
            Gunakan kode di bawah ini untuk memverifikasi tindakan tersebut:
        </p>

        <div class="code-box">
            {{ $code }}
        </div>

        <p style="color: #4b5563; text-align: center; font-size: 14px;">
            Kode ini akan kadaluarsa dalam <strong>5 menit</strong>.<br>
            Jika Anda tidak meminta perubahan ini, abaikan email ini.
        </p>
        
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>