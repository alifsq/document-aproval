<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\AuthResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function login(LoginRequest $request)
    {
        $token = $this->authService->authenticate($request->validated());

        return response()->json($token);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken;

        $token->update([
            'revoked_at' => now()
        ]);

        return response()->noContent();
    }
}
