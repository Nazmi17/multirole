<!DOCTYPE html>
<html>
<head>
    <title>User Baru Terdaftar</title>
</head>
<body>
    <h2>Laporan Pendaftaran Baru</h2>
    <p>Ada user baru yang baru saja mendaftar:</p>
    <ul>
        <li><strong>Nama:</strong> {{ $newUser->name }}</li>
        <li><strong>Email:</strong> {{ $newUser->email }}</li>
        <li><strong>Username:</strong> {{ $newUser->username }}</li>
        <li><strong>Waktu Daftar:</strong> {{ $newUser->created_at->format('d M Y, H:i') }}</li>
    </ul>
    <p>Silakan login ke dashboard admin untuk mengelola user tersebut.</p>
</body>
</html>