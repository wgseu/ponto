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

use App\Models\Pedido;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PedidoType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Pedido',
        'description' => 'Informações do pedido de venda',
        'model' => Pedido::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Código do pedido',
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
            'prestador_id' => [
                'type' => Type::id(),
                'description' => 'Prestador que criou esse pedido',
            ],
            'cliente_id' => [
                'type' => Type::id(),
                'description' => 'Identificador do cliente do pedido',
            ],
            'cliente' => [
                'type' => GraphQL::type('Cliente'),
                'description' => 'Cliente do pedido',
            ],
            'localizacao_id' => [
                'type' => Type::id(),
                'description' => 'Endereço de entrega do pedido, se não informado na venda entrega, o' .
                    ' pedido será para viagem',
            ],
            'localizacao' => [
                'type' => GraphQL::type('Localizacao'),
                'description' => 'Endereço de entrega do pedido, se não informado na venda entrega, o' .
                    ' pedido será para viagem',
            ],
            'entrega_id' => [
                'type' => Type::id(),
                'description' => 'Informa em qual entrega esse pedido foi despachado',
            ],
            'entrega' => [
                'type' => GraphQL::type('Viagem'),
                'description' => 'Informa em qual entrega esse pedido foi despachado',
            ],
            'associacao_id' => [
                'type' => Type::id(),
                'description' => 'Informa se o pedido veio de uma integração e se está associado',
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
            'servicos' => [
                'type' => Type::float(),
                'description' => 'Valor total dos serviços desse pedido',
            ],
            'produtos' => [
                'type' => Type::float(),
                'description' => 'Valor total dos produtos do pedido sem a comissão',
            ],
            'comissao' => [
                'type' => Type::float(),
                'description' => 'Valor total da comissão desse pedido',
            ],
            'subtotal' => [
                'type' => Type::float(),
                'description' => 'Subtotal do pedido sem os descontos',
            ],
            'descontos' => [
                'type' => Type::float(),
                'description' => 'Total de descontos realizado nesse pedido',
            ],
            'total' => [
                'type' => Type::float(),
                'description' => 'Total do pedido já com descontos',
            ],
            'pago' => [
                'type' => Type::float(),
                'description' => 'Valor já pago do pedido',
            ],
            'troco' => [
                'type' => Type::float(),
                'description' => 'Troco do cliente',
            ],
            'lancado' => [
                'type' => Type::float(),
                'description' => 'Valor lançado para pagar, mas não foi pago ainda',
            ],
            'pessoas' => [
                'type' => Type::int(),
                'description' => 'Informa quantas pessoas estão na mesa',
            ],
            'cpf' => [
                'type' => Type::string(),
                'description' => 'CPF/CNPJ na nota',
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'E-mail para envio do XML e Danfe',
            ],
            'descricao' => [
                'type' => Type::string(),
                'description' => 'Detalhes da reserva ou do pedido',
            ],
            'fechador_id' => [
                'type' => Type::id(),
                'description' => 'Informa quem fechou o pedido e imprimiu a conta',
            ],
            'data_impressao' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de impressão da conta do cliente',
            ],
            'motivo' => [
                'type' => Type::string(),
                'description' => 'Informa o motivo do cancelamento',
            ],
            'data_entrega' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data e hora que o pedido foi entregue ao cliente',
            ],
            'data_agendamento' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de agendamento do pedido',
            ],
            'data_conclusao' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de finalização do pedido',
            ],
            'data_criacao' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de criação do pedido',
            ],
        ];
    }
}
