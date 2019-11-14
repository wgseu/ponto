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
use App\Exceptions\SafeValidationException;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Caixas de movimentação financeira
 */
class Caixa extends Model implements ValidateInterface
{
    use ModelEvents;
    use SoftDeletes;

    public const DELETED_AT = 'data_desativada';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'caixas';

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
        'carteira_id',
        'descricao',
        'serie',
        'numero_inicial',
        'ativa',
        'data_desativada',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'serie' => 1,
        'numero_inicial' => 1,
        'ativa' => true,
    ];

    public function carteira()
    {
        return $this->belongsTo('App\Models\Carteira', 'carteira_id');
    }

    /**
     * Regras:
     * Se o caixa estiver em uso não pode ser desativado;
     * O caixa só pode ter a cateira do tipo local;
     */
    public function validate()
    {
        $errors = [];
        $carteira = $this->carteira;
        $movimento = Movimentacao::where('caixa_id', $this->id)
            ->where('aberta', true);
        if ($carteira->tipo != Carteira::TIPO_LOCAL) {
            $errors['carteira_id'] = __('messages.tipo_carteira_invalido');
        }
        if ($movimento->exists() && !$this->ativa) {
            $errors['ativa'] = __('caixa_in_use');
        }
        return $errors;
    }
}
