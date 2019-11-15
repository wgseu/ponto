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

use App\Exceptions\AuthorizationException;
use App\Exceptions\ValidationException;
use App\Models\Cliente;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Payload;
use Tymon\JWTAuth\Token;

class RefreshTokenMutation extends Mutation
{
    protected $attributes = [
        'name' => 'RefreshToken',
    ];

    public function type(): Type
    {
        return GraphQL::type('ClienteAuth');
    }

    public function args(): array
    {
        return [
            'refresh_token' => ['type' => Type::nonNull(Type::string())],
        ];
    }

    public function resolve($root, $args)
    {
        /** @var Payload $payload */
        $payload = JWTAuth::decode(new Token($args['refresh_token'] ?? null));
        if (($payload['typ'] ?? null) == 'refresh') {
            throw new ValidationException(['refresh_token' => __('messages.invalid_refresh_token')]);
        }
        $cliente = Cliente::findOrFail($payload['uid'] ?? null);
        if ($cliente->status != Cliente::STATUS_ATIVO) {
            throw new AuthorizationException(__('messages.verify_account'));
        }
        $token = auth()->fromUser($cliente);
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
        ];
    }
}
