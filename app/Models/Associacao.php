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
 * Lista de pedidos que não foram integrados ainda e devem ser associados
 * ao sistema
 */
class Associacao extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * Status do pedido que não foi integrado ainda
     */
    public const STATUS_AGENDADO = 'agendado';
    public const STATUS_ABERTO = 'aberto';
    public const STATUS_ENTREGA = 'entrega';
    public const STATUS_CONCLUIDO = 'concluido';
    public const STATUS_CANCELADO = 'cancelado';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'associacoes';

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
        'integracao_id',
        'entrega_id',
        'codigo',
        'cliente',
        'chave',
        'pedido',
        'endereco',
        'quantidade',
        'servicos',
        'produtos',
        'descontos',
        'pago',
        'status',
        'motivo',
        'mensagem',
        'sincronizado',
        'integrado',
        'data_confirmacao',
        'data_pedido',
    ];

    /**
     * Integração a qual essa associação de pedido deve ser realizada
     */
    public function integracao()
    {
        return $this->belongsTo('App\Models\Integracao', 'integracao_id');
    }

    /**
     * Entrega que foi realizada
     */
    public function entrega()
    {
        return $this->belongsTo('App\Models\Viagem', 'entrega_id');
    }

    public function validate()
    {
    }
}
