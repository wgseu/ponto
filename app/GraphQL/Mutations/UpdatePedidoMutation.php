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

use App\Models\Pedido;
use App\Models\Viagem;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UpdatePedidoMutation extends CreatePedidoMutation
{
    protected $attributes = [
        'name' => 'UpdatePedido',
    ];

    public function authorize(array $args): bool
    {
        $pedido = Pedido::findOrFail($args['id']);
        $cliente_id = $args['input']['cliente_id'] ?? $pedido->cliente_id;
        $prev_access = Pedido::tipoAccess($pedido->tipo);
        $access = Pedido::tipoAccess($args['input']['tipo'] ?? $pedido->tipo);
        return Auth::check() && (
            ($cliente_id == Auth::user()->id && $pedido->cliente_id == Auth::user()->id)
            || Auth::user()->can('pedido:update')
            || (Auth::user()->can($access) && Auth::user()->can($prev_access))
        );
    }

    public function type(): Type
    {
        return GraphQL::type('PedidoSummary');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Código do pedido',
            ],
            'input' => ['type' => Type::nonNull(GraphQL::type('PedidoUpdateInput'))],
        ];
    }

    /**
     * Cria, altera ou finaliza uma viagem para entrega do pedido
     *
     * @param array $data
     * @param Pedido $pedido
     * @return void
     */
    public function applyDeliveryman($data, $pedido)
    {
        $entrega = $pedido->entrega ?? Viagem::where('responsavel_id', $data['responsavel_id'] ?? null)
            ->whereNull('data_chegada')->first();
        $viagem = new Viagem($data);
        if (!is_null($entrega)) {
            $viagem = $entrega;
            $viagem->fill($data);
        }
        $viagem->save();
        $pedido->entrega_id = $viagem->id;
    }

    public function resolve($root, $args)
    {
        $input = $args['input'];
        $pedido = Pedido::findOrFail($args['id']);
        DB::transaction(function () use ($pedido, $input) {
            $prev_access = Pedido::tipoAccess($pedido->tipo);
            $access = Pedido::tipoAccess($input['tipo'] ?? $pedido->tipo);
            $employee_access = Auth::user()->can('pedido:update') ||
                (Auth::user()->can($access) && Auth::user()->can($prev_access));
            if (!$employee_access) {
                // só deixa o cliente final alterar os campos abaixo
                $input = array_intersect_key($input, [
                    'localizacao_id' => null,
                    'pessoas' => null,
                    'cpf' => null,
                    'email' => null,
                    'descricao' => null,
                    'data_agendamento' => null,
                ]);
            }
            if (($input['estado'] ?? null) == Pedido::ESTADO_CANCELADO) {
                $input = array_intersect_key($input, array_flip(['estado', 'motivo']));
            }
            $pedido->fill($input);
            // o pedido pode ser feito por cliente final ou funcionário
            $funcionario_id = null;
            $prestador = null;
            if ($employee_access) {
                $prestador = Auth::user()->prestador;
                $funcionario_id = is_null($prestador) ? null : $prestador->id;
            }
            if ($pedido->estado != Pedido::ESTADO_CANCELADO) {
                $itens = $input['itens'] ?? [];
                $this->saveItems($itens, $pedido, $prestador, $funcionario_id);
                $cupons = $input['cupons'] ?? [];
                $this->saveCoupons($cupons, $pedido);
                $pagamentos = $input['pagamentos'] ?? [];
                $this->savePayments($pagamentos, $pedido, $funcionario_id);
                if ($pedido->estado == Pedido::ESTADO_ENTREGA) {
                    $entrega = $input['entrega'] ?? [];
                    $this->applyDeliveryman($entrega, $pedido);
                }
            }
            $pedido->save();
        });
        return $pedido;
    }
}
