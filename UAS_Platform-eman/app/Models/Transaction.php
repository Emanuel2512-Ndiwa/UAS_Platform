<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'pickup_address',
        'delivery_address',
        'transaction_date',
        'total_amount',
        'payment_status',
        'service_status',
        'pickup_time',
        'delivery_time',
        'notes',
        'service_id',
        'amount',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function kurir()
    {
        return $this->belongsTo(User::class, 'kurir_id');
    }
}