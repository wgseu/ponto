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
 * Unidades de medidas aplicadas aos produtos
 */
class ZUnidade
{
    private $id;
    private $nome;
    private $descricao;
    private $sigla;

    public function __construct($unidade = array())
    {
        if (is_array($unidade)) {
            $this->setID(isset($unidade['id'])?$unidade['id']:null);
            $this->setNome(isset($unidade['nome'])?$unidade['nome']:null);
            $this->setDescricao(isset($unidade['descricao'])?$unidade['descricao']:null);
            $this->setSigla(isset($unidade['sigla'])?$unidade['sigla']:null);
        }
    }

    /**
     * Identificador da unidade
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
     * Nome da unidade de medida, Ex.: Grama, Quilo
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
     * Detalhes sobre a unidade de medida
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
     * Sigla da unidade de medida, Ex.: UN, L, g
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    public function setSigla($sigla)
    {
        $this->sigla = $sigla;
    }

    public function toArray()
    {
        $unidade = array();
        $unidade['id'] = $this->getID();
        $unidade['nome'] = $this->getNome();
        $unidade['descricao'] = $this->getDescricao();
        $unidade['sigla'] = $this->getSigla();
        return $unidade;
    }

    private function processaUnidade($quantidade, $conteudo)
    {
        $unidade = $this->getSigla();
        $grandezas = array(
            -24 => 'y',
            -21 => 'z',
            -18 => 'a',
            -15 => 'f',
            -12 => 'p',
            -9  => 'n',
            -6  => 'µ',
            -3  => 'm',
            -2  => 'c',
            -1  => 'd',
             0  => '',
             1  => 'da',
             2  => 'h',
             3  => 'k',
             6  => 'M',
             9  => 'G',
             12 => 'T',
             15 => 'P',
             18 => 'E',
             21 => 'Z',
             24 => 'Y'
        );
        $index = intval(log10($conteudo));
        $remain = $conteudo / pow(10, $index);
        if (!array_key_exists($index, $grandezas)) {
            throw new Exception('Não existe grandeza para o conteudo '.$conteudo.' da unidade '.$unidade, 404);
        }
        $unidade = $grandezas[$index].$unidade;
        return array(
            'unidade' => $unidade,
            'quantidade' => $quantidade * $remain
        );
    }

    public function processaSigla($quantidade, $conteudo)
    {
        $data = $this->processaUnidade($quantidade, $conteudo);
        return $data['unidade'];
    }

    public function processaQuantidade($quantidade, $conteudo)
    {
        $data = $this->processaUnidade($quantidade, $conteudo);
        return $data['quantidade'];
    }

    public function formatar($quantidade, $conteudo)
    {
        $data = $this->processaUnidade($quantidade, $conteudo);
        return strval($data['quantidade']) . ' ' . $data['unidade'];
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Unidades')
                         ->where(array('id' => $id));
        return new ZUnidade($query->fetch());
    }

    public static function getPelaSigla($sigla)
    {
        $query = DB::$pdo->from('Unidades')
                         ->where(array('sigla' => $sigla));
        return new ZUnidade($query->fetch());
    }

    private static function validarCampos(&$unidade)
    {
        $erros = array();
        $unidade['nome'] = strip_tags(trim($unidade['nome']));
        if (strlen($unidade['nome']) == 0) {
            $erros['nome'] = 'O nome não pode ser vazio';
        }
        $unidade['descricao'] = strip_tags(trim($unidade['descricao']));
        if (strlen($unidade['descricao']) == 0) {
            $unidade['descricao'] = null;
        }
        $unidade['sigla'] = strip_tags(trim($unidade['sigla']));
        if (strlen($unidade['sigla']) == 0) {
            $erros['sigla'] = 'A sigla não pode ser vazia';
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
        if (stripos($e->getMessage(), 'Sigla_UNIQUE') !== false) {
            throw new ValidationException(array('sigla' => 'A sigla informada já está cadastrada'));
        }
    }

    public static function cadastrar($unidade)
    {
        $_unidade = $unidade->toArray();
        self::validarCampos($_unidade);
        try {
            $_unidade['id'] = DB::$pdo->insertInto('Unidades')->values($_unidade)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_unidade['id']);
    }

    public static function atualizar($unidade)
    {
        $_unidade = $unidade->toArray();
        if (!$_unidade['id']) {
            throw new ValidationException(array('id' => 'O id da unidade não foi informado'));
        }
        self::validarCampos($_unidade);
        $campos = array(
            'nome',
            'descricao',
            'sigla',
        );
        try {
            $query = DB::$pdo->update('Unidades');
            $query = $query->set(array_intersect_key($_unidade, array_flip($campos)));
            $query = $query->where('id', $_unidade['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_unidade['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new Exception('Não foi possível excluir a unidade, o id da unidade não foi informado');
        }
        $query = DB::$pdo->deleteFrom('Unidades')
                         ->where(array('id' => $id));
        return $query->execute();
    }

    private static function initSearch($busca)
    {
        $query = DB::$pdo->from('Unidades')
                         ->orderBy('nome ASC');
        $busca = trim($busca);
        if ($busca != '') {
            $query = $query->where('CONCAT(nome, " ", descricao) LIKE ?', '%'.$busca.'%');
        }
        return $query;
    }

    public static function getTodas($busca = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearch($busca);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_unidades = $query->fetchAll();
        $unidades = array();
        foreach ($_unidades as $unidade) {
            $unidades[] = new ZUnidade($unidade);
        }
        return $unidades;
    }

    public static function getCount($busca = null)
    {
        $query = self::initSearch($busca);
        return $query->count();
    }
}
