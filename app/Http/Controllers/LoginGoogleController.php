<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Exceptions\AuthorizationException;
use Google_Client;
use Illuminate\Http\Request;

class LoginGoogleController extends Controller
{
    /**
     * Google Login
     *
     * @param Request $request
     */
    public function login(Request $request)
    {
        $client = new Google_Client(['client_id' => env('GOOGLE_APP_ID')]);
        $payload = $client->verifyIdToken($request->token);

        if ($payload) {
            $cliente = Cliente::where('email', $payload['email'])->first();
            if (!$cliente) {
                $cliente = new Cliente();
                $cliente->nome = $payload['name'];
                $cliente->email = $payload['email'];
                $cliente->status = Cliente::STATUS_ATIVO;
                $cliente->save();
            }
            $cliente = Cliente::where('email', $payload['email'])
                ->where('status', Cliente::STATUS_ATIVO)
                ->first();
            if ($cliente) {
                return [
                    'refresh_token' => $cliente->createRefreshToken(),
                    'token_type'   => 'bearer',
                    'cliente'      => $cliente->toArray(),
                ];
            }
        }
        
        throw new AuthorizationException(__('messages.acess_invalid'));
    }
}
