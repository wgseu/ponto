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
use App\Exceptions\Exception;
use App\Interfaces\AfterSaveInterface;
use App\Interfaces\BeforeSaveInterface;
use App\Interfaces\ValidateInsertInterface;
use App\Interfaces\ValidateInterface;
use App\Interfaces\ValidateUpdateInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Estoque de produtos por setor
 */
class Estoque extends Model implements
    ValidateInterface,
    ValidateInsertInterface,
    ValidateUpdateInterface,
    AfterSaveInterface,
    BeforeSaveInterface
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
        'producao_id',
        'produto_id',
        'compra_id',
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
        'cancelado',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'preco_compra' => 0,
        'cancelado' => false,
    ];

    /**
     * Informa o que foi produzido através dessa saida de estoque
     */
    public function producao()
    {
        return $this->belongsTo(Estoque::class, 'producao_id');
    }

    /**
     * Produto que entrou no estoque
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    /**
     * Informa de qual compra originou essa entrada em estoque
     */
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    /**
     * Identificador do item que gerou a saída desse produto do estoque
     */
    public function transacao()
    {
        return $this->belongsTo(Item::class, 'transacao_id');
    }

    /**
     * Fornecedor do produto
     */
    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }

    /**
     * Setor de onde o produto foi inserido ou retirado
     */
    public function setor()
    {
        return $this->belongsTo(Setor::class, 'setor_id');
    }

    /**
     * Prestador que inseriu/retirou o produto do estoque
     */
    public function prestador()
    {
        return $this->belongsTo(Prestador::class, 'prestador_id');
    }

    /**
     * Produz e retira do estoque a quantidade informada
     *
     * @return void
     */
    public function consumir()
    {
        $produto = $this->produto;
        if ($produto->tipo == Produto::TIPO_PACOTE) {
            throw new Exception(__('messages.cannot_consume_package'));
        }
        $formacoes = $this->transacao->formacoes()->whereNotNull('composicao_id')->get()->all();
        $custo_aproximado = $this->baixar($formacoes);
        $item = $this->transacao;
        $item->custo_aproximado += $custo_aproximado;
        $item->save();
        $this->preco_compra = $custo_aproximado;
    }

    /**
     * Produz uma composição para ser vendida posteriormente
     *
     * @return void
     */
    public function produzir()
    {
        $produto = $this->produto;
        if ($produto->tipo != Produto::TIPO_COMPOSICAO) {
            throw new Exception(__('messages.only_produce_composition'));
        }
        if (is_null($produto->setor_estoque_id)) {
            throw new Exception(__('messages.production_without_sector'));
        }
        $this->setor_id = $produto->setor_estoque_id;
        $this->save();
        // baixa o estoque dos ingredientes
        $custo_aproximado = $this->baixar();
        $this->preco_compra = $custo_aproximado;
        $this->save();
    }

    /**
     * Baixa do estoque composições e produtos
     *
     * @param Formacao[] $formacoes formação
     * @return float custo aproximado da formação
     */
    protected function baixar($formacoes = [])
    {
        // cria conjunto de composições a serem retiradas ou adicionadas
        $opcionais = [];
        foreach ($formacoes as $formacao) {
            $opcionais[$formacao->composicao_id] = true;
        }
        $custo_aproximado = 0;
        $stack = new \SplStack();
        // simula o primeiro produto como uma composição
        $composicao = new Composicao([
            'produto_id' => $this->produto_id,
            'quantidade' => $this->quantidade,
        ]);
        $stack->push($composicao);
        while (!$stack->isEmpty()) {
            $composicao = $stack->pop();
            $produto = $composicao->produto;
            // verifica se essa composição deve ser produzida ou se já tem no estoque
            $produce = $produto->tipo == Produto::TIPO_COMPOSICAO && (
                $produto->estoqueSetor() < $composicao->quantidade ||
                !empty($opcionais) ||
                is_null($produto->setor_estoque_id)
            );
            if ($produce) {
                // empilha todas as composições que não foram retiradas na venda
                $composicoes = $produto->composicoes;
                foreach ($composicoes as $child_composition) {
                    // aplica a quantidade em profundidade
                    $child_composition->quantidade = $child_composition->quantidade * $composicao->quantidade;
                    $existe = isset($opcionais[$child_composition->id]);
                    if ($existe && $child_composition->tipo != Composicao::TIPO_ADICIONAL) {
                        unset($opcionais[$child_composition->id]);
                    } elseif ($existe && $child_composition->tipo == Composicao::TIPO_ADICIONAL) {
                        unset($opcionais[$child_composition->id]);
                        $stack->push($child_composition);
                    } elseif ($child_composition->tipo != Composicao::TIPO_ADICIONAL) {
                        $stack->push($child_composition);
                    }
                }
                continue;
            }
            // o composto é um produto ou composição produzida
            $estoque = new Estoque([
                'producao_id' => $this->id,
                'setor_id' => $produto->setor_estoque_id,
                'produto_id' => $produto->id,
                'quantidade' => $composicao->quantidade * -1,
                'transacao_id' => $this->transacao_id,
                'prestador_id' => $this->prestador_id,
                'preco_compra' => $produto->custo_medio,
            ]);
            $estoque->save();
            $custo_aproximado += ($composicao->quantidade * $produto->custo_medio) / $this->quantidade;
        }
        return $custo_aproximado;
    }

    /**
     * Calcula o custo médio e estoque
     *
     * @return self
     */
    protected function calculate()
    {
        if ($this->quantidade < 0) {
            $this->custo_medio = $this->preco_compra;
            return;
        }
        if ($this->exists) {
            $old = $this->fresh();
            // custo total anterior
            $custo = $old->estoque * $old->custo_medio - $old->quantidade * $old->preco_compra;
            $estoque = $old->estoque - $old->quantidade;
        } else {
            $custo = $this->produto->estoque * $this->produto->custo_medio;
            $estoque = $this->produto->estoque;
        }
        $custo_total = $custo + $this->quantidade * $this->preco_compra;
        $this->estoque = $estoque + $this->quantidade;
        $this->custo_medio = $custo_total / $this->estoque;
        return $this;
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
    public function validate($old)
    {
        $produto = $this->produto;
        if ($produto->tipo == Produto::TIPO_PACOTE) {
            return ['produto_id' => __('messages.tipo_product_cannot_pacote')];
        }
        if (fmod($this->quantidade, 1) > 0 && !$produto->divisivel) {
            return ['produto_id' => __('messages.produto_indivisible')];
        }
        if (!is_null($this->compra_id) && $this->quantidade <= 0) {
            return ['compra_id' => __('messages.quantidade_cannot_less_0')];
        }
        if (!is_null($this->transacao_id) && $this->quantidade >= 0) {
            return ['transacao_id' => __('messages.quantidade_cannot_greater_0')];
        }
        if (!is_null($this->producao_id) && $this->quantidade >= 0) {
            return ['transacao_id' => __('messages.composition_cannot_insert')];
        }
        if ($this->preco_compra < 0) {
            return ['preco_compra' => __('messages.valor_compra_negative')];
        }
    }

    public function onInsert()
    {
        if ($this->cancelado) {
            return ['cancelado' => __('messages.estoque_new_canceled')];
        }
        $produto = $this->produto;
        if ($this->quantidade < 0 && $produto->estoqueSetor($this->setor_id) < -$this->quantidade) {
            $setor = $this->setor;
            return ['quantidade' => __('messages.low_stock', [
                'product' => $produto->descricao,
                'sector' => $setor->nome,
            ])];
        }
    }

    public function onUpdate($old)
    {
        if ($old->cancelado) {
            return ['cancelado' => __('messages.estoque_already_canceled')];
        }
        if (
            !$this->cancelado &&
            !$this->isChangeAllowed([
                'fornecedor_id',
                'compra_id',
                'lote',
                'fabricacao',
                'vencimento',
                'detalhes',
                'preco_compra',
                'custo_medio',
                'estoque'
            ])
        ) {
            return ['cancelado' => __('messages.estoque_change_denied')];
        }
        if (
            $this->cancelado &&
            !$this->isChangeAllowed(['cancelado', 'detalhes'])
        ) {
            return ['cancelado' => __('messages.estoque_cancel_with_changes')];
        }
    }

    public function beforeSave($old)
    {
        $this->calculate();
    }

    public function afterSave($old)
    {
        $this->updateCounting($old);
    }

    /**
     * Atualiza a contagem do produto
     *
     * @param self $old estoque antes de ser atualizado
     * @return void
     */
    protected function updateCounting($old)
    {
        if ($old && $old->cancelado == $this->cancelado) {
            return;
        }
        // atualiza a contagem de estoque
        if ($this->cancelado) {
            $quantidade = -$this->quantidade;
        } else {
            $quantidade = $this->quantidade;
        }
        $contagem = Contagem::firstOrCreate(
            [
                'setor_id' => $this->setor_id,
                'produto_id' => $this->produto_id,
            ],
            [ 'quantidade' => 0 ]
        );
        $contagem->increment('quantidade', $quantidade);
        $produto = $this->produto;
        $produto->increment('estoque', $quantidade);
        // não atualiza custo médio em saídas
        if ($this->quantidade < 0) {
            return;
        }
        // atualiza o custo médio
        if ($this->cancelado) {
            // TODO: implementar cancelamento de entrada em estoque
        } else {
            $produto->custo_medio = $this->custo_medio;
            $produto->save();
        }
    }
}
