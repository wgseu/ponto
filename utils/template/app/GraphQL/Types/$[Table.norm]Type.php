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

namespace App\GraphQL\Types;

use App\Models\$[Table.norm];
$[table.exists(date|time|datetime|enum)]
use Rebing\GraphQL\Support\Facades\GraphQL;
$[table.end]
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class $[Table.norm]Type extends GraphQLType
{
    protected $attributes = [
        'name' => '$[Table.norm]',
        'description' => $[table.each(description)]$[table.if(first)]'$[Table.description]'$[table.else] .
            ' $[Table.description]'$[table.end]$[table.end],
        'model' => $[Table.norm]::class,
    ];

    public function fields(): array
    {
        return [
$[field.each(all)]
$[field.match(senha|secreto)]
$[field.else]
            '$[field]' => [
$[field.if(primary|reference)]
                'type' => Type::id(),
$[field.else.if(date)]
                'type' => GraphQL::type('Date'),
$[field.else.if(time)]
                'type' => GraphQL::type('Time'),
$[field.else.if(datetime)]
                'type' => GraphQL::type('DateTime'),
$[field.else.if(currency|float|double)]
                'type' => Type::float(),
$[field.else.if(integer|bigint)]
                'type' => Type::int(),
$[field.else.if(blob)]
                'type' => Type::string(),
$[field.else.if(boolean)]
                'type' => Type::boolean(),
$[field.else.if(enum)]
                'type' => GraphQL::type('$[Table.norm]$[Field.norm]'),
$[field.else]
                'type' => Type::string(),
$[field.end]
                'description' => $[field.each(description)]$[field.if(first)]'$[Field.description]'$[field.else] .
                    ' $[Field.description]'$[field.end]$[field.end],
            ],
$[field.end]
$[field.end]
        ];
    }
}
