<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{

    public function index(Request $request)
    {
        $document = Document::where('tenant_id', $request->user()->tenant_id)->get();
        return new DocumentResource($document);
    }

    public function store(DocumentRequest $request){
        $data = $request->validated();

        $data['tenant_id']=$request->user()->tenant_id;
        $data['created_by']=$request->user()->id;

        //

    }
}
