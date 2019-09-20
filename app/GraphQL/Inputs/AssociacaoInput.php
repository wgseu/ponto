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

class AssociacaoInput extends InputType
{
    protected $attributes = [
        'name' => 'Associacao',
        'description' => 'Lista de pedidos que não foram integrados ainda e devem ser associados ao sistema',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da associação no banco de dados',
            ],
            'integracao_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Integração a qual essa associação de pedido deve ser realizada',
            ],
            'entrega_id' => [
                'type' => Type::int(),
                'description' => 'Entrega que foi realizada',
            ],
            'codigo' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:50'],
                'description' => 'Código curto do pedido vindo da plataforma',
            ],
            'cliente' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:255'],
                'description' => 'Nome do cliente que fez o pedido',
            ],
            'chave' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:100'],
                'description' => 'Dado chave do cliente, esperado telefone, e-mail ou CPF',
            ],
            'pedido' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:65535'],
                'description' => 'Pedido no formato JSON para exibição na lista de pedidos e posterior integração',
            ],
            'endereco' => [
                'type' => Type::string(),
                'rules' => ['max:255'],
                'description' => 'Endereço para ser entregue o pedido, nulo para o cliente vir buscar no restaurante',
            ],
            'quantidade' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Quantidade de produtos no pedido',
            ],
            'servicos' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Total dos serviços, geralmente só taxa de entrega',
            ],
            'produtos' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Total dos produtos',
            ],
            'descontos' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Total dos descontos',
            ],
            'pago' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Total que foi pago incluindo o troco',
            ],
            'status' => [
                'type' => Type::nonNull(GraphQL::type('AssociacaoStatusEnum')),
                'description' => 'Status do pedido que não foi integrado ainda',
            ],
            'motivo' => [
                'type' => Type::string(),
                'rules' => ['max:200'],
                'description' => 'Informa o motivo do cancelamento',
            ],
            'mensagem' => [
                'type' => Type::string(),
                'rules' => ['max:255'],
                'description' => 'Mensagem de erro que foi gerada ao tentar integrar automaticamente',
            ],
            'sincronizado' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se a associação já foi sincronizada com a plataforma',
            ],
            'integrado' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se a associação já foi integrada no sistema',
            ],
            'data_confirmacao' => [
                'type' => GraphQL::type('datetime'),
                'description' => 'Data e hora que o pedido foi confirmado e impresso na produção',
            ],
            'data_pedido' => [
                'type' => Type::nonNull(GraphQL::type('datetime')),
                'description' => 'Data e hora que o pedido foi criado na plataforma que o gerou',
            ],
        ];
    }
}
