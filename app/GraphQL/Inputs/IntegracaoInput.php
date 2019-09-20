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

class IntegracaoInput extends InputType
{
    protected $attributes = [
        'name' => 'IntegracaoInput',
        'description' => 'Informa quais integrações estão disponíveis',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador da integração',
            ],
            'nome' => [
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:45'],
                'description' => 'Nome do módulo de integração',
            ],
            'descricao' => [
                'type' => Type::string(),
                'rules' => ['max:200'],
                'description' => 'Descrição do módulo integrador',
            ],
            'icone_url' => [
                'type' => Type::string(),
                'rules' => ['max:200'],
                'description' => 'Nome do ícone do módulo integrador',
            ],
            'login' => [
                'type' => Type::string(),
                'rules' => ['max:200'],
                'description' => 'Login de acesso à API de sincronização',
            ],
            'secret' => [
                'type' => Type::string(),
                'rules' => ['max:200'],
                'description' => 'Chave secreta para acesso à API',
            ],
            'opcoes' => [
                'type' => Type::string(),
                'rules' => ['max:65535'],
                'description' => 'Opções da integração, estados e tokens da loja',
            ],
            'associacoes' => [
                'type' => Type::string(),
                'rules' => ['max:65535'],
                'description' => 'Associações de produtos e cartões',
            ],
            'ativo' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Informa de o módulo de integração está habilitado',
            ],
            'data_atualizacao' => [
                'type' => Type::nonNull(GraphQL::type('datetime')),
                'description' => 'Data de atualização dos dados do módulo de integração',
            ],
        ];
    }
}
