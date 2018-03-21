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
class PatrimonioEstado
{
    const NOVO = 'Novo';
    const CONSERVADO = 'Conservado';
    const RUIM = 'Ruim';
}

/**
 * Informa detalhadamente um bem da empresa
 */
class ZPatrimonio
{
    private $id;
    private $empresa_id;
    private $fornecedor_id;
    private $numero;
    private $descricao;
    private $quantidade;
    private $altura;
    private $largura;
    private $comprimento;
    private $estado;
    private $custo;
    private $valor;
    private $ativo;
    private $imagem_anexada;
    private $data_atualizacao;

    public function __construct($patrimonio = [])
    {
        if (is_array($patrimonio)) {
            $this->setID(isset($patrimonio['id'])?$patrimonio['id']:null);
            $this->setEmpresaID(isset($patrimonio['empresaid'])?$patrimonio['empresaid']:null);
            $this->setFornecedorID(isset($patrimonio['fornecedorid'])?$patrimonio['fornecedorid']:null);
            $this->setNumero(isset($patrimonio['numero'])?$patrimonio['numero']:null);
            $this->setDescricao(isset($patrimonio['descricao'])?$patrimonio['descricao']:null);
            $this->setQuantidade(isset($patrimonio['quantidade'])?$patrimonio['quantidade']:null);
            $this->setAltura(isset($patrimonio['altura'])?$patrimonio['altura']:null);
            $this->setLargura(isset($patrimonio['largura'])?$patrimonio['largura']:null);
            $this->setComprimento(isset($patrimonio['comprimento'])?$patrimonio['comprimento']:null);
            $this->setEstado(isset($patrimonio['estado'])?$patrimonio['estado']:null);
            $this->setCusto(isset($patrimonio['custo'])?$patrimonio['custo']:null);
            $this->setValor(isset($patrimonio['valor'])?$patrimonio['valor']:null);
            $this->setAtivo(isset($patrimonio['ativo'])?$patrimonio['ativo']:null);
            $this->setImagemAnexada(isset($patrimonio['imagemanexada'])?$patrimonio['imagemanexada']:null);
            $this->setDataAtualizacao(isset($patrimonio['dataatualizacao'])?$patrimonio['dataatualizacao']:null);
        }
    }

    /**
     * Identificador do bem
     */
    public function getID()
    {
        return $this->id;
    }

    public function setID($id)
    {
        $this->id = $id;
    }

    /**
     * Empresa a que esse bem pertence
     */
    public function getEmpresaID()
    {
        return $this->empresa_id;
    }

    public function setEmpresaID($empresa_id)
    {
        $this->empresa_id = $empresa_id;
    }

    /**
     * Fornecedor do bem
     */
    public function getFornecedorID()
    {
        return $this->fornecedor_id;
    }

    public function setFornecedorID($fornecedor_id)
    {
        $this->fornecedor_id = $fornecedor_id;
    }

    /**
     * Número que identifica o bem
     */
    public function getNumero()
    {
        return $this->numero;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * Descrição ou nome do bem
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * Quantidade do bem com as mesmas características
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    /**
     * Altura do bem em metros
     */
    public function getAltura()
    {
        return $this->altura;
    }

    public function setAltura($altura)
    {
        $this->altura = $altura;
    }

    /**
     * Largura do bem em metros
     */
    public function getLargura()
    {
        return $this->largura;
    }

    public function setLargura($largura)
    {
        $this->largura = $largura;
    }

    /**
     * Comprimento do bem em metros
     */
    public function getComprimento()
    {
        return $this->comprimento;
    }

    public function setComprimento($comprimento)
    {
        $this->comprimento = $comprimento;
    }

    /**
     * Estado de conservação do bem
     */
    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Valor de custo do bem
     */
    public function getCusto()
    {
        return $this->custo;
    }

    public function setCusto($custo)
    {
        $this->custo = $custo;
    }

    /**
     * Valor que o bem vale atualmente
     */
    public function getValor()
    {
        return $this->valor;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Informa se o bem está ativo e em uso
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Informa se o bem está ativo e em uso
     */
    public function isAtivo()
    {
        return $this->ativo == 'Y';
    }

    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
    }

    /**
     * Caminho relativo da foto do bem
     */
    public function getImagemAnexada()
    {
        return $this->imagem_anexada;
    }

    public function setImagemAnexada($imagem_anexada)
    {
        $this->imagem_anexada = $imagem_anexada;
    }

    /**
     * Data de atualização das informações do bem
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
    }

    public function setDataAtualizacao($data_atualizacao)
    {
        $this->data_atualizacao = $data_atualizacao;
    }

    public function toArray()
    {
        $patrimonio = [];
        $patrimonio['id'] = $this->getID();
        $patrimonio['empresaid'] = $this->getEmpresaID();
        $patrimonio['fornecedorid'] = $this->getFornecedorID();
        $patrimonio['numero'] = $this->getNumero();
        $patrimonio['descricao'] = $this->getDescricao();
        $patrimonio['quantidade'] = $this->getQuantidade();
        $patrimonio['altura'] = $this->getAltura();
        $patrimonio['largura'] = $this->getLargura();
        $patrimonio['comprimento'] = $this->getComprimento();
        $patrimonio['estado'] = $this->getEstado();
        $patrimonio['custo'] = $this->getCusto();
        $patrimonio['valor'] = $this->getValor();
        $patrimonio['ativo'] = $this->getAtivo();
        $patrimonio['imagemanexada'] = $this->getImagemAnexada();
        $patrimonio['dataatualizacao'] = $this->getDataAtualizacao();
        return $patrimonio;
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Patrimonios')
                         ->where(['id' => $id]);
        return new ZPatrimonio($query->fetch());
    }

    public static function getPeloNumeroEstado($numero, $estado)
    {
        $query = DB::$pdo->from('Patrimonios')
                         ->where(['numero' => $numero, 'estado' => $estado]);
        return new ZPatrimonio($query->fetch());
    }

    private static function validarCampos(&$patrimonio)
    {
        $erros = [];
        if (!is_numeric($patrimonio['empresaid'])) {
            $erros['empresaid'] = 'A empresa não foi informada';
        }
        $patrimonio['fornecedorid'] = trim($patrimonio['fornecedorid']);
        if (strlen($patrimonio['fornecedorid']) == 0) {
            $patrimonio['fornecedorid'] = null;
        } elseif (!is_numeric($patrimonio['fornecedorid'])) {
            $erros['fornecedorid'] = 'O fornecedor não foi informado';
        }
        $patrimonio['numero'] = strip_tags(trim($patrimonio['numero']));
        if (strlen($patrimonio['numero']) == 0) {
            $erros['numero'] = 'O número não pode ser vazio';
        }
        $patrimonio['descricao'] = strip_tags(trim($patrimonio['descricao']));
        if (strlen($patrimonio['descricao']) == 0) {
            $erros['descricao'] = 'A descrição não pode ser vazia';
        }
        if (!is_numeric($patrimonio['quantidade'])) {
            $erros['quantidade'] = 'A quantidade não foi informada';
        } elseif ($patrimonio['quantidade'] < 1) {
            $erros['quantidade'] = 'A quantidade deve ser positiva';
        }
        if (!is_numeric($patrimonio['altura'])) {
            $erros['altura'] = 'A altura não foi informada';
        } else {
            $patrimonio['altura'] = floatval($patrimonio['altura']);
            if ($patrimonio['altura'] < 0) {
                $erros['altura'] = 'A altura não pode ser negativa';
            }
        }
        if (!is_numeric($patrimonio['largura'])) {
            $erros['largura'] = 'A largura não foi informada';
        } else {
            $patrimonio['largura'] = floatval($patrimonio['largura']);
            if ($patrimonio['largura'] < 0) {
                $erros['largura'] = 'A largura não pode ser negativa';
            }
        }
        if (!is_numeric($patrimonio['comprimento'])) {
            $erros['comprimento'] = 'O comprimento não foi informado';
        } else {
            $patrimonio['comprimento'] = floatval($patrimonio['comprimento']);
            if ($patrimonio['comprimento'] < 0) {
                $erros['comprimento'] = 'O comprimento não pode ser negativo';
            }
        }
        $patrimonio['estado'] = trim($patrimonio['estado']);
        if (strlen($patrimonio['estado']) == 0) {
            $patrimonio['estado'] = null;
        } elseif (!in_array($patrimonio['estado'], ['Novo', 'Conservado', 'Ruim'])) {
            $erros['estado'] = 'O estado informado não é válido';
        }
        if (!is_numeric($patrimonio['custo'])) {
            $erros['custo'] = 'O custo não foi informado';
        } else {
            $patrimonio['custo'] = floatval($patrimonio['custo']);
            if ($patrimonio['custo'] < 0) {
                $erros['custo'] = 'O custo não pode ser negativo';
            }
        }
        if (!is_numeric($patrimonio['valor'])) {
            $erros['valor'] = 'O valor não foi informado';
        } else {
            $patrimonio['valor'] = floatval($patrimonio['valor']);
            if ($patrimonio['valor'] < 0) {
                $erros['valor'] = 'O valor não pode ser negativo';
            }
        }
        $patrimonio['ativo'] = trim($patrimonio['ativo']);
        if (strlen($patrimonio['ativo']) == 0) {
            $patrimonio['ativo'] = 'N';
        } elseif (!in_array($patrimonio['ativo'], ['Y', 'N'])) {
            $erros['ativo'] = 'O ativo informado não é válido';
        }
        $patrimonio['imagemanexada'] = strip_tags(trim($patrimonio['imagemanexada']));
        if (strlen($patrimonio['imagemanexada']) == 0) {
            $patrimonio['imagemanexada'] = null;
        }
        $patrimonio['dataatualizacao'] = date('Y-m-d H:i:s');
        if (!empty($erros)) {
            throw new ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new ValidationException(['id' => 'O ID informado já está cadastrado']);
        }
        if (stripos($e->getMessage(), 'Numero_Estado_UNIQUE') !== false) {
            throw new ValidationException(['estado' => 'O estado informado já está cadastrado']);
        }
    }

    public static function cadastrar($patrimonio)
    {
        $_patrimonio = $patrimonio->toArray();
        self::validarCampos($_patrimonio);
        try {
            $_patrimonio['id'] = DB::$pdo->insertInto('Patrimonios')->values($_patrimonio)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_patrimonio['id']);
    }

    public static function atualizar($patrimonio)
    {
        $_patrimonio = $patrimonio->toArray();
        if (!$_patrimonio['id']) {
            throw new ValidationException(['id' => 'O id do patrimonio não foi informado']);
        }
        self::validarCampos($_patrimonio);
        $campos = [
            'empresaid',
            'fornecedorid',
            'numero',
            'descricao',
            'quantidade',
            'altura',
            'largura',
            'comprimento',
            'estado',
            'custo',
            'valor',
            'ativo',
            'imagemanexada',
            'dataatualizacao',
        ];
        try {
            $query = DB::$pdo->update('Patrimonios');
            $query = $query->set(array_intersect_key($_patrimonio, array_flip($campos)));
            $query = $query->where('id', $_patrimonio['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_patrimonio['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new Exception('Não foi possível excluir o patrimonio, o id do patrimonio não foi informado');
        }
        $query = DB::$pdo->deleteFrom('Patrimonios')
                         ->where(['id' => $id]);
        return $query->execute();
    }

    private static function initSearch($empresa, $fornecedor, $estado, $busca)
    {
        $query = DB::$pdo->from('Patrimonios')
                         ->orderBy('descricao ASC');
        if (is_numeric($empresa)) {
            $query = $query->where('empresaid', $empresa);
        }
        if (is_numeric($fornecedor)) {
            $query = $query->where('fornecedorid', $fornecedor);
        }
        $estado = trim($estado);
        if ($estado != '') {
            $query = $query->where('estado', $estado);
        }
        $busca = trim($busca);
        if ($busca != '') {
            $query = $query->where('descricao LIKE ?', '%'.$busca.'%');
        }
        return $query;
    }

    public static function getTodos($empresa = null, $fornecedor = null, $estado = null, $busca = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearch($empresa, $fornecedor, $estado, $busca);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_patrimonios = $query->fetchAll();
        $patrimonios = [];
        foreach ($_patrimonios as $patrimonio) {
            $patrimonios[] = new ZPatrimonio($patrimonio);
        }
        return $patrimonios;
    }

    public static function getCount($empresa = null, $fornecedor = null, $estado = null, $busca = null)
    {
        $query = self::initSearch($empresa, $fornecedor, $estado, $busca);
        return $query->count();
    }

    private static function initSearchDoFornecedorID($fornecedor_id)
    {
        return   DB::$pdo->from('Patrimonios')
                         ->where(['fornecedorid' => $fornecedor_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDoFornecedorID($fornecedor_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoFornecedorID($fornecedor_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_patrimonios = $query->fetchAll();
        $patrimonios = [];
        foreach ($_patrimonios as $patrimonio) {
            $patrimonios[] = new ZPatrimonio($patrimonio);
        }
        return $patrimonios;
    }

    public static function getCountDoFornecedorID($fornecedor_id)
    {
        $query = self::initSearchDoFornecedorID($fornecedor_id);
        return $query->count();
    }

    private static function initSearchDaEmpresaID($empresa_id)
    {
        return   DB::$pdo->from('Patrimonios')
                         ->where(['empresaid' => $empresa_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDaEmpresaID($empresa_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaEmpresaID($empresa_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_patrimonios = $query->fetchAll();
        $patrimonios = [];
        foreach ($_patrimonios as $patrimonio) {
            $patrimonios[] = new ZPatrimonio($patrimonio);
        }
        return $patrimonios;
    }

    public static function getCountDaEmpresaID($empresa_id)
    {
        $query = self::initSearchDaEmpresaID($empresa_id);
        return $query->count();
    }
}
