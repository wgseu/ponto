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
 * @author  Francimar Alves <mazinsw@gmail.com>
 */
namespace MZ\Sale;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Comanda individual, permite lançar pedidos em cartões de consumo
 */
class Comanda extends \MZ\Database\Helper
{

    /**
     * Número da comanda
     */
    private $id;
    /**
     * Nome da comanda
     */
    private $nome;
    /**
     * Informa se a comanda está diponível para ser usada nas vendas
     */
    private $ativa;

    /**
     * Constructor for a new empty instance of Comanda
     * @param array $comanda All field and values to fill the instance
     */
    public function __construct($comanda = array())
    {
        parent::__construct($comanda);
    }

    /**
     * Número da comanda
     * @return mixed Número of Comanda
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Comanda Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Nome da comanda
     * @return mixed Nome of Comanda
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param  mixed $nome new value for Nome
     * @return Comanda Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Informa se a comanda está diponível para ser usada nas vendas
     * @return mixed Ativa of Comanda
     */
    public function getAtiva()
    {
        return $this->ativa;
    }

    /**
     * Informa se a comanda está diponível para ser usada nas vendas
     * @return boolean Check if a of Ativa is selected or checked
     */
    public function isAtiva()
    {
        return $this->ativa == 'Y';
    }

    /**
     * Set Ativa value to new on param
     * @param  mixed $ativa new value for Ativa
     * @return Comanda Self instance
     */
    public function setAtiva($ativa)
    {
        $this->ativa = $ativa;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $comanda = parent::toArray($recursive);
        $comanda['id'] = $this->getID();
        $comanda['nome'] = $this->getNome();
        $comanda['ativa'] = $this->getAtiva();
        return $comanda;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $comanda Associated key -> value to assign into this instance
     * @return Comanda Self instance
     */
    public function fromArray($comanda = array())
    {
        if ($comanda instanceof Comanda) {
            $comanda = $comanda->toArray();
        } elseif (!is_array($comanda)) {
            $comanda = array();
        }
        parent::fromArray($comanda);
        if (!isset($comanda['id'])) {
            $this->setID(null);
        } else {
            $this->setID($comanda['id']);
        }
        if (!isset($comanda['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($comanda['nome']);
        }
        if (!isset($comanda['ativa'])) {
            $this->setAtiva(null);
        } else {
            $this->setAtiva($comanda['ativa']);
        }
        return $this;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Comanda $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setNome(Filter::string($this->getNome()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Comanda $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Comanda in array format
     */
    public function validate()
    {
        $errors = array();
        if (is_null($this->getNome())) {
            $errors['nome'] = 'O Nome não pode ser vazio';
        }
        if (is_null($this->getAtiva())) {
            $errors['ativa'] = 'A Ativa não pode ser vazia';
        }
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
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            return new \MZ\Exception\ValidationException(array(
                'id' => vsprintf('O Número "%s" já está cadastrado', array($this->getID())),
            ));
        }
        if (stripos($e->getMessage(), 'Nome_UNIQUE') !== false) {
            return new \MZ\Exception\ValidationException(array(
                'nome' => vsprintf('O Nome "%s" já está cadastrado', array($this->getNome())),
            ));
        }
        return parent::translate($e);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id número to find Comanda
     * @return Comanda A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find(array(
            'id' => intval($id),
        ));
    }

    /**
     * Find this object on database using, Nome
     * @param  string $nome nome to find Comanda
     * @return Comanda A filled instance or empty when not found
     */
    public static function findByNome($nome)
    {
        return self::find(array(
            'nome' => strval($nome),
        ));
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = array())
    {
        $query = self::getDB()->from('Comandas');
        return $query->where($condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @return Comanda A filled Comanda or empty instance
     */
    public static function find($condition)
    {
        $query = self::query($condition)->limit(1);
        $row = $query->fetch();
        if ($row === false) {
            $row = array();
        }
        return new Comanda($row);
    }

    /**
     * Fetch all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @param  integer $limit number of rows to get, null for all
     * @param  integer $offset start index to get rows, null for begining
     * @return array All rows instanced and filled
     */
    public static function findAll($condition = array(), $limit = null, $offset = null)
    {
        $query = self::query($condition);
        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }
        if (!is_null($offset)) {
            $query = $query->offset($offset);
        }
        $rows = $query->fetchAll();
        $result = array();
        foreach ($rows as $row) {
            $result[] = new Comanda($row);
        }
        return $result;
    }

    /**
     * Insert a new Comanda into the database and fill instance from database
     * @return Comanda Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Comandas')->values($values)->execute();
            $comanda = self::findByID($id);
            $this->fromArray($comanda->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Comanda with instance values into database for Número
     * @return Comanda Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da comanda não foi informado');
        }
        unset($values['id']);
        try {
            self::getDB()
                ->update('Comandas')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $comanda = self::findByID($this->getID());
            $this->fromArray($comanda->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Delete this instance from database using Número
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador da comanda não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Comandas')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Count all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = array())
    {
        $query = self::query($condition);
        return $query->count();
    }

    public static function getPeloID($id)
    {
        return self::findByID($id);
    }

    public static function getPeloNome($nome)
    {
        return self::findByNome($nome);
    }

    public static function getProximoID()
    {
        $query = self::query()
            ->select(null)
            ->select('MAX(id) as id');
        return $query->fetch('id') + 1;
    }

    private static function validarCampos(&$comanda)
    {
        $erros = array();
        $comanda['nome'] = strip_tags(trim($comanda['nome']));
        if (strlen($comanda['nome']) == 0) {
            $erros['nome'] = 'O Nome não pode ser vazio';
        }
        $comanda['ativa'] = trim($comanda['ativa']);
        if (strlen($comanda['ativa']) == 0) {
            $comanda['ativa'] = 'N';
        } elseif (!in_array($comanda['ativa'], array('Y', 'N'))) {
            $erros['ativa'] = 'O estado de ativação da comanda não é válido';
        }
        if (!empty($erros)) {
            throw new \MZ\Exception\ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new \MZ\Exception\ValidationException(array('id' => 'O ID informado já está cadastrado'));
        }
        if (stripos($e->getMessage(), 'Nome_UNIQUE') !== false) {
            throw new \MZ\Exception\ValidationException(array('nome' => 'O Nome informado já está cadastrado'));
        }
    }

    public static function cadastrar($comanda)
    {
        $_comanda = $comanda->toArray();
        self::validarCampos($_comanda);
        try {
            $_comanda['id'] = self::getDB()->insertInto('Comandas')->values($_comanda)->execute();
        } catch (\Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_comanda['id']);
    }

    public static function atualizar($comanda)
    {
        $_comanda = $comanda->toArray();
        if (!$_comanda['id']) {
            throw new \MZ\Exception\ValidationException(array('id' => 'O id da comanda não foi informado'));
        }
        self::validarCampos($_comanda);
        $campos = array(
            'nome',
            'ativa',
        );
        try {
            $query = self::getDB()->update('Comandas');
            $query = $query->set(array_intersect_key($_comanda, array_flip($campos)));
            $query = $query->where('id', $_comanda['id']);
            $query->execute();
        } catch (\Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_comanda['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new \Exception('Não foi possível excluir a comanda, o id da comanda não foi informado');
        }
        $query = self::getDB()->deleteFrom('Comandas')
                         ->where(array('id' => $id));
        return $query->execute();
    }

    private static function initSearch($ativa, $busca)
    {
        $query = self::query();
        $busca = trim($busca);
        if (is_numeric($busca)) {
            $query = $query->where('id', $busca);
        } elseif ($busca != '') {
            $query = $query->where('nome LIKE ?', '%'.$busca.'%');
        }
        if (in_array($ativa, array('Y', 'N'))) {
            $query = $query->where('ativa', $ativa);
        }
        $query = $query->orderBy('id ASC');
        return $query;
    }

    public static function getTodas($ativa = null, $busca = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearch($ativa, $busca);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_comandas = $query->fetchAll();
        $comandas = array();
        foreach ($_comandas as $comanda) {
            $comandas[] = new Comanda($comanda);
        }
        return $comandas;
    }

    public static function getCount($ativa = null, $busca = null)
    {
        $query = self::initSearch($ativa, $busca);
        return $query->count();
    }
}
