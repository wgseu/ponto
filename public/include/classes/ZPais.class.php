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
/**
 * Informações de um páis com sua moeda e língua nativa
 */
class ZPais
{
    private $id;
    private $nome;
    private $sigla;
    private $moeda_id;
    private $bandeira_index;
    private $linguagem_id;
    private $entradas;
    private $unitario;

    public function __construct($pais = array())
    {
        if (is_array($pais)) {
            $this->setID(isset($pais['id'])?$pais['id']:null);
            $this->setNome(isset($pais['nome'])?$pais['nome']:null);
            $this->setSigla(isset($pais['sigla'])?$pais['sigla']:null);
            $this->setMoedaID(isset($pais['moedaid'])?$pais['moedaid']:null);
            $this->setBandeiraIndex(isset($pais['bandeiraindex'])?$pais['bandeiraindex']:null);
            $this->setLinguagemID(isset($pais['linguagemid'])?$pais['linguagemid']:null);
            $this->setEntradas(isset($pais['entradas'])?$pais['entradas']:null);
            $this->setUnitario(isset($pais['unitario'])?$pais['unitario']:null);
        }
    }

    /**
     * Identificador do país
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
     * Nome do país
     */
    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * Abreviação do nome do país
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    public function setSigla($sigla)
    {
        $this->sigla = $sigla;
    }

    /**
     * Informa a moeda principal do país
     */
    public function getMoedaID()
    {
        return $this->moeda_id;
    }

    public function setMoedaID($moeda_id)
    {
        $this->moeda_id = $moeda_id;
    }

    /**
     * Index da imagem da bandeira do país
     */
    public function getBandeiraIndex()
    {
        return $this->bandeira_index;
    }

    public function setBandeiraIndex($bandeira_index)
    {
        $this->bandeira_index = $bandeira_index;
    }

    /**
     * Linguagem nativa do país
     */
    public function getLinguagemID()
    {
        return $this->linguagem_id;
    }

    public function setLinguagemID($linguagem_id)
    {
        $this->linguagem_id = $linguagem_id;
    }

    /**
     * Frases, nomes de campos e máscaras específicas do país
     */
    public function getEntradas()
    {
        return $this->entradas;
    }

    public function setEntradas($entradas)
    {
        $this->entradas = $entradas;
    }

    /**
     * Informa se o país tem apenas um estado federativo
     */
    public function getUnitario()
    {
        return $this->unitario;
    }

    /**
     * Informa se o país tem apenas um estado federativo
     */
    public function isUnitario()
    {
        return $this->unitario == 'Y';
    }

    public function setUnitario($unitario)
    {
        $this->unitario = $unitario;
    }

    /**
     * Obtém o código do país pela sigla
     TODO:: adicionar campo na tabela Paises do banco de dados
     */
    public function getCodigo()
    {
        switch ($this->sigla) {
            case 'BRA':
                return 'BR';
            case 'USA':
                return 'US';
            case 'ESP':
                return 'ES';
            case 'MOZ':
                return 'MZ';
        }
        return $this->sigla;
    }

    public function toArray()
    {
        $pais = array();
        $pais['id'] = $this->getID();
        $pais['nome'] = $this->getNome();
        $pais['sigla'] = $this->getSigla();
        $pais['moedaid'] = $this->getMoedaID();
        $pais['bandeiraindex'] = $this->getBandeiraIndex();
        $pais['linguagemid'] = $this->getLinguagemID();
        $pais['entradas'] = $this->getEntradas();
        $pais['unitario'] = $this->getUnitario();
        return $pais;
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Paises')
                         ->where(array('id' => $id));
        return new ZPais($query->fetch());
    }

    public static function getPeloNome($nome)
    {
        $query = DB::$pdo->from('Paises')
                         ->where(array('nome' => $nome));
        return new ZPais($query->fetch());
    }

    public static function getPelaSigla($sigla)
    {
        $query = DB::$pdo->from('Paises')
                         ->where(array('sigla' => $sigla));
        return new ZPais($query->fetch());
    }

    /**
     * Informa a moeda principal do país
     * @return ZMoeda The object fetched from database
     */
    public function findMoedaID()
    {
        return ZMoeda::getPeloID($this->getMoedaID());
    }

    private static function validarCampos(&$pais)
    {
        $erros = array();
        $pais['nome'] = strip_tags(trim($pais['nome']));
        if (strlen($pais['nome']) == 0) {
            $erros['nome'] = 'O nome não pode ser vazio';
        }
        $pais['sigla'] = strip_tags(trim($pais['sigla']));
        if (strlen($pais['sigla']) == 0) {
            $erros['sigla'] = 'A sigla não pode ser vazia';
        }
        if (!is_numeric($pais['moedaid'])) {
            $erros['moedaid'] = 'A moeda não foi informada';
        }
        if (!is_numeric($pais['bandeiraindex'])) {
            $erros['bandeiraindex'] = 'A bandeira não foi informada';
        } elseif ($pais['bandeiraindex'] < 0 || $pais['bandeiraindex'] > 237) {
            $erros['bandeiraindex'] = 'A bandeira informada é inválida';
        }
        if (!is_numeric($pais['linguagemid'])) {
            $erros['linguagemid'] = 'A linguagem não foi informada';
        } else {
            $pais['linguagemid'] = intval($pais['linguagemid']);
            if ($pais['linguagemid'] < 0) {
                $erros['linguagemid'] = 'A linguagem não pode ser negativa';
            }
        }
        $pais['entradas'] = strval($pais['entradas']);
        if (strlen($pais['entradas']) == 0) {
            $pais['entradas'] = null;
        }
        $pais['unitario'] = trim($pais['unitario']);
        if (strlen($pais['unitario']) == 0) {
            $pais['unitario'] = 'N';
        } elseif (!in_array($pais['unitario'], array('Y', 'N'))) {
            $erros['unitario'] = 'O unitário informado não é válido';
        }
        if (!empty($erros)) {
            throw new ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
        }
        if (stripos($e->getMessage(), 'Nome_UNIQUE') !== false) {
            throw new ValidationException(array('nome' => 'O nome informado já está cadastrado'));
        }
        if (stripos($e->getMessage(), 'Sigla_UNIQUE') !== false) {
            throw new ValidationException(array('sigla' => 'A sigla informada já está cadastrada'));
        }
    }

    public static function cadastrar($pais)
    {
        $_pais = $pais->toArray();
        self::validarCampos($_pais);
        try {
            $_pais['id'] = DB::$pdo->insertInto('Paises')->values($_pais)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_pais['id']);
    }

    public static function atualizar($pais)
    {
        $_pais = $pais->toArray();
        if (!$_pais['id']) {
            throw new ValidationException(array('id' => 'O id da pal não foi informado'));
        }
        self::validarCampos($_pais);
        $campos = array(
            'nome',
            'sigla',
            'moedaid',
            'bandeiraindex',
            'linguagemid',
            'entradas',
            'unitario',
        );
        try {
            $query = DB::$pdo->update('Paises');
            $query = $query->set(array_intersect_key($_pais, array_flip($campos)));
            $query = $query->where('id', $_pais['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_pais['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new Exception('Não foi possível excluir a pal, o id da pal não foi informado');
        }
        $query = DB::$pdo->deleteFrom('Paises')
                         ->where(array('id' => $id));
        return $query->execute();
    }

    private static function initSearch($busca, $moeda_id)
    {
        $query = DB::$pdo->from('Paises')
                         ->orderBy('id ASC');
        if (is_numeric($moeda_id)) {
            $query = $query->where('moedaid', $moeda_id);
        }
        $busca = trim($busca);
        if ($busca != '') {
            $query = $query->where('CONCAT(nome, " ", sigla) LIKE ?', '%'.$busca.'%');
        }
        return $query;
    }

    public static function getTodas($busca = null, $moeda_id = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearch($busca, $moeda_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_paiss = $query->fetchAll();
        $paiss = array();
        foreach ($_paiss as $pais) {
            $paiss[] = new ZPais($pais);
        }
        return $paiss;
    }

    public static function getCount($busca = null, $moeda_id = null)
    {
        $query = self::initSearch($busca, $moeda_id);
        return $query->count();
    }

    private static function initSearchDaMoedaID($moeda_id)
    {
        return   DB::$pdo->from('Paises')
                         ->where(array('moedaid' => $moeda_id))
                         ->orderBy('id ASC');
    }

    public static function getTodasDaMoedaID($moeda_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaMoedaID($moeda_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_paiss = $query->fetchAll();
        $paiss = array();
        foreach ($_paiss as $pais) {
            $paiss[] = new ZPais($pais);
        }
        return $paiss;
    }

    public static function getCountDaMoedaID($moeda_id)
    {
        $query = self::initSearchDaMoedaID($moeda_id);
        return $query->count();
    }
}
