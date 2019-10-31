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

use App\Models\Cardapio;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CardapioType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Cardapio',
        'description' => 'Cardápios para cada integração ou local de venda',
        'model' => Cardapio::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador do cardápio',
            ],
            'produto_id' => [
                'type' => Type::id(),
                'description' => 'Produto que faz parte desse cardápio',
            ],
            'integracao_id' => [
                'type' => Type::id(),
                'description' => 'Permite exibir o cardápio somente nessa integração',
            ],
            'local' => [
                'type' => GraphQL::type('CardapioLocal'),
                'description' => 'O cardápio será exibido para vendas nesse local',
            ],
            'acrescimo' => [
                'type' => Type::float(),
                'description' => 'Acréscimo ao preço de venda do produto nesse cardápio',
            ],
            'disponivel' => [
                'type' => Type::boolean(),
                'description' => 'Informa se o produto estará disponível para venda nesse cardápio',
            ],
        ];
    }
}
