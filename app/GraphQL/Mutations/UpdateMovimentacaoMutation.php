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

use App\Models\Auditoria;
use App\Models\Forma;
use App\Models\Movimentacao;
use App\Models\Pagamento;
use App\Models\Resumo;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UpdateMovimentacaoMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateMovimentacao',
    ];

    public function authorize(array $args): bool
    {
        $movimentacao = Movimentacao::findOrFail($args['id']);
        $reabrindo = $movimentacao->aberta == false
            && $movimentacao->aberta != ($args['input']['aberta'] ?? $movimentacao->aberta);
        return Auth::check() && (
                (!$reabrindo && Auth::user()->can('movimentacao:update'))
                || ($reabrindo && Auth::user()->can('caixa:reopen')
            ));
    }

    public function type(): Type
    {
        return GraphQL::type('Movimentacao');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Código da movimentação do caixa',
            ],
            'input' => ['type' => Type::nonNull(GraphQL::type('MovimentacaoUpdateInput'))],
        ];
    }

    public function resolve($root, $args)
    {
        $input = $args['input'];
        $movimentacao = Movimentacao::findOrFail($args['id']);
        $prestador = auth()->user()->prestador;
        $reabrindo = $movimentacao->aberta == false
            && $movimentacao->aberta != ($args['input']['aberta'] ?? $movimentacao->aberta);
        $movimentacao->fill($args['input']);
        
        if ($reabrindo) {
            $caixa = $movimentacao->caixa;
            (new Auditoria([
                'prestador_id' => $prestador->id,
                'autorizador_id' => $prestador->id,
                'tipo' => Auditoria::TIPO_FINANCEIRO,
                'prioridade' => Auditoria::PRIORIDADE_ALTA,
                'descricao' => __('messages.reopen_cashier', ['caixa' => $caixa->descricao]),
                'data_registro' => Carbon::now(),
            ]))->save();
            $movimentacao->fechador_id = null;
            $movimentacao->data_fechamento = null;
            $movimentacao->closeOrSave($prestador);
            return $movimentacao;
        } else {
            DB::transaction(function () use ($movimentacao, $input, $prestador) {
                $caixa = $movimentacao->caixa;
                $gaveta = $caixa->carteira;
                $forma = Forma::where('tipo', Forma::TIPO_DINHEIRO)
                    ->where('ativa', true)->firstOrFail();
                $saldo = Pagamento::where('carteira_id', $gaveta->id)
                    ->where('movimentacao_id', $movimentacao->id)
                    ->where('estado', Pagamento::ESTADO_PAGO)->sum('valor');
                $resumos = $input['resumos'];
                foreach ($resumos as $item) {
                    if ($item['valor'] > 0) {
                        $resumo = new Resumo([
                            'movimentacao_id' => $movimentacao->id,
                            'forma_id' => $item['forma_id'],
                            'valor' => $item['valor'],
                            'cartao_id' => $item['cartao_id'],
                        ]);
                        $resumo->save();
                    }
                }
                if ($saldo > 0) {
                    $origem = new Pagamento([
                        'carteira_id' => $gaveta->id,
                        'moeda_id' => app('currency')->id,
                        'forma_id' => $forma->id,
                        'valor' => $saldo * -1,
                        'estado' => Pagamento::ESTADO_PAGO,
                        'lancado' => $saldo * -1,
                        'detalhes' => __('messages.bleeding_closing_till', ['value' => $movimentacao->id]),
                    ]);
                    $origem->funcionario_id = $prestador->id;
                    $origem->save();
                    $destino = new Pagamento([
                        'carteira_id' => $forma->carteira_id,
                        'moeda_id' => app('currency')->id,
                        'forma_id' => $forma->id,
                        'valor' => $saldo,
                        'estado' => Pagamento::ESTADO_PAGO,
                        'lancado' => $saldo,
                        'detalhes' => __('messages.closing_till', ['value' => $movimentacao->id]),
                    ]);
                    $destino->funcionario_id = $prestador->id;
                    $destino->save();
                }
                $movimentacao->aberta = false;
                $movimentacao->closeOrSave($prestador);
                return $movimentacao;
            });
        }
    }
}
