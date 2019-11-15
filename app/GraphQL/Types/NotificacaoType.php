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

use App\Models\Notificacao;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class NotificacaoType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Notificacao',
        'description' => 'Notificações e avisos para os clientes, funcionários e administradores',
        'model' => Notificacao::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da notificação',
            ],
            'destinatario_id' => [
                'type' => Type::id(),
                'description' => 'Informa quem deverá receber a notificação',
            ],
            'remetente_id' => [
                'type' => Type::id(),
                'description' => 'Cliente que enviou a notificação, nulo quando for enviado pelo sistema',
            ],
            'mensagem' => [
                'type' => Type::string(),
                'description' => 'Mensagem da notificação',
            ],
            'categoria' => [
                'type' => Type::string(),
                'description' => 'Tag que identifica a origem da notificação',
            ],
            'redirecionar' => [
                'type' => Type::string(),
                'description' => 'Redirecionar para essa url ou local',
            ],
            'data_visualizacao' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data em que a notificação foi visualizada',
            ],
            'data_notificacao' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de criação da notificação',
            ],
        ];
    }
}
