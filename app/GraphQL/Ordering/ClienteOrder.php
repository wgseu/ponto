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

class ClienteOrder extends InputType
{
    protected $attributes = [
        'name' => 'ClienteOrder',
        'description' => 'Informações de cliente físico ou jurídico. Clientes, empresas, funcionários, fornecedores e parceiros são cadastrados aqui',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'empresa_id' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'login' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'senha' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'nome' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'sobrenome' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'genero' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'cpf' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'rg' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'im' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'email' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_nascimento' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'slogan' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'status' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'secreto' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'limite_compra' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'instagram' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'facebook_url' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'twitter' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'linkedin_url' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'imagem_url' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'linguagem' => [
                'type' => GraphQL::type('OrderByEnum'),
            ],
            'data_atualizacao' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
            'data_cadastro' => [
                'type' => Type::nonNull(GraphQL::type('OrderByEnum')),
            ],
        ];
    }
}
