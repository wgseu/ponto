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

class ProdutoFilter extends InputType
{
    protected $attributes = [
        'name' => 'ProdutoFilter',
        'description' => 'Informações sobre o produto, composição ou pacote',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'codigo' => [
                'type' => Type::nonNull(GraphQL::type('StringFilter')),
            ],
            'categoria_id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'unidade_id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'setor_estoque_id' => [
                'type' => Type::int(),
            ],
            'setor_preparo_id' => [
                'type' => Type::int(),
            ],
            'tributacao_id' => [
                'type' => Type::int(),
            ],
            'descricao' => [
                'type' => Type::nonNull(GraphQL::type('StringFilter')),
            ],
            'abreviacao' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'detalhes' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'quantidade_minima' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'quantidade_maxima' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'preco_venda' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'custo_producao' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('ProdutoTipoFilter')),
            ],
            'cobrar_servico' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'divisivel' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'pesavel' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'tempo_preparo' => [
                'type' => Type::nonNull(GraphQL::type('NumberFilter')),
            ],
            'disponivel' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'insumo' => [
                'type' => Type::nonNull(Type::boolean()),
            ],
            'avaliacao' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'estoque' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'imagem_url' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'data_atualizacao' => [
                'type' => Type::nonNull(GraphQL::type('DateFilter')),
            ],
            'data_arquivado' => [
                'type' => GraphQL::type('DateFilter'),
            ],
        ];
    }
}
