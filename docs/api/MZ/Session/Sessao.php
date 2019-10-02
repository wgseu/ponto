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
 * Sessão de trabalho do dia, permite que vários caixas sejam abertos
 * utilizando uma mesma sessão
 */
class Sessao extends SyncModel
{

    /**
     * Código da sessão
     */
    private $id;
    /**
     * Data de início da sessão
     */
    private $data_inicio;
    /**
     * Data de fechamento da sessão
     */
    private $data_termino;
    /**
     * Informa se a sessão está aberta
     */
    private $aberta;

    /**
     * Constructor for a new empty instance of Sessao
     * @param array $sessao All field and values to fill the instance
     */
    public function __construct($sessao = [])
    {
        parent::__construct($sessao);
    }

    /**
     * Código da sessão
     * @return int id of Sessão
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Sessão
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Data de início da sessão
     * @return string data de início of Sessão
     */
    public function getDataInicio()
    {
        return $this->data_inicio;
    }

    /**
     * Set DataInicio value to new on param
     * @param string $data_inicio Set data de início for Sessão
     * @return self Self instance
     */
    public function setDataInicio($data_inicio)
    {
        $this->data_inicio = $data_inicio;
        return $this;
    }

    /**
     * Data de fechamento da sessão
     * @return string data de termíno of Sessão
     */
    public function getDataTermino()
    {
        return $this->data_termino;
    }

    /**
     * Set DataTermino value to new on param
     * @param string $data_termino Set data de termíno for Sessão
     * @return self Self instance
     */
    public function setDataTermino($data_termino)
    {
        $this->data_termino = $data_termino;
        return $this;
    }

    /**
     * Informa se a sessão está aberta
     * @return string aberta of Sessão
     */
    public function getAberta()
    {
        return $this->aberta;
    }

    /**
     * Informa se a sessão está aberta
     * @return boolean Check if a of Aberta is selected or checked
     */
    public function isAberta()
    {
        return $this->aberta == 'Y';
    }

    /**
     * Set Aberta value to new on param
     * @param string $aberta Set aberta for Sessão
     * @return self Self instance
     */
    public function setAberta($aberta)
    {
        $this->aberta = $aberta;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $sessao = parent::toArray($recursive);
        $sessao['id'] = $this->getID();
        $sessao['datainicio'] = $this->getDataInicio();
        $sessao['datatermino'] = $this->getDataTermino();
        $sessao['aberta'] = $this->getAberta();
        return $sessao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $sessao Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($sessao = [])
    {
        if ($sessao instanceof self) {
            $sessao = $sessao->toArray();
        } elseif (!is_array($sessao)) {
            $sessao = [];
        }
        parent::fromArray($sessao);
        if (!isset($sessao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($sessao['id']);
        }
        if (!isset($sessao['datainicio'])) {
            $this->setDataInicio(DB::now());
        } else {
            $this->setDataInicio($sessao['datainicio']);
        }
        if (!array_key_exists('datatermino', $sessao)) {
            $this->setDataTermino(null);
        } else {
            $this->setDataTermino($sessao['datatermino']);
        }
        if (!isset($sessao['aberta'])) {
            $this->setAberta('N');
        } else {
            $this->setAberta($sessao['aberta']);
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
        $sessao = parent::publish($requester);
        return $sessao;
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
        $this->setDataInicio(Filter::datetime($original->getDataInicio()));
        $this->setDataTermino(Filter::datetime($original->getDataTermino()));
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
     * @return array All field of Sessao in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        $old = self::findByID($this->getID());
        if (is_null($this->getDataInicio())) {
            $errors['datainicio'] = _t('sessao.data_inicio_cannot_empty');
        } elseif ($old->exists() && $old->getDataInicio() != $this->getDataInicio()) {
            $errors['datainicio'] = _t('sessao.data_inicio_cannot_change');
        }
        if (is_null($this->getDataTermino()) && !$this->isAberta()) {
            $errors['datatermino'] = _t('sessao.data_termino_cannot_empty');
        } elseif (!is_null($this->getDataTermino()) && $this->isAberta()) {
            $errors['datatermino'] = _t('sessao.data_termino_mustbe_empty');
        } elseif ($old->exists() && !$old->isAberta() &&
            $old->getDataTermino() != $this->getDataTermino()
        ) {
            $errors['datatermino'] = _t('sessao.data_termino_cannot_change');
        }
        $count = Movimentacao::count(['sessaoid' => $this->getID(), 'aberta' => 'Y']);
        $sessao = self::findByAberta();
        if (!Validator::checkBoolean($this->getAberta())) {
            $errors['aberta'] = _t('sessao.aberta_invalid');
        } elseif (!$this->exists() && !$this->isAberta()) {
            $errors['aberta'] = _t('sessao.ativa_start_closed');
        } elseif ($this->isAberta() && $sessao->exists() && $this->getID() != $sessao->getID()) {
            $errors['aberta'] = _t('sessao.ativa_already_open');
        } elseif (!$this->isAberta() && $count > 0) {
            $errors['aberta'] = _tc('sessao.movimentation_open', $count, $count);
        } elseif ($old->exists() && !$old->isAberta() && $this->isAberta()) {
            $errors['aberta'] = _t('sessao.cannot_reopen');
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        return Filter::keys($condition, $allowed, 's.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Sessoes s');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('s.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find open session
     * @param  boolean $required when true and none sesstion open found throw an exception
     * @return Sessao A filled instance or empty when not found
     */
    public static function findByAberta($required = false)
    {
        $sessao = self::find([
            'aberta' => 'Y',
        ]);
        if ($required && !$sessao->exists()) {
            throw new \Exception('O caixa ainda não foi aberto');
        }
        return $sessao;
    }

    /**
     * Find open session
     * @param  boolean $required when true and none sesstion open found throw an exception
     * @return Sessao A filled instance or empty when not found
     */
    public static function findLastAberta($required = false)
    {
        $sessao = self::find([], ['aberta' => 1, 'id' => -1]);
        if ($required && !$sessao->exists()) {
            throw new \Exception('Nenhum caixa aberto ou fechado');
        }
        return $sessao;
    }
}
