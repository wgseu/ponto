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

class CatalogoInput extends InputType
{
    protected $attributes = [
        'name' => 'CatalogoInput',
        'description' => 'Informa a lista de produtos disponíveis nos fornecedores',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador do catálogo',
            ],
            'produto_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Produto consultado',
            ],
            'fornecedor_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Fornecedor que possui o produto à venda',
            ],
            'preco_compra' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Preço a qual o produto foi comprado da última vez',
            ],
            'preco_venda' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Preço de venda do produto pelo fornecedor na última consulta',
            ],
            'quantidade_minima' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Quantidade mínima que o fornecedor vende',
            ],
            'estoque' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Quantidade em estoque do produto no fornecedor',
            ],
            'limitado' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se a quantidade de estoque é limitada',
            ],
            'conteudo' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Informa o conteúdo do produto como é comprado, Ex.: 5UN no mesmo pacote',
            ],
            'data_consulta' => [
                'type' => GraphQL::type('datetime'),
                'description' => 'Última data de consulta do preço do produto',
            ],
            'data_parada' => [
                'type' => GraphQL::type('datetime'),
                'description' => 'Data em que o produto deixou de ser vendido pelo fornecedor',
            ],
        ];
    }
}
