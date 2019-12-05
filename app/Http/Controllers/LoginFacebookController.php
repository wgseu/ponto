<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Exceptions\AuthorizationException;
use Illuminate\Http\Request;

class LoginFacebookController extends Controller
{
    /**
     * Facebook Login
     *
     * @param Request $request
     */
    public function loginFacebook(Request $request)
    {
        $email = $request->response['email'];
        $nome = $request->response['name'];
        $cliente = Cliente::where('email', $email)->first();
        if (!$cliente) {
            $cliente = new Cliente();
            $cliente->nome = $nome;
            $cliente->email = $email;
            $cliente->status = Cliente::STATUS_ATIVO;
            $cliente->save();
        }
        $cliente = Cliente::where('email', $email)->where('status', Cliente::STATUS_ATIVO)->first();
        if ($cliente) {
            return [
                'refresh_token' => $cliente->createRefreshToken(),
                'token_type'   => 'bearer',
                'cliente'      => $cliente->toArray(),
            ];
        }
        throw new AuthorizationException(__('messages.acess_invalid'));
    }
}
