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
use App\Util\Currency;
use App\Concerns\ModelEvents;
use App\Exceptions\Exception;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ValidateInterface;
use App\Interfaces\AfterSaveInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\ValidationException;
use App\Interfaces\BeforeSaveInterface;
use App\Interfaces\AfterUpdateInterface;
use App\Interfaces\ValidateInsertInterface;
use App\Interfaces\ValidateUpdateInterface;

/**
 * Produtos, taxas e serviços do pedido, a alteração do estado permite o
 * controle de produção
 */
class Item extends Model implements
    ValidateInterface,
    ValidateUpdateInterface,
    ValidateInsertInterface,
    BeforeSaveInterface,
    AfterSaveInterface,
    AfterUpdateInterface
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
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    /**
     * Prestador que lançou esse item no pedido
     */
    public function prestador()
    {
        return $this->belongsTo(Prestador::class, 'prestador_id');
    }

    /**
     * Produto vendido
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    /**
     * Serviço cobrado ou taxa
     */
    public function servico()
    {
        return $this->belongsTo(Servico::class, 'servico_id');
    }

    /**
     * Pacote em que esse item faz parte
     */
    public function item()
    {
        return $this->belongsTo(self::class, 'item_id');
    }

    /**
     * Informa se esse item foi pago e qual foi o lançamento
     */
    public function pagamento()
    {
        return $this->belongsTo(Pagamento::class, 'pagamento_id');
    }

    /**
     * Grupo da montagem do item
     *
     * @return Builder
     */
    public function getGrupoAttribute()
    {
        return Grupo::leftJoin('formacoes', function ($join) {
            $join->on('formacoes.item_id', '=', DB::raw($this->id));
        })->leftJoin('pacotes', 'pacotes.id', '=', 'formacoes.pacote_id')
          ->whereRaw('grupos.id = pacotes.grupo_id')->first();
    }

    /**
     * Retorna as formações desse item
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function formacoes()
    {
        return $this->hasMany(Formacao::class, 'item_id');
    }

    /**
     * Subitens
     */
    public function itens()
    {
        return $this->hasMany(self::class, 'item_id')->where('cancelado', false);
    }

    /**
     * Calcula a comissão do item
     *
     * @param Prestador $prestador
     * @return self
     */
    protected function calculate()
    {
        $produto = $this->produto;
        if (!is_null($produto)) {
            $this->preco_venda = $produto->preco_venda;
            $this->custo_aproximado = (float)$produto->custo_producao;
        }
        $servico = $this->servico;
        if (!is_null($servico)) {
            $this->preco_venda = $servico->valor;
        }
        if (!$this->exists && !is_null($produto) && $produto->tipo == Produto::TIPO_PRODUTO) {
            $this->estado = self::ESTADO_DISPONIVEL;
        }
        $this->comissao = Currency::round($this->comissao);
        $this->subtotal = Currency::round($this->preco * $this->quantidade);
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
        if (!Currency::isEqual($this->preco, $preco)) {
            throw new Exception(
                __('messages.item_incorrect_price', [
                    'name' => $produto->descricao,
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

    /**
     * Cancela os subitens
     */
    protected function cancelDependencies()
    {
        $itens = $this->itens;
        foreach ($itens as $item) {
            $item->update(['cancelado' => true]);
        }
    }

    /**
     * Move os subitens para o pedido desse item
     */
    protected function moveSubitens()
    {
        $itens = $this->itens;
        foreach ($itens as $item) {
            $item->update(['pedido_id' => $this->pedido_id]);
        }
    }

    /**
     * Altera o estado do item principal como produzido ou não
     */
    protected function checkParentDone()
    {
        $item = $this->item;

        // verifica se voltou um item de fazendo para aguardando
        $waiting_status = [
            self::ESTADO_ADICIONADO,
            self::ESTADO_ENVIADO,
        ];
        $waiting = $item->itens()->whereIn('estado', $waiting_status)->count() > 0;
        if ($waiting && !in_array($item->estado, $waiting_status)) {
            $item->update(['estado' => self::ESTADO_ENVIADO]);
        }
        if ($waiting) {
            return;
        }

        // verifica se voltou um item de pronto para fazendo
        $open_status = [
            self::ESTADO_PROCESSADO,
        ];
        $cooking = $item->itens()->whereIn('estado', $open_status)->count() > 0;
        if ($cooking && !in_array($item->estado, $open_status)) {
            $item->update(['estado' => self::ESTADO_PROCESSADO]);
        }
        if ($cooking) {
            return;
        }

        // verifica se voltou um item de entregue para fazendo
        $done_status = [
            self::ESTADO_PRONTO,
            self::ESTADO_DISPONIVEL,
        ];
        $available = $item->itens()->whereIn('estado', $done_status)->count() > 0;
        if ($available && !in_array($item->estado, $done_status)) {
            $item->update(['estado' => self::ESTADO_PRONTO]);
        }
        if ($available) {
            return;
        }

        // aqui entregou todos os itens
        $item->update(['estado' => $this->estado]);
    }

    public function validate($old)
    {
        $errors = [];
        if ($this->preco < 0) {
            $errors['preco'] = __('messages.item_invalid_price');
        }
        if (!is_null($this->produto_id) && !is_null($this->servico_id)) {
            $errors['produto_id'] = __('messages.item_product_service_same_time');
        }
        if (is_null($this->produto_id) && is_null($this->servico_id)) {
            $errors['produto_id'] = __('messages.item_no_product_or_service');
        }
        if ($this->quantidade <= 0) {
            $errors['quantidade'] = __('messages.item_invalid_quantity');
        } elseif (($this->quantidade > 100 && is_null($this->prestador_id)) || $this->quantidade > 1000) {
            $errors['quantidade'] = __('messages.item_elevated_quantity');
        }
        return $errors;
    }

    public function onInsert()
    {
        if ($this->pedido->finished()) {
            // adicionando item em pedido concluído ou cancelado
            return ['quantidade' => __('messages.item_order_finished')];
        }
    }

    public function onUpdate($old)
    {
        $pedido = $this->pedido;
        $old_pedido = $old->pedido;
        if ($old->cancelado) {
            return ['quantidade' => __('messages.item_already_cancelled')];
        } elseif ($this->cancelado && !is_null($this->item_id) && !$this->item->cancelado) {
            return ['quantidade' => __('messages.parent_item_no_cancelled')];
        } elseif ($old->produto_id != $this->produto_id) {
            return ['produto_id' => __('messages.item_cannot_change_type')];
        } elseif ($old->servico_id != $this->servico_id) {
            return ['servico_id' => __('messages.item_cannot_change_type')];
        } elseif (!is_null($this->produto_id) && $old->quantidade != $this->quantidade) {
            return ['quantidade' => __('messages.item_cannot_change_quantity')];
        } elseif (
            (
                $pedido->estado == Pedido::ESTADO_CANCELADO ||
                $old_pedido->estado == Pedido::ESTADO_CANCELADO
            ) &&
            !$this->cancelado
        ) {
            // alterando um item de ou para pedido cancelado (não está cancelando o item)
            return ['quantidade' => __('messages.item_move_finished')];
        } elseif (
            $pedido->estado == Pedido::ESTADO_CONCLUIDO &&
            (
                $pedido->tipo != Pedido::TIPO_BALCAO ||
                !$this->isChangeAllowed(['estado', 'data_processamento', 'data_atualizacao'])
            )
        ) {
            // alterando outro campo do item sem ser o estado quando o pedido está concluído
            return ['pedido_id' => __('messages.item_order_finished')];
        }
    }

    public function beforeSave($old)
    {
        $this->calculate();
    }

    public function afterSave($old)
    {
        if ($this->cancelado) {
            $this->cancelDependencies();
        }
    }

    public function afterUpdate($old)
    {
        if ($old->pedido_id != $this->pedido_id) {
            $this->moveSubitens();
        }
        if ($old->estado != $this->estado && !is_null($this->item_id)) {
            $this->checkParentDone();
        }
    }
}
