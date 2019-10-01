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

namespace App\GraphQL\Inputs;

use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class SessaoInput extends InputType
{
    protected $attributes = [
        'name' => 'SessaoInput',
        'description' => 'Sessão de trabalho do dia, permite que vários caixas sejam abertos utilizando uma mesma sessão',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Código da sessão',
            ],
            'data_inicio' => [
                'type' => Type::nonNull(GraphQL::type('DateTime')),
                'description' => 'Data de início da sessão',
            ],
            'data_termino' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de fechamento da sessão',
            ],
            'aberta' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se a sessão está aberta',
            ],
        ];
    }
}