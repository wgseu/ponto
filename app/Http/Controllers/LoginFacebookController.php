<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Exceptions\AuthorizationException;
use Facebook\Facebook;
use Illuminate\Http\Request;

class LoginFacebookController extends Controller
{
    /**
     * Facebook Login
     *
     * @param Request $request
     */
    public function login(Request $request)
    {
        $facebook = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_SECRET'),
            'default_graph_version' => env('FACEBOOK_VERSION'),
        ]);
        $fb_response = $facebook->get('/me?fields=id,name,email', $request->token);
        $payload = $fb_response->getGraphUser();
        if ($payload) {
            $cliente = Cliente::where('email', $payload['email'])->first();
            if (!$cliente) {
                $imagem_url = 'https://graph.facebook.com/' . $payload['id'] . '/picture?type=large';
                $data = file_get_contents($imagem_url);
                $cliente = new Cliente();
                $cliente->nome = $payload['name'];
                $cliente->email = $payload['email'];
                if ($data !== false) {
                    $cliente->imagem = base64_encode($data);
                }
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
                    'user'      => $cliente->toArray(),
                ];
            }
        }
        
        throw new AuthorizationException(__('messages.acess_invalid'));
    }
}
