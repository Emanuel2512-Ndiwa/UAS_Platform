<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ ganti ini
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable // ✅ ubah ke Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone_number',
        'address',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function run(): void
    {
        User::factory(10)->create(); // Pastikan factory-nya sudah pakai first_name dan last_name
    }
}
