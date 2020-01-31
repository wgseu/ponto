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

namespace App\GraphQL\Mutations;

use Google_Client;
use App\Models\Cliente;
use App\Models\Integracao;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use App\Exceptions\AuthorizationException;
use Rebing\GraphQL\Support\Facades\GraphQL;

class LoginGoogleMutation extends Mutation
{
    protected $attributes = [
        'name' => 'LoginGoogle',
    ];

    public function type(): Type
    {
        return GraphQL::type('ClienteAuth');
    }

    public function args(): array
    {
        return [
            'token' => ['type' => Type::nonNull(Type::string())],
        ];
    }

    public function resolve($root, $args)
    {
        $integration = Integracao::where('codigo', 'google')
            ->where('ativo', true)->firstOrFail();
        $class_name = env('GOOGLE_LOGIN_CLASS', Google_Client::class);
        $client = new $class_name(['client_id' => $integration->login]);
        $payload = $client->verifyIdToken($args['token']);
        if (!$payload) {
            throw new AuthorizationException(__('messages.access_denied'));
        }
        $cliente = Cliente::where('email', $payload['email'])->first();
        if (is_null($cliente)) {
            $data = file_get_contents($payload['picture']);
            $cliente = new Cliente();
            $cliente->nome = $payload['name'];
            $cliente->email = $payload['email'];
            $cliente->imagem = base64_encode($data);
        }
        if ($cliente->status == Cliente::STATUS_INATIVO) {
            $cliente->status = Cliente::STATUS_ATIVO;
            $cliente->save();
        }
        $token = auth()->fromUser($cliente);
        return [
            'refresh_token' => $cliente->createRefreshToken(),
            'access_token'  => $token,
            'token_type'    => 'bearer',
            'expires_in'    => auth()->factory()->getTTL() * 60,
            'user'          => $cliente,
        ];
    }
}
