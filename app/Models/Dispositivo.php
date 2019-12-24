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
use App\Util\Filter;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Computadores e tablets com opções de acesso
 */
class Dispositivo extends User implements JWTSubject
{
    use ModelEvents;

    /**
     * Tipo de dispositivo
     */
    public const TIPO_COMPUTADOR = 'computador';
    public const TIPO_NAVEGADOR = 'navegador';
    public const TIPO_MOVEL = 'movel';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dispositivos';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Setting model
     *
     * @var Settings
     */
    public $options;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'setor_id',
        'caixa_id',
        'nome',
        'tipo',
        'descricao',
        'opcoes',
        'serial',
        'validacao',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'tipo' => self::TIPO_COMPUTADOR,
    ];

    public function __construct(array $attributes = [])
    {
        $this->options = new Settings();
        parent::__construct($attributes);
    }

    /**
     * Retorna as opções da empresa como string json
     *
     * @return string
     */
    public function getOpcoesAttribute()
    {
        $this->options->includeDefaults = app('settings')->includeDefaults;
        $this->loadOptions();
        return json_encode(Filter::emptyObject($this->options->getValues()));
    }

    public function setOpcoesAttribute($value)
    {
        $this->options->addValues(json_decode($value ?? '{}', true));
        $this->attributes['opcoes'] = base64_encode(json_encode($this->options->getValues(false)));
    }

    public function loadOptions()
    {
        $this->options->addValues(
            json_decode(base64_decode($this->getAttributeFromArray('opcoes')), true)
        );
    }

    /**
     * Informa se o dispositivo está validado e autorizado
     * para realizar consultas na api
     *
     * @return boolean
     */
    public function isValid()
    {
        return !is_null($this->serial) && $this->serial == $this->validacao;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'iss' => null,
            'uid' => null,
            'exp' => Carbon::now('UTC')->addMinutes(365 * 24 * 60)->getTimestamp(),
            'typ' => 'device',
        ];
    }

    public function getAccessTokenAttribute()
    {
        return auth('device')->fromSubject($this);
    }

    /**
     * Setor em que o dispositivo está instalado/será usado
     */
    public function setor()
    {
        return $this->belongsTo(Setor::class, 'setor_id');
    }

    /**
     * Finalidade do dispositivo, caixa ou terminal, o caixa é único entre os
     * dispositivos
     */
    public function caixa()
    {
        return $this->belongsTo(Caixa::class, 'caixa_id');
    }
}
