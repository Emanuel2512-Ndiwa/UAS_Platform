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

        // --- SERVICES SEEDER ---
        \App\Models\Service::create(['name' => 'Cuci Kering', 'price' => 7000]);
        \App\Models\Service::create(['name' => 'Cuci Setrika', 'price' => 10000]);
        \App\Models\Service::create(['name' => 'Setrika Saja', 'price' => 5000]);

        // --- INVENTORY SEEDER (Fixed IDs) ---
        \App\Models\Inventory::create(['id' => 1, 'item_name' => 'Sabun Cair', 'stock' => 10, 'unit' => 'Liter']);
        \App\Models\Inventory::create(['id' => 2, 'item_name' => 'Pewangi', 'stock' => 10, 'unit' => 'Liter']);
    }
}
