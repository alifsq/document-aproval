<?php
namespace App\Services;

use App\Exceptions\InactiveTenantException;
use App\Exceptions\InActiveUserException;
use App\Exceptions\InvalidCredentialException;
use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AuthService
{
    public function authenticate(string $email, string $password)
    {

        $user = User::with('tenant')->where('email', $email)->first();


        if (!$user || !Hash::check($password, $user->password)) {
            throw new InvalidCredentialException();
        }

        if (!$user->is_active) {
            throw new InActiveUserException();
        }

        $tenant = $user->tenant;
        if (!$tenant->is_active) {
            throw new InactiveTenantException();
        }

        // Revoke the old token
        ApiToken::query()->where('user_id', '=', $user->id)
            ->whereNull('revoked_at')
            ->update([
                'revoked_at' => now()
            ]);


        $plainToken = Str::random(64);

        ApiToken::create([
            'user_id' => $user->id,
            'tenant_id' => $user->tenant_id,
            'name' => 'API Token',
            'token_hash' => hash('sha256', $plainToken),
            'expires_at' => now()->addHours(8),
        ]);

        return [
            'token' => $plainToken,
            'expires_at' => now()->addHours(8),
        ];
    }
}
