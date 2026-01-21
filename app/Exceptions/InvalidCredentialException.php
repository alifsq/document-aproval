<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InvalidCredentialException extends Exception
{
    protected $message = 'invalid credentials provided';

    public function getStatusCode()
    {
        return Response::HTTP_UNAUTHORIZED;
    }

    public function render(Request $request)
    {
        return response()->json([
            'message' => $this->message,
        ], $this->getStatusCode());
    }
}
