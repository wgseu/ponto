<?php

namespace App\Console\Commands;

use App\Models\Conta;
use App\Util\Currency;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CalculateInterest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:interest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'calculate interest on bills';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $diaAnterior = Carbon::yesterday();
        $contas = Conta::where('vencimento', '<', Carbon::now())
            ->where('estado', Conta::ESTADO_ATIVA)
            ->where('tipo', Conta::TIPO_RECEITA)
            ->where('data_calculo', '<', $diaAnterior)
            ->orWhereNull('data_calculo')->get();
        foreach ($contas as $conta) {
            try {
                DB::transaction(function () use ($conta) {
                    $data_calculo = strtotime($conta->data_calculo);
                    $vencimento = strtotime("tomorrow", strtotime($conta->vencimento)) - 1;
                    $data = !is_null($data_calculo) ? $data_calculo : $vencimento;
                    $dias = floor((time() - $data) / (60 * 60 * 24));
                    $modo = $conta->formula == Conta::FORMULA_SIMPLES
                        ? $conta->valor : $conta->acrescimo + $conta->valor;
                    $acrecismo = ($modo - $conta->consolidado) * $conta->juros * $dias;
                    $conta->acrescimo += Currency::round($acrecismo);
                    $conta->data_calculo = Carbon::yesterday();
                    $conta->save();
                });
            } catch (\Throwable $th) {
                Log::error('CalculateInterest: ' . $th->getMessage());
            }
        }
    }
}
