<?php

namespace Tests\Feature\Documents;

use App\Models\Document;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;
    public function testTenantInactive(){

        $tenant = Tenant::factory()->inactive()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        $this->actingAs($user)
            ->postJson('api/documents',[
                'title' => 'test',
                'content' => 'test',
                'file_path' => 'test',
            ]);
    }
}
