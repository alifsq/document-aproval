<?php

// Token Handler
namespace App\Providers\Auth;

use App\Models\ApiToken;
use Illuminate\Http\Request;

class TokenHandler {
    public function authenticate(Request $request){
        $header = $request->header('Athorization');

        if(!$header ||str_starts_with($header,'Bearer')){
            return null;
        }
        $token = substr($header,7);
        $tokenHash = hash('256',$token);

        $apitoken = ApiToken::query()
            ->where('token_hash',$tokenHash)
            ->whereNull('revoked_at')
            ->where('expires_at','>',now());

        return $apitoken->user();
    }
}
