<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use App\Http\Resources\DocumentCollection;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{

    public function index(Request $request)
    {
        $document = Document::query()->where('tenant_id','=',$request->user()->id)->get();
        return new DocumentCollection($document);
    }

    public function store(DocumentRequest $request,$id)
    {
        $validated = $request->validated();
        $document = Document::query()->where('id',$id)->update($validated);

    }
}
