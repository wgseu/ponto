<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTFactory;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        if (is_null($request->email)) {
            return ['email' => 'Informe email para logar'];
        }
        $colaborador = Colaborador::where('email', $request->email)->get()->toArray();
        if (!$colaborador) {
            return ['colaborador' => 'Colaborador não cadastrado'];
        }
        if ($colaborador[0]['senha'] == Colaborador::SENHA_TEMPORARIA) {
            return  [
                        'senha' => 'Crie uma senha para continuar',
                        'colaborador_id' => $colaborador[0]['id']
                    ];
        } else {
            if (is_null($request->senha)) {
                return ['senha' => 'Informe a senha'];
            }
        }
        if (Hash::check($request->senha, $colaborador[0]['senha'])) {
            $customClaims = [
                'iss' => null,
                'uid' => $colaborador[0]['id'],
                'sub' => null,
                'typ' => 'check',
                'exp' => Carbon::now('UTC')->addMinutes(30 * 24 * 60)->getTimestamp(),
            ];
            $payload = JWTFactory::claims($customClaims)->make(true);
            return JWTAuth::encode($payload)->get();
        }
        return ['login' => 'Email ou senha incorretos'];
    }
}
