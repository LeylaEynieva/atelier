<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Создаём роль client для тестов
        Role::firstOrCreate(['name' => 'client'], ['description' => 'Клиент']);
    }

    public function test_guest_cannot_access_orders_page()
    {
        $response = $this->get('/orders');
        $response->assertRedirect('/login');
    }

    public function test_client_can_access_orders_page()
    {
        $clientRoleId = Role::where('name', 'client')->first()->id;
        
        $client = User::factory()->create([
            'role_id' => $clientRoleId,
        ]);
        
        $this->actingAs($client);
        
        $response = $this->get('/orders');
        $response->assertStatus(200);
    }
}