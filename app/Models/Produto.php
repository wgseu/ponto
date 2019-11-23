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

use App\Models\Item;
use App\Concerns\ModelEvents;
use App\Exceptions\Exception;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Informações sobre o produto, composição ou pacote
 */
class Produto extends Model implements ValidateInterface
{
    use ModelEvents;
    use SoftDeletes;

    /**
     * Informa qual é o tipo de produto. Produto: Produto normal que possui
     * estoque, Composição: Produto que não possui estoque diretamente, pois é
     * composto de outros produtos ou composições, Pacote: Permite a composição
     * no momento da venda, não possui estoque diretamente
     */
    public const TIPO_PRODUTO = 'produto';
    public const TIPO_COMPOSICAO = 'composicao';
    public const TIPO_PACOTE = 'pacote';

    public const UPDATED_AT = 'data_atualizacao';
    public const DELETED_AT = 'data_arquivado';
    public const CREATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'produtos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo',
        'categoria_id',
        'unidade_id',
        'setor_estoque_id',
        'setor_preparo_id',
        'tributacao_id',
        'descricao',
        'abreviacao',
        'detalhes',
        'quantidade_minima',
        'quantidade_maxima',
        'preco_venda',
        'custo_producao',
        'tipo',
        'cobrar_servico',
        'divisivel',
        'pesavel',
        'tempo_preparo',
        'disponivel',
        'insumo',
        'avaliacao',
        'imagem_url',
    ];

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
     * Retorna o nome abreviado do produto
     *
     * @return string
     */
    public function abreviado()
    {
        if ($this->abreviacao == '') {
            return $this->descricao;
        }
        return $this->abreviacao;
    }

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

    /**
     * Retorna todas as composições ativas
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function composicoes()
    {
        return $this->hasMany('App\Models\Composicao', 'composicao_id')
            ->where('ativa', true);
    }

    /**
     * Retorna os grupos do pacote na ordem correta
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function grupos()
    {
        return $this->hasMany('App\Models\Grupo', 'produto_id')
            ->orderBy('ordem', 'asc')
            ->orderBy('id', 'asc');
    }

    /**
     * Informa quanto desse produto tem no setor de estoque
     *
     * @param int $setor_id retorna estoque desse setor
     * @return float
     */
    public function estoqueSetor($setor_id = null)
    {
        $contagem = Contagem::where('produto_id', $this->id)
            ->where('setor_id', $setor_id ?? $this->setor_preparo_id)->first();
        return is_null($contagem) ? 0 : $contagem->quantidade;
    }

    /**
     * Produz a quantidade de composição informada
     *
     * @param float $quantidade
     * @param int $prestador_id funcionário que está produzindo essa composição
     * @return Estoque
     */
    public function produzir($quantidade, $prestador_id = null)
    {
        $estoque = new Estoque([
            'prestador_id' => $prestador_id,
            'quantidade' => $quantidade,
            'produto_id' => $this->id,
        ]);
        $estoque->produzir();
        return $estoque;
    }

    public function validate()
    {
        $errors = [];
        $old = $this->fresh();
        $item = Item::where('produto_id', $this->produto_id);
        if (
            !is_null($old)
            && $this->tipo != $old->tipo
            && $item->exists()
        ) {
            $errors['tipo'] = __('messages.produto_already_packaged');
        }
        return $errors;
    }
}
