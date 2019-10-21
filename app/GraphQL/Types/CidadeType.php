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

use App\Models\Cidade;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CidadeType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Cidade',
        'description' => 'Cidade de um estado, contém bairros',
        'model' => Cidade::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Código que identifica a cidade',
            ],
            'estado_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Informa a qual estado a cidade pertence',
            ],
            'nome' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Nome da cidade, é único para cada estado',
            ],
            'cep' => [
                'type' => Type::string(),
                'description' => 'Código dos correios para identificação da cidade',
            ],
        ];
    }
}
