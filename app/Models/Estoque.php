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
    ValidateUpdateInterface
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
        return $this->belongsTo('App\Models\Estoque', 'producao_id');
    }

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
    public function compra()
    {
        return $this->belongsTo('App\Models\Compra', 'compra_id');
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
        $this->calculate();
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
                'quantidade' => -$composicao->quantidade,
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
    public function calculate()
    {
        if ($this->quantidade < 0) {
            return $this;
        }
        if ($this->exists) {
            $old = $this->fresh();
            // quantidade anterior à compra
            $estoque = $old->estoque - $old->quantidade;
            // custo total anterior à compra
            $old_custo_total = $old->estoque * $old->custo_medio - $old->quantidade * $old->preco_compra;
            // custo médio anterior à compra
            $custo_medio = $old_custo_total / $estoque;
        } else {
            $estoque = $this->produto->estoque;
            $custo_medio = $this->produto->custo_medio;
        }
        $estoque_total = $estoque + $this->quantidade;
        $this->estoque = $estoque_total;
        $custo_total = $estoque * $custo_medio + $this->quantidade * $this->preco_compra;
        $this->custo_medio = $custo_total / $estoque_total;
        return $this;
    }

    /**
     * Atualiza a contagem do produto
     *
     * @return void
     */
    protected function updateCounting()
    {
        $run_contagem = true;
        $decrement = 0;
        $old = null;
        if ($this->exists) {
            $old = $this->fresh();
            if ($old->quantidade == $this->quantidade && !$this->cancelado) {
                $run_contagem = false;
            }
            $decrement = $old->quantidade;
        }
        if ($this->cancelado) {
            $quantidade = -$decrement;
        } else {
            $quantidade = $this->quantidade - $decrement;
        }
        if ($run_contagem) {
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
        }
        // checa por alteração na quantidade, preço de compra ou cancelamento
        if (
            $this->quantidade < 0 ||
            (
                !is_null($old) &&
                $old->cancelado == $this->cancelado &&
                $old->quantidade == $this->quantidade &&
                $old->preco_compra == $this->preco_compra
            )
        ) {
            return;
        }
        $produto = $this->produto;
        $custo_medio = $this->custo_medio;
        // TODO: implementar correção de custo médio
        // $produto->update(['custo_medio' => $custo_medio]);
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
        $this->updateCounting();
    }

    public function onUpdate()
    {
        $old = $this->fresh();
        if ($old->cancelado) {
            return ['cancelado' => __('messages.estoque_already_canceled')];
        }
        $this->updateCounting();
    }
}
