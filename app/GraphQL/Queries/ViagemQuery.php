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

use App\Models\Viagem;
use App\GraphQL\Utils\Filter;
use App\GraphQL\Utils\Ordering;

use Closure;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Illuminate\Support\Facades\Auth;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ViagemQuery extends Query
{
    protected $attributes = [
        'name' => 'viagens',
    ];

    public function authorize(array $args): bool
    {
        return Auth::check() && Auth::user()->can('viagem:view');
    }

    public function type(): Type
    {
        return GraphQL::paginate('Viagem');
    }

    public function args(): array
    {
        return [
            'filter' => ['name' => 'filter', 'type' => GraphQL::type('ViagemFilter')],
            'order' => ['name' => 'order', 'type' => GraphQL::type('ViagemOrder')],
            'limit' => ['name' => 'limit', 'type' => Type::int(), 'rules' => ['min:1', 'max:100']],
            'page' => ['name' => 'page', 'type' => Type::int(), 'rules' => ['min:1']],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $query = Viagem::with($fields->getRelations())
            ->select($fields->getSelect())
            ->where(Filter::map($args['filter'] ?? []));
        return Ordering::apply($args['order'] ?? [], $query)
            ->paginate($args['limit'] ?? 10, ['*'], 'page', $args['page'] ?? 1);
    }
}
