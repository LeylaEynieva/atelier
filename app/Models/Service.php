<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'price'];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_services')
                    ->withPivot('quantity', 'price', 'total')
                    ->withTimestamps();
    }
}