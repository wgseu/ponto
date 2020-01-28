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

use App\Models\Pedido;
use App\Models\Avaliacao;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\ValidationException;
use Illuminate\Support\Facades\DB;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UpdateAvaliacaoMutation extends CreateAvaliacaoMutation
{
    protected $attributes = [
        'name' => 'UpdateAvaliacao',
    ];

    public function authorize(array $args): bool
    {
        $pedido = Avaliacao::findOrFail($args['id'])->pedido;
        return Auth::check() && (
            $pedido->cliente_id == auth()->user()->id ||
            auth()->user()->can('avaliacao:update')
        );
    }

    public function type(): Type
    {
        return GraphQL::type('Avaliacao');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Identificador da avaliação',
            ],
            'input' => ['type' => Type::nonNull(GraphQL::type('AvaliacaoUpdateInput'))],
        ];
    }

    public function resolve($root, $args)
    {
        $resumo = Avaliacao::whereNull('metrica_id')->findOrFail($args['id']);
        DB::transaction(function () use ($resumo, $args) {
            $resumo->fill($args['input']);
            $subavaliacoes = $args['input']['subavaliacoes'] ?? [];
            self::saveAll($subavaliacoes, $resumo);
            $resumo->save();
        });
        return $resumo;
    }
}
