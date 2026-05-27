<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role_id',
        'phone', 'address', 'position', 'hired_at', 'salary',
        'birth_date', 'notes'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function ordersAsClient()
    {
        return $this->hasMany(Order::class, 'client_id');
    }

    public function ordersAsEmployee()
    {
        return $this->hasMany(Order::class, 'employee_id');
    }

    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }

    public function isEmployee()
    {
        return $this->role->name === 'employee';
    }

    public function isClient()
    {
        return $this->role->name === 'client';
    }
}