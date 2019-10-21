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

namespace App\GraphQL\Ordering;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ViagemOrder extends InputType
{
    protected $attributes = [
        'name' => 'ViagemOrder',
        'description' => 'Registro de viagem de uma entrega ou compra de insumos',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'responsavel_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'latitude' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'longitude' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'quilometragem' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'distancia' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_atualizacao' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_chegada' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_saida' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
        ];
    }
}
