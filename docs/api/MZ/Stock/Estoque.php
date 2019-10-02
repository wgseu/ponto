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

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;
use MZ\Product\Composicao;
use MZ\Environment\Setor;
use MZ\Product\Produto;

/**
 * Estoque de produtos por setor
 */
class Estoque extends SyncModel
{
    protected $table = 'Estoque';

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
     * Informa de qual compra originou essa entrada em estoque
     */
    private $requisito_id;
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
     * Prestador que inseriu/retirou o produto do estoque
     */
    private $prestador_id;
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
     * @return int id of Estoque
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Estoque
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Produto que entrou no estoque
     * @return int produto of Estoque
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param int $produto_id Set produto for Estoque
     * @return self Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Informa de qual compra originou essa entrada em estoque
     * @return int requisição de compra of Estoque
     */
    public function getRequisitoID()
    {
        return $this->requisito_id;
    }

    /**
     * Set RequisitoID value to new on param
     * @param int $requisito_id Set requisição de compra for Estoque
     * @return self Self instance
     */
    public function setRequisitoID($requisito_id)
    {
        $this->requisito_id = $requisito_id;
        return $this;
    }

    /**
     * Identificador do item que gerou a saída desse produto do estoque
     * @return int transação of Estoque
     */
    public function getTransacaoID()
    {
        return $this->transacao_id;
    }

    /**
     * Set TransacaoID value to new on param
     * @param int $transacao_id Set transação for Estoque
     * @return self Self instance
     */
    public function setTransacaoID($transacao_id)
    {
        $this->transacao_id = $transacao_id;
        return $this;
    }

    /**
     * Informa de qual entrada no estoque essa saída foi retirada, permite
     * estoque FIFO
     * @return int entrada of Estoque
     */
    public function getEntradaID()
    {
        return $this->entrada_id;
    }

    /**
     * Set EntradaID value to new on param
     * @param int $entrada_id Set entrada for Estoque
     * @return self Self instance
     */
    public function setEntradaID($entrada_id)
    {
        $this->entrada_id = $entrada_id;
        return $this;
    }

    /**
     * Fornecedor do produto
     * @return int fornecedor of Estoque
     */
    public function getFornecedorID()
    {
        return $this->fornecedor_id;
    }

    /**
     * Set FornecedorID value to new on param
     * @param int $fornecedor_id Set fornecedor for Estoque
     * @return self Self instance
     */
    public function setFornecedorID($fornecedor_id)
    {
        $this->fornecedor_id = $fornecedor_id;
        return $this;
    }

    /**
     * Setor de onde o produto foi inserido ou retirado
     * @return int setor of Estoque
     */
    public function getSetorID()
    {
        return $this->setor_id;
    }

    /**
     * Set SetorID value to new on param
     * @param int $setor_id Set setor for Estoque
     * @return self Self instance
     */
    public function setSetorID($setor_id)
    {
        $this->setor_id = $setor_id;
        return $this;
    }

    /**
     * Prestador que inseriu/retirou o produto do estoque
     * @return int prestador of Estoque
     */
    public function getPrestadorID()
    {
        return $this->prestador_id;
    }

    /**
     * Set PrestadorID value to new on param
     * @param int $prestador_id Set prestador for Estoque
     * @return self Self instance
     */
    public function setPrestadorID($prestador_id)
    {
        $this->prestador_id = $prestador_id;
        return $this;
    }

    /**
     * Tipo de movimentação do estoque. Entrada: Entrada de produtos no
     * estoque, Venda: Saída de produtos através de venda, Consumo: Saída de
     * produtos por consumo próprio, Transferência: Indica a transferência de
     * produtos entre setores
     * @return string tipo de movimento of Estoque
     */
    public function getTipoMovimento()
    {
        return $this->tipo_movimento;
    }

    /**
     * Set TipoMovimento value to new on param
     * @param string $tipo_movimento Set tipo de movimento for Estoque
     * @return self Self instance
     */
    public function setTipoMovimento($tipo_movimento)
    {
        $this->tipo_movimento = $tipo_movimento;
        return $this;
    }

    /**
     * Quantidade do mesmo produto inserido no estoque
     * @return float quantidade of Estoque
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set Quantidade value to new on param
     * @param float $quantidade Set quantidade for Estoque
     * @return self Self instance
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
        return $this;
    }

    /**
     * Preço de compra do produto
     * @return string preço de compra of Estoque
     */
    public function getPrecoCompra()
    {
        return $this->preco_compra;
    }

    /**
     * Set PrecoCompra value to new on param
     * @param string $preco_compra Set preço de compra for Estoque
     * @return self Self instance
     */
    public function setPrecoCompra($preco_compra)
    {
        $this->preco_compra = $preco_compra;
        return $this;
    }

    /**
     * Lote de produção do produto comprado
     * @return string lote of Estoque
     */
    public function getLote()
    {
        return $this->lote;
    }

    /**
     * Set Lote value to new on param
     * @param string $lote Set lote for Estoque
     * @return self Self instance
     */
    public function setLote($lote)
    {
        $this->lote = $lote;
        return $this;
    }

    /**
     * Data de fabricação do produto
     * @return string data de fabricação of Estoque
     */
    public function getDataFabricacao()
    {
        return $this->data_fabricacao;
    }

    /**
     * Set DataFabricacao value to new on param
     * @param string $data_fabricacao Set data de fabricação for Estoque
     * @return self Self instance
     */
    public function setDataFabricacao($data_fabricacao)
    {
        $this->data_fabricacao = $data_fabricacao;
        return $this;
    }

    /**
     * Data de vencimento do produto
     * @return string data de vencimento of Estoque
     */
    public function getDataVencimento()
    {
        return $this->data_vencimento;
    }

    /**
     * Set DataVencimento value to new on param
     * @param string $data_vencimento Set data de vencimento for Estoque
     * @return self Self instance
     */
    public function setDataVencimento($data_vencimento)
    {
        $this->data_vencimento = $data_vencimento;
        return $this;
    }

    /**
     * Detalhes da inserção ou retirada do estoque
     * @return string detalhes of Estoque
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }

    /**
     * Set Detalhes value to new on param
     * @param string $detalhes Set detalhes for Estoque
     * @return self Self instance
     */
    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
        return $this;
    }

    /**
     * Informa a entrada ou saída do estoque foi cancelada
     * @return string cancelado of Estoque
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
     * @param string $cancelado Set cancelado for Estoque
     * @return self Self instance
     */
    public function setCancelado($cancelado)
    {
        $this->cancelado = $cancelado;
        return $this;
    }

    /**
     * Data de entrada ou saída do produto do estoque
     * @return string data de movimento of Estoque
     */
    public function getDataMovimento()
    {
        return $this->data_movimento;
    }

    /**
     * Set DataMovimento value to new on param
     * @param string $data_movimento Set data de movimento for Estoque
     * @return self Self instance
     */
    public function setDataMovimento($data_movimento)
    {
        $this->data_movimento = $data_movimento;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $estoque = parent::toArray($recursive);
        $estoque['id'] = $this->getID();
        $estoque['produtoid'] = $this->getProdutoID();
        $estoque['requisitoid'] = $this->getRequisitoID();
        $estoque['transacaoid'] = $this->getTransacaoID();
        $estoque['entradaid'] = $this->getEntradaID();
        $estoque['fornecedorid'] = $this->getFornecedorID();
        $estoque['setorid'] = $this->getSetorID();
        $estoque['prestadorid'] = $this->getPrestadorID();
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
     * @param mixed $estoque Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($estoque = [])
    {
        if ($estoque instanceof self) {
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
        if (!array_key_exists('requisitoid', $estoque)) {
            $this->setRequisitoID(null);
        } else {
            $this->setRequisitoID($estoque['requisitoid']);
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
        if (!isset($estoque['prestadorid'])) {
            $this->setPrestadorID(null);
        } else {
            $this->setPrestadorID($estoque['prestadorid']);
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
            $this->setCancelado('N');
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
     * Convert this instance into array associated key -> value with only public fields
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $estoque = parent::publish($requester);
        return $estoque;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     * @param \MZ\Provider\Prestador $updater user that want to update this object
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $updater, $localized = false)
    {
        $this->setID($original->getID());
        $this->setTransacaoID(Filter::number($original->getTransacaoID()));
        $this->setEntradaID(Filter::number($original->getEntradaID()));
        $this->setPrestadorID(Filter::number($original->getPrestadorID()));
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setRequisitoID(Filter::number($this->getRequisitoID()));
        $this->setFornecedorID(Filter::number($this->getFornecedorID()));
        $this->setSetorID(Filter::number($this->getSetorID()));
        $this->setQuantidade(Filter::float($this->getQuantidade(), $localized));
        $this->setPrecoCompra(Filter::money($this->getPrecoCompra(), $localized));
        $this->setLote(Filter::string($this->getLote()));
        $this->setDataFabricacao(Filter::datetime($this->getDataFabricacao()));
        $this->setDataVencimento(Filter::datetime($this->getDataVencimento()));
        $this->setDetalhes(Filter::string($this->getDetalhes()));
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Estoque in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        $produto = $this->findProdutoID();
        if (is_null($this->getProdutoID())) {
            $errors['produtoid'] = _t('estoque.produto_id_cannot_empty');
        } elseif ($produto->getTipo() != Produto::TIPO_PRODUTO) {
            $errors['produtoid'] = _t('estoque.is_not_product');
        } elseif (fmod($this->getQuantidade(), 1) > 0 && !$produto->isDivisivel()) {
            $errors['produtoid'] = _t('estoque.produto_indivisible', $produto->getDescricao());
        }
        if (is_null($this->getSetorID())) {
            $errors['setorid'] = _t('estoque.setor_id_cannot_empty');
        }
        if (is_null($this->getPrestadorID())) {
            $errors['prestadorid'] = _t('estoque.prestador_id_cannot_empty');
        }
        if (!Validator::checkInSet($this->getTipoMovimento(), self::getTipoMovimentoOptions())) {
            $errors['tipomovimento'] = _t('estoque.tipo_movimento_invalid');
        }
        if (is_null($this->getQuantidade())) {
            $errors['quantidade'] = _t('estoque.quantidade_cannot_empty');
        } elseif ($this->getQuantidade() == 0) {
            $errors['quantidade'] = _t('estoque.quantidade_zero');
        } elseif ($this->getTipoMovimento() == self::TIPO_MOVIMENTO_ENTRADA && $this->getQuantidade() < 0) {
            $errors['quantidade'] = _t('estoque.quantidade_negative');
        } elseif (!is_null($this->getTransacaoID()) && $this->getQuantidade() > 0) {
            $errors['quantidade'] = _t('estoque.sales_cannot_add');
        }
        if (is_null($this->getPrecoCompra())) {
            $errors['precocompra'] = _t('estoque.preco_compra_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getCancelado())) {
            $errors['cancelado'] = _t('estoque.cancelado_invalid');
        } elseif ($this->exists() && $this->isCancelado()) {
            $old = self::findByID($this->getID());
            if ($old->isCancelado()) {
                $errors['cancelado'] = _t('estoque.already_canceled');
            } else {
                $count = self::count(['entradaid' => $this->getID(), 'cancelado' => 'N']);
                if ($count > 0) {
                    $errors['cancelado'] = _t('estoque.used_cannot_cancel');
                }
            }
        } elseif (!$this->exists() && $this->isCancelado()) {
            $errors['cancelado'] = _t('estoque.new_canceled');
        }
        $this->setDataMovimento(DB::now());
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        $values = $this->toArray();
        if ($this->exists()) {
            unset($values['datamovimento']);
        }
        return $values;
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
        $compra = 0;
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
            $compra += -$this->getQuantidade() * $this->getPrecoCompra();
            $restante = $restante - $this->getQuantidade();
            if ($restante > -0.0005) {
                break;
            }
            $this->setQuantidade($restante);
        }
        return $compra;
    }

    /**
     * Retira do estoque a quantidade informada
     * @param 
     */
    public function retirar($opcionais)
    {
        $compra = 0;
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
                $compra += $this->retirarFIFO();
            }
        }
        return $compra;
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
     * Informa de qual compra originou essa entrada em estoque
     * @return \MZ\Stock\Requisito The object fetched from database
     */
    public function findRequisitoID()
    {
        return \MZ\Stock\Requisito::findByID($this->getRequisitoID());
    }

    /**
     * Identificador do item que gerou a saída desse produto do estoque
     * @return \MZ\Sale\Item The object fetched from database
     */
    public function findTransacaoID()
    {
        return \MZ\Sale\Item::findByID($this->getTransacaoID());
    }

    /**
     * Informa de qual entrada no estoque essa saída foi retirada, permite
     * estoque FIFO
     * @return \MZ\Stock\Estoque The object fetched from database
     */
    public function findEntradaID()
    {
        return \MZ\Stock\Estoque::findByID($this->getEntradaID());
    }

    /**
     * Fornecedor do produto
     * @return \MZ\Stock\Fornecedor The object fetched from database
     */
    public function findFornecedorID()
    {
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
     * Prestador que inseriu/retirou o produto do estoque
     * @return \MZ\Provider\Prestador The object fetched from database
     */
    public function findPrestadorID()
    {
        return \MZ\Provider\Prestador::findByID($this->getPrestadorID());
    }

    /**
     * Find first available stock entry
     * @return Estoque Self instance filled or empty when not found
     */
    public function findAvailableEntry()
    {
        $query = $this->query(
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
     * @param int $index choose option from index
     * @return string[]|string A associative key -> translated representative text or text for index
     */
    public static function getTipoMovimentoOptions($index = null)
    {
        $options = [
            self::TIPO_MOVIMENTO_ENTRADA => _t('estoque.tipo_movimento_entrada'),
            self::TIPO_MOVIMENTO_VENDA => _t('estoque.tipo_movimento_venda'),
            self::TIPO_MOVIMENTO_CONSUMO => _t('estoque.tipo_movimento_consumo'),
            self::TIPO_MOVIMENTO_TRANSFERENCIA => _t('estoque.tipo_movimento_transferencia'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        return Filter::keys($condition, $allowed, 'e.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Estoque e');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('e.id DESC');
        return DB::buildCondition($query, $condition);
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
        return (float)self::sum(['quantidade'], $condition);
    }
}
