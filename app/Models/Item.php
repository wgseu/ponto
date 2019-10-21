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
 * Produtos, taxas e serviços do pedido, a alteração do estado permite o
 * controle de produção
 */
class Item extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * Estado de preparo e envio do produto
     */
    public const ESTADO_ADICIONADO = 'adicionado';
    public const ESTADO_ENVIADO = 'enviado';
    public const ESTADO_PROCESSADO = 'processado';
    public const ESTADO_PRONTO = 'pronto';
    public const ESTADO_DISPONIVEL = 'disponivel';
    public const ESTADO_ENTREGUE = 'entregue';

    public const UPDATED_AT = 'data_atualizacao';
    public const CREATED_AT = 'data_lancamento';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'itens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pedido_id',
        'prestador_id',
        'produto_id',
        'servico_id',
        'item_id',
        'pagamento_id',
        'descricao',
        'composicao',
        'preco',
        'quantidade',
        'subtotal',
        'comissao',
        'total',
        'preco_venda',
        'preco_compra',
        'detalhes',
        'estado',
        'cancelado',
        'motivo',
        'desperdicado',
        'data_processamento',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'comissao' => 0,
        'preco_compra' => 0,
        'estado' => self::ESTADO_ADICIONADO,
        'cancelado' => false,
        'desperdicado' => false,
    ];

    /**
     * Pedido a qual pertence esse item
     */
    public function pedido()
    {
        return $this->belongsTo('App\Models\Pedido', 'pedido_id');
    }

    /**
     * Prestador que lançou esse item no pedido
     */
    public function prestador()
    {
        return $this->belongsTo('App\Models\Prestador', 'prestador_id');
    }

    /**
     * Produto vendido
     */
    public function produto()
    {
        return $this->belongsTo('App\Models\Produto', 'produto_id');
    }

    /**
     * Serviço cobrado ou taxa
     */
    public function servico()
    {
        return $this->belongsTo('App\Models\Servico', 'servico_id');
    }

    /**
     * Pacote em que esse item faz parte
     */
    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id');
    }

    /**
     * Informa se esse item foi pago e qual foi o lançamento
     */
    public function pagamento()
    {
        return $this->belongsTo('App\Models\Pagamento', 'pagamento_id');
    }

    public function validate()
    {
    }
}
