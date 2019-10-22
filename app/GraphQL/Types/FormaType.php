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

use App\Models\Forma;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class FormaType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Forma',
        'description' => 'Formas de pagamento disponíveis para pedido e contas',
        'model' => Forma::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da forma de pagamento',
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('FormaTipo')),
                'description' => 'Tipo de pagamento',
            ],
            'carteira_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Carteira que será usada para entrada de valores no caixa',
            ],
            'descricao' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Descrição da forma de pagamento',
            ],
            'min_parcelas' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Quantidade mínima de parcelas',
            ],
            'max_parcelas' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Quantidade máxima de parcelas',
            ],
            'parcelas_sem_juros' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Quantidade de parcelas em que não será cobrado juros',
            ],
            'juros' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Juros cobrado ao cliente no parcelamento',
            ],
            'ativa' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se a forma de pagamento está ativa',
            ],
        ];
    }
}
