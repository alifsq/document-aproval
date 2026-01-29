<?php

namespace Tests\Feature\Documents;

use App\Enums\DocumentStatus;
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
    public function testStaffViewDocument()
    {
        $user = User::factory()->create();
        $document = Document::factory()->create([
            'tenant_id' =>$user->id,
            'created_by' => $user->id,
        ]);
        $response = $this->actingAs($user, 'api')
            ->getJson('/api/documents/{$document->id}');
        $response->assertStatus(200);
    }
    public function testStaffCanCreateDocument()
    {
        // buat user dengan role staff
        $user = User::factory()->create();

        // data yang dikirim untuk create
        $data = [
            'title' => 'Test Document',
            'content' => 'Ini konten dokumen',
        ];

        // actingAs staff & panggil endpoint create
        $response = $this->actingAs($user, 'api')
            ->postJson('/api/documents', $data);

        // cek status response 200 / 201
        $response->assertStatus(201);

        // cek data tersimpan di database
        $this->assertDatabaseHas('documents', [
            'title' => 'Test Document',
            'content' => 'Ini konten dokumen',
            'created_by' => $user->id,
            'status' => DocumentStatus::DRAFT,
        ]);
    }

    public function testAdminCanCreateDocument()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user, 'api')
            ->postJson('/api/documents',[
                'title' => 'Test Document',
                'content' => 'Ini konten dokumen'
            ]);
        $response->assertStatus(403);
    }

}
