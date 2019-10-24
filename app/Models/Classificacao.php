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
    ];

    /**
     * Classificação superior, quando informado, esta classificação será uma
     * subclassificação
     */
    public function classificacao()
    {
        return $this->belongsTo('App\Models\Classificacao', 'classificacao_id');
    }

    /**
     * Regras:
     * subclassificação não pode ser referencia para uma uma nova subclassificação.
     * Depois de usada com referência uma Classificação não pode alterar a subclassificação.
     */
    public function validate()
    {
        $errors = [];
        if (!is_null($this->classificacao_id)) {
            $classificacaopai = $this->classificacao;
            if (!is_null($classificacaopai) && !is_null($classificacaopai->classificacao_id)) {
                $errors['classificacao_id'] = __('messagens.classificacaopai_already');
            } elseif ($this->id == $this->classificacao_id) {
                $errors['classificacao_id'] = __('messagens.classificacaopai_some');
            }
        }
        if ($this->exists) {
            $classificacao = self::where('classificacao_id', $this->id);
            $oldClassificacao = $this->fresh();
            if ($classificacao->exists() && $oldClassificacao->classificacao_id != $this->classificacao_id) {
                $errors['classificacao_id'] = __('messagens.classificacao_invalid_update');
            }
        }
        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }
}
