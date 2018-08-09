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
namespace MZ\Environment;

use MZ\Database\Model;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Informa detalhadamente um bem da empresa
 */
class Patrimonio extends Model
{

    /**
     * Estado de conservação do bem
     */
    const ESTADO_NOVO = 'Novo';
    const ESTADO_CONSERVADO = 'Conservado';
    const ESTADO_RUIM = 'Ruim';

    /**
     * Identificador do bem
     */
    private $id;
    /**
     * Empresa a que esse bem pertence
     */
    private $empresa_id;
    /**
     * Fornecedor do bem
     */
    private $fornecedor_id;
    /**
     * Número que identifica o bem
     */
    private $numero;
    /**
     * Descrição ou nome do bem
     */
    private $descricao;
    /**
     * Quantidade do bem com as mesmas características
     */
    private $quantidade;
    /**
     * Altura do bem em metros
     */
    private $altura;
    /**
     * Largura do bem em metros
     */
    private $largura;
    /**
     * Comprimento do bem em metros
     */
    private $comprimento;
    /**
     * Estado de conservação do bem
     */
    private $estado;
    /**
     * Valor de custo do bem
     */
    private $custo;
    /**
     * Valor que o bem vale atualmente
     */
    private $valor;
    /**
     * Informa se o bem está ativo e em uso
     */
    private $ativo;
    /**
     * Caminho relativo da foto do bem
     */
    private $imagem_anexada;
    /**
     * Data de atualização das informações do bem
     */
    private $data_atualizacao;

    /**
     * Constructor for a new empty instance of Patrimonio
     * @param array $patrimonio All field and values to fill the instance
     */
    public function __construct($patrimonio = [])
    {
        parent::__construct($patrimonio);
    }

    /**
     * Identificador do bem
     * @return mixed ID of Patrimonio
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Patrimonio Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Empresa a que esse bem pertence
     * @return mixed Empresa of Patrimonio
     */
    public function getEmpresaID()
    {
        return $this->empresa_id;
    }

    /**
     * Set EmpresaID value to new on param
     * @param  mixed $empresa_id new value for EmpresaID
     * @return Patrimonio Self instance
     */
    public function setEmpresaID($empresa_id)
    {
        $this->empresa_id = $empresa_id;
        return $this;
    }

    /**
     * Fornecedor do bem
     * @return mixed Fornecedor of Patrimonio
     */
    public function getFornecedorID()
    {
        return $this->fornecedor_id;
    }

    /**
     * Set FornecedorID value to new on param
     * @param  mixed $fornecedor_id new value for FornecedorID
     * @return Patrimonio Self instance
     */
    public function setFornecedorID($fornecedor_id)
    {
        $this->fornecedor_id = $fornecedor_id;
        return $this;
    }

    /**
     * Número que identifica o bem
     * @return mixed Número of Patrimonio
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set Numero value to new on param
     * @param  mixed $numero new value for Numero
     * @return Patrimonio Self instance
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * Descrição ou nome do bem
     * @return mixed Descrição of Patrimonio
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Patrimonio Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Quantidade do bem com as mesmas características
     * @return mixed Quantidade of Patrimonio
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set Quantidade value to new on param
     * @param  mixed $quantidade new value for Quantidade
     * @return Patrimonio Self instance
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
        return $this;
    }

    /**
     * Altura do bem em metros
     * @return mixed Altura of Patrimonio
     */
    public function getAltura()
    {
        return $this->altura;
    }

    /**
     * Set Altura value to new on param
     * @param  mixed $altura new value for Altura
     * @return Patrimonio Self instance
     */
    public function setAltura($altura)
    {
        $this->altura = $altura;
        return $this;
    }

    /**
     * Largura do bem em metros
     * @return mixed Largura of Patrimonio
     */
    public function getLargura()
    {
        return $this->largura;
    }

    /**
     * Set Largura value to new on param
     * @param  mixed $largura new value for Largura
     * @return Patrimonio Self instance
     */
    public function setLargura($largura)
    {
        $this->largura = $largura;
        return $this;
    }

    /**
     * Comprimento do bem em metros
     * @return mixed Comprimento of Patrimonio
     */
    public function getComprimento()
    {
        return $this->comprimento;
    }

    /**
     * Set Comprimento value to new on param
     * @param  mixed $comprimento new value for Comprimento
     * @return Patrimonio Self instance
     */
    public function setComprimento($comprimento)
    {
        $this->comprimento = $comprimento;
        return $this;
    }

    /**
     * Estado de conservação do bem
     * @return mixed Estado of Patrimonio
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set Estado value to new on param
     * @param  mixed $estado new value for Estado
     * @return Patrimonio Self instance
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * Valor de custo do bem
     * @return mixed Custo of Patrimonio
     */
    public function getCusto()
    {
        return $this->custo;
    }

    /**
     * Set Custo value to new on param
     * @param  mixed $custo new value for Custo
     * @return Patrimonio Self instance
     */
    public function setCusto($custo)
    {
        $this->custo = $custo;
        return $this;
    }

    /**
     * Valor que o bem vale atualmente
     * @return mixed Valor of Patrimonio
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param  mixed $valor new value for Valor
     * @return Patrimonio Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Informa se o bem está ativo e em uso
     * @return mixed Ativo of Patrimonio
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Informa se o bem está ativo e em uso
     * @return boolean Check if o of Ativo is selected or checked
     */
    public function isAtivo()
    {
        return $this->ativo == 'Y';
    }

    /**
     * Set Ativo value to new on param
     * @param  mixed $ativo new value for Ativo
     * @return Patrimonio Self instance
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * Caminho relativo da foto do bem
     * @return mixed Foto do bem of Patrimonio
     */
    public function getImagemAnexada()
    {
        return $this->imagem_anexada;
    }

    /**
     * Set ImagemAnexada value to new on param
     * @param  mixed $imagem_anexada new value for ImagemAnexada
     * @return Patrimonio Self instance
     */
    public function setImagemAnexada($imagem_anexada)
    {
        $this->imagem_anexada = $imagem_anexada;
        return $this;
    }

    /**
     * Data de atualização das informações do bem
     * @return mixed Data de atualização of Patrimonio
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
    }

    /**
     * Set DataAtualizacao value to new on param
     * @param  mixed $data_atualizacao new value for DataAtualizacao
     * @return Patrimonio Self instance
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
        $patrimonio = parent::toArray($recursive);
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

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $patrimonio Associated key -> value to assign into this instance
     * @return Patrimonio Self instance
     */
    public function fromArray($patrimonio = [])
    {
        if ($patrimonio instanceof Patrimonio) {
            $patrimonio = $patrimonio->toArray();
        } elseif (!is_array($patrimonio)) {
            $patrimonio = [];
        }
        parent::fromArray($patrimonio);
        if (!isset($patrimonio['id'])) {
            $this->setID(null);
        } else {
            $this->setID($patrimonio['id']);
        }
        if (!isset($patrimonio['empresaid'])) {
            $this->setEmpresaID(null);
        } else {
            $this->setEmpresaID($patrimonio['empresaid']);
        }
        if (!array_key_exists('fornecedorid', $patrimonio)) {
            $this->setFornecedorID(null);
        } else {
            $this->setFornecedorID($patrimonio['fornecedorid']);
        }
        if (!isset($patrimonio['numero'])) {
            $this->setNumero(null);
        } else {
            $this->setNumero($patrimonio['numero']);
        }
        if (!isset($patrimonio['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($patrimonio['descricao']);
        }
        if (!isset($patrimonio['quantidade'])) {
            $this->setQuantidade(null);
        } else {
            $this->setQuantidade($patrimonio['quantidade']);
        }
        if (!isset($patrimonio['altura'])) {
            $this->setAltura(null);
        } else {
            $this->setAltura($patrimonio['altura']);
        }
        if (!isset($patrimonio['largura'])) {
            $this->setLargura(null);
        } else {
            $this->setLargura($patrimonio['largura']);
        }
        if (!isset($patrimonio['comprimento'])) {
            $this->setComprimento(null);
        } else {
            $this->setComprimento($patrimonio['comprimento']);
        }
        if (!isset($patrimonio['estado'])) {
            $this->setEstado(null);
        } else {
            $this->setEstado($patrimonio['estado']);
        }
        if (!isset($patrimonio['custo'])) {
            $this->setCusto(null);
        } else {
            $this->setCusto($patrimonio['custo']);
        }
        if (!isset($patrimonio['valor'])) {
            $this->setValor(null);
        } else {
            $this->setValor($patrimonio['valor']);
        }
        if (!isset($patrimonio['ativo'])) {
            $this->setAtivo('N');
        } else {
            $this->setAtivo($patrimonio['ativo']);
        }
        if (!array_key_exists('imagemanexada', $patrimonio)) {
            $this->setImagemAnexada(null);
        } else {
            $this->setImagemAnexada($patrimonio['imagemanexada']);
        }
        if (!isset($patrimonio['dataatualizacao'])) {
            $this->setDataAtualizacao(DB::now());
        } else {
            $this->setDataAtualizacao($patrimonio['dataatualizacao']);
        }
        return $this;
    }

    /**
     * Get relative foto do bem path or default foto do bem
     * @param boolean $default If true return default image, otherwise check field
     * @return string relative web path for patrimônio foto do bem
     */
    public function makeImagemAnexada($default = false)
    {
        $imagem_anexada = $this->getImagemAnexada();
        if ($default) {
            $imagem_anexada = null;
        }
        return get_image_url($imagem_anexada, 'patrimonio', 'patrimonio.png');
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $patrimonio = parent::publish();
        $patrimonio['imagemanexada'] = $this->makeImagemAnexada();
        return $patrimonio;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Patrimonio $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setEmpresaID(Filter::number($this->getEmpresaID()));
        $this->setFornecedorID(Filter::number($this->getFornecedorID()));
        $this->setNumero(Filter::string($this->getNumero()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setQuantidade(Filter::float($this->getQuantidade()));
        $this->setAltura(Filter::float($this->getAltura()));
        $this->setLargura(Filter::float($this->getLargura()));
        $this->setComprimento(Filter::float($this->getComprimento()));
        $this->setCusto(Filter::money($this->getCusto()));
        $this->setValor(Filter::money($this->getValor()));
        $imagem_anexada = upload_image('raw_imagemanexada', 'patrimonio');
        if (is_null($imagem_anexada) && trim($this->getImagemAnexada()) != '') {
            $this->setImagemAnexada($original->getImagemAnexada());
        } else {
            $this->setImagemAnexada($imagem_anexada);
        }
        $this->setDataAtualizacao(DB::now());
    }

    /**
     * Clean instance resources like images and docs
     * @param  Patrimonio $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
        if (!is_null($this->getImagemAnexada()) && $dependency->getImagemAnexada() != $this->getImagemAnexada()) {
            @unlink(get_image_path($this->getImagemAnexada(), 'patrimonio'));
        }
        $this->setImagemAnexada($dependency->getImagemAnexada());
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Patrimonio in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getEmpresaID())) {
            $errors['empresaid'] = 'A empresa não pode ser vazia';
        }
        if (is_null($this->getNumero())) {
            $errors['numero'] = 'O número não pode ser vazio';
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = 'A descrição não pode ser vazia';
        }
        if (is_null($this->getQuantidade())) {
            $errors['quantidade'] = 'A quantidade não pode ser vazia';
        } elseif ($this->getQuantidade() < 1) {
            $errors['quantidade'] = 'A quantidade não pode ser nula ou negativa';
        }
        if (is_null($this->getAltura())) {
            $errors['altura'] = 'A altura não pode ser vazia';
        } elseif ($this->getAltura() < 0) {
            $errors['altura'] = 'A altura não pode ser nula ou negativa';
        }
        if (is_null($this->getLargura())) {
            $errors['largura'] = 'A largura não pode ser vazia';
        } elseif ($this->getLargura() < 0) {
            $errors['largura'] = 'A largura não pode ser nula ou negativa';
        }
        if (is_null($this->getComprimento())) {
            $errors['comprimento'] = 'O comprimento não pode ser vazio';
        } elseif ($this->getComprimento() < 0) {
            $errors['comprimento'] = 'O comprimento não pode ser nulo ou negativo';
        }
        if (!Validator::checkInSet($this->getEstado(), self::getEstadoOptions())) {
            $errors['estado'] = 'O estado é inválido';
        }
        if (is_null($this->getCusto())) {
            $errors['custo'] = 'O custo não pode ser vazio';
        } elseif ($this->getCusto() < 0) {
            $errors['custo'] = 'O custo não pode ser nulo ou negativo';
        }
        if (is_null($this->getValor())) {
            $errors['valor'] = 'O valor não pode ser vazio';
        } elseif ($this->getValor() < 0) {
            $errors['valor'] = 'O valor não pode ser negativo';
        }
        if (!Validator::checkBoolean($this->getAtivo())) {
            $errors['ativo'] = 'O ativo é inválido';
        }
        $this->setDataAtualizacao(DB::now());
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
        if (contains(['Numero', 'Estado', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'numero' => sprintf(
                    'O número "%s" já está cadastrado',
                    $this->getNumero()
                ),
                'estado' => sprintf(
                    'O estado "%s" já está cadastrado',
                    $this->getEstado()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Patrimônio into the database and fill instance from database
     * @return Patrimonio Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Patrimonios')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Patrimônio with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Patrimonio Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do patrimônio não foi informado');
        }
        $values = DB::filterValues($values, $only, $except);
        try {
            DB::update('Patrimonios')
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
            throw new \Exception('O identificador do patrimônio não foi informado');
        }
        $result = DB::deleteFrom('Patrimonios')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Patrimonio Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, Numero, Estado
     * @param  string $numero número to find Patrimônio
     * @param  string $estado estado to find Patrimônio
     * @return Patrimonio Self filled instance or empty when not found
     */
    public function loadByNumeroEstado($numero, $estado)
    {
        return $this->load([
            'numero' => strval($numero),
            'estado' => strval($estado),
        ]);
    }

    /**
     * Empresa a que esse bem pertence
     * @return \MZ\Account\Cliente The object fetched from database
     */
    public function findEmpresaID()
    {
        return \MZ\Account\Cliente::findByID($this->getEmpresaID());
    }

    /**
     * Fornecedor do bem
     * @return \MZ\Stock\Fornecedor The object fetched from database
     */
    public function findFornecedorID()
    {
        if (is_null($this->getFornecedorID())) {
            return new \MZ\Stock\Fornecedor();
        }
        return \MZ\Stock\Fornecedor::findByID($this->getFornecedorID());
    }

    /**
     * Gets textual and translated Estado for Patrimonio
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getEstadoOptions($index = null)
    {
        $options = [
            self::ESTADO_NOVO => 'Novo',
            self::ESTADO_CONSERVADO => 'Conservado',
            self::ESTADO_RUIM => 'Ruim',
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
        $patrimonio = new Patrimonio();
        $allowed = Filter::concatKeys('p.', $patrimonio->toArray());
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
            $field = 'p.descricao LIKE ?';
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
        $query = DB::from('Patrimonios p');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('p.descricao ASC');
        $query = $query->orderBy('p.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Patrimonio A filled Patrimônio or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Patrimonio($row);
    }

    /**
     * Find this object on database using, Numero, Estado
     * @param  string $numero número to find Patrimônio
     * @param  string $estado estado to find Patrimônio
     * @return Patrimonio A filled instance or empty when not found
     */
    public static function findByNumeroEstado($numero, $estado)
    {
        $result = new self();
        return $result->loadByNumeroEstado($numero, $estado);
    }

    /**
     * Find all Patrimônio
     * @param  array  $condition Condition to get all Patrimônio
     * @param  array  $order     Order Patrimônio
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Patrimonio
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
            $result[] = new Patrimonio($row);
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
