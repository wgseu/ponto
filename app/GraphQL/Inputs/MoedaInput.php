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

class MoedaInput extends InputType
{
    protected $attributes = [
        'name' => 'MoedaInput',
        'description' => 'Moedas financeiras de um país',
    ];

    public function fields(): array
    {
        return [
            'nome' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Nome da moeda',
                'rules' => ['max:45'],
            ],
            'simbolo' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Símbolo da moeda, Ex.: R$, $',
                'rules' => ['max:10'],
            ],
            'codigo' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Código internacional da moeda, Ex.: USD, BRL',
                'rules' => ['max:45'],
            ],
            'divisao' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Informa o número fracionário para determinar a quantidade de casas' .
                    ' decimais, Ex: 100 para 0,00. 10 para 0,0',
            ],
            'fracao' => [
                'type' => Type::string(),
                'description' => 'Informa o nome da fração, Ex.: Centavo',
                'rules' => ['max:45'],
            ],
            'formato' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Formado de exibição do valor, Ex: $ %s, para $ 3,00',
                'rules' => ['max:45'],
            ],
            'conversao' => [
                'type' => Type::float(),
                'description' => 'Multiplicador para conversão para a moeda principal',
            ],
            'ativa' => [
                'type' => Type::boolean(),
                'description' => 'Informa se a moeda é recebida pela empresa, a moeda do país mesmo' .
                    ' desativada sempre é aceita',
            ],
        ];
    }
}
