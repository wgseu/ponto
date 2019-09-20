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

class PedidoOrder extends InputType
{
    protected $attributes = [
        'name' => 'PedidoOrder',
        'description' => 'Informações do pedido de venda',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'pedido_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'mesa_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'comanda_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'sessao_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'prestador_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'cliente_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'localizacao_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'entrega_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'associacao_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'estado' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'servicos' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'produtos' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'comissao' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'subtotal' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'descontos' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'total' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'pago' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'troco' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'lancado' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'pessoas' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'cpf' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'email' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'descricao' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'fechador_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_impressao' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'motivo' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_entrega' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_agendamento' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_conclusao' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_criacao' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
        ];
    }
}
