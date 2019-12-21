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

use App\Models\Cliente;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ClienteType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Cliente',
        'description' => 'Informações de cliente físico ou jurídico. Clientes, empresas,' .
            ' funcionários, fornecedores e parceiros são cadastrados aqui',
        'model' => Cliente::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'Identificador do cliente',
            ],
            'tipo' => [
                'type' => GraphQL::type('ClienteTipo'),
                'description' => 'Informa o tipo de pessoa, que pode ser física ou jurídica',
            ],
            'empresa_id' => [
                'type' => Type::id(),
                'description' => 'Informa se esse cliente faz parte da empresa informada',
            ],
            'login' => [
                'type' => Type::string(),
                'description' => 'Nome de usuário utilizado para entrar no sistema, aplicativo ou site',
            ],
            'nome' => [
                'type' => Type::string(),
                'description' => 'Primeiro nome da pessoa física ou nome fantasia da empresa',
            ],
            'sobrenome' => [
                'type' => Type::string(),
                'description' => 'Restante do nome da pessoa física ou Razão social da empresa',
            ],
            'genero' => [
                'type' => GraphQL::type('ClienteGenero'),
                'description' => 'Informa o gênero do cliente do tipo pessoa física',
            ],
            'cpf' => [
                'type' => Type::string(),
                'description' => 'Cadastro de Pessoa Física(CPF) ou Cadastro Nacional de Pessoa' .
                    ' Jurídica(CNPJ)',
            ],
            'rg' => [
                'type' => Type::string(),
                'description' => 'Registro Geral(RG) ou Inscrição Estadual (IE)',
            ],
            'im' => [
                'type' => Type::string(),
                'description' => 'Inscrição municipal da empresa',
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'E-mail do cliente ou da empresa',
            ],
            'data_nascimento' => [
                'type' => GraphQL::type('Date'),
                'description' => 'Data de aniversário ou data de fundação',
            ],
            'slogan' => [
                'type' => Type::string(),
                'description' => 'Slogan ou detalhes do cliente',
            ],
            'status' => [
                'type' => GraphQL::type('ClienteStatus'),
                'description' => 'Informa o estado da conta do cliente',
            ],
            'limite_compra' => [
                'type' => Type::float(),
                'description' => 'Limite de compra utilizando a forma de pagamento Conta',
            ],
            'instagram' => [
                'type' => Type::string(),
                'description' => 'URL para acessar a página do Instagram do cliente',
            ],
            'facebook_url' => [
                'type' => Type::string(),
                'description' => 'URL para acessar a página do Facebook do cliente',
            ],
            'imagem_url' => [
                'type' => Type::string(),
                'description' => 'Foto do cliente ou logo da empresa',
            ],
            'linguagem' => [
                'type' => Type::string(),
                'description' => 'Código da linguagem utilizada pelo cliente para visualizar o aplicativo' .
                    ' e o site, Ex: pt-BR',
            ],
            'telefone' => [
                'type' => GraphQL::type('Telefone'),
                'description' => 'Telefone principal do cliente',
            ],
            'data_envio' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data do envio do e-mail de validação ou recuperação de conta',
                'privacy' => function (array $args): bool {
                    return Auth::check() && Auth::user()->can('cliente:view');
                },
            ],
            'data_atualizacao' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de atualização das informações do cliente',
            ],
            'data_cadastro' => [
                'type' => GraphQL::type('DateTime'),
                'description' => 'Data de cadastro do cliente',
            ],
        ];
    }
}
