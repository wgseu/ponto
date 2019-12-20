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

class PropriedadeInput extends InputType
{
    protected $attributes = [
        'name' => 'PropriedadeInput',
        'description' => 'Informa tamanhos de pizzas e opções de peso do produto',
    ];

    public function fields(): array
    {
        return [
            'grupo_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Grupo que possui essa propriedade como item de um pacote',
            ],
            'nome' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Nome da propriedade, Ex.: Grande, Pequena',
                'rules' => ['max:100'],
            ],
            'abreviacao' => [
                'type' => Type::string(),
                'description' => 'Abreviação do nome da propriedade, Ex.: G para Grande, P para Pequena,' .
                    ' essa abreviação fará parte do nome do produto',
                'rules' => ['max:100'],
            ],
            'imagem_url' => [
                'type' => Type::string(),
                'description' => 'Url da imagem que representa a propriedade',
                'rules' => ['max:100'],
            ],
            'imagem' => [
                'type' => Type::string(),
                'description' => 'Imagem que representa a propriedade',
                'rules' => ['max:100'],
            ],
        ];
    }
}
