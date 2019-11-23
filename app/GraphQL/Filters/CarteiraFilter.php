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

class CarteiraFilter extends InputType
{
    protected $attributes = [
        'name' => 'CarteiraFilter',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'tipo' => [
                'type' => GraphQL::type('CarteiraTipoFilter'),
            ],
            'carteira_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'banco_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'descricao' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'conta' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'agencia' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'saldo' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'lancado' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'transacao' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'limite' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'token' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'ambiente' => [
                'type' => GraphQL::type('CarteiraAmbienteFilter'),
            ],
            'logo_url' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'cor' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'ativa' => [
                'type' => Type::boolean(),
            ],
            'data_desativada' => [
                'type' => GraphQL::type('DateFilter'),
            ],
        ];
    }
}
