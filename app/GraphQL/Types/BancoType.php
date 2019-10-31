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

use App\Models\Banco;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class BancoType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Banco',
        'description' => 'Bancos disponíveis no país',
        'model' => Banco::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador do banco',
            ],
            'numero' => [
                'type' => Type::string(),
                'description' => 'Número do banco',
            ],
            'fantasia' => [
                'type' => Type::string(),
                'description' => 'Nome fantasia do banco',
            ],
            'razao_social' => [
                'type' => Type::string(),
                'description' => 'Razão social do banco',
            ],
            'agencia_mascara' => [
                'type' => Type::string(),
                'description' => 'Mascara para formatação do número da agência',
            ],
            'conta_mascara' => [
                'type' => Type::string(),
                'description' => 'Máscara para formatação do número da conta',
            ],
        ];
    }
}
