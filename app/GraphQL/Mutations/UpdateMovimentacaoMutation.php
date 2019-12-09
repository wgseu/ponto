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
use App\Models\Movimentacao;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UpdateMovimentacaoMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateMovimentacao',
    ];

    public function authorize(array $args): bool
    {
        return Auth::check() && (Auth::user()->can('movimentacao:update') || Auth::user()->can('caixa:reopen'));
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
        $movimentacao = Movimentacao::findOrFail($args['id']);
        $reabrindo = $movimentacao->aberta == false
        && $movimentacao->aberta != ($args['input']['aberta'] ?? $movimentacao->aberta);
        $movimentacao->fill($args['input']);

        $prestador = auth()->user()->prestador;
        $caixa = $movimentacao->caixa;
        if ($reabrindo && Auth::user()->can('caixa:reopen')) {
            (new Auditoria([
                'prestador_id' => $prestador->id,
                'autorizador_id' => $prestador->id,
                'tipo' => Auditoria::TIPO_FINANCEIRO,
                'prioridade' => Auditoria::PRIORIDADE_ALTA,
                'descricao' => __('reopen_cashier', ['caixa' => $caixa->descricao]),
                'data_registro' => Carbon::now(),
            ]))->save();
            $movimentacao->fechador_id = null;
            $movimentacao->data_fechamento = null;
        }
        $movimentacao->closeOrSave($prestador);
        return $movimentacao;
    }
}
