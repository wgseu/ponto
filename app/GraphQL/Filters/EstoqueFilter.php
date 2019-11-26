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
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'producao_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'produto_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'compra_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'transacao_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'fornecedor_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'setor_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'prestador_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'quantidade' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'preco_compra' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'custo_medio' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'estoque' => [
                'type' => GraphQL::type('NumberFilter'),
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
            'cancelado' => [
                'type' => Type::boolean(),
            ],
            'data_movimento' => [
                'type' => GraphQL::type('DateFilter'),
            ],
        ];
    }
}
