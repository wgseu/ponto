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

/**
 * Setor de impressão e de estoque
 */
class Setor extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'setores';

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
        'setor_id',
        'nome',
        'descricao',
    ];

    /**
     * Informa o setor que abrange esse subsetor
     */
    public function setor()
    {
        return $this->belongsTo('App\Models\Setor', 'setor_id');
    }

    /**
     * Regras:
     * Subsetor não pode ser referência para uma uma nova subsetor.
     * Depois de ser usuado como referência um setor não pode alterar o setor_id para uma subsetor.
     */
    public function validate()
    {
        $errors = [];
        if (!is_null($this->setor_id)) {
            $setorpai = $this->setor;
            if (!is_null($setorpai) && !is_null($setorpai->setor_id)) {
                $errors['setor_id'] = __('messagens.setorpai_already');
            } elseif ($this->id == $this->setor_id) {
                $errors['setor_id'] = __('messagens.setorpai_some');
            }
        }
        if ($this->exists) {
            $setor = self::where('setor_id', $this->id);
            $oldSetor = $this->fresh();
            if ($setor->exists() && $oldSetor->setor_id != $this->setor_id) {
                $errors['setor_id'] = __('messagens.setorpai_invalid_update');
            }
        }
        if (!empty($errors)) {
            throw SafeValidationException::withMessages($errors);
        }
    }
}
