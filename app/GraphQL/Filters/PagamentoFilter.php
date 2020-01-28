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
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'carteira_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'moeda_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'pagamento_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'agrupamento_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'movimentacao_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'funcionario_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'forma_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'pedido_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'conta_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'cartao_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'cheque_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'crediario_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'credito_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'total' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'taxas' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'valor' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'numero_parcela' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'parcelas' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'lancado' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'codigo' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'detalhes' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'estado' => [
                'type' => GraphQL::type('PagamentoEstadoFilter'),
            ],
            'data_pagamento' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'data_compensacao' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'data_lancamento' => [
                'type' => GraphQL::type('DateFilter'),
            ],
        ];
    }
}
