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

class PagamentoInput extends InputType
{
    protected $attributes = [
        'name' => 'PagamentoInput',
        'description' => 'Pagamentos de contas e pedidos',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador do pagamento no banco',
            ],
            'moeda_id' => [
                'type' => Type::id(),
                'description' => 'Informa em qual moeda está o valor informado',
            ],
            'pagamento_id' => [
                'type' => Type::id(),
                'description' => 'Informa o pagamento principal ou primeira parcela, o valor lançado é' .
                    ' zero para os pagamentos filhos, restante de antecipação e taxas são' .
                    ' filhos do valor antecipado',
            ],
            'movimentacao_id' => [
                'type' => Type::id(),
                'description' => 'Movimentação do caixa quando for pagamento de pedido ou quando a conta' .
                    ' for paga do caixa',
            ],
            'forma_id' => [
                'type' => Type::id(),
                'description' => 'Forma da pagamento do pedido',
            ],
            'conta_id' => [
                'type' => Type::id(),
                'description' => 'Conta que foi paga/recebida',
            ],
            'cartao_id' => [
                'type' => Type::id(),
                'description' => 'Cartão em que foi pago, para forma de pagamento em cartão',
            ],
            'cheque' => [
                'type' => GraphQL::type('ChequeUpdateInput'),
                'description' => 'Cheque em que foi pago',
            ],
            'crediario' => [
                'type' => GraphQL::type('ContaUpdateInput'),
                'description' => 'Conta que foi utilizada como pagamento do pedido',
            ],
            'credito' => [
                'type' => GraphQL::type('CreditoUpdateInput'),
                'description' => 'Crédito que foi utilizado para pagar o pedido',
            ],
            'numero_parcela' => [
                'type' => Type::int(),
                'description' => 'Informa qual o número da parcela para este pagamento',
            ],
            'parcelas' => [
                'type' => Type::int(),
                'description' => 'Quantidade de parcelas desse pagamento',
            ],
            'lancado' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Valor lançado para pagamento do pedido ou conta na moeda local do país',
            ],
            'codigo' => [
                'type' => Type::string(),
                'description' => 'Código do pagamento, usado em transações online',
                'rules' => ['max:100'],
            ],
            'detalhes' => [
                'type' => Type::string(),
                'description' => 'Detalhes do pagamento',
                'rules' => ['max:200'],
            ],
            'estado' => [
                'type' => GraphQL::type('PagamentoEstado'),
                'description' => 'Informa qual o andamento do processo de pagamento',
            ],
            'data_pagamento' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de pagamento',
            ],
            'data_compensacao' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de compensação do pagamento',
            ],
        ];
    }
}
