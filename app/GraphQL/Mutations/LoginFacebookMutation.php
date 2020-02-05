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

use Facebook\Facebook;
use App\Models\Cliente;
use App\Models\Integracao;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use App\Exceptions\AuthorizationException;
use Rebing\GraphQL\Support\Facades\GraphQL;

class LoginFacebookMutation extends Mutation
{
    protected $attributes = [
        'name' => 'LoginFacebook',
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
        try {
            $integration = Integracao::where('codigo', 'facebook')
                ->where('ativo', true)->firstOrFail();
            $class_name = env('FACEBOOK_LOGIN_CLASS', Facebook::class);
            $facebook = new $class_name([
                'app_id' => $integration->login,
                'app_secret' => $integration->secret,
                'default_graph_version' => 'v5.0',
            ]);
            $fb_response = $facebook->get('/me?fields=id,name,email', $args['token']);
            $payload = $fb_response->getGraphUser();
            if (!$payload) {
                throw new AuthorizationException(__('messages.access_denied'));
            }
            $cliente = Cliente::where('email', $payload['email'])->first();
            if (is_null($cliente)) {
                $imagem_url = sprintf(
                    env('FACEBOOK_IMG_FORMAT', 'https://graph.facebook.com/%s/picture?type=large'),
                    $payload['id']
                );
                $data = file_get_contents($imagem_url);
                $cliente = new Cliente();
                $cliente->nome = $payload['name'];
                $cliente->email = $payload['email'];
                if ($data !== false) {
                    $cliente->imagem = base64_encode($data);
                }
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
        } catch (\Throwable $th) {
            var_dump($th);
            throw $th;
        }
    }
}
