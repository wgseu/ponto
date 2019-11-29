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

use App\Models\Pagamento;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PagamentoType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Pagamento',
        'description' => 'Pagamentos de contas e pedidos',
        'model' => Pagamento::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador do pagamento',
            ],
            'carteira_id' => [
                'type' => Type::id(),
                'description' => 'Carteira de destino do valor',
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
            'agrupamento_id' => [
                'type' => Type::id(),
                'description' => 'Permite antecipar recebimentos de cartões, um pagamento agrupado é' .
                    ' internamente tratado como desativado',
            ],
            'movimentacao_id' => [
                'type' => Type::id(),
                'description' => 'Movimentação do caixa quando for pagamento de pedido ou quando a conta' .
                    ' for paga do caixa',
            ],
            'funcionario_id' => [
                'type' => Type::id(),
                'description' => 'Funcionário que lançou o pagamento no sistema',
            ],
            'forma_id' => [
                'type' => Type::id(),
                'description' => 'Forma da pagamento do pedido',
            ],
            'forma' => [
                'type' => GraphQL::type('Forma'),
                'description' => 'Forma da pagamento do pedido',
            ],
            'pedido_id' => [
                'type' => Type::id(),
                'description' => 'Pedido que foi pago',
            ],
            'conta_id' => [
                'type' => Type::id(),
                'description' => 'Conta que foi paga/recebida',
            ],
            'cartao_id' => [
                'type' => Type::id(),
                'description' => 'Cartão em que foi pago, para forma de pagamento em cartão',
            ],
            'cartao' => [
                'type' => GraphQL::type('Cartao'),
                'description' => 'Cartão em que foi pago, para forma de pagamento em cartão',
            ],
            'cheque_id' => [
                'type' => Type::id(),
                'description' => 'Cheque em que foi pago',
            ],
            'crediario_id' => [
                'type' => Type::id(),
                'description' => 'Conta que foi utilizada como pagamento do pedido',
            ],
            'credito_id' => [
                'type' => Type::id(),
                'description' => 'Crédito que foi utilizado para pagar o pedido',
            ],
            'valor' => [
                'type' => Type::float(),
                'description' => 'Valor pago ou recebido na moeda informada no momento do recebimento',
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
                'type' => Type::float(),
                'description' => 'Valor lançado para pagamento do pedido ou conta na moeda local do país',
            ],
            'codigo' => [
                'type' => Type::string(),
                'description' => 'Código do pagamento, usado em transações online',
            ],
            'detalhes' => [
                'type' => Type::string(),
                'description' => 'Detalhes do pagamento',
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
            'data_lancamento' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data e hora do lançamento do pagamento',
            ],
        ];
    }
}
