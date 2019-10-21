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

class IntegracaoInput extends InputType
{
    protected $attributes = [
        'name' => 'IntegracaoInput',
        'description' => 'Informa quais integrações estão disponíveis',
    ];

    public function fields(): array
    {
        return [
            'nome' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Nome do módulo de integração',
                'rules' => ['max:45'],
            ],
            'descricao' => [
                'type' => Type::string(),
                'description' => 'Descrição do módulo integrador',
                'rules' => ['max:200'],
            ],
            'icone_url' => [
                'type' => Type::string(),
                'description' => 'Nome do ícone do módulo integrador',
                'rules' => ['max:200'],
            ],
            'login' => [
                'type' => Type::string(),
                'description' => 'Login de acesso à API de sincronização',
                'rules' => ['max:200'],
            ],
            'secret' => [
                'type' => Type::string(),
                'description' => 'Chave secreta para acesso à API',
                'rules' => ['max:200'],
            ],
            'opcoes' => [
                'type' => Type::string(),
                'description' => 'Opções da integração, estados e tokens da loja',
                'rules' => ['max:65535'],
            ],
            'associacoes' => [
                'type' => Type::string(),
                'description' => 'Associações de produtos e cartões',
                'rules' => ['max:65535'],
            ],
            'ativo' => [
                'type' => Type::boolean(),
                'description' => 'Informa de o módulo de integração está habilitado',
            ],
        ];
    }
}
