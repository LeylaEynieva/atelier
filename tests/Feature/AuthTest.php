<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Role::firstOrCreate(['name' => 'client'], ['description' => 'Клиент']);
    }

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test Client',
            'email' => 'test@atelier.ru',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        
        $this->assertAuthenticated();
        
        $this->assertDatabaseHas('users', [
            'email' => 'test@atelier.ru',
        ]);
    }

    public function test_user_can_login()
    {
        $clientRoleId = Role::where('name', 'client')->first()->id;
        
        $user = User::factory()->create([
            'email' => 'login@atelier.ru',
            'password' => bcrypt('password123'),
            'role_id' => $clientRoleId,
        ]);
        
        $response = $this->post('/login', [
            'email' => 'login@atelier.ru',
            'password' => 'password123',
        ]);
        
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_user_cannot_login_with_wrong_password()
    {
        $clientRoleId = Role::where('name', 'client')->first()->id;
        
        User::factory()->create([
            'email' => 'test@atelier.ru',
            'password' => bcrypt('correct'),
            'role_id' => $clientRoleId,
        ]);
        
        $response = $this->post('/login', [
            'email' => 'test@atelier.ru',
            'password' => 'wrong',
        ]);
        
        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }
}