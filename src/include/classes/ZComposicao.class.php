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

class ComposicaoTipo {
	const COMPOSICAO = 'Composicao';
	const OPCIONAL = 'Opcional';
	const ADICIONAL = 'Adicional';
}

/**
 * Informa as propriedades da composição de um produto composto
 */
class ZComposicao {
	private $id;
	private $composicao_id;
	private $produto_id;
	private $tipo;
	private $quantidade;
	private $valor;
	private $ativa;

	public function __construct($composicao = array()) {
		if(is_array($composicao)) {
			$this->setID($composicao['id']);
			$this->setComposicaoID($composicao['composicaoid']);
			$this->setProdutoID($composicao['produtoid']);
			$this->setTipo($composicao['tipo']);
			$this->setQuantidade($composicao['quantidade']);
			$this->setValor($composicao['valor']);
			$this->setAtiva($composicao['ativa']);
		}
	}

	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Informe a qual pertence essa composição, deve sempre ser um produto do tipo Composição
	 */
	public function getComposicaoID() {
		return $this->composicao_id;
	}

	/**
	 * Informe a qual pertence essa composição, deve sempre ser um produto do tipo Composição
	 */
	public function setComposicaoID($composicao_id) {
		$this->composicao_id = $composicao_id;
	}

	/**
	 * Produto ou composição que faz parte dessa composição, Obs: Não pode ser um pacote
	 */
	public function getProdutoID() {
		return $this->produto_id;
	}

	/**
	 * Produto ou composição que faz parte dessa composição, Obs: Não pode ser um pacote
	 */
	public function setProdutoID($produto_id) {
		$this->produto_id = $produto_id;
	}

	/**
	 * Tipo de composição, 'Composicao' sempre retira do estoque, 'Opcional' permite desmarcar na venda, 'Adicional' permite adicionar na venda
	 */
	public function getTipo() {
		return $this->tipo;
	}

	/**
	 * Tipo de composição, 'Composicao' sempre retira do estoque, 'Opcional' permite desmarcar na venda, 'Adicional' permite adicionar na venda
	 */
	public function setTipo($tipo) {
		$this->tipo = $tipo;
	}

	/**
	 * Quantidade que será consumida desse produto para cada composição formada
	 */
	public function getQuantidade() {
		return $this->quantidade;
	}

	/**
	 * Quantidade que será consumida desse produto para cada composição formada
	 */
	public function setQuantidade($quantidade) {
		$this->quantidade = $quantidade;
	}

	/**
	 * Desconto que será realizado ao retirar esse produto da composição no  momento da venda
	 */
	public function getValor() {
		return $this->valor;
	}

	/**
	 * Desconto que será realizado ao retirar esse produto da composição no  momento da venda
	 */
	public function setValor($valor) {
		$this->valor = $valor;
	}

	/**
	 * Indica se a composição está sendo usada atualmente na composição do produto
	 */
	public function getAtiva() {
		return $this->ativa;
	}

	/**
	 * Indica se a composição está sendo usada atualmente na composição do produto
	 */
	public function isAtiva() {
		return $this->ativa == 'Y';
	}

	/**
	 * Indica se a composição está sendo usada atualmente na composição do produto
	 */
	public function setAtiva($ativa) {
		$this->ativa = $ativa;
	}

	public function toArray() {
		$composicao = array();
		$composicao['id'] = $this->getID();
		$composicao['composicaoid'] = $this->getComposicaoID();
		$composicao['produtoid'] = $this->getProdutoID();
		$composicao['tipo'] = $this->getTipo();
		$composicao['quantidade'] = $this->getQuantidade();
		$composicao['valor'] = $this->getValor();
		$composicao['ativa'] = $this->getAtiva();
		return $composicao;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Composicoes')
		                 ->where(array('id' => $id));
		return new ZComposicao($query->fetch());
	}

	public static function getPelaComposicaoIDProdutoID($composicao_id, $produto_id) {
		$query = DB::$pdo->from('Composicoes')
		                 ->where(array('composicaoid' => $composicao_id, 'produtoid' => $produto_id));
		return new ZComposicao($query->fetch());
	}

	private static function validarCampos(&$composicao) {
		$erros = array();
		if(!is_numeric($composicao['composicaoid']))
			$erros['composicaoid'] = 'A composição não foi informada';
		if(!is_numeric($composicao['produtoid']))
			$erros['produtoid'] = 'O produto não foi informado';
		$composicao['tipo'] = trim($composicao['tipo']);
		if(strlen($composicao['tipo']) == 0)
			$composicao['tipo'] = null;
		else if(!in_array($composicao['tipo'], array('Composicao', 'Opcional', 'Adicional')))
			$erros['tipo'] = 'O tipo informado não é válido';
		if(!is_numeric($composicao['quantidade']))
			$erros['quantidade'] = 'A quantidade não foi informada';
		if(!is_numeric($composicao['valor']))
			$erros['valor'] = 'O valor não foi informado';
		else
			$composicao['valor'] = floatval($composicao['valor']);
		$composicao['ativa'] = trim($composicao['ativa']);
		if(strlen($composicao['ativa']) == 0)
			$composicao['ativa'] = 'N';
		else if(!in_array($composicao['ativa'], array('Y', 'N')))
			$erros['ativa'] = 'A informação se a composição está ativa não é válida';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
		if(stripos($e->getMessage(), 'UK_Composicoes_CompID_ProdID') !== false)
			throw new ValidationException(array('produtoid' => 'O produto informado já está cadastrado'));
	}

	public static function cadastrar($composicao) {
		$_composicao = $composicao->toArray();
		self::validarCampos($_composicao);
		try {
			$_composicao['id'] = DB::$pdo->insertInto('Composicoes')->values($_composicao)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_composicao['id']);
	}

	public static function atualizar($composicao) {
		$_composicao = $composicao->toArray();
		if(!$_composicao['id'])
			throw new ValidationException(array('id' => 'O id da composicao não foi informado'));
		self::validarCampos($_composicao);
		$campos = array(
			'composicaoid',
			'produtoid',
			'tipo',
			'quantidade',
			'valor',
			'ativa',
		);
		try {
			$query = DB::$pdo->update('Composicoes');
			$query = $query->set(array_intersect_key($_composicao, array_flip($campos)));
			$query = $query->where('id', $_composicao['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_composicao['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir a composicao, o id da composicao não foi informado');
		$query = DB::$pdo->deleteFrom('Composicoes')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch() {
		return   DB::$pdo->from('Composicoes')
		                 ->orderBy('id ASC');
	}

	public static function getTodas($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_composicaos = $query->fetchAll();
		$composicaos = array();
		foreach($_composicaos as $composicao)
			$composicaos[] = new ZComposicao($composicao);
		return $composicaos;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

	private static function initSearchDaComposicaoID($composicao_id, $somente_selecionaveis, $incluir_adicionais) {
		$query = DB::$pdo->from('Composicoes c')
		                 ->where(array('c.ativa' => 'Y', 'c.composicaoid' => intval($composicao_id)));
		if($somente_selecionaveis)
			$query = $query->where('c.tipo <> ?', ComposicaoTipo::COMPOSICAO);
		if($somente_selecionaveis && !$incluir_adicionais)
			$query = $query->where('c.tipo <> ?', ComposicaoTipo::ADICIONAL);
		return $query;
	}

	public static function getTodasDaComposicaoID($composicao_id, $somente_selecionaveis = false, 
			$incluir_adicionais = false, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaComposicaoID($composicao_id, $somente_selecionaveis, $incluir_adicionais);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_composicaos = $query->fetchAll();
		$composicaos = array();
		foreach($_composicaos as $composicao)
			$composicaos[] = new ZComposicao($composicao);
		return $composicaos;
	}

	public static function getTodasDaComposicaoIDEx($composicao_id, $somente_selecionaveis = false, 
			$incluir_adicionais = false, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaComposicaoID($composicao_id, $somente_selecionaveis, $incluir_adicionais);
		$query = $query->select('p.descricao as produtodescricao')
					   ->select('p.abreviacao as produtoabreviacao')
					   ->select('p.conteudo as produtoconteudo')
					   ->select('u.sigla as unidadesigla')
					   ->select('IF(ISNULL(p.imagem), NULL, CONCAT(p.id, ".png")) as imagemurl')
					   ->select('p.dataatualizacao as produtodataatualizacao')
					   ->select('IF(c.tipo = ?, "N", "Y") as selecionavel', ComposicaoTipo::COMPOSICAO)
					   ->leftJoin('Produtos p ON p.id = c.produtoid')
					   ->leftJoin('Unidades u ON u.id = p.unidadeid');
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		return $query->fetchAll();
	}

	public static function getCountDaComposicaoID($composicao_id, $somente_selecionaveis = false, $incluir_adicionais = false) {
		$query = self::initSearchDaComposicaoID($composicao_id, $somente_selecionaveis, $incluir_adicionais);
		return $query->count();
	}

	private static function initSearchDoProdutoID($produto_id) {
		return   DB::$pdo->from('Composicoes')
		                 ->where(array('produtoid' => $produto_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDoProdutoID($produto_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoProdutoID($produto_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_composicaos = $query->fetchAll();
		$composicaos = array();
		foreach($_composicaos as $composicao)
			$composicaos[] = new ZComposicao($composicao);
		return $composicaos;
	}

	public static function getCountDoProdutoID($produto_id) {
		$query = self::initSearchDoProdutoID($produto_id);
		return $query->count();
	}

}
