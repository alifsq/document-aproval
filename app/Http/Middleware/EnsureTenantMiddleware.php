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
        $user = auth()->user(); // Diambil dari Custom Guard kamu

        // Jika user tidak ada (biasanya sudah dicek di auth:api), tolak
        if (!$user) {
            throw new InvalidCredentialException();

        }

        // Poin 2.2: Tambahan proteksi jika tenant non-aktif (Double Check)
        if (!$user->tenant || !$user->tenant->is_active) {
            throw new InactiveTenantException();
        }

        // Poin 2.1: Tenant Mismatch (Logic Inti)
        // Kita asumsikan routenya punya parameter, misal: /api/projects/{project}
        $resource = $request->route()->parameter('id');

        if ($resource && isset($resource->tenant_id)) {
            if ($resource->tenant_id !== $user->tenant_id) {
                // Jangan pakai 404 jika ingin sembunyikan keberadaan data (Security by Obscurity)
                // Tapi standar Poin 2.1 kamu minta 403 Forbidden
                abort(403, 'Unauthorized access to this resource.');
            }
        }


        return $next($request);
    }
}
