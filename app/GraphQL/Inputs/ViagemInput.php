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

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ViagemInput extends InputType
{
    protected $attributes = [
        'name' => 'ViagemInput',
        'description' => 'Registro de viagem de uma entrega ou compra de insumos',
    ];

    public function fields(): array
    {
        return [
            'responsavel_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Responsável pela entrega ou compra',
            ],
            'latitude' => [
                'type' => Type::float(),
                'description' => 'Ponto latitudinal para localização do responsável em tempo real',
            ],
            'longitude' => [
                'type' => Type::float(),
                'description' => 'Ponto longitudinal para localização do responsável em tempo real',
            ],
            'quilometragem' => [
                'type' => Type::float(),
                'description' => 'Quilometragem no veículo antes de iniciar a viagem',
            ],
            'distancia' => [
                'type' => Type::float(),
                'description' => 'Distância percorrida até chegar de volta ao ponto de partida',
            ],
            'data_chegada' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de chegada no estabelecimento',
            ],
            'data_saida' => [
                'type' => Type::nonNull(GraphQL::type('DateTime')),
                'description' => 'Data e hora que o responsável saiu para entregar o pedido ou fazer as compras',
            ],
        ];
    }
}
