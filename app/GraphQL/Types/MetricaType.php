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

use App\Models\Metrica;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class MetricaType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Metrica',
        'description' => 'Métricas de avaliação do atendimento e outros serviços do estabelecimento',
        'model' => Metrica::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da métrica',
            ],
            'nome' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Nome da métrica',
            ],
            'descricao' => [
                'type' => Type::string(),
                'description' => 'Descreve o que deve ser avaliado pelo cliente',
            ],
            'tipo' => [
                'type' => Type::nonNull(GraphQL::type('MetricaTipo')),
                'description' => 'Tipo de métrica que pode ser velocidade de entrega, quantidade no atendimento, sabor da comida e apresentação do prato',
            ],
            'quantidade' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Quantidade das últimas avaliações para reavaliação da métrica',
            ],
            'avaliacao' => [
                'type' => Type::float(),
                'description' => 'Média das avaliações para o período informado',
            ],
            'data_processamento' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data do último processamento da avaliação',
            ],
            'data_arquivado' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data em que essa métrica foi arquivada',
            ],
        ];
    }
}
