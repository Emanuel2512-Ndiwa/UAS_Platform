<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

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
    public function items()
    {
        return $this->hasMany(LaundryItem::class, 'OrderID');
    }

}
