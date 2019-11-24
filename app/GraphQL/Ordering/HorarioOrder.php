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

use Rebing\GraphQL\Support\InputType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class HorarioOrder extends InputType
{
    protected $attributes = [
        'name' => 'HorarioOrder',
        'description' => 'Informa o horário de funcionamento do estabelecimento',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'modo' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'funcao_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'prestador_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'cozinha_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'inicio' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'fim' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'mensagem' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'entrega_minima' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'entrega_maxima' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'fechado' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
        ];
    }
}
