<?php

namespace Tests\Feature;

use Database\Seeders\TenantSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function testLogin(): void
    {
        $this->seed([TenantSeeder::class, UserSeeder::class]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@mail.com',
            'password' => 'test1',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'expires_at',
            ]);

        // token disimpan hashed (bukan plaintext)
        $this->assertDatabaseCount('api_tokens', 1);

        $this->assertDatabaseMissing('api_tokens', [
            'token_hash' => $response->json('token'),
        ]);
    }

    public function testLogout(): void
    {
        $this->seed([TenantSeeder::class, UserSeeder::class]);

        // login dulu (dapat plaintext token)
        $login = $this->postJson('/api/auth/login', [
            'email' => 'test@mail.com',
            'password' => 'test1',
        ]);

        $login->assertStatus(200);
        $token = $login->json('token');

        // logout
        $response = $this->postJson('/api/auth/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);


        $response->assertStatus(204);

        // token harus revoked
        $this->assertDatabaseMissing('api_tokens', [
            'revoked_at' => null,
        ]);
    }

}
