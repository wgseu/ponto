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
 * Informa as propriedades da composição de um produto composto
 */
class Composicao extends Model implements ValidateInterface
{
    use ModelEvents;
    use SoftDeletes;

    public const DELETED_AT = 'data_remocao';

    /**
     * Tipo de composição, 'Composicao' sempre retira do estoque, 'Opcional'
     * permite desmarcar na venda, 'Adicional' permite adicionar na venda
     */
    public const TIPO_COMPOSICAO = 'composicao';
    public const TIPO_OPCIONAL = 'opcional';
    public const TIPO_ADICIONAL = 'adicional';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'composicoes';

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
        'composicao_id',
        'produto_id',
        'tipo',
        'quantidade',
        'valor',
        'quantidade_maxima',
        'ativa',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'tipo' => self::TIPO_COMPOSICAO,
        'valor' => 0,
        'quantidade_maxima' => 1,
        'ativa' => true,
    ];

    /**
     * Informa a qual produto pertence essa composição, deve sempre ser um
     * produto do tipo Composição
     */
    public function composicao()
    {
        return $this->belongsTo(Produto::class, 'composicao_id');
    }

    /**
     * Produto ou composição que faz parte dessa composição, Obs: Não pode ser
     * um pacote
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function validate($old)
    {
    }
}
