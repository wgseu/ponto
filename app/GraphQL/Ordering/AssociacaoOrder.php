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

class AssociacaoOrder extends InputType
{
    protected $attributes = [
        'name' => 'AssociacaoOrder',
        'description' => 'Lista de pedidos que não foram integrados ainda e devem ser associados ao sistema',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'integracao_id' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'entrega_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'codigo' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'cliente' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'chave' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'pedido' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'endereco' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'quantidade' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'servicos' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'produtos' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'descontos' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'pago' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'status' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'motivo' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'mensagem' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'sincronizado' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'integrado' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'data_confirmacao' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_pedido' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
        ];
    }
}
