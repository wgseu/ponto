<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;

class EmpresaController extends Controller
{ 
   public function store(Request $request)
    {
        if (is_null($request->fantasia)) {
            return ['fantasia' => 'Informe nome fantasia'];
        }
        if (is_null($request->email)) {
            return ['email' => 'Informe o email'];
        }
        if (is_null($request->senha)) {
            return ['senha' => 'Informe uma senha'];
        }
        if (is_null($request->fone1)) {
            return ['fone1' => 'Informe número de contato'];
        }
        return Empresa::create([
            'fantasia' => $request->fantasia,
            'razao_social' => $request->razao_social ?? null,
            'email' => $request->email,
            'senha' => $request->senha,
            'data_criacao' => \Carbon\Carbon::now(),
            'cnpj' => $request->cnpj ?? null,
            'fone1' => $request->fone1,
            'fone2' => $request->fone2 ?? null,
            'imagem_url' => $request->imagem_url ?? null,
        ]);
    }
}
