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

use App\Mail\MailContact;
use App\Models\Cliente;
use App\Models\Telefone;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Carbon;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UpdateClienteMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateCliente',
    ];

    public function authorize(array $args): bool
    {
        return (Auth::check() && Auth::user()->can('cliente:update')) ||
            (Auth::check() && Auth::user()->id == $args['id']);
    }

    public function type(): Type
    {
        return GraphQL::type('Cliente');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Identificador do cliente',
            ],
            'input' => ['type' => Type::nonNull(GraphQL::type('ClienteUpdateInput'))],
        ];
    }

    public function resolve($root, $args)
    {
        $cliente = Cliente::findOrFail($args['id']);
        $old = $cliente->replicate();
        try {
            DB::transaction(function () use ($old, $args, $cliente) {
                $cliente->fill($args['input']);
                $revalidar = $old->status == Cliente::STATUS_ATIVO &&
                    $old->email != $cliente->email &&
                    !is_null($cliente->email);
                if ($revalidar) {
                    $cliente->status = Cliente::STATUS_INATIVO;
                }
                $cliente->save();
                $telefones = $args['input']['telefones'] ?? [];
                foreach ($telefones as $fone) {
                    $telefone = new Telefone();
                    if (isset($fone['id'])) {
                        $telefone = Telefone::where('cliente_id', $cliente->id)
                            ->findOrFail($fone['id']);
                    }
                    $old_phone = $telefone->replicate();
                    $telefone->fill($fone);
                    $telefone->pais_id = $fone['pais_id'] ?? app('country')->id;
                    $telefone->cliente_id = $cliente->id;
                    if (
                        $telefone->exists &&
                        !is_null($telefone->data_validacao) &&
                        (
                            $telefone->numero != $old_phone->numero ||
                            $telefone->pais_id != $old_phone->pais_id
                        )
                    ) {
                        // invalida a validação
                        $telefone->data_validacao = null;
                        // TODO: enviar SMS
                    }
                    $telefone->save();
                }
                if ($revalidar) {
                    $data = [
                        'nome' => $cliente->nome,
                        'url' => url('/account/verify/' . $cliente->createValidateToken())
                    ];
                    Mail::to($cliente->email)->send(new MailContact($data));
                    $cliente->data_envio = Carbon::now();
                    $cliente->save();
                }
            });
            $old->clean($cliente);
        } catch (\Throwable $th) {
            $cliente->clean($old);
            throw $th;
        }
        return $cliente;
    }
}
