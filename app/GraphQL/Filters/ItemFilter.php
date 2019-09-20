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

class ItemFilter extends InputType
{
    protected $attributes = [
        'name' => 'ItemFilter',
        'description' => 'Produtos, taxas e serviços do pedido, a alteração do estado permite o controle de produção',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'pedido_id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'prestador_id' => [
                'type' => Type::int(),
            ],
            'produto_id' => [
                'type' => Type::int(),
            ],
            'servico_id' => [
                'type' => Type::int(),
            ],
            'item_id' => [
                'type' => Type::int(),
            ],
            'pagamento_id' => [
                'type' => Type::int(),
            ],
            'descricao' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'composicao' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'preco' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'quantidade' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'subtotal' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'comissao' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'total' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'preco_venda' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'preco_compra' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'detalhes' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'estado' => [
                'type' => Type::nonNull(GraphQL::type('ItemEstadoFilter')),
            ],
            'cancelado' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'motivo' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'desperdicado' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'data_processamento' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'data_atualizacao' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'data_lancamento' => [
                'type' => Type::nonNull(GraphQL::type('DateFilter')),
            ],
        ];
    }
}
