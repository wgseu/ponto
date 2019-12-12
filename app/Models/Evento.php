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
 * Eventos de envio das notas
 */
class Evento extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * Estado do evento
     */
    public const ESTADO_ABERTO = 'aberto';
    public const ESTADO_ASSINADO = 'assinado';
    public const ESTADO_VALIDADO = 'validado';
    public const ESTADO_PENDENTE = 'pendente';
    public const ESTADO_PROCESSAMENTO = 'processamento';
    public const ESTADO_DENEGADO = 'denegado';
    public const ESTADO_CANCELADO = 'cancelado';
    public const ESTADO_REJEITADO = 'rejeitado';
    public const ESTADO_CONTINGENCIA = 'contingencia';
    public const ESTADO_INUTILIZADO = 'inutilizado';
    public const ESTADO_AUTORIZADO = 'autorizado';

    public const CREATED_AT = 'data_criacao';
    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eventos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nota_id',
        'estado',
        'mensagem',
        'codigo',
    ];

    /**
     * Nota a qual o evento foi criado
     */
    public function nota()
    {
        return $this->belongsTo(Nota::class, 'nota_id');
    }

    public function validate()
    {
    }
}
