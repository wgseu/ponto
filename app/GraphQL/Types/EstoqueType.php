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

use App\Models\Estoque;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class EstoqueType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Estoque',
        'description' => 'Estoque de produtos por setor',
        'model' => Estoque::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da entrada no estoque',
            ],
            'producao_id' => [
                'type' => Type::id(),
                'description' => 'Informa o que foi produzido através dessa saida de estoque',
            ],
            'produto_id' => [
                'type' => Type::id(),
                'description' => 'Produto que entrou no estoque',
            ],
            'requisito_id' => [
                'type' => Type::id(),
                'description' => 'Informa de qual compra originou essa entrada em estoque',
            ],
            'transacao_id' => [
                'type' => Type::id(),
                'description' => 'Identificador do item que gerou a saída desse produto do estoque',
            ],
            'fornecedor_id' => [
                'type' => Type::id(),
                'description' => 'Fornecedor do produto',
            ],
            'setor_id' => [
                'type' => Type::id(),
                'description' => 'Setor de onde o produto foi inserido ou retirado',
            ],
            'prestador_id' => [
                'type' => Type::id(),
                'description' => 'Prestador que inseriu/retirou o produto do estoque',
            ],
            'quantidade' => [
                'type' => Type::float(),
                'description' => 'Quantidade do mesmo produto inserido no estoque',
            ],
            'preco_compra' => [
                'type' => Type::float(),
                'description' => 'Preço de compra do produto',
            ],
            'lote' => [
                'type' => Type::string(),
                'description' => 'Lote de produção do produto comprado',
            ],
            'fabricacao' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de fabricação do produto',
            ],
            'vencimento' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de vencimento do produto',
            ],
            'detalhes' => [
                'type' => Type::string(),
                'description' => 'Detalhes da inserção ou retirada do estoque',
            ],
            'cancelado' => [
                'type' => Type::boolean(),
                'description' => 'Informa a entrada ou saída do estoque foi cancelada',
            ],
            'data_movimento' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de entrada ou saída do produto do estoque',
            ],
        ];
    }
}
