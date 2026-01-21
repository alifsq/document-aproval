<?php

namespace Database\Factories;

use App\Models\ApiToken;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApiToken>
 */
class ApiTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ApiToken::class;
    public function definition(): array
    {
        return [
            'user_id' => null,
            'tenant_id' => null,
            'token_hash' => hash('sha256', Str::random(64)),
            'name' => 'api-token',
            'expires_at' => now()->addHours(8),
            'revoked_at' => null,
        ];
    }

    public function expired()
    {
        return $this->state(fn() => [
            'expires_at' => now()->subMinute(),
        ]);
    }

    public function revoked()
    {
        return $this->state(fn() => [
            'revoked_at' => now(),
        ]);
    }
}
