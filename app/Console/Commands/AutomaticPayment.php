<?php

namespace App\Console\Commands;

use DateTime;
use App\Models\Conta;
use App\Models\Empresa;
use App\Models\Pagamento;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\ValidationException;

class AutomaticPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automatic:payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily payment in automatic debit';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $contas = Conta::where('automatico', true)
            ->where('tipo', '<>', Conta::TIPO_RECEITA)
            ->where('estado', Conta::ESTADO_ATIVA)
            ->where('vencimento', '<=', Carbon::now())
            ->where('agrupamento_id', null)->get();
        foreach ($contas as $conta) {
            try {
                DB::transaction(function () use ($conta) {
                    $empresa = Empresa::find(1);
                    $pais = $empresa->pais;
                    $pagamento = new Pagamento([
                        'carteira_id' => $conta->carteira_id,
                        'moeda_id' => $pais->moeda_id,
                        'valor' => $conta->valor + $conta->acrescimo,
                        'lancado' => $conta->valor,
                        'estado' => Pagamento::ESTADO_PAGO,
                        'conta_id' => $conta->id,
                        'data_pagamento' => Carbon::now(),
                    ]);
                    $pagamento->save();
                    $conta->estado = Conta::ESTADO_PAGA;
                    $conta->consolidado += $pagamento->valor;
                    $conta->save();
                    $frequencia = $conta->frequencia;
                    if ($conta->modo == Conta::MODO_MENSAL) {
                        $data = new DateTime($conta->vencimento);
                        $vencimento = $data->modify("+ $frequencia month");
                        $vencimento = date_format($vencimento, 'Y-m-d H:i:s');
                    } else {
                        $data = new DateTime($conta->vencimento);
                        $vencimento = $data->modify("+ $frequencia day");
                        $vencimento = date_format($vencimento, 'Y-m-d H:i:s');
                    }
                    $new_conta = $conta->replicate();
                    $new_conta->fill([
                        'parcelas' => $conta->parcelas + 1,
                        'numero_parcela' => $conta->numero_parcela + 1,
                        'consolidado' => 0.00,
                        'acrescimo' => 0.00,
                        'vencimento' => $vencimento,
                        'estado' => Conta::ESTADO_ATIVA,
                    ]);
                    $new_conta->save();
                });
            } catch (\Throwable $th) {
                Log::error('AutomaticPayment: ' . $th->getMessage());
            }
        }
    }
}
