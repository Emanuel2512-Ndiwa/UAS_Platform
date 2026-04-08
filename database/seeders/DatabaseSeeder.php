<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = \Illuminate\Support\Facades\Hash::make('password');

        User::create([
            'firstname' => 'Super',
            'lastname' => 'Admin',
            'email' => 'admin@example.com',
            'password' => $password,
            'phone_number' => '081234567890',
            'address' => 'Jakarta',
            'role' => 'admin',
        ]);
        User::create([
            'firstname' => 'John',
            'lastname' => 'Karyawan',
            'email' => 'karyawan@example.com',
            'password' => $password,
            'phone_number' => '081234567891',
            'address' => 'Jakarta',
            'role' => 'karyawan',
        ]);
        User::create([
            'firstname' => 'Budi',
            'lastname' => 'Kurir',
            'email' => 'kurir@example.com',
            'password' => $password,
            'phone_number' => '081234567892',
            'address' => 'Jakarta',
            'role' => 'kurir',
        ]);
        User::create([
            'firstname' => 'Siti',
            'lastname' => 'Pelanggan',
            'email' => 'pelanggan@example.com',
            'password' => $password,
            'phone_number' => '081234567893',
            'address' => 'Jakarta',
            'role' => 'pelanggan',
        ]);
    }
}
