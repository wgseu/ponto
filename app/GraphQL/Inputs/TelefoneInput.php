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

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class TelefoneInput extends InputType
{
    protected $attributes = [
        'name' => 'Telefone',
        'description' => 'Telefones dos clientes, apenas o telefone principal deve ser único por cliente',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador do telefone',
            ],
            'cliente_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Informa o cliente que possui esse número de telefone',
            ],
            'pais_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Informa o país desse número de telefone',
            ],
            'numero' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:12'],
                'description' => 'Número de telefone com DDD',
            ],
            'operadora' => [
                'type' => Type::string(),
                'rules' => ['max:45'],
                'description' => 'Informa qual a operadora desse telefone',
            ],
            'servico' => [
                'type' => Type::string(),
                'rules' => ['max:45'],
                'description' => 'Informa qual serviço está associado à esse número, Ex: WhatsApp',
            ],
            'principal' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se o telefone é principal e exclusivo do cliente',
            ],
        ];
    }
}
