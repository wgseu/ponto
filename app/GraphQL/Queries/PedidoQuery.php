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

namespace App\GraphQL\Queries;

use App\Models\Pedido;
use App\GraphQL\Utils\Filter;
use App\GraphQL\Utils\Ordering;
use Closure;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Illuminate\Support\Facades\Auth;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;

class PedidoQuery extends Query
{
    protected $attributes = [
        'name' => 'pedidos',
    ];

    public function authorize(array $args): bool
    {
        return Auth::check() && (
            ($args['filter']['cliente_id']['eq'] ?? null) == Auth::user()->id
            || Auth::user()->can('pedido:view')
        );
    }

    public function type(): Type
    {
        return GraphQL::paginate('Pedido');
    }

    public function args(): array
    {
        return [
            'filter' => ['name' => 'filter', 'type' => GraphQL::type('PedidoFilter')],
            'order' => ['name' => 'order', 'type' => GraphQL::type('PedidoOrder')],
            'limit' => ['name' => 'limit', 'type' => Type::int(), 'rules' => ['min:1', 'max:100']],
            'page' => ['name' => 'page', 'type' => Type::int(), 'rules' => ['min:1']],
        ];
    }

    /**
     * Verifica se o filtro passado filtra somente pedidos abertos
     * usado para não limitar a quantidade de registros retornados
     *
     * @param array $filter
     * @return boolean
     */
    public static function isOpenState($filter)
    {
        $eq = $filter['eq'] ?? null;
        if (isset($filter['eq']) && $eq != Pedido::ESTADO_CONCLUIDO && $eq != Pedido::ESTADO_CANCELADO) {
            return true;
        }
        $in = $filter['in'] ?? [];
        if (
            isset($filter['in']) &&
            !in_array(Pedido::ESTADO_CONCLUIDO, $in) &&
            !in_array(Pedido::ESTADO_CANCELADO, $in)
        ) {
            return true;
        }
        $ni = $filter['ni'] ?? [];
        if (
            isset($filter['ni']) &&
            in_array(Pedido::ESTADO_CONCLUIDO, $ni) &&
            in_array(Pedido::ESTADO_CANCELADO, $ni)
        ) {
            return true;
        }
        return false;
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $query = Filter::apply(
            $args['filter'] ?? [],
            Pedido::with($fields->getRelations())->select($fields->getSelect())
        );
        $limit = $args['limit'] ?? 10;
        if (!isset($args['limit']) && self::isOpenState($args['filter']['estado'] ?? null)) {
            $limit = (clone $query)->count();
        }
        return Ordering::apply($args['order'] ?? [], $query)
            ->paginate($limit, ['*'], 'page', $args['page'] ?? 1);
    }
}
