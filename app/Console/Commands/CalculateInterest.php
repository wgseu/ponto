<?php

namespace App\Console\Commands;

use App\Models\Conta;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Exceptions\SafeValidationException;

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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

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
        try {
            DB::transaction(function () use ($contas) {
                foreach ($contas as $conta) {
                    $vencimento = strtotime("tomorrow", strtotime($conta->vencimento)) - 1;
                    $dias = floor((time() - $vencimento) / (60 * 60 * 24));
                    $modo = $conta->formula == Conta::FORMULA_SIMPLES
                    ? $conta->valor : $conta->acrescimo + $conta->valor;
                    $acrecismo = ($modo - $conta->consolidado) * $conta->juros * $dias;
                    $conta->valor += $acrecismo;
                    $conta->acrescimo += $acrecismo;
                    $conta->data_calculo = Carbon::now();
                    $conta->save();
                }
            });
        } catch (\Throwable $th) {
            throw SafeValidationException::withMessages(['error' => $th->getMessage()]);
        }
    }
}
