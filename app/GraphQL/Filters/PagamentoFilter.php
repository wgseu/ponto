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

class PagamentoFilter extends InputType
{
    protected $attributes = [
        'name' => 'PagamentoFilter',
        'description' => 'Pagamentos de contas e pedidos',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'carteira_id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'moeda_id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'pagamento_id' => [
                'type' => Type::int(),
            ],
            'agrupamento_id' => [
                'type' => Type::int(),
            ],
            'movimentacao_id' => [
                'type' => Type::int(),
            ],
            'funcionario_id' => [
                'type' => Type::int(),
            ],
            'forma_id' => [
                'type' => Type::int(),
            ],
            'pedido_id' => [
                'type' => Type::int(),
            ],
            'conta_id' => [
                'type' => Type::int(),
            ],
            'cartao_id' => [
                'type' => Type::int(),
            ],
            'cheque_id' => [
                'type' => Type::int(),
            ],
            'crediario_id' => [
                'type' => Type::int(),
            ],
            'credito_id' => [
                'type' => Type::int(),
            ],
            'valor' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'numero_parcela' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'parcelas' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'lancado' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'codigo' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'detalhes' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'estado' => [
                'type' => Type::nonNull(GraphQL::type('PagamentoEstadoFilter')),
            ],
            'data_pagamento' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'data_compensacao' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'data_lancamento' => [
                'type' => Type::nonNull(GraphQL::type('DateFilter')),
            ],
        ];
    }
}
