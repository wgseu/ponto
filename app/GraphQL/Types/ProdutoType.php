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

use App\Models\Produto;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProdutoType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Produto',
        'description' => 'Informações sobre o produto, composição ou pacote',
        'model' => Produto::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Código do produto',
            ],
            'codigo' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Código do produto podendo ser de barras ou aleatório, deve ser único' .
                    ' entre todos os produtos',
            ],
            'categoria_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Categoria do produto, permite a rápida localização ao utilizar tablets',
            ],
            'unidade_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Informa a unidade do produtos, Ex.: Grama, Litro.',
            ],
            'setor_estoque_id' => [
                'type' => Type::id(),
                'description' => 'Informa de qual setor o produto será retirado após a venda',
            ],
            'setor_preparo_id' => [
                'type' => Type::id(),
                'description' => 'Informa em qual setor de preparo será enviado o ticket de preparo ou' .
                    ' autorização, se nenhum for informado nada será impresso',
            ],
            'tributacao_id' => [
                'type' => Type::id(),
                'description' => 'Informações de tributação do produto',
            ],
            'descricao' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Descrição do produto, Ex.: Refri. Coca Cola 2L.',
            ],
            'abreviacao' => [
                'type' => Type::string(),
                'description' => 'Nome abreviado do produto, Ex.: Cebola, Tomate, Queijo',
            ],
            'detalhes' => [
                'type' => Type::string(),
                'description' => 'Informa detalhes do produto, Ex: Com Cebola, Pimenta, Orégano',
            ],
            'quantidade_minima' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Informa a quantidade limite para que o sistema avise que o produto já' .
                    ' está acabando',
            ],
            'quantidade_maxima' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Informa a quantidade máxima do produto no estoque, não proibe, apenas' .
                    ' avisa',
            ],
            'preco_venda' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Preço de venda base desse produto para todos os cardápios',
            ],
            'custo_producao' => [
                'type' => Type::float(),
                'description' => 'Informa qual o valor para o custo de produção do produto, utilizado' .
                    ' quando não há formação de composição do produto',
                'privacy' => function (array $args): bool {
                    return Auth::check() && Auth::user()->can('produto:view');
                },
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('ProdutoTipo')),
                'description' => 'Informa qual é o tipo de produto. Produto: Produto normal que possui' .
                    ' estoque, Composição: Produto que não possui estoque diretamente, pois é' .
                    ' composto de outros produtos ou composições, Pacote: Permite a composição' .
                    ' no momento da venda, não possui estoque diretamente',
            ],
            'cobrar_servico' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se deve ser cobrado a taxa de serviço dos garçons sobre este' .
                    ' produto',
            ],
            'divisivel' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se o produto pode ser vendido fracionado',
            ],
            'pesavel' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se o peso do produto deve ser obtido de uma balança,' .
                    ' obrigatoriamente o produto deve ser divisível',
            ],
            'tempo_preparo' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Tempo de preparo em minutos para preparar uma composição, 0 para não' .
                    ' informado',
            ],
            'disponivel' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se o produto estará disponível para venda em todos os cardápios',
            ],
            'insumo' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se o produto é de uso interno e não está disponível para venda',
            ],
            'avaliacao' => [
                'type' => Type::float(),
                'description' => 'Média das avaliações do último período',
            ],
            'estoque' => [
                'type' => Type::float(),
                'description' => 'Estoque geral do produto',
            ],
            'imagem_url' => [
                'type' => Type::string(),
                'description' => 'Imagem do produto',
            ],
            'data_atualizacao' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de atualização das informações do produto',
            ],
            'data_arquivado' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data em que o produto foi arquivado e não será mais usado',
            ],
        ];
    }
}
