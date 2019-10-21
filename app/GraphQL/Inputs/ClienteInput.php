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

class ClienteInput extends InputType
{
    protected $attributes = [
        'name' => 'ClienteInput',
        'description' => 'Informações de cliente físico ou jurídico. Clientes, empresas, funcionários, fornecedores e parceiros são cadastrados aqui',
    ];

    public function fields(): array
    {
        return [
            'tipo' => [
                'type' => GraphQL::type('ClienteTipo'),
                'description' => 'Informa o tipo de pessoa, que pode ser física ou jurídica',
            ],
            'empresa_id' => [
                'type' => Type::int(),
                'description' => 'Informa se esse cliente faz parte da empresa informada',
            ],
            'login' => [
                'type' => Type::string(),
                'description' => 'Nome de usuário utilizado para entrar no sistema, aplicativo ou site',
                'rules' => ['max:50'],
            ],
            'senha' => [
                'type' => Type::string(),
                'description' => 'Senha embaralhada do cliente',
                'rules' => ['max:255'],
            ],
            'nome' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Primeiro nome da pessoa física ou nome fantasia da empresa',
                'rules' => ['max:100'],
            ],
            'sobrenome' => [
                'type' => Type::string(),
                'description' => 'Restante do nome da pessoa física ou Razão social da empresa',
                'rules' => ['max:100'],
            ],
            'genero' => [
                'type' => GraphQL::type('ClienteGenero'),
                'description' => 'Informa o gênero do cliente do tipo pessoa física',
            ],
            'cpf' => [
                'type' => Type::string(),
                'description' => 'Cadastro de Pessoa Física(CPF) ou Cadastro Nacional de Pessoa Jurídica(CNPJ)',
                'rules' => ['max:20'],
            ],
            'rg' => [
                'type' => Type::string(),
                'description' => 'Registro Geral(RG) ou Inscrição Estadual (IE)',
                'rules' => ['max:20'],
            ],
            'im' => [
                'type' => Type::string(),
                'description' => 'Inscrição municipal da empresa',
                'rules' => ['max:20'],
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'E-mail do cliente ou da empresa',
                'rules' => ['max:100'],
            ],
            'data_nascimento' => [
                'type' => GraphQL::type('Date'),
                'description' => 'Data de aniversário ou data de fundação',
            ],
            'slogan' => [
                'type' => Type::string(),
                'description' => 'Slogan ou detalhes do cliente',
                'rules' => ['max:100'],
            ],
            'status' => [
                'type' => GraphQL::type('ClienteStatus'),
                'description' => 'Informa o estado da conta do cliente',
            ],
            'secreto' => [
                'type' => Type::string(),
                'description' => 'Código secreto para recuperar a conta do cliente',
                'rules' => ['max:40'],
            ],
            'limite_compra' => [
                'type' => Type::float(),
                'description' => 'Limite de compra utilizando a forma de pagamento Conta',
            ],
            'instagram' => [
                'type' => Type::string(),
                'description' => 'URL para acessar a página do Instagram do cliente',
                'rules' => ['max:200'],
            ],
            'facebook_url' => [
                'type' => Type::string(),
                'description' => 'URL para acessar a página do Facebook do cliente',
                'rules' => ['max:200'],
            ],
            'twitter' => [
                'type' => Type::string(),
                'description' => 'URL para acessar a página do Twitter do cliente',
                'rules' => ['max:200'],
            ],
            'linkedin_url' => [
                'type' => Type::string(),
                'description' => 'URL para acessar a página do LinkedIn do cliente',
                'rules' => ['max:200'],
            ],
            'imagem_url' => [
                'type' => Type::string(),
                'description' => 'Foto do cliente ou logo da empresa',
                'rules' => ['max:100'],
            ],
            'linguagem' => [
                'type' => Type::string(),
                'description' => 'Código da linguagem utilizada pelo cliente para visualizar o aplicativo e o site, Ex: pt-BR',
                'rules' => ['max:20'],
            ],
        ];
    }
}
