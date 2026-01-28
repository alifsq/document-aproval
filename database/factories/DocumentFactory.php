<?php

namespace Database\Factories;

use App\Enums\DocumentStatus;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $table = 'documents';
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'created_by'=> null,
            'title' => fake()->sentence(),
            'content' => fake()->paragraph(),
            'status' => DocumentStatus::DRAFT,
        ];
    }

    public function submitted()
    {
        return $this->state(fn() => [
            'status' => DocumentStatus::SUBMITTED,
            'submitted_at' => now(),
        ]);
    }

    public function approved(){
        return $this->state(fn() => [
            'status' => DocumentStatus::APPROVED,
            'approved_at' => now(),
        ]);
    }

    public function rejected(){
        return $this->state(fn() => [
            'status' => DocumentStatus::REJECTED,
            'rejected_at' => now(),
        ]);
    }
}
