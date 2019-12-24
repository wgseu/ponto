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
use App\Interfaces\ValidateUpdateInterface;
use App\Exceptions\ValidationException;

/**
 * Formas de pagamento disponíveis para pedido e contas
 */
class Forma extends Model implements ValidateInterface, ValidateUpdateInterface
{
    use ModelEvents;

    /**
     * Tipo de pagamento
     */
    public const TIPO_DINHEIRO = 'dinheiro';
    public const TIPO_CREDITO = 'credito';
    public const TIPO_DEBITO = 'debito';
    public const TIPO_VALE = 'vale';
    public const TIPO_CHEQUE = 'cheque';
    public const TIPO_CREDIARIO = 'crediario';
    public const TIPO_SALDO = 'saldo';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'formas';

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
        'tipo',
        'carteira_id',
        'descricao',
        'min_parcelas',
        'max_parcelas',
        'parcelas_sem_juros',
        'juros',
        'ativa',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'min_parcelas' => 1,
        'max_parcelas' => 1,
        'parcelas_sem_juros' => 1,
        'juros' => 0,
        'ativa' => true,
    ];

    /**
     * Carteira que será usada para entrada de valores no caixa
     */
    public function carteira()
    {
        return $this->belongsTo(Carteira::class, 'carteira_id');
    }

    public function validate($old)
    {
        $errors = [];
        if (
            !is_null($this->parcelas_sem_juros) &&
            !is_null($this->min_parcelas) &&
            $this->parcelas_sem_juros < $this->min_parcelas
        ) {
            $errors['parcelas_sem_juros'] = __('messages.minimum_installments_not_allowed');
        }
        if (
            !is_null($this->min_parcelas) &&
            !is_null($this->max_parcelas) &&
            $this->min_parcelas > $this->max_parcelas
        ) {
            $errors['max_parcelas'] = __('messages.maximum_portion_allows');
        }
        return $errors;
    }

    public function onUpdate($old)
    {
        $errors = [];
        $old_forma = self::find($this->id);
        $count = Pagamento::where('forma_id', $this->id)->count();
        if ($old_forma->exists() && $count > 0 && $old_forma->tipo != $this->tipo) {
            $errors['tipo'] = __('messages.tipo_cannot_change');
        }
        return $errors;
    }
}
