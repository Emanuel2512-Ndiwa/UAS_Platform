<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'pickup_address', 'delivery_address', 'transaction_date', 'total_amount', 'service_status', 'pickup_time', 'delivery_time', 'notes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}