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

class PedidoInput extends InputType
{
    protected $attributes = [
        'name' => 'PedidoInput',
        'description' => 'Informações do pedido de venda',
    ];

    public function fields(): array
    {
        return [
            'itens' => [
                'type' => Type::listOf(GraphQL::type('ItemUpdateInput')),
                'description' => 'Itens do pedido a serem adicionados ou alterados',
            ],
            'pagamentos' => [
                'type' => Type::listOf(GraphQL::type('PagamentoUpdateInput')),
                'description' => 'Pagamentos do pedido a serem adicionados ou alterados',
            ],
            'cupons' => [
                'type' => Type::listOf(GraphQL::type('CupomPedidoInput')),
                'description' => 'Cupons que serão usados no pedido',
            ],
            'pedido_id' => [
                'type' => Type::id(),
                'description' => 'Informa o pedido da mesa / comanda principal quando as mesas / comandas' .
                    ' forem agrupadas',
            ],
            'mesa_id' => [
                'type' => Type::id(),
                'description' => 'Identificador da mesa, único quando o pedido não está fechado',
            ],
            'comanda_id' => [
                'type' => Type::id(),
                'description' => 'Identificador da comanda, único quando o pedido não está fechado',
            ],
            'sessao_id' => [
                'type' => Type::id(),
                'description' => 'Identificador da sessão de vendas',
            ],
            'cliente_id' => [
                'type' => Type::id(),
                'description' => 'Identificador do cliente do pedido',
            ],
            'localizacao_id' => [
                'type' => Type::id(),
                'description' => 'Endereço de entrega do pedido, se não informado na venda entrega, o' .
                    ' pedido será para viagem',
            ],
            'entrega_id' => [
                'type' => Type::id(),
                'description' => 'Informa em qual entrega esse pedido foi despachado',
            ],
            'entrega' => [
                'type' => GraphQL::type('ViagemUpdateInput'),
                'description' => 'Informa em qual entrega esse pedido foi despachado',
            ],
            'tipo' => [
                'type' => GraphQL::type('PedidoTipo'),
                'description' => 'Tipo de venda',
            ],
            'estado' => [
                'type' => GraphQL::type('PedidoEstado'),
                'description' => 'Estado do pedido, Agendado: O pedido deve ser processado na data de' .
                    ' agendamento. Aberto: O pedido deve ser processado. Entrega: O pedido' .
                    ' saiu para entrega. Fechado: O cliente pediu a conta e está pronto para' .
                    ' pagar. Concluído: O pedido foi pago e concluído, Cancelado: O pedido foi' .
                    ' cancelado com os itens e pagamentos',
            ],
            'descontos' => [
                'type' => Type::float(),
                'description' => 'Total de descontos nesse pedido',
            ],
            'pessoas' => [
                'type' => Type::int(),
                'description' => 'Informa quantas pessoas estão na mesa',
            ],
            'cpf' => [
                'type' => Type::string(),
                'description' => 'CPF/CNPJ na nota',
                'rules' => ['max:20'],
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'E-mail para envio do XML e Danfe',
                'rules' => ['max:100'],
            ],
            'descricao' => [
                'type' => Type::string(),
                'description' => 'Detalhes da reserva ou do pedido',
                'rules' => ['max:255'],
            ],
            'motivo' => [
                'type' => Type::string(),
                'description' => 'Informa o motivo do cancelamento',
                'rules' => ['max:200'],
            ],
            'data_agendamento' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de agendamento do pedido',
            ],
        ];
    }
}
