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

use App\Exceptions\Exception;
use App\Models\Cheque;
use App\Models\Conta;
use App\Models\Credito;
use App\Models\Cupom;
use App\Models\Formacao;
use App\Models\Item;
use App\Models\Pagamento;
use App\Models\Pedido;
use App\Models\Prestador;
use App\Models\Produto;
use App\Rules\Montagem;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CreatePedidoMutation extends Mutation
{
    protected $attributes = [
        'name' => 'CreatePedido',
    ];

    public function authorize(array $args): bool
    {
        $cliente_id = $args['input']['cliente_id'] ?? null;
        $access = Pedido::tipoAccess($args['input']['tipo'] ?? Pedido::TIPO_BALCAO);
        return Auth::check() && (
            $cliente_id == Auth::user()->id
            || Auth::user()->can('pedido:create')
            || Auth::user()->can($access)
        );
    }

    public function type(): Type
    {
        return GraphQL::type('PedidoSummary');
    }

    public function args(): array
    {
        return [
            'input' => ['type' => Type::nonNull(GraphQL::type('PedidoInput'))],
        ];
    }

    /**
     * Atualiza a lista de pedidos recalculando os totais
     *
     * @param Pedido[] $pedidos
     * @return void
     */
    protected function updateOrders($pedidos)
    {
        foreach ($pedidos as $pedido) {
            $pedido->save();
        }
    }

    /**
     * Converte o array de dados de formações para array de objetos
     *
     * @param array $data_formations
     * @return Formacao[]
     */
    private static function makeFormations($data_formations)
    {
        $formations = [];
        foreach ($data_formations as $data) {
            $formations[] = new Formacao($data);
        }
        return $formations;
    }

    /**
     * Salva o item e sua formação, retira do estoque se o pedido não for agendado
     *
     * @param Item $item
     * @param Formacao[] $formations
     * @param Pedido $pedido
     * @return void
     */
    private static function saveItemFormation($item, $formations, $pedido)
    {
        if ($item->exists) {
            $item->save();
        } else {
            $item->formar($formations);
        }
        // garante que o produto não sairá do estoque imediatamente quando for agendamento
        if (
            $pedido->estado != Pedido::ESTADO_AGENDADO &&
            !$item->reservado &&
            $item->produto->tipo != Produto::TIPO_PACOTE
        ) {
            $item->reservar();
        }
    }

    /**
     * Insere ou atualiza os itens do pedido
     *
     * @param array $data_itens
     * @param Pedido $pedido
     * @param Prestador $prestador
     * @param int $funcionario_id
     * @param int $level
     * @return Item[]
     */
    protected function saveItem($data, $pedido, $prestador, $funcionario_id, $level, &$pedidos_afetados)
    {
        $item = new Item();
        $atualizando = isset($data['id']);
        $cancelamento = $data['cancelado'] ?? false;
        if ($atualizando) {
            // permite mover um item de uma mesa para outra
            $query = Item::where('cancelado', false);
            if ($cancelamento) {
                // só deixa cancelar itens desse pedido
                $query->where('pedido_id', $pedido->id);
            }
            $item = $query->findOrFail($data['id']);
            if ($item->pedido_id != $pedido->id && !isset($pedidos_afetados[$item->pedido_id])) {
                // movendo o item de outro pedido para esse
                $pedidos_afetados[$item->pedido_id] = Pedido::findOrFail($item->pedido_id);
            }
        }
        if ($cancelamento) {
            $data = array_intersect_key($data, array_flip(['cancelado', 'motivo', 'desperdicado']));
        }
        $item->fill($data);
        if ($cancelamento) {
            $item->save();
            return $item;
        }
        $item->pedido_id = $pedido->id;
        if (is_null($item->prestador_id)) {
            $item->prestador_id = $funcionario_id;
        }
        // muda a data de processamento em caso de alteração de estado
        if ($item->exists && $item->estado != $item->fresh()->estado) {
            $item->data_processamento = Carbon::now();
        }
        // não salva os subitens antes de checar a formação como um todo
        if ($level > 0) {
            return $item;
        }
        if (is_null($item->produto_id)) {
            $item->save();
            return $item;
        }
        $formations = self::makeFormations($data['formacoes'] ?? []);
        if ($item->produto->tipo != Produto::TIPO_PACOTE) {
            self::saveItemFormation($item, $formations, $pedido);
            return $item;
        }
        $montagem = new Montagem($item->toArray());
        $montagem->initialize();
        $montagem->addItem($item, $formations, true);
        $subitens = $data['subitens'] ?? [];
        foreach ($subitens as $subdata) {
            $subitem = $this->saveItem(
                $subdata,
                $pedido,
                $prestador,
                $funcionario_id,
                1,
                $pedidos_afetados
            );
            $subformations = self::makeFormations($subdata['formacoes'] ?? []);
            $montagem->addItem($subitem, $subformations);
        }
        if (!$atualizando) {
            $montagem->verify();
        }
        foreach ($montagem->itens as $info) {
            /** @var Item $subitem */
            $subitem = $info['item'];
            if (!$atualizando) {
                $subitem->item_id = $item->id;
            }
            /** @var Formacao[] $subformations */
            $subformations = $info['formacoes'];
            self::saveItemFormation($subitem, $subformations, $pedido);
        }
        return $item;
    }

    /**
     * Insere ou atualiza os itens do pedido
     *
     * @param array $data_itens
     * @param Pedido $pedido
     * @param Prestador $prestador
     * @param int $funcionario_id
     * @return Item[]
     */
    protected function saveItems($data_itens, $pedido, $prestador, $funcionario_id)
    {
        $itens = [];
        $pedidos_afetados = [];
        foreach ($data_itens as $data) {
            $itens[] = $this->saveItem(
                $data,
                $pedido,
                $prestador,
                $funcionario_id,
                0,
                $pedidos_afetados
            );
        }
        $this->updateOrders($pedidos_afetados);
        return $itens;
    }

    /**
     * Salva um cupom
     *
     * @param array $data
     * @param Pedido $pedido
     */
    protected function saveCoupon($data, $pedido)
    {
        if (isset($data['id'])) {
            $cupom = Cupom::where('id', $data['id'])->firstOrFail();
        } else {
            $cupom = Cupom::where('codigo', $data['codigo'] ?? '')->firstOrFail();
        }
        if ($cupom->quantidade < 0) {
            // informou o cupom usado, não faz nada
            return $cupom;
        }
        $cupom_id = $cupom->id;
        $cupom = $cupom->replicate();
        $cupom->cupom_id = $cupom_id;
        $cupom->pedido_id = $pedido->id;
        $cupom->cliente_id = $pedido->cliente_id;
        $cupom->quantidade = -1;
        $cupom->save();
        // mantém os descontos de outros cupons e aplica o desse
        $pedido->descontos -= $cupom->valor;
        return $cupom;
    }

    /**
     * Salva uma lista de cupons
     *
     * @param array $list
     * @param Pedido $pedido
     */
    protected function saveCoupons($list, $pedido)
    {
        foreach ($list as $data) {
            $this->saveCoupon($data, $pedido);
        }
    }

    private function payWithBalance($pagamento, $data, $pedido, $funcionario_id)
    {
        $credito = new Credito($data);
        // não deixa o cliente gastar os crédito de outro
        if (is_null($funcionario_id)) {
            $credito->cliente_id = Auth::user()->id;
        } else {
            $credito->cliente_id = $credito->cliente_id ?? $pedido->cliente_id ?? Auth::user()->id;
        }
        $credito->save();
        $pagamento->credito_id = $credito->id;
    }

    private function payCreateAccount($pagamento, $data, $pedido, $funcionario_id)
    {
        $crediario = new Conta($data);
        $crediario->cliente_id = $crediario->cliente_id ?? $pedido->cliente_id ?? Auth::user()->id;
        $crediario->pedido_id = $pedido->id;
        $crediario->funcionario_id = $funcionario_id;
        $crediario->save();
        $pagamento->crediario_id = $crediario->id;
    }

    private function payWithCheck($pagamento, $data, $pedido)
    {
        $cheque = new Cheque($data);
        $cheque->cliente_id = $cheque->cliente_id ?? $pedido->cliente_id ?? Auth::user()->id;
        $cheque->save();
        $pagamento->cheque_id = $cheque->id;
    }

    /**
     * Salva o pagamento do pedido
     *
     * @param array $pagamento_data
     * @param Pedido $pedido
     * @param int $funcionario_id
     * @return Pagamento
     */
    protected function savePayment($data, $pedido, $funcionario_id)
    {
        $pagamento = new Pagamento();
        if (isset($data['id'])) {
            $pagamento = $pedido->pagamentos()->where('id', $data['id'])->firstOrFail();
        }
        $cancelamento = ($data['estado'] ?? null) == Pagamento::ESTADO_CANCELADO;
        if ($cancelamento) {
            $data = array_intersect_key($data, array_flip(['estado']));
        }
        $pagamento->fill($data);
        if ($cancelamento) {
            $pagamento->save();
            return $pagamento;
        }
        $pagamento->pedido_id = $pedido->id;
        $pagamento->funcionario_id = $funcionario_id;
        $pagamento->moeda_id = $pagamento->moeda_id ?? app('currency')->id;
        // cria objetos do pagamento como conta, desconto do crédito e folha de cheque
        if (isset($data['credito'])) {
            $this->payWithBalance($pagamento, $data['credito'], $pedido, $funcionario_id);
        } elseif (is_null($funcionario_id)) {
            // não cadastra conta e nem cheque
        } elseif (isset($data['crediario'])) {
            $this->payCreateAccount($pagamento, $data['crediario'], $pedido, $funcionario_id);
        } elseif (isset($data['cheque'])) {
            $this->payWithCheck($pagamento, $data['cheque'], $pedido);
        }
        $pagamento->save();
        if (count($data['itens'] ?? []) > 0) {
            // marca os itens como pago por esse pagamento
            Item::whereIn('id', $data['itens'])
                ->where('cancelado', false)
                ->where('pedido_id', $pedido->id)
                ->whereNull('pagamento_id')
                ->update(['pagamento_id' => $pagamento->id]);
        }
        return $pagamento;
    }

    /**
     * Salva uma lista de pagamentos
     *
     * @param array $data_pagamentos
     * @param Pedido $pedido
     * @param int $funcionario_id
     * @param int $pagamento_id
     * @return Pagamento[]
     */
    protected function savePayments(
        $data_pagamentos,
        $pedido,
        $funcionario_id,
        $pagamento_id = null
    ) {
        foreach ($data_pagamentos as $pagamento_data) {
            $pagamento_data['pagamento_id'] = $pagamento_id;
            $pagamento = $this->savePayment($pagamento_data, $pedido, $funcionario_id);
            if (isset($pagamento_data['subpagamentos'])) {
                $this->savePayments(
                    $pagamento_data['subpagamentos'],
                    $pedido,
                    $funcionario_id,
                    $pagamento->id
                );
            }
        }
    }

    public function resolve($root, $args)
    {
        $input = $args['input'];
        $pedido = new Pedido($input);
        DB::transaction(function () use ($pedido, $input) {
            // o pedido pode ser feito por cliente final ou funcionário
            $funcionario_id = null;
            $prestador = null;
            $access = Pedido::tipoAccess($pedido->tipo);
            $employee_access = Auth::user()->can('pedido:create') || Auth::user()->can($access);
            if ($employee_access) {
                $prestador = Auth::user()->prestador;
                $funcionario_id = is_null($prestador) ? null : $prestador->id;
            }
            if (
                empty($input['itens'] ?? null) &&
                in_array($pedido->tipo, [Pedido::TIPO_BALCAO, Pedido::TIPO_ENTREGA])
            ) {
                throw new Exception(__('messages.no_item_added'));
            }
            if ($pedido->estado == Pedido::ESTADO_AGENDADO && is_null($pedido->data_agendamento)) {
                $pedido->data_agendamento = Carbon::now();
            }
            $pedido->prestador_id = $funcionario_id;
            $pedido->save();
            $itens = $input['itens'] ?? [];
            $this->saveItems($itens, $pedido, $prestador, $funcionario_id);
            $cupons = $input['cupons'] ?? [];
            $this->saveCoupons($cupons, $pedido);
            $pagamentos = $input['pagamentos'] ?? [];
            $this->savePayments($pagamentos, $pedido, $funcionario_id);
            if ($pedido->tipo == Pedido::TIPO_BALCAO) {
                $pedido->estado = Pedido::ESTADO_CONCLUIDO;
            }
            $pedido->save();
        });
        return $pedido;
    }
}
