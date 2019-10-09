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

$[table.exists(date|time|enum)]
use Rebing\GraphQL\Support\Facades\GraphQL;
$[table.else.exists(data_cadastro|data_criacao|data_lancamento|data_envio|data_atualizacao|data_arquivado|data_arquivamento)]
$[table.else.exists(datetime)]
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
$[field.if(primary)]
$[field.else.if(reference)]
            '$[field]' => [
                'type' => $[field.if(null)]$[field.else]Type::nonNull($[field.end]Type::int()$[field.if(null)]$[field.else])$[field.end],
                'description' => '$[Field.comment]',
            ],
$[field.else.if(date)]
            '$[field]' => [
                'type' => $[field.if(null|info)]$[field.else]Type::nonNull($[field.end]GraphQL::type('Date')$[field.if(null|info)]$[field.else])$[field.end],
                'description' => '$[Field.comment]',
            ],
$[field.else.if(time)]
            '$[field]' => [
                'type' => $[field.if(null|info)]$[field.else]Type::nonNull($[field.end]GraphQL::type('Time')$[field.if(null|info)]$[field.else])$[field.end],
                'description' => '$[Field.comment]',
            ],
$[field.else.if(datetime)]
$[field.match(.*cadastro|.*criacao|.*lancamento|.*envio|.*atualizacao|.*arquivado|.*arquivamento)]
$[field.else]
            '$[field]' => [
                'type' => $[field.if(null|info)]$[field.else]Type::nonNull($[field.end]GraphQL::type('DateTime')$[field.if(null|info)]$[field.else])$[field.end],
                'description' => '$[Field.comment]',
            ],
$[field.end]
$[field.else.if(currency)]
            '$[field]' => [
                'type' => $[field.if(null|info)]$[field.else]Type::nonNull($[field.end]Type::float()$[field.if(null|info)]$[field.else])$[field.end],
                'description' => '$[Field.comment]',
            ],
$[field.else.if(float|double)]
            '$[field]' => [
                'type' => $[field.if(null|info)]$[field.else]Type::nonNull($[field.end]Type::float()$[field.if(null|info)]$[field.else])$[field.end],
                'description' => '$[Field.comment]',
            ],
$[field.else.if(integer|bigint)]
            '$[field]' => [
                'type' => $[field.if(null|info)]$[field.else]Type::nonNull($[field.end]Type::int()$[field.if(null|info)]$[field.else])$[field.end],
                'description' => '$[Field.comment]',
            ],
$[field.else.if(blob)]
            '$[field]' => [
                'type' => $[field.if(null|info)]$[field.else]Type::nonNull($[field.end]Type::string()$[field.if(null|info)]$[field.else])$[field.end],
                'description' => '$[Field.comment]',
            ],
$[field.else.if(boolean)]
            '$[field]' => [
                'type' => $[field.if(null|info)]$[field.else]Type::nonNull($[field.end]Type::boolean()$[field.if(null|info)]$[field.else])$[field.end],
                'description' => '$[Field.comment]',
            ],
$[field.else.if(enum)]
            '$[field]' => [
                'type' => $[field.if(null|info)]$[field.else]Type::nonNull($[field.end]GraphQL::type('$[Table.norm]$[Field.norm]')$[field.if(null|info)]$[field.else])$[field.end],
                'description' => '$[Field.comment]',
            ],
$[field.else]
            '$[field]' => [
                'type' => $[field.if(null|info)]$[field.else]Type::nonNull($[field.end]Type::string()$[field.if(null|info)]$[field.else])$[field.end],
                'description' => '$[Field.comment]',
                'rules' => ['max:$[field.length]'],
            ],
$[field.end]
$[field.end]
        ];
    }
}
