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

class CategoriaInput extends InputType
{
    protected $attributes = [
        'name' => 'CategoriaInput',
        'description' => 'Informa qual a categoria dos produtos e permite a rápida localização dos' .
            ' mesmos',
    ];

    public function fields(): array
    {
        return [
            'categoria_id' => [
                'type' => Type::id(),
                'description' => 'Informa a categoria pai da categoria atual, a categoria atual é uma' .
                    ' subcategoria',
            ],
            'descricao' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Descrição da categoria. Ex.: Refrigerantes, Salgados',
                'rules' => ['max:45'],
            ],
            'detalhes' => [
                'type' => Type::string(),
                'description' => 'Informa os detalhes gerais dos produtos dessa categoria',
                'rules' => ['max:200'],
            ],
            'imagem_url' => [
                'type' => Type::string(),
                'description' => 'Imagem representativa da categoria',
                'rules' => ['max:100'],
            ],
            'ordem' => [
                'type' => Type::int(),
                'description' => 'Informa a ordem de exibição das categorias nas vendas',
            ],
        ];
    }
}
