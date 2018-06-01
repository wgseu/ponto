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
namespace MZ\System;

use MZ\Database\Model;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Página WEB que contém informações de contato, termos e outras
 * informações da empresa
 */
class Pagina extends Model
{
    const NOME_SOBRE = 'sobre';
    const NOME_PRIVACIDADE = 'privacidade';
    const NOME_TERMOS = 'termos';

    /**
     * Identificador da página
     */
    private $id;
    /**
     * Nome da página, único no sistema com o código da linguagem
     */
    private $nome;
    /**
     * Código da linguagem para exibição no idioma correto, único com o nome
     */
    private $linguagem_id;
    /**
     * Conteúdo da página, geralmente texto formatado em HTML
     */
    private $conteudo;

    /**
     * Constructor for a new empty instance of Pagina
     * @param array $pagina All field and values to fill the instance
     */
    public function __construct($pagina = [])
    {
        parent::__construct($pagina);
    }

    /**
     * Identificador da página
     * @return mixed ID of Pagina
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Pagina Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Nome da página, único no sistema com o código da linguagem
     * @return mixed Nome of Pagina
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param  mixed $nome new value for Nome
     * @return Pagina Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Código da linguagem para exibição no idioma correto, único com o nome
     * @return mixed Linguagem of Pagina
     */
    public function getLinguagemID()
    {
        return $this->linguagem_id;
    }

    /**
     * Set LinguagemID value to new on param
     * @param  mixed $linguagem_id new value for LinguagemID
     * @return Pagina Self instance
     */
    public function setLinguagemID($linguagem_id)
    {
        $this->linguagem_id = $linguagem_id;
        return $this;
    }

    /**
     * Conteúdo da página, geralmente texto formatado em HTML
     * @return mixed Conteúdo of Pagina
     */
    public function getConteudo()
    {
        return $this->conteudo;
    }

    /**
     * Set Conteudo value to new on param
     * @param  mixed $conteudo new value for Conteudo
     * @return Pagina Self instance
     */
    public function setConteudo($conteudo)
    {
        $this->conteudo = $conteudo;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $pagina = parent::toArray($recursive);
        $pagina['id'] = $this->getID();
        $pagina['nome'] = $this->getNome();
        $pagina['linguagemid'] = $this->getLinguagemID();
        $pagina['conteudo'] = $this->getConteudo();
        return $pagina;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $pagina Associated key -> value to assign into this instance
     * @return Pagina Self instance
     */
    public function fromArray($pagina = [])
    {
        if ($pagina instanceof Pagina) {
            $pagina = $pagina->toArray();
        } elseif (!is_array($pagina)) {
            $pagina = [];
        }
        parent::fromArray($pagina);
        if (!isset($pagina['id'])) {
            $this->setID(null);
        } else {
            $this->setID($pagina['id']);
        }
        if (!isset($pagina['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($pagina['nome']);
        }
        if (!isset($pagina['linguagemid'])) {
            $this->setLinguagemID(null);
        } else {
            $this->setLinguagemID($pagina['linguagemid']);
        }
        if (!array_key_exists('conteudo', $pagina)) {
            $this->setConteudo(null);
        } else {
            $this->setConteudo($pagina['conteudo']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $pagina = parent::publish();
        return $pagina;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Pagina $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setNome(Filter::string($this->getNome()));
        $this->setLinguagemID(Filter::number($this->getLinguagemID()));
        $this->setConteudo(Filter::text($this->getConteudo()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Pagina $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Pagina in array format
     */
    public function validate()
    {
        $errors = [];
        if (!Validator::checkInSet($this->getNome(), self::getNomeOptions())) {
            $errors['nome'] = 'A página não foi informada ou não existe no site';
        }
        if (!Validator::checkInSet($this->getLinguagemID(), get_languages_info())) {
            $errors['linguagemid'] = 'A linguagem não foi informada ou ainda não é suportada';
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
        if (contains(['Nome', 'LinguagemID', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'nome' => sprintf(
                    'O nome "%s" já está cadastrado',
                    $this->getNome()
                ),
                'linguagemid' => sprintf(
                    'A linguagem "%s" já está cadastrada',
                    $this->getLinguagemID()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Página into the database and fill instance from database
     * @return Pagina Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Paginas')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Página with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Pagina Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da página não foi informado');
        }
        $values = DB::filterValues($values, $only, $except);
        try {
            DB::update('Paginas')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $this->loadByID($this->getID());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador da página não foi informado');
        }
        $result = DB::deleteFrom('Paginas')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Pagina Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, Nome, LinguagemID
     * @param  string $nome nome to find Página
     * @param  int $linguagem_id linguagem to find Página
     * @return Pagina Self filled instance or empty when not found
     */
    public function loadByNomeLinguagemID($nome, $linguagem_id)
    {
        return $this->load([
            'nome' => strval($nome),
            'linguagemid' => intval($linguagem_id),
        ]);
    }

    /**
     * Gets textual and translated Nome for Pagina
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getNomeOptions($index = null)
    {
        $options = [
            self::NOME_SOBRE => 'Sobre a empresa',
            self::NOME_PRIVACIDADE => 'Privacidade',
            self::NOME_TERMOS => 'Termos de uso',
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $pagina = new Pagina();
        $allowed = Filter::concatKeys('p.', $pagina->toArray());
        return $allowed;
    }

    /**
     * Filter order array
     * @param  mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $allowed = self::getAllowedKeys();
        return Filter::orderBy($order, $allowed, 'p.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        if (isset($condition['search'])) {
            $search = $condition['search'];
            $field = 'p.nome LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'p.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Paginas p');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('p.nome ASC');
        $query = $query->orderBy('p.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Pagina A filled Página or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Pagina($row);
    }

    /**
     * Find this object on database using, Nome, LinguagemID
     * @param  string $nome nome to find Página
     * @param  int $linguagem_id linguagem to find Página
     * @return Pagina A filled instance or empty when not found
     */
    public static function findByNomeLinguagemID($nome, $linguagem_id)
    {
        $result = new self();
        return $result->loadByNomeLinguagemID($nome, $linguagem_id);
    }


    /**
     * Find this object on database using, Nome and current LinguagemID
     * @param  string $nome nome to find página
     * @return Pagina A filled instance or empty when not found
     */
    public static function findByName($page_name)
    {
        $pagina = Pagina::findByNomeLinguagemID($page_name, current_language_id());
        if (!$pagina->exists()) {
            $pagina = Pagina::findByNomeLinguagemID($page_name, 1046);
        }
        return $pagina;
    }

    /**
     * Find all Página
     * @param  array  $condition Condition to get all Página
     * @param  array  $order     Order Página
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Pagina
     */
    public static function findAll($condition = [], $order = [], $limit = null, $offset = null)
    {
        $query = self::query($condition, $order);
        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }
        if (!is_null($offset)) {
            $query = $query->offset($offset);
        }
        $rows = $query->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[] = new Pagina($row);
        }
        return $result;
    }

    /**
     * Count all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        return $query->count();
    }
}
