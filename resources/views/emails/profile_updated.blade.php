<!DOCTYPE html>
<html>
<head>
    <title>Update Profil Berhasil</title>
    <style>
        body { font-family: sans-serif; background-color: #f3f4f6; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-top: 4px solid #3b82f6; }
        .alert-icon { font-size: 40px; text-align: center; margin-bottom: 20px; display: block; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #ef4444; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; font-weight: bold; }
        .footer { font-size: 12px; color: #6b7280; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <span class="alert-icon">🛡️</span>
        <h2 style="color: #1f2937; text-align: center;">Profil Anda Telah Diperbarui</h2>
        
        <p style="color: #4b5563; line-height: 1.5;">
            Halo, kami ingin memberitahukan bahwa informasi profil pada akun Anda (Nama, Email, Password, atau Avatar) baru saja diubah.
        </p>

        <p style="color: #4b5563; line-height: 1.5;">
            <strong>Waktu Perubahan:</strong> {{ now()->format('d M Y, H:i') }} WIB
        </p>

        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 20px 0;">

        <p style="color: #4b5563;">
            Jika Anda yang melakukan perubahan ini, silakan abaikan email ini.
        </p>

        <p style="color: #ef4444; font-weight: bold;">
            Namun, jika Anda TIDAK merasa melakukan perubahan ini, segera amankan akun Anda:
        </p>

        <div style="text-align: center;">
            <a href="{{ route('password.request') }}" class="btn">Reset Password & Amankan Akun</a>
        </div>
        
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>