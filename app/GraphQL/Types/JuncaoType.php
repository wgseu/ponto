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

use App\Models\Juncao;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class JuncaoType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Juncao',
        'description' => 'Junções de mesas, informa quais mesas estão juntas ao pedido',
        'model' => Juncao::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da junção',
            ],
            'mesa_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Mesa que está junta ao pedido',
            ],
            'pedido_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Pedido a qual a mesa está junta, o pedido deve ser de uma mesa',
            ],
            'estado' => [
                'type' => Type::nonNull(GraphQL::type('JuncaoEstado')),
                'description' => 'Estado a junção da mesa. Associado: a mesa está junta ao pedido, Liberado: A mesa está livre, Cancelado: A mesa está liberada',
            ],
            'data_movimento' => [
                'type' => Type::nonNull(GraphQL::type('DateTime')),
                'description' => 'Data e hora da junção das mesas',
            ],
        ];
    }
}
