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

use App\Models\Pacote;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PacoteType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Pacote',
        'description' => 'Contém todos as opções para a formação do produto final',
        'model' => Pacote::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador do pacote',
            ],
            'pacote_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Pacote a qual pertence as informações de formação do produto final',
            ],
            'grupo_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Grupo de formação, Ex.: Tamanho, Sabores e Complementos.',
            ],
            'produto_id' => [
                'type' => Type::int(),
                'description' => 'Produto selecionável do grupo. Não pode conter propriedade.',
            ],
            'propriedade_id' => [
                'type' => Type::int(),
                'description' => 'Propriedade selecionável do grupo. Não pode conter produto.',
            ],
            'associacao_id' => [
                'type' => Type::int(),
                'description' => 'Informa a propriedade pai de um complemento, permite atribuir preços diferentes dependendo da propriedade, Ex.: Tamanho -> Sabor, onde Tamanho é pai de Sabor',
            ],
            'quantidade_minima' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Permite definir uma quantidade mínima obrigatória para a venda desse item',
            ],
            'quantidade_maxima' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Define a quantidade máxima que pode ser vendido esse item repetidamente',
            ],
            'acrescimo' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Valor acrescentado ao produto quando o item é selecionado',
            ],
            'selecionado' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se o complemento está selecionado por padrão, recomendado apenas para produtos',
            ],
            'disponivel' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Indica se o pacote estará disponível para venda',
            ],
            'data_arquivado' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data em que o pacote foi arquivado e não será mais usado',
            ],
        ];
    }
}
