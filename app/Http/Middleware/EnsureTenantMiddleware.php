<?php

namespace App\Http\Middleware;

use App\Exceptions\InactiveTenantException;
use App\Exceptions\InActiveUserException;
use App\Exceptions\InvalidCredentialException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Cek tenant
        if (!$user || !$user->tenant) {
            return response()->json(['message' => 'Tenant not found'], 403);
        }

        // Cek tenant active
        if (!$user->tenant->is_active) {
            return response()->json(['message' => 'Tenant inactive'], 403);
        }

        return $next($request);
    }
}
