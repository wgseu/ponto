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

namespace App\GraphQL\Filters;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class PromocaoFilter extends InputType
{
    protected $attributes = [
        'name' => 'PromocaoFilter',
        'description' => 'Informa se há descontos nos produtos em determinados dias da semana, o preço pode subir ou descer e ser agendado para ser aplicado',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'promocao_id' => [
                'type' => Type::int(),
            ],
            'categoria_id' => [
                'type' => Type::int(),
            ],
            'produto_id' => [
                'type' => Type::int(),
            ],
            'servico_id' => [
                'type' => Type::int(),
            ],
            'bairro_id' => [
                'type' => Type::int(),
            ],
            'zona_id' => [
                'type' => Type::int(),
            ],
            'integracao_id' => [
                'type' => Type::int(),
            ],
            'local' => [
                'type' => GraphQL::type('PromocaoLocalFilter'),
            ],
            'inicio' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'fim' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'valor' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'pontos' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'parcial' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'proibir' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'evento' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'agendamento' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'limitar_vendas' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'funcao_vendas' => [
                'type' => Type::nonNull(GraphQL::type('PromocaoFuncaoVendasFilter')),
            ],
            'vendas_limite' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'limitar_cliente' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'funcao_cliente' => [
                'type' => Type::nonNull(GraphQL::type('PromocaoFuncaoClienteFilter')),
            ],
            'cliente_limite' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'ativa' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'chamada' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'banner_url' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'data_arquivado' => [
                'type' => GraphQL::type('DateFilter'),
            ],
        ];
    }
}
