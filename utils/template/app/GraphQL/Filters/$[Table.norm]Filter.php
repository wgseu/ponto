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

namespace App\GraphQL\Filters;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class $[Table.norm]Filter extends InputType
{
    protected $attributes = [
        'name' => '$[Table.norm]Filter',
    ];

    public function fields(): array
    {
        return [
$[field.each(all)]
            '$[field]' => [
$[field.if(primary)]
                'type' => Type::id(),
$[field.else.if(reference)]
                'type' => Type::int(),
$[field.else.if(date)]
                'type' => GraphQL::type('DateFilter'),
$[field.else.if(time)]
                'type' => GraphQL::type('TimeFilter'),
$[field.else.if(datetime)]
                'type' => GraphQL::type('DateFilter'),
$[field.else.if(currency)]
                'type' => GraphQL::type('NumberFilter'),
$[field.else.if(float|double)]
                'type' => GraphQL::type('NumberFilter'),
$[field.else.if(integer|bigint)]
                'type' => GraphQL::type('NumberFilter'),
$[field.else.if(blob)]
$[field.else.if(boolean)]
                'type' => Type::boolean(),
$[field.else.if(enum)]
                'type' => GraphQL::type('$[Table.norm]$[Field.norm]Filter'),
$[field.else]
                'type' => GraphQL::type('StringFilter'),
$[field.end]
            ],
$[field.end]
        ];
    }
}
