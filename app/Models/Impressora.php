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
use Illuminate\Database\Eloquent\Model;

/**
 * Impressora para impressão de serviços e contas
 */
class Impressora extends Model
{
    use ModelEvents;

    /**
     * Modo de impressão
     */
    public const MODO_TERMINAL = 'terminal';
    public const MODO_CAIXA = 'caixa';
    public const MODO_SERVICO = 'servico';
    public const MODO_ESTOQUE = 'estoque';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'impressoras';

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
        'dispositivo_id',
        'setor_id',
        'nome',
        'modelo',
        'modo',
        'opcoes',
        'colunas',
        'avanco',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'modo' => self::MODO_TERMINAL,
        'colunas' => 48,
        'avanco' => 6,
    ];

    public function __construct(array $attributes = [])
    {
        $this->options = new Settings();
        parent::__construct($attributes);
    }

    /**
     * Retorna as opções da impressora como string json
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

    /**
     * Carrega as opções da impressora
     *
     * @return void
     */
    public function loadOptions()
    {
        $this->options->addValues(
            json_decode(base64_decode($this->getAttributeFromArray('opcoes')), true)
        );
    }

    /**
     * Dispositivo que contém a impressora
     */
    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class, 'dispositivo_id');
    }

    /**
     * Setor de impressão
     */
    public function setor()
    {
        return $this->belongsTo(Setor::class, 'setor_id');
    }
}
