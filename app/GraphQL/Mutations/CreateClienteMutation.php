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

use App\Models\Cliente;
use App\Mail\MailContact;
use App\Models\Empresa;
use App\Models\Telefone;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateClienteMutation extends Mutation
{
    protected $attributes = [
        'name' => 'CreateCliente',
    ];

    public function type(): Type
    {
        return GraphQL::type('ClienteVerify');
    }

    public function args(): array
    {
        return [
            'input' => ['type' => Type::nonNull(GraphQL::type('ClienteInput'))],
        ];
    }

    public function resolve($root, $args)
    {
        $cliente_data = [];
        DB::transaction(function () use ($args, &$cliente_data) {
            $cliente = new Cliente();
            $cliente->fill($args['input']);
            if (!Auth::check() || !Auth::user()->can('cliente:create')) {
                $cliente->ip = $_SERVER['REMOTE_ADDR'] ?? null;
            }
            $cliente->save();
            $telefones = $args['input']['telefones'] ?? [];
            foreach ($telefones as $fone) {
                $telefone = new Telefone($fone);
                $telefone->pais_id = $fone['pais_id'] ?? app('country')->id;
                $telefone->cliente_id = $cliente->id;
                $telefone->save();
            }
            if ($cliente->email) {
                $data = [
                    'nome' => $cliente->nome,
                    'url' => url('/account/verify/' . $cliente->createValidateToken())
                ];
                Mail::to($cliente->email)->send(new MailContact($data));
                $cliente->data_envio = Carbon::now();
                $cliente->save();
                $cliente_data['refresh_token'] = $cliente->createRefreshToken();
            }
            $cliente_data = array_merge($cliente->toArray(), $cliente_data);
        });
        return $cliente_data;
    }
}
