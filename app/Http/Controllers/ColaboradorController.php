<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colaborador;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class ColaboradorController extends Controller
{
    public function store(Request $request)
    {
        if (is_null($request->empresa_id)) {
            return ['empresa' => 'Informe a empresa desse colaborador'];
        }
        if (is_null($request->nome)) {
            return ['nome' => 'Informe o nome'];
        }
        if (is_null($request->sobrenome)) {
            return ['sobrenome' => 'Informe o sobrenome'];
        }
        if (is_null($request->email)) {
            return ['email' => 'Informe o email'];
        }
        if (is_null($request->carga_horaria)) {
            return ['carga_horaria' => 'Informe a carga horária do colaborador'];
        }
        if (is_null($request->status)) {
            return ['status' => 'Informe o status'];
        }
        if (is_null($request->ativo)) {
            return ['ativo' => 'Informe se o colaborador está ativo ou não'];
        }
        return Colaborador::create([
            'empresa_id' => $request->empresa_id,
            'nome' => $request->nome,
            'sobrenome' => $request->sobrenome,
            'email' => $request->email,
            'senha' => $request->senha ?? null,
            'carga_horaria' => $request->carga_horaria,
            'status' => $request->status,
            'acumulado' => $request->acumulado ?? null,
            'ativo' => $request->ativo,
        ]);
    }

    public function resetPass(Request $request)
    {
        $colaborador = Colaborador::find($request->colaborador_id);
        $colaborador->update([
            'senha' => $request->senha
        ]);
        if (Hash::check($request->senha, $colaborador->senha)) {
            $customClaims = [
                'iss' => null,
                'uid' => $colaborador->id,
                'sub' => null,
                'typ' => 'reset',
                'exp' => Carbon::now('UTC')->addMinutes(30 * 24 * 60)->getTimestamp(),
            ];
            $payload = JWTFactory::claims($customClaims)->make(true);
            return JWTAuth::encode($payload)->get();
        }
    }
}
