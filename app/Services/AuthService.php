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


        // Di dalam AuthService.php
        $plainToken = Str::random(64);
        $hashToStore = hash('sha256', $plainToken);

        // DEBUG: Intip apa yang akan disimpan
        // dd(['mentah' => $plainToken, 'hash_siap_simpan' => $hashToStore]);

        ApiToken::create([
            'user_id' => $user->id,
            'tenant_id' => $user->tenant_id,
            'name' => 'API Token',
            'token_hash' => $hashToStore, // Simpan variabel yang sudah di-hash
            'expires_at' => now()->addHours(8),
        ]);

        return [
            'token' => $plainToken, // Kirim yang MENTAH ke Postman
            'expires_at' => now()->addHours(8),
        ];
    }
}
