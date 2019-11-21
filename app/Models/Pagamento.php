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
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Pagamentos de contas e pedidos
 */
class Pagamento extends Model implements ValidateInterface
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
        return $this->belongsTo('App\Models\Carteira', 'carteira_id');
    }

    /**
     * Informa em qual moeda está o valor informado
     */
    public function moeda()
    {
        return $this->belongsTo('App\Models\Moeda', 'moeda_id');
    }

    /**
     * Informa o pagamento principal ou primeira parcela, o valor lançado é
     * zero para os pagamentos filhos, restante de antecipação e taxas são
     * filhos do valor antecipado
     */
    public function pagamento()
    {
        return $this->belongsTo('App\Models\Pagamento', 'pagamento_id');
    }

    /**
     * Permite antecipar recebimentos de cartões, um pagamento agrupado é
     * internamente tratado como desativado
     */
    public function agrupamento()
    {
        return $this->belongsTo('App\Models\Pagamento', 'agrupamento_id');
    }

    /**
     * Movimentação do caixa quando for pagamento de pedido ou quando a conta
     * for paga do caixa
     */
    public function movimentacao()
    {
        return $this->belongsTo('App\Models\Movimentacao', 'movimentacao_id');
    }

    /**
     * Funcionário que lançou o pagamento no sistema
     */
    public function funcionario()
    {
        return $this->belongsTo('App\Models\Prestador', 'funcionario_id');
    }

    /**
     * Forma da pagamento do pedido
     */
    public function forma()
    {
        return $this->belongsTo('App\Models\Forma', 'forma_id');
    }

    /**
     * Pedido que foi pago
     */
    public function pedido()
    {
        return $this->belongsTo('App\Models\Pedido', 'pedido_id');
    }

    /**
     * Conta que foi paga/recebida
     */
    public function conta()
    {
        return $this->belongsTo('App\Models\Conta', 'conta_id');
    }

    /**
     * Cartão em que foi pago, para forma de pagamento em cartão
     */
    public function cartao()
    {
        return $this->belongsTo('App\Models\Cartao', 'cartao_id');
    }

    /**
     * Cheque em que foi pago
     */
    public function cheque()
    {
        return $this->belongsTo('App\Models\Cheque', 'cheque_id');
    }

    /**
     * Conta que foi utilizada como pagamento do pedido
     */
    public function crediario()
    {
        return $this->belongsTo('App\Models\Conta', 'crediario_id');
    }

    /**
     * Crédito que foi utilizado para pagar o pedido
     */
    public function credito()
    {
        return $this->belongsTo('App\Models\Credito', 'credito_id');
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
            }
        } else {
            $this->data_pagamento = null;
            $this->data_compensacao = null;
        }
        return $this;
    }

    protected function cancel()
    {
        // TODO: reduzir carteira
        // TODO: cancelar crediário
        // TODO: cancelar uso de crédito
        // TODO: cancelar emissão de cheque
    }

    public function validate()
    {
    }

    public function onUpdate()
    {
        $erros = [];
        $old = $this->fresh();
        if ($old->estado == self::ESTADO_CANCELADO) {
            $erros['estado'] = __('messages.payment_already_cancelled');
        } elseif ($this->estado == self::ESTADO_CANCELADO) {
            $this->cancel();
        }
    }
}
