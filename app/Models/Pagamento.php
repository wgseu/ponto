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

use Illuminate\Database\Eloquent\Model;

/**
 * Pagamentos de contas e pedidos
 */
class Pagamento extends Model
{
    /**
     * Informa qual o andamento do processo de pagamento
     */
    const ESTADO_ABERTO = 'aberto';
    const ESTADO_AGUARDANDO = 'aguardando';
    const ESTADO_ANALISE = 'analise';
    const ESTADO_PAGO = 'pago';
    const ESTADO_DISPUTA = 'disputa';
    const ESTADO_DEVOLVIDO = 'devolvido';
    const ESTADO_CANCELADO = 'cancelado';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pagamentos';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $fillable = [
        'carteira_id',
        'moeda_id',
        'pagamento_id',
        'agrupamento_id',
        'movimentacao_id',
        'funcionario_id',
        'forma_id',
        'pedido_id',
        'conta_id',
        'cartao_id',
        'cheque_id',
        'crediario_id',
        'credito_id',
        'valor',
        'numero_parcela',
        'parcelas',
        'lancado',
        'codigo',
        'detalhes',
        'estado',
        'data_pagamento',
        'data_compensacao',
        'data_lancamento',
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
}
