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

class PatrimonioInput extends InputType
{
    protected $attributes = [
        'name' => 'Patrimonio',
        'description' => 'Informa detalhadamente um bem da empresa',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador do bem',
            ],
            'empresa_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Empresa a que esse bem pertence',
            ],
            'fornecedor_id' => [
                'type' => Type::int(),
                'description' => 'Fornecedor do bem',
            ],
            'numero' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:45'],
                'description' => 'Número que identifica o bem',
            ],
            'descricao' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:200'],
                'description' => 'Descrição ou nome do bem',
            ],
            'quantidade' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Quantidade do bem com as mesmas características',
            ],
            'altura' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Altura do bem em metros',
            ],
            'largura' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Largura do bem em metros',
            ],
            'comprimento' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Comprimento do bem em metros',
            ],
            'estado' => [
                'type' => Type::nonNull(GraphQL::type('PatrimonioEstadoEnum')),
                'description' => 'Estado de conservação do bem',
            ],
            'custo' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Valor de custo do bem',
            ],
            'valor' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Valor que o bem vale atualmente',
            ],
            'ativo' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa se o bem está ativo e em uso',
            ],
            'imagem_url' => [
                'type' => Type::string(),
                'rules' => ['max:200'],
                'description' => 'Caminho relativo da foto do bem',
            ],
            'data_atualizacao' => [
                'type' => Type::nonNull(GraphQL::type('datetime')),
                'description' => 'Data de atualização das informações do bem',
            ],
        ];
    }
}
