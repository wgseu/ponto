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
 * Créditos de clientes
 */
class Credito extends Model implements ValidateInterface
{
    use ModelEvents;

    public const CREATED_AT = 'data_cadastro';
    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'creditos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cliente_id',
        'valor',
        'detalhes',
        'cancelado',
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
     * Cliente a qual o crédito pertence
     */
    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'cliente_id');
    }

    /**
     * Regras:
     * O resgate de credito não pode ser superior ao saldo.de creditos
     * Depois de Cancelados os creditos não podem ser modificados;
     * Não pode cancelar um credito maior que o saldo de creditos.
     * Impossivel tranferir abatimento para outros clientes;
     * Uma transferencia de credito não pode deixar saldo negativo.
     * Impossível criar um credito já cancelando;
     */
    public function validate()
    {
        $errors = [];
        $saldo = self::where('cliente_id', $this->cliente_id)
                    ->where('cancelado', false)
                    ->sum('valor');
        $oldCredito = $this->fresh();
        if ($this->exists && !$this->cancelado) {
            $saldo -= $oldCredito->valor;
        }
        if (!$this->cancelado && $this->valor < 0 && ($saldo + $this->valor) < 0) {
            $errors['valor'] = __('messages.saldo_insufficient');
        }
        if ($this->exists) {
            if ($oldCredito->cancelado) {
                $errors['cancelado'] = __('messages.cancel_cannot_update');
            } elseif ($oldCredito->cliente_id == $this->cliente_id && $this->cancelado && ($saldo - $this->valor) < 0) {
                $errors['cancelado'] = __('messages.cancel_cannot_greater');
            } elseif ($oldCredito->cliente_id != $this->cliente_id && $this->valor < 0) {
                $errors['cliente_id'] = __('messages.cannot_transfer_value_negative');
            } elseif ($oldCredito->cliente_id != $this->cliente_id) {
                $oldsaldo = self::where('cliente_id', $oldCredito->cliente_id)
                    ->where('cancelado', false)
                    ->sum('valor');
                if (($oldsaldo - $oldCredito->valor) < 0) {
                    $errors['cliente_id'] = __('messages.saldo_transfer_insufficient');
                }
            }
        } elseif ($this->cancelado) {
            $errors['cancelado'] = __('messages.cancel_cannot_create');
        }
        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }
}
