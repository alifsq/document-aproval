<?php

namespace Tests\Feature\Auth;

use App\Models\ApiToken;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    public function testLoginSuccess()
    {
        $tenant = Tenant::factory()->create();

        $user = User::factory()->for($tenant)->create();

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'expires_at',
            ]);

    }
    public function testLoginWrongPassword()
    {
        $user = User::factory()->create();

        $response = $this->postJson('api/auth/login', [
            'email' => $user->email,
            'password' => 'password-wrong'
        ]);

        $response->assertStatus(401);
    }

    public function testLoginUserInactive()
    {
        $user = User::factory()->inactive()->create();

        $response = $this->postJson('api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertStatus(403);
    }

    public function testLoginTenantInactive()
    {
        $tenant = Tenant::factory()->inactive()->create();
        $user = User::factory()->for($tenant)->create();

        $response = $this->postJson('api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertStatus(403);
    }

    public function testTokenExpired()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->for($tenant)->create();

        $token = ApiToken::factory()->expired()->create([
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'token_hash' => hash('sha256', 'expired-token')
        ]);
        // dd(
        //     [
        //         'factory' => $token->getAttributes(),
        //         'fresh' => $token->fresh()->getAttributes(),
        //     ]
        // );

        $response = $this->withHeaders([
            'Authorization' => 'Bearer expired-token',
        ])->getJson('api/documents');

        $response->assertStatus(401)->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testRevokeToken(){
            
    }



}
