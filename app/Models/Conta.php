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
use App\Interfaces\AfterSaveInterface;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;
use App\Interfaces\ValidateUpdateInterface;
use App\Util\Upload;
use Illuminate\Support\Facades\Storage;

/**
 * Contas a pagar e ou receber
 */
class Conta extends Model implements
    ValidateInterface,
    ValidateUpdateInterface,
    AfterSaveInterface
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
        'conta_id',
        'agrupamento_id',
        'carteira_id',
        'cliente_id',
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
        'anexo',
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
        return $this->belongsTo(Classificacao::class, 'classificacao_id');
    }

    /**
     * Funcionário que lançou a conta
     */
    public function funcionario()
    {
        return $this->belongsTo(Prestador::class, 'funcionario_id');
    }

    /**
     * Informa a conta principal
     */
    public function conta()
    {
        return $this->belongsTo(Conta::class, 'conta_id');
    }

    /**
     * Informa se esta conta foi agrupada e não precisa ser mais paga
     * individualmente, uma conta agrupada é tratada internamente como
     * desativada
     */
    public function agrupamento()
    {
        return $this->belongsTo(Conta::class, 'agrupamento_id');
    }

    /**
     * Informa a carteira que essa conta será paga automaticamente ou para
     * informar as contas a pagar dessa carteira
     */
    public function carteira()
    {
        return $this->belongsTo(Carteira::class, 'carteira_id');
    }

    /**
     * Cliente a qual a conta pertence
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Pedido da qual essa conta foi gerada
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    /**
     * Devolve a lista de todas as subcontas não canceladas
     */
    public function contas()
    {
        return $this->hasMany(self::class, 'conta_id')
            ->where('estado', '<>', self::ESTADO_CANCELADA);
    }

    /**
     * Devolve a lista com as contas agrupadas no primeiro nível
     */
    public function agrupamentos()
    {
        return $this->hasMany(self::class, 'agrupamento_id');
    }

    /**
     * Devolve a lista de todos os pagamentos não cancelados
     */
    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class, 'conta_id')
            ->where('estado', '<>', Pagamento::ESTADO_CANCELADO);
    }

    /**
     * Get the user's first name.
     *
     * @param  string $value
     * @return string
     */
    public function getAnexoUrlAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        }
        return null;
    }

    public function setAnexoUrlAttribute($value)
    {
        if (!is_null($value)) {
            $value = is_null($this->anexo_url) ? null : $this->attributes['anexo_url'];
        }
        $this->attributes['anexo_url'] = $value;
    }

    public function setAnexoAttribute($value)
    {
        if (isset($value)) {
            $this->attributes['anexo_url'] = Upload::send($value, 'docs/accounts', 'private');
        }
    }

    /**
     * Limpa os recursos do cliente atual se alterado
     *
     * @param self $old
     * @return void
     */
    public function clean($old)
    {
        if (!is_null($this->anexo_url) && $this->anexo_url != $old->anexo_url) {
            Storage::delete($this->attributes['anexo_url']);
        }
        $this->attributes['anexo_url'] = is_null($old->anexo_url) ? null : $old->attributes['anexo_url'];
    }

    public function validate($old)
    {
        $errors = [];
        if (!is_null($this->pedido_id)) {
            $pedido = $this->pedido;
            if ($pedido->estado != Pedido::ESTADO_CANCELADO && $this->estado == Conta::ESTADO_CANCELADA) {
                $errors['pedido_id'] = __('messages.account_not_canceled_order_open');
            }
        }
        if ($this->automatico == true && $this->tipo != Conta::TIPO_DESPESA) {
            $errors['pedido_id'] = __('messages.automatic_order_not_recipe');
        }
        if ($this->frequencia < 0) {
            $errors['frequencia'] = __('messages.frequency_lowest_zero');
        }
        if (is_null($this->carteira_id) && $this->automatico == true) {
            $errors['carteira_id'] = __('messages.wallet_null_not_automatic');
        }
        $agrupamento = $this->agrupamento;
        if (
            !is_null($agrupamento)
            && $agrupamento->estado == Conta::ESTADO_CANCELADA
        ) {
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
        $conta = $this->conta;
        if (!is_null($conta) && !is_null($conta->conta_id)) {
            $errors['conta_id'] = __('messages.account_have_conta_id');
        }
        if ($this->tipo == self::TIPO_RECEITA && Number::isGreater($this->consolidado, $this->total())) {
            $errors['valor'] = __('messages.total_received_greater_account');
        }
        if (
            $this->tipo == self::TIPO_DESPESA
            && Number::isGreater(-$this->consolidado, (-$this->valor - $this->acrescimo))
        ) {
            $errors['valor'] = __('messages.total_paid_greater_account');
        }
        $cliente = $this->cliente;
        if (!is_null($this->cliente_id) && $this->tipo == self::TIPO_RECEITA && $cliente->limite_compra > 0) {
            $query = self::where('cliente_id', $this->cliente_id)
                ->where('estado', self::ESTADO_ATIVA);
            if ($this->exists) {
                $query->where('id', '<>', $this->id);
            }
            $utilizado = $query->sum('valor + acrescimo - consolidado');
            if ($this->total() + $utilizado > $cliente->limite_compra) {
                $restante = ($this->total() + $utilizado) - $cliente->limite_compra;
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
        return $errors;
    }

    public function onUpdate($old)
    {
        $errors = [];
        if ($old->estado == self::ESTADO_PAGA && $this->estado != self::ESTADO_CANCELADA) {
            $errors['id'] = __('messages.account_cannot_changed_consolidated');
        }
        if ($old->estado == self::ESTADO_CANCELADA) {
            $errors['id'] = __('messages.account_cannot_changed_canceled');
        }
        if (
            ($this->estado == self::ESTADO_PAGA
                && !Number::isEqual($this->consolidado, $this->total()))
            || ($old->estado == self::ESTADO_PAGA
                && !Number::isEqual($old->consolidado, $this->total()))
        ) {
            $errors['id'] = __('messages.account_cannot_changed_total');
        }
        if (
            !is_null($old->agrupamento_id)
            && !is_null($this->agrupamento_id)
            && $this->estado != Conta::ESTADO_CANCELADA
        ) {
            $errors['agrupamento_id'] = __('messages.grouping_no_change');
        }
        return $errors;
    }

    public function afterSave($old)
    {
        if ($this->estado == self::ESTADO_CANCELADA) {
            $this->cancelDependecies();
        }
    }

    private function cancelDependecies()
    {
        // desagrupa as contas dependentes
        $agrupamentos = $this->agrupamentos;
        foreach ($agrupamentos as $conta) {
            $conta->update(['agrupamento_id' => null]);
        }
        // cancela os pagamentos dessa conta
        $pagamentos = $this->pagamentos;
        foreach ($pagamentos as $pagamento) {
            $pagamento->update(['estado' => Pagamento::ESTADO_CANCELADO]);
        }
        // cancela as subcontas
        $contas = $this->contas;
        foreach ($contas as $conta) {
            $conta->update(['estado' => self::ESTADO_CANCELADA]);
        }
    }

    public function total()
    {
        return $this->valor + $this->acrescimo;
    }
}
