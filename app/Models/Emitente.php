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
 * Dados do emitente das notas fiscais
 */
class Emitente extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * Ambiente de emissão das notas
     */
    public const AMBIENTE_HOMOLOGACAO = 'homologacao';
    public const AMBIENTE_PRODUCAO = 'producao';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'emitentes';

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
        'contador_id',
        'regime_id',
        'ambiente',
        'csc_teste',
        'csc',
        'token_teste',
        'token',
        'ibpt',
        'data_expiracao',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'ambiente' => self::AMBIENTE_HOMOLOGACAO,
    ];

    /**
     * Contador responsável pela contabilidade da empresa
     */
    public function contador()
    {
        return $this->belongsTo(Cliente::class, 'contador_id');
    }

    /**
     * Regime tributário da empresa
     */
    public function regime()
    {
        return $this->belongsTo(Regime::class, 'regime_id');
    }

    public function validate()
    {
    }
}
