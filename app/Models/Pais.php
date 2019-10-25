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

use App\Core\Settings;
use App\Concerns\ModelEvents;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Informações de um páis com sua moeda e língua nativa
 */
class Pais extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paises';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Setting model
     * 
     * @var App\Models\Settings
     */
    public $entries;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'sigla',
        'codigo',
        'moeda_id',
        'idioma',
        'prefixo',
        'entradas',
        'unitario',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'unitario' => false,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->entries = new Settings();
    }

    public function loadEntries()
    {
        $this->entries->addValues(json_decode(base64_decode($this->entradas)));
    }

    public function applyEntries()
    {
        $this->entradas = base64_encode(json_encode($this->entries->getValues()));
    }

    /**
     * Informa a moeda principal do país
     */
    public function moeda()
    {
        return $this->belongsTo('App\Models\Moeda', 'moeda_id');
    }

    public function validate()
    {
    }
}
