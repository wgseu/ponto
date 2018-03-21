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
namespace MZ\Product;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Informa tamanhos de pizzas e opções de peso do produto
 */
class Propriedade extends \MZ\Database\Helper
{

    /**
     * Identificador da propriedade
     */
    private $id;
    /**
     * Grupo que possui essa propriedade como item de um pacote
     */
    private $grupo_id;
    /**
     * Nome da propriedade, Ex.: Grande, Pequena
     */
    private $nome;
    /**
     * Abreviação do nome da propriedade, Ex.: G para Grande, P para Pequena,
     * essa abreviação fará parte do nome do produto
     */
    private $abreviacao;
    /**
     * Imagem que representa a propriedade
     */
    private $imagem;
    /**
     * Data de atualização dos dados ou da imagem da propriedade
     */
    private $data_atualizacao;

    /**
     * Constructor for a new empty instance of Propriedade
     * @param array $propriedade All field and values to fill the instance
     */
    public function __construct($propriedade = [])
    {
        parent::__construct($propriedade);
    }

    /**
     * Identificador da propriedade
     * @return mixed ID of Propriedade
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Propriedade Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Grupo que possui essa propriedade como item de um pacote
     * @return mixed Grupo of Propriedade
     */
    public function getGrupoID()
    {
        return $this->grupo_id;
    }

    /**
     * Set GrupoID value to new on param
     * @param  mixed $grupo_id new value for GrupoID
     * @return Propriedade Self instance
     */
    public function setGrupoID($grupo_id)
    {
        $this->grupo_id = $grupo_id;
        return $this;
    }

    /**
     * Nome da propriedade, Ex.: Grande, Pequena
     * @return mixed Nome of Propriedade
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param  mixed $nome new value for Nome
     * @return Propriedade Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Abreviação do nome da propriedade, Ex.: G para Grande, P para Pequena,
     * essa abreviação fará parte do nome do produto
     * @return mixed Abreviação of Propriedade
     */
    public function getAbreviacao()
    {
        return $this->abreviacao;
    }

    /**
     * Set Abreviacao value to new on param
     * @param  mixed $abreviacao new value for Abreviacao
     * @return Propriedade Self instance
     */
    public function setAbreviacao($abreviacao)
    {
        $this->abreviacao = $abreviacao;
        return $this;
    }

    /**
     * Imagem que representa a propriedade
     * @return mixed Imagem of Propriedade
     */
    public function getImagem()
    {
        return $this->imagem;
    }

    /**
     * Set Imagem value to new on param
     * @param  mixed $imagem new value for Imagem
     * @return Propriedade Self instance
     */
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
        return $this;
    }

    /**
     * Data de atualização dos dados ou da imagem da propriedade
     * @return mixed Data de atualização of Propriedade
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
    }

    /**
     * Set DataAtualizacao value to new on param
     * @param  mixed $data_atualizacao new value for DataAtualizacao
     * @return Propriedade Self instance
     */
    public function setDataAtualizacao($data_atualizacao)
    {
        $this->data_atualizacao = $data_atualizacao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $propriedade = parent::toArray($recursive);
        $propriedade['id'] = $this->getID();
        $propriedade['grupoid'] = $this->getGrupoID();
        $propriedade['nome'] = $this->getNome();
        $propriedade['abreviacao'] = $this->getAbreviacao();
        $propriedade['imagem'] = $this->getImagem();
        $propriedade['dataatualizacao'] = $this->getDataAtualizacao();
        return $propriedade;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $propriedade Associated key -> value to assign into this instance
     * @return Propriedade Self instance
     */
    public function fromArray($propriedade = [])
    {
        if ($propriedade instanceof Propriedade) {
            $propriedade = $propriedade->toArray();
        } elseif (!is_array($propriedade)) {
            $propriedade = [];
        }
        parent::fromArray($propriedade);
        if (!isset($propriedade['id'])) {
            $this->setID(null);
        } else {
            $this->setID($propriedade['id']);
        }
        if (!isset($propriedade['grupoid'])) {
            $this->setGrupoID(null);
        } else {
            $this->setGrupoID($propriedade['grupoid']);
        }
        if (!isset($propriedade['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($propriedade['nome']);
        }
        if (!array_key_exists('abreviacao', $propriedade)) {
            $this->setAbreviacao(null);
        } else {
            $this->setAbreviacao($propriedade['abreviacao']);
        }
        if (!array_key_exists('imagem', $propriedade)) {
            $this->setImagem(null);
        } else {
            $this->setImagem($propriedade['imagem']);
        }
        if (!isset($propriedade['dataatualizacao'])) {
            $this->setDataAtualizacao(null);
        } else {
            $this->setDataAtualizacao($propriedade['dataatualizacao']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $propriedade = parent::publish();
        $propriedade['imagem'] = $this->makeImagem();
        return $propriedade;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Propriedade $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setGrupoID(Filter::number($this->getGrupoID()));
        $this->setNome(Filter::string($this->getNome()));
        $this->setAbreviacao(Filter::string($this->getAbreviacao()));
        $imagem = upload_image('raw_imagem', 'propriedade');
        if (is_null($imagem) && trim($this->getImagem()) != '') {
            $this->setImagem($original->getImagem());
        } else {
            $this->setImagem($imagem);
        }
        $this->setDataAtualizacao(Filter::datetime($this->getDataAtualizacao()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Propriedade $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
        if (!is_null($this->getImagem()) && $dependency->getImagem() != $this->getImagem()) {
            unlink(get_image_path($this->getImagem(), 'propriedade'));
        }
        $this->setImagem($dependency->getImagem());
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Propriedade in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getGrupoID())) {
            $errors['grupoid'] = 'O grupo não pode ser vazio';
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = 'O nome não pode ser vazio';
        }
        if (is_null($this->getDataAtualizacao())) {
            $errors['dataatualizacao'] = 'A data de atualização não pode ser vazia';
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
            return new \MZ\Exception\ValidationException([
                'id' => sprintf(
                    'O id "%s" já está cadastrado',
                    $this->getID()
                ),
            ]);
        }
        if (stripos($e->getMessage(), 'GrupoID_Nome_UNIQUE') !== false) {
            return new \MZ\Exception\ValidationException([
                'grupoid' => sprintf(
                    'O grupo "%s" já está cadastrado',
                    $this->getGrupoID()
                ),
                'nome' => sprintf(
                    'O nome "%s" já está cadastrado',
                    $this->getNome()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Get relative imagem path or default imagem
     * @param boolean $default If true return default image, otherwise check field
     * @return string relative web path for propriedade imagem
     */
    public function makeImagem($default = false)
    {
        $imagem = $this->getImagem();
        if ($default) {
            $imagem = null;
        }
        return get_image_url($imagem, 'propriedade', 'propriedade.png');
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Propriedade
     * @return Propriedade A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, GrupoID, Nome
     * @param  int $grupo_id grupo to find Propriedade
     * @param  string $nome nome to find Propriedade
     * @return Propriedade A filled instance or empty when not found
     */
    public static function findByGrupoIDNome($grupo_id, $nome)
    {
        return self::find([
            'grupoid' => intval($grupo_id),
            'nome' => strval($nome),
        ]);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $propriedade = new Propriedade();
        $allowed = Filter::concatKeys('p.', $propriedade->toArray());
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
        $query = self::getDB()->from('Propriedades p');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('p.nome ASC');
        $query = $query->orderBy('p.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Propriedade A filled Propriedade or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch();
        if ($row === false) {
            $row = [];
        }
        return new Propriedade($row);
    }

    /**
     * Fetch all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @param  integer $limit number of rows to get, null for all
     * @param  integer $offset start index to get rows, null for begining
     * @return array All rows instanced and filled
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
            $result[] = new Propriedade($row);
        }
        return $result;
    }

    /**
     * Insert a new Propriedade into the database and fill instance from database
     * @return Propriedade Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Propriedades')->values($values)->execute();
            $propriedade = self::findByID($id);
            $this->fromArray($propriedade->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Propriedade with instance values into database for ID
     * @return Propriedade Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da propriedade não foi informado');
        }
        unset($values['id']);
        try {
            self::getDB()
                ->update('Propriedades')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $propriedade = self::findByID($this->getID());
            $this->fromArray($propriedade->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Save the Propriedade into the database
     * @return Propriedade Self instance
     */
    public function save()
    {
        if ($this->exists()) {
            return $this->update();
        }
        return $this->insert();
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador da propriedade não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Propriedades')
            ->where('id', $this->getID())
            ->execute();
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

    /**
     * Grupo que possui essa propriedade como item de um pacote
     * @return \MZ\Product\Grupo The object fetched from database
     */
    public function findGrupoID()
    {
        return \MZ\Product\Grupo::findByID($this->getGrupoID());
    }
}
