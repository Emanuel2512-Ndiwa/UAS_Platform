<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'kurir_id',
        'order_date',
        'pickup_date',
        'deliver_date',
        'status',
        'total_price',
        'payment_status',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kurir()
    {
        return $this->belongsTo(User::class, 'kurir_id');
    }
}
