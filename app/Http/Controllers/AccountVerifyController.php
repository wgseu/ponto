<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Payload;
use Tymon\JWTAuth\Token;

class AccountVerifyController extends Controller
{
    public function activateByToken($token)
    {
        /** @var Payload $payload */
        $payload = JWTAuth::decode(new Token($token));
        if (($payload['typ'] ?? null) == 'check') {
            $user = Cliente::findOrFail($payload['uid'] ?? null);
            $user->status = Cliente::STATUS_ATIVO;
            $user->save();
            return redirect(url('/authenticate'));
        }
        return response()->json(['error' => __('messages.token_or_user_invalid')], 401);
    }
}
