<?php
namespace App\Services;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class DocumentService
{

    public function create(User $user, $data)
    {
        Gate::authorize('create',Document::class);

        return Document::create([
        'tenant_id' => $user->tenant_id,
        'created_by' => $user->id,
        'title' => $data['title'],
        'content' => $data['content'],
        'status' => DocumentStatus::DRAFT,
    ]);
    }
}
