<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ponto;

class PontoController extends Controller
{
    public function store(Request $request)
    {
        return Ponto::create([
            'colaborador_id' => $request->colaborador_id,
            'data_ponto' => \Carbon\Carbon::now(),
        ]);
    }
}
