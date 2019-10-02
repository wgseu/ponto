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
 * Junções de mesas, informa quais mesas estão juntas ao pedido
 */
class Juncao extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * Estado a junção da mesa. Associado: a mesa está junta ao pedido,
     * Liberado: A mesa está livre, Cancelado: A mesa está liberada
     */
    const ESTADO_ASSOCIADO = 'associado';
    const ESTADO_LIBERADO = 'liberado';
    const ESTADO_CANCELADO = 'cancelado';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'juncoes';

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
        'mesa_id',
        'pedido_id',
        'estado',
        'data_movimento',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'estado' => self::ESTADO_ASSOCIADO,
    ];

    /**
     * Mesa que está junta ao pedido
     */
    public function mesa()
    {
        return $this->belongsTo('App\Models\Mesa', 'mesa_id');
    }

    /**
     * Pedido a qual a mesa está junta, o pedido deve ser de uma mesa
     */
    public function pedido()
    {
        return $this->belongsTo('App\Models\Pedido', 'pedido_id');
    }

    public function validate()
    {
    }
}
