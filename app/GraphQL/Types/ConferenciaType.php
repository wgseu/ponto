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

use App\Models\Conferencia;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ConferenciaType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Conferencia',
        'description' => 'Conferência diária de produto em cada setor',
        'model' => Conferencia::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da conferência',
            ],
            'funcionario_id' => [
                'type' => Type::id(),
                'description' => 'Funcionário que está realizando a conferẽncia do estoque',
            ],
            'numero' => [
                'type' => Type::int(),
                'description' => 'Número da conferência, incrementado todo dia',
            ],
            'produto_id' => [
                'type' => Type::id(),
                'description' => 'Produto que está sendo conferido nesse setor',
            ],
            'setor_id' => [
                'type' => Type::id(),
                'description' => 'Setor em que o produto está localizado',
            ],
            'quantidade' => [
                'type' => Type::float(),
                'description' => 'Quantidade registrada do produto nesse setor',
            ],
            'conferido' => [
                'type' => Type::float(),
                'description' => 'Quantidade conferida do produto nesse setor',
            ],
            'data_conferencia' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data em que a conferência foi realizada',
            ],
        ];
    }
}
