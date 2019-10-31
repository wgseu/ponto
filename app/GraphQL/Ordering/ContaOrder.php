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

class ContaOrder extends InputType
{
    protected $attributes = [
        'name' => 'ContaOrder',
        'description' => 'Contas a pagar e ou receber',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'classificacao_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'funcionario_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'conta_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'agrupamento_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'carteira_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'cliente_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'pedido_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'tipo' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'descricao' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'valor' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'consolidado' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'fonte' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'numero_parcela' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'parcelas' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'frequencia' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'modo' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'automatico' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'acrescimo' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'multa' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'juros' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'formula' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'vencimento' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'numero' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'anexo_url' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'estado' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_calculo' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_emissao' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
        ];
    }
}
