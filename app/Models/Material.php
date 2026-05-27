<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['name', 'unit', 'price_per_unit', 'stock_quantity'];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_materials')
                    ->withPivot('quantity', 'price', 'total')
                    ->withTimestamps();
    }
}