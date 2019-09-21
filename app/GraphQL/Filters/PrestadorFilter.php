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

class PrestadorFilter extends InputType
{
    protected $attributes = [
        'name' => 'PrestadorFilter',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'codigo' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'pin' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'funcao_id' => [
                'type' => Type::int(),
            ],
            'cliente_id' => [
                'type' => Type::int(),
            ],
            'empresa_id' => [
                'type' => Type::int(),
            ],
            'vinculo' => [
                'type' => GraphQL::type('PrestadorVinculoFilter'),
            ],
            'porcentagem' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'pontuacao' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'remuneracao' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'data_termino' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'data_cadastro' => [
                'type' => GraphQL::type('DateFilter'),
            ],
        ];
    }
}
