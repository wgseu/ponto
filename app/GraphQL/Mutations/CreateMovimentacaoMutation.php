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

use App\Exceptions\Exception;
use App\Models\Forma;
use App\Models\Movimentacao;
use App\Models\Pagamento;
use App\Models\Saldo;
use App\Models\Sessao;
use App\Util\Currency;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CreateMovimentacaoMutation extends Mutation
{
    protected $attributes = [
        'name' => 'CreateMovimentacao',
    ];

    public function authorize(array $args): bool
    {
        return Auth::check() && Auth::user()->can('movimentacao:create')
            && auth('device')->check() && auth('device')->user()->isValid();
    }

    public function type(): Type
    {
        return GraphQL::type('Movimentacao');
    }

    public function args(): array
    {
        return [
            'input' => ['type' => Type::nonNull(GraphQL::type('MovimentacaoInput'))],
        ];
    }

    public function resolve($root, $args)
    {
        $input = $args['input'];
        if (is_null(auth('device')->user()->caixa_id)) {
            throw new Exception(__('messages.device_without_till'));
        }
        $movimentacao = new Movimentacao();
        $movimentacao->fill($args['input']);
        DB::transaction(function () use ($movimentacao, $input) {
            $prestador = auth()->user()->prestador;
            $sessao = Sessao::where('aberta', true)->first();
            $caixa = auth('device')->user()->caixa;
            $movimentacao->iniciador_id = $prestador->id;
            $movimentacao->sessao_id = !is_null($sessao) ? $sessao->id : null;
            $movimentacao->caixa_id = $caixa->id;
            $movimentacao->createSessaoOrSave();
            $valor_inicial = $input['valor_inicial'];
            if (!Currency::isGreater($valor_inicial, 0)) {
                return;
            }
            $forma = Forma::where('tipo', Forma::TIPO_DINHEIRO)
                ->where('ativa', true)->firstOrFail();
            $tesouraria = $forma->carteira;
            $saldo = Saldo::where('moeda_id', app('currency')->id)
                ->where('carteira_id', $tesouraria->id)->first();
            if (
                (!is_null($saldo) && $saldo->valor >= $valor_inicial)
                || (is_null($saldo) && $valor_inicial == 0)
            ) {
                $origem = new Pagamento([
                    'moeda_id' => app('currency')->id,
                    'forma_id' => $forma->id,
                    'valor' => $valor_inicial * -1,
                    'estado' => Pagamento::ESTADO_PAGO,
                    'lancado' => $valor_inicial * -1,
                    'detalhes' => __('messages.open_till_transfer', ['value' => $movimentacao->id]),
                ]);
                $origem->funcionario_id = $prestador->id;
                $origem->save();
                $destino = new Pagamento([
                    'moeda_id' => app('currency')->id,
                    'carteira_id' => $movimentacao->caixa->carteira->id,
                    'forma_id' => $forma->id,
                    'movimentacao_id' => $movimentacao->id,
                    'valor' => $valor_inicial,
                    'estado' => Pagamento::ESTADO_PAGO,
                    'lancado' => $valor_inicial,
                    'detalhes' => __('messages.open_till_base'),
                ]);
                $destino->funcionario_id = $prestador->id;
                $destino->save();
                return $movimentacao;
            }
            throw new Exception(__('messages.insufficient_funds'));
        });
    }
}
