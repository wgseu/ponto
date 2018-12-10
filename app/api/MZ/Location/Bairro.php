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
namespace MZ\Location;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\System\Permissao;
use MZ\Exception\ValidationException;

/**
 * Bairro de uma cidade
 */
class Bairro extends SyncModel
{

    /**
     * Identificador do bairro
     */
    private $id;
    /**
     * Cidade a qual o bairro pertence
     */
    private $cidade_id;
    /**
     * Nome do bairro
     */
    private $nome;
    /**
     * Valor cobrado para entregar um pedido nesse bairro
     */
    private $valor_entrega;
    /**
     * Informa se o bairro está disponível para entrega de pedidos
     */
    private $disponivel;
    /**
     * Informa se o bairro está mapeado por zonas e se é obrigatório selecionar
     * uma zona
     */
    private $mapeado;
    /**
     * Tempo médio de entrega para esse bairro
     */
    private $tempo_entrega;

    /**
     * Constructor for a new empty instance of Bairro
     * @param array $bairro All field and values to fill the instance
     */
    public function __construct($bairro = [])
    {
        parent::__construct($bairro);
    }

    /**
     * Identificador do bairro
     * @return int id of Bairro
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Bairro
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Cidade a qual o bairro pertence
     * @return int cidade of Bairro
     */
    public function getCidadeID()
    {
        return $this->cidade_id;
    }

    /**
     * Set CidadeID value to new on param
     * @param int $cidade_id Set cidade for Bairro
     * @return self Self instance
     */
    public function setCidadeID($cidade_id)
    {
        $this->cidade_id = $cidade_id;
        return $this;
    }

    /**
     * Nome do bairro
     * @return string nome of Bairro
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param string $nome Set nome for Bairro
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Valor cobrado para entregar um pedido nesse bairro
     * @return string valor da entrega of Bairro
     */
    public function getValorEntrega()
    {
        return $this->valor_entrega;
    }

    /**
     * Set ValorEntrega value to new on param
     * @param string $valor_entrega Set valor da entrega for Bairro
     * @return self Self instance
     */
    public function setValorEntrega($valor_entrega)
    {
        $this->valor_entrega = $valor_entrega;
        return $this;
    }

    /**
     * Informa se o bairro está disponível para entrega de pedidos
     * @return string disponível of Bairro
     */
    public function getDisponivel()
    {
        return $this->disponivel;
    }

    /**
     * Informa se o bairro está disponível para entrega de pedidos
     * @return boolean Check if o of Disponivel is selected or checked
     */
    public function isDisponivel()
    {
        return $this->disponivel == 'Y';
    }

    /**
     * Set Disponivel value to new on param
     * @param string $disponivel Set disponível for Bairro
     * @return self Self instance
     */
    public function setDisponivel($disponivel)
    {
        $this->disponivel = $disponivel;
        return $this;
    }

    /**
     * Informa se o bairro está mapeado por zonas e se é obrigatório selecionar
     * uma zona
     * @return string mapeado of Bairro
     */
    public function getMapeado()
    {
        return $this->mapeado;
    }

    /**
     * Informa se o bairro está mapeado por zonas e se é obrigatório selecionar
     * uma zona
     * @return boolean Check if o of Mapeado is selected or checked
     */
    public function isMapeado()
    {
        return $this->mapeado == 'Y';
    }

    /**
     * Set Mapeado value to new on param
     * @param string $mapeado Set mapeado for Bairro
     * @return self Self instance
     */
    public function setMapeado($mapeado)
    {
        $this->mapeado = $mapeado;
        return $this;
    }

    /**
     * Tempo médio de entrega para esse bairro
     * @return int tempo de entrega of Bairro
     */
    public function getTempoEntrega()
    {
        return $this->tempo_entrega;
    }

    /**
     * Set TempoEntrega value to new on param
     * @param int $tempo_entrega Set tempo de entrega for Bairro
     * @return self Self instance
     */
    public function setTempoEntrega($tempo_entrega)
    {
        $this->tempo_entrega = $tempo_entrega;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $bairro = parent::toArray($recursive);
        $bairro['id'] = $this->getID();
        $bairro['cidadeid'] = $this->getCidadeID();
        $bairro['nome'] = $this->getNome();
        $bairro['valorentrega'] = $this->getValorEntrega();
        $bairro['disponivel'] = $this->getDisponivel();
        $bairro['mapeado'] = $this->getMapeado();
        $bairro['tempoentrega'] = $this->getTempoEntrega();
        return $bairro;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $bairro Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($bairro = [])
    {
        if ($bairro instanceof self) {
            $bairro = $bairro->toArray();
        } elseif (!is_array($bairro)) {
            $bairro = [];
        }
        parent::fromArray($bairro);
        if (!isset($bairro['id'])) {
            $this->setID(null);
        } else {
            $this->setID($bairro['id']);
        }
        if (!isset($bairro['cidadeid'])) {
            $this->setCidadeID(null);
        } else {
            $this->setCidadeID($bairro['cidadeid']);
        }
        if (!isset($bairro['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($bairro['nome']);
        }
        if (!isset($bairro['valorentrega'])) {
            $this->setValorEntrega(0);
        } else {
            $this->setValorEntrega($bairro['valorentrega']);
        }
        if (!isset($bairro['disponivel'])) {
            $this->setDisponivel('N');
        } else {
            $this->setDisponivel($bairro['disponivel']);
        }
        if (!isset($bairro['mapeado'])) {
            $this->setMapeado('N');
        } else {
            $this->setMapeado($bairro['mapeado']);
        }
        if (!array_key_exists('tempoentrega', $bairro)) {
            $this->setTempoEntrega(null);
        } else {
            $this->setTempoEntrega($bairro['tempoentrega']);
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
        $bairro = parent::publish($requester);
        return $bairro;
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
        $this->setCidadeID(Filter::number($this->getCidadeID()));
        $this->setNome(Filter::string($this->getNome()));
        $this->setValorEntrega(Filter::money($this->getValorEntrega(), $localized));
        $this->setTempoEntrega(Filter::number($this->getTempoEntrega()));
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
     * @return array All field of Bairro in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getCidadeID())) {
            $errors['cidadeid'] = _t('bairro.cidade_id_cannot_empty');
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = _t('bairro.nome_cannot_empty');
        }
        if (is_null($this->getValorEntrega())) {
            $errors['valorentrega'] = _t('bairro.valor_entrega_cannot_empty');
        } elseif ($this->getValorEntrega() < 0) {
            $errors['valorentrega'] = 'O valor da entrega não pode ser negativo';
        }
        if (!Validator::checkBoolean($this->getDisponivel())) {
            $errors['disponivel'] = _t('bairro.disponivel_invalid');
        }
        if (!Validator::checkBoolean($this->getMapeado())) {
            $errors['mapeado'] = _t('bairro.mapeado_invalid');
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
        if (contains(['CidadeID', 'Nome', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'cidadeid' => _t(
                    'bairro.cidade_id_used',
                    $this->getCidadeID()
                ),
                'nome' => _t(
                    'bairro.nome_used',
                    $this->getNome()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Load into this object from database using, CidadeID, Nome
     * @return self Self filled instance or empty when not found
     */
    public function loadByCidadeIDNome()
    {
        return $this->load([
            'cidadeid' => intval($this->getCidadeID()),
            'nome' => strval($this->getNome()),
        ]);
    }

    /**
     * Cidade a qual o bairro pertence
     * @return \MZ\Location\Cidade The object fetched from database
     */
    public function findCidadeID()
    {
        return \MZ\Location\Cidade::findByID($this->getCidadeID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    protected function getAllowedKeys()
    {
        $bairro = new self();
        $allowed = Filter::concatKeys('b.', $bairro->toArray());
        $allowed['e.paisid'] = true;
        $allowed['c.estadoid'] = true;
        return $allowed;
    }

    /**
     * Filter order array
     * @param mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    protected function filterOrder($order)
    {
        $allowed = $this->getAllowedKeys();
        return Filter::orderBy($order, $allowed, ['b.', 'c.', 'e.']);
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        return Filter::keys($condition, $allowed, ['b.', 'c.', 'e.']);
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Bairros b')
            ->leftJoin('Cidades c ON c.id = b.cidadeid')
            ->leftJoin('Estados e ON e.id = c.estadoid');
        if (isset($condition['search'])) {
            $search = $condition['search'];
            $query = DB::buildSearch($search, 'b.nome', $query);
        }
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('b.nome ASC');
        $query = $query->orderBy('b.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find district with that name or register a new one
     * @param  int $cidade_id cidade to find Bairro
     * @param  string $nome nome to find Bairro
     * @return Bairro A filled instance
     */
    public static function findOrInsert($cidade_id, $nome)
    {
        $bairro = self::findByCidadeIDNome(
            $cidade_id,
            Filter::string($nome)
        );
        if ($bairro->exists()) {
            return $bairro;
        }
        $msg = 'O bairro não está cadastrado e você não tem permissão para cadastrar um';
        app()->needPermission([Permissao::NOME_CADASTROBAIRROS], $msg);
        $bairro->setCidadeID($cidade_id);
        $bairro->setNome($nome);
        $bairro->setValorEntrega(0.0);
        $bairro->filter(new Bairro(), app()->auth->provider);
        try {
            $bairro->insert();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            if (isset($errors['nome'])) {
                $errors['bairro'] = $errors['nome'];
                unset($errors['nome']);
            }
            throw new ValidationException($errors);
        }
        return $bairro;
    }

    /**
     * Find this object on database using, CidadeID, Nome
     * @param int $cidade_id cidade to find Bairro
     * @param string $nome nome to find Bairro
     * @return self A filled instance or empty when not found
     */
    public static function findByCidadeIDNome($cidade_id, $nome)
    {
        $result = new self();
        $result->setCidadeID($cidade_id);
        $result->setNome($nome);
        return $result->loadByCidadeIDNome();
    }
}
