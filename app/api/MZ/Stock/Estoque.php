<?php
/**
 * Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
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
 * @author Equipe GrandChef <desenvolvimento@mzsw.com.br>
 */
namespace MZ\Stock;

use MZ\Database\Model;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Product\Composicao;
use MZ\Environment\Setor;
use MZ\Product\Produto;

/**
 * Estoque de produtos por setor
 */
class Estoque extends Model
{

    /**
     * Tipo de movimentação do estoque. Entrada: Entrada de produtos no
     * estoque, Venda: Saída de produtos através de venda, Consumo: Saída de
     * produtos por consumo próprio, Transferência: Indica a transferência de
     * produtos entre setores
     */
    const TIPO_MOVIMENTO_ENTRADA = 'Entrada';
    const TIPO_MOVIMENTO_VENDA = 'Venda';
    const TIPO_MOVIMENTO_CONSUMO = 'Consumo';
    const TIPO_MOVIMENTO_TRANSFERENCIA = 'Transferencia';

    /**
     * Identificador da entrada no estoque
     */
    private $id;
    /**
     * Produto que entrou no estoque
     */
    private $produto_id;
    /**
     * Identificador do item que gerou a saída desse produto do estoque
     */
    private $transacao_id;
    /**
     * Informa de qual entrada no estoque essa saída foi retirada, permite
     * estoque FIFO
     */
    private $entrada_id;
    /**
     * Fornecedor do produto
     */
    private $fornecedor_id;
    /**
     * Setor de onde o produto foi inserido ou retirado
     */
    private $setor_id;
    /**
     * Funcionário que inseriu/retirou o produto do estoque
     */
    private $funcionario_id;
    /**
     * Tipo de movimentação do estoque. Entrada: Entrada de produtos no
     * estoque, Venda: Saída de produtos através de venda, Consumo: Saída de
     * produtos por consumo próprio, Transferência: Indica a transferência de
     * produtos entre setores
     */
    private $tipo_movimento;
    /**
     * Quantidade do mesmo produto inserido no estoque
     */
    private $quantidade;
    /**
     * Preço de compra do produto
     */
    private $preco_compra;
    /**
     * Lote de produção do produto comprado
     */
    private $lote;
    /**
     * Data de fabricação do produto
     */
    private $data_fabricacao;
    /**
     * Data de vencimento do produto
     */
    private $data_vencimento;
    /**
     * Detalhes da inserção ou retirada do estoque
     */
    private $detalhes;
    /**
     * Informa a entrada ou saída do estoque foi cancelada
     */
    private $cancelado;
    /**
     * Data de entrada ou saída do produto do estoque
     */
    private $data_movimento;

    /**
     * Constructor for a new empty instance of Estoque
     * @param array $estoque All field and values to fill the instance
     */
    public function __construct($estoque = [])
    {
        parent::__construct($estoque);
    }

    /**
     * Identificador da entrada no estoque
     * @return mixed ID of Estoque
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Estoque Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Produto que entrou no estoque
     * @return mixed Produto of Estoque
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param  mixed $produto_id new value for ProdutoID
     * @return Estoque Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Identificador do item que gerou a saída desse produto do estoque
     * @return mixed Transação of Estoque
     */
    public function getTransacaoID()
    {
        return $this->transacao_id;
    }

    /**
     * Set TransacaoID value to new on param
     * @param  mixed $transacao_id new value for TransacaoID
     * @return Estoque Self instance
     */
    public function setTransacaoID($transacao_id)
    {
        $this->transacao_id = $transacao_id;
        return $this;
    }

    /**
     * Informa de qual entrada no estoque essa saída foi retirada, permite
     * estoque FIFO
     * @return mixed Entrada of Estoque
     */
    public function getEntradaID()
    {
        return $this->entrada_id;
    }

    /**
     * Set EntradaID value to new on param
     * @param  mixed $entrada_id new value for EntradaID
     * @return Estoque Self instance
     */
    public function setEntradaID($entrada_id)
    {
        $this->entrada_id = $entrada_id;
        return $this;
    }

    /**
     * Fornecedor do produto
     * @return mixed Fornecedor of Estoque
     */
    public function getFornecedorID()
    {
        return $this->fornecedor_id;
    }

    /**
     * Set FornecedorID value to new on param
     * @param  mixed $fornecedor_id new value for FornecedorID
     * @return Estoque Self instance
     */
    public function setFornecedorID($fornecedor_id)
    {
        $this->fornecedor_id = $fornecedor_id;
        return $this;
    }

    /**
     * Setor de onde o produto foi inserido ou retirado
     * @return mixed Setor of Estoque
     */
    public function getSetorID()
    {
        return $this->setor_id;
    }

    /**
     * Set SetorID value to new on param
     * @param  mixed $setor_id new value for SetorID
     * @return Estoque Self instance
     */
    public function setSetorID($setor_id)
    {
        $this->setor_id = $setor_id;
        return $this;
    }

    /**
     * Funcionário que inseriu/retirou o produto do estoque
     * @return mixed Funcionário of Estoque
     */
    public function getFuncionarioID()
    {
        return $this->funcionario_id;
    }

    /**
     * Set FuncionarioID value to new on param
     * @param  mixed $funcionario_id new value for FuncionarioID
     * @return Estoque Self instance
     */
    public function setFuncionarioID($funcionario_id)
    {
        $this->funcionario_id = $funcionario_id;
        return $this;
    }

    /**
     * Tipo de movimentação do estoque. Entrada: Entrada de produtos no
     * estoque, Venda: Saída de produtos através de venda, Consumo: Saída de
     * produtos por consumo próprio, Transferência: Indica a transferência de
     * produtos entre setores
     * @return mixed Tipo de movimento of Estoque
     */
    public function getTipoMovimento()
    {
        return $this->tipo_movimento;
    }

    /**
     * Set TipoMovimento value to new on param
     * @param  mixed $tipo_movimento new value for TipoMovimento
     * @return Estoque Self instance
     */
    public function setTipoMovimento($tipo_movimento)
    {
        $this->tipo_movimento = $tipo_movimento;
        return $this;
    }

    /**
     * Quantidade do mesmo produto inserido no estoque
     * @return mixed Quantidade of Estoque
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set Quantidade value to new on param
     * @param  mixed $quantidade new value for Quantidade
     * @return Estoque Self instance
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
        return $this;
    }

    /**
     * Preço de compra do produto
     * @return mixed Preço de compra of Estoque
     */
    public function getPrecoCompra()
    {
        return $this->preco_compra;
    }

    /**
     * Set PrecoCompra value to new on param
     * @param  mixed $preco_compra new value for PrecoCompra
     * @return Estoque Self instance
     */
    public function setPrecoCompra($preco_compra)
    {
        $this->preco_compra = $preco_compra;
        return $this;
    }

    /**
     * Lote de produção do produto comprado
     * @return mixed Lote of Estoque
     */
    public function getLote()
    {
        return $this->lote;
    }

    /**
     * Set Lote value to new on param
     * @param  mixed $lote new value for Lote
     * @return Estoque Self instance
     */
    public function setLote($lote)
    {
        $this->lote = $lote;
        return $this;
    }

    /**
     * Data de fabricação do produto
     * @return mixed Data de fabricação of Estoque
     */
    public function getDataFabricacao()
    {
        return $this->data_fabricacao;
    }

    /**
     * Set DataFabricacao value to new on param
     * @param  mixed $data_fabricacao new value for DataFabricacao
     * @return Estoque Self instance
     */
    public function setDataFabricacao($data_fabricacao)
    {
        $this->data_fabricacao = $data_fabricacao;
        return $this;
    }

    /**
     * Data de vencimento do produto
     * @return mixed Data de vencimento of Estoque
     */
    public function getDataVencimento()
    {
        return $this->data_vencimento;
    }

    /**
     * Set DataVencimento value to new on param
     * @param  mixed $data_vencimento new value for DataVencimento
     * @return Estoque Self instance
     */
    public function setDataVencimento($data_vencimento)
    {
        $this->data_vencimento = $data_vencimento;
        return $this;
    }

    /**
     * Detalhes da inserção ou retirada do estoque
     * @return mixed Detalhes of Estoque
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }

    /**
     * Set Detalhes value to new on param
     * @param  mixed $detalhes new value for Detalhes
     * @return Estoque Self instance
     */
    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
        return $this;
    }

    /**
     * Informa a entrada ou saída do estoque foi cancelada
     * @return mixed Cancelado of Estoque
     */
    public function getCancelado()
    {
        return $this->cancelado;
    }

    /**
     * Informa a entrada ou saída do estoque foi cancelada
     * @return boolean Check if o of Cancelado is selected or checked
     */
    public function isCancelado()
    {
        return $this->cancelado == 'Y';
    }

    /**
     * Set Cancelado value to new on param
     * @param  mixed $cancelado new value for Cancelado
     * @return Estoque Self instance
     */
    public function setCancelado($cancelado)
    {
        $this->cancelado = $cancelado;
        return $this;
    }

    /**
     * Data de entrada ou saída do produto do estoque
     * @return mixed Data de movimento of Estoque
     */
    public function getDataMovimento()
    {
        return $this->data_movimento;
    }

    /**
     * Set DataMovimento value to new on param
     * @param  mixed $data_movimento new value for DataMovimento
     * @return Estoque Self instance
     */
    public function setDataMovimento($data_movimento)
    {
        $this->data_movimento = $data_movimento;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $estoque = parent::toArray($recursive);
        $estoque['id'] = $this->getID();
        $estoque['produtoid'] = $this->getProdutoID();
        $estoque['transacaoid'] = $this->getTransacaoID();
        $estoque['entradaid'] = $this->getEntradaID();
        $estoque['fornecedorid'] = $this->getFornecedorID();
        $estoque['setorid'] = $this->getSetorID();
        $estoque['funcionarioid'] = $this->getFuncionarioID();
        $estoque['tipomovimento'] = $this->getTipoMovimento();
        $estoque['quantidade'] = $this->getQuantidade();
        $estoque['precocompra'] = $this->getPrecoCompra();
        $estoque['lote'] = $this->getLote();
        $estoque['datafabricacao'] = $this->getDataFabricacao();
        $estoque['datavencimento'] = $this->getDataVencimento();
        $estoque['detalhes'] = $this->getDetalhes();
        $estoque['cancelado'] = $this->getCancelado();
        $estoque['datamovimento'] = $this->getDataMovimento();
        return $estoque;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $estoque Associated key -> value to assign into this instance
     * @return Estoque Self instance
     */
    public function fromArray($estoque = [])
    {
        if ($estoque instanceof Estoque) {
            $estoque = $estoque->toArray();
        } elseif (!is_array($estoque)) {
            $estoque = [];
        }
        parent::fromArray($estoque);
        if (!isset($estoque['id'])) {
            $this->setID(null);
        } else {
            $this->setID($estoque['id']);
        }
        if (!isset($estoque['produtoid'])) {
            $this->setProdutoID(null);
        } else {
            $this->setProdutoID($estoque['produtoid']);
        }
        if (!array_key_exists('transacaoid', $estoque)) {
            $this->setTransacaoID(null);
        } else {
            $this->setTransacaoID($estoque['transacaoid']);
        }
        if (!array_key_exists('entradaid', $estoque)) {
            $this->setEntradaID(null);
        } else {
            $this->setEntradaID($estoque['entradaid']);
        }
        if (!array_key_exists('fornecedorid', $estoque)) {
            $this->setFornecedorID(null);
        } else {
            $this->setFornecedorID($estoque['fornecedorid']);
        }
        if (!isset($estoque['setorid'])) {
            $this->setSetorID(null);
        } else {
            $this->setSetorID($estoque['setorid']);
        }
        if (!isset($estoque['funcionarioid'])) {
            $this->setFuncionarioID(null);
        } else {
            $this->setFuncionarioID($estoque['funcionarioid']);
        }
        if (!isset($estoque['tipomovimento'])) {
            $this->setTipoMovimento(null);
        } else {
            $this->setTipoMovimento($estoque['tipomovimento']);
        }
        if (!isset($estoque['quantidade'])) {
            $this->setQuantidade(null);
        } else {
            $this->setQuantidade($estoque['quantidade']);
        }
        if (!isset($estoque['precocompra'])) {
            $this->setPrecoCompra(null);
        } else {
            $this->setPrecoCompra($estoque['precocompra']);
        }
        if (!array_key_exists('lote', $estoque)) {
            $this->setLote(null);
        } else {
            $this->setLote($estoque['lote']);
        }
        if (!array_key_exists('datafabricacao', $estoque)) {
            $this->setDataFabricacao(null);
        } else {
            $this->setDataFabricacao($estoque['datafabricacao']);
        }
        if (!array_key_exists('datavencimento', $estoque)) {
            $this->setDataVencimento(null);
        } else {
            $this->setDataVencimento($estoque['datavencimento']);
        }
        if (!array_key_exists('detalhes', $estoque)) {
            $this->setDetalhes(null);
        } else {
            $this->setDetalhes($estoque['detalhes']);
        }
        if (!isset($estoque['cancelado'])) {
            $this->setCancelado(null);
        } else {
            $this->setCancelado($estoque['cancelado']);
        }
        if (!isset($estoque['datamovimento'])) {
            $this->setDataMovimento(DB::now());
        } else {
            $this->setDataMovimento($estoque['datamovimento']);
        }
        return $this;
    }

    /**
     * Get last buy price for informed product
     * @param int $produtoid Product id to get last price
     * @return float the last buy price
     */
    public static function getUltimoPrecoCompra($produtoid)
    {
        $estoque = self::find(['produtoid' => $produtoid, 'cancelado' => 'N'], ['id' => -1]);
        return $estoque->getPrecoCompra() + 0.0;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $estoque = parent::publish();
        return $estoque;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Estoque $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setTransacaoID(Filter::number($original->getTransacaoID()));
        $this->setEntradaID(Filter::number($original->getEntradaID()));
        $this->setFuncionarioID(Filter::number($original->getFuncionarioID()));
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setFornecedorID(Filter::number($this->getFornecedorID()));
        $this->setSetorID(Filter::number($this->getSetorID()));
        $this->setQuantidade(Filter::float($this->getQuantidade()));
        $this->setPrecoCompra(Filter::money($this->getPrecoCompra()));
        $this->setLote(Filter::string($this->getLote()));
        $this->setDataFabricacao(Filter::datetime($this->getDataFabricacao()));
        $this->setDataVencimento(Filter::datetime($this->getDataVencimento()));
        $this->setDetalhes(Filter::string($this->getDetalhes()));
        $this->setDataMovimento(DB::now());
    }

    /**
     * Clean instance resources like images and docs
     * @param  Estoque $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Estoque in array format
     */
    public function validate()
    {
        $errors = [];
        $produto = $this->findProdutoID();
        if (is_null($this->getProdutoID())) {
            $errors['produtoid'] = 'O produto não pode ser vazio';
        } elseif ($produto->getTipo() != Produto::TIPO_PRODUTO) {
            $errors['produtoid'] = 'O produto informado não é do tipo produto';
        }
        if (is_null($this->getSetorID())) {
            $errors['setorid'] = 'O setor não pode ser vazio';
        }
        if (is_null($this->getFuncionarioID())) {
            $errors['funcionarioid'] = 'O funcionário não pode ser vazio';
        }
        if (!Validator::checkInSet($this->getTipoMovimento(), self::getTipoMovimentoOptions())) {
            $errors['tipomovimento'] = 'O tipo de movimento não foi informado ou é inválido';
        }
        if (is_null($this->getQuantidade())) {
            $errors['quantidade'] = 'A quantidade não pode ser vazia';
        } elseif ($this->getQuantidade() <= 0 && $this->getTipoMovimento() == self::TIPO_MOVIMENTO_ENTRADA) {
            $errors['quantidade'] = 'A quantidade não pode ser nula ou negativa';
        }
        if (is_null($this->getPrecoCompra())) {
            $errors['precocompra'] = 'O preço de compra não pode ser vazio';
        }
        if (!Validator::checkBoolean($this->getCancelado())) {
            $errors['cancelado'] = 'O cancelamento não foi informado ou é inválido';
        } elseif ($this->exists() && $this->isCancelado()) {
            $old_estoque = self::findByID($this->getID());
            if ($old_estoque->isCancelado()) {
                $errors['cancelado'] = 'Essa movimentação está cancelada e não pode ser alterada';
            } else {
                $count = self::count(['entradaid' => $this->getID(), 'cancelado' => 'N']);
                if ($count > 0) {
                    $errors['cancelado'] = 'Essa entrada já foi movimentada e não pode ser cancelada';
                }
            }
        }
        $this->setDataMovimento(DB::now());
        if (!empty($errors)) {
            throw new \MZ\Exception\ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Translate SQL exception into application exception
     * @param  \Exception $e exception to translate into a readable error
     * @return \MZ\Exception\ValidationException new exception translated
     */
    protected function translate($e)
    {
        return parent::translate($e);
    }

    /**
     * Insert a new Estoque into the database and fill instance from database
     * @return Estoque Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Estoque')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Estoque with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Estoque Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do estoque não foi informado');
        }
        $values = DB::filterValues($values, $only, $except);
        unset($values['datamovimento']);
        try {
            DB::update('Estoque')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $this->loadByID($this->getID());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador do estoque não foi informado');
        }
        $result = DB::deleteFrom('Estoque')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    public function cancelar()
    {
        $old_cancelado = $this->getCancelado();
        $this->setCancelado('Y');
        try {
            $this->update();
        } catch (\Exception $e) {
            $this->setCancelado($old_cancelado);
            throw $e;
        }
    }

    public function retirarFIFO()
    {
        $negativo = is_boolean_config('Estoque', 'Estoque.Negativo');
        $restante = $this->getQuantidade();
        $produto = $this->findProdutoID();
        while (true) {
            $entrada = $this->findAvailableEntry();
            if (!$entrada->exists()) {
                if ($negativo) {
                    $entrada->setQuantidade(-$restante);
                    $entrada->setPrecoCompra(0.0000);
                } else {
                    throw new \Exception(sprintf(
                        'Não há estoque para o produto "%s"',
                        $produto->getDescricao()
                    ));
                }
            }
            if ($entrada->getQuantidade() < -$this->getQuantidade()) {
                $this->setQuantidade(-$entrada->getQuantidade());
            }
            $this->setID(null);
            $this->setPrecoCompra($entrada->getPrecoCompra());
            $this->setEntradaID($entrada->getID());
            $this->setFornecedorID($entrada->getFornecedorID());
            $this->setLote($entrada->getLote());
            $this->setDataFabricacao($entrada->getDataFabricacao());
            $this->setDataVencimento($entrada->getDataVencimento());
            $this->setCancelado('N');
            $this->insert();
            $restante = $restante - $this->getQuantidade();
            if ($restante > -0.0005) {
                break;
            }
            $this->setQuantidade($restante);
        }
    }

    public function retirar($opcionais)
    {
        $setor = Setor::findDefault();
        $this->setTipoMovimento(self::TIPO_MOVIMENTO_VENDA);
        $stack = new \SplStack();
        $composicao = new Composicao();
        $composicao->setProdutoID($this->getProdutoID());
        $composicao->setQuantidade($this->getQuantidade());
        $stack->push($composicao);
        while (!$stack->isEmpty()) {
            $composicao = $stack->pop();
            $produto = $composicao->findProdutoID();
            if ($produto->getTipo() == Produto::TIPO_PACOTE) {
                break;
            }
            if ($produto->getTipo() == Produto::TIPO_COMPOSICAO) {
                // empilha todas as composições que não foram retiradas na venda
                $composicoes = Composicao::findAll(['composicaoid' => $composicao->getProdutoID()]);
                foreach ($composicoes as $_composicao) {
                    // aplica a quantidade em profundidade
                    $_composicao->setQuantidade($_composicao->getQuantidade() * $composicao->getQuantidade());
                    $existe = isset($opcionais[$_composicao->getID()]);
                    if ($existe && $_composicao->getTipo() != Composicao::TIPO_ADICIONAL) {
                        unset($opcionais[$_composicao->getID()]);
                    } elseif ($existe && $_composicao->getTipo() == Composicao::TIPO_ADICIONAL) {
                        unset($opcionais[$_composicao->getID()]);
                        $stack->push($_composicao);
                    } elseif ($_composicao->getTipo() != Composicao::TIPO_ADICIONAL) {
                        $stack->push($_composicao);
                    }
                }
            } else {
                // o composto é um produto
                $this->setSetorID($produto->getSetorEstoqueID());
                if (is_null($this->getSetorID())) {
                    $this->setSetorID($setor->getID());
                }
                $this->setProdutoID($produto->getID());
                $this->setQuantidade(-$composicao->getQuantidade());
                $this->retirarFIFO();
            }
        }
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Estoque Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Produto que entrou no estoque
     * @return \MZ\Product\Produto The object fetched from database
     */
    public function findProdutoID()
    {
        return \MZ\Product\Produto::findByID($this->getProdutoID());
    }

    /**
     * Identificador do item que gerou a saída desse produto do estoque
     * @return \MZ\Sale\ProdutoPedido The object fetched from database
     */
    public function findTransacaoID()
    {
        if (is_null($this->getTransacaoID())) {
            return new \MZ\Sale\ProdutoPedido();
        }
        return \MZ\Sale\ProdutoPedido::findByID($this->getTransacaoID());
    }

    /**
     * Informa de qual entrada no estoque essa saída foi retirada, permite
     * estoque FIFO
     * @return \MZ\Stock\Estoque The object fetched from database
     */
    public function findEntradaID()
    {
        if (is_null($this->getEntradaID())) {
            return new \MZ\Stock\Estoque();
        }
        return \MZ\Stock\Estoque::findByID($this->getEntradaID());
    }

    /**
     * Fornecedor do produto
     * @return \MZ\Stock\Fornecedor The object fetched from database
     */
    public function findFornecedorID()
    {
        if (is_null($this->getFornecedorID())) {
            return new \MZ\Stock\Fornecedor();
        }
        return \MZ\Stock\Fornecedor::findByID($this->getFornecedorID());
    }

    /**
     * Setor de onde o produto foi inserido ou retirado
     * @return \MZ\Environment\Setor The object fetched from database
     */
    public function findSetorID()
    {
        return \MZ\Environment\Setor::findByID($this->getSetorID());
    }

    /**
     * Funcionário que inseriu/retirou o produto do estoque
     * @return \MZ\Employee\Funcionario The object fetched from database
     */
    public function findFuncionarioID()
    {
        return \MZ\Employee\Funcionario::findByID($this->getFuncionarioID());
    }

    /**
     * Find first available stock entry
     * @return Estoque Self instance filled or empty when not found
     */
    public function findAvailableEntry()
    {
        $query = self::query(
            [
                'produtoid' => $this->getProdutoID(),
                'setorid' => $this->getSetorID(),
                'cancelado' => 'N',
            ],
            ['id' => 1]
        )->select('ROUND(e.quantidade + SUM(COALESCE(t.quantidade, 0)), 6) as restante')
            ->leftJoin('Estoque t ON t.entradaid = e.id AND t.cancelado = ?', 'N')
            ->where('e.quantidade > ?', 0)
            ->groupBy('e.id')
            ->having('restante > 0')
            ->limit(1);
        $data = $query->fetch() ?: [];
        $restante = isset($data['restante']) ? $data['restante'] : 0.0;
        $estoque = new Estoque($data);
        $estoque->setQuantidade($restante + 0.0);
        return $estoque;
    }

    /**
     * Gets textual and translated TipoMovimento for Estoque
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getTipoMovimentoOptions($index = null)
    {
        $options = [
            self::TIPO_MOVIMENTO_ENTRADA => 'Entrada',
            self::TIPO_MOVIMENTO_VENDA => 'Venda',
            self::TIPO_MOVIMENTO_CONSUMO => 'Consumo',
            self::TIPO_MOVIMENTO_TRANSFERENCIA => 'Transferência',
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $estoque = new Estoque();
        $allowed = Filter::concatKeys('e.', $estoque->toArray());
        return $allowed;
    }

    /**
     * Filter order array
     * @param  mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $allowed = self::getAllowedKeys();
        return Filter::orderBy($order, $allowed, 'e.');
    }

    /**
     * Filter select array with allowed fields
     * @param  array $select selectable fields
     * @return array allowed select fields
     */
    private static function filterSelect($select)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($select, $allowed, 'e.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'e.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Estoque e');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('e.id DESC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Estoque A filled Estoque or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Estoque($row);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Estoque A filled Estoque or empty instance
     */
    public static function sum($condition, $field)
    {
        $query = self::query($condition);
        $fields = self::filterSelect([strval($field) => 1]);
        if (empty($fields)) {
            return null;
        }
        reset($fields);
        $field = key($fields);
        return $query->select(null)->select("SUM($field)")->fetchColumn();
    }

    /**
     * Sum quantity of product id
     * @param  int $produto_id product id to sum stock quantity
     * @param  int $setor_id product sector id to filter stock
     * @return float A sum of quantity
     */
    public static function sumByProdutoID($produto_id, $setor_id = null)
    {
        $condition = [
            'produtoid' => intval($produto_id),
            'cancelado' => 'N'
        ];
        if (!is_null($setor_id)) {
            $condition['setorid'] = $setor_id;
        }
        return (float)self::sum($condition, 'quantidade');
    }

    /**
     * Find all Estoque
     * @param  array  $condition Condition to get all Estoque
     * @param  array  $order     Order Estoque
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Estoque
     */
    public static function findAll($condition = [], $order = [], $limit = null, $offset = null)
    {
        $query = self::query($condition, $order);
        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }
        if (!is_null($offset)) {
            $query = $query->offset($offset);
        }
        $rows = $query->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[] = new Estoque($row);
        }
        return $result;
    }

    /**
     * Count all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        return $query->count();
    }
}
