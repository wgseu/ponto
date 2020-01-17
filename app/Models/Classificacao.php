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

use App\Util\Image;
use App\Concerns\ModelEvents;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Classificação se contas, permite atribuir um grupo de contas
 */
class Classificacao extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'classificacoes';

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
        'descricao',
        'icone_url',
        'icone',
    ];

    /**
     * Classificação superior, quando informado, esta classificação será uma
     * subclassificação
     */
    public function classificacao()
    {
        return $this->belongsTo(Classificacao::class, 'classificacao_id');
    }

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getIconeUrlAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        }
        return null;
    }

    public function setIconeUrlAttribute($value)
    {
        if (!is_null($value)) {
            $value = is_null($this->icone_url) ? null : $this->attributes['icone_url'];
        }
        $this->attributes['icone_url'] = $value;
    }

    public function setIconeAttribute($value)
    {
        if (isset($value)) {
            $this->attributes['icone_url'] = Image::upload($value, 'classifications');
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
        if (!is_null($this->icone_url) && $this->icone_url != $old->icone_url) {
            Storage::delete($this->attributes['icone_url']);
        }
        $this->attributes['icone_url'] = is_null($old->icone_url) ? null : $old->attributes['icone_url'];
    }

    /**
     * Regras:
     * subclassificação não pode ser referencia para uma uma nova subclassificação.
     * Depois de usada com referência uma Classificação não pode alterar a subclassificação.
     */
    public function validate($old)
    {
        $errors = [];
        if (!is_null($this->classificacao_id)) {
            $classificacaopai = $this->classificacao;
            if (!is_null($classificacaopai) && !is_null($classificacaopai->classificacao_id)) {
                $errors['classificacao_id'] = __('messages.classificacaopai_already');
            } elseif ($this->id == $this->classificacao_id) {
                $errors['classificacao_id'] = __('messages.classificacaopai_some');
            }
        }
        if ($this->exists) {
            $classificacao = self::where('classificacao_id', $this->id);
            if ($classificacao->exists() && $old->classificacao_id != $this->classificacao_id) {
                $errors['classificacao_id'] = __('messages.classificacao_invalid_update');
            }
        }
        return $errors;
    }
}
