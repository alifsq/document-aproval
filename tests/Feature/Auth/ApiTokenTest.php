<?php

namespace Tests\Feature\Auth;

use App\Models\ApiToken;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTokenTest extends TestCase
{
    use RefreshDatabase;

    public function testTokenValid()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->for($tenant)->create();

        $plainText = 'test-token-succes';
        $token = ApiToken::factory()->create([
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'token_hash'=>hash('sha256',$plainText),
        ]);

        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $plainText
        )->getJson('/api/documents');

        $response->assertStatus(200);
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

        $response = $this->withHeader(
            'Authorization',
            'Bearer    ' . $token->plain_token
        )->getJson('api/documents');

        $response->assertStatus(401);
    }
}
