<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Tymon\JWTAuth\Facades\JWTAuth;

class MailController extends Controller
{
    public static function activeUser($token)
    {
        $token = JWTAuth::parseToken()->authenticate();
        if ($token) {
            $user = Cliente::findOrFail($token->id);
            $user->status = Cliente::STATUS_ATIVO;
            $user->save();
            return redirect(url('/conta/entrar'));
        }
        return response()->json(['error' => __('messages.token_or_user_invalid')]);
    }
}
