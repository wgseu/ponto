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
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Contém todos as opções para a formação do produto final
 */
class Pacote extends Model implements ValidateInterface
{
    use ModelEvents;
    use SoftDeletes;

    const DELETED_AT = 'data_arquivado';

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
        return $this->belongsTo('App\Models\Produto', 'pacote_id');
    }

    /**
     * Grupo de formação, Ex.: Tamanho, Sabores e Complementos.
     */
    public function grupo()
    {
        return $this->belongsTo('App\Models\Grupo', 'grupo_id');
    }

    /**
     * Produto selecionável do grupo. Não pode conter propriedade.
     */
    public function produto()
    {
        return $this->belongsTo('App\Models\Produto', 'produto_id');
    }

    /**
     * Propriedade selecionável do grupo. Não pode conter produto.
     */
    public function propriedade()
    {
        return $this->belongsTo('App\Models\Propriedade', 'propriedade_id');
    }

    /**
     * Informa a propriedade pai de um complemento, permite atribuir preços
     * diferentes dependendo da propriedade, Ex.: Tamanho -> Sabor, onde
     * Tamanho é pai de Sabor
     */
    public function associacao()
    {
        return $this->belongsTo('App\Models\Pacote', 'associacao_id');
    }

    public function validate()
    {
    }
}
