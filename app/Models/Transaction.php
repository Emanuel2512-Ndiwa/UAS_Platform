<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'customer_name_offline',
        'service_id',
        'order_type',
        'total_amount',
        'midtrans_snap_token',
        'midtrans_id',
        'payment_status',
        'payment_method',
        'service_status',
        'kurir_id',
        'karyawan_id',
        'distance',
        'eta_minutes',
        'pickup_address',
        'delivery_address',
        'quantity',
        'transaction_date',
        'pickup_time',
        'delivery_time',
        'notes',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'total_amount'     => 'decimal:2',
        'distance'         => 'decimal:2',
    ];

    /**
     * Auto-generate order_id unik sebelum insert
     */
    protected static function booted(): void
    {
        static::creating(function (Transaction $transaction) {
            if (empty($transaction->order_id)) {
                $transaction->order_id = 'LAUNDRY-' . strtoupper(Str::random(5)) . '-' . now()->format('Hm');
            }
        });
    }

    // Relasi ke pelanggan
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke service/layanan
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // Relasi ke kurir
    public function kurir()
    {
        return $this->belongsTo(User::class, 'kurir_id');
    }

    // Relasi ke karyawan yang mengerjakan
    public function karyawan()
    {
        return $this->belongsTo(User::class, 'karyawan_id');
    }
}