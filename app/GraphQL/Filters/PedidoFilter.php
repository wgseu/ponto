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

namespace App\GraphQL\Filters;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class PedidoFilter extends InputType
{
    protected $attributes = [
        'name' => 'PedidoFilter',
        'description' => 'Informações do pedido de venda',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'pedido_id' => [
                'type' => Type::int(),
            ],
            'mesa_id' => [
                'type' => Type::int(),
            ],
            'comanda_id' => [
                'type' => Type::int(),
            ],
            'sessao_id' => [
                'type' => Type::int(),
            ],
            'prestador_id' => [
                'type' => Type::int(),
            ],
            'cliente_id' => [
                'type' => Type::int(),
            ],
            'localizacao_id' => [
                'type' => Type::int(),
            ],
            'entrega_id' => [
                'type' => Type::int(),
            ],
            'associacao_id' => [
                'type' => Type::int(),
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('PedidoTipoFilter')),
            ],
            'estado' => [
                'type' => Type::nonNull(GraphQL::type('PedidoEstadoFilter')),
            ],
            'servicos' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'produtos' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'comissao' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'subtotal' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'descontos' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'total' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'pago' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'troco' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'lancado' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'pessoas' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'cpf' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'email' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'descricao' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'fechador_id' => [
                'type' => Type::int(),
            ],
            'data_impressao' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'motivo' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'data_entrega' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'data_agendamento' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'data_conclusao' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'data_criacao' => [
                'type' => Type::nonNull(GraphQL::type('DateFilter')),
            ],
        ];
    }
}
