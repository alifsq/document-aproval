<?php

namespace App\Providers\Auth;

use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Http\Request;

class TokenHandler
{
    public function authenticate(Request $request): ?User
    {
        $header = $request->header('Authorization');

        logger()->info('TOKEN HANDLER HIT', [
            'authorization' => $header,
        ]);

        if (! $header || ! str_starts_with($header, 'Bearer ')) {
            return null;
        }

        $plainToken = substr($header, 7);
        $tokenHash  = hash('sha256', $plainToken);

        $apiToken = ApiToken::query()
            ->where('token_hash', $tokenHash)
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->first();

        logger()->info('TOKEN DEBUG', [
            'plain' => $plainToken,
            'hash' => $tokenHash,
            'found' => (bool) $apiToken,
            'expires_at' => optional($apiToken)->expires_at,
            'revoked_at' => optional($apiToken)->revoked_at,
        ]);

        if (! $apiToken) {
            return null;
        }

        $user = $apiToken->user;

        // 🔑 WAJIB agar logout bisa revoke token
        $user->setRelation('currentAccessToken', $apiToken);

        return $user;
    }
}
