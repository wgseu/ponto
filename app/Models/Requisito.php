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
 * Informa os produtos da lista de compras
 */
class Requisito extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'requisitos';

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
        'lista_id',
        'produto_id',
        'compra_id',
        'fornecedor_id',
        'quantidade',
        'comprado',
        'preco_maximo',
        'preco',
        'observacoes',
        'data_recolhimento',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'quantidade' => 0,
        'comprado' => 0,
        'preco_maximo' => 0,
        'preco' => 0,
    ];

    /**
     * Lista de compra desse produto
     */
    public function lista()
    {
        return $this->belongsTo(Lista::class, 'lista_id');
    }

    /**
     * Produto que deve ser comprado
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    /**
     * Informa em qual fornecedor foi realizado a compra desse produto
     */
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    /**
     * Fornecedor em que deve ser consultado ou realizado as compras dos
     * produtos, pode ser alterado no momento da compra
     */
    public function fornecedor()
    {
        return $this->belongsTo(Cliente::class, 'fornecedor_id');
    }

    /**
     * Regras:
     * A compra e o requisito devem ter o mesmo fornecedor;
     * A quantidade comprada não pode ser superior a quantidade pedida;
     * A quantidade, comprado, preço máximo e preço não podem ser negativos.
     */
    public function validate($old)
    {
        $errors = [];
        if (!is_null($this->compra_id)) {
            $compra = $this->compra;
            if ($compra->fornecedor_id != $this->fornecedor_id) {
                $errors['fornecedor_id'] = __('messages.fornecedor_different_sale');
            }
        }
        if ($this->comprado > $this->quantidade) {
            $errors['quantidade'] = __('messages.quantidade_cannot_greater_comprado');
        }
        if ($this->quantidade < 0) {
            $errors['quantidade'] = __('messages.quantidade_cannot_negative');
        }
        if ($this->comprado < 0) {
            $errors['comprado'] = __('messages.comprado_cannot_negative');
        }
        if ($this->preco_maximo < 0) {
            $errors['preco_maximo'] = __('messages.preco_maximo_cannot_negative');
        }
        if ($this->preco < 0) {
            $errors['preco'] = __('messages.preco_cannot_negative');
        }
        return $errors;
    }
}
