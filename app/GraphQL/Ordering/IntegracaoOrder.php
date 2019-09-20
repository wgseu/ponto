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

class IntegracaoOrder extends InputType
{
    protected $attributes = [
        'name' => 'IntegracaoOrder',
        'description' => 'Informa quais integrações estão disponíveis',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'nome' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'descricao' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'icone_url' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'login' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'secret' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'opcoes' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'associacoes' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'ativo' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'data_atualizacao' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
        ];
    }
}
