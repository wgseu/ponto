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
use Illuminate\Support\Carbon;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Sessão de trabalho do dia, permite que vários caixas sejam abertos
 * utilizando uma mesma sessão
 */
class Sessao extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sessoes';

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
        'cozinha_id',
        'data_inicio',
        'data_termino',
        'aberta',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'aberta' => true,
    ];

    /**
     * Remo de cozinha que será trabalhado nessa sessão
     */
    public function cozinha()
    {
        return $this->belongsTo('App\Models\Cozinha', 'cozinha_id');
    }
    
    /*
     * Informa se a sessão está aberta
     * @return boolean Check if a of Aberta is selected or checked
     */
    public function aberta()
    {
        return $this->aberta == true;
    }

    /**
     * Cancela sessão
     */
    public function cancel()
    {
        $this->data_termino = Carbon::now();
        $this->aberta = false;
        $this->save();
    }

    public function validate()
    {
    }
}
