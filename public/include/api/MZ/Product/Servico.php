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
 * Taxas, eventos e serviço cobrado nos pedidos
 */
class Servico extends \MZ\Database\Helper
{

    /**
     * Tipo de serviço, Evento: Eventos como show no estabelecimento
     */
    const TIPO_EVENTO = 'Evento';
    const TIPO_TAXA = 'Taxa';

    /**
     * Identificador do serviço
     */
    private $id;
    /**
     * Nome do serviço, Ex.: Comissão, Entrega, Couvert
     */
    private $nome;
    /**
     * Descrição do serviço, Ex.: Show de fulano
     */
    private $descricao;
    /**
     * Detalhes do serviço, Ex.: Com participação especial de fulano
     */
    private $detalhes;
    /**
     * Tipo de serviço, Evento: Eventos como show no estabelecimento
     */
    private $tipo;
    /**
     * Informa se a taxa é obrigatória
     */
    private $obrigatorio;
    /**
     * Data de início do evento
     */
    private $data_inicio;
    /**
     * Data final do evento
     */
    private $data_fim;
    /**
     * Valor do serviço
     */
    private $valor;
    /**
     * Informa se a taxa ou serviço é individual para cada pessoa
     */
    private $individual;
    /**
     * Informa se o serviço está ativo
     */
    private $ativo;

    /**
     * Constructor for a new empty instance of Servico
     * @param array $servico All field and values to fill the instance
     */
    public function __construct($servico = [])
    {
        parent::__construct($servico);
    }

    /**
     * Identificador do serviço
     * @return mixed ID of Servico
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Servico Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Nome do serviço, Ex.: Comissão, Entrega, Couvert
     * @return mixed Nome of Servico
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param  mixed $nome new value for Nome
     * @return Servico Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Descrição do serviço, Ex.: Show de fulano
     * @return mixed Descrição of Servico
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Servico Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Detalhes do serviço, Ex.: Com participação especial de fulano
     * @return mixed Detalhes of Servico
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }

    /**
     * Set Detalhes value to new on param
     * @param  mixed $detalhes new value for Detalhes
     * @return Servico Self instance
     */
    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
        return $this;
    }

    /**
     * Tipo de serviço, Evento: Eventos como show no estabelecimento
     * @return mixed Tipo of Servico
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param  mixed $tipo new value for Tipo
     * @return Servico Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Informa se a taxa é obrigatória
     * @return mixed Obrigatório of Servico
     */
    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }

    /**
     * Informa se a taxa é obrigatória
     * @return boolean Check if o of Obrigatorio is selected or checked
     */
    public function isObrigatorio()
    {
        return $this->obrigatorio == 'Y';
    }

    /**
     * Set Obrigatorio value to new on param
     * @param  mixed $obrigatorio new value for Obrigatorio
     * @return Servico Self instance
     */
    public function setObrigatorio($obrigatorio)
    {
        $this->obrigatorio = $obrigatorio;
        return $this;
    }

    /**
     * Data de início do evento
     * @return mixed Data de início of Servico
     */
    public function getDataInicio()
    {
        return $this->data_inicio;
    }

    /**
     * Set DataInicio value to new on param
     * @param  mixed $data_inicio new value for DataInicio
     * @return Servico Self instance
     */
    public function setDataInicio($data_inicio)
    {
        $this->data_inicio = $data_inicio;
        return $this;
    }

    /**
     * Data final do evento
     * @return mixed Data final of Servico
     */
    public function getDataFim()
    {
        return $this->data_fim;
    }

    /**
     * Set DataFim value to new on param
     * @param  mixed $data_fim new value for DataFim
     * @return Servico Self instance
     */
    public function setDataFim($data_fim)
    {
        $this->data_fim = $data_fim;
        return $this;
    }

    /**
     * Valor do serviço
     * @return mixed Valor of Servico
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param  mixed $valor new value for Valor
     * @return Servico Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Informa se a taxa ou serviço é individual para cada pessoa
     * @return mixed Individual of Servico
     */
    public function getIndividual()
    {
        return $this->individual;
    }

    /**
     * Informa se a taxa ou serviço é individual para cada pessoa
     * @return boolean Check if o of Individual is selected or checked
     */
    public function isIndividual()
    {
        return $this->individual == 'Y';
    }

    /**
     * Set Individual value to new on param
     * @param  mixed $individual new value for Individual
     * @return Servico Self instance
     */
    public function setIndividual($individual)
    {
        $this->individual = $individual;
        return $this;
    }

    /**
     * Informa se o serviço está ativo
     * @return mixed Ativo of Servico
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Informa se o serviço está ativo
     * @return boolean Check if o of Ativo is selected or checked
     */
    public function isAtivo()
    {
        return $this->ativo == 'Y';
    }

    /**
     * Set Ativo value to new on param
     * @param  mixed $ativo new value for Ativo
     * @return Servico Self instance
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $servico = parent::toArray($recursive);
        $servico['id'] = $this->getID();
        $servico['nome'] = $this->getNome();
        $servico['descricao'] = $this->getDescricao();
        $servico['detalhes'] = $this->getDetalhes();
        $servico['tipo'] = $this->getTipo();
        $servico['obrigatorio'] = $this->getObrigatorio();
        $servico['datainicio'] = $this->getDataInicio();
        $servico['datafim'] = $this->getDataFim();
        $servico['valor'] = $this->getValor();
        $servico['individual'] = $this->getIndividual();
        $servico['ativo'] = $this->getAtivo();
        return $servico;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $servico Associated key -> value to assign into this instance
     * @return Servico Self instance
     */
    public function fromArray($servico = [])
    {
        if ($servico instanceof Servico) {
            $servico = $servico->toArray();
        } elseif (!is_array($servico)) {
            $servico = [];
        }
        parent::fromArray($servico);
        if (!isset($servico['id'])) {
            $this->setID(null);
        } else {
            $this->setID($servico['id']);
        }
        if (!isset($servico['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($servico['nome']);
        }
        if (!isset($servico['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($servico['descricao']);
        }
        if (!array_key_exists('detalhes', $servico)) {
            $this->setDetalhes(null);
        } else {
            $this->setDetalhes($servico['detalhes']);
        }
        if (!isset($servico['tipo'])) {
            $this->setTipo(null);
        } else {
            $this->setTipo($servico['tipo']);
        }
        if (!isset($servico['obrigatorio'])) {
            $this->setObrigatorio(null);
        } else {
            $this->setObrigatorio($servico['obrigatorio']);
        }
        if (!array_key_exists('datainicio', $servico)) {
            $this->setDataInicio(null);
        } else {
            $this->setDataInicio($servico['datainicio']);
        }
        if (!array_key_exists('datafim', $servico)) {
            $this->setDataFim(null);
        } else {
            $this->setDataFim($servico['datafim']);
        }
        if (!isset($servico['valor'])) {
            $this->setValor(null);
        } else {
            $this->setValor($servico['valor']);
        }
        if (!isset($servico['individual'])) {
            $this->setIndividual(null);
        } else {
            $this->setIndividual($servico['individual']);
        }
        if (!isset($servico['ativo'])) {
            $this->setAtivo(null);
        } else {
            $this->setAtivo($servico['ativo']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $servico = parent::publish();
        return $servico;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Servico $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setNome(Filter::string($this->getNome()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setDetalhes(Filter::string($this->getDetalhes()));
        $this->setDataInicio(Filter::datetime($this->getDataInicio()));
        $this->setDataFim(Filter::datetime($this->getDataFim()));
        $this->setValor(Filter::money($this->getValor()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Servico $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Servico in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNome())) {
            $errors['nome'] = 'O nome não pode ser vazio';
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = 'A descrição não pode ser vazia';
        }
        if (is_null($this->getTipo())) {
            $errors['tipo'] = 'O tipo não pode ser vazio';
        }
        if (!is_null($this->getTipo()) &&
            !array_key_exists($this->getTipo(), self::getTipoOptions())
        ) {
            $errors['tipo'] = 'O tipo é inválido';
        }
        if (is_null($this->getObrigatorio())) {
            $errors['obrigatorio'] = 'O obrigatório não pode ser vazio';
        }
        if (!is_null($this->getObrigatorio()) &&
            !array_key_exists($this->getObrigatorio(), self::getBooleanOptions())
        ) {
            $errors['obrigatorio'] = 'O obrigatório é inválido';
        }
        if (is_null($this->getValor())) {
            $errors['valor'] = 'O valor não pode ser vazio';
        }
        if (is_null($this->getIndividual())) {
            $errors['individual'] = 'O individual não pode ser vazio';
        }
        if (!is_null($this->getIndividual()) &&
            !array_key_exists($this->getIndividual(), self::getBooleanOptions())
        ) {
            $errors['individual'] = 'O individual é inválido';
        }
        if (is_null($this->getAtivo())) {
            $errors['ativo'] = 'O ativo não pode ser vazio';
        }
        if (!is_null($this->getAtivo()) &&
            !array_key_exists($this->getAtivo(), self::getBooleanOptions())
        ) {
            $errors['ativo'] = 'O ativo é inválido';
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
        return parent::translate($e);
    }

    /**
     * Gets textual and translated Tipo for Servico
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_EVENTO => 'Evento',
            self::TIPO_TAXA => 'Taxa',
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Serviço
     * @return Servico A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $servico = new Servico();
        $allowed = Filter::concatKeys('s.', $servico->toArray());
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
        return Filter::orderBy($order, $allowed, 's.');
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
            $field = 's.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 's.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Servicos s');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('s.descricao ASC');
        $query = $query->orderBy('s.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Servico A filled Serviço or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch();
        if ($row === false) {
            $row = [];
        }
        return new Servico($row);
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
            $result[] = new Servico($row);
        }
        return $result;
    }

    /**
     * Insert a new Serviço into the database and fill instance from database
     * @return Servico Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Servicos')->values($values)->execute();
            $servico = self::findByID($id);
            $this->fromArray($servico->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Serviço with instance values into database for ID
     * @return Servico Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do serviço não foi informado');
        }
        unset($values['id']);
        try {
            self::getDB()
                ->update('Servicos')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $servico = self::findByID($this->getID());
            $this->fromArray($servico->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Save the Serviço into the database
     * @return Servico Self instance
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
            throw new \Exception('O identificador do serviço não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Servicos')
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
}
