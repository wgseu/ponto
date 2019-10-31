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

use App\Models\Pontuacao;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PontuacaoType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Pontuacao',
        'description' => 'Informa os pontos ganhos e gastos por compras de produtos promocionais',
        'model' => Pontuacao::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da pontuação',
            ],
            'promocao_id' => [
                'type' => Type::id(),
                'description' => 'Informa a promoção que originou os pontos ou que descontou os pontos',
            ],
            'cliente_id' => [
                'type' => Type::id(),
                'description' => 'Cliente que possui esses pontos, não informar quando tiver travado por' .
                    ' pedido',
            ],
            'pedido_id' => [
                'type' => Type::id(),
                'description' => 'Informa se essa pontuação será usada apenas nesse pedido',
            ],
            'item_id' => [
                'type' => Type::id(),
                'description' => 'Informa qual venda originou esses pontos, tanto saída como entrada',
            ],
            'quantidade' => [
                'type' => Type::int(),
                'description' => 'Quantidade de pontos ganhos ou gastos',
            ],
            'data_cadastro' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de cadastro dos pontos',
            ],
        ];
    }
}
