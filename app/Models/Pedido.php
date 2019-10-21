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
 * Informações do pedido de venda
 */
class Pedido extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * Tipo de venda
     */
    public const TIPO_MESA = 'mesa';
    public const TIPO_COMANDA = 'comanda';
    public const TIPO_BALCAO = 'balcao';
    public const TIPO_ENTREGA = 'entrega';

    /**
     * Estado do pedido, Agendado: O pedido deve ser processado na data de
     * agendamento. Aberto: O pedido deve ser processado. Entrega: O pedido
     * saiu para entrega. Fechado: O cliente pediu a conta e está pronto para
     * pagar. Concluído: O pedido foi pago e concluído, Cancelado: O pedido foi
     * cancelado com os itens e pagamentos
     */
    public const ESTADO_AGENDADO = 'agendado';
    public const ESTADO_ABERTO = 'aberto';
    public const ESTADO_ENTREGA = 'entrega';
    public const ESTADO_FECHADO = 'fechado';
    public const ESTADO_CONCLUIDO = 'concluido';
    public const ESTADO_CANCELADO = 'cancelado';

    public const CREATED_AT = 'data_criacao';
    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pedidos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pedido_id',
        'mesa_id',
        'comanda_id',
        'sessao_id',
        'prestador_id',
        'cliente_id',
        'localizacao_id',
        'entrega_id',
        'associacao_id',
        'tipo',
        'estado',
        'servicos',
        'produtos',
        'comissao',
        'subtotal',
        'descontos',
        'total',
        'pago',
        'troco',
        'lancado',
        'pessoas',
        'cpf',
        'email',
        'descricao',
        'fechador_id',
        'data_impressao',
        'motivo',
        'data_entrega',
        'data_agendamento',
        'data_conclusao',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'tipo' => self::TIPO_MESA,
        'estado' => self::ESTADO_ABERTO,
        'servicos' => 0,
        'produtos' => 0,
        'comissao' => 0,
        'subtotal' => 0,
        'descontos' => 0,
        'total' => 0,
        'pago' => 0,
        'troco' => 0,
        'lancado' => 0,
        'pessoas' => 1,
    ];

    /**
     * Informa o pedido da mesa / comanda principal quando as mesas / comandas
     * forem agrupadas
     */
    public function pedido()
    {
        return $this->belongsTo('App\Models\Pedido', 'pedido_id');
    }

    /**
     * Identificador da mesa, único quando o pedido não está fechado
     */
    public function mesa()
    {
        return $this->belongsTo('App\Models\Mesa', 'mesa_id');
    }

    /**
     * Identificador da comanda, único quando o pedido não está fechado
     */
    public function comanda()
    {
        return $this->belongsTo('App\Models\Comanda', 'comanda_id');
    }

    /**
     * Identificador da sessão de vendas
     */
    public function sessao()
    {
        return $this->belongsTo('App\Models\Sessao', 'sessao_id');
    }

    /**
     * Prestador que criou esse pedido
     */
    public function prestador()
    {
        return $this->belongsTo('App\Models\Prestador', 'prestador_id');
    }

    /**
     * Identificador do cliente do pedido
     */
    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'cliente_id');
    }

    /**
     * Endereço de entrega do pedido, se não informado na venda entrega, o
     * pedido será para viagem
     */
    public function localizacao()
    {
        return $this->belongsTo('App\Models\Localizacao', 'localizacao_id');
    }

    /**
     * Informa em qual entrega esse pedido foi despachado
     */
    public function entrega()
    {
        return $this->belongsTo('App\Models\Viagem', 'entrega_id');
    }

    /**
     * Informa se o pedido veio de uma integração e se está associado
     */
    public function associacao()
    {
        return $this->belongsTo('App\Models\Associacao', 'associacao_id');
    }

    /**
     * Informa quem fechou o pedido e imprimiu a conta
     */
    public function fechador()
    {
        return $this->belongsTo('App\Models\Prestador', 'fechador_id');
    }

    public function validate()
    {
    }
}
