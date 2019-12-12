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
use App\Interfaces\ValidateUpdateInterface;
use App\Util\Filter;
use Illuminate\Database\Eloquent\Model;

/**
 * Telefones dos clientes, apenas o telefone principal deve ser único por
 * cliente
 */
class Telefone extends Model implements ValidateInterface, ValidateUpdateInterface
{
    use ModelEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'telefones';

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
        'cliente_id',
        'pais_id',
        'numero',
        'operadora',
        'servico',
        'principal',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'principal' => false,
    ];

    /**
     * Informa o cliente que possui esse número de telefone
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Informa o país desse número de telefone
     */
    public function pais()
    {
        return $this->belongsTo(Pais::class, 'pais_id');
    }

    public function validate()
    {
        $errors = [];
        $query = self::where('cliente_id', $this->cliente_id)->where(function ($query) {
            $query->where('numero', Filter::include9thDigit($this->numero))
                ->orWhere('numero', Filter::remove9thDigit($this->numero));
        });
        if ($this->exists) {
            $query->where('id', '<>', $this->id);
        }
        if ($query->exists()) {
            $errors['numero'] = __('messages.phone_exists');
        }
        $query = self::where('cliente_id', $this->cliente_id)->where('principal', true);
        if ($this->exists) {
            $query->where('id', '<>', $this->id);
        }
        if ($this->principal && $query->exists()) {
            $errors['numero'] = __('messages.main_phone_exists');
        }
        return $errors;
    }

    public function onUpdate()
    {
        $errors = [];
        $old = $this->fresh();
        if (
            !is_null($this->data_validacao) &&
            (
                $old->numero != $this->numero ||
                $old->pais_id != $this->pais_id
            )
        ) {
            $errors['numero'] = __('messages.phone_mustbe_revalidated');
        }
        if ($old->tentativas >= 3 && $old->codigo_verificacao == $this->codigo_verificacao) {
            $errors['numero'] = __('messages.phone_code_mustbe_regenerated');
        }
        return $errors;
    }
}
