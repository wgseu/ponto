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

namespace App\GraphQL\Types;

use App\Models\Credito;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CreditoType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Credito',
        'description' => 'Créditos de clientes',
        'model' => Credito::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador do crédito',
            ],
            'cliente_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Cliente a qual o crédito pertence',
            ],
            'valor' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Valor do crédito',
            ],
            'detalhes' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Detalhes do crédito, justificativa do crédito',
            ],
            'cancelado' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se o crédito foi cancelado',
            ],
            'data_cadastro' => [
                'type' => Type::nonNull(GraphQL::type('DateTime')),
                'description' => 'Data de cadastro do crédito',
            ],
        ];
    }
}
