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

namespace App\GraphQL\Ordering;

use Rebing\GraphQL\Support\InputType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ChequeOrder extends InputType
{
    protected $attributes = [
        'name' => 'ChequeOrder',
        'description' => 'Folha de cheque lançado como pagamento',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'cliente_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'banco_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'agencia' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'conta' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'numero' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'valor' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'vencimento' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'cancelado' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'recolhimento' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_cadastro' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
        ];
    }
}
