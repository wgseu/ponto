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

use Illuminate\Database\Eloquent\Model;

/**
 * Informa os cupons de descontos e seus usos
 */
class Cupom extends Model
{
    /**
     * Informa se o desconto será por valor ou porcentagem
     */
    const TIPO_DESCONTO_VALOR = 'valor';
    const TIPO_DESCONTO_PORCENTAGEM = 'porcentagem';

    /**
     * Informa a regra para decidir se a quantidade de pedidos permite usar
     * esse cupom
     */
    const FUNCAO_PEDIDOS_MENOR = 'menor';
    const FUNCAO_PEDIDOS_IGUAL = 'igual';
    const FUNCAO_PEDIDOS_MAIOR = 'maior';

    /**
     * Informa a regra para decidir se o valor do pedido permite usar esse
     * cupom
     */
    const FUNCAO_VALOR_MENOR = 'menor';
    const FUNCAO_VALOR_IGUAL = 'igual';
    const FUNCAO_VALOR_MAIOR = 'maior';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cupons';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'valor' => 0,
        'porcentagem' => 0,
        'limitar_pedidos' => false,
        'funcao_pedidos' => self::FUNCAO_PEDIDOS_MAIOR,
        'pedidos_limite' => 0,
        'limitar_valor' => false,
        'funcao_valor' => self::FUNCAO_VALOR_MAIOR,
        'valor_limite' => 0,
    ];

    /**
     * Informa de qual cupom foi usado
     */
    public function cupom()
    {
        return $this->belongsTo('App\Models\Cupom', 'cupom_id');
    }

    /**
     * Informa qual pedido usou este cupom
     */
    public function pedido()
    {
        return $this->belongsTo('App\Models\Pedido', 'pedido_id');
    }

    /**
     * Informa o cliente que possui e pode usar esse cupom
     */
    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'cliente_id');
    }
}
