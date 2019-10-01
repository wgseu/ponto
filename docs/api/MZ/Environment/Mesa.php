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

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;
use MZ\Sale\Pedido;
use MZ\Sale\Juncao;

/**
 * Mesas para lançamento de pedidos
 */
class Mesa extends SyncModel
{

    /**
     * Número da mesa
     */
    private $id;
    /**
     * Setor em que a mesa está localizada
     */
    private $setor_id;
    /**
     * Número da mesa
     */
    private $numero;
    /**
     * Nome da mesa
     */
    private $nome;
    /**
     * Informa se a mesa está disponível para lançamento de pedidos
     */
    private $ativa;

    /**
     * Constructor for a new empty instance of Mesa
     * @param array $mesa All field and values to fill the instance
     */
    public function __construct($mesa = [])
    {
        parent::__construct($mesa);
    }

    /**
     * Número da mesa
     * @return int número of Mesa
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set número for Mesa
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Setor em que a mesa está localizada
     * @return int setor of Mesa
     */
    public function getSetorID()
    {
        return $this->setor_id;
    }

    /**
     * Set SetorID value to new on param
     * @param int $setor_id Set setor for Mesa
     * @return self Self instance
     */
    public function setSetorID($setor_id)
    {
        $this->setor_id = $setor_id;
        return $this;
    }

    /**
     * Número da mesa
     * @return int número of Mesa
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set Numero value to new on param
     * @param int $numero Set número for Mesa
     * @return self Self instance
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * Nome da mesa
     * @return string nome of Mesa
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param string $nome Set nome for Mesa
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Informa se a mesa está disponível para lançamento de pedidos
     * @return string ativa of Mesa
     */
    public function getAtiva()
    {
        return $this->ativa;
    }

    /**
     * Informa se a mesa está disponível para lançamento de pedidos
     * @return boolean Check if a of Ativa is selected or checked
     */
    public function isAtiva()
    {
        return $this->ativa == 'Y';
    }

    /**
     * Set Ativa value to new on param
     * @param string $ativa Set ativa for Mesa
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
        $mesa = parent::toArray($recursive);
        $mesa['id'] = $this->getID();
        $mesa['setorid'] = $this->getSetorID();
        $mesa['numero'] = $this->getNumero();
        $mesa['nome'] = $this->getNome();
        $mesa['ativa'] = $this->getAtiva();
        return $mesa;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $mesa Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($mesa = [])
    {
        if ($mesa instanceof self) {
            $mesa = $mesa->toArray();
        } elseif (!is_array($mesa)) {
            $mesa = [];
        }
        parent::fromArray($mesa);
        if (!isset($mesa['id'])) {
            $this->setID(null);
        } else {
            $this->setID($mesa['id']);
        }
        if (!array_key_exists('setorid', $mesa)) {
            $this->setSetorID(null);
        } else {
            $this->setSetorID($mesa['setorid']);
        }
        if (!isset($mesa['numero'])) {
            $this->setNumero(null);
        } else {
            $this->setNumero($mesa['numero']);
        }
        if (!isset($mesa['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($mesa['nome']);
        }
        if (!isset($mesa['ativa'])) {
            $this->setAtiva('N');
        } else {
            $this->setAtiva($mesa['ativa']);
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
        $mesa = parent::publish($requester);
        return $mesa;
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
        $this->setSetorID(Filter::number($this->getSetorID()));
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
     * @return array All field of Mesa in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNumero())) {
            $errors['numero'] = _t('mesa.numero_cannot_empty');
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = _t('mesa.nome_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getAtiva())) {
            $errors['ativa'] = _t('mesa.ativa_invalid');
        }
        $old_mesa = self::findByID($this->getID());
        if ($old_mesa->exists() && $old_mesa->isAtiva() && !$this->isAtiva()) {
            $pedido = Pedido::findByMesaID($old_mesa->getID());
            if ($pedido->exists()) {
                $errors['ativa'] = 'A mesa não pode ser desativada porque possui um pedido em aberto';
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
                    'mesa.nome_used',
                    $this->getNome()
                ),
            ]);
        }
        if (contains(['Numero', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'numero' => _t(
                    'mesa.numero_used',
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
     * @return Mesa Self id filled instance with next numero
     */
    public function loadNextNumero()
    {
        $last = self::find([], ['numero' => -1]);
        return $this->setNumero($last->getNumero() + 1);
    }

    /**
     * Setor em que a mesa está localizada
     * @return \MZ\Environment\Setor The object fetched from database
     */
    public function findSetorID()
    {
        return \MZ\Environment\Setor::findByID($this->getSetorID());
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
                $condition['id'] = Filter::number($search);
            } else {
                $field = 'm.nome LIKE ?';
                $condition[$field] = '%'.$search.'%';
                $allowed[$field] = true;
            }
        }
        return Filter::keys($condition, $allowed, 'm.');
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
        $query = DB::from('Mesas m');
        if (isset($condition['pedidos'])) {
            $query = $query->select('p.estado')
                ->select('p.id as pedidoid')
                ->select('c.nome as cliente')
                ->select('e.mesaid as juntaid')
                ->select('s.nome as juntanome')
                ->leftJoin(
                    'Pedidos p ON p.mesaid = m.id AND p.tipo = ? AND p.cancelado = ? AND p.estado <> ?',
                    Pedido::TIPO_MESA,
                    'N',
                    Pedido::ESTADO_FINALIZADO
                )
                ->leftJoin('Juncoes j ON j.mesaid = m.id AND j.estado = ?', Juncao::ESTADO_ASSOCIADO)
                ->leftJoin('Clientes c ON c.id = p.clienteid')
                ->leftJoin('Pedidos e ON e.id = j.pedidoid')
                ->leftJoin('Mesas s ON s.id = e.mesaid')
                ->groupBy('m.id');
            if (isset($order['funcionario'])) {
                $funcionario_id = intval($order['funcionario']);
                $query = $query->orderBy('IF(p.prestadorid = ?, 1, 0) DESC', $funcionario_id);
            }
        }
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('m.numero ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, Nome
     * @param string $nome nome to find Mesa
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
     * @param int $numero número to find Mesa
     * @return self A filled instance or empty when not found
     */
    public static function findByNumero($numero)
    {
        $result = new self();
        $result->setNumero($numero);
        return $result->loadByNumero();
    }
}
