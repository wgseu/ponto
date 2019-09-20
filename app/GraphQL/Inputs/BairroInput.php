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

class BairroInput extends InputType
{
    protected $attributes = [
        'name' => 'BairroInput',
        'description' => 'Bairro de uma cidade',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador do bairro',
            ],
            'cidade_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Cidade a qual o bairro pertence',
            ],
            'nome' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:100'],
                'description' => 'Nome do bairro',
            ],
            'valor_entrega' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Valor cobrado para entregar um pedido nesse bairro',
            ],
            'disponivel' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se o bairro está disponível para entrega de pedidos',
            ],
            'mapeado' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se o bairro está mapeado por zonas e se é obrigatório selecionar uma zona',
            ],
            'entrega_minima' => [
                'type' => Type::int(),
                'description' => 'Tempo mínimo de entrega para esse bairro, sobrescreve o tempo por dia',
            ],
            'entrega_maxima' => [
                'type' => Type::int(),
                'description' => 'Tempo máximo de entrega para esse bairro, sobrescreve o tempo por dia',
            ],
        ];
    }
}
