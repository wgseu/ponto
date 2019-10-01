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

class ComposicaoInput extends InputType
{
    protected $attributes = [
        'name' => 'ComposicaoInput',
        'description' => 'Informa as propriedades da composição de um produto composto',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da composição',
            ],
            'composicao_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Informa a qual produto pertence essa composição, deve sempre ser um produto do tipo Composição',
            ],
            'produto_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Produto ou composição que faz parte dessa composição, Obs: Não pode ser um pacote',
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('ComposicaoTipo')),
                'description' => 'Tipo de composição, \'Composicao\' sempre retira do estoque, \'Opcional\' permite desmarcar na venda, \'Adicional\' permite adicionar na venda',
            ],
            'quantidade' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Quantidade que será consumida desse produto para cada composição formada',
            ],
            'valor' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Desconto que será realizado ao retirar esse produto da composição no  momento da venda',
            ],
            'quantidade_maxima' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Define a quantidade máxima que essa composição pode ser vendida repetidamente',
            ],
            'ativa' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Indica se a composição está sendo usada atualmente na composição do produto',
            ],
            'data_remocao' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data em que a composição foi removida e não será mais exibida por padrão',
            ],
        ];
    }
}