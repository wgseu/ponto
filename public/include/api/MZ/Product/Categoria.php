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
 * Informa qual a categoria dos produtos e permite a rápida localização dos
 * mesmos
 */
class Categoria extends \MZ\Database\Helper
{

    /**
     * Identificador da categoria
     */
    private $id;
    /**
     * Informa a categoria pai da categoria atual, a categoria atual é uma
     * subcategoria
     */
    private $categoria_id;
    /**
     * Descrição da categoria. Ex.: Refrigerantes, Salgados
     */
    private $descricao;
    /**
     * Informa se a categoria é destinada para produtos ou serviços
     */
    private $servico;
    /**
     * Imagem representativa da categoria
     */
    private $imagem;
    /**
     * Data de atualização das informações da categoria
     */
    private $data_atualizacao;

    /**
     * Constructor for a new empty instance of Categoria
     * @param array $categoria All field and values to fill the instance
     */
    public function __construct($categoria = [])
    {
        parent::__construct($categoria);
    }

    /**
     * Identificador da categoria
     * @return mixed ID of Categoria
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Categoria Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa a categoria pai da categoria atual, a categoria atual é uma
     * subcategoria
     * @return mixed Categoria superior of Categoria
     */
    public function getCategoriaID()
    {
        return $this->categoria_id;
    }

    /**
     * Set CategoriaID value to new on param
     * @param  mixed $categoria_id new value for CategoriaID
     * @return Categoria Self instance
     */
    public function setCategoriaID($categoria_id)
    {
        $this->categoria_id = $categoria_id;
        return $this;
    }

    /**
     * Descrição da categoria. Ex.: Refrigerantes, Salgados
     * @return mixed Descrição of Categoria
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Categoria Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Informa se a categoria é destinada para produtos ou serviços
     * @return mixed Serviço of Categoria
     */
    public function getServico()
    {
        return $this->servico;
    }

    /**
     * Informa se a categoria é destinada para produtos ou serviços
     * @return boolean Check if o of Servico is selected or checked
     */
    public function isServico()
    {
        return $this->servico == 'Y';
    }

    /**
     * Set Servico value to new on param
     * @param  mixed $servico new value for Servico
     * @return Categoria Self instance
     */
    public function setServico($servico)
    {
        $this->servico = $servico;
        return $this;
    }

    /**
     * Imagem representativa da categoria
     * @return mixed Imagem of Categoria
     */
    public function getImagem()
    {
        return $this->imagem;
    }

    /**
     * Set Imagem value to new on param
     * @param  mixed $imagem new value for Imagem
     * @return Categoria Self instance
     */
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
        return $this;
    }

    /**
     * Data de atualização das informações da categoria
     * @return mixed Data de atualização of Categoria
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
    }

    /**
     * Set DataAtualizacao value to new on param
     * @param  mixed $data_atualizacao new value for DataAtualizacao
     * @return Categoria Self instance
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
        $categoria = parent::toArray($recursive);
        $categoria['id'] = $this->getID();
        $categoria['categoriaid'] = $this->getCategoriaID();
        $categoria['descricao'] = $this->getDescricao();
        $categoria['servico'] = $this->getServico();
        $categoria['imagem'] = $this->getImagem();
        $categoria['dataatualizacao'] = $this->getDataAtualizacao();
        return $categoria;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $categoria Associated key -> value to assign into this instance
     * @return Categoria Self instance
     */
    public function fromArray($categoria = [])
    {
        if ($categoria instanceof Categoria) {
            $categoria = $categoria->toArray();
        } elseif (!is_array($categoria)) {
            $categoria = [];
        }
        parent::fromArray($categoria);
        if (!isset($categoria['id'])) {
            $this->setID(null);
        } else {
            $this->setID($categoria['id']);
        }
        if (!array_key_exists('categoriaid', $categoria)) {
            $this->setCategoriaID(null);
        } else {
            $this->setCategoriaID($categoria['categoriaid']);
        }
        if (!isset($categoria['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($categoria['descricao']);
        }
        if (!isset($categoria['servico'])) {
            $this->setServico(null);
        } else {
            $this->setServico($categoria['servico']);
        }
        if (!array_key_exists('imagem', $categoria)) {
            $this->setImagem(null);
        } else {
            $this->setImagem($categoria['imagem']);
        }
        if (!isset($categoria['dataatualizacao'])) {
            $this->setDataAtualizacao(null);
        } else {
            $this->setDataAtualizacao($categoria['dataatualizacao']);
        }
        return $this;
    }

    /**
     * Get relative imagem path or default imagem
     * @param boolean $default If true return default image, otherwise check field
     * @return string relative web path for categoria imagem
     */
    public function makeImagem($default = false)
    {
        $imagem = $this->getImagem();
        if ($default) {
            $imagem = null;
        }
        return get_image_url($imagem, 'categoria', 'categoria.png');
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $categoria = parent::publish();
        $categoria['imagem'] = $this->makeImagem();
        return $categoria;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Categoria $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setCategoriaID(Filter::number($this->getCategoriaID()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $imagem = upload_image('raw_imagem', 'categoria');
        if (is_null($imagem) && trim($this->getImagem()) != '') {
            $this->setImagem($original->getImagem());
        } else {
            $this->setImagem($imagem);
        }
        $this->setDataAtualizacao(Filter::datetime($this->getDataAtualizacao()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Categoria $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
        if (!is_null($this->getImagem()) && $dependency->getImagem() != $this->getImagem()) {
            @unlink(get_image_path($this->getImagem(), 'categoria'));
        }
        $this->setImagem($dependency->getImagem());
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Categoria in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = 'A descrição não pode ser vazia';
        }
        if (is_null($this->getServico())) {
            $errors['servico'] = 'O serviço não pode ser vazio';
        }
        if (!Validator::checkBoolean($this->getServico(), true)) {
            $errors['servico'] = 'O serviço é inválido';
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
        if (stripos($e->getMessage(), 'Descricao_UNIQUE') !== false) {
            return new \MZ\Exception\ValidationException([
                'descricao' => sprintf(
                    'A descrição "%s" já está cadastrada',
                    $this->getDescricao()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Categoria into the database and fill instance from database
     * @return Categoria Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Categorias')->values($values)->execute();
            $categoria = self::findByID($id);
            $this->fromArray($categoria->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Categoria with instance values into database for ID
     * @return Categoria Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da categoria não foi informado');
        }
        unset($values['id']);
        try {
            self::getDB()
                ->update('Categorias')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $categoria = self::findByID($this->getID());
            $this->fromArray($categoria->toArray());
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
            throw new \Exception('O identificador da categoria não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Categorias')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Categoria Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, ID
     * @param  int $id id to find Categoria
     * @return Categoria Self filled instance or empty when not found
     */
    public function loadByID($id)
    {
        return $this->load([
            'id' => intval($id),
        ]);
    }

    /**
     * Load into this object from database using, Descricao
     * @param  string $descricao descrição to find Categoria
     * @return Categoria Self filled instance or empty when not found
     */
    public function loadByDescricao($descricao)
    {
        return $this->load([
            'descricao' => strval($descricao),
        ]);
    }

    /**
     * Informa a categoria pai da categoria atual, a categoria atual é uma
     * subcategoria
     * @return \MZ\Product\Categoria The object fetched from database
     */
    public function findCategoriaID()
    {
        if (is_null($this->getCategoriaID())) {
            return new \MZ\Product\Categoria();
        }
        return \MZ\Product\Categoria::findByID($this->getCategoriaID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $categoria = new Categoria();
        $allowed = Filter::concatKeys('c.', $categoria->toArray());
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
        return Filter::orderBy($order, $allowed, 'c.');
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
            $field = 'c.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'c.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Categorias c');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('c.descricao ASC');
        $query = $query->orderBy('c.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Categoria A filled Categoria or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Categoria($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Categoria
     * @return Categoria A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, Descricao
     * @param  string $descricao descrição to find Categoria
     * @return Categoria A filled instance or empty when not found
     */
    public static function findByDescricao($descricao)
    {
        return self::find([
            'descricao' => strval($descricao),
        ]);
    }

    /**
     * Find all Categoria
     * @param  array  $condition Condition to get all Categoria
     * @param  array  $order     Order Categoria
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Categoria
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
            $result[] = new Categoria($row);
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
