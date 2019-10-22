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

use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class ImpressoraInput extends InputType
{
    protected $attributes = [
        'name' => 'ImpressoraInput',
        'description' => 'Impressora para impressão de serviços e contas',
    ];

    public function fields(): array
    {
        return [
            'dispositivo_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Dispositivo que contém a impressora',
            ],
            'setor_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Setor de impressão',
            ],
            'nome' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Nome da impressora instalada no sistema operacional',
                'rules' => ['max:100'],
            ],
            'modelo' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Informa qual conjunto de comandos deve ser utilizado',
                'rules' => ['max:45'],
            ],
            'modo' => [
                'type' => GraphQL::type('ImpressoraModo'),
                'description' => 'Modo de impressão',
            ],
            'opcoes' => [
                'type' => Type::string(),
                'description' => 'Opções da impressora, Ex.: Cortar papel, Acionar gaveta e outros',
                'rules' => ['max:65535'],
            ],
            'colunas' => [
                'type' => Type::int(),
                'description' => 'Quantidade de colunas do cupom',
            ],
            'avanco' => [
                'type' => Type::int(),
                'description' => 'Quantidade de linhas para avanço do papel',
            ],
        ];
    }
}
