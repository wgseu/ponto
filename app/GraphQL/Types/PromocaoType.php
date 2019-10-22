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

use App\Models\Promocao;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PromocaoType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Promocao',
        'description' => 'Informa se há descontos nos produtos em determinados dias da semana, o preço pode subir ou descer e ser agendado para ser aplicado',
        'model' => Promocao::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da promoção',
            ],
            'promocao_id' => [
                'type' => Type::id(),
                'description' => 'Promoção que originou os pontos do cliente/pedido, se informado a promoção será o resgate e somente pontos gerados por ela poderão ser usados',
            ],
            'categoria_id' => [
                'type' => Type::id(),
                'description' => 'Permite fazer promoção para qualquer produto dessa categoria',
            ],
            'produto_id' => [
                'type' => Type::id(),
                'description' => 'Informa qual o produto participará da promoção de desconto ou terá acréscimo',
            ],
            'servico_id' => [
                'type' => Type::id(),
                'description' => 'Informa se essa promoção será aplicada nesse serviço',
            ],
            'bairro_id' => [
                'type' => Type::id(),
                'description' => 'Bairro que essa promoção se aplica, somente serviços',
            ],
            'zona_id' => [
                'type' => Type::id(),
                'description' => 'Zona que essa promoção se aplica, somente serviços',
            ],
            'integracao_id' => [
                'type' => Type::id(),
                'description' => 'Permite alterar o preço do produto para cada integração',
            ],
            'local' => [
                'type' => GraphQL::type('PromocaoLocal'),
                'description' => 'Local onde o preço será aplicado',
            ],
            'inicio' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Momento inicial da semana em minutos que o produto começa a sofrer alteração de preço, em evento será o unix timestamp',
            ],
            'fim' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Momento final da semana em minutos que o produto volta ao preço normal, em evento será o unix timestamp',
            ],
            'valor' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Acréscimo ou desconto aplicado ao produto ou serviço',
            ],
            'pontos' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Informa quantos pontos será ganho (Positivo) ou descontado (Negativo) na compra desse produto',
            ],
            'parcial' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se o resgate dos produtos podem ser feitos de forma parcial',
            ],
            'proibir' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se deve proibir a venda desse produto no período informado',
            ],
            'evento' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se a promoção será aplicada apenas no intervalo de data informado',
            ],
            'agendamento' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se essa promoção é um agendamento de preço, na data inicial o preço será aplicado, assim como a visibilidade do produto ou serviço será ativada ou desativada de acordo com o proibir',
            ],
            'limitar_vendas' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se deve limitar a quantidade de vendas dessa categoria, produto ou serviço',
            ],
            'funcao_vendas' => [
                'type' => Type::nonNull(GraphQL::type('PromocaoFuncaoVendas')),
                'description' => 'Informa a regra para decidir se ainda pode vender com essa promoção',
            ],
            'vendas_limite' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Quantidade de vendas que essa promoção será programada',
            ],
            'limitar_cliente' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se deve limitar a venda desse produto por cliente',
            ],
            'funcao_cliente' => [
                'type' => Type::nonNull(GraphQL::type('PromocaoFuncaoCliente')),
                'description' => 'Informa a regra para decidir se o cliente consegue comprar mais nessa promoção',
            ],
            'cliente_limite' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Quantidade de compras que o cliente será limitado a comprar',
            ],
            'ativa' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se a promoção está ativa',
            ],
            'chamada' => [
                'type' => Type::string(),
                'description' => 'Chamada para a promoção',
            ],
            'banner_url' => [
                'type' => Type::string(),
                'description' => 'Imagem promocional',
            ],
            'data_arquivado' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data em que a promoção foi arquivada',
            ],
        ];
    }
}
