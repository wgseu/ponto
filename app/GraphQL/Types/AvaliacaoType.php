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

use App\Models\Avaliacao;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class AvaliacaoType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Avaliacao',
        'description' => 'Avaliação de atendimento e outros serviços do estabelecimento',
        'model' => Avaliacao::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da avaliação',
            ],
            'metrica_id' => [
                'type' => Type::id(),
                'description' => 'Métrica de avaliação',
            ],
            'cliente_id' => [
                'type' => Type::id(),
                'description' => 'Informa o cliente que avaliou esse pedido ou produto, obrigatório quando' .
                    ' for avaliação de produto',
            ],
            'pedido_id' => [
                'type' => Type::id(),
                'description' => 'Pedido que foi avaliado, quando nulo o produto deve ser informado',
            ],
            'produto_id' => [
                'type' => Type::id(),
                'description' => 'Produto que foi avaliado',
            ],
            'estrelas' => [
                'type' => Type::float(),
                'description' => 'Quantidade de estrelas de 1 a 5',
            ],
            'comentario' => [
                'type' => Type::string(),
                'description' => 'Comentário da avaliação',
            ],
            'data_avaliacao' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data da avaliação',
            ],
        ];
    }
}
