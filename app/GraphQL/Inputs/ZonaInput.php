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

class ZonaInput extends InputType
{
    protected $attributes = [
        'name' => 'ZonaInput',
        'description' => 'Zonas de um bairro',
    ];

    public function fields(): array
    {
        return [
            'bairro_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Bairro em que essa zona está localizada',
            ],
            'nome' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Nome da zona, Ex. Sul, Leste, Começo, Fim',
                'rules' => ['max:45'],
            ],
            'adicional_entrega' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Taxa adicional para entrega nessa zona, será somado com a taxa para esse bairro',
            ],
            'disponivel' => [
                'type' => Type::boolean(),
                'description' => 'Informa se a zona está disponível para entrega de pedidos',
            ],
            'area' => [
                'type' => Type::string(),
                'description' => 'Área de cobertura para entrega',
                'rules' => ['max:65535'],
            ],
            'entrega_minima' => [
                'type' => Type::int(),
                'description' => 'Tempo mínimo para entrega nessa zona, sobrescreve o tempo de entrega para o bairro',
            ],
            'entrega_maxima' => [
                'type' => Type::int(),
                'description' => 'Tempo máximo para entrega nessa zona, sobrescreve o tempo de entrega para o bairro',
            ],
        ];
    }
}
