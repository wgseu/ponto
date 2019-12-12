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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Pagamentos de contas e pedidos
 */
class Pagamento extends Model implements
    ValidateInterface,
    ValidateInsertInterface,
    ValidateUpdateInterface
{
    use ModelEvents;

    /**
     * Informa qual o andamento do processo de pagamento
     */
    public const ESTADO_ABERTO = 'aberto';
    public const ESTADO_AGUARDANDO = 'aguardando';
    public const ESTADO_ANALISE = 'analise';
    public const ESTADO_PAGO = 'pago';
    public const ESTADO_DISPUTA = 'disputa';
    public const ESTADO_DEVOLVIDO = 'devolvido';
    public const ESTADO_CANCELADO = 'cancelado';

    public const CREATED_AT = 'data_lancamento';
    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pagamentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'moeda_id',
        'pagamento_id',
        'movimentacao_id',
        'forma_id',
        'cartao_id',
        'numero_parcela',
        'parcelas',
        'lancado',
        'codigo',
        'detalhes',
        'estado',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'numero_parcela' => 1,
        'parcelas' => 1,
        'estado' => self::ESTADO_ABERTO,
    ];

    /**
     * Carteira de destino do valor
     */
    public function carteira()
    {
        return $this->belongsTo(Carteira::class, 'carteira_id');
    }

    /**
     * Informa em qual moeda está o valor informado
     */
    public function moeda()
    {
        return $this->belongsTo(Moeda::class, 'moeda_id');
    }

    /**
     * Informa o pagamento principal ou primeira parcela, o valor lançado é
     * zero para os pagamentos filhos, restante de antecipação e taxas são
     * filhos do valor antecipado
     */
    public function pagamento()
    {
        return $this->belongsTo(Pagamento::class, 'pagamento_id');
    }

    /**
     * Permite antecipar recebimentos de cartões, um pagamento agrupado é
     * internamente tratado como desativado
     */
    public function agrupamento()
    {
        return $this->belongsTo(Pagamento::class, 'agrupamento_id');
    }

    /**
     * Movimentação do caixa quando for pagamento de pedido ou quando a conta
     * for paga do caixa
     */
    public function movimentacao()
    {
        return $this->belongsTo(Movimentacao::class, 'movimentacao_id');
    }

    /**
     * Funcionário que lançou o pagamento no sistema
     */
    public function funcionario()
    {
        return $this->belongsTo(Prestador::class, 'funcionario_id');
    }

    /**
     * Forma da pagamento do pedido
     */
    public function forma()
    {
        return $this->belongsTo(Forma::class, 'forma_id');
    }

    /**
     * Pedido que foi pago
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    /**
     * Conta que foi paga/recebida
     */
    public function conta()
    {
        return $this->belongsTo(Conta::class, 'conta_id');
    }

    /**
     * Cartão em que foi pago, para forma de pagamento em cartão
     */
    public function cartao()
    {
        return $this->belongsTo(Cartao::class, 'cartao_id');
    }

    /**
     * Cheque em que foi pago
     */
    public function cheque()
    {
        return $this->belongsTo(Cheque::class, 'cheque_id');
    }

    /**
     * Conta que foi utilizada como pagamento do pedido
     */
    public function crediario()
    {
        return $this->belongsTo(Conta::class, 'crediario_id');
    }

    /**
     * Crédito que foi utilizado para pagar o pedido
     */
    public function credito()
    {
        return $this->belongsTo(Credito::class, 'credito_id');
    }

    /**
     * Informa se o pagamento foi aprovado
     *
     * @return bool
     */
    public function pago()
    {
        return $this->estado == self::ESTADO_PAGO && is_null($this->agrupamento_id);
    }

    /**
     * Calcula o valor na moeda escolhida e preenche outras informações
     *
     * @return self
     */
    public function calculate()
    {
        $cartao = $this->cartao;
        $this->carteira_id = null;
        if (!is_null($cartao)) {
            $this->carteira_id = $cartao->carteira_id;
        }
        $forma = $this->forma;
        if (!is_null($forma) && is_null($this->carteira_id)) {
            $this->carteira_id = $forma->carteira_id;
        }
        $moeda = $this->moeda;
        $this->valor = $moeda->conversao * $this->lancado;
        if ($this->estado == self::ESTADO_PAGO) {
            $this->data_pagamento = Carbon::now();
            $this->data_compensacao = $this->data_pagamento;
            if (!is_null($cartao)) {
                $this->data_compensacao = $this->data_pagamento->addDays($cartao->dias_repasse);
            } elseif (!is_null($this->cheque_id)) {
                $this->data_compensacao = null;
            }
        } else {
            $this->data_pagamento = null;
            $this->data_compensacao = null;
        }
        return $this;
    }

    /**
     * Atualiza a contagem do produto
     *
     * @return void
     */
    protected function updateWallet()
    {
        $old_valor = 0;
        if ($this->exists) {
            $old = $this->fresh();
            if (
                $old->pago() == $this->pago() &&
                ($old->valor == $this->valor || !$this->pago())
            ) {
                return;
            }
            if ($old->pago()) {
                $old_valor = $old->valor;
            }
        } elseif (!$this->pago()) {
            return;
        }
        if (!$this->pago()) {
            $valor = -$old_valor;
        } else {
            $valor = $this->valor - $old_valor;
        }
        $saldo = Saldo::firstOrCreate(
            [
                'moeda_id' => $this->moeda_id,
                'carteira_id' => $this->carteira_id,
            ],
            [ 'valor' => 0 ]
        );
        $saldo->increment('valor', $valor);
    }

    protected function cancel()
    {
        // TODO: cancelar crediário
        // TODO: cancelar uso de crédito
        // TODO: cancelar emissão de cheque
    }

    public function validate()
    {
        if (is_null($this->data_pagamento) && $this->pago()) {
            return ['data_pagamento' => __('messages.no_payment_date')];
        }
    }

    public function onInsert()
    {
        if ($this->estado == self::ESTADO_CANCELADO) {
            return ['estado' => __('messages.payment_inserting_cancelled')];
        }
        $this->updateWallet();
    }

    public function onUpdate()
    {
        $old = $this->fresh();
        if ($old->estado == self::ESTADO_CANCELADO) {
            return ['estado' => __('messages.payment_already_cancelled')];
        } elseif ($this->estado == self::ESTADO_CANCELADO) {
            $this->cancel();
        }
        $this->updateWallet();
    }
}
