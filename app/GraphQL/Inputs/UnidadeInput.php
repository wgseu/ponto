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

class UnidadeInput extends InputType
{
    protected $attributes = [
        'name' => 'UnidadeInput',
        'description' => 'Unidades de medidas aplicadas aos produtos',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da unidade',
            ],
            'nome' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:45'],
                'description' => 'Nome da unidade de medida, Ex.: Grama, Quilo',
            ],
            'descricao' => [
                'type' => Type::string(),
                'rules' => ['max:45'],
                'description' => 'Detalhes sobre a unidade de medida',
            ],
            'sigla' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:10'],
                'description' => 'Sigla da unidade de medida, Ex.: UN, L, g',
            ],
        ];
    }
}
