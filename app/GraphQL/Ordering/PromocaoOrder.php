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

namespace App\GraphQL\Ordering;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class PromocaoOrder extends InputType
{
    protected $attributes = [
        'name' => 'PromocaoOrder',
        'description' => 'Informa se há descontos nos produtos em determinados dias da semana, o preço pode subir ou descer e ser agendado para ser aplicado',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'promocao_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'categoria_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'produto_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'servico_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'bairro_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'zona_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'integracao_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'local' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'inicio' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'fim' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'valor' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'pontos' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'parcial' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'proibir' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'evento' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'agendamento' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'limitar_vendas' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'funcao_vendas' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'vendas_limite' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'limitar_cliente' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'funcao_cliente' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'cliente_limite' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'ativa' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'chamada' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'banner_url' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_arquivado' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
        ];
    }
}
