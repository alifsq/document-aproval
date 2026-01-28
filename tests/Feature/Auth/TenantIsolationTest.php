<?php

namespace Tests\Feature;

use App\Models\ApiToken;
use App\Models\Document;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
public function testTenantCanAccessOwnResources()
{
    $tenant = Tenant::factory()->create();
    $user = User::factory()->for($tenant)->create();
    $token = ApiToken::factory()->for($user)->create();

    $document = Document::factory()->for($tenant)->create();

    $response = $this->withHeader(
        'Authorization',
        'Bearer '.$token->plain_token
    )->getJson("/api/documents/{$document->id}");

    $response->assertStatus(200);
}
}
