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
namespace MZ\Product;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Informa tamanhos de pizzas e opções de peso do produto
 */
class Propriedade extends SyncModel
{

    /**
     * Identificador da propriedade
     */
    private $id;
    /**
     * Grupo que possui essa propriedade como item de um pacote
     */
    private $grupo_id;
    /**
     * Nome da propriedade, Ex.: Grande, Pequena
     */
    private $nome;
    /**
     * Abreviação do nome da propriedade, Ex.: G para Grande, P para Pequena,
     * essa abreviação fará parte do nome do produto
     */
    private $abreviacao;
    /**
     * Imagem que representa a propriedade
     */
    private $imagem_url;
    /**
     * Data de atualização dos dados ou da imagem da propriedade
     */
    private $data_atualizacao;

    /**
     * Constructor for a new empty instance of Propriedade
     * @param array $propriedade All field and values to fill the instance
     */
    public function __construct($propriedade = [])
    {
        parent::__construct($propriedade);
    }

    /**
     * Identificador da propriedade
     * @return int id of Propriedade
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Propriedade
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Grupo que possui essa propriedade como item de um pacote
     * @return int grupo of Propriedade
     */
    public function getGrupoID()
    {
        return $this->grupo_id;
    }

    /**
     * Set GrupoID value to new on param
     * @param int $grupo_id Set grupo for Propriedade
     * @return self Self instance
     */
    public function setGrupoID($grupo_id)
    {
        $this->grupo_id = $grupo_id;
        return $this;
    }

    /**
     * Nome da propriedade, Ex.: Grande, Pequena
     * @return string nome of Propriedade
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param string $nome Set nome for Propriedade
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Abreviação do nome da propriedade, Ex.: G para Grande, P para Pequena,
     * essa abreviação fará parte do nome do produto
     * @return string abreviação of Propriedade
     */
    public function getAbreviacao()
    {
        return $this->abreviacao;
    }

    /**
     * Set Abreviacao value to new on param
     * @param string $abreviacao Set abreviação for Propriedade
     * @return self Self instance
     */
    public function setAbreviacao($abreviacao)
    {
        $this->abreviacao = $abreviacao;
        return $this;
    }

    /**
     * Imagem que representa a propriedade
     * @return string imagem of Propriedade
     */
    public function getImagemURL()
    {
        return $this->imagem_url;
    }

    /**
     * Set ImagemURL value to new on param
     * @param string $imagem_url Set imagem for Propriedade
     * @return self Self instance
     */
    public function setImagemURL($imagem_url)
    {
        $this->imagem_url = $imagem_url;
        return $this;
    }

    /**
     * Data de atualização dos dados ou da imagem da propriedade
     * @return string data de atualização of Propriedade
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
    }

    /**
     * Set DataAtualizacao value to new on param
     * @param string $data_atualizacao Set data de atualização for Propriedade
     * @return self Self instance
     */
    public function setDataAtualizacao($data_atualizacao)
    {
        $this->data_atualizacao = $data_atualizacao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $propriedade = parent::toArray($recursive);
        $propriedade['id'] = $this->getID();
        $propriedade['grupoid'] = $this->getGrupoID();
        $propriedade['nome'] = $this->getNome();
        $propriedade['abreviacao'] = $this->getAbreviacao();
        $propriedade['imagemurl'] = $this->getImagemURL();
        $propriedade['dataatualizacao'] = $this->getDataAtualizacao();
        return $propriedade;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $propriedade Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($propriedade = [])
    {
        if ($propriedade instanceof self) {
            $propriedade = $propriedade->toArray();
        } elseif (!is_array($propriedade)) {
            $propriedade = [];
        }
        parent::fromArray($propriedade);
        if (!isset($propriedade['id'])) {
            $this->setID(null);
        } else {
            $this->setID($propriedade['id']);
        }
        if (!isset($propriedade['grupoid'])) {
            $this->setGrupoID(null);
        } else {
            $this->setGrupoID($propriedade['grupoid']);
        }
        if (!isset($propriedade['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($propriedade['nome']);
        }
        if (!array_key_exists('abreviacao', $propriedade)) {
            $this->setAbreviacao(null);
        } else {
            $this->setAbreviacao($propriedade['abreviacao']);
        }
        if (!array_key_exists('imagemurl', $propriedade)) {
            $this->setImagemURL(null);
        } else {
            $this->setImagemURL($propriedade['imagemurl']);
        }
        if (!isset($propriedade['dataatualizacao'])) {
            $this->setDataAtualizacao(DB::now());
        } else {
            $this->setDataAtualizacao($propriedade['dataatualizacao']);
        }
        return $this;
    }

    /* Obtém a descrição da propriedade abreviada */
    public function getAbreviado()
    {
        if ($this->getAbreviacao() === null) {
            return $this->getNome();
        }
        return $this->getAbreviacao();
    }

    /**
     * Get relative imagem path or default imagem
     * @param boolean $default If true return default image, otherwise check field
     * @param string  $default_name Default image name
     * @return string relative web path for propriedade imagem
     */
    public function makeImagemURL($default = false, $default_name = 'propriedade.png')
    {
        $imagem_url = $this->getImagemURL();
        if ($default) {
            $imagem_url = null;
        }
        return get_image_url($imagem_url, 'propriedade', $default_name);
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $propriedade = parent::publish($requester);
        $propriedade['imagemurl'] = $this->makeImagemURL(false, null);
        return $propriedade;
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
        $this->setGrupoID(Filter::number($this->getGrupoID()));
        $this->setNome(Filter::string($this->getNome()));
        $this->setAbreviacao(Filter::string($this->getAbreviacao()));
        $imagem_url = upload_image('raw_imagemurl', 'propriedade', null, 256, 256, false, 'crop');
        if (is_null($imagem_url) && trim($this->getImagemURL()) != '') {
            $this->setImagemURL($original->getImagemURL());
        } else {
            $this->setImagemURL($imagem_url);
        }
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
        if (!is_null($this->getImagemURL()) && $dependency->getImagemURL() != $this->getImagemURL()) {
            @unlink(get_image_path($this->getImagemURL(), 'propriedade'));
        }
        $this->setImagemURL($dependency->getImagemURL());
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Propriedade in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getGrupoID())) {
            $errors['grupoid'] = _t('propriedade.grupo_id_cannot_empty');
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = _t('propriedade.nome_cannot_empty');
        }
        $this->setDataAtualizacao(DB::now());
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
        if (contains(['GrupoID', 'Nome', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'grupoid' => _t(
                    'propriedade.grupo_id_used',
                    $this->getGrupoID()
                ),
                'nome' => _t(
                    'propriedade.nome_used',
                    $this->getNome()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Load into this object from database using, GrupoID, Nome
     * @return self Self filled instance or empty when not found
     */
    public function loadByGrupoIDNome()
    {
        return $this->load([
            'grupoid' => intval($this->getGrupoID()),
            'nome' => strval($this->getNome()),
        ]);
    }

    /**
     * Grupo que possui essa propriedade como item de um pacote
     * @return \MZ\Product\Grupo The object fetched from database
     */
    public function findGrupoID()
    {
        return \MZ\Product\Grupo::findByID($this->getGrupoID());
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
            $field = 'p.nome LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'p.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Propriedades p');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('p.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, GrupoID, Nome
     * @param int $grupo_id grupo to find Propriedade
     * @param string $nome nome to find Propriedade
     * @return self A filled instance or empty when not found
     */
    public static function findByGrupoIDNome($grupo_id, $nome)
    {
        $result = new self();
        $result->setGrupoID($grupo_id);
        $result->setNome($nome);
        return $result->loadByGrupoIDNome();
    }
}
