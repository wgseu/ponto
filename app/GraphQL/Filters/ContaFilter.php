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

class ContaFilter extends InputType
{
    protected $attributes = [
        'name' => 'ContaFilter',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'classificacao_id' => [
                'type' => Type::int(),
            ],
            'funcionario_id' => [
                'type' => Type::int(),
            ],
            'conta_id' => [
                'type' => Type::int(),
            ],
            'agrupamento_id' => [
                'type' => Type::int(),
            ],
            'carteira_id' => [
                'type' => Type::int(),
            ],
            'cliente_id' => [
                'type' => Type::int(),
            ],
            'pedido_id' => [
                'type' => Type::int(),
            ],
            'tipo' => [
                'type' => GraphQL::type('ContaTipoFilter'),
            ],
            'descricao' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'valor' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'consolidado' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'fonte' => [
                'type' => GraphQL::type('ContaFonteFilter'),
            ],
            'numero_parcela' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'parcelas' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'frequencia' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'modo' => [
                'type' => GraphQL::type('ContaModoFilter'),
            ],
            'automatico' => [
                'type' => Type::boolean(),
            ],
            'acrescimo' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'multa' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'juros' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'formula' => [
                'type' => GraphQL::type('ContaFormulaFilter'),
            ],
            'vencimento' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'numero' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'anexo_url' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'estado' => [
                'type' => GraphQL::type('ContaEstadoFilter'),
            ],
            'data_calculo' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'data_emissao' => [
                'type' => GraphQL::type('DateFilter'),
            ],
        ];
    }
}
