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

use App\Models\Sistema;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SistemaType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Sistema',
        'description' => 'Informações do restaurante e da aplicação',
        'model' => Sistema::class,
    ];

    public function fields(): array
    {
        return [
            'empresa' => [
                'type' => GraphQL::type('Empresa'),
                'description' => 'Informações da empresa',
            ],
            'fuso_horario' => [
                'type' => Type::string(),
                'description' => 'Informa qual o fuso horário da aplicação',
            ],
            'versao' => [
                'type' => Type::string(),
                'description' => 'Informa a versão atual da aplicação',
            ],
            'opcoes' => [
                'type' => Type::string(),
                'description' => 'Opções gerais do sistema',
                'privacy' => function (array $args): bool {
                    return Auth::check() && Auth::user()->can('sistema:view');
                },
            ],
        ];
    }
}
