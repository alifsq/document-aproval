<?php
namespace App\Services;

use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    public function authenticate(array $credentials){
        $user = User::query()->where('email', $credentials['email'])->first();

        // if not user and not checked hash in password => abort
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            abort(401, 'invalid credential');
        }

        // if user not active or tenan not active => abort
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            abort(403, 'account dissabled');
        }

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
