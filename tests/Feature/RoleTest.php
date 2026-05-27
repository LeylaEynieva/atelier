<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin'], ['description' => 'Администратор']);
        Role::firstOrCreate(['name' => 'client'], ['description' => 'Клиент']);
    }

    public function test_admin_can_access_admin_dashboard()
    {
        $adminRoleId = Role::where('name', 'admin')->first()->id;
        
        $admin = User::factory()->create([
            'role_id' => $adminRoleId,
        ]);
        
        $this->actingAs($admin);
        
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_client_cannot_access_admin_dashboard()
    {
        $clientRoleId = Role::where('name', 'client')->first()->id;
        
        $client = User::factory()->create([
            'role_id' => $clientRoleId,
        ]);
        
        $this->actingAs($client);
        
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/');
    }
}