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

use App\Models\Movimentacao;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class MovimentacaoType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Movimentacao',
        'description' => 'Movimentação do caixa, permite abrir diversos caixas na conta de operadores',
        'model' => Movimentacao::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Código da movimentação do caixa',
            ],
            'sessao_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Sessão do dia, permite abrir vários caixas no mesmo dia com o mesmo código da sessão',
            ],
            'caixa_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Caixa a qual pertence essa movimentação',
            ],
            'aberta' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se o caixa está aberto',
            ],
            'iniciador_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Funcionário que abriu o caixa',
            ],
            'fechador_id' => [
                'type' => Type::int(),
                'description' => 'Funcionário que fechou o caixa',
            ],
            'data_fechamento' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de fechamento do caixa',
            ],
            'data_abertura' => [
                'type' => Type::nonNull(GraphQL::type('DateTime')),
                'description' => 'Data de abertura do caixa',
            ],
        ];
    }
}
