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

class ClassificacaoInput extends InputType
{
    protected $attributes = [
        'name' => 'ClassificacaoInput',
        'description' => 'Classificação se contas, permite atribuir um grupo de contas',
    ];

    public function fields(): array
    {
        return [
            'classificacao_id' => [
                'type' => Type::int(),
                'description' => 'Classificação superior, quando informado, esta classificação será uma subclassificação',
            ],
            'descricao' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Descrição da classificação',
                'rules' => ['max:100'],
            ],
            'icone_url' => [
                'type' => Type::string(),
                'description' => 'Ícone da categoria da conta',
                'rules' => ['max:100'],
            ],
        ];
    }
}
