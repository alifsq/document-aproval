<?php

namespace Tests\Feature\Auth;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function testLoginSuccess(){
        $tenant = Tenant::factory()->create();

        $user = User::factory()->for($tenant)->create();

        $response = $this->postJson('/api/auth/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'expires_at',
            ]);

    }
    public function testLoginWrongPassword(){
        $user = User::query()->first();

        $response = $this->postJson('api/auth/login',[
            'email'=>$user->email,
            'password' => 'password-wrong'
        ]);

        $response->assertStatus(401);
    }
    
}
