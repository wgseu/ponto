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

class PrestadorInput extends InputType
{
    protected $attributes = [
        'name' => 'PrestadorInput',
        'description' => 'Prestador de serviço que realiza alguma tarefa na empresa',
    ];

    public function fields(): array
    {
        return [
            'codigo' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Código do prestador, podendo ser de barras',
                'rules' => ['max:100'],
            ],
            'pin' => [
                'type' => Type::string(),
                'description' => 'Código pin para acesso rápido',
                'rules' => ['max:200'],
            ],
            'funcao_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Função do prestada na empresa',
            ],
            'cliente_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Cliente que representa esse prestador, único no cadastro de prestadores',
            ],
            'empresa_id' => [
                'type' => Type::id(),
                'description' => 'Informa a empresa que gerencia os colaboradores, nulo para a empresa do próprio estabelecimento',
            ],
            'vinculo' => [
                'type' => GraphQL::type('PrestadorVinculo'),
                'description' => 'Vínculo empregatício com a empresa, funcionário e autônomo são pessoas físicas, prestador é pessoa jurídica',
            ],
            'porcentagem' => [
                'type' => Type::float(),
                'description' => 'Porcentagem cobrada pelo funcionário ou autônomo ao cliente, Ex.: Comissão de 10%',
            ],
            'pontuacao' => [
                'type' => Type::int(),
                'description' => 'Define a distribuição da porcentagem pela parcela de pontos',
            ],
            'remuneracao' => [
                'type' => Type::float(),
                'description' => 'Remuneracao pelas atividades exercidas, não está incluso comissões',
            ],
            'data_termino' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de término de contrato, informado apenas quando ativo for não',
            ],
        ];
    }
}
