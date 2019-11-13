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

use App\Util\Mask;
use App\Util\Number;
use App\Concerns\ModelEvents;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\SafeValidationException;
use App\Interfaces\ValidateUpdateInterface;

/**
 * Contas a pagar e ou receber
 */
class Conta extends Model implements ValidateInterface, ValidateUpdateInterface
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
        'tipo' => self::TIPO_RECEITA,
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
        $errors = [];
        if (!is_null($this->pedido_id)) {
            $pedido = $this->pedido;
            if ($pedido->estado != Pedido::ESTADO_CANCELADO) {
                $errors['pedido_id'] = __('messages.account_not_canceled_order_open');
            } elseif ($this->automatico == true && $this->tipo != Conta::TIPO_RECEITA) {
                $errors['pedido_id'] = __('messages.automatic_order_not_recipe');
            }
        }
        if (($this->modo == Conta::MODO_MENSAL || $this->modo == Conta::MODO_DIARIO) && $this->frequencia <= 0) {
            $errors['frequencia'] = __('messages.frequency_lowest_zero');
        } elseif ($this->modo == Conta::MODO_DIARIO && $this->frequencia > 31) {
            $errors['frequencia'] = __('messages.frequency_higher', ['value' => $this->frequencia]);
        }
        if (is_null($this->carteira_id) && $this->automatico == true) {
            $errors['carteira_id'] = __('messages.wallet_null_not_automatic');
        }
        $agrupamento = self::find($this->agrupamento_id);
        if (!is_null($this->agrupamento_id) && $agrupamento->estado == Conta::ESTADO_CANCELADA) {
            $errors['agrupamento_id'] = __('messages.grouping_already_canceled');
        }
        if (Number::isEqual($this->valor, 0)) {
            $errors['valor'] = __('messages.valor_cannot_zero');
        } elseif ($this->valor < 0 && $this->tipo == self::TIPO_RECEITA) {
            $errors['valor'] = __('messages.receita_negativa');
        } elseif ($this->valor > 0 && $this->tipo == self::TIPO_DESPESA) {
            $errors['valor'] = __('messages.despesa_positiva');
        }
        if ($this->tipo == self::TIPO_RECEITA && $this->acrescimo < 0) {
            $errors['acrescimo'] = __('messages.acrescimo_cannot_negative');
        } elseif ($this->tipo == self::TIPO_DESPESA && $this->acrescimo > 0) {
            $errors['acrescimo'] = __('messages.acrescimo_cannot_positive');
        }
        if ($this->tipo == self::TIPO_RECEITA && $this->multa < 0) {
            $errors['multa'] = __('messages.multa_cannot_negative');
        } elseif ($this->tipo == self::TIPO_DESPESA && $this->multa > 0) {
            $errors['multa'] = __('messages.multa_cannot_positive');
        }
        if ($this->juros < 0) {
            $errors['juros'] = __('messages.juros_cannot_negative');
        }
        $conta_id = self::find($this->conta_id);
        if (!is_null($conta_id)) {
            if (!is_null($conta_id->conta_id)) {
                $errors['conta_id'] = __('messages.account_have_conta_id');
            }
        }
        if ($this->tipo == self::TIPO_RECEITA && Number::isGreater($this->consolidado, $this->valor)) {
            $errors['valor'] = __('messages.total_received_greater_account');
        }
        if ($this->tipo == self::TIPO_DESPESA && Number::isGreater(-$this->consolidado, -$this->valor)) {
            $errors['valor'] = __('messages.total_paid_greater_account');
        }
        if (!is_null($this->cliente_id) && $this->valor > 0) {
            $cliente = $this->cliente;
            if ($cliente->limite_compra > 0) {
                $query = self::where('cliente_id', $this->cliente_id)
                    ->where('estado', self::ESTADO_ATIVA);
                if ($this->exists) {
                    $query->where('id', '<>', $this->id);
                }
                $utilizado = $query->sum('valor - consolidado');
                if ($this->valor + $utilizado > $cliente->limite_compra) {
                    $restante = ($this->valor + $utilizado) - $cliente->limite_compra;
                    $errors['valor'] = __(
                        'messages.account_no_limit',
                        [
                            'customer' => $cliente->getNomeCompleto(),
                            'remaining' => Mask::money($restante, true),
                            'used' => Mask::money($utilizado, true),
                            'limit' => Mask::money($cliente->limite_compra, true)
                        ]
                    );
                }
            }
        }
        if (!empty($errors)) {
            throw SafeValidationException::withMessages($errors);
        }
    }

    public function onUpdate()
    {
        $errors = [];
        $old_conta = self::find($this->id);
        if ($old_conta->estado == self::ESTADO_PAGA && $this->estado != self::ESTADO_CANCELADA) {
            $errors['id'] = __('messages.account_cannot_changed_consolidated');
        }
        if ($old_conta->estado == self::ESTADO_CANCELADA) {
            $errors['id'] = __('messages.account_cannot_changed_canceled');
        }
        if (
            ($this->estado == self::ESTADO_PAGA
                && !Number::isEqual($this->consolidado, $this->valor))
            || ($old_conta->estado == self::ESTADO_PAGA
                && !Number::isEqual($old_conta->consolidado, $this->valor))
        ) {
            $errors['id'] = __('messages.account_cannot_changed_total');
        }
        if (
            !is_null($old_conta->agrupamento_id)
            && !is_null($this->agrupamento_id)
            && $this->estado != Conta::ESTADO_CANCELADA
        ) {
            $errors['agrupamento_id'] = __('messages.grouping_no_change');
        }
        if (!empty($errors)) {
            throw SafeValidationException::withMessages($errors);
        }
    }

    public function cancel($recursive = false)
    {
        DB::transaction(function () use ($recursive) {
            $this->internalCancel($recursive);
        });
    }

    private function internalCancel($recursive)
    {
        $this->estado = self::ESTADO_CANCELADA;
        $this->save();
        $pagamentos = Pagamento::where('conta_id', $this->id)
            ->where('estado', '<>', Pagamento::ESTADO_CANCELADO)->get();
        foreach ($pagamentos as $pagamento) {
            $pagamento->cancel();
        }
        $contas = self::where('agrupamento_id', $this->id)
            ->where('estado', '<>', self::ESTADO_CANCELADA)->get();
        foreach ($contas as $conta) {
            $conta->agrupamento_id = null;
            $conta->save();
            if ($recursive) {
                $conta->internalCancel($recursive);
            }
        }
    }
}
