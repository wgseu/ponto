<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
	Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
	O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
	DISPOSIÇÕES GERAIS
	O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
	ou outros avisos ou restrições de propriedade do GrandChef.

	O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
	ou descompilação do GrandChef.

	PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA

	GrandChef é a especialidade do desenvolvedor e seus
	licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
	de leis de propriedade.

	O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
	direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
*/
class ProdutoPedidoEstado {
	const ADICIONADO = 'Adicionado';
	const ENVIADO = 'Enviado';
	const PROCESSADO = 'Processado';
	const PRONTO = 'Pronto';
	const DISPONIVEL = 'Disponivel';
	const ENTREGUE = 'Entregue';
}

class ZProdutoPedido {
	private $id;
	private $pedido_id;
	private $funcionario_id;
	private $produto_id;
	private $servico_id;
	private $produto_pedido_id;
	private $descricao;
	private $preco;
	private $quantidade;
	private $porcentagem;
	private $preco_venda;
	private $preco_compra;
	private $detalhes;
	private $estado;
	private $visualizado;
	private $data_visualizacao;
	private $data_atualizacao;
	private $cancelado;
	private $data_hora;

	public function __construct($produto_pedido = array()) {
		$this->fromArray($produto_pedido);
	}

	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	public function getPedidoID() {
		return $this->pedido_id;
	}

	public function setPedidoID($pedido_id) {
		$this->pedido_id = $pedido_id;
	}

	public function getFuncionarioID() {
		return $this->funcionario_id;
	}

	public function setFuncionarioID($funcionario_id) {
		$this->funcionario_id = $funcionario_id;
	}

	public function getProdutoID() {
		return $this->produto_id;
	}

	public function setProdutoID($produto_id) {
		$this->produto_id = $produto_id;
	}

	public function getServicoID() {
		return $this->servico_id;
	}

	public function setServicoID($servico_id) {
		$this->servico_id = $servico_id;
	}

	public function getProdutoPedidoID() {
		return $this->produto_pedido_id;
	}

	public function setProdutoPedidoID($produto_pedido_id) {
		$this->produto_pedido_id = $produto_pedido_id;
	}

	public function getDescricao() {
		return $this->descricao;
	}

	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}

	public function getPreco() {
		return $this->preco;
	}

	public function setPreco($preco) {
		$this->preco = $preco;
	}

	public function getQuantidade() {
		return $this->quantidade;
	}

	public function setQuantidade($quantidade) {
		$this->quantidade = $quantidade;
	}

	public function getPorcentagem() {
		return $this->porcentagem;
	}

	public function setPorcentagem($porcentagem) {
		$this->porcentagem = $porcentagem;
	}

	public function getPrecoVenda() {
		return $this->preco_venda;
	}

	public function setPrecoVenda($preco_venda) {
		$this->preco_venda = $preco_venda;
	}

	public function getPrecoCompra() {
		return $this->preco_compra;
	}

	public function setPrecoCompra($preco_compra) {
		$this->preco_compra = $preco_compra;
	}

	public function getDetalhes() {
		return $this->detalhes;
	}

	public function setDetalhes($detalhes) {
		$this->detalhes = $detalhes;
	}

	public function getEstado() {
		return $this->estado;
	}

	public function setEstado($estado) {
		$this->estado = $estado;
	}

	public function getVisualizado() {
		return $this->visualizado;
	}

	public function isVisualizado() {
		return $this->visualizado == 'Y';
	}

	public function setVisualizado($visualizado) {
		$this->visualizado = $visualizado;
	}

	public function getDataVisualizacao() {
		return $this->data_visualizacao;
	}

	public function setDataVisualizacao($data_visualizacao) {
		$this->data_visualizacao = $data_visualizacao;
	}

	public function getDataAtualizacao() {
		return $this->data_atualizacao;
	}

	public function setDataAtualizacao($data_atualizacao) {
		$this->data_atualizacao = $data_atualizacao;
	}

	public function getCancelado() {
		return $this->cancelado;
	}

	public function isCancelado() {
		return $this->cancelado == 'Y';
	}

	public function setCancelado($cancelado) {
		$this->cancelado = $cancelado;
	}

	public function getDataHora() {
		return $this->data_hora;
	}

	public function setDataHora($data_hora) {
		$this->data_hora = $data_hora;
	}

	public function getSubvenda() {
		return $this->getPrecoVenda() * $this->getQuantidade();
	}

	public function getSubtotal() {
		return $this->getPreco() * $this->getQuantidade();
	}

	/**
	 * Obtém o desconto de uma unidade
	 * @return float valor de desconto unitário
	 */
	public function getDesconto() {
		return $this->getPrecoVenda() - $this->getPreco();
	}

	/**
	 * Obtém o desconto desse lançamento, inclui todas as quantidades
	 * @return float desconto geral do lançamento
	 */
	public function getDescontos() {
		return $this->getSubvenda() - $this->getSubtotal();
	}

	public function getComissao() {
		return $this->getSubtotal() * $this->getPorcentagem() / 100.0;
	}

	public function getTotal() {
		return $this->getSubtotal() + $this->getComissao();
	}

	public function getCusto() {
		return $this->getQuantidade() * $this->getPrecoCompra();
	}

	public function getLucro() {
		return $this->getSubtotal() - $this->getCusto();
	}

	public function isServico() {
		return !is_null($this->getServicoID()) && is_greater($this->getPreco(), 0.00);
	}

	public function getDestino($values)
	{
		switch ($values['pedido_tipo']) {
			case \PedidoTipo::MESA:
				return $values['mesa_nome'];
			case \PedidoTipo::COMANDA:
				return $values['comanda_nome'];
			case \PedidoTipo::AVULSO:
				return 'Balcão';
			default:
				return 'Entrega';
		}
	}

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $produto_pedido Associated key -> value to assign into this instance
     * @return ProdutoPedido Self instance
     */
    public function fromArray($produto_pedido = array())
    {
        if ($produto_pedido instanceof ProdutoPedido) {
            $produto_pedido = $produto_pedido->toArray();
        } elseif (!is_array($produto_pedido)) {
            $produto_pedido = array();
        }
        if (!isset($produto_pedido['id'])) {
            $this->setID(null);
        } else {
            $this->setID($produto_pedido['id']);
        }
        if (!isset($produto_pedido['pedidoid'])) {
            $this->setPedidoID(null);
        } else {
            $this->setPedidoID($produto_pedido['pedidoid']);
        }
        if (!isset($produto_pedido['funcionarioid'])) {
            $this->setFuncionarioID(null);
        } else {
            $this->setFuncionarioID($produto_pedido['funcionarioid']);
        }
        if (!array_key_exists('produtoid', $produto_pedido)) {
            $this->setProdutoID(null);
        } else {
            $this->setProdutoID($produto_pedido['produtoid']);
        }
        if (!array_key_exists('servicoid', $produto_pedido)) {
            $this->setServicoID(null);
        } else {
            $this->setServicoID($produto_pedido['servicoid']);
        }
        if (!array_key_exists('produtopedidoid', $produto_pedido)) {
            $this->setProdutoPedidoID(null);
        } else {
            $this->setProdutoPedidoID($produto_pedido['produtopedidoid']);
        }
        if (!array_key_exists('descricao', $produto_pedido)) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($produto_pedido['descricao']);
        }
        if (!isset($produto_pedido['preco'])) {
            $this->setPreco(null);
        } else {
            $this->setPreco($produto_pedido['preco']);
        }
        if (!isset($produto_pedido['quantidade'])) {
            $this->setQuantidade(null);
        } else {
            $this->setQuantidade($produto_pedido['quantidade']);
        }
        if (!isset($produto_pedido['porcentagem'])) {
            $this->setPorcentagem(null);
        } else {
            $this->setPorcentagem($produto_pedido['porcentagem']);
        }
        if (!isset($produto_pedido['precovenda'])) {
            $this->setPrecoVenda(null);
        } else {
            $this->setPrecoVenda($produto_pedido['precovenda']);
        }
        if (!isset($produto_pedido['precocompra'])) {
            $this->setPrecoCompra(null);
        } else {
            $this->setPrecoCompra($produto_pedido['precocompra']);
        }
        if (!array_key_exists('detalhes', $produto_pedido)) {
            $this->setDetalhes(null);
        } else {
            $this->setDetalhes($produto_pedido['detalhes']);
        }
        if (!isset($produto_pedido['estado'])) {
            $this->setEstado(null);
        } else {
            $this->setEstado($produto_pedido['estado']);
        }
        if (!isset($produto_pedido['visualizado'])) {
            $this->setVisualizado(null);
        } else {
            $this->setVisualizado($produto_pedido['visualizado']);
        }
        if (!array_key_exists('datavisualizacao', $produto_pedido)) {
            $this->setDataVisualizacao(null);
        } else {
            $this->setDataVisualizacao($produto_pedido['datavisualizacao']);
        }
        if (!array_key_exists('dataatualizacao', $produto_pedido)) {
            $this->setDataAtualizacao(null);
        } else {
            $this->setDataAtualizacao($produto_pedido['dataatualizacao']);
        }
        if (!isset($produto_pedido['cancelado'])) {
            $this->setCancelado(null);
        } else {
            $this->setCancelado($produto_pedido['cancelado']);
        }
        if (!isset($produto_pedido['datahora'])) {
            $this->setDataHora(null);
        } else {
            $this->setDataHora($produto_pedido['datahora']);
        }
        return $this;
    }

	public function toArray() {
		$produto_pedido = array();
		$produto_pedido['id'] = $this->getID();
		$produto_pedido['pedidoid'] = $this->getPedidoID();
		$produto_pedido['funcionarioid'] = $this->getFuncionarioID();
		$produto_pedido['produtoid'] = $this->getProdutoID();
		$produto_pedido['servicoid'] = $this->getServicoID();
		$produto_pedido['produtopedidoid'] = $this->getProdutoPedidoID();
		$produto_pedido['descricao'] = $this->getDescricao();
		$produto_pedido['preco'] = $this->getPreco();
		$produto_pedido['quantidade'] = $this->getQuantidade();
		$produto_pedido['porcentagem'] = $this->getPorcentagem();
		$produto_pedido['precovenda'] = $this->getPrecoVenda();
		$produto_pedido['precocompra'] = $this->getPrecoCompra();
		$produto_pedido['detalhes'] = $this->getDetalhes();
		$produto_pedido['estado'] = $this->getEstado();
		$produto_pedido['visualizado'] = $this->getVisualizado();
		$produto_pedido['datavisualizacao'] = $this->getDataVisualizacao();
		$produto_pedido['dataatualizacao'] = $this->getDataAtualizacao();
		$produto_pedido['cancelado'] = $this->getCancelado();
		$produto_pedido['datahora'] = $this->getDataHora();
		return $produto_pedido;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Produtos_Pedidos')
		                 ->where(array('id' => $id));
		return new ZProdutoPedido($query->fetch());
	}

	private static function validarCampos(&$produto_pedido) {
		$erros = array();
		if(!is_numeric($produto_pedido['pedidoid']))
			$erros['pedidoid'] = 'O id do pedido não foi informado';
		if(!is_numeric($produto_pedido['funcionarioid']))
			$erros['funcionarioid'] = 'O id do funcionário não foi informado';
		$produto_pedido['produtoid'] = trim($produto_pedido['produtoid']);
		if(strlen($produto_pedido['produtoid']) == 0)
			$produto_pedido['produtoid'] = null;
		else if(!is_numeric($produto_pedido['produtoid']))
			$erros['produtoid'] = 'O código do produto não é válido';
		$produto_pedido['servicoid'] = trim($produto_pedido['servicoid']);
		if(strlen($produto_pedido['servicoid']) == 0)
			$produto_pedido['servicoid'] = null;
		else if(!is_numeric($produto_pedido['servicoid']))
			$erros['servicoid'] = 'O código do serviço não é válido';
		$produto_pedido['produtopedidoid'] = trim($produto_pedido['produtopedidoid']);
		if(strlen($produto_pedido['produtopedidoid']) == 0)
			$produto_pedido['produtopedidoid'] = null;
		else if(!is_numeric($produto_pedido['produtopedidoid']))
			$erros['produtopedidoid'] = 'O id do pedido do pacote não é válido';
		$produto_pedido['descricao'] = strip_tags(trim($produto_pedido['descricao']));
		if(strlen($produto_pedido['descricao']) == 0)
			$produto_pedido['descricao'] = null;
		if(!is_numeric($produto_pedido['preco']))
			$erros['preco'] = 'O preço do produto não foi informado';
		if(!is_numeric($produto_pedido['quantidade']))
			$erros['quantidade'] = 'A quantidade não foi informada';
		if(!is_numeric($produto_pedido['porcentagem']))
			$erros['porcentagem'] = 'A porcentagem da comissão não foi informada';
		else
			$produto_pedido['porcentagem'] = floatval($produto_pedido['porcentagem']);
		if(!is_numeric($produto_pedido['precovenda']))
			$erros['precovenda'] = 'O preço de venda não foi informado';
		if(!is_numeric($produto_pedido['precocompra']))
			$erros['precocompra'] = 'O preço de compra não foi informado';
		else
			$produto_pedido['precocompra'] = floatval($produto_pedido['precocompra']);
		$produto_pedido['detalhes'] = strip_tags(trim($produto_pedido['detalhes']));
		if(strlen($produto_pedido['detalhes']) == 0)
			$produto_pedido['detalhes'] = null;
		$produto_pedido['estado'] = trim($produto_pedido['estado']);
		if(strlen($produto_pedido['estado']) == 0)
			$produto_pedido['estado'] = null;
		else if(!in_array($produto_pedido['estado'], array('Adicionado', 'Enviado', 'Processado', 'Pronto', 'Disponivel', 'Entregue')))
			$erros['estado'] = 'O estado informado não é válido';
		$produto_pedido['visualizado'] = trim($produto_pedido['visualizado']);
		if(strlen($produto_pedido['visualizado']) == 0)
			$produto_pedido['visualizado'] = 'N';
		else if(!in_array($produto_pedido['visualizado'], array('Y', 'N')))
			$erros['visualizado'] = 'A visualização informada não é válida';
		$produto_pedido['datavisualizacao'] = null;
		$produto_pedido['dataatualizacao'] = date('Y-m-d H:i:s');
		$produto_pedido['cancelado'] = trim($produto_pedido['cancelado']);
		if(strlen($produto_pedido['cancelado']) == 0)
			$produto_pedido['cancelado'] = 'N';
		else if(!in_array($produto_pedido['cancelado'], array('Y', 'N')))
			$erros['cancelado'] = 'A informação de cancelamento não é válida';
		$produto_pedido['datahora'] = date('Y-m-d H:i:s');
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
	}

	public static function cadastrar($produto_pedido, $produto, $formacoes) {
		$_produto_pedido = $produto_pedido->toArray();
		self::validarCampos($_produto_pedido);
		try {
			if(is_null($produto_pedido->getProdutoPedidoID())) {
				// aplica o desconto dos opcionais e acrescenta o valor dos adicionais
				// apenas nas composições fora de pacotes
				foreach ($formacoes as $key => $_formacao) {
					$formacao = new ZFormacao($_formacao);
					if($formacao->getTipo() != FormacaoTipo::COMPOSICAO)
						continue;
					$composicao = ZComposicao::getPeloID($formacao->getComposicaoID());
					if(is_null($composicao->getID()))
						throw new ValidationException(array('formacao' => 'A composição formada não existe'));
					$operacao = -1;
					if($composicao->getTipo() == ComposicaoTipo::ADICIONAL)
						$operacao = 1;
					$_produto_pedido['precovenda'] += $operacao * $composicao->getValor();
					$_produto_pedido['preco'] += $operacao * $composicao->getValor();
				}
			}
			$_produto_pedido['id'] = DB::$pdo->insertInto('Produtos_Pedidos')->values($_produto_pedido)->execute();
			$produto_pedido = self::getPeloID($_produto_pedido['id']);
			// TODO: verificar se o preço informado está correto
			$composicoes = array();
			foreach ($formacoes as $key => $_formacao) {
				$formacao = new ZFormacao($_formacao);
				$formacao->setProdutoPedidoID($produto_pedido->getID());
				$formacao = ZFormacao::cadastrar($formacao);
				if($formacao->getTipo() == FormacaoTipo::COMPOSICAO)
					$composicoes[$formacao->getComposicaoID()] = $formacao->getID();
			}
			ZEstoque::retirar($produto_pedido, $composicoes);
		} catch (PDOException $e) {
			self::handleException($e);
			$msg = $e->getMessage();
			if(preg_match('/SQLSTATE\[\w+\]: <<[^>]+>>: \d+ (.*)/', $msg, $matches))
				$msg = $matches[1];
			throw new Exception($msg, 45000);
		}
		return $produto_pedido;
	}

	public static function atualizar($produto_pedido) {
		$_produto_pedido = $produto_pedido->toArray();
		if(!$_produto_pedido['id'])
			throw new ValidationException(array('id' => 'O id do produtopedido não foi informado'));
		self::validarCampos($_produto_pedido);
		$campos = array(
			'pedidoid',
			'funcionarioid',
			'produtoid',
			'servicoid',
			'produtopedidoid',
			'descricao',
			'preco',
			'quantidade',
			'porcentagem',
			'precovenda',
			'precocompra',
			'detalhes',
			'estado',
			'visualizado',
			'datavisualizacao',
			'dataatualizacao',
			'cancelado',
			'datahora',
		);
		try {
			$query = DB::$pdo->update('Produtos_Pedidos');
			$query = $query->set(array_intersect_key($_produto_pedido, array_flip($campos)));
			$query = $query->where('id', $_produto_pedido['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_produto_pedido['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir o produtopedido, o id do produtopedido não foi informado');
		$query = DB::$pdo->deleteFrom('Produtos_Pedidos')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch(
		$busca,
		$produto_id,
		$funcionario_id,
		$sessao_id,
		$movimentacao_id,
		$tipo,
		$estado,
		$modulo,
		$data_inicio,
		$data_fim
	) {
		$query = DB::$pdo->from('Produtos_Pedidos pdp')
						 ->select('p.tipo as pedido_tipo')
						 ->select('cf.login as funcionario_login')
						 ->select('COALESCE(sc.descricao, COALESCE(pdp.descricao, pd.descricao)) as produto_descricao')
						 ->select('m.nome as mesa_nome')
						 ->select('cm.nome as comanda_nome')
						 ->leftJoin('Pedidos p ON p.id = pdp.pedidoid')
						 ->leftJoin('Produtos pd ON pd.id = pdp.produtoid')
						 ->leftJoin('Servicos sc ON sc.id = pdp.servicoid')
						 ->leftJoin('Mesas m ON m.id = p.mesaid')
						 ->leftJoin('Comandas cm ON cm.id = p.comandaid')
						 ->leftJoin('Funcionarios f ON f.id = pdp.funcionarioid')
						 ->leftJoin('Clientes cf ON cf.id = f.clienteid')
		                 ->orderBy('pdp.id DESC');
		$busca = trim($busca);
		if(is_numeric($busca)) {
			$query = $query->where('pdp.pedidoid', intval($busca));
		} else if($busca != '') {
			$query = $query->where('pdp.detalhes LIKE ?', '%'.$busca.'%');
		}
		$tipo = trim($tipo);
		if($tipo == '') { // não faz nada
		} else if($tipo == 'Produtos') {
			$query = $query->where('NOT pdp.produtoid', null);
		} else if($tipo == 'Servico') {
			$query = $query->where('NOT pdp.servicoid', null);
		} else if($tipo == 'Desconto') {
			$query = $query->where('NOT pdp.servicoid', null);
			$query = $query->where('pdp.preco < 0');
		} else if($tipo == 'Evento' || $tipo == 'Taxa') {
			$query = $query->where('sc.tipo', $tipo);
			$query = $query->where('pdp.preco >= 0');
		} else {
			$query = $query->where('pd.tipo', $tipo);
		}
		$estado = trim($estado);
		if($estado == '') { // não faz nada
		} else if($estado == 'Valido') {
			$query = $query->where('pdp.cancelado', 'N');
		} else if($estado == 'Cancelado') {
			$query = $query->where('pdp.cancelado', 'Y');
		} else {
			$query = $query->where('pdp.estado', $estado);
		}
		$modulo = trim($modulo);
		if($modulo != '') {
			$query = $query->where('p.tipo', $modulo);
		}
		if(is_numeric($produto_id))
			$query = $query->where('pdp.produtoid', intval($produto_id));
		if(is_numeric($funcionario_id))
			$query = $query->where('pdp.funcionarioid', intval($funcionario_id));
		if (is_numeric($sessao_id)) {
			$query = $query->where('p.sessaoid', intval($sessao_id));
		}
		if (is_numeric($movimentacao_id)) {
			$query = $query->where('p.movimentacaoid', intval($movimentacao_id));
		}
		if(!is_null($data_inicio))
			$query = $query->where('pdp.datahora >= ?', date('Y-m-d', $data_inicio));
		if(!is_null($data_fim))
			$query = $query->where('pdp.datahora <= ?', date('Y-m-d 23:59:59', $data_fim));
		return $query;
	}

	public static function getTodos(
		$busca = null,
		$produto_id = null,
		$funcionario_id = null, 
		$sessao_id = null,
		$movimentacao_id = null,
		$tipo = null,
		$estado = null,
		$modulo = null,
		$data_inicio = null,
		$data_fim = null,
		$raw = false,
		$inicio = null,
		$quantidade = null
	) {
		$query = self::initSearch($busca, $produto_id, $funcionario_id, $sessao_id, $movimentacao_id, 
			$tipo, $estado, $modulo, $data_inicio, $data_fim);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_produto_pedidos = $query->fetchAll();
		if ($raw) {
			return $_produto_pedidos;
		}
		$produto_pedidos = array();
		foreach($_produto_pedidos as $produto_pedido)
			$produto_pedidos[] = new ZProdutoPedido($produto_pedido);
		return $produto_pedidos;
	}

	public static function getCount($busca = null, $produto_id = null, $funcionario_id = null, 
			$tipo = null, $estado = null, $modulo = null, $data_inicio = null, $data_fim = null) {
		$query = self::initSearch($busca, $produto_id, $funcionario_id, $tipo, $estado, $modulo, $data_inicio, $data_fim);
		return $query->count();
	}

	private static function initSearchDoPedidoID($pedido_id) {
		return   DB::$pdo->from('Produtos_Pedidos')
		                 ->where('cancelado', 'N')
		                 ->where('pedidoid', $pedido_id)
		                 ->orderBy('id ASC');
	}

	public static function getTodosDoPedidoID($pedido_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoPedidoID($pedido_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_produto_pedidos = $query->fetchAll();
		$produto_pedidos = array();
		foreach($_produto_pedidos as $produto_pedido)
			$produto_pedidos[] = new ZProdutoPedido($produto_pedido);
		return $produto_pedidos;
	}

	public static function getCountDoPedidoID($pedido_id) {
		$query = self::initSearchDoPedidoID($pedido_id);
		return $query->count();
	}

	private static function initSearchDoProdutoID($produto_id) {
		return   DB::$pdo->from('Produtos_Pedidos')
		                 ->where(array('produtoid' => $produto_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDoProdutoID($produto_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoProdutoID($produto_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_produto_pedidos = $query->fetchAll();
		$produto_pedidos = array();
		foreach($_produto_pedidos as $produto_pedido)
			$produto_pedidos[] = new ZProdutoPedido($produto_pedido);
		return $produto_pedidos;
	}

	public static function getCountDoProdutoID($produto_id) {
		$query = self::initSearchDoProdutoID($produto_id);
		return $query->count();
	}

	private static function initSearchDoFuncionarioID($funcionario_id) {
		return   DB::$pdo->from('Produtos_Pedidos')
		                 ->where(array('funcionarioid' => $funcionario_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDoFuncionarioID($funcionario_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoFuncionarioID($funcionario_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_produto_pedidos = $query->fetchAll();
		$produto_pedidos = array();
		foreach($_produto_pedidos as $produto_pedido)
			$produto_pedidos[] = new ZProdutoPedido($produto_pedido);
		return $produto_pedidos;
	}

	public static function getCountDoFuncionarioID($funcionario_id) {
		$query = self::initSearchDoFuncionarioID($funcionario_id);
		return $query->count();
	}

	private static function initSearchPorCategoria($sessao_id) {
		$query = DB::$pdo->from('Produtos_Pedidos pp')
						 ->select(null)
						 ->select('SUM(pp.preco * pp.quantidade) as total')
						 ->select('COALESCE(c.descricao, "Taxas e Serviços") as descricao')
						 ->leftJoin('Pedidos p ON p.id = pp.pedidoid')
						 ->leftJoin('Produtos pd ON pd.id = pp.produtoid')
						 ->leftJoin('Categorias c ON c.id = pd.categoriaid')
						 ->where(array('p.cancelado' => 'N', 'pp.cancelado' => 'N'))
						 ->groupBy('pd.categoriaid')
		                 ->orderBy('total DESC');
		if(!is_null($sessao_id))
			$query = $query->where(array('p.sessaoid' => $sessao_id));
		if(!is_null($data_inicio) && is_null($sessao_id))
			$query = $query->where('pp.datahora >= ?', date('Y-m-d', $data_inicio));
		if(!is_null($data_fim) && is_null($sessao_id))
			$query = $query->where('pp.datahora <= ?', date('Y-m-d 23:59:59', $data_fim));
		return $query;
	}

	public static function getTodosPorCategoria($sessao_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchPorCategoria($sessao_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		return $query->fetchAll();
	}

	public static function getCountPorCategoria($sessao_id) {
		$query = self::initSearchPorCategoria($sessao_id);
		$query = $query->select(null)->groupBy(null)->select('COUNT(DISTINCT c.id)');
		return (int) $query->fetchColumn();
	}

	private static function initSearchDoLocal($tipo, $mesa_id, $comanda_id) {
		$query = DB::$pdo->from('Produtos_Pedidos pp')
						 ->select(null)
						 ->select(
						 	'IF(COUNT(pp.id) = 1, pp.id, 0) as id, '.
						 	'pp.pedidoid, '.
							'pp.funcionarioid, '.
							'pp.produtoid, '.
							'pp.servicoid, '.
						 	'pp.produtopedidoid, '.
							'pp.descricao, '.
							'pp.preco, '.
							'pp.porcentagem, '.
							'pp.precovenda, '.
							'pp.precocompra, '.
							'pp.detalhes, '.
							'pp.estado, '.
							'pp.visualizado, '.
							'pp.datavisualizacao, '.
							'pp.dataatualizacao, '.
							'pp.cancelado, '.
							'pp.datahora')
						 ->select('SUM(pp.quantidade) as quantidade')
						 ->select('COALESCE(pp.descricao, pd.descricao) as produtodescricao')
						 ->select('pd.dataatualizacao as produtodataatualizacao')
						 ->select('p.tipo')
						 ->select('pd.tipo as produtotipo')
						 ->select('pd.conteudo as produtoconteudo')
						 ->select('u.sigla as unidadesigla')
						 ->select('p.mesaid')
						 ->select('p.comandaid')
						 ->select('IF(IsNull(pd.imagem), NULL, CONCAT(pd.id, ".png")) as imagemurl')
						 ->leftJoin('Pedidos p ON p.id = pp.pedidoid')
						 ->leftJoin('Produtos pd ON pd.id = pp.produtoid')
						 ->leftJoin('Unidades u ON u.id = pd.unidadeid')
		                 ->where(array(
			                 	'NOT pp.produtoid' => null,
			                 	'p.cancelado' => 'N',
			                 	'pp.cancelado' => 'N',
		                 	))
		                 ->where('p.estado <> ?', PedidoEstado::FINALIZADO)
		                 ->orderBy('pp.id ASC')
		                 ->groupBy('pp.produtoid, pp.preco, pp.detalhes');
		if($tipo == PedidoTipo::COMANDA) {
			$query = $query->where(array(
								'p.comandaid' => $comanda_id, 
								'p.tipo' => PedidoTipo::COMANDA, 
							));
		} else {
			$query = $query->where(array(
								'p.mesaid' => $mesa_id, 
								'p.tipo' => PedidoTipo::MESA, 
							));
		}
		return $query;
	}

	public static function getTodosDoLocal($tipo, $mesa_id, $comanda_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoLocal($tipo, $mesa_id, $comanda_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		return $query->fetchAll();
	}

	public static function getCountDoLocal($tipo, $mesa_id, $comanda_id) {
		$query = self::initSearchDoLocal($tipo, $mesa_id, $comanda_id);
		return $query->count();
	}

}
