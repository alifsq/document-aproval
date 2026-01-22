<?php

namespace App\Guards;

use App\Models\ApiToken;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class ApiTokenGuard implements Guard
{
    protected $user;
    protected $provider;
    protected $request;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        $token = $this->request->bearerToken();
        if (!$token) {
            return null;
        }

        $hash = hash('sha256', $token);

        $tokenModel = ApiToken::query()
            ->where('token_hash', $hash)
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->first();

        if (!$tokenModel) {
            return null;
        }

        return $this->user = $this->provider->retrieveById($tokenModel->user_id);
    }

    public function check()
    {
        return $this->user() !== null;
    }

    public function guest()
    {
        return $this->user() === null;
    }

    public function hasUser()
    {
        return $this->user() !== null;
    }

    public function id()
    {
        $user = $this->user();
        return $user ? $user->getAuthIdentifier() : null;
    }

    public function setUser(\Illuminate\Contracts\Auth\Authenticatable $user)
    {
        $this->user = $user;
        return $this;
    }

    public function validate(array $credentials = [])
    {
        return false;
    }
}
