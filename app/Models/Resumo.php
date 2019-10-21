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

/**
 * Resumo de fechamento de caixa, informa o valor contado no fechamento do
 * caixa para cada forma de pagamento
 */
class Resumo extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'resumos';

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
        'movimentacao_id',
        'forma_id',
        'cartao_id',
        'valor',
    ];

    /**
     * Movimentação do caixa referente ao resumo
     */
    public function movimentacao()
    {
        return $this->belongsTo('App\Models\Movimentacao', 'movimentacao_id');
    }

    /**
     * Tipo de pagamento do resumo
     */
    public function forma()
    {
        return $this->belongsTo('App\Models\Forma', 'forma_id');
    }

    /**
     * Cartão da forma de pagamento
     */
    public function cartao()
    {
        return $this->belongsTo('App\Models\Cartao', 'cartao_id');
    }

    public function validate()
    {
    }
}
