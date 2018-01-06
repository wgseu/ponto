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
 * Cidade de um estado, contém bairros
 */
class ZCidade
{
    private $id;
    private $estado_id;
    private $nome;
    private $cep;

    public function __construct($cidade = array())
    {
        if (is_array($cidade)) {
            $this->setID(isset($cidade['id'])?$cidade['id']:null);
            $this->setEstadoID(isset($cidade['estadoid'])?$cidade['estadoid']:null);
            $this->setNome(isset($cidade['nome'])?$cidade['nome']:null);
            $this->setCEP(isset($cidade['cep'])?$cidade['cep']:null);
        }
    }

    /**
     * Código que identifica a cidade
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
     * Informa a qual estado a cidade pertence
     */
    public function getEstadoID()
    {
        return $this->estado_id;
    }

    public function setEstadoID($estado_id)
    {
        $this->estado_id = $estado_id;
    }

    /**
     * Nome da cidade, é único para cada estado
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
     * Código dos correios para identificação da cidade
     */
    public function getCEP()
    {
        return $this->cep;
    }

    public function setCEP($cep)
    {
        $this->cep = $cep;
    }

    public function toArray()
    {
        $cidade = array();
        $cidade['id'] = $this->getID();
        $cidade['estadoid'] = $this->getEstadoID();
        $cidade['nome'] = $this->getNome();
        $cidade['cep'] = $this->getCEP();
        return $cidade;
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Cidades')
                         ->where(array('id' => $id));
        return new ZCidade($query->fetch());
    }

    public static function getPeloEstadoIDNome($estado_id, $nome)
    {
        $query = DB::$pdo->from('Cidades')
                         ->where(array('estadoid' => $estado_id, 'nome' => $nome));
        return new ZCidade($query->fetch());
    }

    public static function getPeloCEP($cep)
    {
        $query = DB::$pdo->from('Cidades')
                         ->where(array('cep' => $cep));
        return new ZCidade($query->fetch());
    }

    public static function procuraOuCadastra($estado_id, $nome)
    {
        $nome = trim($nome);
        $cidade = self::getPeloEstadoIDNome($estado_id, $nome);
        if (!is_null($cidade->getID())) {
            return $cidade;
        }
        $cidade->setEstadoID($estado_id);
        $cidade->setNome($nome);
        return ZCidade::cadastrar($cidade);
    }

    private static function validarCampos(&$cidade)
    {
        $erros = array();
        if (!is_numeric($cidade['estadoid'])) {
            $erros['estadoid'] = 'O estado não foi informado';
        }
        $cidade['nome'] = strip_tags(trim($cidade['nome']));
        if (strlen($cidade['nome']) == 0) {
            $erros['nome'] = 'O nome não pode ser vazio';
        }
        $cidade['cep'] = \MZ\Util\Filter::unmask($cidade['cep'], _p('Mascara', 'CEP'));
        if (strlen($cidade['cep']) == 0) {
            $cidade['cep'] = null;
        } elseif (!check_cep($cidade['cep'])) {
            $erros['cep'] = vsprintf('%s inválido', array(_p('Titulo', 'CEP')));
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
        if (stripos($e->getMessage(), 'EstadoID_Nome_UNIQUE') !== false) {
            throw new ValidationException(array('nome' => 'O nome informado já está cadastrado'));
        }
        if (stripos($e->getMessage(), 'CEP_UNIQUE') !== false) {
            throw new ValidationException(
                array('cep' => vsprintf('O %s informado já está cadastrado', array(_p('Titulo', 'CEP'))))
            );
        }
    }

    public static function cadastrar($cidade)
    {
        $_cidade = $cidade->toArray();
        self::validarCampos($_cidade);
        try {
            $_cidade['id'] = DB::$pdo->insertInto('Cidades')->values($_cidade)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_cidade['id']);
    }

    public static function atualizar($cidade)
    {
        $_cidade = $cidade->toArray();
        if (!$_cidade['id']) {
            throw new ValidationException(array('id' => 'O id da cidade não foi informado'));
        }
        self::validarCampos($_cidade);
        $campos = array(
            'estadoid',
            'nome',
            'cep',
        );
        try {
            $query = DB::$pdo->update('Cidades');
            $query = $query->set(array_intersect_key($_cidade, array_flip($campos)));
            $query = $query->where('id', $_cidade['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_cidade['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new Exception('Não foi possível excluir a cidade, o id da cidade não foi informado');
        }
        $query = DB::$pdo->deleteFrom('Cidades')
                         ->where(array('id' => $id));
        return $query->execute();
    }

    private static function initSearch($busca, $pais_id, $estado_id)
    {
        $query = DB::$pdo->from('Cidades c')
                         ->orderBy('c.nome ASC');
        $busca = trim($busca);
        if ($busca != '') {
            $query = $query->where('c.nome LIKE ?', '%'.$busca.'%');
        }
        if (is_numeric($pais_id)) {
            $query = $query->leftJoin('Estados e ON e.id = c.estadoid')
                           ->where('e.paisid', $pais_id);
        }
        if (is_numeric($estado_id)) {
            $query = $query->where('c.estadoid', $estado_id);
        }
        return $query;
    }

    public static function getTodas(
        $busca = null,
        $pais_id = null,
        $estado_id = null,
        $inicio = null,
        $quantidade = null
    ) {
        $query = self::initSearch($busca, $pais_id, $estado_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_cidades = $query->fetchAll();
        $cidades = array();
        foreach ($_cidades as $cidade) {
            $cidades[] = new ZCidade($cidade);
        }
        return $cidades;
    }

    public static function getCount($busca = null, $pais_id = null, $estado_id = null)
    {
        $query = self::initSearch($busca, $pais_id, $estado_id);
        return $query->count();
    }

    private static function initSearchDoEstadoID($estado_id, $busca)
    {
        $query = DB::$pdo->from('Cidades')
                         ->where(array('estadoid' => $estado_id))
                         ->orderBy('nome ASC');
        $busca = trim($busca);
        if ($busca != '') {
            $query = $query->where('nome LIKE ?', '%'.$busca.'%');
        }
        return $query;
    }

    public static function getTodasDoEstadoID($estado_id, $busca = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoEstadoID($estado_id, $busca);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_cidades = $query->fetchAll();
        $cidades = array();
        foreach ($_cidades as $cidade) {
            $cidades[] = new ZCidade($cidade);
        }
        return $cidades;
    }

    public static function getCountDoEstadoID($estado_id, $busca = null)
    {
        $query = self::initSearchDoEstadoID($estado_id, $busca);
        return $query->count();
    }
}
