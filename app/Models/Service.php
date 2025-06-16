<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'image'];

    public function pelanggan()
    {
        $services = Service::orderBy('id', 'asc')->take(3)->get();
        return view('pages.home', compact('services'));
    }
}
