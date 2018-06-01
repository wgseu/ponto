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
namespace MZ\Payment;

use MZ\Database\Model;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Folhas de cheque de um pagamento
 */
class FolhaCheque extends Model
{

    /**
     * Identificador da folha de cheque
     */
    private $id;
    /**
     * Cheque a qual pertence esssa folha
     */
    private $cheque_id;
    /**
     * Número de compensação do cheque
     */
    private $compensacao;
    /**
     * Número da folha do cheque
     */
    private $numero;
    /**
     * Valor na folha do cheque
     */
    private $valor;
    /**
     * Data de vencimento do cheque
     */
    private $vencimento;
    /**
     * C1 do cheque
     */
    private $c = [];
    /**
     * Número de série do cheque
     */
    private $serie;
    /**
     * Informa se o cheque foi recolhido no banco
     */
    private $recolhido;
    /**
     * Data de recolhimento do cheque
     */
    private $recolhimento;

    /**
     * Constructor for a new empty instance of FolhaCheque
     * @param array $folha_cheque All field and values to fill the instance
     */
    public function __construct($folha_cheque = [])
    {
        parent::__construct($folha_cheque);
    }

    /**
     * Identificador da folha de cheque
     * @return mixed ID of FolhaCheque
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return FolhaCheque Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Cheque a qual pertence esssa folha
     * @return mixed Cheque of FolhaCheque
     */
    public function getChequeID()
    {
        return $this->cheque_id;
    }

    /**
     * Set ChequeID value to new on param
     * @param  mixed $cheque_id new value for ChequeID
     * @return FolhaCheque Self instance
     */
    public function setChequeID($cheque_id)
    {
        $this->cheque_id = $cheque_id;
        return $this;
    }

    /**
     * Número de compensação do cheque
     * @return mixed Compensação of FolhaCheque
     */
    public function getCompensacao()
    {
        return $this->compensacao;
    }

    /**
     * Set Compensacao value to new on param
     * @param  mixed $compensacao new value for Compensacao
     * @return FolhaCheque Self instance
     */
    public function setCompensacao($compensacao)
    {
        $this->compensacao = $compensacao;
        return $this;
    }

    /**
     * Número da folha do cheque
     * @return mixed Número of FolhaCheque
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set Numero value to new on param
     * @param  mixed $numero new value for Numero
     * @return FolhaCheque Self instance
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * Valor na folha do cheque
     * @return mixed Valor of FolhaCheque
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param  mixed $valor new value for Valor
     * @return FolhaCheque Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Data de vencimento do cheque
     * @return mixed Vencimento of FolhaCheque
     */
    public function getVencimento()
    {
        return $this->vencimento;
    }

    /**
     * Set Vencimento value to new on param
     * @param  mixed $vencimento new value for Vencimento
     * @return FolhaCheque Self instance
     */
    public function setVencimento($vencimento)
    {
        $this->vencimento = $vencimento;
        return $this;
    }

    /**
     * C1 do cheque
     * @param  integer $index index to get C
     * @return mixed C1 of FolhaCheque
     */
    public function getC($index)
    {
        if ($index < 1 || $index > 3) {
            throw new \Exception(
                vsprintf(
                    'Índice %d inválido, aceito somente de %d até %d',
                    [intval($index), 1, 3]
                ),
                500
            );
        }
        return $this->c[$index];
    }

    /**
     * Set C value to new on param
     * @param  integer $index index for set C
     * @param  mixed $c new value for C
     * @return FolhaCheque Self instance
     */
    public function setC($index, $c)
    {
        if ($index < 1 || $index > 3) {
            throw new \Exception(
                vsprintf(
                    'Índice %d inválido, aceito somente de %d até %d',
                    [intval($index), 1, 3]
                ),
                500
            );
        }
        $this->c[$index] = $c;
        return $this;
    }

    /**
     * Número de série do cheque
     * @return mixed Série of FolhaCheque
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Set Serie value to new on param
     * @param  mixed $serie new value for Serie
     * @return FolhaCheque Self instance
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;
        return $this;
    }

    /**
     * Informa se o cheque foi recolhido no banco
     * @return mixed Recolhido of FolhaCheque
     */
    public function getRecolhido()
    {
        return $this->recolhido;
    }

    /**
     * Informa se o cheque foi recolhido no banco
     * @return boolean Check if o of Recolhido is selected or checked
     */
    public function isRecolhido()
    {
        return $this->recolhido == 'Y';
    }

    /**
     * Set Recolhido value to new on param
     * @param  mixed $recolhido new value for Recolhido
     * @return FolhaCheque Self instance
     */
    public function setRecolhido($recolhido)
    {
        $this->recolhido = $recolhido;
        return $this;
    }

    /**
     * Data de recolhimento do cheque
     * @return mixed Data de recolhimento of FolhaCheque
     */
    public function getRecolhimento()
    {
        return $this->recolhimento;
    }

    /**
     * Set Recolhimento value to new on param
     * @param  mixed $recolhimento new value for Recolhimento
     * @return FolhaCheque Self instance
     */
    public function setRecolhimento($recolhimento)
    {
        $this->recolhimento = $recolhimento;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $folha_cheque = parent::toArray($recursive);
        $folha_cheque['id'] = $this->getID();
        $folha_cheque['chequeid'] = $this->getChequeID();
        $folha_cheque['compensacao'] = $this->getCompensacao();
        $folha_cheque['numero'] = $this->getNumero();
        $folha_cheque['valor'] = $this->getValor();
        $folha_cheque['vencimento'] = $this->getVencimento();
        $folha_cheque['c1'] = $this->getC(1);
        $folha_cheque['c2'] = $this->getC(2);
        $folha_cheque['c3'] = $this->getC(3);
        $folha_cheque['serie'] = $this->getSerie();
        $folha_cheque['recolhido'] = $this->getRecolhido();
        $folha_cheque['recolhimento'] = $this->getRecolhimento();
        return $folha_cheque;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $folha_cheque Associated key -> value to assign into this instance
     * @return FolhaCheque Self instance
     */
    public function fromArray($folha_cheque = [])
    {
        if ($folha_cheque instanceof FolhaCheque) {
            $folha_cheque = $folha_cheque->toArray();
        } elseif (!is_array($folha_cheque)) {
            $folha_cheque = [];
        }
        parent::fromArray($folha_cheque);
        if (!isset($folha_cheque['id'])) {
            $this->setID(null);
        } else {
            $this->setID($folha_cheque['id']);
        }
        if (!isset($folha_cheque['chequeid'])) {
            $this->setChequeID(null);
        } else {
            $this->setChequeID($folha_cheque['chequeid']);
        }
        if (!isset($folha_cheque['compensacao'])) {
            $this->setCompensacao(null);
        } else {
            $this->setCompensacao($folha_cheque['compensacao']);
        }
        if (!isset($folha_cheque['numero'])) {
            $this->setNumero(null);
        } else {
            $this->setNumero($folha_cheque['numero']);
        }
        if (!isset($folha_cheque['valor'])) {
            $this->setValor(null);
        } else {
            $this->setValor($folha_cheque['valor']);
        }
        if (!isset($folha_cheque['vencimento'])) {
            $this->setVencimento(null);
        } else {
            $this->setVencimento($folha_cheque['vencimento']);
        }
        if (!isset($folha_cheque['c1'])) {
            $this->setC(1, null);
        } else {
            $this->setC(1, $folha_cheque['c1']);
        }
        if (!isset($folha_cheque['c2'])) {
            $this->setC(2, null);
        } else {
            $this->setC(2, $folha_cheque['c2']);
        }
        if (!isset($folha_cheque['c3'])) {
            $this->setC(3, null);
        } else {
            $this->setC(3, $folha_cheque['c3']);
        }
        if (!array_key_exists('serie', $folha_cheque)) {
            $this->setSerie(null);
        } else {
            $this->setSerie($folha_cheque['serie']);
        }
        if (!isset($folha_cheque['recolhido'])) {
            $this->setRecolhido('N');
        } else {
            $this->setRecolhido($folha_cheque['recolhido']);
        }
        if (!array_key_exists('recolhimento', $folha_cheque)) {
            $this->setRecolhimento(null);
        } else {
            $this->setRecolhimento($folha_cheque['recolhimento']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $folha_cheque = parent::publish();
        return $folha_cheque;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param FolhaCheque $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setChequeID(Filter::number($this->getChequeID()));
        $this->setCompensacao(Filter::string($this->getCompensacao()));
        $this->setNumero(Filter::string($this->getNumero()));
        $this->setValor(Filter::money($this->getValor()));
        $this->setVencimento(Filter::datetime($this->getVencimento()));
        $this->setC(1, Filter::number($this->getC(1)));
        $this->setC(2, Filter::number($this->getC(2)));
        $this->setC(3, Filter::number($this->getC(3)));
        $this->setSerie(Filter::string($this->getSerie()));
        $this->setRecolhimento(Filter::datetime($this->getRecolhimento()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  FolhaCheque $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of FolhaCheque in array format
     */
    public function validate()
    {
        $errors = [];
        $old_folha = self::findByID($this->getID());
        if (is_null($this->getChequeID())) {
            $errors['chequeid'] = 'O cheque não pode ser vazio';
        }
        if (is_null($this->getCompensacao())) {
            $errors['compensacao'] = 'A compensação não pode ser vazia';
        }
        if (is_null($this->getNumero())) {
            $errors['numero'] = 'O número não pode ser vazio';
        }
        if (is_null($this->getValor())) {
            $errors['valor'] = 'O valor não pode ser vazio';
        }
        if (is_null($this->getVencimento())) {
            $errors['vencimento'] = 'O vencimento não pode ser vazio';
        }
        if (is_null($this->getC(1))) {
            $errors['c1'] = 'O C1 não pode ser vazio';
        }
        if (is_null($this->getC(2))) {
            $errors['c2'] = 'O C2 não pode ser vazio';
        }
        if (is_null($this->getC(3))) {
            $errors['c3'] = 'O C3 não pode ser vazio';
        }
        if (!Validator::checkBoolean($this->getRecolhido())) {
            $errors['recolhido'] = 'A informação de recolhimento é inválida';
        } elseif ($this->isRecolhido() && $old_folha->exists() && $old_folha->isRecolhido()) {
            $errors['recolhido'] = 'Essa folha de cheque já foi recolhida';
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
        if (stripos($e->getMessage(), 'UK_Folhas_Cheques_ChequeID_Numero') !== false) {
            return new \MZ\Exception\ValidationException([
                'chequeid' => sprintf(
                    'O cheque "%s" já está cadastrado',
                    $this->getChequeID()
                ),
                'numero' => sprintf(
                    'O número "%s" já está cadastrado',
                    $this->getNumero()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Folha de cheque into the database and fill instance from database
     * @return FolhaCheque Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Folhas_Cheques')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Folha de cheque with instance values into database for ID
     * @return FolhaCheque Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da folha de cheque não foi informado');
        }
        $values = DB::filterValues($values, $only, $except);
        try {
            DB::update('Folhas_Cheques')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $this->loadByID($this->getID());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    public function recolher()
    {
        $this->setRecolhido('Y');
        $this->setRecolhimento(DB::now());
        return $this->update();
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador da folha de cheque não foi informado');
        }
        $result = DB::deleteFrom('Folhas_Cheques')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return FolhaCheque Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, ChequeID, Numero
     * @param  int $cheque_id cheque to find Folha de cheque
     * @param  string $numero número to find Folha de cheque
     * @return FolhaCheque Self filled instance or empty when not found
     */
    public function loadByChequeIDNumero($cheque_id, $numero)
    {
        return $this->load([
            'chequeid' => intval($cheque_id),
            'numero' => strval($numero),
        ]);
    }

    /**
     * Cheque a qual pertence esssa folha
     * @return \MZ\Payment\Cheque The object fetched from database
     */
    public function findChequeID()
    {
        return \MZ\Payment\Cheque::findByID($this->getChequeID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $folha_cheque = new FolhaCheque();
        $allowed = Filter::concatKeys('f.', $folha_cheque->toArray());
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
        return Filter::orderBy($order, $allowed, ['f.', 'c.']);
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
            $field = 'f.numero LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, ['f.', 'c.']);
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Folhas_Cheques f')
            ->leftJoin('Cheques c ON c.id = f.chequeid');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('f.numero ASC');
        $query = $query->orderBy('f.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return FolhaCheque A filled Folha de cheque or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new FolhaCheque($row);
    }

    /**
     * Find this object on database using, ChequeID, Numero
     * @param  int $cheque_id cheque to find Folha de cheque
     * @param  string $numero número to find Folha de cheque
     * @return FolhaCheque A filled instance or empty when not found
     */
    public static function findByChequeIDNumero($cheque_id, $numero)
    {
        $result = new self();
        return $result->loadByChequeIDNumero($cheque_id, $numero);
    }

    /**
     * Find all Folha de cheque
     * @param  array  $condition Condition to get all Folha de cheque
     * @param  array  $order     Order Folha de cheque
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as FolhaCheque
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
            $result[] = new FolhaCheque($row);
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
