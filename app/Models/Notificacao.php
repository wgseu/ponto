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
use Illuminate\Database\Eloquent\Model;

/**
 * Notificações e avisos para os clientes, funcionários e administradores
 */
class Notificacao extends Model
{
    use ModelEvents;

    public const CREATED_AT = 'data_notificacao';
    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notificacoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'destinatario_id',
        'remetente_id',
        'mensagem',
        'categoria',
        'redirecionar',
        'data_visualizacao',
    ];

    /**
     * Informa quem deverá receber a notificação
     */
    public function destinatario()
    {
        return $this->belongsTo(Cliente::class, 'destinatario_id');
    }

    /**
     * Cliente que enviou a notificação, nulo quando for enviado pelo sistema
     */
    public function remetente()
    {
        return $this->belongsTo(Cliente::class, 'remetente_id');
    }
}
