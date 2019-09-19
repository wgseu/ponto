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
 * Informações sobre o produto, composição ou pacote
 */
class Produto extends Model
{
    /**
     * Informa qual é o tipo de produto. Produto: Produto normal que possui
     * estoque, Composição: Produto que não possui estoque diretamente, pois é
     * composto de outros produtos ou composições, Pacote: Permite a composição
     * no momento da venda, não possui estoque diretamente
     */
    const TIPO_PRODUTO = 'produto';
    const TIPO_COMPOSICAO = 'composicao';
    const TIPO_PACOTE = 'pacote';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'produtos';

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
        'quantidade_minima' => 0,
        'quantidade_maxima' => 0,
        'preco_venda' => 0,
        'tipo' => self::TIPO_PRODUTO,
        'cobrar_servico' => true,
        'divisivel' => false,
        'pesavel' => false,
        'tempo_preparo' => 0,
        'disponivel' => true,
        'insumo' => false,
        'estoque' => 0,
    ];

    /**
     * Categoria do produto, permite a rápida localização ao utilizar tablets
     */
    public function categoria()
    {
        return $this->belongsTo('App\Models\Categoria', 'categoria_id');
    }

    /**
     * Informa a unidade do produtos, Ex.: Grama, Litro.
     */
    public function unidade()
    {
        return $this->belongsTo('App\Models\Unidade', 'unidade_id');
    }

    /**
     * Informa de qual setor o produto será retirado após a venda
     */
    public function setorEstoque()
    {
        return $this->belongsTo('App\Models\Setor', 'setor_estoque_id');
    }

    /**
     * Informa em qual setor de preparo será enviado o ticket de preparo ou
     * autorização, se nenhum for informado nada será impresso
     */
    public function setorPreparo()
    {
        return $this->belongsTo('App\Models\Setor', 'setor_preparo_id');
    }

    /**
     * Informações de tributação do produto
     */
    public function tributacao()
    {
        return $this->belongsTo('App\Models\Tributacao', 'tributacao_id');
    }
}
