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
    const ESTADO_ABERTO = 'aberto';
    const ESTADO_ASSINADO = 'assinado';
    const ESTADO_VALIDADO = 'validado';
    const ESTADO_PENDENTE = 'pendente';
    const ESTADO_PROCESSAMENTO = 'processamento';
    const ESTADO_DENEGADO = 'denegado';
    const ESTADO_CANCELADO = 'cancelado';
    const ESTADO_REJEITADO = 'rejeitado';
    const ESTADO_CONTINGENCIA = 'contingencia';
    const ESTADO_INUTILIZADO = 'inutilizado';
    const ESTADO_AUTORIZADO = 'autorizado';

    const CREATED_AT = 'data_criacao';
    const UPDATED_AT = null;

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
        return $this->belongsTo('App\Models\Nota', 'nota_id');
    }

    public function validate()
    {
    }
}
