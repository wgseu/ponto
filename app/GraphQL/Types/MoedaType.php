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

use App\Models\Moeda;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class MoedaType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Moeda',
        'description' => 'Moedas financeiras de um país',
        'model' => Moeda::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da moeda',
            ],
            'nome' => [
                'type' => Type::string(),
                'description' => 'Nome da moeda',
            ],
            'simbolo' => [
                'type' => Type::string(),
                'description' => 'Símbolo da moeda, Ex.: R$, $',
            ],
            'codigo' => [
                'type' => Type::string(),
                'description' => 'Código internacional da moeda, Ex.: USD, BRL',
            ],
            'divisao' => [
                'type' => Type::int(),
                'description' => 'Informa o número fracionário para determinar a quantidade de casas' .
                    ' decimais, Ex: 100 para 0,00. 10 para 0,0',
            ],
            'fracao' => [
                'type' => Type::string(),
                'description' => 'Informa o nome da fração, Ex.: Centavo',
            ],
            'formato' => [
                'type' => Type::string(),
                'description' => 'Formado de exibição do valor, Ex: $ %s, para $ 3,00',
            ],
            'conversao' => [
                'type' => Type::float(),
                'description' => 'Multiplicador para conversão para a moeda principal',
            ],
            'data_atualizacao' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data da última atualização do fator de conversão',
            ],
            'ativa' => [
                'type' => Type::boolean(),
                'description' => 'Informa se a moeda é recebida pela empresa, a moeda do país mesmo' .
                    ' desativada sempre é aceita',
            ],
        ];
    }
}
