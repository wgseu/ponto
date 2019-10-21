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
use Illuminate\Validation\ValidationException;

/**
 * Folha de cheque lançado como pagamento
 */
class Cheque extends Model implements ValidateInterface
{
    use ModelEvents;

    public const CREATED_AT = 'data_cadastro';
    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cheques';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cliente_id',
        'banco_id',
        'agencia',
        'conta',
        'numero',
        'valor',
        'vencimento',
        'cancelado',
        'recolhimento',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'cancelado' => false,
    ];

    /**
     * Cliente que emitiu o cheque
     */
    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'cliente_id');
    }

    /**
     * Banco do cheque
     */
    public function banco()
    {
        return $this->belongsTo('App\Models\Banco', 'banco_id');
    }

    /**
     * Regras:
     * Um cheque não pode ter a mesma agencia conta e numero a mesnos que esteje cancelado;
     * Um cheque não pode ser criado com status de cancelado;
     * Depois de recolhido e cancelado um cheque não pode ser alterado;
     * O valor do cheque não pode ser negativo.
     */
    public function validate()
    {
        $errors = [];
        $oldCheque = self::find($this->id);
        if (!$this->exists) {
            $cheque = self::where('numero', $this->numero)
                ->where('agencia', $this->agencia)
                ->where('conta', $this->conta)
                ->where('cancelado', false);
            if ($cheque->exists()) {
                $errors['numero'] = __('messages.duplicate_cheque');
            }
            if ($this->cancelado) {
                $errors['cancelado'] = __('messages.new_canceled');
            }
        } else {
            if (!is_null($oldCheque->recolhimento)) {
                $errors['recolhimento'] = __('messages.recolhido_cannot_update');
            }
            if ($oldCheque->cancelado) {
                $errors['cancelado'] = __('messages.cancel_cannot_update');
            }
        }
        if ($this->valor < 0) {
            $errors['valor'] = __('messages.value_negative');
        }
        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }
}
