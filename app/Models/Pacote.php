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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Contém todos as opções para a formação do produto final
 */
class Pacote extends Model implements ValidateInterface, ValidateUpdateInterface
{
    use ModelEvents;
    use SoftDeletes;

    public const DELETED_AT = 'data_arquivado';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pacotes';

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
        'pacote_id',
        'grupo_id',
        'produto_id',
        'propriedade_id',
        'associacao_id',
        'quantidade_minima',
        'quantidade_maxima',
        'acrescimo',
        'selecionado',
        'disponivel',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'quantidade_minima' => 0,
        'quantidade_maxima' => 1,
        'selecionado' => false,
        'disponivel' => true,
    ];

    /**
     * Pacote a qual pertence as informações de formação do produto final
     */
    public function pacote()
    {
        return $this->belongsTo(Produto::class, 'pacote_id');
    }

    /**
     * Grupo de formação, Ex.: Tamanho, Sabores e Complementos.
     */
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    /**
     * Produto selecionável do grupo. Não pode conter propriedade.
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    /**
     * Propriedade selecionável do grupo. Não pode conter produto.
     */
    public function propriedade()
    {
        return $this->belongsTo(Propriedade::class, 'propriedade_id');
    }

    /**
     * Informa a propriedade pai de um complemento, permite atribuir preços
     * diferentes dependendo da propriedade, Ex.: Tamanho -> Sabor, onde
     * Tamanho é pai de Sabor
     */
    public function associacao()
    {
        return $this->belongsTo(Pacote::class, 'associacao_id');
    }

    /**
     * Regras:
     * O produto do pacote_id deve ser do tipo pacote,
     * Produto_id não pode ser do tipo pacote,
     * O produto do grupo deve ser igual ao pacote,
     * O grupo da propriedade deve ser igual ao grupo do pacote,
     * Não é permitido propriedade e produto,
     * Propriedade e pacote não podem ser nulos;
     * Associação depende de uma propriedade,
     * A associções não podem ter o mesmo grupo ou niveis interior ao grupo,
     * A quantidade minima e maxima não pode ser negativas,
     * A minima não pode ser maior que a maxima a menos que a maxima seja 0,
     * A quantidade maxima do pacote não pode superior que a quantidade maxima do grupo,
     * Não é permitido alterar o pacote_id ou o grupo,
     * Não é permitido associar um pacote com ele mesmo,
     * Não é permitido associar uma associação com outra associação.
     */
    public function validate()
    {
        $errors = [];
        $pacote = $this->pacote;
        $produto = $this->produto;
        $grupo = Grupo::find($this->grupo_id);
        $propriedade = $this->propriedade;
        $associacao = $this->associacao;
        $grupo_associado = is_null($associacao) ? null : $associacao->grupo;
        if ($pacote->tipo != Produto::TIPO_PACOTE) {
            $errors['pacote_id'] = __('messages.pacote_cannot_different_pacote');
        }
        if (!is_null($produto) && $produto->tipo == Produto::TIPO_PACOTE) {
            $errors['produto_id'] = __('messages.produto_cannot_type_pacote');
        }
        if ($this->pacote_id != $grupo->produto_id) {
            $errors['grupo_id'] = __('messages.produto_cannot_different_produto_grupo');
        }
        if (!is_null($propriedade) && $this->grupo_id != $propriedade->grupo_id) {
            $errors['propriedade_id'] = __('messages.grupo_cannot_different_grupo_propriedade');
        }
        if (!is_null($this->propriedade_id) && !is_null($this->produto_id)) {
            $errors['propriedade_id'] = __('messages.pacote_require_propriedade_or_produto');
        } elseif (is_null($this->propriedade_id) && is_null($this->produto_id)) {
            $errors['associacao_id'] =  __('messages.propriedade_and_pacote_cannot_null');
        }
        if (
            !is_null($associacao)
            && ($grupo_associado->ordem > $grupo->ordem
            || ($grupo_associado->ordem == $grupo->ordem
                && $associacao->grupo_id >= $this->grupo_id
            ))
        ) {
            $errors['associacao_id'] = __('messages.item_cannot_equals_or_less_group');
        }
        if ($this->quantidade_minima < 0) {
            $errors['quantidade_maxima'] = __('messages.quantidade_minima_cannot_negative');
        } elseif ($this->quantidade_maxima < 0) {
            $errors['quantidade_maxima'] = __('messages.quantidade_maxima_cannot_negative');
        } elseif ($this->quantidade_minima > $this->quantidade_maxima && $this->quantidade_maxima != 0) {
            $errors['quantidade_maxima'] = __('messages.quantidade_minima_cannot_greater_maxima');
        } elseif (
            $this->quantidade_maxima > $grupo->quantidade_maxima &&
            $grupo->quantidade_maxima != 0 &&
            $grupo->tipo == Grupo::TIPO_INTEIRO
        ) {
            $errors['quantidade_maxima'] = __('messages.quantidade_maxima_cannot_greater_maxima_grupo');
        }
        return $errors;
    }

    public function onUpdate()
    {
        $errors = [];
        $oldPacote = $this->fresh();
        if (!is_null($this->associacao_id)) {
            if ($this->id == $this->associacao_id) {
                $errors['associacao_id'] = __('messagens.associacao_some');
            }
        }
        if ($this->pacote_id != $oldPacote->pacote_id) {
            $errors['pacote_id'] = __('messages.cannot_update_pacote');
        }
        if ($this->grupo_id != $oldPacote->grupo_id) {
            $errors['grupo_id'] = __('messages.cannot_update_grupo_pacote');
        }
        return $errors;
    }
}
