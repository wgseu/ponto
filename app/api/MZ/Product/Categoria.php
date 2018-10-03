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
namespace MZ\Product;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Informa qual a categoria dos produtos e permite a rápida localização dos
 * mesmos
 */
class Categoria extends SyncModel
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
     * Informa os detalhes gerais dos produtos dessa categoria
     */
    private $detalhes;
    /**
     * Informa se a categoria é destinada para produtos ou serviços
     */
    private $servico;
    /**
     * Imagem representativa da categoria
     */
    private $imagem_url;
    /**
     * Informa a ordem de exibição das categorias nas vendas
     */
    private $ordem;
    /**
     * Data de atualização das informações da categoria
     */
    private $data_atualizacao;
    /**
     * Data em que a categoria foi arquivada e não será mais usada
     */
    private $data_arquivado;

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
     * @return int id of Categoria
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Categoria
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa a categoria pai da categoria atual, a categoria atual é uma
     * subcategoria
     * @return int categoria superior of Categoria
     */
    public function getCategoriaID()
    {
        return $this->categoria_id;
    }

    /**
     * Set CategoriaID value to new on param
     * @param int $categoria_id Set categoria superior for Categoria
     * @return self Self instance
     */
    public function setCategoriaID($categoria_id)
    {
        $this->categoria_id = $categoria_id;
        return $this;
    }

    /**
     * Descrição da categoria. Ex.: Refrigerantes, Salgados
     * @return string descrição of Categoria
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Categoria
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Informa os detalhes gerais dos produtos dessa categoria
     * @return string detalhes of Categoria
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }

    /**
     * Set Detalhes value to new on param
     * @param string $detalhes Set detalhes for Categoria
     * @return self Self instance
     */
    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
        return $this;
    }

    /**
     * Informa se a categoria é destinada para produtos ou serviços
     * @return string serviço of Categoria
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
     * @param string $servico Set serviço for Categoria
     * @return self Self instance
     */
    public function setServico($servico)
    {
        $this->servico = $servico;
        return $this;
    }

    /**
     * Imagem representativa da categoria
     * @return string imagem of Categoria
     */
    public function getImagemURL()
    {
        return $this->imagem_url;
    }

    /**
     * Set ImagemURL value to new on param
     * @param string $imagem_url Set imagem for Categoria
     * @return self Self instance
     */
    public function setImagemURL($imagem_url)
    {
        $this->imagem_url = $imagem_url;
        return $this;
    }

    /**
     * Informa a ordem de exibição das categorias nas vendas
     * @return int ordem of Categoria
     */
    public function getOrdem()
    {
        return $this->ordem;
    }

    /**
     * Set Ordem value to new on param
     * @param int $ordem Set ordem for Categoria
     * @return self Self instance
     */
    public function setOrdem($ordem)
    {
        $this->ordem = $ordem;
        return $this;
    }

    /**
     * Data de atualização das informações da categoria
     * @return string data de atualização of Categoria
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
    }

    /**
     * Set DataAtualizacao value to new on param
     * @param string $data_atualizacao Set data de atualização for Categoria
     * @return self Self instance
     */
    public function setDataAtualizacao($data_atualizacao)
    {
        $this->data_atualizacao = $data_atualizacao;
        return $this;
    }

    /**
     * Data em que a categoria foi arquivada e não será mais usada
     * @return string data de arquivação of Categoria
     */
    public function getDataArquivado()
    {
        return $this->data_arquivado;
    }

    /**
     * Set DataArquivado value to new on param
     * @param string $data_arquivado Set data de arquivação for Categoria
     * @return self Self instance
     */
    public function setDataArquivado($data_arquivado)
    {
        $this->data_arquivado = $data_arquivado;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $categoria = parent::toArray($recursive);
        $categoria['id'] = $this->getID();
        $categoria['categoriaid'] = $this->getCategoriaID();
        $categoria['descricao'] = $this->getDescricao();
        $categoria['detalhes'] = $this->getDetalhes();
        $categoria['servico'] = $this->getServico();
        $categoria['imagemurl'] = $this->getImagemURL();
        $categoria['ordem'] = $this->getOrdem();
        $categoria['dataatualizacao'] = $this->getDataAtualizacao();
        $categoria['dataarquivado'] = $this->getDataArquivado();
        return $categoria;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $categoria Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($categoria = [])
    {
        if ($categoria instanceof self) {
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
        if (!array_key_exists('detalhes', $categoria)) {
            $this->setDetalhes(null);
        } else {
            $this->setDetalhes($categoria['detalhes']);
        }
        if (!isset($categoria['servico'])) {
            $this->setServico('N');
        } else {
            $this->setServico($categoria['servico']);
        }
        if (!array_key_exists('imagemurl', $categoria)) {
            $this->setImagemURL(null);
        } else {
            $this->setImagemURL($categoria['imagemurl']);
        }
        if (!isset($categoria['ordem'])) {
            $this->setOrdem(0);
        } else {
            $this->setOrdem($categoria['ordem']);
        }
        if (!isset($categoria['dataatualizacao'])) {
            $this->setDataAtualizacao(null);
        } else {
            $this->setDataAtualizacao($categoria['dataatualizacao']);
        }
        if (!array_key_exists('dataarquivado', $categoria)) {
            $this->setDataArquivado(null);
        } else {
            $this->setDataArquivado($categoria['dataarquivado']);
        }
        return $this;
    }

    /**
     * Get relative imagem path or default imagem
     * @param boolean $default If true return default image, otherwise check field
     * @param string  $default_name Default image name
     * @return string relative web path for categoria imagem
     */
    public function makeImagemURL($default = false, $default_name = 'categoria.png')
    {
        $imagem_url = $this->getImagemURL();
        if ($default) {
            $imagem_url = null;
        }
        return get_image_url($imagem_url, 'categoria', $default_name);
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $categoria = parent::publish();
        $categoria['imagemurl'] = $this->makeImagemURL(false, null);
        return $categoria;
    }

    public function isAvailable()
    {
        return Produto::count(['categoriaid' => $this->getID(), 'visivel' => 'Y']) > 0;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setCategoriaID(Filter::number($this->getCategoriaID()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setDetalhes(Filter::string($this->getDetalhes()));
        $imagem_url = upload_image('raw_imagemurl', 'categoria', null, 256, 256);
        if (is_null($imagem_url) && trim($this->getImagemURL()) != '') {
            $this->setImagemURL($original->getImagemURL());
        } else {
            $this->setImagemURL($imagem_url);
        }
        $this->setOrdem(Filter::number($this->getOrdem()));
        $this->setDataArquivado(Filter::datetime($this->getDataArquivado()));
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
        if (!is_null($this->getImagemURL()) && $dependency->getImagemURL() != $this->getImagemURL()) {
            @unlink(get_image_path($this->getImagemURL(), 'categoria'));
        }
        $this->setImagemURL($dependency->getImagemURL());
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Categoria in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (!is_null($this->getCategoriaID())) {
            $categoriapai = $this->findCategoriaID();
            if (!$categoriapai->exists()) {
                $errors['categoriaid'] = _t('categoria.categoriapai_not_found');
            } elseif (!is_null($categoriapai->getCategoriaID())) {
                $errors['categoriaid'] = _t('categoria.categoriapai_already');
            } elseif ($categoriapai->getID() == $this->getID()) {
                $errors['categoriaid'] = _t('categoria.categoriapai_same');
            }
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = _t('categoria.descricao_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getServico())) {
            $errors['servico'] = _t('categoria.servico_invalid');
        }
        if (is_null($this->getOrdem())) {
            $errors['ordem'] = _t('categoria.ordem_cannot_empty');
        }
        $this->setDataAtualizacao(DB::now());
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Translate SQL exception into application exception
     * @param \Exception $e exception to translate into a readable error
     * @return \MZ\Exception\ValidationException new exception translated
     */
    protected function translate($e)
    {
        if (contains(['Descricao', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'descricao' => _t(
                    'categoria.descricao_used',
                    $this->getDescricao()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Categoria into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Categorias')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Categoria with instance values into database for ID
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('categoria.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Categorias')
                ->set($values)
                ->where(['id' => $this->getID()])
                ->execute();
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $affected;
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     * @throws \MZ\Exception\ValidationException for invalid id
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('categoria.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Categorias')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param array $condition Condition for searching the row
     * @param array $order associative field name -> [-1, 1]
     * @return self Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, Descricao
     * @return self Self filled instance or empty when not found
     */
    public function loadByDescricao()
    {
        return $this->load([
            'descricao' => strval($this->getDescricao()),
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
        $categoria = new self();
        $allowed = Filter::concatKeys('c.', $categoria->toArray());
        return $allowed;
    }

    /**
     * Filter order array
     * @param mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $allowed = self::getAllowedKeys();
        $order = Filter::order($order);
        if (isset($order['vendas'])) {
            $field = 'SUM(i.quantidade)';
            $order = replace_key($order, 'vendas', $field);
            $allowed[$field] = true;
        }
        return Filter::orderBy($order, $allowed, 'c.');
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
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
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Categorias c');
        $order = Filter::order($order);
        if (isset($condition['disponivel']) || isset($order['vendas'])) {
            $query = $query->leftJoin('Produtos p ON p.categoriaid = c.id AND p.visivel = "Y"');
            $query = $query->groupBy('c.id');
        }
        if (isset($condition['disponivel'])) {
            $disponivel = $condition['disponivel'];
            $query = $query->having('(CASE WHEN COUNT(p.id) > 0 THEN "Y" ELSE "N" END) = ?', $disponivel);
        }
        if (isset($order['vendas'])) {
            $query = $query->leftJoin(
                'Itens i ON i.produtoid = p.id AND i.datahora > ?',
                DB::now('-1 month')
            );
        }
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('c.descricao ASC');
        $query = $query->orderBy('c.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Categoria or empty instance
     */
    public static function find($condition, $order = [])
    {
        $result = new self();
        return $result->load($condition, $order);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Categoria or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('categoria.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find this object on database using, Descricao
     * @param string $descricao descrição to find Categoria
     * @return self A filled instance or empty when not found
     */
    public static function findByDescricao($descricao)
    {
        $result = new self();
        $result->setDescricao($descricao);
        return $result->loadByDescricao();
    }

    /**
     * Find all Categoria
     * @param array  $condition Condition to get all Categoria
     * @param array  $order     Order Categoria
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Categoria
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
            $result[] = new self($row);
        }
        return $result;
    }

    /**
     * Count all rows from database with matched condition critery
     * @param array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        $query = $query->select(null)->groupBy(null)->select('COUNT(DISTINCT c.id)');
        return (int) $query->fetchColumn();
    }
}
