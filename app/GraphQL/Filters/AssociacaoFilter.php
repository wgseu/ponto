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

class AssociacaoFilter extends InputType
{
    protected $attributes = [
        'name' => 'AssociacaoFilter',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'integracao_id' => [
                'type' => Type::int(),
            ],
            'entrega_id' => [
                'type' => Type::int(),
            ],
            'codigo' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'cliente' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'chave' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'pedido' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'endereco' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'quantidade' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'servicos' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'produtos' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'descontos' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'pago' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'status' => [
                'type' => GraphQL::type('AssociacaoStatusFilter'),
            ],
            'motivo' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'mensagem' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'sincronizado' => [
                'type' => Type::boolean(),
            ],
            'integrado' => [
                'type' => Type::boolean(),
            ],
            'data_confirmacao' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'data_pedido' => [
                'type' => GraphQL::type('DateFilter'),
            ],
        ];
    }
}
