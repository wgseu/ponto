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

class EventoInput extends InputType
{
    protected $attributes = [
        'name' => 'EventoInput',
        'description' => 'Eventos de envio das notas',
    ];

    public function fields(): array
    {
        return [
            'nota_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Nota a qual o evento foi criado',
            ],
            'estado' => [
                'type' => Type::nonNull(GraphQL::type('EventoEstado')),
                'description' => 'Estado do evento',
            ],
            'mensagem' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Mensagem do evento, descreve que aconteceu',
                'rules' => ['max:65535'],
            ],
            'codigo' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Código de status do evento, geralmente código de erro de uma exceção',
                'rules' => ['max:20'],
            ],
        ];
    }
}
