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
 * Registro de viagem de uma entrega ou compra de insumos
 */
class Viagem extends Model implements ValidateInterface
{
    use ModelEvents;

    public const UPDATED_AT = 'data_atualizacao';
    public const CREATED_AT = 'data_saida';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'viagens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'responsavel_id',
        'latitude',
        'longitude',
        'quilometragem',
        'distancia',
        'data_chegada',
    ];

    /**
     * Responsável pela entrega ou compra
     */
    public function responsavel()
    {
        return $this->belongsTo(Prestador::class, 'responsavel_id');
    }

    /**
     * Regras:
     * A data de chegada é anterior a de saída;
     */
    public function validate()
    {
        $errors = [];
        if (
            !is_null($this->data_chegada) &&
            date_timestamp_get($this->data_chegada) < date_timestamp_get($this->data_saida)
        ) {
            $errors['data_chegada'] = __('messages.error_time_viagem');
        }
        return $errors;
    }
}
