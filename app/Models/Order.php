<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'client_id', 'employee_id', 'order_status_id',
        'order_date', 'deadline', 'completed_at',
        'description', 'total_price'
    ];

    protected $casts = [
        'order_date' => 'date',
        'deadline' => 'date',
        'completed_at' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'order_services')
                    ->withPivot('quantity', 'price', 'total')
                    ->withTimestamps();
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'order_materials')
                    ->withPivot('quantity', 'price', 'total')
                    ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function recalcTotal()
    {
        $servicesTotal = $this->services->sum(fn($s) => $s->pivot->total);
        $materialsTotal = $this->materials->sum(fn($m) => $m->pivot->total);
        $this->update(['total_price' => $servicesTotal + $materialsTotal]);
    }
}