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

use App\Models\Grupo;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class GrupoType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Grupo',
        'description' => 'Grupos de pacotes, permite criar grupos como Tamanho, Sabores para formações de produtos',
        'model' => Grupo::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador do grupo',
            ],
            'produto_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Informa o pacote base da formação',
            ],
            'nome' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Nome resumido do grupo da formação, Exemplo: Tamanho, Sabores',
            ],
            'descricao' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Descrição do grupo da formação, Exemplo: Escolha o tamanho, Escolha os sabores',
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('GrupoTipo')),
                'description' => ' Informa se a formação final será apenas uma unidade ou vários itens',
            ],
            'quantidade_minima' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Permite definir uma quantidade mínima obrigatória para continuar com a venda',
            ],
            'quantidade_maxima' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Define a quantidade máxima de itens que podem ser escolhidos',
            ],
            'funcao' => [
                'type' => Type::nonNull(GraphQL::type('GrupoFuncao')),
                'description' => 'Informa qual será a fórmula de cálculo do preço, Mínimo: obtém o menor preço, Média:  define o preço do produto como a média dos itens selecionados, Máximo: Obtém o preço do item mais caro do grupo, Soma: Soma todos os preços dos produtos selecionados',
            ],
            'ordem' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Informa a ordem de exibição dos grupos',
            ],
            'data_arquivado' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data em que o grupo foi arquivado e não será mais usado',
            ],
        ];
    }
}
