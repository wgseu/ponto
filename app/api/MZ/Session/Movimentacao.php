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
namespace MZ\Session;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Movimentação do caixa, permite abrir diversos caixas na conta de
 * operadores
 */
class Movimentacao extends SyncModel
{

    /**
     * Código da movimentação do caixa
     */
    private $id;
    /**
     * Sessão do dia, permite abrir vários caixas no mesmo dia com o mesmo
     * código da sessão
     */
    private $sessao_id;
    /**
     * Caixa a qual pertence essa movimentação
     */
    private $caixa_id;
    /**
     * Informa se o caixa está aberto
     */
    private $aberta;
    /**
     * Funcionário que abriu o caixa
     */
    private $iniciador_id;
    /**
     * Funcionário que fechou o caixa
     */
    private $fechador_id;
    /**
     * Data de fechamento do caixa
     */
    private $data_fechamento;
    /**
     * Data de abertura do caixa
     */
    private $data_abertura;

    /**
     * Constructor for a new empty instance of Movimentacao
     * @param array $movimentacao All field and values to fill the instance
     */
    public function __construct($movimentacao = [])
    {
        parent::__construct($movimentacao);
    }

    /**
     * Código da movimentação do caixa
     * @return int id of Movimentação
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Movimentação
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Sessão do dia, permite abrir vários caixas no mesmo dia com o mesmo
     * código da sessão
     * @return int sessão of Movimentação
     */
    public function getSessaoID()
    {
        return $this->sessao_id;
    }

    /**
     * Set SessaoID value to new on param
     * @param int $sessao_id Set sessão for Movimentação
     * @return self Self instance
     */
    public function setSessaoID($sessao_id)
    {
        $this->sessao_id = $sessao_id;
        return $this;
    }

    /**
     * Caixa a qual pertence essa movimentação
     * @return int caixa of Movimentação
     */
    public function getCaixaID()
    {
        return $this->caixa_id;
    }

    /**
     * Set CaixaID value to new on param
     * @param int $caixa_id Set caixa for Movimentação
     * @return self Self instance
     */
    public function setCaixaID($caixa_id)
    {
        $this->caixa_id = $caixa_id;
        return $this;
    }

    /**
     * Informa se o caixa está aberto
     * @return string aberta of Movimentação
     */
    public function getAberta()
    {
        return $this->aberta;
    }

    /**
     * Informa se o caixa está aberto
     * @return boolean Check if a of Aberta is selected or checked
     */
    public function isAberta()
    {
        return $this->aberta == 'Y';
    }

    /**
     * Set Aberta value to new on param
     * @param string $aberta Set aberta for Movimentação
     * @return self Self instance
     */
    public function setAberta($aberta)
    {
        $this->aberta = $aberta;
        return $this;
    }

    /**
     * Funcionário que abriu o caixa
     * @return int funcionário inicializador of Movimentação
     */
    public function getIniciadorID()
    {
        return $this->iniciador_id;
    }

    /**
     * Set IniciadorID value to new on param
     * @param int $iniciador_id Set funcionário inicializador for Movimentação
     * @return self Self instance
     */
    public function setIniciadorID($iniciador_id)
    {
        $this->iniciador_id = $iniciador_id;
        return $this;
    }

    /**
     * Funcionário que fechou o caixa
     * @return int funcionário fechador of Movimentação
     */
    public function getFechadorID()
    {
        return $this->fechador_id;
    }

    /**
     * Set FechadorID value to new on param
     * @param int $fechador_id Set funcionário fechador for Movimentação
     * @return self Self instance
     */
    public function setFechadorID($fechador_id)
    {
        $this->fechador_id = $fechador_id;
        return $this;
    }

    /**
     * Data de fechamento do caixa
     * @return string data de fechamento of Movimentação
     */
    public function getDataFechamento()
    {
        return $this->data_fechamento;
    }

    /**
     * Set DataFechamento value to new on param
     * @param string $data_fechamento Set data de fechamento for Movimentação
     * @return self Self instance
     */
    public function setDataFechamento($data_fechamento)
    {
        $this->data_fechamento = $data_fechamento;
        return $this;
    }

    /**
     * Data de abertura do caixa
     * @return string data de abertura of Movimentação
     */
    public function getDataAbertura()
    {
        return $this->data_abertura;
    }

    /**
     * Set DataAbertura value to new on param
     * @param string $data_abertura Set data de abertura for Movimentação
     * @return self Self instance
     */
    public function setDataAbertura($data_abertura)
    {
        $this->data_abertura = $data_abertura;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $movimentacao = parent::toArray($recursive);
        $movimentacao['id'] = $this->getID();
        $movimentacao['sessaoid'] = $this->getSessaoID();
        $movimentacao['caixaid'] = $this->getCaixaID();
        $movimentacao['aberta'] = $this->getAberta();
        $movimentacao['iniciadorid'] = $this->getIniciadorID();
        $movimentacao['fechadorid'] = $this->getFechadorID();
        $movimentacao['datafechamento'] = $this->getDataFechamento();
        $movimentacao['dataabertura'] = $this->getDataAbertura();
        return $movimentacao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $movimentacao Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($movimentacao = [])
    {
        if ($movimentacao instanceof self) {
            $movimentacao = $movimentacao->toArray();
        } elseif (!is_array($movimentacao)) {
            $movimentacao = [];
        }
        parent::fromArray($movimentacao);
        if (!isset($movimentacao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($movimentacao['id']);
        }
        if (!isset($movimentacao['sessaoid'])) {
            $this->setSessaoID(null);
        } else {
            $this->setSessaoID($movimentacao['sessaoid']);
        }
        if (!isset($movimentacao['caixaid'])) {
            $this->setCaixaID(null);
        } else {
            $this->setCaixaID($movimentacao['caixaid']);
        }
        if (!isset($movimentacao['aberta'])) {
            $this->setAberta('N');
        } else {
            $this->setAberta($movimentacao['aberta']);
        }
        if (!isset($movimentacao['iniciadorid'])) {
            $this->setIniciadorID(null);
        } else {
            $this->setIniciadorID($movimentacao['iniciadorid']);
        }
        if (!array_key_exists('fechadorid', $movimentacao)) {
            $this->setFechadorID(null);
        } else {
            $this->setFechadorID($movimentacao['fechadorid']);
        }
        if (!array_key_exists('datafechamento', $movimentacao)) {
            $this->setDataFechamento(null);
        } else {
            $this->setDataFechamento($movimentacao['datafechamento']);
        }
        if (!isset($movimentacao['dataabertura'])) {
            $this->setDataAbertura(DB::now());
        } else {
            $this->setDataAbertura($movimentacao['dataabertura']);
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
        $movimentacao = parent::publish($requester);
        return $movimentacao;
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
        $this->setSessaoID(Filter::number($this->getSessaoID()));
        $this->setCaixaID(Filter::number($this->getCaixaID()));
        $this->setIniciadorID(Filter::number($this->getIniciadorID()));
        $this->setFechadorID(Filter::number($this->getFechadorID()));
        $this->setDataFechamento(Filter::datetime($this->getDataFechamento()));
        $this->setDataAbertura(Filter::datetime($this->getDataAbertura()));
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
     * @return array All field of Movimentacao in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getSessaoID())) {
            $errors['sessaoid'] = _t('movimentacao.sessao_id_cannot_empty');
        }
        $caixa = $this->findCaixaID();
        if (is_null($this->getCaixaID())) {
            $errors['caixaid'] = _t('movimentacao.caixa_id_cannot_empty');
        } elseif (!$caixa->isAtivo()) {
            $errors['caixaid'] = _t('movimentacao.caixa_inactive', $caixa->getDescricao());
        }
        if (!Validator::checkBoolean($this->getAberta())) {
            $errors['aberta'] = _t('movimentacao.aberta_invalid');
        } elseif (!$this->exists() && !$this->isAberta()) {
            $errors['aberta'] = _t('movimentacao.aberta_create_closed');
        }
        if (is_null($this->getIniciadorID())) {
            $errors['iniciadorid'] = _t('movimentacao.iniciador_id_cannot_empty');
        }
        if (is_null($this->getDataAbertura())) {
            $errors['dataabertura'] = _t('movimentacao.data_abertura_cannot_empty');
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Find open cash register preferred to employee informed
     * @return self A filled instance or empty when not found
     */
    public function loadByAberta()
    {
        return $this->load(
            ['aberta' => 'Y'],
            [
                'inicializador' => [-1 => $this->getIniciadorID()],
                'dataabertura' => -1
            ]
        );
    }

    /**
     * Sessão do dia, permite abrir vários caixas no mesmo dia com o mesmo
     * código da sessão
     * @return \MZ\Session\Sessao The object fetched from database
     */
    public function findSessaoID()
    {
        return \MZ\Session\Sessao::findByID($this->getSessaoID());
    }

    /**
     * Caixa a qual pertence essa movimentação
     * @return \MZ\Session\Caixa The object fetched from database
     */
    public function findCaixaID()
    {
        return \MZ\Session\Caixa::findByID($this->getCaixaID());
    }

    /**
     * Funcionário que abriu o caixa
     * @return \MZ\Provider\Prestador The object fetched from database
     */
    public function findIniciadorID()
    {
        return \MZ\Provider\Prestador::findByID($this->getIniciadorID());
    }

    /**
     * Funcionário que fechou o caixa
     * @return \MZ\Provider\Prestador The object fetched from database
     */
    public function findFechadorID()
    {
        if (is_null($this->getFechadorID())) {
            return new \MZ\Provider\Prestador();
        }
        return \MZ\Provider\Prestador::findByID($this->getFechadorID());
    }

    /**
     * Filter order array
     * @param mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    protected function filterOrder($order)
    {
        $allowed = $this->getAllowedKeys();
        $order = Filter::order($order);
        if (isset($order['inicializador'])) {
            $field = '(m.iniciadorid = ?)';
            $order = replace_key($order, 'inicializador', $field);
            $allowed[$field] = true;
        }
        return Filter::orderBy($order, $allowed, 'm.');
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        if (isset($condition['apartir_abertura'])) {
            $field = 'm.dataabertura >= ?';
            $condition[$field] = Filter::datetime($condition['apartir_abertura'], '00:00:00');
            $allowed[$field] = true;
            unset($condition['apartir_abertura']);
        }
        if (isset($condition['ate_abertura'])) {
            $field = 'm.dataabertura <= ?';
            $condition[$field] = Filter::datetime($condition['ate_abertura'], '23:59:59');
            $allowed[$field] = true;
            unset($condition['ate_abertura']);
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
        $query = DB::from('Movimentacoes m');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('m.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find open cash register preferred to employee informed
     * @param  int $funcionario_id preferred to employee
     * @return Movimentacao A filled instance or empty when not found
     */
    public static function findByAberta($funcionario_id = null)
    {
        $result = new self();
        $result->setIniciadorID($funcionario_id);
        return $result->loadByAberta();
    }

    /**
     * Check if cash register is open
     * @param  int $caixa_id cash register id to find open cash register
     * @return bool true when cash register is open, false otherwise
     */
    public static function isCaixaOpen($caixa_id)
    {
        $abertos = self::count([
            'caixaid' => $caixa_id,
            'aberta' => 'Y'
        ]);
        return $abertos > 0;
    }
}
