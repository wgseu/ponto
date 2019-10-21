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

use App\Models\Endereco;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class EnderecoType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Endereco',
        'description' => 'Endereços de ruas e avenidas com informação de CEP',
        'model' => Endereco::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador do endereço',
            ],
            'cidade_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Cidade a qual o endereço pertence',
            ],
            'bairro_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Bairro a qual o endereço está localizado',
            ],
            'logradouro' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Nome da rua ou avenida',
            ],
            'cep' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Código dos correios para identificar a rua ou avenida',
            ],
        ];
    }
}
