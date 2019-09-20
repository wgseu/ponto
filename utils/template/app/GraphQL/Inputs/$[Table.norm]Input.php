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

namespace App\GraphQL\Inputs;

$[table.exists(date|time|datetime|enum)]
use Rebing\GraphQL\Support\Facades\GraphQL;
$[table.end]
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class $[Table.norm]Input extends InputType
{
    protected $attributes = [
        'name' => '$[Table.norm]Input',
        'description' => '$[Table.comment]',
    ];

    public function fields(): array
    {
        return [
$[field.each(all)]
            '$[field]' => [
$[field.if(primary)]
                'type' => Type::id(),
$[field.else.if(reference)]
                'type' => $[field.if(null)]$[field.else]Type::nonNull($[field.end]Type::int()$[field.if(null)]$[field.else])$[field.end],
$[field.else.if(date)]
                'type' => $[field.if(null)]$[field.else]Type::nonNull($[field.end]GraphQL::type('date')$[field.if(null)]$[field.else])$[field.end],
$[field.else.if(time)]
                'type' => $[field.if(null)]$[field.else]Type::nonNull($[field.end]GraphQL::type('time')$[field.if(null)]$[field.else])$[field.end],
$[field.else.if(datetime)]
                'type' => $[field.if(null)]$[field.else]Type::nonNull($[field.end]GraphQL::type('datetime')$[field.if(null)]$[field.else])$[field.end],
$[field.else.if(currency)]
                'type' => $[field.if(null)]$[field.else]Type::nonNull($[field.end]Type::float()$[field.if(null)]$[field.else])$[field.end],
$[field.else.if(float|double)]
                'type' => $[field.if(null)]$[field.else]Type::nonNull($[field.end]Type::float()$[field.if(null)]$[field.else])$[field.end],
$[field.else.if(integer|bigint)]
                'type' => $[field.if(null)]$[field.else]Type::nonNull($[field.end]Type::int()$[field.if(null)]$[field.else])$[field.end],
$[field.else.if(blob)]
                'type' => $[field.if(null)]$[field.else]Type::nonNull($[field.end]Type::string()$[field.if(null)]$[field.else])$[field.end],
$[field.else.if(boolean)]
                'type' => $[field.if(null)]$[field.else]Type::nonNull($[field.end]Type::boolean()$[field.if(null)]$[field.else])$[field.end],
$[field.else.if(enum)]
                'type' => $[field.if(null)]$[field.else]Type::nonNull($[field.end]GraphQL::type('$[Table.norm]$[Field.norm]')$[field.if(null)]$[field.else])$[field.end],
$[field.else]
                'type' => $[field.if(null)]$[field.else]Type::nonNull($[field.end]Type::string()$[field.if(null)]$[field.else])$[field.end],
                'rules' => ['max:$[field.length]'],
$[field.end]
                'description' => '$[Field.comment]',
            ],
$[field.end]
        ];
    }
}
