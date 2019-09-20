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

class LocalizacaoOrder extends InputType
{
    protected $attributes = [
        'name' => 'LocalizacaoOrder',
        'description' => 'Endereço detalhado de um cliente',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'cliente_id' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'bairro_id' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'zona_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'cep' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'logradouro' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'numero' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'complemento' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'condominio' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'bloco' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'apartamento' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'referencia' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'latitude' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'longitude' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'apelido' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_arquivado' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
        ];
    }
}
