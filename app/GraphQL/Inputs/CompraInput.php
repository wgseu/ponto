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

class CompraInput extends InputType
{
    protected $attributes = [
        'name' => 'CompraInput',
        'description' => 'Compras realizadas em uma lista num determinado fornecedor',
    ];

    public function fields(): array
    {
        return [
            'numero' => [
                'type' => Type::string(),
                'description' => 'Informa o número fiscal da compra',
                'rules' => ['max:100'],
            ],
            'comprador_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Informa o funcionário que comprou os produtos da lista',
            ],
            'fornecedor_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Fornecedor em que os produtos foram compras',
            ],
            'conta_id' => [
                'type' => Type::id(),
                'description' => 'Conta que foi gerada para essa compra',
            ],
            'documento_url' => [
                'type' => Type::string(),
                'description' => 'Informa o nome do documento no servidor do sistema',
                'rules' => ['max:200'],
            ],
            'documento' => [
                'type' => Type::string(),
                'description' => 'Base64 do documento a ser alterado/cadastrado',
            ],
            'data_compra' => [
                'type' => Type::nonNull(GraphQL::type('DateTime')),
                'description' => 'Informa da data de finalização da compra',
            ],
        ];
    }
}
