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

use App\Models\Lista;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ListaType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Lista',
        'description' => 'Lista de compras de produtos',
        'model' => Lista::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da lista de compras',
            ],
            'descricao' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Nome da lista, pode ser uma data',
            ],
            'estado' => [
                'type' => Type::nonNull(GraphQL::type('ListaEstado')),
                'description' => 'Estado da lista de compra. Análise: Ainda estão sendo adicionado' .
                    ' produtos na lista, Fechada: Está pronto para compra, Comprada: Todos os' .
                    ' itens foram comprados',
            ],
            'encarregado_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Informa o funcionário encarregado de fazer as compras',
            ],
            'viagem_id' => [
                'type' => Type::id(),
                'description' => 'Informações da viagem para realizar as compras',
            ],
            'data_viagem' => [
                'type' => Type::nonNull(GraphQL::type('DateTime')),
                'description' => 'Data e hora para o encarregado ir fazer as compras',
            ],
            'data_cadastro' => [
                'type' => Type::nonNull(GraphQL::type('DateTime')),
                'description' => 'Data de cadastro da lista',
            ],
        ];
    }
}
