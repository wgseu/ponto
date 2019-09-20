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

class EstoqueFilter extends InputType
{
    protected $attributes = [
        'name' => 'EstoqueFilter',
        'description' => 'Estoque de produtos por setor',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'produto_id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'requisito_id' => [
                'type' => Type::int(),
            ],
            'transacao_id' => [
                'type' => Type::int(),
            ],
            'entrada_id' => [
                'type' => Type::int(),
            ],
            'fornecedor_id' => [
                'type' => Type::int(),
            ],
            'setor_id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'prestador_id' => [
                'type' => Type::int(),
            ],
            'quantidade' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'preco_compra' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'lote' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'fabricacao' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'vencimento' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'detalhes' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'reservado' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'cancelado' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'data_movimento' => [
                'type' => Type::nonNull(GraphQL::type('DateFilter')),
            ],
        ];
    }
}
