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

class RequisitoInput extends InputType
{
    protected $attributes = [
        'name' => 'RequisitoInput',
        'description' => 'Informa os produtos da lista de compras',
    ];

    public function fields(): array
    {
        return [
            'lista_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Lista de compra desse produto',
            ],
            'produto_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Produto que deve ser comprado',
            ],
            'compra_id' => [
                'type' => Type::id(),
                'description' => 'Informa em qual fornecedor foi realizado a compra desse produto',
            ],
            'fornecedor_id' => [
                'type' => Type::id(),
                'description' => 'Fornecedor em que deve ser consultado ou realizado as compras dos' .
                    ' produtos, pode ser alterado no momento da compra',
            ],
            'quantidade' => [
                'type' => Type::float(),
                'description' => 'Quantidade de produtos que deve ser comprado',
            ],
            'comprado' => [
                'type' => Type::float(),
                'description' => 'Informa quantos produtos já foram comprados',
            ],
            'preco_maximo' => [
                'type' => Type::float(),
                'description' => 'Preço máximo que deve ser pago na compra desse produto',
            ],
            'preco' => [
                'type' => Type::float(),
                'description' => 'Preço em que o produto foi comprado da última vez ou o novo preço',
            ],
            'observacoes' => [
                'type' => Type::string(),
                'description' => 'Detalhes na compra desse produto',
                'rules' => ['max:100'],
            ],
            'data_recolhimento' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Informa o momento do recolhimento da mercadoria na pratileira',
            ],
        ];
    }
}
