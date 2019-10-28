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

use App\Models\Caixa;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CaixaType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Caixa',
        'description' => 'Caixas de movimentação financeira',
        'model' => Caixa::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador do caixa',
            ],
            'carteira_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Carteira que representa a gaveta de dinheiro do caixa',
            ],
            'descricao' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Descrição do caixa',
            ],
            'serie' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Série do caixa',
            ],
            'numero_inicial' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Número inicial na geração da nota, será usado quando maior que o último' .
                    ' número utilizado',
            ],
            'ativa' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se o caixa está ativo',
            ],
            'data_desativada' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Informa se o caixa está ativo',
            ],
        ];
    }
}
