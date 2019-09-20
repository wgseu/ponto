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

use GraphQL\Type\Definition\Type;
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
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'classificacao_id' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'funcionario_id' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
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
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'descricao' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'valor' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'consolidado' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'fonte' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'numero_parcela' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'parcelas' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'frequencia' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'modo' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'automatico' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'acrescimo' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'multa' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'juros' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'formula' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'vencimento' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'numero' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'anexo_url' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'estado' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'data_calculo' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_emissao' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
        ];
    }
}
