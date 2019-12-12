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

namespace App\Models;

use App\Concerns\ModelEvents;
use App\Interfaces\ValidateInsertInterface;
use App\Interfaces\ValidateInterface;
use App\Interfaces\ValidateUpdateInterface;
use App\Util\Number;
use App\Util\Validator;
use Illuminate\Database\Eloquent\Model;

/**
 * Informações do pedido de venda
 */
class Pedido extends Model implements
    ValidateInterface,
    ValidateInsertInterface,
    ValidateUpdateInterface
{
    use ModelEvents;

    /**
     * Tipo de venda
     */
    public const TIPO_MESA = 'mesa';
    public const TIPO_COMANDA = 'comanda';
    public const TIPO_BALCAO = 'balcao';
    public const TIPO_ENTREGA = 'entrega';

    /**
     * Estado do pedido, Agendado: O pedido deve ser processado na data de
     * agendamento. Aberto: O pedido deve ser processado. Entrega: O pedido
     * saiu para entrega. Fechado: O cliente pediu a conta e está pronto para
     * pagar. Concluído: O pedido foi pago e concluído, Cancelado: O pedido foi
     * cancelado com os itens e pagamentos
     */
    public const ESTADO_AGENDADO = 'agendado';
    public const ESTADO_ABERTO = 'aberto';
    public const ESTADO_ENTREGA = 'entrega';
    public const ESTADO_FECHADO = 'fechado';
    public const ESTADO_CONCLUIDO = 'concluido';
    public const ESTADO_CANCELADO = 'cancelado';

    public const CREATED_AT = 'data_criacao';
    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pedidos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pedido_id',
        'mesa_id',
        'comanda_id',
        'sessao_id',
        'prestador_id',
        'cliente_id',
        'localizacao_id',
        'entrega_id',
        'associacao_id',
        'tipo',
        'estado',
        'pessoas',
        'cpf',
        'email',
        'descricao',
        'motivo',
        'data_agendamento',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'tipo' => self::TIPO_BALCAO,
        'estado' => self::ESTADO_ABERTO,
        'servicos' => 0,
        'produtos' => 0,
        'comissao' => 0,
        'subtotal' => 0,
        'descontos' => 0,
        'total' => 0,
        'pago' => 0,
        'troco' => 0,
        'lancado' => 0,
        'pessoas' => 1,
    ];

    /**
     * Informa o pedido da mesa / comanda principal quando as mesas / comandas
     * forem agrupadas
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    /**
     * Identificador da mesa, único quando o pedido não está fechado
     */
    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'mesa_id');
    }

    /**
     * Identificador da comanda, único quando o pedido não está fechado
     */
    public function comanda()
    {
        return $this->belongsTo(Comanda::class, 'comanda_id');
    }

    /**
     * Identificador da sessão de vendas
     */
    public function sessao()
    {
        return $this->belongsTo(Sessao::class, 'sessao_id');
    }

    /**
     * Prestador que criou esse pedido
     */
    public function prestador()
    {
        return $this->belongsTo(Prestador::class, 'prestador_id');
    }

    /**
     * Identificador do cliente do pedido
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Endereço de entrega do pedido, se não informado na venda entrega, o
     * pedido será para viagem
     */
    public function localizacao()
    {
        return $this->belongsTo(Localizacao::class, 'localizacao_id');
    }

    /**
     * Informa em qual entrega esse pedido foi despachado
     */
    public function entrega()
    {
        return $this->belongsTo(Viagem::class, 'entrega_id');
    }

    /**
     * Informa se o pedido veio de uma integração e se está associado
     */
    public function associacao()
    {
        return $this->belongsTo(Associacao::class, 'associacao_id');
    }

    /**
     * Informa quem fechou o pedido e imprimiu a conta
     */
    public function fechador()
    {
        return $this->belongsTo(Prestador::class, 'fechador_id');
    }

    /**
     * Informa quem fechou o pedido e imprimiu a conta
     */
    public function itens()
    {
        return $this->hasMany(Item::class, 'pedido_id')->where('cancelado', false);
    }

    /**
     * Devolve a lista de todos os pagamentos não cancelados
     */
    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class, 'pedido_id')
            ->where('estado', '<>', Pagamento::ESTADO_CANCELADO);
    }

    /**
     * Devolve a lista de promessa de pagamentos, inclui troco
     */
    public function lancados()
    {
        return $this->pagamentos()
            ->where('estado', '<>', Pagamento::ESTADO_PAGO);
    }

    /**
     * Lista de pagamentos já aprovados, inclui troco
     */
    public function pagos()
    {
        return $this->pagamentos()
            ->where('estado', Pagamento::ESTADO_PAGO);
    }

    /**
     * Lista de lançamento de trocos
     */
    public function trocos()
    {
        return $this->pagamentos()
            ->where('lancado', '<', 0);
    }

    /**
     * Informa se o pedido está finalizado por conclusão ou cancelamento
     *
     * @return bool
     */
    public function finished()
    {
        return in_array($this->estado, [self::ESTADO_CONCLUIDO, self::ESTADO_CANCELADO]);
    }

    /**
     * Informa a permissão necessária para criar ou adicionar itens no pedido
     *
     * @param string $tipo tipo de pedido
     * @return string nome de acesso
     */
    public static function tipoAccess($tipo)
    {
        switch ($tipo) {
            case self::TIPO_MESA:
                return 'pedido:type:table';
            case self::TIPO_COMANDA:
                return 'pedido:type:card';
            case self::TIPO_ENTREGA:
                return 'pedido:type:delivery';
            default:
                return 'pedido:type:counter';
        }
    }

    public function payChanges()
    {
        $trocos = $this->trocos()
            ->where('estado', '<>', Pagamento::ESTADO_PAGO)->get();
        foreach ($trocos as $troco) {
            $troco->update(['estado' => Pagamento::ESTADO_PAGO]);
        }
    }

    public function totalize()
    {
        $this->servicos = (float)$this->itens()->whereNotNull('servico_id')->sum('subtotal');
        $this->produtos = (float)$this->itens()->whereNotNull('produto_id')->sum('subtotal');
        $this->comissao = (float)$this->itens()->sum('comissao');
        $this->subtotal = $this->servicos + $this->produtos + $this->comissao;
        $this->descontos = (float)$this->itens()->where('subtotal', '<', 0)->sum('subtotal');
        $this->total = $this->subtotal + $this->descontos;
        $this->pago = (float)$this->pagos()->sum('lancado');
        $this->troco = (float)$this->trocos()->sum('lancado');
        $this->lancado = (float)$this->lancados()->sum('lancado');
        return $this;
    }

    /**
     * Cancela os itens e pagamentos
     *
     * @return void
     */
    protected function cancel()
    {
        $pagamentos = $this->pagamentos;
        foreach ($pagamentos as $pagamento) {
            $pagamento->update(['estado' => Pagamento::ESTADO_CANCELADO]);
        }
        $itens = $this->itens;
        foreach ($itens as $item) {
            $item->update(['cancelado' => true]);
        }
    }

    /**
     * Retira do estoque os itens que ainda não foram reservados
     *
     * @return void
     */
    protected function reserveProducts()
    {
        $itens = $this->itens()->where('reservado', false)
            ->whereNotNull('produto_id')->get();
        foreach ($itens as $item) {
            $produto = $item->produto;
            if ($produto->tipo != Produto::TIPO_PACOTE) {
                $item->reservar();
            }
        }
    }

    public function validate()
    {
        $errors = [];
        if (
            !Validator::checkCNPJ($this->cpf) &&
            !Validator::checkCPF($this->cpf, true)
        ) {
            $errors['cpf'] = __('messages.cpf_invalid', 'CPF');
        } elseif (
            !Validator::checkCPF($this->cpf) &&
            !Validator::checkCNPJ($this->cpf, true)
        ) {
            $errors['cpf'] = __('messages.cpf_invalid', 'CNPJ');
        }
        if (!Validator::checkEmail($this->email, true)) {
            $errors['email'] = __('messages.invalid_email');
        }
        // não pode entregar sem um cliente
        if ($this->tipo == self::TIPO_ENTREGA && is_null($this->cliente_id)) {
            $errors['cliente_id'] = __('messages.delivery_without_customer');
        }
        // só entrega pode ter endereço no pedido
        if ($this->tipo != self::TIPO_ENTREGA && !is_null($this->localizacao_id)) {
            $errors['localizacao_id'] = __('messages.non_delivery_with_address');
        }
        // só pedido para entrega pode ser entregue
        if ($this->tipo != self::TIPO_ENTREGA && !is_null($this->entrega_id)) {
            $errors['entrega_id'] = __('messages.non_delivery_delivering');
        }
        // nãp pode sair para entrega sem o entregador
        if (
            in_array($this->estado, [self::ESTADO_ENTREGA, self::ESTADO_CONCLUIDO]) &&
            !is_null($this->localizacao_id) &&
            is_null($this->entrega_id)
        ) {
            $errors['entrega_id'] = __('messages.delivering_without_deliveryman');
        }
        // só entrega se tiver endereço
        if (is_null($this->localizacao_id) && !is_null($this->entrega_id)) {
            $errors['entrega_id'] = __('messages.delivering_without_address');
        }
        if ($this->tipo == self::TIPO_MESA && is_null($this->mesa_id)) {
            $errors['mesa_id'] = __('messages.table_without_local');
        }
        if ($this->tipo != self::TIPO_COMANDA && !is_null($this->comanda_id)) {
            $errors['comanda_id'] = __('messages.other_order_as_card');
        }
        if (!in_array($this->tipo, [self::TIPO_COMANDA, self::TIPO_MESA]) && !is_null($this->mesa_id)) {
            $errors['mesa_id'] = __('messages.other_order_as_table');
        }
        // só um pedido por mesa
        if (!is_null($this->mesa_id)) {
            $other = self::where('mesa_id', $this->mesa_id)
                ->where('estado', '<>', self::ESTADO_CANCELADO)
                ->where('estado', '<>', self::ESTADO_CONCLUIDO);
            // não pode criar uma comanda em uma mesa já aberta
            if ($this->tipo == self::TIPO_COMANDA) {
                $other->where('tipo', '<>', self::TIPO_COMANDA);
            }
            if ($this->exists) {
                $other->where('id', '<>', $this->id);
            }
            if ($other->exists()) {
                $errors['mesa_id'] = __('messages.table_already_open');
            }
        }
        // só um pedido por comanda
        if ($this->tipo == self::TIPO_COMANDA && !is_null($this->comanda_id)) {
            $other = self::where('comanda_id', $this->comanda_id)
                ->where('estado', '<>', self::ESTADO_CANCELADO)
                ->where('estado', '<>', self::ESTADO_CONCLUIDO);
            if ($this->exists) {
                $other->where('id', '<>', $this->id);
            }
            if ($other->exists()) {
                $errors['comanda_id'] = __('messages.card_already_open');
            }
        }
        return $errors;
    }

    public function onInsert()
    {
        $errors = [];
        if (is_null($this->prestador_id) && $this->estado != self::ESTADO_AGENDADO) {
            $errors['estado'] = __('messages.order_mustbe_scheduled');
        }
        // um pedido deve ser criado aberto ou agendado
        if (!in_array($this->estado, [self::ESTADO_ABERTO, self::ESTADO_AGENDADO])) {
            $errors['estado'] = __('messages.order_mustbe_open_scheduled');
        }
        return $errors;
    }

    public function onUpdate()
    {
        $errors = [];
        $old = $this->fresh();
        // não pode alterar pedido cancelado
        if ($old->estado == self::ESTADO_CANCELADO) {
            $errors['estado'] = __('messages.order_already_cancelled');
        } elseif ($this->estado == self::ESTADO_CANCELADO) {
            $this->cancel();
        } elseif ($old->estado == self::ESTADO_AGENDADO && $this->estado != self::ESTADO_CANCELADO) {
            $this->reserveProducts();
        }
        if ($this->tipo == self::TIPO_ENTREGA) {
            // não pode entregar sem endereço
            if (
                is_null($this->localizacao_id) &&
                $this->estado == self::ESTADO_ENTREGA
            ) {
                $errors['estado'] = __('messages.cannot_delivery_togo');
            }
            // precisa entregar antes de concluir
            if (
                !is_null($this->localizacao_id) &&
                $old->estado != self::ESTADO_ENTREGA &&
                $this->estado == self::ESTADO_CONCLUIDO
            ) {
                $errors['estado'] = __('messages.order_not_delivered');
            }
            // precisa estar pago ou com promessa de pagamento
            if (!Number::isEqual($this->total, $this->pago + $this->lancado)) {
                $errors['pago'] = __('messages.order_incomplete_payment');
            }
        }
        if (in_array($this->tipo, [self::TIPO_ENTREGA, self::TIPO_BALCAO])) {
            // no balcão ou entrega precisa ter algum item no pedido
            if (!$this->itens()->exists()) {
                $errors['total'] = __('messages.no_item_added');
            }
        }
        // só fecha conta de mesas e comandas
        if (
            !in_array($this->tipo, [self::TIPO_MESA, self::TIPO_COMANDA]) &&
            $this->estado == self::ESTADO_FECHADO
        ) {
            $errors['estado'] = __('messages.only_pause_local_order');
        }
        // ao fechar o pedido, não pode ter pagamento pendente
        if ($this->estado == self::ESTADO_CONCLUIDO && !Number::isEqual($this->lancado, 0)) {
            $errors['lancado'] = __('messages.order_payment_pending');
        }
        // ao fechar o pedido, tudo precisa estar pago, inclusive o troco entregue
        if ($this->estado == self::ESTADO_CONCLUIDO && !Number::isEqual($this->total, $this->pago)) {
            $errors['pago'] = __('messages.order_unpaid');
        }
        return $errors;
    }
}
