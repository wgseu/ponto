<?php

namespace App\Http\Controllers;

use App\Models\Ponto;
use App\Models\Colaborador;
use Illuminate\Http\Request;

class PontoController extends Controller
{
    public function store(Request $request)
    {
        $colaborador = Colaborador::find($request->colaborador_id)->toArray();
        if (!$colaborador) {
            return ['colaborador' => 'Colaborador não encontrado'];
        }
        if (!$colaborador['ativo']) {
            return ['ativo' => 'Conta não está ativa'];
        }
        if ($colaborador['status'] != Colaborador::STATUS_TRABALHO) {
            return ['status' => 'Impedido de bater ponto'];
        }
        return Ponto::create([
            'colaborador_id' => $request->colaborador_id,
            'data_ponto' => \Carbon\Carbon::now(),
            'latitude' => $request->latitude ?? null,
            'longitude' => $request->longitude ?? null,
            'anexo_url' => $request->aneno_url ?? null,
            'descricao' => $request->descricao ?? null,
        ]);
    }
}
