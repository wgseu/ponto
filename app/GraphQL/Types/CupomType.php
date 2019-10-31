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

use App\Models\Cupom;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CupomType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Cupom',
        'description' => 'Informa os cupons de descontos e seus usos',
        'model' => Cupom::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador do cupom',
            ],
            'cupom_id' => [
                'type' => Type::id(),
                'description' => 'Informa de qual cupom foi usado',
            ],
            'pedido_id' => [
                'type' => Type::id(),
                'description' => 'Informa qual pedido usou este cupom',
            ],
            'cliente_id' => [
                'type' => Type::id(),
                'description' => 'Informa o cliente que possui e pode usar esse cupom',
            ],
            'codigo' => [
                'type' => Type::string(),
                'description' => 'Código para uso do cupom',
            ],
            'quantidade' => [
                'type' => Type::int(),
                'description' => 'Quantidade de cupons disponíveis ou usados',
            ],
            'tipo_desconto' => [
                'type' => GraphQL::type('CupomTipoDesconto'),
                'description' => 'Informa se o desconto será por valor ou porcentagem',
            ],
            'valor' => [
                'type' => Type::float(),
                'description' => 'Valor do desconto que será aplicado no pedido',
            ],
            'porcentagem' => [
                'type' => Type::float(),
                'description' => 'Porcentagem de desconto do pedido',
            ],
            'incluir_servicos' => [
                'type' => Type::boolean(),
                'description' => 'Informa se o cupom também se aplica nos serviços',
            ],
            'limitar_pedidos' => [
                'type' => Type::boolean(),
                'description' => 'Informa se deve limitar o cupom pela quantidade de pedidos válidos do' .
                    ' cliente',
            ],
            'funcao_pedidos' => [
                'type' => GraphQL::type('CupomFuncaoPedidos'),
                'description' => 'Informa a regra para decidir se a quantidade de pedidos permite usar' .
                    ' esse cupom',
            ],
            'pedidos_limite' => [
                'type' => Type::int(),
                'description' => 'Quantidade de pedidos válidos que permite usar esse cupom',
            ],
            'limitar_valor' => [
                'type' => Type::boolean(),
                'description' => 'Informa se deve limitar o uso do cupom pelo valor do pedido',
            ],
            'funcao_valor' => [
                'type' => GraphQL::type('CupomFuncaoValor'),
                'description' => 'Informa a regra para decidir se o valor do pedido permite usar esse' .
                    ' cupom',
            ],
            'valor_limite' => [
                'type' => Type::float(),
                'description' => 'Valor do pedido com os serviços que permite usar esse cupom',
            ],
            'validade' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Validade do cupom',
            ],
            'data_registro' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de registro do cupom ou do uso',
            ],
        ];
    }
}
