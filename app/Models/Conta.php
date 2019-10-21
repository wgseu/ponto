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

/**
 * Contas a pagar e ou receber
 */
class Conta extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * Tipo de conta se receita ou despesa
     */
    public const TIPO_RECEITA = 'receita';
    public const TIPO_DESPESA = 'despesa';

    /**
     * Fonte dos valores, comissão e remuneração se pagar antes do vencimento,
     * o valor será proporcional
     */
    public const FONTE_FIXA = 'fixa';
    public const FONTE_VARIAVEL = 'variavel';
    public const FONTE_COMISSAO = 'comissao';
    public const FONTE_REMUNERACAO = 'remuneracao';

    /**
     * Modo de cobrança se diário ou mensal, a quantidade é definida em
     * frequencia
     */
    public const MODO_DIARIO = 'diario';
    public const MODO_MENSAL = 'mensal';

    /**
     * Fórmula de juros que será cobrado em caso de atraso
     */
    public const FORMULA_SIMPLES = 'simples';
    public const FORMULA_COMPOSTO = 'composto';

    /**
     * Informa o estado da conta
     */
    public const ESTADO_ANALISE = 'analise';
    public const ESTADO_ATIVA = 'ativa';
    public const ESTADO_PAGA = 'paga';
    public const ESTADO_CANCELADA = 'cancelada';
    public const ESTADO_DESATIVADA = 'desativada';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contas';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'classificacao_id',
        'funcionario_id',
        'conta_id',
        'agrupamento_id',
        'carteira_id',
        'cliente_id',
        'pedido_id',
        'tipo',
        'descricao',
        'valor',
        'consolidado',
        'fonte',
        'numero_parcela',
        'parcelas',
        'frequencia',
        'modo',
        'automatico',
        'acrescimo',
        'multa',
        'juros',
        'formula',
        'vencimento',
        'numero',
        'anexo_url',
        'estado',
        'data_calculo',
        'data_emissao',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'tipo' => self::TIPO_DESPESA,
        'consolidado' => 0,
        'fonte' => self::FONTE_FIXA,
        'numero_parcela' => 1,
        'parcelas' => 1,
        'frequencia' => 0,
        'modo' => self::MODO_MENSAL,
        'automatico' => false,
        'acrescimo' => 0,
        'multa' => 0,
        'juros' => 0,
        'formula' => self::FORMULA_COMPOSTO,
        'estado' => self::ESTADO_ATIVA,
    ];

    /**
     * Classificação da conta
     */
    public function classificacao()
    {
        return $this->belongsTo('App\Models\Classificacao', 'classificacao_id');
    }

    /**
     * Funcionário que lançou a conta
     */
    public function funcionario()
    {
        return $this->belongsTo('App\Models\Prestador', 'funcionario_id');
    }

    /**
     * Informa a conta principal
     */
    public function conta()
    {
        return $this->belongsTo('App\Models\Conta', 'conta_id');
    }

    /**
     * Informa se esta conta foi agrupada e não precisa ser mais paga
     * individualmente, uma conta agrupada é tratada internamente como
     * desativada
     */
    public function agrupamento()
    {
        return $this->belongsTo('App\Models\Conta', 'agrupamento_id');
    }

    /**
     * Informa a carteira que essa conta será paga automaticamente ou para
     * informar as contas a pagar dessa carteira
     */
    public function carteira()
    {
        return $this->belongsTo('App\Models\Carteira', 'carteira_id');
    }

    /**
     * Cliente a qual a conta pertence
     */
    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'cliente_id');
    }

    /**
     * Pedido da qual essa conta foi gerada
     */
    public function pedido()
    {
        return $this->belongsTo('App\Models\Pedido', 'pedido_id');
    }

    public function validate()
    {
    }
}
