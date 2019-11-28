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

use App\Util\Mask;
use App\Util\Number;
use App\Concerns\ModelEvents;
use App\Exceptions\Exception;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\ValidationException;
use App\Interfaces\ValidateUpdateInterface;

/**
 * Produtos, taxas e serviços do pedido, a alteração do estado permite o
 * controle de produção
 */
class Item extends Model implements
    ValidateInterface,
    ValidateUpdateInterface
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
        'produto_id',
        'servico_id',
        'item_id',
        'pagamento_id',
        'preco',
        'quantidade',
        'detalhes',
        'estado',
        'cancelado',
        'motivo',
        'desperdicado',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'comissao' => 0,
        'custo_aproximado' => 0,
        'estado' => self::ESTADO_ADICIONADO,
        'cancelado' => false,
        'reservado' => false,
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

    /**
     * Retorna as formações desse item
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function formacoes()
    {
        return $this->hasMany('App\Models\Formacao', 'item_id');
    }

    /**
     * Calcula a comissão do item
     *
     * @param Prestador $prestador
     * @return self
     */
    public function calculate($prestador = null)
    {
        if ((is_null($prestador) || $prestador->id != $this->prestador_id) && !is_null($this->prestador_id)) {
            $prestador = $this->$prestador;
        }
        $produto = $this->produto;
        if (!is_null($produto)) {
            $this->preco_venda = $produto->preco_venda;
            $this->custo_aproximado = (float)$produto->custo_producao;
        }
        $servico = $this->servico;
        if (!is_null($servico)) {
            $this->preco_venda = $servico->valor;
        }
        $this->subtotal = $this->preco * $this->quantidade;
        if (!is_null($prestador) && !is_null($produto)) {
            $comissao = $this->subtotal * ($prestador->porcentagem / 100);
            $this->comissao = $produto->cobrar_servico ? $comissao : 0;
        }
        $this->total = $this->subtotal + $this->comissao;
        return $this;
    }

    /**
     * @param Formacao[] $formacoes lista de formações da composição
     */
    private function checkFormation($formacoes)
    {
        // apenas composições fora de pacotes
        if (!is_null($this->item_id)) {
            return;
        }
        // aplica o desconto dos opcionais e acrescenta o valor dos adicionais
        $produto = $this->produto;
        $preco = $produto->preco_venda;
        foreach ($formacoes as $formacao) {
            if (is_null($formacao->composicao_id)) {
                continue;
            }
            $composicao = $formacao->composicao;
            if (is_null($composicao)) {
                throw new ValidationException([
                    'formacao' => __('messages.item_composition_not_found', ['item' => $produto->descricao])
                ]);
            }
            if ($formacao->quantidade > $composicao->quantidade_maxima) {
                $produto_composicao = $composicao->produto;
                throw new ValidationException([
                    'formacao' => __('messages.item_composition_exceded', [
                        'max' => $composicao->quantidade_maxima,
                        'item' => $produto_composicao->abreviado(),
                    ])
                ]);
            }
            $operacao = -1;
            if ($composicao->tipo == Composicao::TIPO_ADICIONAL) {
                $operacao = 1;
            }
            $preco += $operacao * $composicao->valor * $formacao->quantidade;
        }
        if (!Number::isEqual($this->preco, $preco)) {
            throw new Exception(
                __('messages.item_incorrect_price', [
                    'item' => $produto->descricao,
                    'expected' => Mask::money($preco, true),
                    'given' => Mask::money($this->preco, true),
                ])
            );
        }
    }

    /**
     * Salva o item e sua formação
     *
     * @param Formacao[] $formacoes
     * @return void
     */
    public function formar($formacoes = [])
    {
        if (is_null($this->produto_id)) {
            throw new Exception(__('messages.compose_only_product'));
        }
        $this->checkFormation($formacoes);
        $this->save();
        foreach ($formacoes as $formacao) {
            $formacao->item_id = $this->id;
            $formacao->save();
        }
    }

    /**
     * Reserva o item retirando o produto do estoque
     *
     * @return void
     */
    public function reservar()
    {
        if (is_null($this->produto_id)) {
            throw new Exception(__('messages.can_reserve_only_product'));
        }
        if ($this->reservado) {
            throw new Exception(__('messages.already_reserved'));
        }
        $produto = $this->produto;
        if ($produto->tipo == Produto::TIPO_PACOTE) {
            throw new Exception(__('messages.cannot_reserve_package'));
        }
        $estoque = new Estoque([
            'setor_id' => $produto->setor_estoque_id,
            'produto_id' => $this->produto_id,
            'quantidade' => $this->quantidade,
            'transacao_id' => $this->id,
            'prestador_id' => $this->prestador_id,
        ]);
        $estoque->consumir();
        $this->reservado = true;
        $this->custo_aproximado += $estoque->preco_compra;
        $this->save();
    }

    public function validate()
    {
        $errors = [];
        if (is_null($this->produto_id) && is_null($this->servico_id) && $this->preco >= 0) {
            $errors['preco'] = __('messages.invalid_discount_value');
        } elseif ((!is_null($this->produto_id) || !is_null($this->servico_id)) && $this->preco < 0) {
            $errors['preco'] = __('messages.item_invalid_price');
        }
        if (!is_null($this->produto_id) && !is_null($this->servico_id)) {
            $errors['produto_id'] = __('messages.item_product_service_same_time');
        }
        if ($this->quantidade <= 0) {
            $errors['quantidade'] = __('messages.item_invalid_quantity');
        }
        if (($this->quantidade > 100 && is_null($this->prestador_id)) || $this->quantidade > 1000) {
            $errors['quantidade'] = __('messages.item_elevated_quantity');
        }
        if ($this->pedido->finished()) {
            $errors['quantidade'] = __('messages.item_order_finished');
        }
        return $errors;
    }

    public function onUpdate()
    {
        $errors = [];
        $old = $this->fresh();
        if ($old->produto_id !== $this->produto_id) {
            $errors['produto_id'] = __('messages.item_cannot_change_type');
        }
        if ($old->servico_id !== $this->servico_id) {
            $errors['servico_id'] = __('messages.item_cannot_change_type');
        }
        if (!is_null($this->produto_id) && $old->quantidade != $this->quantidade) {
            $errors['quantidade'] = __('messages.item_cannot_change_quantity');
        }
        if ($this->pedido_id != $old->pedido_id && $old->pedido->finished()) {
            $errors['quantidade'] = __('messages.item_move_finished');
        }
        return $errors;
    }
}
