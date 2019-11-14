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
use App\Exceptions\SafeValidationException;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Estoque de produtos por setor
 */
class Estoque extends Model implements ValidateInterface
{
    use ModelEvents;

    public const CREATED_AT = 'data_movimento';
    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'estoques';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'produto_id',
        'requisito_id',
        'transacao_id',
        'fornecedor_id',
        'setor_id',
        'prestador_id',
        'quantidade',
        'preco_compra',
        'lote',
        'fabricacao',
        'vencimento',
        'detalhes',
        'reservado',
        'cancelado',
    ];

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

    /**
     * Regras:
     *
     * É obrigatório informar apenas um requisito ou uma transação;
     * O produto não pode ser do tipo pacote;
     * Se o produto é indivisivel a quantidade não pode ser float;
     * Se for entrada de produto a quantidade não pode ser negativa;
     * Se for saida de produto a quantidade não pode ser positiva;
     * Se cancelado não pode ser alterado;
     * Não pode criar já cancelado.
     */
    public function validate()
    {
        $errors = [];
        $produto = $this->produto;
        if (!is_null($this->requisito_id) && !is_null($this->transacao_id)) {
            $errors['requisito_id'] = __('messages.one_required_requisito_or_transacao');
        }
        if ($produto->tipo == Produto::TIPO_PACOTE) {
            $errors['produto_id'] = __('messages.tipo_product_cannot_pacote');
        }
        if (fmod($this->quantidade, 1) > 0 && !$produto->divisivel) {
            $errors['produto_id'] = __('messages.produto_indivisible');
        }
        if (!is_null($this->requisito_id) && $this->quantidade <= 0) {
            $errors['requisito_id'] = __('messages.quantidade_cannot_less_0');
        }
        if (!is_null($this->transacao_id) && $this->quantidade >= 0) {
            $errors['transacao_id'] = __('messages.quantidade_cannot_greater_0');
        }
        if (!$this->exists && $this->cancelado) {
            $errors['cancelado'] = __('messages.estoque_new_canceled');
        }
        if ($this->preco_compra < 0) {
            $errors['preco_compra'] = __('messages.valor_compra_negative');
        }
        return $errors;
    }
}
