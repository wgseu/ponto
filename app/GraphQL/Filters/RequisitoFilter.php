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

class RequisitoFilter extends InputType
{
    protected $attributes = [
        'name' => 'RequisitoFilter',
        'description' => 'Informa os produtos da lista de compras',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'lista_id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'produto_id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'compra_id' => [
                'type' => Type::int(),
            ],
            'fornecedor_id' => [
                'type' => Type::int(),
            ],
            'quantidade' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'comprado' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'preco_maximo' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'preco' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'observacoes' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'data_recolhimento' => [
                'type' => GraphQL::type('DateFilter'),
            ],
        ];
    }
}
