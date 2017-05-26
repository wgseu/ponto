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
class ProdutoTipo {
	const PRODUTO = 'Produto';
	const COMPOSICAO = 'Composicao';
	const PACOTE = 'Pacote';
}

/**
 * Informações sobre o produto, composição ou pacote
 */
class ZProduto {
	private $id;
	private $codigo_barras;
	private $categoria_id;
	private $unidade_id;
	private $setor_estoque_id;
	private $setor_preparo_id;
	private $tributacao_id;
	private $descricao;
	private $abreviacao;
	private $detalhes;
	private $quantidade_limite;
	private $quantidade_maxima;
	private $conteudo;
	private $preco_venda;
	private $custo_producao;
	private $tipo;
	private $cobrar_servico;
	private $divisivel;
	private $pesavel;
	private $perecivel;
	private $tempo_preparo;
	private $visivel;
	private $imagem;
	private $data_atualizacao;

	public function __construct($produto = array()) {
		$this->fromArray($produto);
	}

	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Código de barras do produto, deve ser único entre todos os produtos
	 */
	public function getCodigoBarras() {
		return $this->codigo_barras;
	}

	/**
	 * Código de barras do produto, deve ser único entre todos os produtos
	 */
	public function setCodigoBarras($codigo_barras) {
		$this->codigo_barras = $codigo_barras;
	}

	/**
	 * Categoria do produto, permite a rápida localização ao utilizar tablets
	 */
	public function getCategoriaID() {
		return $this->categoria_id;
	}

	/**
	 * Categoria do produto, permite a rápida localização ao utilizar tablets
	 */
	public function setCategoriaID($categoria_id) {
		$this->categoria_id = $categoria_id;
	}

	/**
	 * Informa a unidade do produtos, Ex.: Grama, Litro.
	 */
	public function getUnidadeID() {
		return $this->unidade_id;
	}

	/**
	 * Informa a unidade do produtos, Ex.: Grama, Litro.
	 */
	public function setUnidadeID($unidade_id) {
		$this->unidade_id = $unidade_id;
	}

	/**
	 * Informa de qual setor o produto será retirado após a venda
	 */
	public function getSetorEstoqueID() {
		return $this->setor_estoque_id;
	}

	/**
	 * Informa de qual setor o produto será retirado após a venda
	 */
	public function setSetorEstoqueID($setor_estoque_id) {
		$this->setor_estoque_id = $setor_estoque_id;
	}

	/**
	 * Informa em qual setor de preparo será enviado o ticket de preparo ou autorização, se nenhum for informado nada será impresso
	 */
	public function getSetorPreparoID() {
		return $this->setor_preparo_id;
	}

	/**
	 * Informa em qual setor de preparo será enviado o ticket de preparo ou autorização, se nenhum for informado nada será impresso
	 */
	public function setSetorPreparoID($setor_preparo_id) {
		$this->setor_preparo_id = $setor_preparo_id;
	}

	/**
	 * Informações de tributação do produto
	 */
	public function getTributacaoID() {
		return $this->tributacao_id;
	}

	public function setTributacaoID($tributacao_id) {
		$this->tributacao_id = $tributacao_id;
	}

	/**
	 * Descrição do produto, Ex.: Refri. Coca Cola 2L.
	 */
	public function getDescricao() {
		return $this->descricao;
	}

	/**
	 * Descrição do produto, Ex.: Refri. Coca Cola 2L.
	 */
	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}

	/**
	 * Nome abreviado do produto, Ex.: Cebola, Tomate, Queijo
	 */
	public function getAbreviacao() {
		return $this->abreviacao;
	}

	/**
	 * Nome abreviado do produto, Ex.: Cebola, Tomate, Queijo
	 */
	public function setAbreviacao($abreviacao) {
		$this->abreviacao = $abreviacao;
	}

	/**
	 * Informa detalhes do produto, Ex: Com Cebola, Pimenta, Orégano
	 */
	public function getDetalhes() {
		return $this->detalhes;
	}

	/**
	 * Informa detalhes do produto, Ex: Com Cebola, Pimenta, Orégano
	 */
	public function setDetalhes($detalhes) {
		$this->detalhes = $detalhes;
	}

	/**
	 * Informa a quantidade limite para que o sistema avise que o produto já está acabando
	 */
	public function getQuantidadeLimite() {
		return $this->quantidade_limite;
	}

	/**
	 * Informa a quantidade limite para que o sistema avise que o produto já está acabando
	 */
	public function setQuantidadeLimite($quantidade_limite) {
		$this->quantidade_limite = $quantidade_limite;
	}

	/**
	 * Informa a quantidade máxima do produto no estoque, não proibe, apenas avisa
	 */
	public function getQuantidadeMaxima() {
		return $this->quantidade_maxima;
	}

	/**
	 * Informa a quantidade máxima do produto no estoque, não proibe, apenas avisa
	 */
	public function setQuantidadeMaxima($quantidade_maxima) {
		$this->quantidade_maxima = $quantidade_maxima;
	}

	/**
	 * Informa o conteúdo do produto, Ex.: 2000 para 2L de conteúdo, 200 para 200g de peso ou 1 para 1 unidade
	 */
	public function getConteudo() {
		return $this->conteudo;
	}

	/**
	 * Informa o conteúdo do produto, Ex.: 2000 para 2L de conteúdo, 200 para 200g de peso ou 1 para 1 unidade
	 */
	public function setConteudo($conteudo) {
		$this->conteudo = $conteudo;
	}

	/**
	 * Preço de venda ou preço de venda base para pacotes
	 */
	public function getPrecoVenda() {
		return $this->preco_venda;
	}

	/**
	 * Preço de venda ou preço de venda base para pacotes
	 */
	public function setPrecoVenda($preco_venda) {
		$this->preco_venda = $preco_venda;
	}

	/**
	 * Informa qual o valor para o custo de produção do produto, utilizado quando não há formação de composição do produto
	 */
	public function getCustoProducao() {
		return $this->custo_producao;
	}

	/**
	 * Informa qual o valor para o custo de produção do produto, utilizado quando não há formação de composição do produto
	 */
	public function setCustoProducao($custo_producao) {
		$this->custo_producao = $custo_producao;
	}

	/**
	 * Informa qual é o tipo de produto. Produto: Produto normal que possui estoque, Composição: Produto que não possui estoque diretamente, pois é composto de outros produtos ou composições, Pacote: Permite a composição no momento da venda, não possui estoque diretamente
	 */
	public function getTipo() {
		return $this->tipo;
	}

	/**
	 * Informa qual é o tipo de produto. Produto: Produto normal que possui estoque, Composição: Produto que não possui estoque diretamente, pois é composto de outros produtos ou composições, Pacote: Permite a composição no momento da venda, não possui estoque diretamente
	 */
	public function setTipo($tipo) {
		$this->tipo = $tipo;
	}

	/**
	 * Informa se deve ser cobrado a taxa de serviço dos garçons sobre este produto
	 */
	public function getCobrarServico() {
		return $this->cobrar_servico;
	}

	/**
	 * Informa se deve ser cobrado a taxa de serviço dos garçons sobre este produto
	 */
	public function isCobrarServico() {
		return $this->cobrar_servico == 'Y';
	}

	/**
	 * Informa se deve ser cobrado a taxa de serviço dos garçons sobre este produto
	 */
	public function setCobrarServico($cobrar_servico) {
		$this->cobrar_servico = $cobrar_servico;
	}

	/**
	 * Informa se o produto pode ser vendido fracionado
	 */
	public function getDivisivel() {
		return $this->divisivel;
	}

	/**
	 * Informa se o produto pode ser vendido fracionado
	 */
	public function isDivisivel() {
		return $this->divisivel == 'Y';
	}

	/**
	 * Informa se o produto pode ser vendido fracionado
	 */
	public function setDivisivel($divisivel) {
		$this->divisivel = $divisivel;
	}

	/**
	 * Informa se o peso do produto deve ser obtido de uma balança, obrigatoriamente o produto deve ser divisível
	 */
	public function getPesavel() {
		return $this->pesavel;
	}

	/**
	 * Informa se o peso do produto deve ser obtido de uma balança, obrigatoriamente o produto deve ser divisível
	 */
	public function isPesavel() {
		return $this->pesavel == 'Y';
	}

	/**
	 * Informa se o peso do produto deve ser obtido de uma balança, obrigatoriamente o produto deve ser divisível
	 */
	public function setPesavel($pesavel) {
		$this->pesavel = $pesavel;
	}

	/**
	 * Informa se o produto vence em pouco tempo
	 */
	public function getPerecivel() {
		return $this->perecivel;
	}

	/**
	 * Informa se o produto vence em pouco tempo
	 */
	public function isPerecivel() {
		return $this->perecivel == 'Y';
	}

	/**
	 * Informa se o produto vence em pouco tempo
	 */
	public function setPerecivel($perecivel) {
		$this->perecivel = $perecivel;
	}

	/**
	 * Tempo de preparo em minutos para preparar uma composição, 0 para não informado
	 */
	public function getTempoPreparo() {
		return $this->tempo_preparo;
	}

	/**
	 * Tempo de preparo em minutos para preparar uma composição, 0 para não informado
	 */
	public function setTempoPreparo($tempo_preparo) {
		$this->tempo_preparo = $tempo_preparo;
	}

	/**
	 * Informa se o produto estará disponível para venda
	 */
	public function getVisivel() {
		return $this->visivel;
	}

	/**
	 * Informa se o produto estará disponível para venda
	 */
	public function isVisivel() {
		return $this->visivel == 'Y';
	}

	/**
	 * Informa se o produto estará disponível para venda
	 */
	public function setVisivel($visivel) {
		$this->visivel = $visivel;
	}

	/**
	 * Imagem do produto
	 */
	public function getImagem() {
		return $this->imagem;
	}

	/**
	 * Imagem do produto
	 */
	public function setImagem($imagem) {
		$this->imagem = $imagem;
	}

	/**
	 * Data de atualização das informações do produto
	 */
	public function getDataAtualizacao() {
		return $this->data_atualizacao;
	}

	/**
	 * Data de atualização das informações do produto
	 */
	public function setDataAtualizacao($data_atualizacao) {
		$this->data_atualizacao = $data_atualizacao;
	}

	/* Obtém a descrição do produto abreviada */
	public function getAbreviado() {
		if(trim($this->abreviacao) == '')
			return $this->descricao;
		return $this->abreviacao;
	}

	public function toArray() {
		$produto = array();
		$produto['id'] = $this->getID();
		$produto['codigobarras'] = $this->getCodigoBarras();
		$produto['categoriaid'] = $this->getCategoriaID();
		$produto['unidadeid'] = $this->getUnidadeID();
		$produto['setorestoqueid'] = $this->getSetorEstoqueID();
		$produto['setorpreparoid'] = $this->getSetorPreparoID();
		$produto['tributacaoid'] = $this->getTributacaoID();
		$produto['descricao'] = $this->getDescricao();
		$produto['abreviacao'] = $this->getAbreviacao();
		$produto['detalhes'] = $this->getDetalhes();
		$produto['quantidadelimite'] = $this->getQuantidadeLimite();
		$produto['quantidademaxima'] = $this->getQuantidadeMaxima();
		$produto['conteudo'] = $this->getConteudo();
		$produto['precovenda'] = $this->getPrecoVenda();
		$produto['custoproducao'] = $this->getCustoProducao();
		$produto['tipo'] = $this->getTipo();
		$produto['cobrarservico'] = $this->getCobrarServico();
		$produto['divisivel'] = $this->getDivisivel();
		$produto['pesavel'] = $this->getPesavel();
		$produto['perecivel'] = $this->getPerecivel();
		$produto['tempopreparo'] = $this->getTempoPreparo();
		$produto['visivel'] = $this->getVisivel();
		$produto['imagem'] = $this->getImagem();
		$produto['dataatualizacao'] = $this->getDataAtualizacao();
		return $produto;
	}

	public function fromArray($produto = array()) {
		if(!is_array($produto))
			return $this;
		$this->setID(isset($produto['id'])?$produto['id']:null);
		$this->setCodigoBarras(isset($produto['codigobarras'])?$produto['codigobarras']:null);
		$this->setCategoriaID(isset($produto['categoriaid'])?$produto['categoriaid']:null);
		$this->setUnidadeID(isset($produto['unidadeid'])?$produto['unidadeid']:null);
		$this->setSetorEstoqueID(isset($produto['setorestoqueid'])?$produto['setorestoqueid']:null);
		$this->setSetorPreparoID(isset($produto['setorpreparoid'])?$produto['setorpreparoid']:null);
		$this->setTributacaoID(isset($produto['tributacaoid'])?$produto['tributacaoid']:null);
		$this->setDescricao(isset($produto['descricao'])?$produto['descricao']:null);
		$this->setAbreviacao(isset($produto['abreviacao'])?$produto['abreviacao']:null);
		$this->setDetalhes(isset($produto['detalhes'])?$produto['detalhes']:null);
		$this->setQuantidadeLimite(isset($produto['quantidadelimite'])?$produto['quantidadelimite']:null);
		$this->setQuantidadeMaxima(isset($produto['quantidademaxima'])?$produto['quantidademaxima']:null);
		$this->setConteudo(isset($produto['conteudo'])?$produto['conteudo']:null);
		$this->setPrecoVenda(isset($produto['precovenda'])?$produto['precovenda']:null);
		$this->setCustoProducao(isset($produto['custoproducao'])?$produto['custoproducao']:null);
		$this->setTipo(isset($produto['tipo'])?$produto['tipo']:null);
		$this->setCobrarServico(isset($produto['cobrarservico'])?$produto['cobrarservico']:null);
		$this->setDivisivel(isset($produto['divisivel'])?$produto['divisivel']:null);
		$this->setPesavel(isset($produto['pesavel'])?$produto['pesavel']:null);
		$this->setPerecivel(isset($produto['perecivel'])?$produto['perecivel']:null);
		$this->setTempoPreparo(isset($produto['tempopreparo'])?$produto['tempopreparo']:null);
		$this->setVisivel(isset($produto['visivel'])?$produto['visivel']:null);
		$this->setImagem(isset($produto['imagem'])?$produto['imagem']:null);
		$this->setDataAtualizacao(isset($produto['dataatualizacao'])?$produto['dataatualizacao']:null);
	}

	private static function initGet() {
		return DB::$pdo->from('Produtos p')
						 ->select(null)
						 ->select('p.id')
						 ->select('p.codigobarras')
						 ->select('p.categoriaid')
						 ->select('p.unidadeid')
						 ->select('p.setorestoqueid')
						 ->select('p.setorpreparoid')
						 ->select('p.tributacaoid')
						 ->select('p.descricao')
						 ->select('p.abreviacao')
						 ->select('p.detalhes')
						 ->select('p.quantidadelimite')
						 ->select('p.quantidademaxima')
						 ->select('p.conteudo')
						 ->select('(p.precovenda + COALESCE(prm.valor, 0)) as precovenda')
						 ->select('p.custoproducao')
						 ->select('p.tipo')
						 ->select('p.cobrarservico')
						 ->select('p.divisivel')
						 ->select('p.pesavel')
						 ->select('p.perecivel')
						 ->select('p.tempopreparo')
						 ->select('IF(COALESCE(prm.proibir, "N") = "N", p.visivel, "N") as visivel')
						 ->select('IF(ISNULL(p.imagem), NULL, CONCAT(p.id, ".png")) as imagem')
						 ->select('p.dataatualizacao')
						 // extra
						 ->select('COALESCE(sum(e.quantidade), 0) as estoque')
						 ->leftJoin('Estoque e ON e.produtoid = p.id AND e.cancelado = "N" AND (ISNULL(p.setorestoqueid) OR e.setorid = p.setorestoqueid)')
						 ->leftJoin('Promocoes prm ON prm.produtoid = p.id AND NOW() BETWEEN DATE_ADD(CURDATE(), INTERVAL prm.inicio - DAYOFWEEK(CURDATE()) * 1440 MINUTE) AND DATE_ADD(CURDATE(), INTERVAL prm.fim - DAYOFWEEK(CURDATE()) * 1440 MINUTE)');
	}

	public static function getPeloID($id) {
		$query = self::initGet()->where(array('p.id' => $id));
		return new ZProduto($query->fetch());
	}

	public static function getPelaDescricao($descricao) {
		$query = self::initGet()->where(array('p.descricao' => $descricao));
		return new ZProduto($query->fetch());
	}

	public static function getPeloCodigoBarras($codigo_barras) {
		$query = self::initGet()->where(array('p.codigobarras' => $codigo_barras));
		return new ZProduto($query->fetch());
	}

	public static function getImagemPeloID($produto_id, $dataSomente = false) {
		$query = DB::$pdo->from('Produtos p')
						 ->select(null)
						 ->select('p.dataatualizacao')
						 ->where(array('p.id' => $produto_id));
		if(!$dataSomente)
			$query = $query->select('p.imagem');
		return $query->fetch();
	}

	private static function validarCampos(&$produto) {
		$erros = array();
		$produto['codigobarras'] = strip_tags(trim($produto['codigobarras']));
		if(strlen($produto['codigobarras']) == 0)
			$produto['codigobarras'] = null;
		else if(!is_number($produto['codigobarras']))
			$erros['codigobarras'] = 'O código de barras deve conter apenas números';
		if(!is_numeric($produto['categoriaid']))
			$erros['categoriaid'] = 'A categoria não foi informada';
		if(!is_numeric($produto['unidadeid']))
			$erros['unidadeid'] = 'A unidade não foi informada';
		$produto['setorestoqueid'] = trim($produto['setorestoqueid']);
		if(strlen($produto['setorestoqueid']) == 0)
			$produto['setorestoqueid'] = null;
		else if(!is_numeric($produto['setorestoqueid']))
			$erros['setorestoqueid'] = 'O setor de estoque não foi informado';
		$produto['setorpreparoid'] = trim($produto['setorpreparoid']);
		if(strlen($produto['setorpreparoid']) == 0)
			$produto['setorpreparoid'] = null;
		else if(!is_numeric($produto['setorpreparoid']))
			$erros['setorpreparoid'] = 'O setor de preparo não foi informado';
		$produto['tributacaoid'] = trim($produto['tributacaoid']);
		if(strlen($produto['tributacaoid']) == 0)
			$produto['tributacaoid'] = null;
		else if(!is_numeric($produto['tributacaoid']))
			$erros['tributacaoid'] = 'A tributação não foi informada';
		$produto['descricao'] = strip_tags(trim($produto['descricao']));
		if(strlen($produto['descricao']) == 0)
			$erros['descricao'] = 'A descrição não pode ser vazia';
		$produto['abreviacao'] = strip_tags(trim($produto['abreviacao']));
		if(strlen($produto['abreviacao']) == 0)
			$produto['abreviacao'] = null;
		$produto['detalhes'] = strip_tags(trim($produto['detalhes']));
		if(strlen($produto['detalhes']) == 0)
			$produto['detalhes'] = null;
		if(!is_numeric($produto['quantidadelimite']))
			$erros['quantidadelimite'] = 'A quantidade limite não foi informada';
		else if($produto['quantidadelimite'] < 0)
			$erros['quantidadelimite'] = 'A quantidade limite não pode ser negativa';
		if(!is_numeric($produto['quantidademaxima']))
			$erros['quantidademaxima'] = 'A quantidade máxima não foi informada';
		else {
			$produto['quantidademaxima'] = floatval($produto['quantidademaxima']);
			if($produto['quantidademaxima'] < 0)
				$erros['quantidademaxima'] = 'A quantidade máxima não pode ser negativa';
		}
		if(!is_numeric($produto['conteudo']))
			$erros['conteudo'] = 'O conteúdo não foi informado';
		else {
			$produto['conteudo'] = floatval($produto['conteudo']);
			$unidade = ZUnidade::getPeloID($produto['unidadeid']);
			if(is_equal($produto['conteudo'], 0, 0.0001))
				$erros['conteudo'] = 'O conteúdo não pode ser nulo';
			else if($produto['conteudo'] != 1 && strtoupper($unidade->getSigla()) == 'UN')
				$erros['conteudo'] = 'O conteúdo deve ser unitário com valor 1';
		}
		if(!is_numeric($produto['precovenda']))
			$erros['precovenda'] = 'O preço de venda não foi informado';
		else if($produto['precovenda'] < 0)
			$erros['precovenda'] = 'O preço de venda não pode ser negativo';
		$produto['custoproducao'] = trim($produto['custoproducao']);
		if(strlen($produto['custoproducao']) == 0)
			$produto['custoproducao'] = null;
		else if(!is_numeric($produto['custoproducao']))
			$erros['custoproducao'] = 'O custo de produção não foi informado';
		else if($produto['custoproducao'] < 0)
			$erros['custoproducao'] = 'O custo de produção não pode ser negativo';
		$produto['tipo'] = trim($produto['tipo']);
		if(strlen($produto['tipo']) == 0)
			$produto['tipo'] = null;
		else if(!in_array($produto['tipo'], array('Produto', 'Composicao', 'Pacote')))
			$erros['tipo'] = 'O tipo informado não é válido';
		if($produto['tipo'] == ProdutoTipo::PACOTE && ZPacote::existe($produto['id']))
			$erros['tipo'] = 'O produto não pode ser um pacote pois já faz parte de um';
		$produto['cobrarservico'] = trim($produto['cobrarservico']);
		if(strlen($produto['cobrarservico']) == 0)
			$produto['cobrarservico'] = 'N';
		else if(!in_array($produto['cobrarservico'], array('Y', 'N')))
			$erros['cobrarservico'] = 'A cobrança de serviço informada não é válida';
		$produto['divisivel'] = trim($produto['divisivel']);
		if(strlen($produto['divisivel']) == 0)
			$produto['divisivel'] = 'N';
		else if(!in_array($produto['divisivel'], array('Y', 'N')))
			$erros['divisivel'] = 'O divisível informado não é válido';
		$produto['pesavel'] = trim($produto['pesavel']);
		if(strlen($produto['pesavel']) == 0)
			$produto['pesavel'] = 'N';
		else if(!in_array($produto['pesavel'], array('Y', 'N')))
			$erros['pesavel'] = 'O pesável informado não é válido';
		$produto['perecivel'] = trim($produto['perecivel']);
		if(strlen($produto['perecivel']) == 0)
			$produto['perecivel'] = 'N';
		else if(!in_array($produto['perecivel'], array('Y', 'N')))
			$erros['perecivel'] = 'O perecível informado não é válido';
		if($produto['tipo'] != ProdutoTipo::COMPOSICAO)
			$produto['tempopreparo'] = 0;
		if(!is_numeric($produto['tempopreparo']))
			$erros['tempopreparo'] = 'O tempo de preparo não foi informado';
		else {
			$produto['tempopreparo'] = intval($produto['tempopreparo']);
			if($produto['tempopreparo'] < 0)
				$erros['tempopreparo'] = 'O tempo de preparo não pode ser negativo';
		}
		$produto['visivel'] = trim($produto['visivel']);
		if(strlen($produto['visivel']) == 0)
			$produto['visivel'] = 'N';
		else if(!in_array($produto['visivel'], array('Y', 'N')))
			$erros['visivel'] = 'O visível informado não é válido';
		if($produto['imagem'] === '')
			$produto['imagem'] = null;
		$produto['dataatualizacao'] = date('Y-m-d H:i:s');
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
		if(stripos($e->getMessage(), 'Descricao_UNIQUE') !== false)
			throw new ValidationException(array('descricao' => 'A descrição informada já está cadastrada'));
		if(stripos($e->getMessage(), 'CodBarras_UNIQUE') !== false)
			throw new ValidationException(array('codigobarras' => 'O código de barras informado já está cadastrado'));
	}

	public static function cadastrar($produto) {
		$_produto = $produto->toArray();
		self::validarCampos($_produto);
		try {
			$_produto['id'] = DB::$pdo->insertInto('Produtos')->values($_produto)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_produto['id']);
	}

	public static function atualizar($produto) {
		$_produto = $produto->toArray();
		if(!$_produto['id'])
			throw new ValidationException(array('id' => 'O id do produto não foi informado'));
		self::validarCampos($_produto);
		$campos = array(
			'codigobarras',
			'categoriaid',
			'unidadeid',
			'setorestoqueid',
			'setorpreparoid',
			'tributacaoid',
			'descricao',
			'abreviacao',
			'detalhes',
			'quantidadelimite',
			'quantidademaxima',
			'conteudo',
			'precovenda',
			'custoproducao',
			'tipo',
			'cobrarservico',
			'divisivel',
			'pesavel',
			'perecivel',
			'tempopreparo',
			'visivel',
			'dataatualizacao',
		);
		if($_produto['imagem'] !== true)
			$campos[] = 'imagem';
		try {
			$query = DB::$pdo->update('Produtos');
			$query = $query->set(array_intersect_key($_produto, array_flip($campos)));
			$query = $query->where('id', $_produto['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_produto['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir o produto, o id do produto não foi informado');
		$query = DB::$pdo->deleteFrom('Produtos')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch($busca, $categoria_id, $unidade_id, $tipo, $estoque) {
		$negativo = intval(is_boolean_config('Estoque', 'Estoque.Negativo'));
		if(!is_null($estoque)) {
			$estoque = intval($estoque);
			if($estoque)
				$tipo = ProdutoTipo::PRODUTO;
		} else
			$estoque = 1;
		$query = self::initGet()
					 ->leftJoin('Categorias c ON c.id = p.categoriaid')
					 ->where('(COALESCE(prm.proibir, "N") = "N" OR 1 = ?)', $estoque)
					 ->having('(p.tipo <> "Produto" OR (estoque > 0 OR 1 = ?))', intval($negativo || $estoque))
					 ->groupBy('p.id');
 		if(!$estoque)
 			$query = $query->where('p.visivel', 'Y');
		if(trim($tipo) != '')
			$query = $query->where('p.tipo', trim($tipo));
 		if(is_numeric($categoria_id)) {
			$query = $query->where('(p.categoriaid = ? OR c.categoriaid = ?)', $categoria_id, $categoria_id);
		}
 		if(is_numeric($unidade_id))
			$query = $query->where('p.unidadeid', $unidade_id);
		$busca = trim($busca);
		if(is_numeric($busca)) {
			$query = $query->where('(p.id = ? OR p.codigobarras = ?)', intval($busca), $busca);
		} else if($busca != '') {
			$keywords = preg_split('/[\s,]+/', $busca);
	 		foreach ($keywords as $word) {
				$query = $query->where('p.descricao LIKE ?', '%'.$word.'%');
                $query = $query->orderBy('COALESCE(NULLIF(LOCATE(?, CONCAT(" ", p.descricao)), 0), 65535) ASC', ' '.$word);
                $query = $query->orderBy('COALESCE(NULLIF(LOCATE(?, p.descricao), 0), 65535) ASC', $word);
	 		}
	 	}
		$query = $query->orderBy('p.descricao ASC');
 		return $query;
	}

	public static function getTodos($busca = null, $categoria_id = null, $unidade_id = null, 
			$tipo = null, $estoque = null, $inicio = null, $quantidade = null) {
		$query = self::initSearch($busca, $categoria_id, $unidade_id, $tipo, $estoque);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_produtos = $query->fetchAll();
		$produtos = array();
		foreach($_produtos as $produto)
			$produtos[] = new ZProduto($produto);
		return $produtos;
	}

	public static function getTodosEx($busca = null, $categoria_id = null, $unidade_id = null, 
			$tipo = null, $estoque = null, $inicio = null, $quantidade = null) {
		$query = self::initSearch($busca, $categoria_id, $unidade_id, $tipo, $estoque);
		$query = $query->leftJoin('Unidades u ON u.id = p.unidadeid')
					   ->select('c.descricao as categoria')
					   ->select('u.sigla as unidade');
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		return $query->fetchAll();
	}

	public static function getCount($busca = null, $categoria_id = null, $unidade_id = null, 
			$tipo = null, $estoque = null) {
		$query = self::initSearch($busca, $categoria_id, $unidade_id, $tipo, $estoque);
		$query = $query->select(null)
					   ->select('COUNT(DISTINCT p.id) as count')
					   ->select('p.tipo')
					   ->select('COALESCE(sum(e.quantidade), 0) as estoque')
					   ->groupBy(null);
		return (int) $query->fetchColumn();
	}

}
