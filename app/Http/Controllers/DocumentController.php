<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use App\Http\Resources\DocumentCollection;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DocumentController extends Controller
{
    protected DocumentService $documentService;

    public function __construct(DocumentService $documentService) {
        $this->documentService = $documentService;
    }

    public function index(Request $request)
    {
        Gate::authorize('view',Document::class);
        $document = Document::query()->where('tenant_id','=',$request->user()->id)->get();
        return new DocumentCollection($document);
    }

    public function store(DocumentRequest $request)
    {
        $validated = $request->validated();
        $document = $this->documentService->create($request->user(),$validated);

        return new DocumentResource($document);
    }
}
