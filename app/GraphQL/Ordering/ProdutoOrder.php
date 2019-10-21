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

class ProdutoOrder extends InputType
{
    protected $attributes = [
        'name' => 'ProdutoOrder',
        'description' => 'Informações sobre o produto, composição ou pacote',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'codigo' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'categoria_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'unidade_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'setor_estoque_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'setor_preparo_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'tributacao_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'descricao' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'abreviacao' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'detalhes' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'quantidade_minima' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'quantidade_maxima' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'preco_venda' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'custo_producao' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'tipo' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'cobrar_servico' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'divisivel' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'pesavel' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'tempo_preparo' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'disponivel' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'insumo' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'avaliacao' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'estoque' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'imagem_url' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_atualizacao' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_arquivado' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
        ];
    }
}
