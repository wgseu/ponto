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
 * Informa a lista de produtos disponíveis nos fornecedores
 */
class Catalogo extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'catalogos';

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
        'produto_id',
        'fornecedor_id',
        'preco_compra',
        'preco_venda',
        'quantidade_minima',
        'estoque',
        'limitado',
        'conteudo',
        'data_consulta',
        'data_parada',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'preco_venda' => 0,
        'quantidade_minima' => 1,
        'estoque' => 0,
        'limitado' => false,
        'conteudo' => 1,
    ];

    /**
     * Produto consultado
     */
    public function produto()
    {
        return $this->belongsTo('App\Models\Produto', 'produto_id');
    }

    /**
     * Fornecedor que possui o produto à venda
     */
    public function fornecedor()
    {
        return $this->belongsTo('App\Models\Fornecedor', 'fornecedor_id');
    }

    public function validate()
    {
    }
}
