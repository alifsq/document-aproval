<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InactiveTenantException extends Exception
{
    protected $message = 'Tenant Inactive';
    public function getStatusCode()
    {
        return Response::HTTP_FORBIDDEN;
    }
    public function render(Request $request)
    {
        return response()->json([
            'message' => $this->message
        ], $this->getStatusCode());
    }
}
