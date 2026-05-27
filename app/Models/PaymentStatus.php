<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    protected $table = 'payment_statuses';
    protected $fillable = ['name', 'color'];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_status_id');
    }
}