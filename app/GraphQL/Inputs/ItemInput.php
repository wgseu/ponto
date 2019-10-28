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

class ItemInput extends InputType
{
    protected $attributes = [
        'name' => 'ItemInput',
        'description' => 'Produtos, taxas e serviços do pedido, a alteração do estado permite o' .
            ' controle de produção',
    ];

    public function fields(): array
    {
        return [
            'pedido_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Pedido a qual pertence esse item',
            ],
            'prestador_id' => [
                'type' => Type::id(),
                'description' => 'Prestador que lançou esse item no pedido',
            ],
            'produto_id' => [
                'type' => Type::id(),
                'description' => 'Produto vendido',
            ],
            'servico_id' => [
                'type' => Type::id(),
                'description' => 'Serviço cobrado ou taxa',
            ],
            'item_id' => [
                'type' => Type::id(),
                'description' => 'Pacote em que esse item faz parte',
            ],
            'pagamento_id' => [
                'type' => Type::id(),
                'description' => 'Informa se esse item foi pago e qual foi o lançamento',
            ],
            'descricao' => [
                'type' => Type::string(),
                'description' => 'Sobrescreve a descrição do produto na exibição',
                'rules' => ['max:200'],
            ],
            'composicao' => [
                'type' => Type::string(),
                'description' => 'Informa a composição escolhida',
                'rules' => ['max:65535'],
            ],
            'preco' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Preço do produto já com desconto',
            ],
            'quantidade' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Quantidade de itens vendidos',
            ],
            'subtotal' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Subtotal do item sem comissão',
            ],
            'comissao' => [
                'type' => Type::float(),
                'description' => 'Valor total de comissão cobrada nesse item da venda',
            ],
            'total' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Total a pagar do item com a comissão',
            ],
            'preco_venda' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Preço de normal do produto no momento da venda',
            ],
            'preco_compra' => [
                'type' => Type::float(),
                'description' => 'Preço de compra do produto calculado automaticamente na hora da venda',
            ],
            'detalhes' => [
                'type' => Type::string(),
                'description' => 'Observações do item pedido, Ex.: bem gelado, mal passado',
                'rules' => ['max:255'],
            ],
            'estado' => [
                'type' => GraphQL::type('ItemEstado'),
                'description' => 'Estado de preparo e envio do produto',
            ],
            'cancelado' => [
                'type' => Type::boolean(),
                'description' => 'Informa se o item foi cancelado',
            ],
            'motivo' => [
                'type' => Type::string(),
                'description' => 'Informa o motivo do item ser cancelado',
                'rules' => ['max:200'],
            ],
            'desperdicado' => [
                'type' => Type::boolean(),
                'description' => 'Informa se o item foi cancelado por conta de desperdício',
            ],
            'data_processamento' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data do processamento do item',
            ],
        ];
    }
}
