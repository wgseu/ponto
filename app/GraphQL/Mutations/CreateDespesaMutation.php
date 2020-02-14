<?php

/**
 * Copyright 2014 da GrandChef - GrandChef Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Restaurantes e Afins.
 * O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
 * DISPOSIÇÕES GERAIS
 * O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
 * ou outros avisos ou restrições de propriedade do GrandChef.
 *
 * O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
 * ou descompilação do GrandChef.
 *
 * PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA
 *
 * GrandChef é a especialidade do desenvolvedor e seus
 * licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
 * de leis de propriedade.
 *
 * O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
 * direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
 *
 * @author Equipe GrandChef <desenvolvimento@grandchef.com.br>
 */

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Classificacao;
use App\Models\Conta;
use App\Models\Forma;
use App\Models\Pagamento;
use App\Models\Saldo;
use Exception;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Carbon;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CreateDespesaMutation extends Mutation
{
    protected $attributes = [
        'name' => 'CreateDespesa',
    ];

    public function authorize(array $args): bool
    {
        return Auth::check() && Auth::user()->can('conta:create');
    }

    public function type(): Type
    {
        return GraphQL::type('Conta');
    }

    public function args(): array
    {
        return [
            'input' => ['type' => Type::nonNull(GraphQL::type('DespesaInput'))],
        ];
    }

    public function resolve($root, $args)
    {
        try {
            $input = $args['input'];
            $prestador = Auth::user()->prestador;
            $conta = new Conta();
            $conta->fill($input);
            $conta->data_emissao = Carbon::now();
            $conta->funcionario_id = $prestador ? $prestador->id : null;
            $conta->tipo = Conta::TIPO_DESPESA;
            $conta->estado = Conta::ESTADO_PAGA;
            DB::transaction(function () use ($input, $prestador, $conta) {
                $valor = $input['valor'];
                $classificacao = Classificacao::firstOrFail();
                $conta->classificacao_id = $classificacao->id;
                $conta->vencimento = Carbon::now();
                $conta->consolidado = $input['valor'];
                $conta->save();
                $saldo = Saldo::where('carteira_id', $input['carteira_id'])
                    ->where('moeda_id', app('currency')->id)->sum('valor');
                $forma = Forma::where('tipo', Forma::TIPO_DINHEIRO)
                    ->where('ativa', true)->firstOrFail();
                if ($saldo >= ($valor * -1)) {
                    $pagamento = new Pagamento([
                        'moeda_id' => app('currency')->id,
                        'carteira_id' => $input['carteira_id'],
                        'forma_id' => $forma->id,
                        'valor' => $valor,
                        'estado' => Pagamento::ESTADO_PAGO,
                        'lancado' => $valor,
                        'detalhes' => __('messages.register_expense', ['value' => $conta->id]),
                    ]);
                    $pagamento->conta_id = $conta->id;
                    $pagamento->funcionario_id = $prestador->id;
                    $pagamento->save();
                    return $conta;
                } else {
                    throw new Exception('saldo Insuficiente');
                }
            });
        } catch (\Throwable $th) {
            $conta->clean(new Conta());
            throw $th;
        }
        return $conta;
    }
}
