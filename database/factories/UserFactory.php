<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        // Получаем ID роли client (или создаём, если нет)
        $clientRoleId = Role::firstOrCreate(['name' => 'client'], ['description' => 'Клиент'])->id;
        
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'role_id' => $clientRoleId,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
        ];
    }
}