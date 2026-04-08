<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'stock',
        'unit',
        'status',
    ];

    /**
     * Auto-update status berdasarkan stock saat disimpan
     */
    protected static function booted(): void
    {
        static::saving(function (Inventory $inventory) {
            if ($inventory->stock <= 0) {
                $inventory->status = 'empty';
            } elseif ($inventory->stock <= 2) {
                $inventory->status = 'low_stock';
            } else {
                $inventory->status = 'safe';
            }
        });
    }
}
