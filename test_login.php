<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;

$roles = ['admin', 'karyawan', 'kurir', 'pelanggan'];

echo "\n--- HASIL TES LOGIN ROLE ---\n";
foreach ($roles as $role) {
    $email = "{$role}@example.com";
    $password = 'password';
    
    if (Auth::attempt(['email' => $email, 'password' => $password])) {
        $user = Auth::user();
        echo "[SUKSES] Berhasil login sebagai: {$user->firstname} {$user->lastname} | Role: {$user->role} | is_active: {$user->is_active}\n";
        Auth::logout();
    } else {
        echo "[GAGAL] Gagal login untuk email: {$email}\n";
    }
}
echo "------------------------------\n";
