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
 * Computadores e tablets com opções de acesso
 */
class Dispositivo extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * Tipo de dispositivo
     */
    const TIPO_COMPUTADOR = 'computador';
    const TIPO_TABLET = 'tablet';
    const TIPO_NAVEGADOR = 'navegador';

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

    /**
     * Setor em que o dispositivo está instalado/será usado
     */
    public function setor()
    {
        return $this->belongsTo('App\Models\Setor', 'setor_id');
    }

    /**
     * Finalidade do dispositivo, caixa ou terminal, o caixa é único entre os
     * dispositivos
     */
    public function caixa()
    {
        return $this->belongsTo('App\Models\Caixa', 'caixa_id');
    }

    public function validate()
    {
    }
}
