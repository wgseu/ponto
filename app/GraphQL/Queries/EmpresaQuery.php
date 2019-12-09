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

use App\Models\Empresa;
use App\Util\Filter;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;

class EmpresaQuery extends Query
{
    protected $attributes = [
        'name' => 'empresa',
    ];

    public function authorize(array $args): bool
    {
        return Auth::check() && Auth::user()->can('empresa:view');
    }

    public function type(): Type
    {
        return GraphQL::type('Empresa');
    }

    public function args(): array
    {
        return [
            'all' => [
                'name' => 'all',
                'type' => Type::boolean(),
                'description' => 'Se verdadeiro devolve todas as opções da empresa',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        /** @var Empresa $empresa */
        $empresa = app('business');
        $empresa->loadOptions();
        $empresa_data = $empresa->toArray();
        $empresa_data['opcoes'] = json_encode(Filter::emptyObject(
            $empresa->options->getValues($args['all'] ?? false)
        ));
        return $empresa_data;
    }
}
