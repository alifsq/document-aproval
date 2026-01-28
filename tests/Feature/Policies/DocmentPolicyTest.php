<?php

namespace Tests\Feature\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class DocmentPolicyTest extends TestCase
{
    use RefreshDatabase;
    public function testStaffCanCreate()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        self::assertTrue(Gate::allows('create', Document::class));
    }

    public function testManagerCanCreate()
    {
        $user = User::factory()->manager()->create();
        $this->actingAs($user);
        self::assertTrue(Gate::allows('create', Document::class));
    }

    public function testAuditorCantCreate()
    {
        $user = User::factory()->auditor()->create();
        $this->actingAs($user);
        self::assertFalse(Gate::allows('create', Document::class));
    }

    public function testStaffCanEdit()
    {
        $user = User::factory()->create();
        $document = Document::factory()->create([
            'created_by' => $user->id,
        ]);
        $this->actingAs($user);
        self::assertTrue(Gate::allows('update', $document));
    }

    public function testManagerCantEdit()
    {
        $user = User::factory()->manager()->create();
        $document = Document::factory()->create([
            'created_by' => $user->id,
        ]);
        $this->actingAs($user);
        self::assertFalse(Gate::allows('update', $document));
    }

    public function testStaffCanSubmit()
    {
        $user = User::factory()->create();
        $document = Document::factory()->create([
            'created_by' => $user->id,
        ]);
        $this->actingAs($user);
        self::assertTrue(Gate::allows('submit', $document));
    }

    public function testAuditorCantSubmit()
    {
        $user = User::factory()->manager()->create();
        $document = Document::factory()->create([
            'created_by' => $user->id,
        ]);
        $this->actingAs($user);
        self::assertFalse(Gate::allows('submit', $document));
    }

    public function testManagerCanApprove()
    {
        $user = User::factory()->manager()->create();
        $document = Document::factory()->submitted()->create([
            'created_by' => $user->id,
        ]);
        $this->actingAs($user);
        self::assertTrue(Gate::allows('approve', $document));
    }
    public function testAdminCantApprove()
    {
        $user = User::factory()->admin()->create();
        $document = Document::factory()->submitted()->create([
            'created_by' => $user->id,
        ]);
        $this->actingAs($user);
        self::assertFalse(Gate::allows('approve', $document));
    }

    public function testStaffCanView()
    {
        $user = User::factory()->create();
        $document = Document::factory()->create([
            'tenant_id' => $user->tenant_id,
            'created_by' => $user->id,
        ]);
        $this->actingAs($user);
        self::assertTrue(Gate::allows('view', $document));
    }

    public function testManagerCanViewAll()
    {
        $user = User::factory()->manager()->create();
        $document = Document::factory()->create([
            'tenant_id' => $user->tenant_id,
            'created_by' => $user->id,

        ]);

        $this->actingAs($user);
        self::assertTrue(Gate::allows('view', $document));
    }

    public function testAdminCanViewAll()
    {
        $user = User::factory()->manager()->create();
        $document = Document::factory()->create([
            'tenant_id' => $user->tenant_id,
            'created_by' => $user->id,
        ]);

        $this->actingAs($user);
        self::assertTrue(Gate::allows('view', $document));
    }

}
