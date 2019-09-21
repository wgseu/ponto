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

class LocalizacaoFilter extends InputType
{
    protected $attributes = [
        'name' => 'LocalizacaoFilter',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'cliente_id' => [
                'type' => Type::int(),
            ],
            'bairro_id' => [
                'type' => Type::int(),
            ],
            'zona_id' => [
                'type' => Type::int(),
            ],
            'cep' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'logradouro' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'numero' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'tipo' => [
                'type' => GraphQL::type('LocalizacaoTipoFilter'),
            ],
            'complemento' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'condominio' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'bloco' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'apartamento' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'referencia' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'latitude' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'longitude' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'apelido' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'data_arquivado' => [
                'type' => GraphQL::type('DateFilter'),
            ],
        ];
    }
}
