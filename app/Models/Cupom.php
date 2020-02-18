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
use App\Interfaces\AfterInsertInterface;
use App\Interfaces\AfterUpdateInterface;
use App\Interfaces\BeforeInsertInterface;
use App\Interfaces\BeforeSaveInterface;
use App\Interfaces\ValidateInsertInterface;
use App\Interfaces\ValidateInterface;
use App\Interfaces\ValidateUpdateInterface;
use App\Util\Mask;
use App\Util\Number;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Informa os cupons de descontos e seus usos
 */
class Cupom extends Model implements
    ValidateInterface,
    ValidateUpdateInterface,
    ValidateInsertInterface,
    BeforeInsertInterface,
    BeforeSaveInterface,
    AfterInsertInterface,
    AfterUpdateInterface
{
    use ModelEvents;

    /**
     * Informa se o desconto será por valor ou porcentagem
     */
    public const TIPO_DESCONTO_VALOR = 'valor';
    public const TIPO_DESCONTO_PORCENTAGEM = 'porcentagem';

    /**
     * Informa a regra para decidir se a quantidade de pedidos permite usar
     * esse cupom
     */
    public const FUNCAO_PEDIDOS_MENOR = 'menor';
    public const FUNCAO_PEDIDOS_IGUAL = 'igual';
    public const FUNCAO_PEDIDOS_MAIOR = 'maior';

    /**
     * Informa a regra para decidir se o valor do pedido permite usar esse
     * cupom
     */
    public const FUNCAO_VALOR_MENOR = 'menor';
    public const FUNCAO_VALOR_IGUAL = 'igual';
    public const FUNCAO_VALOR_MAIOR = 'maior';

    public const CREATED_AT = 'data_registro';
    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cupons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cupom_id',
        'pedido_id',
        'cliente_id',
        'codigo',
        'quantidade',
        'tipo_desconto',
        'valor',
        'porcentagem',
        'incluir_servicos',
        'limitar_pedidos',
        'funcao_pedidos',
        'pedidos_limite',
        'limitar_valor',
        'funcao_valor',
        'valor_limite',
        'validade',
        'cancelado',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'disponivel' => 0,
        'valor' => 0,
        'porcentagem' => 0,
        'limitar_pedidos' => false,
        'funcao_pedidos' => self::FUNCAO_PEDIDOS_MAIOR,
        'pedidos_limite' => 0,
        'limitar_valor' => false,
        'funcao_valor' => self::FUNCAO_VALOR_MAIOR,
        'valor_limite' => 0,
        'cancelado' => false,
    ];

    /**
     * Quantidade de cupons usados
     *
     * @return int
     */
    public function getUsadoAttribute()
    {
        return $this->quantidade - $this->disponivel;
    }

    /**
     * Informa de qual cupom foi usado
     */
    public function cupom()
    {
        return $this->belongsTo(self::class, 'cupom_id');
    }

    /**
     * Informa qual pedido usou ou ganhou este cupom
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    /**
     * Informa o cliente que possui e pode usar esse cupom
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Verifica se pode usar o cupom com base na quantidade de pedidos
     *
     * @return bool
     */
    public function checkOrder()
    {
        if (!$this->limitar_pedidos) {
            return [];
        }
        $using = is_null($this->pedido_id) ? 0 : 1;
        $count = Pedido::where('cliente_id', $this->cliente_id)
            ->where('estado', Pedido::ESTADO_CONCLUIDO)
            ->count() - $using;
        if ($this->funcao_pedidos == self::FUNCAO_PEDIDOS_MENOR) {
            return $count < $this->pedidos_limite ? [] : [
                'funcao_pedidos' => __('messages.cannot_use_coupon_too_order', [
                    'count' => $count,
                    'limit' => $this->pedidos_limite,
                ])
            ];
        }
        if ($this->funcao_pedidos == self::FUNCAO_PEDIDOS_IGUAL) {
            return $count == $this->pedidos_limite ? [] : [
                'funcao_pedidos' => __('messages.cannot_use_coupon_order_quantity', [
                    'count' => $count,
                    'limit' => $this->pedidos_limite,
                ])
            ];
        }
        // FUNCAO_PEDIDOS_MAIOR
        return $count > $this->pedidos_limite ? [] : [
            'funcao_pedidos' => __('messages.cannot_use_coupon_few_order', [
                'count' => $count,
                'limit' => $this->pedidos_limite,
            ])
        ];
    }

    /**
     * Verifica se pode usar o cupom com base no valor do pedido
     *
     * @return bool
     */
    public function checkValue()
    {
        if (!$this->limitar_valor) {
            return [];
        }
        $value = $this->pedido->produtos;
        if ($this->incluir_servicos) {
            $value += $this->pedido->servicos;
        }
        if ($this->funcao_valor == self::FUNCAO_VALOR_MENOR) {
            return Number::isLess($value, $this->valor_limite) ? [] : [
                'funcao_valor' => __('messages.cannot_use_coupon_high_order', [
                    'value' => Mask::money($value, true),
                    'limit' => Mask::money($this->valor_limite, true),
                ])
            ];
        }
        if ($this->funcao_valor == self::FUNCAO_VALOR_IGUAL) {
            return Number::isEqual($value, $this->valor_limite) ? [] : [
                'funcao_valor' => __('messages.cannot_use_coupon_order_products', [
                    'value' => Mask::money($value, true),
                    'limit' => Mask::money($this->valor_limite, true),
                ])
            ];
        }
        // FUNCAO_VALOR_MAIOR
        return Number::isGreater($value, $this->valor_limite) ? [] : [
            'funcao_valor' => __('messages.cannot_use_coupon_low_order', [
                'value' => Mask::money($value, true),
                'limit' => Mask::money($this->valor_limite, true),
            ])
        ];
    }

    public function onInsert()
    {
        // não pode criar cupom já cancelado
        if ($this->cancelado) {
            return ['cancelado' => __('messages.cannot_create_cancelled_coupon')];
        }
        // não informou o cupom que está usando
        if ($this->quantidade < 0 && is_null($this->cupom_id)) {
            return ['cupom_id' => __('messages.source_coupon_not_given')];
        }
        // está usando um cupom que já foi usado
        if ($this->quantidade < 0 && $this->cupom->quantidade < 0) {
            return ['quantidade' => __('messages.source_coupon_not_creation')];
        }
        // o cupom que está usando foi cancelado
        if ($this->quantidade < 0 && $this->cupom->cancelado) {
            return ['cupom_id' => __('messages.source_coupon_cancelled')];
        }
        // todos os cupons já foram usados
        if ($this->quantidade < 0 && $this->cupom->usado >= $this->cupom->quantidade) {
            return ['cupom_id' => __('messages.coupon_full_used')];
        }
        // só pode usar 1 cupom por pedido
        if ($this->quantidade < 0 && $this->quantidade != -1) {
            return ['quantidade' => __('messages.must_use_one_coupon_by_time')];
        }
        // o cliente deve ser informado no uso do cupom
        if ($this->quantidade < 0 && is_null($this->cliente_id)) {
            return ['quantidade' => __('messages.coupon_customer_not_set')];
        }
        // o pedido deve ser informado no uso do cupom
        if ($this->quantidade < 0 && is_null($this->pedido_id)) {
            return ['quantidade' => __('messages.coupon_order_not_set')];
        }
        // o cliente deve ser o mesmo do pedido
        if ($this->quantidade < 0 && $this->pedido->cliente_id != $this->cliente_id) {
            return ['quantidade' => __('messages.coupon_must_have_same_customer_in_order')];
        }
        // verifica se pode usar o cupom com base na quantidade de pedidos
        if ($this->quantidade < 0 && !empty($error = $this->checkOrder())) {
            return $error;
        }
        // verifica se pode usar o cupom com base no valor do pedido
        if ($this->quantidade < 0 && !empty($error = $this->checkValue())) {
            return $error;
        }
        // o cliente não pode usar o mesmo cupom no mesmo pedido
        // nem em outro pedido se o cupom não for dele
        if (
            $this->quantidade < 0 &&
            self::when(is_null($this->cupom->cliente_id), function ($query) {
                return $query->where('pedido_id', $this->pedido_id);
            })
                ->where('cupom_id', $this->cupom_id)
                ->where('cancelado', false)
                ->count() > 0
        ) {
            return ['cupom_id' => __('messages.coupon_already_used')];
        }
    }

    public function onUpdate($old)
    {
        // não pode atualizar um cupom cancelado
        if ($old->cancelado) {
            return ['cancelado' => __('messages.cannot_update_cancelled_coupon')];
        }
        // não pode alterar um uso de cupom, só cancelar
        if ($old->quantidade < 0 && !$this->isChangeAllowed(['cancelado'])) {
            return ['cancelado' => __('messages.cannot_change_used_coupon')];
        }
        // não pode alterar um cupom que já foi usado, só reduzir a quantidade
        if ($old->quantidade > 0 && $old->usado > 0 && !$this->isChangeAllowed(['quantidade', 'validade'])) {
            return ['quantidade' => __('messages.cannot_update_used_coupon')];
        }
        // não pode mudar um cupom para uso
        if ($old->quantidade > 0 && $this->quantidade < 0) {
            return ['quantidade' => __('messages.cannot_change_coupon_type')];
        }
        // não pode reduzir a quantidade se já usou todos
        if ($this->quantidade > 0 && $this->usado > $this->quantidade) {
            return ['quantidade' => __('messages.cannot_reduce_used_coupon')];
        }
    }

    public function validate($old)
    {
        // o código deve conter alguma letras de A-Z e/ou números
        if (!preg_match('/[A-Z\d]{2,}/', $this->codigo)) {
            return ['cupom_id' => __('messages.invalid_coupon_code')];
        }
        // não pode informar o uso se está criando um cupom
        if ($this->quantidade > 0 && !is_null($this->cupom_id)) {
            return ['cupom_id' => __('messages.source_coupon_given_at_creation')];
        }
        // precisa usar ou criar um cupom com quantidade válida
        if ($this->quantidade == 0) {
            return ['quantidade' => __('messages.invalid_coupon_quantity')];
        }
        // o fornecimento de cupom não deve ter pedido
        if ($this->quantidade > 0 && !is_null($this->pedido_id)) {
            return ['pedido_id' => __('messages.coupon_creation_cannot_have_order')];
        }
        // não pode haver mais de um cupom disponível com o mesmo código
        // para o mesmo cliente ou em geral
        if (
            $this->quantidade > 0 &&
            self::where('codigo', $this->codigo)
                ->when(is_null($this->cliente_id), function ($query) {
                    return $query->whereNull('cliente_id');
                })
                ->when(!is_null($this->cliente_id), function ($query) {
                    return $query->where('cliente_id', $this->cliente_id);
                })
                ->where('validade', '>=', Carbon::now())
                ->where('disponivel', '>', 0)
                ->where('cancelado', false)
                ->count() > 0
        ) {
            return ['codigo' => __('messages.coupon_code_already_exists')];
        }
    }

    public function beforeSave($old)
    {
        if ($this->quantidade < 0 || (!is_null($old) && $old->quantidade == $this->quantidade)) {
            return;
        }
        $this->disponivel = $this->quantidade - (is_null($old) ? 0 : $old->usado);
    }

    public function beforeInsert()
    {
        if ($this->quantidade > 0) {
            $this->disponivel = $this->quantidade;
            return;
        }
        $this->tipo_desconto = self::TIPO_DESCONTO_VALOR;
        if ($this->cupom->tipo_desconto == self::TIPO_DESCONTO_VALOR) {
            $this->valor = $this->cupom->valor;
            return;
        }
        // força o pedido ter o valor dos produtos e serviços atualizados
        $this->pedido->calculate();
        $value = $this->pedido->produtos;
        if ($this->incluir_servicos) {
            $value += $this->pedido->servicos;
        }
        $this->valor = $value * $this->cupom->porcentagem / 100;
    }

    public function afterInsert()
    {
        if ($this->quantidade > 0) {
            return;
        }
        // reduz a quantidade disponível
        $this->cupom->increment('disponivel', $this->quantidade);
    }

    public function afterUpdate($old)
    {
        if ($this->quantidade > 0 || !$this->cancelado) {
            return;
        }
        // está cancelando o uso do cupom
        $this->cupom->increment('disponivel', -$this->quantidade);
    }
}
