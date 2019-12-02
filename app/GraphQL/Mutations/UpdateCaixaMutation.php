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
use App\Models\Auditoria;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\ValidationException;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UpdateCaixaMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateCaixa',
    ];

    public function authorize(array $args): bool
    {
        return Auth::check() && Auth::user()->can('caixa:update');
    }

    public function type(): Type
    {
        return GraphQL::type('Caixa');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Identificador do caixa',
            ],
            'input' => ['type' => Type::nonNull(GraphQL::type('CaixaUpdateInput'))],
        ];
    }

    public function resolve($root, $args)
    {
        $caixa = Caixa::findOrFail($args['id']);
        $caixa->fill($args['input']);
        $condition = $caixa->ativa == false && $caixa->ativa != ($args['input']['ativa'] ?? false);
        $prestador = auth()->user()->prestador;
        if ($condition && !Auth::user()->can('caixa:reopen')) {
            throw new ValidationException(['reopen', __('messages.not_permition_reopen')]);
        }
        if ($condition && Auth::user()->can('caixa:reopen')) {
            (new Auditoria([
                'prestador_id' => $prestador->id,
                'autorizador_id' => $prestador->id,
                'tipo' => Auditoria::TIPO_FINANCEIRO,
                'prioridade' => Auditoria::PRIORIDADE_ALTA,
                'descricao' => 'Reabertura do caixa ' . $caixa->descricao,
                'data_registro' => Carbon::now(),
            ]))->save();
            $caixa->data_desativada = null;
            $caixa->ativa = 1;
        }
        $caixa->save();
        return $caixa;
    }
}
