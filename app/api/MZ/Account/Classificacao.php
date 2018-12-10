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
namespace MZ\Account;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Classificação se contas, permite atribuir um grupo de contas
 */
class Classificacao extends SyncModel
{

    /**
     * Identificador da classificação
     */
    private $id;
    /**
     * Classificação superior, quando informado, esta classificação será uma
     * subclassificação
     */
    private $classificacao_id;
    /**
     * Descrição da classificação
     */
    private $descricao;
    /**
     * Ícone da categoria da conta
     */
    private $icone_url;

    /**
     * Constructor for a new empty instance of Classificacao
     * @param array $classificacao All field and values to fill the instance
     */
    public function __construct($classificacao = [])
    {
        parent::__construct($classificacao);
    }

    /**
     * Identificador da classificação
     * @return int id of Classificação
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Classificação
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Classificação superior, quando informado, esta classificação será uma
     * subclassificação
     * @return int classificação superior of Classificação
     */
    public function getClassificacaoID()
    {
        return $this->classificacao_id;
    }

    /**
     * Set ClassificacaoID value to new on param
     * @param int $classificacao_id Set classificação superior for Classificação
     * @return self Self instance
     */
    public function setClassificacaoID($classificacao_id)
    {
        $this->classificacao_id = $classificacao_id;
        return $this;
    }

    /**
     * Descrição da classificação
     * @return string descrição of Classificação
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Classificação
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Ícone da categoria da conta
     * @return string ícone of Classificação
     */
    public function getIconeURL()
    {
        return $this->icone_url;
    }

    /**
     * Set IconeURL value to new on param
     * @param string $icone_url Set ícone for Classificação
     * @return self Self instance
     */
    public function setIconeURL($icone_url)
    {
        $this->icone_url = $icone_url;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $classificacao = parent::toArray($recursive);
        $classificacao['id'] = $this->getID();
        $classificacao['classificacaoid'] = $this->getClassificacaoID();
        $classificacao['descricao'] = $this->getDescricao();
        $classificacao['iconeurl'] = $this->getIconeURL();
        return $classificacao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $classificacao Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($classificacao = [])
    {
        if ($classificacao instanceof self) {
            $classificacao = $classificacao->toArray();
        } elseif (!is_array($classificacao)) {
            $classificacao = [];
        }
        parent::fromArray($classificacao);
        if (!isset($classificacao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($classificacao['id']);
        }
        if (!array_key_exists('classificacaoid', $classificacao)) {
            $this->setClassificacaoID(null);
        } else {
            $this->setClassificacaoID($classificacao['classificacaoid']);
        }
        if (!isset($classificacao['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($classificacao['descricao']);
        }
        if (!array_key_exists('iconeurl', $classificacao)) {
            $this->setIconeURL(null);
        } else {
            $this->setIconeURL($classificacao['iconeurl']);
        }
        return $this;
    }

    /**
     * Get relative ícone path or default ícone
     * @param boolean $default If true return default image, otherwise check field
     * @param string  $default_name Default image name
     * @return string relative web path for classificação ícone
     */
    public function makeIconeURL($default = false, $default_name = 'classificacao.png')
    {
        $icone_url = $this->getIconeURL();
        if ($default) {
            $icone_url = null;
        }
        return get_image_url($icone_url, 'classificacao', $default_name);
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $classificacao = parent::publish($requester);
        $classificacao['iconeurl'] = $this->makeIconeURL(false, null);
        return $classificacao;
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
        $this->setClassificacaoID(Filter::number($this->getClassificacaoID()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $icone_url = upload_image('raw_iconeurl', 'classificacao');
        if (is_null($icone_url) && trim($this->getIconeURL()) != '') {
            $this->setIconeURL($original->getIconeURL());
        } else {
            $this->setIconeURL($icone_url);
        }
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
        if (!is_null($this->getIconeURL()) && $dependency->getIconeURL() != $this->getIconeURL()) {
            @unlink(get_image_path($this->getIconeURL(), 'classificacao'));
        }
        $this->setIconeURL($dependency->getIconeURL());
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Classificacao in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = _t('classificacao.descricao_cannot_empty');
        }
        $superior = $this->findClassificacaoID();
        if ($superior->exists() && !is_null($superior->getClassificacaoID())) {
            $errors['descricao'] = 'Essa classificação superior não pode ser atribuída';
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
        if (contains(['Descricao', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'descricao' => _t(
                    'classificacao.descricao_used',
                    $this->getDescricao()
                ),
            ]);
        }
        return parent::translate($e);
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
     * Classificação superior, quando informado, esta classificação será uma
     * subclassificação
     * @return \MZ\Account\Classificacao The object fetched from database
     */
    public function findClassificacaoID()
    {
        if (is_null($this->getClassificacaoID())) {
            return new \MZ\Account\Classificacao();
        }
        return \MZ\Account\Classificacao::findByID($this->getClassificacaoID());
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
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Classificacoes c');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('c.descricao ASC');
        $query = $query->orderBy('c.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, Descricao
     * @param string $descricao descrição to find Classificação
     * @return self A filled instance or empty when not found
     */
    public static function findByDescricao($descricao)
    {
        $result = new self();
        $result->setDescricao($descricao);
        return $result->loadByDescricao();
    }
}
