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

class TributacaoInput extends InputType
{
    protected $attributes = [
        'name' => 'TributacaoInput',
        'description' => 'Informação tributária dos produtos',
    ];

    public function fields(): array
    {
        return [
            'ncm' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Código NCM (Nomenclatura Comum do Mercosul) do produto',
                'rules' => ['max:10'],
            ],
            'cest' => [
                'type' => Type::string(),
                'description' => 'Código CEST do produto (Opcional)',
                'rules' => ['max:20'],
            ],
            'origem_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Origem do produto',
            ],
            'operacao_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'CFOP do produto',
            ],
            'imposto_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Imposto do produto',
            ],
        ];
    }
}
