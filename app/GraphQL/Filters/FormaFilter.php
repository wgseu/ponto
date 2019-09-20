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

class FormaFilter extends InputType
{
    protected $attributes = [
        'name' => 'FormaFilter',
        'description' => 'Formas de pagamento disponíveis para pedido e contas',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('FormaTipoFilter')),
            ],
            'carteira_id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'descricao' => [
                'type' => Type::nonNull(GraphQL::type('StringFilter')),
            ],
            'min_parcelas' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'max_parcelas' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'parcelas_sem_juros' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'juros' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'ativa' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
        ];
    }
}
