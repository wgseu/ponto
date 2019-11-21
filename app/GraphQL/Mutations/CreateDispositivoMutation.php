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

use App\Models\Dispositivo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CreateDispositivoMutation extends Mutation
{
    protected $attributes = [
        'name' => 'CreateDispositivo',
    ];

    public function authorize(array $args): bool
    {
        return Auth::check() && Auth::user()->can('dispositivo:create');
    }

    public function type(): Type
    {
        return GraphQL::type('Dispositivo');
    }

    public function args(): array
    {
        return [
            'input' => ['type' => Type::nonNull(GraphQL::type('DispositivoInput'))],
        ];
    }

    public function resolve($root, $args)
    {
        $dispositivo = new Dispositivo();
        $dispositivo->opcoes = null;
        $dispositivo->fill($args['input']);
        $dispositivo->options->addValues(json_decode($dispositivo->opcoes ?? '{}', true));
        $dispositivo->applyOptions();
        $dispositivo->save();
        return $dispositivo;
    }
}
