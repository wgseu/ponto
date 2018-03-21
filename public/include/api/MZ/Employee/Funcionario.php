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
namespace MZ\Employee;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Funcionário que trabalha na empresa e possui uma determinada função
 */
class Funcionario extends \MZ\Database\Helper
{

    /**
     * Código do funcionário
     */
    private $id;
    /**
     * Função do funcionário na empresa
     */
    private $funcao_id;
    /**
     * Cliente que representa esse funcionário, único no cadastro de
     * funcionários
     */
    private $cliente_id;
    /**
     * Código de barras utilizado pelo funcionário para autorizar uma operação
     * no sistema
     */
    private $codigo_barras;
    /**
     * Porcentagem cobrada pelo funcionário ao cliente, Ex.: Comissão de 10%
     */
    private $porcentagem;
    /**
     * Código da linguagem utilizada pelo funcionário para visualizar o
     * programa e o site
     */
    private $linguagem_id;
    /**
     * Define a distribuição da porcentagem pela parcela de pontos
     */
    private $pontuacao;
    /**
     * Informa se o funcionário está ativo na empresa
     */
    private $ativo;
    /**
     * Data de saída do funcionário, informado apenas quando ativo for não
     */
    private $data_saida;
    /**
     * Data em que o funcionário foi cadastrado no sistema
     */
    private $data_cadastro;

    /**
     * Constructor for a new empty instance of Funcionario
     * @param array $funcionario All field and values to fill the instance
     */
    public function __construct($funcionario = [])
    {
        parent::__construct($funcionario);
    }

    /**
     * Código do funcionário
     * @return mixed Código of Funcionario
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Funcionario Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Função do funcionário na empresa
     * @return mixed Função of Funcionario
     */
    public function getFuncaoID()
    {
        return $this->funcao_id;
    }

    /**
     * Set FuncaoID value to new on param
     * @param  mixed $funcao_id new value for FuncaoID
     * @return Funcionario Self instance
     */
    public function setFuncaoID($funcao_id)
    {
        $this->funcao_id = $funcao_id;
        return $this;
    }

    /**
     * Cliente que representa esse funcionário, único no cadastro de
     * funcionários
     * @return mixed Cliente of Funcionario
     */
    public function getClienteID()
    {
        return $this->cliente_id;
    }

    /**
     * Set ClienteID value to new on param
     * @param  mixed $cliente_id new value for ClienteID
     * @return Funcionario Self instance
     */
    public function setClienteID($cliente_id)
    {
        $this->cliente_id = $cliente_id;
        return $this;
    }

    /**
     * Código de barras utilizado pelo funcionário para autorizar uma operação
     * no sistema
     * @return mixed Código de barras of Funcionario
     */
    public function getCodigoBarras()
    {
        return $this->codigo_barras;
    }

    /**
     * Set CodigoBarras value to new on param
     * @param  mixed $codigo_barras new value for CodigoBarras
     * @return Funcionario Self instance
     */
    public function setCodigoBarras($codigo_barras)
    {
        $this->codigo_barras = $codigo_barras;
        return $this;
    }

    /**
     * Porcentagem cobrada pelo funcionário ao cliente, Ex.: Comissão de 10%
     * @return mixed Comissão of Funcionario
     */
    public function getPorcentagem()
    {
        return $this->porcentagem;
    }

    /**
     * Set Porcentagem value to new on param
     * @param  mixed $porcentagem new value for Porcentagem
     * @return Funcionario Self instance
     */
    public function setPorcentagem($porcentagem)
    {
        $this->porcentagem = $porcentagem;
        return $this;
    }

    /**
     * Código da linguagem utilizada pelo funcionário para visualizar o
     * programa e o site
     * @return mixed Linguagem of Funcionario
     */
    public function getLinguagemID()
    {
        return $this->linguagem_id;
    }

    /**
     * Set LinguagemID value to new on param
     * @param  mixed $linguagem_id new value for LinguagemID
     * @return Funcionario Self instance
     */
    public function setLinguagemID($linguagem_id)
    {
        $this->linguagem_id = $linguagem_id;
        return $this;
    }

    /**
     * Define a distribuição da porcentagem pela parcela de pontos
     * @return mixed Pontuação of Funcionario
     */
    public function getPontuacao()
    {
        return $this->pontuacao;
    }

    /**
     * Set Pontuacao value to new on param
     * @param  mixed $pontuacao new value for Pontuacao
     * @return Funcionario Self instance
     */
    public function setPontuacao($pontuacao)
    {
        $this->pontuacao = $pontuacao;
        return $this;
    }

    /**
     * Informa se o funcionário está ativo na empresa
     * @return mixed Ativo of Funcionario
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Informa se o funcionário está ativo na empresa
     * @return boolean Check if o of Ativo is selected or checked
     */
    public function isAtivo()
    {
        return $this->ativo == 'Y';
    }

    /**
     * Set Ativo value to new on param
     * @param  mixed $ativo new value for Ativo
     * @return Funcionario Self instance
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * Data de saída do funcionário, informado apenas quando ativo for não
     * @return mixed Data de saída of Funcionario
     */
    public function getDataSaida()
    {
        return $this->data_saida;
    }

    /**
     * Set DataSaida value to new on param
     * @param  mixed $data_saida new value for DataSaida
     * @return Funcionario Self instance
     */
    public function setDataSaida($data_saida)
    {
        $this->data_saida = $data_saida;
        return $this;
    }

    /**
     * Data em que o funcionário foi cadastrado no sistema
     * @return mixed Data de cadastro of Funcionario
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    /**
     * Set DataCadastro value to new on param
     * @param  mixed $data_cadastro new value for DataCadastro
     * @return Funcionario Self instance
     */
    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $funcionario = parent::toArray($recursive);
        $funcionario['id'] = $this->getID();
        $funcionario['funcaoid'] = $this->getFuncaoID();
        $funcionario['clienteid'] = $this->getClienteID();
        $funcionario['codigobarras'] = $this->getCodigoBarras();
        $funcionario['porcentagem'] = $this->getPorcentagem();
        $funcionario['linguagemid'] = $this->getLinguagemID();
        $funcionario['pontuacao'] = $this->getPontuacao();
        $funcionario['ativo'] = $this->getAtivo();
        $funcionario['datasaida'] = $this->getDataSaida();
        $funcionario['datacadastro'] = $this->getDataCadastro();
        return $funcionario;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $funcionario Associated key -> value to assign into this instance
     * @return Funcionario Self instance
     */
    public function fromArray($funcionario = [])
    {
        if ($funcionario instanceof Funcionario) {
            $funcionario = $funcionario->toArray();
        } elseif (!is_array($funcionario)) {
            $funcionario = [];
        }
        parent::fromArray($funcionario);
        if (!isset($funcionario['id'])) {
            $this->setID(null);
        } else {
            $this->setID($funcionario['id']);
        }
        if (!isset($funcionario['funcaoid'])) {
            $this->setFuncaoID(null);
        } else {
            $this->setFuncaoID($funcionario['funcaoid']);
        }
        if (!isset($funcionario['clienteid'])) {
            $this->setClienteID(null);
        } else {
            $this->setClienteID($funcionario['clienteid']);
        }
        if (!array_key_exists('codigobarras', $funcionario)) {
            $this->setCodigoBarras(null);
        } else {
            $this->setCodigoBarras($funcionario['codigobarras']);
        }
        if (!isset($funcionario['porcentagem'])) {
            $this->setPorcentagem(null);
        } else {
            $this->setPorcentagem($funcionario['porcentagem']);
        }
        if (!isset($funcionario['linguagemid'])) {
            $this->setLinguagemID(null);
        } else {
            $this->setLinguagemID($funcionario['linguagemid']);
        }
        if (!isset($funcionario['pontuacao'])) {
            $this->setPontuacao(null);
        } else {
            $this->setPontuacao($funcionario['pontuacao']);
        }
        if (!isset($funcionario['ativo'])) {
            $this->setAtivo(null);
        } else {
            $this->setAtivo($funcionario['ativo']);
        }
        if (!array_key_exists('datasaida', $funcionario)) {
            $this->setDataSaida(null);
        } else {
            $this->setDataSaida($funcionario['datasaida']);
        }
        if (!isset($funcionario['datacadastro'])) {
            $this->setDataCadastro(null);
        } else {
            $this->setDataCadastro($funcionario['datacadastro']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $funcionario = parent::publish();
        return $funcionario;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Funcionario $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setFuncaoID(Filter::number($this->getFuncaoID()));
        $this->setClienteID(Filter::number($this->getClienteID()));
        $this->setCodigoBarras(Filter::string($this->getCodigoBarras()));
        $this->setPorcentagem(Filter::float($this->getPorcentagem()));
        $this->setLinguagemID(Filter::number($this->getLinguagemID()));
        $this->setPontuacao(Filter::number($this->getPontuacao()));
        $this->setDataSaida(Filter::datetime($this->getDataSaida()));
        $this->setDataCadastro(Filter::datetime($this->getDataCadastro()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Funcionario $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Funcionario in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getFuncaoID())) {
            $errors['funcaoid'] = 'A função não pode ser vazia';
        }
        if (is_null($this->getClienteID())) {
            $errors['clienteid'] = 'O cliente não pode ser vazio';
        }
        if (is_null($this->getPorcentagem())) {
            $errors['porcentagem'] = 'A comissão não pode ser vazia';
        }
        if (is_null($this->getLinguagemID())) {
            $errors['linguagemid'] = 'A linguagem não pode ser vazia';
        }
        if (is_null($this->getPontuacao())) {
            $errors['pontuacao'] = 'A pontuação não pode ser vazia';
        }
        if (is_null($this->getAtivo())) {
            $errors['ativo'] = 'O ativo não pode ser vazio';
        }
        if (!is_null($this->getAtivo()) &&
            !array_key_exists($this->getAtivo(), self::getBooleanOptions())
        ) {
            $errors['ativo'] = 'O ativo é inválido';
        }
        if (is_null($this->getDataCadastro())) {
            $errors['datacadastro'] = 'A data de cadastro não pode ser vazia';
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
                    'O código "%s" já está cadastrado',
                    $this->getID()
                ),
            ]);
        }
        if (stripos($e->getMessage(), 'UK_ClienteID') !== false) {
            return new \MZ\Exception\ValidationException([
                'clienteid' => sprintf(
                    'O cliente "%s" já está cadastrado',
                    $this->getClienteID()
                ),
            ]);
        }
        if (stripos($e->getMessage(), 'CodigoBarras_UNIQUE') !== false) {
            return new \MZ\Exception\ValidationException([
                'codigobarras' => sprintf(
                    'O código de barras "%s" já está cadastrado',
                    $this->getCodigoBarras()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id código to find Funcionário
     * @return Funcionario A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, ClienteID
     * @param  int $cliente_id cliente to find Funcionário
     * @return Funcionario A filled instance or empty when not found
     */
    public static function findByClienteID($cliente_id)
    {
        return self::find([
            'clienteid' => intval($cliente_id),
        ]);
    }

    /**
     * Find this object on database using, CodigoBarras
     * @param  string $codigo_barras código de barras to find Funcionário
     * @return Funcionario A filled instance or empty when not found
     */
    public static function findByCodigoBarras($codigo_barras)
    {
        return self::find([
            'codigobarras' => strval($codigo_barras),
        ]);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $funcionario = new Funcionario();
        $allowed = Filter::concatKeys('f.', $funcionario->toArray());
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
        return Filter::orderBy($order, $allowed, 'f.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'f.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Funcionarios f');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('f.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Funcionario A filled Funcionário or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch();
        if ($row === false) {
            $row = [];
        }
        return new Funcionario($row);
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
            $result[] = new Funcionario($row);
        }
        return $result;
    }

    /**
     * Insert a new Funcionário into the database and fill instance from database
     * @return Funcionario Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Funcionarios')->values($values)->execute();
            $funcionario = self::findByID($id);
            $this->fromArray($funcionario->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Funcionário with instance values into database for Código
     * @return Funcionario Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do funcionário não foi informado');
        }
        unset($values['id']);
        try {
            self::getDB()
                ->update('Funcionarios')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $funcionario = self::findByID($this->getID());
            $this->fromArray($funcionario->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Save the Funcionário into the database
     * @return Funcionario Self instance
     */
    public function save()
    {
        if ($this->exists()) {
            return $this->update();
        }
        return $this->insert();
    }

    /**
     * Delete this instance from database using Código
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador do funcionário não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Funcionarios')
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
     * Função do funcionário na empresa
     * @return \MZ\Employee\Funcao The object fetched from database
     */
    public function findFuncaoID()
    {
        return \MZ\Employee\Funcao::findByID($this->getFuncaoID());
    }

    /**
     * Cliente que representa esse funcionário, único no cadastro de
     * funcionários
     * @return \MZ\Account\Cliente The object fetched from database
     */
    public function findClienteID()
    {
        return \MZ\Account\Cliente::findByID($this->getClienteID());
    }
}
