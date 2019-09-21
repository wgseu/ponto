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

class ClienteFilter extends InputType
{
    protected $attributes = [
        'name' => 'ClienteFilter',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
            ],
            'tipo' => [
                'type' => GraphQL::type('ClienteTipoFilter'),
            ],
            'empresa_id' => [
                'type' => Type::int(),
            ],
            'login' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'senha' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'nome' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'sobrenome' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'genero' => [
                'type' => GraphQL::type('ClienteGeneroFilter'),
            ],
            'cpf' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'rg' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'im' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'email' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'data_nascimento' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'slogan' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'status' => [
                'type' => GraphQL::type('ClienteStatusFilter'),
            ],
            'secreto' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'limite_compra' => [
                'type' => GraphQL::type('NumberFilter'),
            ],
            'instagram' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'facebook_url' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'twitter' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'linkedin_url' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'imagem_url' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'linguagem' => [
                'type' => GraphQL::type('StringFilter'),
            ],
            'data_atualizacao' => [
                'type' => GraphQL::type('DateFilter'),
            ],
            'data_cadastro' => [
                'type' => GraphQL::type('DateFilter'),
            ],
        ];
    }
}
