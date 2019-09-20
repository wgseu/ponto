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

class DispositivoFilter extends InputType
{
    protected $attributes = [
        'name' => 'DispositivoFilter',
        'description' => 'Computadores e tablets com opções de acesso',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'setor_id' => [
                'type' => Type::int(),
            ],
            'caixa_id' => [
                'type' => Type::int(),
            ],
            'nome' => [
                'type' => Type::nonNull(GraphQL::type('StringFilter')),
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('DispositivoTipoFilter')),
            ],
            'descricao' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'opcoes' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'serial' => [
                'type' => Type::nonNull(GraphQL::type('StringFilter')),
            ],
            'validacao' => [
                'type' => GraphQL::type('StringFilter'),
            ],
        ];
    }
}
