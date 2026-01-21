<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'role' => UserRole::STAFF,
            'is_active' => true,
        ];
    }
    /**
     * Indicate that the model's email address should be unverified.
     */
    public function admin()
    {
        return $this->state(fn() => [
            'role' => UserRole::ADMIN,
        ]);
    }

    public function manager()
    {
        return $this->state(fn() => [
            'role' => UserRole::MANAGER,
        ]);
    }
    public function auditor()
    {
        return $this->state(fn() => [
            'role' => UserRole::AUDITOR,
        ]);
    }

    public function inactive()
    {
        return $this->state(fn() => [
            'is_active' => false,
        ]);
    }
}
