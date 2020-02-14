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

use App\Models\Caixa;
use App\Models\Carteira;
use App\Models\Forma;
use App\Models\Movimentacao;
use App\Models\Pagamento;
use App\Models\Saldo;
use Exception;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CreateTransferenciaMutation extends Mutation
{
    protected $attributes = [
        'name' => 'CreateTransferencia',
    ];

    public function authorize(array $args): bool
    {
        return Auth::check() && Auth::user()->can('pagamento:create');
    }

    public function type(): Type
    {
        return GraphQL::type('Pagamento');
    }

    public function args(): array
    {
        return [
            'input' => ['type' => Type::nonNull(GraphQL::type('TransferenciaInput'))],
        ];
    }

    public function resolve($root, $args)
    {
        $destino = DB::transaction(function () use ($args) {
            $input = $args['input'];
            $valor = $input['valor'];
            $origem_id = $input['origem_id'];
            $prestador = Auth::user()->prestador;
            if (!$origem_id) {
                $caixa_id = auth('device')->user()->caixa_id;
                $caixa = Caixa::findOrFail($caixa_id);
                $movimentacao = Movimentacao::where('iniciador_id', $prestador->id)
                    ->where('caixa_id', $caixa->id)
                    ->where('aberta', true)->firstOrFail();
                $saldo = Pagamento::where('carteira_id', $caixa->carteira_id)
                    ->where('movimentacao_id', $movimentacao->id)
                    ->where('estado', Pagamento::ESTADO_PAGO)->sum('valor');
            } else {
                $saldo = Saldo::where('carteira_id', $origem_id)
                    ->where('moeda_id', app('currency')->id)->sum('valor');
            }
            $forma = Forma::where('tipo', Forma::TIPO_DINHEIRO)
                ->where('ativa', true)->firstOrFail();
            if ($saldo >= $valor) {
                $origem = new Pagamento([
                    'moeda_id' => app('currency')->id,
                    'carteira_id' => $origem_id ?: $movimentacao->caixa->carteira->id,
                    'forma_id' => $forma->id,
                    'valor' => $valor * -1,
                    'estado' => Pagamento::ESTADO_PAGO,
                    'lancado' => $valor * -1,
                    'detalhes' => $origem_id ? 'tranferencia de conta' : 'sangria',
                ]);
                $origem->movimentacao_id = $origem_id ? null : $movimentacao->id;
                $origem->funcionario_id = $prestador->id;
                $origem->save();
                $destino = new Pagamento([
                    'moeda_id' => app('currency')->id,
                    'carteira_id' => $input['destino_id'],
                    'forma_id' => $forma->id,
                    'valor' => $valor,
                    'estado' => Pagamento::ESTADO_PAGO,
                    'lancado' => $valor,
                    'detalhes' => $origem_id ? 'tranferencia de conta' : 'sangria',
                ]);
                $destino->funcionario_id = $prestador->id;
                $destino->save();
                return $destino;
            } else {
                throw new Exception('saldo Insuficiente');
            }
        });
        return $destino;
    }
}
