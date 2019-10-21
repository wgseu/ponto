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
namespace MZ\Sale;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Comanda individual, permite lançar pedidos em cartões de consumo
 */
class Comanda extends SyncModel
{

    /**
     * Número da comanda
     */
    private $id;
    /**
     * Número da comanda
     */
    private $numero;
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
    public function __construct($comanda = [])
    {
        parent::__construct($comanda);
    }

    /**
     * Número da comanda
     * @return int número of Comanda
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set número for Comanda
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Número da comanda
     * @return int número of Comanda
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set Numero value to new on param
     * @param int $numero Set número for Comanda
     * @return self Self instance
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * Nome da comanda
     * @return string nome of Comanda
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param string $nome Set nome for Comanda
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Informa se a comanda está diponível para ser usada nas vendas
     * @return string ativa of Comanda
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
     * @param string $ativa Set ativa for Comanda
     * @return self Self instance
     */
    public function setAtiva($ativa)
    {
        $this->ativa = $ativa;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $comanda = parent::toArray($recursive);
        $comanda['id'] = $this->getID();
        $comanda['numero'] = $this->getNumero();
        $comanda['nome'] = $this->getNome();
        $comanda['ativa'] = $this->getAtiva();
        return $comanda;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $comanda Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($comanda = [])
    {
        if ($comanda instanceof self) {
            $comanda = $comanda->toArray();
        } elseif (!is_array($comanda)) {
            $comanda = [];
        }
        parent::fromArray($comanda);
        if (!isset($comanda['id'])) {
            $this->setID(null);
        } else {
            $this->setID($comanda['id']);
        }
        if (!isset($comanda['numero'])) {
            $this->setNumero(null);
        } else {
            $this->setNumero($comanda['numero']);
        }
        if (!isset($comanda['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($comanda['nome']);
        }
        if (!isset($comanda['ativa'])) {
            $this->setAtiva('N');
        } else {
            $this->setAtiva($comanda['ativa']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $comanda = parent::publish($requester);
        return $comanda;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     * @param \MZ\Provider\Prestador $updater user that want to update this object
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $updater, $localized = false)
    {
        $this->setID($original->getID());
        $this->setNumero(Filter::number($this->getNumero()));
        $this->setNome(Filter::string($this->getNome()));
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Comanda in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNumero())) {
            $errors['numero'] = _t('comanda.numero_cannot_empty');
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = _t('comanda.nome_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getAtiva())) {
            $errors['ativa'] = _t('comanda.ativa_invalid');
        }
        $old_comanda = self::findByID($this->getID());
        if ($old_comanda->exists() && $old_comanda->isAtiva() && !$this->isAtiva()) {
            $pedido = Pedido::findByComandaID($old_comanda->getID());
            if ($pedido->exists()) {
                $errors['ativa'] = _t('comanda.ativa_open');
            }
        }
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
        if (contains(['Nome', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'nome' => _t(
                    'comanda.nome_used',
                    $this->getNome()
                ),
            ]);
        }
        if (contains(['Numero', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'numero' => _t(
                    'comanda.numero_used',
                    $this->getNumero()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Load into this object from database using, Nome
     * @return self Self filled instance or empty when not found
     */
    public function loadByNome()
    {
        return $this->load([
            'nome' => strval($this->getNome()),
        ]);
    }

    /**
     * Load into this object from database using, Numero
     * @return self Self filled instance or empty when not found
     */
    public function loadByNumero()
    {
        return $this->load([
            'numero' => intval($this->getNumero()),
        ]);
    }

    /**
     * Load next available number from database into this object numero field
     * @return self Self id filled instance with next numero
     */
    public function loadNextNumero()
    {
        $last = self::find([], ['numero' => -1]);
        return $this->setNumero($last->getNumero() + 1);
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        if (isset($condition['search'])) {
            $search = $condition['search'];
            if (Validator::checkDigits($search)) {
                $condition['id'] = intval($search);
            } else {
                $field = 'c.nome LIKE ?';
                $condition[$field] = '%'.$search.'%';
                $allowed[$field] = true;
            }
        }
        return Filter::keys($condition, $allowed, 'c.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $order = Filter::order($order);
        $query = DB::from('Comandas c');
        if (isset($condition['pedidos'])) {
            $query = $query->select('p.estado')
                ->select('p.id as pedidoid')
                ->select('l.nome as cliente')
                ->select('p.descricao as observacao')
                ->select('p.mesaid as juntaid')
                ->select('m.nome as juntanome')
                ->leftJoin(
                    'Pedidos p ON p.comandaid = c.id AND p.tipo = ? AND p.cancelado = ? AND p.estado <> ?',
                    Pedido::TIPO_COMANDA,
                    'N',
                    Pedido::ESTADO_FINALIZADO
                )
                ->leftJoin('Mesas m ON m.id = p.mesaid')
                ->leftJoin('Clientes l ON l.id = p.clienteid');
            if (isset($order['funcionario'])) {
                $prestador_id = intval($order['funcionario']);
                $query = $query->orderBy('IF(p.prestadorid = ?, 1, 0) DESC', $prestador_id);
            }
            if (array_key_exists('mesas', $condition)) {
                $query = $query->where('NOT p.mesaid', $condition['mesas']);
            }
        }
        $condition = $this->filterCondition($condition);
        $order = Filter::order($order);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('c.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, Nome
     * @param string $nome nome to find Comanda
     * @return self A filled instance or empty when not found
     */
    public static function findByNome($nome)
    {
        $result = new self();
        $result->setNome($nome);
        return $result->loadByNome();
    }

    /**
     * Find this object on database using, Numero
     * @param int $numero número to find Comanda
     * @return self A filled instance or empty when not found
     */
    public static function findByNumero($numero)
    {
        $result = new self();
        $result->setNumero($numero);
        return $result->loadByNumero();
    }
}