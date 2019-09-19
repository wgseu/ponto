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
 * Estoque de produtos por setor
 */
class Estoque extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'estoques';

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
        'preco_compra' => 0,
        'reservado' => false,
        'cancelado' => false,
    ];

    /**
     * Produto que entrou no estoque
     */
    public function produto()
    {
        return $this->belongsTo('App\Models\Produto', 'produto_id');
    }

    /**
     * Informa de qual compra originou essa entrada em estoque
     */
    public function requisito()
    {
        return $this->belongsTo('App\Models\Requisito', 'requisito_id');
    }

    /**
     * Identificador do item que gerou a saída desse produto do estoque
     */
    public function transacao()
    {
        return $this->belongsTo('App\Models\Item', 'transacao_id');
    }

    /**
     * Informa de qual entrada no estoque essa saída foi retirada, permite
     * estoque FIFO
     */
    public function entrada()
    {
        return $this->belongsTo('App\Models\Estoque', 'entrada_id');
    }

    /**
     * Fornecedor do produto
     */
    public function fornecedor()
    {
        return $this->belongsTo('App\Models\Fornecedor', 'fornecedor_id');
    }

    /**
     * Setor de onde o produto foi inserido ou retirado
     */
    public function setor()
    {
        return $this->belongsTo('App\Models\Setor', 'setor_id');
    }

    /**
     * Prestador que inseriu/retirou o produto do estoque
     */
    public function prestador()
    {
        return $this->belongsTo('App\Models\Prestador', 'prestador_id');
    }
}
