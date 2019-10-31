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
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'codigo' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'categoria_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'unidade_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'setor_estoque_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'setor_preparo_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'tributacao_id' => [
                'type' => GraphQL::type('IdFilter'),
            ],
            'descricao' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'abreviacao' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'detalhes' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'quantidade_minima' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'quantidade_maxima' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'preco_venda' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'custo_medio' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'custo_producao' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'tipo' => [
                'type' => GraphQL::type('ProdutoTipoFilter'),
            ],
            'cobrar_servico' => [
                'type' => Type::boolean(),
            ],
            'divisivel' => [
                'type' => Type::boolean(),
            ],
            'pesavel' => [
                'type' => Type::boolean(),
            ],
            'tempo_preparo' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'disponivel' => [
                'type' => Type::boolean(),
            ],
            'insumo' => [
                'type' => Type::boolean(),
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
                'type' => GraphQL::type('DateFilter'),
            ],
            'data_arquivado' => [
                'type' => GraphQL::type('DateFilter'),
            ],
        ];
    }
}
