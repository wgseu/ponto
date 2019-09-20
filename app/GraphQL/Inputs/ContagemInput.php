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

class ContagemInput extends InputType
{
    protected $attributes = [
        'name' => 'Contagem',
        'description' => 'Guarda a soma do estoque de cada produto por setor',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da contagem',
            ],
            'produto_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Produto que possui o estoque acumulado nesse setor',
            ],
            'setor_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Setor em que o produto está localizado',
            ],
            'quantidade' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Quantidade do produto nesse setor',
            ],
            'data_atualizacao' => [
                'type' => Type::nonNull(GraphQL::type('datetime')),
                'description' => 'Data em que a contagem foi atualizada',
            ],
        ];
    }
}
