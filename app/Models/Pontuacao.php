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
 * Informa os pontos ganhos e gastos por compras de produtos promocionais
 */
class Pontuacao extends Model implements ValidateInterface
{
    use ModelEvents;

    public const CREATED_AT = 'data_cadastro';
    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pontuacoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'promocao_id',
        'cliente_id',
        'pedido_id',
        'item_id',
        'quantidade',
    ];

    /**
     * Informa a promoção que originou os pontos ou que descontou os pontos
     */
    public function promocao()
    {
        return $this->belongsTo(Promocao::class, 'promocao_id');
    }

    /**
     * Cliente que possui esses pontos, não informar quando tiver travado por
     * pedido
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Informa se essa pontuação será usada apenas nesse pedido
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    /**
     * Informa qual venda originou esses pontos, tanto saída como entrada
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function validate()
    {
        $errors = [];
        if (!is_null($this->item_id) && is_null($this->pedido_id)) {
            $errors['item_id'] = __('messages.pedido_not_null');
        }
        return $errors;
    }
}
