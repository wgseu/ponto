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
class EstoqueTipoMovimento
{
    const ENTRADA = 'Entrada';
    const VENDA = 'Venda';
    const CONSUMO = 'Consumo';
    const TRANSFERENCIA = 'Transferencia';
}

class ZEstoque
{
    private $id;
    private $produto_id;
    private $transacao_id;
    private $entrada_id;
    private $fornecedor_id;
    private $setor_id;
    private $funcionario_id;
    private $tipo_movimento;
    private $quantidade;
    private $preco_compra;
    private $lote;
    private $data_fabricacao;
    private $data_vencimento;
    private $detalhes;
    private $cancelado;
    private $data_movimento;

    public function __construct($estoque = [])
    {
        if (is_array($estoque)) {
            $this->setID(isset($estoque['id'])?$estoque['id']:null);
            $this->setProdutoID(isset($estoque['produtoid'])?$estoque['produtoid']:null);
            $this->setTransacaoID(isset($estoque['transacaoid'])?$estoque['transacaoid']:null);
            $this->setEntradaID(isset($estoque['entradaid'])?$estoque['entradaid']:null);
            $this->setFornecedorID(isset($estoque['fornecedorid'])?$estoque['fornecedorid']:null);
            $this->setSetorID(isset($estoque['setorid'])?$estoque['setorid']:null);
            $this->setFuncionarioID(isset($estoque['funcionarioid'])?$estoque['funcionarioid']:null);
            $this->setTipoMovimento(isset($estoque['tipomovimento'])?$estoque['tipomovimento']:null);
            $this->setQuantidade(isset($estoque['quantidade'])?$estoque['quantidade']:null);
            $this->setPrecoCompra(isset($estoque['precocompra'])?$estoque['precocompra']:null);
            $this->setLote(isset($estoque['lote'])?$estoque['lote']:null);
            $this->setDataFabricacao(isset($estoque['datafabricacao'])?$estoque['datafabricacao']:null);
            $this->setDataVencimento(isset($estoque['datavencimento'])?$estoque['datavencimento']:null);
            $this->setDetalhes(isset($estoque['detalhes'])?$estoque['detalhes']:null);
            $this->setCancelado(isset($estoque['cancelado'])?$estoque['cancelado']:null);
            $this->setDataMovimento(isset($estoque['datamovimento'])?$estoque['datamovimento']:null);
        }
    }

    public function getID()
    {
        return $this->id;
    }

    public function setID($id)
    {
        $this->id = $id;
    }

    public function getProdutoID()
    {
        return $this->produto_id;
    }

    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
    }

    public function getTransacaoID()
    {
        return $this->transacao_id;
    }

    public function setTransacaoID($transacao_id)
    {
        $this->transacao_id = $transacao_id;
    }

    public function getEntradaID()
    {
        return $this->entrada_id;
    }

    public function setEntradaID($entrada_id)
    {
        $this->entrada_id = $entrada_id;
    }

    public function getFornecedorID()
    {
        return $this->fornecedor_id;
    }

    public function setFornecedorID($fornecedor_id)
    {
        $this->fornecedor_id = $fornecedor_id;
    }

    public function getSetorID()
    {
        return $this->setor_id;
    }

    public function setSetorID($setor_id)
    {
        $this->setor_id = $setor_id;
    }

    public function getFuncionarioID()
    {
        return $this->funcionario_id;
    }

    public function setFuncionarioID($funcionario_id)
    {
        $this->funcionario_id = $funcionario_id;
    }

    public function getTipoMovimento()
    {
        return $this->tipo_movimento;
    }

    public function setTipoMovimento($tipo_movimento)
    {
        $this->tipo_movimento = $tipo_movimento;
    }

    public function getQuantidade()
    {
        return $this->quantidade;
    }

    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    public function getPrecoCompra()
    {
        return $this->preco_compra;
    }

    public function setPrecoCompra($preco_compra)
    {
        $this->preco_compra = $preco_compra;
    }

    public function getLote()
    {
        return $this->lote;
    }

    public function setLote($lote)
    {
        $this->lote = $lote;
    }

    public function getDataFabricacao()
    {
        return $this->data_fabricacao;
    }

    public function setDataFabricacao($data_fabricacao)
    {
        $this->data_fabricacao = $data_fabricacao;
    }

    public function getDataVencimento()
    {
        return $this->data_vencimento;
    }

    public function setDataVencimento($data_vencimento)
    {
        $this->data_vencimento = $data_vencimento;
    }

    public function getDetalhes()
    {
        return $this->detalhes;
    }

    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
    }

    public function getCancelado()
    {
        return $this->cancelado;
    }

    public function isCancelado()
    {
        return $this->cancelado == 'Y';
    }

    public function setCancelado($cancelado)
    {
        $this->cancelado = $cancelado;
    }

    public function getDataMovimento()
    {
        return $this->data_movimento;
    }

    public function setDataMovimento($data_movimento)
    {
        $this->data_movimento = $data_movimento;
    }

    public function toArray()
    {
        $estoque = [];
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

    public static function getPeloID($id)
    {
        $query = \DB::$pdo->from('Estoque')
                         ->where(['id' => $id]);
        return new Estoque($query->fetch());
    }

    public static function getUltimoPrecoCompra($produtoid)
    {
        $query = \DB::$pdo->from('Estoque')
                         ->select(null)
                         ->select('precocompra')
                         ->where([
                                'produtoid' => $produtoid,
                                'cancelado' => 'N',
                            ])
                         ->orderBy('id DESC')
                         ->limit(1);
        return $query->fetchColumn() + 0.0;
    }

    public static function getEntradaDisponivel(&$estoque)
    {
        $query = \DB::$pdo->from('Estoque ee')
                         ->select('ROUND(ee.quantidade + SUM(COALESCE(es.quantidade, 0)), 6) as quantidaderestante')
                         ->leftJoin('Estoque es ON es.entradaid = ee.id AND es.cancelado = ?', 'N')
                         ->where([
                                'ee.produtoid' => $estoque->getProdutoID(),
                                'ee.setorid' => $estoque->getSetorID(),
                                'ee.cancelado' => 'N',
                            ])
                         ->where('ee.quantidade > 0')
                         ->groupBy('ee.id')
                         ->having('quantidaderestante > 0')
                         ->limit(1);
        $array = $query->fetch();
        $_estoque = new Estoque($array);
        $_estoque->setQuantidade($array['quantidaderestante']);
        return $_estoque;
    }

    private static function validarCampos(&$estoque)
    {
        $erros = [];
        if (!is_numeric($estoque['produtoid'])) {
            $erros['produtoid'] = 'O código do produto não foi informado';
        }
        $estoque['transacaoid'] = trim($estoque['transacaoid']);
        if (strlen($estoque['transacaoid']) == 0) {
            $estoque['transacaoid'] = null;
        } elseif (!is_numeric($estoque['transacaoid'])) {
            $erros['transacaoid'] = 'O ID da transacao não é um número';
        }
        $estoque['entradaid'] = trim($estoque['entradaid']);
        if (strlen($estoque['entradaid']) == 0) {
            $estoque['entradaid'] = null;
        } elseif (!is_numeric($estoque['entradaid'])) {
            $erros['entradaid'] = 'O id da entrada do produto não é um número';
        }
        $estoque['fornecedorid'] = trim($estoque['fornecedorid']);
        if (strlen($estoque['fornecedorid']) == 0) {
            $estoque['fornecedorid'] = null;
        } elseif (!is_numeric($estoque['fornecedorid'])) {
            $erros['fornecedorid'] = 'O código do fornecedor não é um número';
        }
        if (!is_numeric($estoque['setorid'])) {
            $erros['setorid'] = 'O id do setor não é um número';
        }
        if (!is_numeric($estoque['funcionarioid'])) {
            $erros['funcionarioid'] = 'O código do funcionario não foi informado corretamente';
        }
        $estoque['tipomovimento'] = strval($estoque['tipomovimento']);
        if (!in_array($estoque['tipomovimento'], ['Entrada', 'Venda', 'Consumo', 'Transferencia'])) {
            $erros['tipomovimento'] = 'O tipo de movimento informado não é válido';
        }
        if (!is_numeric($estoque['quantidade'])) {
            $erros['quantidade'] = 'A quantidade informada não é válida';
        }
        if ($estoque['quantidade'] <= 0 && $estoque['tipomovimento'] == 'Entrada') {
            $erros['quantidade'] = 'A quantidade não pode ser nula ou negativa';
        }
        $estoque['precocompra'] = trim($estoque['precocompra']);
        if (strlen($estoque['precocompra']) == 0) {
            $estoque['precocompra'] = null;
        } elseif (!is_numeric($estoque['precocompra'])) {
            $erros['precocompra'] = 'O preço de compra não é válido';
        }
        $estoque['lote'] = strip_tags(trim($estoque['lote']));
        if (strlen($estoque['lote']) == 0) {
            $estoque['lote'] = null;
        }
        $estoque['datafabricacao'] = strval($estoque['datafabricacao']);
        if (strlen($estoque['datafabricacao']) == 0) {
            $estoque['datafabricacao'] = null;
        } else {
            $time = strtotime($estoque['datafabricacao']);
            if ($time === false) {
                $erros['datafabricacao'] = 'A data de fabricação é inválida';
            } else {
                $estoque['datafabricacao'] = date('Y-m-d', $time);
            }
        }
        $estoque['datavencimento'] = strval($estoque['datavencimento']);
        if (strlen($estoque['datavencimento']) == 0) {
            $estoque['datavencimento'] = null;
        } else {
            $time = strtotime($estoque['datavencimento']);
            if ($time === false) {
                $erros['datavencimento'] = 'A data de vencimento é inválida';
            } else {
                $estoque['datavencimento'] = date('Y-m-d', $time);
            }
        }
        $estoque['detalhes'] = strip_tags(trim($estoque['detalhes']));
        if (strlen($estoque['detalhes']) == 0) {
            $estoque['detalhes'] = null;
        }
        $estoque['cancelado'] = trim($estoque['cancelado']);
        if (strlen($estoque['cancelado']) == 0) {
            $estoque['cancelado'] = 'N';
        } elseif (!in_array($estoque['cancelado'], ['Y', 'N'])) {
            $erros['cancelado'] = 'O status de cancelamento informado não é válido';
        }
        $estoque['datamovimento'] = date('Y-m-d H:i:s');
        if (!empty($erros)) {
            throw new \MZ\Exception\ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new \MZ\Exception\ValidationException(['id' => 'O ID informado já está cadastrado']);
        }
    }

    public static function cadastrar($estoque)
    {
        $_estoque = $estoque->toArray();
        self::validarCampos($_estoque);
        try {
            $_estoque['id'] = \DB::$pdo->insertInto('Estoque')->values($_estoque)->execute();
        } catch (\Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_estoque['id']);
    }

    public static function atualizar($estoque)
    {
        $_estoque = $estoque->toArray();
        if (!$_estoque['id']) {
            throw new \MZ\Exception\ValidationException(['id' => 'O id do estoque não foi informado']);
        }
        self::validarCampos($_estoque);
        $campos = [
            'produtoid',
            'transacaoid',
            'entradaid',
            'fornecedorid',
            'setorid',
            'funcionarioid',
            'tipomovimento',
            'quantidade',
            'precocompra',
            'lote',
            'datafabricacao',
            'datavencimento',
            'detalhes',
            'cancelado',
            'datamovimento',
        ];
        try {
            $query = \DB::$pdo->update('Estoque');
            $query = $query->set(array_intersect_key($_estoque, array_flip($campos)));
            $query = $query->where('id', $_estoque['id']);
            $query->execute();
        } catch (\Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_estoque['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new \Exception('Não foi possível excluir o estoque, o id do estoque não foi informado');
        }
        $query = \DB::$pdo->deleteFrom('Estoque')
                         ->where(['id' => $id]);
        return $query->execute();
    }

    public function cancelar()
    {
        if ($this->isCancelado()) {
            throw new \Exception('A entrada no estoque para esse produto já está cancelada');
        }
        $query = \DB::$pdo->from('Estoque')
                         ->where('entradaid', $this->getID());
        if ($query->count() > 0) {
            throw new \Exception('Não foi possível cancelar a entrada no estoque, uma ou mais vendas já foram realizadas');
        }
        $query = \DB::$pdo->update('Estoque')
                         ->set('cancelado', 'Y')
                         ->where('id', $this->getID());
        try {
            $query->execute();
        } catch (\Exception $e) {
            $produto = $estoque->findProdutoID();
            throw new \Exception('Não foi possível cancelar a entrada do produto "' . $produto->getDescricao() . '"!');
        }
        $this->setCancelado('Y');
    }

    public static function inserir($estoque)
    {
        $setor = Setor::findByNome('Vendas');
        if (!$setor->exists()) {
            $setor = Setor::getPrimeiro();
        }
        $estoque->setTransacaoID(null);
        $estoque->setEntradaID(null);
        $estoque->setTipoMovimento(EstoqueTipo::MOVIMENTO_ENTRADA);
        $estoque->setCancelado('N');
        $estoque->setSetorID($setor->getID());
        $produto = $estoque->findProdutoID();
        if (!$produto->exists()) {
            throw new \MZ\Exception\ValidationException(['produtoid' => 'O produto informado não existe']);
        }
        if ($produto->getTipo() != Produto::TIPO_PRODUTO) {
            throw new \MZ\Exception\ValidationException(['produtoid' => 'O produto informado não é do tipo produto']);
        }
        if (!is_null($produto->getSetorEstoqueID())) {
            $estoque->setSetorID($produto->getSetorEstoqueID());
        }
        return self::cadastrar($estoque);
    }

    public static function retirar(&$produto_pedido, $ignore_composicoes)
    {
        $setor = Setor::findByNome('Vendas');
        if (!$setor->exists()) {
            $setor = Setor::getPrimeiro();
        }
        $estoque = new Estoque();
        $estoque->setTransacaoID($produto_pedido->getID());
        $estoque->setFuncionarioID($produto_pedido->getFuncionarioID());
        $estoque->setTipoMovimento(EstoqueTipo::MOVIMENTO_VENDA);
        $estoque->setCancelado('N');
        $stack = new SplStack();
        $composicao = new Composicao();
        $composicao->setProdutoID($produto_pedido->getProdutoID());
        $composicao->setQuantidade($produto_pedido->getQuantidade());
        $stack->push($composicao);
        while (!$stack->isEmpty()) {
            $composicao = $stack->pop();
            $produto = $composicao->findProdutoID();
            if ($produto->getTipo() == Produto::TIPO_PACOTE) {
                break;
            }
            if ($produto->getTipo() == Produto::TIPO_COMPOSICAO) {
                $composicoes = Composicao::getTodasDaComposicaoID(null, $composicao->getProdutoID());
                foreach ($composicoes as $_composicao) {
                    $_composicao->setQuantidade($_composicao->getQuantidade() * $composicao->getQuantidade());
                    $existe = isset($ignore_composicoes[$_composicao->getID()]);
                    if ($existe && $_composicao->getTipo() != Composicao::TIPO_ADICIONAL) {
                        unset($ignore_composicoes[$_composicao->getID()]);
                    } elseif ($existe && $_composicao->getTipo() == Composicao::TIPO_ADICIONAL) {
                        unset($ignore_composicoes[$_composicao->getID()]);
                        $stack->push($_composicao);
                    } elseif ($_composicao->getTipo() != Composicao::TIPO_ADICIONAL) {
                        $stack->push($_composicao);
                    }
                }
            } else { // o composto é um produto
                $estoque->setSetorID($setor->getID());
                if (!is_null($produto->getSetorEstoqueID())) {
                    $estoque->setSetorID($produto->getSetorEstoqueID());
                }
                $estoque->setProdutoID($produto->getID());
                $estoque->setQuantidade(-$composicao->getQuantidade());
                self::retirarFIFO($estoque, $produto);
            }
        }
    }

    public static function retirarFIFO(&$estoque, &$produto)
    {
        $negativo = is_boolean_config('Estoque', 'Estoque.Negativo');
        $restante = $estoque->getQuantidade();
        while (true) {
            $entrada = self::getEntradaDisponivel($estoque);
            if (!$entrada->exists()) {
                if ($negativo) {
                    $entrada->setQuantidade(-$restante);
                    $entrada->setPrecoCompra(0.0000);
                } else {
                    throw new \Exception('Não há estoque para o produto "' . $produto->getDescricao() . '"');
                }
            }
            if ($entrada->getQuantidade() < -$estoque->getQuantidade()) {
                $estoque->setQuantidade(-$entrada->getQuantidade());
            }
            $estoque->setID(null);
            $estoque->setPrecoCompra($entrada->getPrecoCompra());
            $estoque->setEntradaID($entrada->getID());
            $estoque->setFornecedorID($entrada->getFornecedorID());
            $estoque->setLote($entrada->getLote());
            $estoque->setDataFabricacao($entrada->getDataFabricacao());
            $estoque->setDataVencimento($entrada->getDataVencimento());
            $estoque = self::cadastrar($estoque);
            $restante = $restante - $estoque->getQuantidade();
            if ($restante > -0.0005) {
                break;
            }
            $estoque->setQuantidade($restante);
        }
    }

    private static function initSearch($produto_id, $fornecedor_id, $tipo)
    {
        $query = \DB::$pdo->from('Estoque')
                         ->orderBy('id DESC');
        if (is_numeric($produto_id)) {
            $query = $query->where('produtoid', $produto_id);
        }
        if (is_numeric($fornecedor_id)) {
            $query = $query->where('fornecedorid', $fornecedor_id);
        }
        $tipo = trim($tipo);
        if ($tipo != '') {
            $query = $query->where('tipomovimento', $tipo);
        }
        return $query;
    }

    public static function getTodos($produto_id = null, $fornecedor_id = null, $tipo = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearch($produto_id, $fornecedor_id, $tipo);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_estoques = $query->fetchAll();
        $estoques = [];
        foreach ($_estoques as $estoque) {
            $estoques[] = new Estoque($estoque);
        }
        return $estoques;
    }

    public static function getCount($produto_id = null, $fornecedor_id = null, $tipo = null)
    {
        $query = self::initSearch($produto_id, $fornecedor_id, $tipo);
        return $query->count();
    }

    private static function initSearchDoProdutoID($produto_id)
    {
        return   \DB::$pdo->from('Estoque')
                         ->where(['produtoid' => $produto_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDoProdutoID($produto_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoProdutoID($produto_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_estoques = $query->fetchAll();
        $estoques = [];
        foreach ($_estoques as $estoque) {
            $estoques[] = new Estoque($estoque);
        }
        return $estoques;
    }

    public static function getCountDoProdutoID($produto_id)
    {
        $query = self::initSearchDoProdutoID($produto_id);
        return $query->count();
    }

    private static function initSearchDaTransacaoID($transacao_id)
    {
        return   \DB::$pdo->from('Estoque')
                         ->where(['transacaoid' => $transacao_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDaTransacaoID($transacao_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaTransacaoID($transacao_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_estoques = $query->fetchAll();
        $estoques = [];
        foreach ($_estoques as $estoque) {
            $estoques[] = new Estoque($estoque);
        }
        return $estoques;
    }

    public static function getCountDaTransacaoID($transacao_id)
    {
        $query = self::initSearchDaTransacaoID($transacao_id);
        return $query->count();
    }

    private static function initSearchDoFornecedorID($fornecedor_id)
    {
        return   \DB::$pdo->from('Estoque')
                         ->where(['fornecedorid' => $fornecedor_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDoFornecedorID($fornecedor_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoFornecedorID($fornecedor_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_estoques = $query->fetchAll();
        $estoques = [];
        foreach ($_estoques as $estoque) {
            $estoques[] = new Estoque($estoque);
        }
        return $estoques;
    }

    public static function getCountDoFornecedorID($fornecedor_id)
    {
        $query = self::initSearchDoFornecedorID($fornecedor_id);
        return $query->count();
    }

    private static function initSearchDoFuncionarioID($funcionario_id)
    {
        return   \DB::$pdo->from('Estoque')
                         ->where(['funcionarioid' => $funcionario_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDoFuncionarioID($funcionario_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoFuncionarioID($funcionario_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_estoques = $query->fetchAll();
        $estoques = [];
        foreach ($_estoques as $estoque) {
            $estoques[] = new Estoque($estoque);
        }
        return $estoques;
    }

    public static function getCountDoFuncionarioID($funcionario_id)
    {
        $query = self::initSearchDoFuncionarioID($funcionario_id);
        return $query->count();
    }

    private static function initSearchDoSetorID($setor_id)
    {
        return   \DB::$pdo->from('Estoque')
                         ->where(['setorid' => $setor_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDoSetorID($setor_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoSetorID($setor_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_estoques = $query->fetchAll();
        $estoques = [];
        foreach ($_estoques as $estoque) {
            $estoques[] = new Estoque($estoque);
        }
        return $estoques;
    }

    public static function getCountDoSetorID($setor_id)
    {
        $query = self::initSearchDoSetorID($setor_id);
        return $query->count();
    }

    private static function initSearchDaEntradaID($entrada_id)
    {
        return   \DB::$pdo->from('Estoque')
                         ->where(['entradaid' => $entrada_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDaEntradaID($entrada_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaEntradaID($entrada_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_estoques = $query->fetchAll();
        $estoques = [];
        foreach ($_estoques as $estoque) {
            $estoques[] = new Estoque($estoque);
        }
        return $estoques;
    }

    public static function getCountDaEntradaID($entrada_id)
    {
        $query = self::initSearchDaEntradaID($entrada_id);
        return $query->count();
    }
}
