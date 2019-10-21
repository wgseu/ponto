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

use App\Models\Formacao;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class FormacaoType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Formacao',
        'description' => 'Informa qual foi a formação que gerou esse produto, assim como quais item foram retirados/adicionados da composição',
        'model' => Formacao::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da formação',
            ],
            'item_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Informa qual foi o produto vendido para essa formação',
            ],
            'pacote_id' => [
                'type' => Type::int(),
                'description' => 'Informa qual pacote foi selecionado no momento da venda',
            ],
            'composicao_id' => [
                'type' => Type::int(),
                'description' => 'Informa qual composição foi retirada ou adicionada no momento da venda',
            ],
            'quantidade' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Quantidade de itens selecionados',
            ],
        ];
    }
}
