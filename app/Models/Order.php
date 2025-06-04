<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders'; //sesuai nama tabel di database

    public $timestamps = false;

    protected $fillable = [
        'UserID',
        'KurirID',
        'PickupTime',
        'DeliveryTime',
        'Status',
        'CreatedAt',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }

    public function kurir()
    {
        return $this->belongsTo(User::class, 'KurirID');
    }
    
}
