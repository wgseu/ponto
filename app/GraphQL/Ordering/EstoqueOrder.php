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

class EstoqueOrder extends InputType
{
    protected $attributes = [
        'name' => 'EstoqueOrder',
        'description' => 'Estoque de produtos por setor',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'produto_id' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'requisito_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'transacao_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'entrada_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'fornecedor_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'setor_id' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'prestador_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'quantidade' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'preco_compra' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'lote' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'fabricacao' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'vencimento' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'detalhes' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'reservado' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'cancelado' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'data_movimento' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
        ];
    }
}
